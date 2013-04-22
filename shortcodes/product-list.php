<?php add_shortcode('product-list','ProductList'); 
/*
Lists all the products neatly with add to cart buttons
*/
function ProductList() {
	
	$cart = new Cart(); ?>

	<div class="row">
	<?php foreach (Products::GetAll() as $product) { ?>
		<div class="span4" style="height:500px;">
		<?php echo $product->Html(); ?>
		</div>
	<?php } ?>
    </div>

    <?php 
    Product::AddToCartScript();
}
?>