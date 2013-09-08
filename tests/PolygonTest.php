<?php
require_once __DIR__.'/../vendor/autoload.php';
use Geometry\Polygon;

class PolygonTest extends PHPUnit_Framework_TestCase {

	public function testCreation()
	{
		// points
		$points = array(
			array(1, 3),
			array(1.5, 3.5),
			array(1.5, 2.5),
		);

		// not valid
		$p = new Polygon();
		$this->assertFalse($p->is_valid());

		// use outline function
		$p->set_outline($points);
		$this->assertTrue($p->is_valid());

		// use constructor
		$p = new Polygon($points);
		$this->assertTrue($p->is_valid());

		// outline has been autocorrected
		$points[] = $points[0];
		$this->assertEquals($p->get_outline(), $points);

		// outline has been unchanged
		$p = new Polygon($points);
		$this->assertEquals($p->get_outline(), $points);
	}

	public function testInputValidation()
	{
		// points
		$points = array(
			array(1, 3),
		);

		// not enough points
		$p = new Polygon($points);
		$this->assertFalse($p->is_valid());
		$this->assertEquals($p->get_outline(), array());

		// wrong form
		$points = array(
			array(1, 3, 5),
			array(3, 3),
		);
		$p->set_outline($points);
		$this->assertFalse($p->is_valid());

		// add points
		$points[] = array(1.5, 3.5);
		$points[] = array(1.5, 2.5);
		$p = new Polygon($points);
	}

	public function testPip()
	{
		// arrange
		$points = array(
			array(-1, -1),
			array(-1,  1),
			array( 1,  1),
			array( 1, -1),
		);

		// act
		$p = new Polygon($points);

		// assert
		$this->assertTrue($p->pip(0, 0));
		$this->assertTrue($p->pip(0.99, -0.99));
		$this->assertFalse($p->pip(1.01, 0));
	}

	public function testClockwise()
	{
		// points
		$points = array(
			array(-1, -1),
			array(-1,  1),
			array( 1,  1),
			array( 1, -1),
		);

		// clockwise
		$p = new Polygon($points);
		$this->assertTrue($p->is_clockwise());

		// counter-clockwise
		$p = new Polygon(array_reverse($points));
		$this->assertFalse($p->is_clockwise());
	}

	public function testCentroid()
	{
		// arrange
		$points_1 = array(
			array(-1, -1),
			array(-1,  1),
			array( 1,  1),
			array( 1, -1),
		);
		$points_2 = array(
			array(-1, -1),
			array(-1,  0), // does not change the shape
			array(-1,  1),
			array( 1,  1),
			array( 1, -1),
		);

		// act
		$p_1 = new Polygon($points_1);
		$p_2 = new Polygon($points_2);

		// assert
		$this->assertEquals($p_1->centroid(), array(0,0));
		$this->assertEquals($p_2->centroid(), array(0,0));
	}
}