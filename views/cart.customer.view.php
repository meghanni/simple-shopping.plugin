<?php 
/*
 * $cart (Cart)
 */
?>
<table class="table table-condensed">
<tr><td>Name:</td><td><?php echo $cart->Title . ' ' . $cart->FirstName . ' ' . $cart->LastName; ?></td></tr>
<tr><td>Delivery Address:</td><td><?php echo $cart->Address; ?></td></tr>
<tr><td>Email:</td><td><?php echo $cart->Email; ?></td></tr>
<tr><td>Telephone Number:</td><td><?php echo $cart->Tel; ?></td></tr>
</table>