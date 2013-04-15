<?php add_shortcode('product-test','ProductTest'); 
/*
Lists all the products neatly with add to cart buttons
*/
function ProductTest() {
	
	$product = Products::GetProduct(219);
	
	echo var_dump($product->GetTotalPriceLevels());
	
}
?>
