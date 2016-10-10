<?php
  /**
   * Returns a list of all the dois from the text file.
   * @return     array list of dois
   */
define("CROSSREF", "http://api.crossref.org/works/");
define("DOCUMENTS", "documents/");

getdois();

function getdois() {
  $filename = 'dois.txt';
  $contents = file($filename);
  mkdir('documents', 0755, true);
  $indexArray = Array();
  $i = 0;
  foreach($contents as $line) {
	  $line = trim($line);
	  $doiData = generateDetails($line, $i);
	  if($doiData) {
	  	$doiArray = Array("doiFile"=> $line, "publisher"=>$doiData["publisher"], "title"=>$doiData["title"], "url"=> "http://dx.doi.org/". $line, "id"=> $i, "filename"=>$doiData['filename']);
	  	$indexArray[$i] = $doiArray;
		  $jsonDoi = json_encode(array('index'=> $indexArray));
	  }
	  $i++;
  }
  file_put_contents("index.json", $jsonDoi) or die("Unable to write file");	
  return true;
}
 /**
  * Creates a new json document using the information provided by CrossRef
  *
  * @param      string $url the url of the DOI
  *
  * @return     array  Returns the publisher's name and the paper name
  */
function generateDetails($url, $id) {
	
	$curl = curl_init();
	$doi = CROSSREF . trim($url);

	curl_setopt($curl, CURLOPT_URL, $doi);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

		$doiDetails = curl_exec($curl);
		if($doiDetails === false) {
			echo "Curl Error: " . curl_error($curl);
			curl_close($curl);
			return false;
		} else {
			$doiString = json_decode($doiDetails, true);
			$publisher = trim($doiString["message"]["publisher"]);
			$paperTitle = strip_tags(trim($doiString["message"]["title"][0]));
			$regexTitle = preg_split("^[a-zA-Z0-9]{4,32}$^", $paperTitle);
			$filename = saveJsonToFile($doiDetails, $regexTitle[0]);		
			$doiReturn = array("publisher" => $publisher, "title" => $paperTitle, "filename"=> $filename);
		}

	curl_close($curl);
	return $doiReturn;
}

function saveJsonToFile($json, $filename) {
	if($filename) {
	$filename = str_replace("/", "", $filename);
	$jsonFile = DOCUMENTS . trim($filename) . ".json";
	
	file_put_contents($jsonFile, $json);
		return $jsonFile;	
	}
	else 
		return false;
}

?>
<div>The DOI data has been generated.
</div><br />
<a href="index.php">Go back to the index page</a>