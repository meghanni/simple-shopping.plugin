<?php 
/*
 * required $product (Product)
 */
?>

<h3><?php echo $product->Title; ?></h3>

<div class="ssccart"><?php echo $product->AddToCartHtml(); ?></div>
<div style="margin-top:10px;height:180px;">
    <?php echo $product->GetImageLink('thumb') ?>
</div>
<p><?php echo $product->Description; ?></p>	