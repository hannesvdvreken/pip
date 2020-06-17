<?php

namespace Geometry;

class Polygon
{
    /**
     * @var array
     */
    protected $coordinates = [];

    /**
     * @var bool
     */
    protected $valid;

    /**
     * @param array $coordinates
     */
    public function __construct(array $coordinates = null)
    {
        if ($coordinates) {
            $this->setOutline($coordinates);
        } else {
            $this->valid = false;
        }
    }

    /**
     * @param array $coordinates
     *
     * @return $this
     */
    public function setOutline(array $coordinates)
    {
        $points = [];
        $this->valid = true;

        // Check if it has at least 3 points.
        if (count($coordinates) >= 3) {
            // Check format
            foreach ($coordinates as $point) {
                if ($this->validPoint($point)) {
                    $points[] = $point;
                } else {
                    $this->valid = false;
                    return $this;
                }
            }
        } else {
            $this->valid = false;
            return $this;
        }

        // Make sure the last one is the same as the first one.
        if ($this->firstAndLastEqual($points)) {
            $points[] = reset($points);
        }

        // Assign.
        $this->coordinates = $points;
    }

    /**
     * @return array
     */
    public function getOutline()
    {
        return $this->coordinates;
    }

    /**
     * @return bool
     */
    public function isValid()
    {
        return $this->valid;
    }

    /**
     * @param double $latitude
     * @param double $longitude
     *
     * @return bool
     */
    public function pip($latitude, $longitude)
    {
        // init
        $left = 0;
        $right = 0;
        $previous = reset($this->coordinates);

        // calculate
        while ($point = next($this->coordinates)) {
            $x1 = $previous[0];
            $y1 = $previous[1];

            $x2 = $point[0];
            $y2 = $point[1];

            // If the edge is on the same height.
            if (max($x2, $x1) >= $latitude && min($x2, $x1) < $latitude) {
                // Change edge's direction if needed.
                if ($x2 > $x1) {
                    list($x1, $y1, $x2, $y2) = [$x2, $y2, $x1, $y1];
                }

                // Check position relative to the edge.
                $temp = ($x2 - $x1) * ($longitude - $y1) - ($y2 - $y1) * ($latitude - $x1);
                $left += ($temp < 0) ? 1 : 0;
            }

            // Shift.
            $previous = $point;
        }

        // Return
        return $left % 2 === 1;
    }

    /**
     * @return bool
     */
    public function isClockwise()
    {
        // Init
        $sum = 0;

        // Get the first.
        $previous = reset($this->coordinates);

        // Loop.
        while ($point = next($this->coordinates)) {
            $sum += ($point[0] - $previous[0]) * ($point[1] + $previous[1]);

            // Shift
            $previous = $point;
        }

        // Reset pointer.
        reset($this->coordinates);

        // Return.
        return $sum >= 0;
    }

    /**
     * @return array
     */
    public function centroid()
    {
        // Init
        $cx = 0;
        $cy = 0;
        $a  = 0;

        // First point.
        $previous = reset($this->coordinates);

        // Loop all points.
        while ($point = next($this->coordinates)) {
            $temp = ($previous[0] * $point[1]) - ($point[0] * $previous[1]);
            $cx += (($previous[0] + $point[0]) * $temp);
            $cy += (($previous[1] + $point[1]) * $temp);
            $a += $temp * 3;

            // Shift
            $previous = $point;
        }

        // Reset the pointer.
        reset($this->coordinates);

        // Return the centroid.
        return [$cx / $a, $cy / $a];
    }

    /**
     * @param $point
     *
     * @return bool
     */
    protected function validPoint($point)
    {
        return is_array($point) &&
            count($point) == 2 &&
            is_numeric($point[0]) &&
            is_numeric($point[1]);
    }

    /**
     * @param array $points
     *
     * @return bool
     */
    protected function firstAndLastEqual(array $points)
    {
        // Get first and last.
        $first = reset($points);
        $last = end($points);

        // Return if the first and the last are the same.
        return $first[0] !== $last[0] || $first[1] !== $last[1];
    }
}
