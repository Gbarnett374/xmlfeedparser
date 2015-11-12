<?php 
//PHP 5.5.29
class feedParser
{
	protected $url;
	protected $filter;
	protected $filteredXML = [];
	protected $index;
	protected $delimiter;
/**
 * @param [array] $params contains the params to be set. 
 */
	public function __construct($params)
	{
		foreach ($params as $k => $v) {
			if (!isset($params[$k]) || $v == "") {
				throw new Exception("{$k} is undefined.");
			} else {
				$this->$k = $v;
			}
		}
	}	
/**
 * filterFeed Reads the XML from the url and filters it based on the provided filter. The results are organized into an array keyed by the unqiue values of the filter.
 */
	function filterFeed()
	{
		$reader = new XMLReader();
		if (!$reader->open($this->url)) {
			throw new Exception("Cannot open feed from the provided URL.");
		}

		while ($reader->read()) {
			if ($reader->name == "product") {
				//get the entire product node. 
				$xml_node = $reader->readOuterXML();
			}

			if ($reader->name == $this->filter) {
				//read the values for the $this->filter node.
				$reader->read();

				//get string/value from the node we are filtering and explode it by a delimiter.
				$nodeValues = [];
				$nodeValues = explode($this->delimiter, $reader->value);

				if (!empty($nodeValues[$this->index])) {
					$this->filteredXML[$nodeValues[$this->index]][] = $xml_node;
				} else {
					throw new Exception("The index specified does not exist.");
				}

				//Go to the next product.
				$reader->next("product");
			}
		}
		//if the array has no items then the filtered node does not exist. 
		if (empty($this->filteredXML)) {
			throw new Exception("{$this->filter} does not exist in the XML.");
		}
	}
/**
 * Formats and writes the XML, & jSON files.
 * @param  [string] $directory - The directroy to write the files.
 */
	function write($directory)
	{
		if (!mkdir($directory)) {
			throw new Exception("Cannot create directory.");
		}

		foreach ($this->filteredXML as $k => $v) {
			$xml = "<products>\n\t";
			$xml .= implode("\n\t", $v);
			$xml .= "\n</products>";
		
			//Parse filter/categorys for file names. 
			//Replace any splaces or slashes. 
			$base_file_name = strtolower(str_replace(array(" ", "/"), "", trim($k)));
			$path = $directory . "/{$base_file_name}";

			//Write the XML file.
			$xml_file = fopen("{$path}.xml", "a"); 
			fwrite($xml_file, $xml);
			fclose($xml_file);

			//Convert to jSON. 
			$xml  = simplexml_load_string($xml);
			$json = json_encode($xml, JSON_PRETTY_PRINT);
			
			//Write the jSON file.
			$json_file = fopen("{$path}.json", "a");
			fwrite($json_file, $json); 
			fclose($json_file);
		}	
	}
}//end class 

/********************************Run Script******************************************************************************************/

$url = '';
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
	"index"	    => $index,
	"delimiter" => ">"
];

try{
	$parser = new feedParser($params);
	$parser->filterFeed();
	$parser->write($directory);
	
} catch (Exception $e) {
	echo $e->getMessage();
}