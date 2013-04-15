<?php add_shortcode('product-list','ProductList'); 
/*
Lists all the products neatly with add to cart buttons
*/
function ProductList() {
	
	$cart = new Cart(); 
	
	//this is being done in the header by the cart menu so don't need to do this again here!
	//$cart->CheckRequest(); // this is all we need to do to catch requests 'for add to cart'
	

	?><div class="row"><?php
	foreach (Products::GetAll() as $product) {
	?>
		<div class="span4" style="height:500px;">
		<?php echo $product->Html(); ?>
		</div>
	<?php
	}
	?></div><?php
	Product::AddToCartScript();
	
	
}
?>
