<?php
$w3renc = array(
	'amber' => '#ffc107', 'amberT' => '#000',
	'aqua' => '#00ffff', 'aquaT' => '#000',
	'blue' => '#2196F3', 'blueT' => '#fff',
	'light-blue' => '#87CEEB', 'light-blueT' => '#000',
	'brown' => '#795548', 'brownT' => '#fff',
	'cyan' => '#00bcd4', 'cyanT' => '#000',
	'blue-grey' => '#607d8b', 'blue-greyT' => '#fff',
	'green' => '#4CAF50', 'greenT' => '#fff',
	'light-green' => '#8bc34a', 'light-greenT' => '#000',
	'indigo' => '#3f51b5', 'indigoT' => '#fff',
	'khaki' => '#f0e68c', 'khakiT' => '#000',
	'lime' => '#cddc39', 'limeT' => '#000',
	'orange' => '#ff9800', 'orangeT' => '#000',
	'deep-orange' => '#ff5722', 'deep-orangeT' => '#fff',
	'pink' => '#e91e63', 'pinkT' => '#fff',
	'purple' => '#9c27b0', 'purpleT' => '#fff',
	'deep-purple' => '#673ab7', 'deep-purpleT' => '#fff',
	'red' => '#f44336', 'redT' => '#fff',
	'sand' => '#fdf5e6', 'sandT' => '#000',
	'teal' => '#009688', 'tealT' => '#fff',
	'yellow' => '#ffeb3b', 'yellowT' => '#000',
	'white' => '#fff', 'whiteT' => '#000',
	'black' => '#000', 'blackT' => '#fff',
	'grey' => '#9e9e9e', 'greyT' => '#000',
	'light-grey' => '#f1f1f1', 'light-greyT' => '#000',
	'dark-grey' => '#616159', 'dark-greyT' => '#fff',
	'pale-red' => '#ffdddd', 'pale-redT' => '#000',
	'pale-green' => '#ddffdd', 'pale-greenT' => '#000',
	'pale-yellow' => '#ffffcc', 'pale-yellowT' => '#000',
	'pale-blue' => '#ddffff', 'pale-blueT' => '#000',
	'blue-grey-l5' => '#f5f7f8', 'blue-grey-l5T' => '#000',
	'blue-grey-l4' => '#dfe5e8', 'blue-grey-l4T' => '#000',
	'blue-grey-l3' => '#becbd2', 'blue-grey-l3T' => '#000',
	'blue-grey-l2' => '#9eb1bb', 'blue-grey-l2T' => '#000',
	'blue-grey-l1' => '#7d97a5', 'blue-grey-l1T' => '#fff',
	'blue-grey-d1' => '#57707d', 'blue-grey-d1T' => '#fff',
	'blue-grey-d2' => '#4d636f', 'blue-grey-d2T' => '#fff',
	'blue-grey-d3' => '#435761', 'blue-grey-d3T' => '#fff',
	'blue-grey-d4' => '#3a4b53', 'blue-grey-d4T' => '#fff',
	'blue-grey-d5' => '#303e45', 'blue-grey-d5T' => '#fff'
	);
if(has_filter('rencColor')) $w3renc = apply_filters('rencColor', $w3renc);
$w3rencDef = array(
	'mebg' => 'blue-grey-d2',
	'mebt' => 'blue-grey-d4',
	'mebw' => 'orange',
	'mebo' => 'blue-grey-l2',
	'mebc' => 'green',
	'blbg' => 'white',
	'titc' => 'black',
	'txtc' => 'black',
	'lblc' => 'black',
	'inlc' => 'green',
	'inlb' => '',
	'line' => 'dark-grey',
	'inbg' => 'light-grey',
	'sebg' => 'dark-grey',
	'wabg' => 'orange',
	'wmbg' => 'blue-grey-d2',
	'msbs' => 'pale-green',
	'msbr' => 'white'
	);
if(has_filter('rencDefColor')) $w3rencDef = apply_filters('rencDefColor', $w3rencDef);
//
// Other colors
$w3rencPlus = array(
	'highway-brown' => '#633517', 'highway-brownT' => '#fff',
	'highway-red' => '#a6001a', 'highway-redT' => '#fff',
	'highway-orange' => '#e06000', 'highway-orangeT' => '#fff',
	'highway-schoolbus' => '#ee9600', 'highway-schoolbusT' => '#fff',
	'highway-yellow' => '#ffab00', 'highway-yellowT' => '#fff',
	'highway-green' => '#004d33', 'highway-greenT' => '#fff',
	'highway-blue' => '#00477e', 'highway-blueT' => '#fff'
);
?>
