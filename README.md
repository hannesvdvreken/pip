pip
===

A point in polygon algorithm, written in php. Practical for finding out if a point is actually inside a convex or concave polygon. 

Usage
-----

Initiate a Polygon object with coordinates as specified in [the GeoJSON Format](http://www.geojson.org/geojson-spec.html#polygon)

    $poly = new \Geometry\Polygon(array $coords);

    if ( $poly->pip($x, $y) )
    {
        echo "the polygon includes ($x,$y)";
    }

Origin
------

Written during Apps For Ghent, 2012.

License
-------

MIT
