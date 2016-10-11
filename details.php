<pre>
<h1>Details Page</h1>
<?php
	//using the id number passed through the querystring, find the article with the corresponding id and display its details
	if($_GET['id'] >= 0) {
		$doiId = $_GET['id'];

		$doiIndex = file_get_contents('index.json');
		$doiArray = json_decode($doiIndex, true);
		if($doiArray['index'][$doiId]) {
			$myDoi = file_get_contents(trim($doiArray['index'][$doiId]['filename']));
			if($myDoi) {
				$detailsArray = json_decode($myDoi, true); 
				//print_r($detailsArray);
				//printAuthors($detailsArray['message']['author']);
			?>
			<div>Publisher: <?php print $detailsArray['message']['publisher']; ?></div>
			<div>Title: <?php print $detailsArray['message']['title'][0]; ?></div>
			<div
			<div>Issue: <?php print $detailsArray['message']['issue']; ?></div>
			<div>Reference Count: <?php print $detailsArray['message']['reference-count']; ?></div>
			<div>DOI: <a href="<?php print $detailsArray['message']['URL'] ;?>"target="_blank"><?php print $detailsArray['message']['URL']; ?></a></div>
			<div>Date: <?php print $detailsArray['message']['publisher']; ?></div>
			<div>Type: <?php print $detailsArray['message']['type']; ?></div>
			<div class="authors">Author(s): <?php printAuthors($detailsArray['message']['author']); ?></div>
			<div>Volume: <?php print $detailsArray['message']['volume']; ?></div>
			<div>Subject: <?php print $detailsArray['message']['subject'][0]; ?></div>
<?php			
			}
		} else {
			print "No DOI was found.";
		}
		//print_r($doiArray['index'][$doiId]);
	} else {
		print "You have not selected a DOI to display";
	}

	function printAuthors($data) {
		foreach($data as $author) {
			print "<p>". $author["family"] . ", " . $author["given"] . "</p>";
		}

		return true;
	}
?>
<p><a href="index.php">Return to Index Page.</a></p>
</pre>
