<?php

?>
<?php if(!file_exists("index.json")) { ?>
	<div id="generateButtonLabel">You'll need to generate data before you can see the list of DOIs</div><br />
	<a href="generate.php">Generate</a>
<?php } else { ?>

<?php
	$doiIndex = file_get_contents("index.json");
	$doiArray = json_decode($doiIndex, true);
	$i = 0;
	//print_r($doiArray);
	//usort($doiArray, 'sort_by_publisher');
	uasort($doiArray, function($a, $b) {
		if($a->publisher == $b->publisher) {
			return $a->title > $b->title ? 1: -1;
		} else {
			return $b->publisher - $a->publisher;
		}

		/*if($a['publisher'] == $b['publisher']) {
			return $a['title'] > $b['title'] ? 1: -1;
		} else {
			return $b['publisher'] - $a['publisher'];
		}*/
		});
		//return $a->publisher < $b->publisher ? -1 : 1;
	//print_r($doiArray);
	$i = 0;
	foreach ($doiArray as $item) {
		foreach ($item as $doi) {
			$id = $doi['id'];
			$url = "details.php?id=" . $i;
		 ?>
			<div>
				<a href="<?php print $url; ?>"" ><?php print $doi['title']; ?></a><br />
			</div>
<?php
	$i++;
		}
}
}
?>