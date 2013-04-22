<?php 
/*
 * $cart (Cart)
 * $withChangeQuantity (bool)
 * $withRemove (bool)
 * $currencySymbol (string)
 * 
 */
?>
<table class="table table-hover table-condensed table-striped" width="100%">
<tr><th colspan="2">Product Ordered</th><th>Qty</th><th colspan="2">Price</th></tr>

    <?php foreach ($cart->CartLines as $cartLine) { ?>
        <?php $product = Products::GetProduct($cartLine->ProductID); ?>
        <tr>
        <td><?php echo $product->ImageLinkThumb; ?></td>

        <?php if ($withLink) { ?>
            <td><a href="<?php echo the_permalink(); ?>"><?php echo $cartLine->Type; ?> <?php echo $product->Title; ?></a></td>
        <?php } else { ?>
            <td><?php echo $cartLine->Type; ?> <?php echo $product->Title; ?></td>
        <?php } ?>


        <?php if ($withChangeQuantity) { ?>

            <td><?php echo $cartLine->Quantity; ?></td>

        <?php } else { ?>

            <td><?php echo $cartLine->Quantity; ?></td>

        <?php } ?>


        <td><?php echo $currencySymbol; ?> <?php echo $cartLine->LinePrice(); ?></td>

            <?php if ($withRemove) { ?>
                    <td><?php echo $cartLine->RemoveHtml(); ?></td>
            <?php } ?>

        </tr>
    <?php } ?>

    <tr>
        <td colspan="3"><h3>Total Price</h3></td>
        <td><h3><?php echo $currencySymbol; ?><?php echo $cart->TotalPrice(); ?></h3></td>

    </tr>
</table>