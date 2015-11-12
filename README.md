# xmlfeedparser
Tested using PHP 5.5.29

PHP CLI Program. Takes an XML file and parses it into separate XML & jSON files based on a specified filter. 

The default filter is the 2nd item in the catagory tag which is separated by a > delimiter. Please note the program uses 0 based indexes, which means Food Cupboard will have an index of 1. 
Example from products.xml:
<category>Groceries &gt; <strong>Food Cupboard</strong> &gt; Tinned &amp; Packet Goods &gt; Fruit &amp; Desserts &gt; Tinned Fruit</category>

All the XML product nodes with a category that contains Food Cupboard will be writen to a foodcupboard.json & a foodcupboard.xml

The program allows you to set the filter, index, and delimiter parameters. 

If the tag that you are filtering by contains more then one item separated by a delimiter, then specifiy the delimiter, and the position of the item you want to filter by using a 0 based index.

Otherwise you can leave the default delimiter and set the index to 0 to take the first item in the tag. 

To run program:
php feedParser_controller.php 

Program will create a a feed folder inside /tmp which will contain the xml & jSON files. 
