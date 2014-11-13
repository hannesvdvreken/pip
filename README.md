# Polygon tools

[![Build Status](https://travis-ci.org/hannesvdvreken/pip.png?branch=master)](https://travis-ci.org/hannesvdvreken/pip)

A [point-in-polygon algorithm](http://en.wikipedia.org/wiki/Point_in_polygon), centroid and orientation calculation, written in PHP.
Figure out (pun) if a point is actually inside a convex or concave polygon.

## Usage

Initiate a Polygon object with coordinates as specified in [the GeoJSON Format](http://www.geojson.org/geojson-spec.html#polygon).
An array of latitude, longitude pairs.

```php
$coords = [
    [51.046945330263, 3.7388005491447],
    [50.884119340619, 4.7054353759129],
    [51.260385196697, 4.3696193284848],
];

$poly = new \Geometry\Polygon($coords);

$coords = $poly->getOutline();
$poly->setOutline($coords);

$centroid = $poly->centroid();
echo "the centroid of the polygon is ($centroid[0], $centroid[1]).\n";

if ($poly->isValid()) {
    echo "the polygon was succesfully created.\n";
}

if (!$poly->isClockwise()) {
    echo "the points define a counter-clockwise polygon.\n";
}

if ($poly->pip($x, $y)) {
    echo "the polygon includes ($x,$y).\n";
}
```

## Origin

Written during Apps For Ghent, 2012.

## License

[MIT](license)