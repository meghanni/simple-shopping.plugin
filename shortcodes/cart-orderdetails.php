<?php add_shortcode('cart-orderdetails','CartOrderDetails');
/*
Lists all the products neatly with add to cart buttons
*/
function CartOrderDetails() {
?>
	
	<?php $cart = new Cart(); $cart->IsValid();?>

	
	<!-- the form -->
	<form action="/confirm-and-place-order" class="form-horizontal" name="frmContact" method="post" action="">
	<fieldset>
	  <legend>Please enter your order details:</legend>
	  <div class="controls">
	  <p class="text-error"><?php echo $cart->ErrorMessage; ?></p>
	  </div>
	  
	  <div class="control-group">
		 <label class="control-label" for="FirstName">Title:</label>
		 <div class="controls">
		   <select name="Title">
			  <option value="Mrs" <?php if ($cart->Title == "Mrs") echo "selected" ?>>Mrs</option>
			  <option value="Mr" <?php if ($cart->Title == "Mr") echo "selected" ?>>Mr</option>
			  <option value="Ms" <?php if ($cart->Title == "Ms") echo "selected" ?>>Ms</option>
			  <option value="Miss" <?php if ($cart->Title == "Miss") echo "selected" ?>>Miss</option>
			  <option value="Dr" <?php if ($cart->Title == "Dr") echo "selected" ?>>Dr</option>
			</select>
		 </div>
	  </div>	  
	  
	  <div class="control-group">
		 <label class="control-label" for="FirstName">First Name:</label>
		 <div class="controls">
		   <input class="input-xlarge" type="text" name="FirstName" value="<?php echo $cart->FirstName; ?>" placeholder="First Name">
		 </div>
	  </div>
	  
	 <div class="control-group">
		 <label class="control-label" for="LastName">Last Name:</label>
		 <div class="controls">
		   <input class="input-xlarge" type="text" name="LastName" value="<?php echo $cart->LastName; ?>" placeholder="Last Name">
		 </div>
	  </div>
	  
	  <div class="control-group">
		 <label class="control-label" for="Address">Delivery Address:</label>
		 <div class="controls">
		   <textarea class="input-xlarge" name="Address" rows="10" placeholder="Address:"><?php echo $cart->Address; ?></textarea>
		 </div>
	  </div>
	    
	  
	 <div class="control-group">
		 <label class="control-label" for="Email">Email Address:</label>
		 <div class="controls">
		   <input class="input-xlarge" type="text" name="Email" value="<?php echo $cart->Email; ?>" placeholder="Email Address">
		 </div>
	  </div> 
	  
	  <div class="control-group">
		 <label class="control-label" for="Tel">Telephone Number:</label>
		 <div class="controls">
		   <input class="input-xlarge" type="text" name="Tel" value="<?php echo $cart->Tel; ?>" placeholder="Telephone Number">
		 </div>
	  </div> 
	  
	  
	  <div class="control-group">
		 	<div class="controls">
			 	<button type="submit" class="btn btn-large">Submit</button>
		 	<div>
		 <div>
	</fieldset>
	</form>
		
		
		
<?php } ?>
