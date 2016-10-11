<h1>Index Page</h1>
<?php
	//Check if the index.json file exists. If it doesn't, you'll need to create one first.
	if(!file_exists("index.json")) { 
?>
	<div id="generateButtonLabel">You'll need to generate data before you can see the list of DOIs</div><br />
	<a href="generate.php">Generate</a>
<?php } else { ?>

<?php
	//Get the contents of the index.json file and convert it from json to a multidimensional array.
	$doiIndex = file_get_contents("index.json");
	$doiArray = json_decode($doiIndex, true);
	$i = 0;
	
	$doiPublisher = '';
	$publisherCount = 0;
	$lastTitle = '';
	//iterate through the array.  If the previous item has the same 
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