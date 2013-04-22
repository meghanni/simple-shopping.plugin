<?php

/*
 * $cartLine (CartLine)
 */
?>
<form style="display:inline;" class="removeitem" method="post">
<input type="hidden" name="cart_action" value="remove"></input>
<input type="hidden" name="ProductID" value="<?php echo $cartLine->ProductID; ?>"></input>
<input type="hidden" name="PriceID" value="<?php echo $cartLine->PriceID; ?>"></input>
<button title="Remove This" type="submit" class="btn btn-danger btn-mini">X</button>
</form>
