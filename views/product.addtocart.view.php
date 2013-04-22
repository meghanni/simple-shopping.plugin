<?php 
/*
 * required vars $product, $cart
 */
?>
<form style="display:inline;" method="post">
    <input type="hidden" id ="ProductID" name="ProductID" value="<?php echo $product->ID; ?>" >

    <?php echo $product->QuantityHtml(); ?>

    <input type="hidden" id = "cart_action" name = "cart_action" value="add" >
    <button class=".submit" type="submit" class="btn">Add To Cart</button>
</form>
            

<?php foreach ($cart->CartLines as $line) { ?>

    <?php if ($line->ProductID == $product->ID) { ?>
        <div style="margin-top:10px;">
        <?php echo $line->Quantity. " " . $line->Type; ?> in Cart "
        <?php echo $line->RemoveHtml();?>
        </div>
    <?php } ?>

<?php } ?>