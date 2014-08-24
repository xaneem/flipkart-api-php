<?php
/**
 * Demo Code
 * PHP Wrapper for Flipkart API (unofficial)
 * GitHub: https://github.com/xaneem/flipkart-api-php
 * Demo: http://www.clusterdev.com/flipkart-api-demo
 * License: MIT License
 *
 * @author Saneem (@xaneem, xaneem@gmail.com)
 */

//Don't forget to use a valid Affiliate Id and Access Token.

//Include the class.
include "clusterdev.flipkart-api.php";

//Replace <affiliate-id> and <access-token> with the correct values
$flipkart = new \clusterdev\Flipkart("<affiliate-id>", "<access-token>", "json");

//To view category pages, API URL is passed as query string.
$url = isset($_GET['url'])?$_GET['url']:false;
if($url){
	//URL is base64 encoded to prevent errors in some server setups.
	$url = base64_decode($url);

	//This parameter lets users allow out-of-stock items to be displayed.
	$hidden = isset($_GET['hidden'])?false:true;

	//Call the API using the URL.
	$details = $flipkart->call_url($url);

	if(!$details){
		echo 'Error: Could not retrieve products list.';
		exit();
	}

	//The response is expected to be JSON. Decode it into associative arrays.
	$details = json_decode($details, TRUE);

	//The response is expected to contain these values.
	$nextUrl = $details['nextUrl'];
	$validTill = $details['validTill'];
	$products = $details['productInfoList'];

	//The navigation buttons.
	echo '<h2><a href="?">HOME</a> | <a href="?url='.base64_encode($nextUrl).'">NEXT >></a></h2>';

	//Message to be displayed if out-of-stock items are hidden.
	if($hidden)
		echo 'Products that are out of stock are hidden by default.<br><a href="?hidden=1&url='.base64_encode($url).'">SHOW OUT-OF-STOCK ITEMS</a><br><br>';

	//Products table
	echo "<table border=2 cellpadding=10 cellspacing=1 style='text-align:center'>";
	$count = 0;
	$end = 1;

	//Make sure there are products in the list.
	if(count($products) > 0){
		foreach ($products as $product) {

			//Hide out-of-stock items unless requested.
			$inStock = $product['productBaseInfo']['productAttributes']['inStock'];
			if(!$inStock && $hidden)
				continue;
			
			//Keep count.
			$count++;

			//The API returns these values nested inside the array.
			//Only image, price, url and title are used in this demo
			$productId = $product['productBaseInfo']['productIdentifier']['productId'];
			$title = $product['productBaseInfo']['productAttributes']['title'];
			$productDescription = $product['productBaseInfo']['productAttributes']['productDescription'];

			//We take the 200x200 image, there are other sizes too.
			$productImage = array_key_exists('200x200', $product['productBaseInfo']['productAttributes']['imageUrls'])?$product['productBaseInfo']['productAttributes']['imageUrls']['200x200']:'';
			$sellingPrice = $product['productBaseInfo']['productAttributes']['sellingPrice']['amount'];
			$productUrl = $product['productBaseInfo']['productAttributes']['productUrl'];
			$productBrand = $product['productBaseInfo']['productAttributes']['productBrand'];
			$color = $product['productBaseInfo']['productAttributes']['color'];
			$productUrl = $product['productBaseInfo']['productAttributes']['productUrl'];

			//Setting up the table rows/columns for a 3x3 view.
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
	}

	//A message if no products are printed.	
	if($count==0){
		echo '<tr><td>The retrieved products are not in stock. Try the Next button or another category.</td><tr>';
	}

	//A hack to make sure the tags are closed.	
	if($end!=1)
		echo '</td></tr>';

	echo '</table>';

	//Next URL link at the bottom.
	echo '<h2><a href="?url='.base64_encode($nextUrl).'">NEXT >></a></h2>';

	//That's all we need for the category view.
	exit();
}

//If the control reaches here, the API directory view is shown.

//Query the API
$home = $flipkart->api_home();

//Make sure there is a response.
if($home==false){
	echo 'Error: Could not retrieve API homepage';
	exit();
}

//Convert into associative arrays.
$home = json_decode($home, TRUE);

$list = $home['apiGroups']['affiliate']['apiListings'];

echo '<h1>API Homepage</h1>Click on a category link to show available products from that category.<br><br>';

//Create the tabulated view for different categories.
echo '<table border=2 style="text-align:center;">';
$count = 0;
$end = 1;
foreach ($list as $key => $data) {
	$count++;
	$end = 0;

	//To build a 3x3 table.
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
	//URL is base64 encoded when sent in query string.
	echo '<a href="?url='.base64_encode($data['availableVariants']['v0.1.0']['get']).'">View Products &raquo;</a>';
}

if($end!=1)
	echo '</td></tr>';
echo '</table>';

//This was just a rough example created in limited time.
//Good luck with the API.