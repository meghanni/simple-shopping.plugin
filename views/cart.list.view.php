<?php

/*
 * $totalLines int
 * $cart Cart
 * $action string
 */
?>

<div class="pull-right">
    <form method="post">
        <input type="hidden" name="cart_action" value="emptycart"></input>
        <button title="Empty Cart" type="submit" class="btn btn-danger btn-mini">Empty Cart</button>
    </form>
</div>			

<p>You have <?php echo $totalLines; ?> item(s) in your cart.</p>


<?php echo $cart->CartLinesHtml(true, true, true); ?>

<form method="post">
    <input type="hidden" name="c_action" value="<?php echo $action; ?>"></input>
<button type="submit" class="btn btn-large">Proceed With This Order</button>
</form>
