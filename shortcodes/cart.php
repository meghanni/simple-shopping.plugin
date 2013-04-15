<?php add_shortcode('cart','CartList'); 
/*
Lists all the products neatly with add to cart buttons
*/
function CartList() {
?>
	
	<?php $cart = new Cart(); ?>
	
	<?php if ($cart->TotalLines() == 0) { ?>
		<p>Your cart is currently empty.</p>
	<?php } else { ?>

		<div class="pull-right">
			<form method="post">
				<input type="hidden" name="cart_action" value="emptycart"></input>
				<button title="Empty Cart" type="submit" class="btn btn-danger btn-mini">Empty Cart</button>
			</form>
		</div>			
		
		</p>You have <?php echo $cart->TotalLines(); ?> item(s) in your cart.</p>
		
		
		<?php echo $cart->CartLinesHtml(true, true, true); ?>

		<form action="/order-details">
		<button type="submit" class="btn btn-large">Proceed With This Order</button>
		</form>
	
	<?php } ?>
	
<?php
}

?>
