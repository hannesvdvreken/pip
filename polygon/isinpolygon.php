<?php

/*
* Author: Hannes Van De Vreken
* Params:
* $coords should be an array, made out of associative arrays. For example: [{"lat":"3.5311423525","lon":"52.2342525"},{...}]
*         all coordinates should be ordered, in order of appearance, based on the polygon that is descibed by those points
* $lon = longitude of point, to be decided wether it's located in the polygon, defined by $coords
* $lat = latitude of point, to be decided wether it's located in the polygon, defined by $coords
*/

function is_located_in_polygon( $coords, $lon, $lat ){
	
	$num = count( $coords );
	//duplicate last coordinates for creating a circular list
	$coords[$num] = $coords[0] ;
	//fyi: circular list is only gone through once, after this, so no need for pointers etc.
	
	//number of edges, 
	$numleft = 0 ;
	$numright = 0 ;
	
	for( $i = 0 ; $i < $num ; $i++ ){
		$x1 = $coords[$i]["lon"] ;
		$x2 = $coords[$i+1]["lon"] ;
		$y1 = $coords[$i]["lat"] ;
		$y2 = $coords[$i+1]["lat"] ;
		if( max( $x2 , $x1 ) > $lon && min( $x2, $x1 ) < $lon ){
			//next if-structure: 	needed to change edge's direction
			//                   	especially needed for next if-structure, 
                        //			to decide position of point (lon,lat) according to edge
			if( $x2 > $x1 ){
				$x3 = $x2 ;
				$x2 = $x1 ;
				$x1 = $x3 ;
				$y3 = $y2 ;
				$y2 = $y1 ;
				$y1 = $y3 ;
			}
			// is edge located on the west/east (left/right) side of the point? Edges are always running from bottom to top
			// (switched in prev. if-structure)
			if( ($x2 - $x1)*($lat-$y1) - ($y2 - $y1)*($lon - $x1) < 0 ){
				$numleft++;
			}else{
				$numright++;
			}
		}
	}

	//It's definitely not inside the polygon if none of the edges has x coordinates which are 
        //respectively above the points x coordinate, respectively below...
	//human: af all vertexes (points of polygon) are located above or below point (lon,lat)
	//and the point is inside the polygon, if it has an even index, as descibed by
        //     http://en.wikipedia.org/wiki/Point_in_polygon
	
	return !($numleft == 0 || $numright == 0) && abs($numleft - $numright) % 2 == 0 ;
	
}

//if not clear, ticket on github.
?>
