<?php add_shortcode('cart-menu','CartMenu');
/*
allows a short code to be placed in the menu
for this to work a filter must be placed in the main plugin php file
*/

function CartMenu($atts) {
	extract(shortcode_atts( array('page' => ''), $atts)); 
	
	$cart = new Cart();
	return "<i class=\"icon-shopping-cart icon-white\"></i><a href=\"/$page\"><span class=\"cart-menu\">" . $cart->CartMenuHtml() . "</span></a>";
}
?>
