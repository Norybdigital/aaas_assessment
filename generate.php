<?php
  /**
   * Returns a list of all the dois from the text file.
   * @return     array list of dois
   */
define("CROSSREF", "http://api.crossref.org/works/");
define("DOCUMENTS", "documents/");

getdois();

//This function reads through the list of dois in doi.txt and generates an individual json file for each one.  It then generates an index.json file containing basic information on each doi.
function getdois() {

  $filename = 'dois.txt';
  $contents = file($filename);

  //create a document directory, just in case one doesn't exist.
  mkdir('documents', 0775, true);
  $indexArray = Array();
  $i = 0;
  $lastTitle = '';
  foreach($contents as $line) {
	  $line = trim($line);
	  $doiData = generateDetails($line, $i);

	  if($doiData && $doiData["title"] !== $lastTitle) {
	  	$doiArray = Array("doiFile"=> $line, "publisher"=>$doiData["publisher"], "title"=>$doiData["title"], "url"=> "http://dx.doi.org/". $line, "id"=> $i, "filename"=>$doiData["filename"]);
	  	$indexArray[$i] = $doiArray;
	  	$lastTitle = $doiData["title"];
	  }
	  

	  $i++;
  }
 
  foreach($indexArray as $key => $row) {
		$publisher[$key] = $row["publisher"];
		$title[$key] = $row["title"];
	}
	array_multisort($publisher, SORT_ASC, $title, SORT_ASC, $indexArray);

  $jsonDoi = json_encode(array("index"=> $indexArray));
  file_put_contents("index.json", $jsonDoi) or die("Unable to write file");	
  return true;
}

//This functions uses curl to fetch the content of a DOI and creates a JSON document. 
function generateDetails($url, $id) {
	//set up curl call
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
			$regexTitle = preg_split("^[a-zA-Z0-9]{2,32}$^", $paperTitle);
			$filename = saveJsonToFile($doiDetails, $regexTitle[0]);		
			$doiReturn = array("publisher" => $publisher, "title" => $paperTitle, "filename"=> $filename);
		}

	curl_close($curl);
	return $doiReturn;
}

//Actually creates the json file
function saveJsonToFile($json, $filename) {
	if($filename) {
		$filename = str_replace("/", "", $filename);
		$jsonFile = DOCUMENTS . trim($filename) . ".json";
		if($jsonFile && $json) {
			file_put_contents($jsonFile, $json);
		return $jsonFile;		
		}
		else 
			return false;
		
	}
	else 
		return false;
}

?>
<div>The DOI data has been generated.
</div><br />
<a href="index.php">Go back to the index page</a>