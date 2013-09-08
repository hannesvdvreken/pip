<?php
namespace Geometry;

class Polygon {

	/**
	 * The datapoints that define the polygon.
	 *
	 * @var array
	 */
	protected $coordinates;

	/**
	 * are the datapoints valid?
	 *
	 * @var boolean
	 */
	protected $valid;

	/**
	 * Create a new Polygon instance.
	 *
	 * @param array $coordinates
	 * @return void
	 */
	public function __construct(array $coordinates = null)
	{
		if ($coordinates)
		{
			$this->set_outline($coordinates);
		}
		else
		{
			$this->valid = false;
		}
	}

	/**
	 * Re-use an instance and reset its outline points.
	 *
	 * @param array $coordinates
	 * @return boolean
	 */
	public function set_outline($coordinates)
	{
		$points = array();
		$this->valid = true;

		// check
		if (is_array($coordinates) && // must be array
			count($coordinates) > 2 ) // at least 3 points
		{
			// check form
			foreach ($coordinates as $point) 
			{
				if (is_array($point) &&
					count($point) == 2 &&
					is_numeric($point[0]) &&
					is_numeric($point[1]))
				{
					$points[] = $point;
				}
				else
				{
					$this->coordinates = array();
					$this->valid = false;
					return false;
				}
			}
		}
		else
		{
			$this->coordinates = array();
			$this->valid = false;
			return false;
		}

		$first = reset($points);
		$last = end($points);

		if ($first[0] != $last[0] ||
			$first[1] != $last[1])
		{
			$points[] = $first;
		}

		// assign
		$this->coordinates = $points;
		return true;
	}

	/**
	 * Return the datapoints.
	 *
	 * @return array
	 */
	public function get_outline()
	{
		return $this->coordinates;
	}

	/**
	 * Did the latest datapoints define a proper Polygon?
	 *
	 * @return boolean
	 */
	public function is_valid()
	{
		return $this->valid;
	}

	/**
	 * Figure out if a given datapoint is inside the Polygon's outline.
	 *
	 * @param numeric $x
	 * @param numeric $y
	 * @return boolean
	 */
	public function pip($x, $y)
	{
		// init
		$left = 0 ;
		$right = 0 ;
		$previous = null;

		// calculate
		foreach ($this->coordinates as &$point) 
		{
			if ($previous)
			{
				$x1 = $previous[0]; $y1 = $previous[1];
				$x2 = $point[0];    $y2 = $point[1];

				if( max( $x2 , $x1 ) > $x && min( $x2, $x1 ) < $y )
				{
					// change edge's direction
					if( $x2 > $x1 )
					{
						list($x2, $x1) = array($x1, $x2);
						list($y2, $y1) = array($y1, $y2);
					}

					// check position
					$temp = ($x2 - $x1) * ($x - $y1) - ($y2 - $y1) * ($y - $x1);
					$left += ($temp <  0);
					$right+= ($temp >= 0);
				}
			}
			$previous = $point;
		}

		// return
		return $left * $right != 0 && abs($left - $right) % 2 == 0 ;
	}

	/**
	 * In what direction do the datapoints define the outline?
	 *
	 * @return boolean
	 */
	public function is_clockwise()
	{
		// init
		$sum = 0;
		$previous = null;

		// calculate
		foreach ($this->coordinates as &$point) 
		{
			if ($previous)
			{
				$sum += ($point[0] - $previous[0]) * ($point[1] + $previous[1]);
			}
			$previous = $point;
		}

		// return
		return $sum >= 0;
	}

	/**
	 * Calculate the Polygon's centroid.
	 * http://en.wikipedia.org/wiki/Centroid#Centroid_of_polygon
	 * 
	 * @return array
	 */
	public function centroid()
	{
		if ( ! $this->is_valid()) return false;

		// init
		$cx = 0;
		$cy = 0;
		$a  = 0;
		$previous = null;

		// calculate
		foreach ($this->coordinates as &$point) 
		{
			if ($previous)
			{
				$temp = ($previous[0] * $point[1]) - ($point[0] * $previous[1]);
				$cx += (($previous[0] + $point[0]) * $temp);
				$cy += (($previous[1] + $point[1]) * $temp);
				$a += $temp;
			}
			$previous = $point;
		}

		// return
		$a *= 3;
		return array($cx / $a, $cy / $a);
	}
}