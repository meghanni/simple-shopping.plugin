<?php 
add_action("wp_ajax_ssc_cart", "CartMaintenance");
add_action("wp_ajax_nopriv_ssc_cart", "CartMaintenance");

//http://wp.smashingmagazine.com/2011/10/18/how-to-use-ajax-in-wordpress/
function CartMaintenance() {

	$id = $_POST['ProductID'];

	//just doing this will perform the add to cart function on the database
	$cart = new Cart(); 
	
	$product=Products::GetProduct($id);
	
	//return the new html for add to cart button and text, and the cart menu item
    $view = new SSC_View('product.addtocart');
    $view->Set('product', $product);
    $view->Set('cart', $cart);
	$result['addtocart']=$view->Render();;
    
    $view = new SSC_View('cart.menu');
    $view->Set('totalLines', $cart->TotalLines());
	$result['cartmenu']=$view->Render();
	
	echo json_encode($result);

	die();
   

}

?>
