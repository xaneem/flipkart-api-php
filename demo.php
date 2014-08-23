<?php
//This file is under development.
//Make sure you use a correct affid and valid token.

//This page tries to demonstrate the use of the API
//The API homepage has the links to categories.
//Product details are shown in the category pages.

include "flipkart.php";

//Replace the <affiliate-id> and <access-token> with the ones generated
//through your affiliate account.
$flipkart = new \clusterdev\Flipkart("<affiliate-id>", "<access-token>", "json");

$url = isset($_GET['url'])?$_GET['url']:false;

if($url){
	$url = urldecode($url);

	$details = $flipkart->call_url($url);
	$details = json_decode($details, TRUE);


	$nextUrl = $details['nextUrl'];
	$validTill = $details['validTill'];
	$products = $details['productInfoList'];

	echo '<h2><a href="?">HOME</a> | <a href="?url='.urlencode($nextUrl).'">NEXT >></a></h2>';
	echo "<table border=2 cellpadding=10 cellspacing=1 style='text-align:center'>";
	$count = 0;

	$end = 1;
	foreach ($products as $product) {
		// echo '<pre>';
		// print_r($product);
		// echo "</pre>";
		// continue;

		$inStock = $product['productBaseInfo']['productAttributes']['inStock'];
		if(!$inStock)
			continue;
		
		$count++;

		$productId = $product['productBaseInfo']['productIdentifier']['productId'];
		$title = $product['productBaseInfo']['productAttributes']['title'];
		$productDescription = $product['productBaseInfo']['productAttributes']['productDescription'];
		$productImage = array_key_exists('200x200', $product['productBaseInfo']['productAttributes']['imageUrls'])?$product['productBaseInfo']['productAttributes']['imageUrls']['200x200']:'';
		
		$inStock = $product['productBaseInfo']['productAttributes']['inStock'];
		$sellingPrice = $product['productBaseInfo']['productAttributes']['sellingPrice']['amount'];
		$productUrl = $product['productBaseInfo']['productAttributes']['productUrl'];
		$productBrand = $product['productBaseInfo']['productAttributes']['productBrand'];

		$color = $product['productBaseInfo']['productAttributes']['color'];
		$productUrl = $product['productBaseInfo']['productAttributes']['productUrl'];

		$end = 0;
		if($count%3==1)
			echo '<tr><td>';
		else if($count%3==2)
			echo '</td><td>';
		else{
			echo '</td><td>';
			$end =1;
		}

		echo '<a target="_blank" href="'.$productUrl.'"><img src="'.$productImage.'"/><br>'.$title."</a><br>Rs. ".$sellingPrice;

		if($end)
			echo '</td></tr>';

	}

	if($count==0){
		echo '<tr><td>The 50 retrieved products are not in stock. Try the Next button or another category</td><tr>';
	}

	if($end!=1)
		echo '</td></tr>';

	echo '</table>';
	echo '<h2><a href="?url='.urlencode($nextUrl).'">NEXT >></a></h2>';

	return;
}


//If no URL given
//Get home page
$home = $flipkart->api_home();

if($home==false){
	echo 'Error: Could not retrieve API homepage';
}

$home = json_decode($home, TRUE);
$list = $home['apiGroups']['affiliate']['apiListings'];


echo '<h1>API Homepage</h1>Click on a category link to show available products from that category.<br><br>';



echo '<table border=2 style="text-align:center;">';
$count = 0;
$end = 1;
foreach ($list as $key => $data) {
	$count++;
	$end = 0;

	if($count%3==1)
		echo '<tr><td>';
	else if($count%3==2)
		echo '</td><td>';
	else{
		echo '</td><td>';
		$end =1;
	}

	echo "<strong>".$key."</strong>";
	echo "<br>";
	echo '<a href="?url='.urlencode($data['availableVariants']['v0.1.0']['get']).'">View Products &raquo;</a>';
}

if($end!=1)
	echo '</td></tr>';

echo '</table>';
