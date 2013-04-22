<?php

/*
 * $cart Cart
 * $tc bool
 * $companyName string
 * $action string
 */
?>

<p>Please check the details below and then click 'Send Order To <?php echo PluginSettings::CompanyName(); ?>' to complete this order.</p>
<!-- print order and then show button -->

<h3>Your Details:</h3>
<?php echo $cart->CustomerHtml(); ?>

<h3>Your Order:</h3>
<?php echo $cart->CartLinesHtml(false, false); ?>

<form method="post">
<input type="hidden" name="c_action" value="<?php echo $action; ?>"></input>
<?php $tc='';?>
<?php if ($tc != '' ) { ?>
    <p>
    <label class="checkbox">
      <input type="checkbox" name="ssc_tc" value="checked">
      Please tick to confirm you have read and agree to our <a target="blank" href="<?php echo $tc; ?>">Terms and Conditions</a>.
    </label>
    </p>
<?php } ?>
<button type="submit" class="btn btn-large">Send Order To <?php echo $companyName; ?></button>
</form>
