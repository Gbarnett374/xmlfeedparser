<?php 

require './feedParserClass.php';

$url = './products.xml';
$filter = "category";
/*Index is the postion of the element you want to filter. 
If the elements in the node are not separated by a delimiter then use 0. 
If the specified index does not exist an error will be thrown.
*/
$index = 1;
$delimiter = ">";
$directory = "/tmp/feed"; 

$params = [
    "url"       => $url,
    "filter"    => $filter,
    "index"     => $index,
    "delimiter" => ">"
];

try {
    $parser = new feedParser($params);
    $parser->filterFeed();
    $parser->write($directory);
    
} catch (Exception $e) {
    echo $e->getMessage();
}