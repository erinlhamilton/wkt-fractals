<?php

/* Creates a Well-Known Text version of a fractal tree. 
 * Tree fractal code adapted from Rosetta Code (http://rosettacode.org/wiki/Fractal_tree) 
 * which is licensed under GNU Free Documentation Licencse 1.2. Well-Known Text created 
 * using the geoPHP.inc library https://github.com/phayes/geoPHP/wiki) by Patrick Hayes 
 * which is licensed under both GPL version 2 (or later) and Modified BSD License.
 * By: Erin Hamilton
 * September 30, 2013
*/
//Change the memory allocated to php
ini_set('memory_limit','512M');

//must use the geoPHP library
include_once('geoPHP.inc'); 

$wkt_writer = new wkt();
$lineArray = array();
$width = 512;
$height = 512;
$depth = 8;

/* Recursive function that creates a new line each time it is called.
 * Pushes line to lineArray variable. The number of times it is called 
 * is based on the depth variable.
 */ 
function drawTree($x1, $y1, $angle, $depth){
	global $lineArray;
    if ($depth != 0){
        $x2 = $x1 + (int)(cos(deg2rad($angle)) * $depth * 10.0);
        $y2 = $y1 + (int)(sin(deg2rad($angle)) * $depth * 10.0);
		
		$lineArray[] = '('.$x1.' '.$y1.','.$x2.' '.$y2.')';
		
        drawTree($x2, $y2, $angle - 30, $depth - 1);
        drawTree($x2, $y2, $angle + 30, $depth - 1);
    }
}
 
drawTree($width/2, $height, -90, $depth);

$lines = implode(", ", $lineArray);
//creates a geometry collection of linestrings
$tree = geoPHP::load('MULTILINESTRING('.$lines.')', 'wkt');
//write WKT to a string
$output = $wkt_writer->write($tree);
file_put_contents('tree_fractal.csv', $output);