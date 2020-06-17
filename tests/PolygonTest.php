<?php

use Geometry\Polygon;
use PHPUnit\Framework\TestCase;

class PolygonTest extends TestCase
{
    public function testCreation()
    {
        // points
        $points = [
            [1, 3],
            [1.5, 3.5],
            [1.5, 2.5],
        ];

        // not valid
        $p = new Polygon();
        $this->assertFalse($p->isValid());

        // use outline method
        $p->setOutline($points);
        $this->assertTrue($p->isValid());

        // use constructor
        $p = new Polygon($points);
        $this->assertTrue($p->isValid());

        // outline has been autocorrected
        $points[] = $points[0];
        $this->assertEquals($p->getOutline(), $points);

        // outline has been unchanged
        $p = new Polygon($points);
        $this->assertEquals($p->getOutline(), $points);
    }

    public function testInputValidation()
    {
        // points
        $points = [[1, 3]];

        // not enough points
        $p = new Polygon($points);
        $this->assertFalse($p->isValid());
        $this->assertEquals($p->getOutline(), []);

        // wrong form
        $points = [
            [1, 3, 5],
            [3, 3],
        ];
        $p->setOutline($points);
        $this->assertFalse($p->isValid());

        // add points
        $points[] = [1.5, 3.5];
        $points[] = [1.5, 2.5];
        $p = new Polygon($points);
        $this->assertFalse($p->isValid());
        $this->assertEquals($p->getOutline(), []);
    }

    public function testPip()
    {
        // arrange
        $points = [
            [-1, -1],
            [-1,  1],
            [ 1,  1],
            [ 1, -1],
        ];

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
        $points = [
            [-1, -1],
            [-1,  1],
            [ 1,  1],
            [ 1, -1],
        ];

        // clockwise
        $p = new Polygon($points);
        $this->assertTrue($p->isClockwise());

        // counter-clockwise
        $p = new Polygon(array_reverse($points));
        $this->assertFalse($p->isClockwise());
    }

    public function testCentroid()
    {
        // arrange
        $points_1 = [
            [-1, -1],
            [-1,  1],
            [ 1,  1],
            [ 1, -1],
        ];
        $points_2 = [
            [-1, -1],
            [-1,  0], // extra point does not change the shape
            [-1,  1],
            [ 1,  1],
            [ 1, -1],
        ];

        // act
        $p_1 = new Polygon($points_1);
        $p_2 = new Polygon($points_2);

        // assert
        $this->assertEquals($p_1->centroid(), [0,0]);
        $this->assertEquals($p_2->centroid(), [0,0]);
    }
}
