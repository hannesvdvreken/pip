Polygon tools
===

A point-in-polygon algorithm, centroid and orientation calculation, written in php. Figure out (pun) if a point is actually inside a convex or concave polygon.


Usage
-----

Initiate a Polygon object with coordinates as specified in [the GeoJSON Format](http://www.geojson.org/geojson-spec.html#polygon)

```PHP
use \Geometry\Polygon;

$poly = new Polygon($coords);

$coords = $poly->get_outline();
$poly->set_outline($coords);

$centroid = $poly->centroid();
echo "the centroid of the polygon is ($centroid[0], $centroid[1]).\n";

if ($poly->is_valid())
{
    echo "the polygon was succesfully created.\n";
}

if ( ! $poly->is_clockwise())
{
    echo "the points define a counter-clockwise polygon.\n";
}

if ($poly->pip($x, $y))
{
    echo "the polygon includes ($x,$y).\n";
}
```

Origin
------

Written during Apps For Ghent, 2012.

License
-------

MIT
