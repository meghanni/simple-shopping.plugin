<div>
<select name="PriceID">
    <?php foreach ($prices as $key=>$price) {?>
        <?php if (isset($price->Quantity) && isset($price->Price)) { ?>
        <option value="<?php echo $key; ?>"> <?php echo $price->Quantity; ?> <?php echo $price->Type; ?> - <?php echo $currencySymbol; echo $price->Price; ?></option>
        <?php } ?>
    <?php } ?>
</select>
</div>

