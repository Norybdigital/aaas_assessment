<h1>Index Pages</h1>
<?php if(!file_exists("index.json")) { ?>
	<div id="generateButtonLabel">You'll need to generate data before you can see the list of DOIs</div><br />
	<a href="generate.php">Generate</a>
<?php } else { ?>

<?php
	$doiIndex = file_get_contents("index.json");
	$doiArray = json_decode($doiIndex, true);
	$i = 0;
	foreach($doiArray['index'] as $key => $row) {
		//print_r($row);
		$publisher[$key] = $row['publisher'];
		$title[$key] = $row['title'];
	}
	array_multisort($publisher, SORT_ASC, $title, SORT_ASC, $doiArray['index']);
	//print_r($doiArray);
	$doiPublisher = '';
	$publisherCount = 0;
	foreach ($doiArray as $item) {
		foreach ($item as $doi) {
			$id = $doi['id'];
			$url = "details.php?id=" . $i;
			if(strtolower($doi['publisher']) !== strtolower($doiPublisher)) {
				$publisherCount++;
				$doiPublisher = $doi['publisher']; 
				if($publisherCount >= 1) {
					print "</div>";
				}
				?>
				<div class="publisher-title"><h2><?php print $doi['publisher']; ?></h2>
		<?php 	} ?>
			<div>
				<a href="<?php print $url; ?>"" ><?php print $doi['title']; ?></a><br />
			</div>
<?php
	$i++;
		}
}
}
?>