<?php 

class Price {
 var $Quantity;
 var $Price;
 var $Type;
 
 public function __construct($quantity, $price, $type) {
 	$this->Quantity = $quantity;
 	$this->Price = $price;
    $this->Type = $type;
 }
}

class Product {
 private $ID;
 private $Title;
 private $Description;
 private $Price;
 private $Quantity;
 private $Prices;
 private $ImageURL;
 
 	public function __get($name) {
 		switch ($name) {
 		
 			case 'Prices':
 				return $this->GetPrices();
 				break;
 			case 'Price': //only used when one price level is active
 				return $this->GetPrice();
 				break;
 			case 'Quantity': //only used when one price level is active
 				return $this->GetQuantity();
 				break;
            case 'Type': //only used when one price level is active
                return $this->GetType();
                break;
 			
 			default:
 			return $this->$name;
 		}
	}

	public function __set($name, $value) {
 		switch ($name) {
 		
 			case 'Prices':
 				return $this->SavePrices($value);
 			break;
 			
 			default:
 			$this->$name = $value;
 		}	
		

		
	}
	
	function QuantityHtml() {
	ob_start();
	?>
			<?php if ( $this->GetTotalPriceLevels() == 1 ) { ?>
			<input type="hidden" name="PriceID" value="0" >
			<p>Quantity: <strong><?php echo $this->GetQuantity(); ?> <?php echo $this->GetType(); ?> </strong>  Price: <strong><?php echo PluginSettings::CurrencySymbol(); echo $this->GetPrice(); ?></strong></p>
			
			<?php } else { ?>
				<div><select name="PriceID">
					<?php foreach ($this->GetPrices() as $key=>$price) {?>
						<?php if (isset($price->Quantity) && isset($price->Price)) { ?>
						<option value="<?php echo $key; ?>"> <?php echo $price->Quantity; ?> <?php echo $price->Type; ?> - <?php echo PluginSettings::CurrencySymbol(); echo $price->Price; ?></option>
						<?php } ?>
					<?php } ?>
				</select></div>
				
			<?php } ?>	
	
	<?php
	$string=ob_get_contents();
	ob_end_clean();
	return $string; 	
	}
 
	function AddToCartHtml() {
	ob_start();
	 ?>
	 <?php $cart = new Cart();?>
            
	 <form style="display:inline;" method="post">
			<input type="hidden" id ="ProductID" name="ProductID" value="<?php echo $this->ID; ?>" >

			<?php echo $this->QuantityHtml(); ?>

			<input type="hidden" id = "cart_action" name = "cart_action" value="add" >
			<button class=".submit" type="submit" class="btn">Add To Cart</button>
	</form>
            

    <?php 
        foreach ($cart->CartLines as $line) {
            
            if ($line->ProductID == $this->ID) { 
                ?><div style="margin-top:10px;"><?php
                echo $line->Quantity . " " . $line->Type . " in Cart ";
                echo $line->RemoveHtml();
                ?></div><?php
            }
        }
    
    ?>
	
	 <?php
	  $string=ob_get_contents();
	  ob_end_clean();
	  return $string; 	 
	}
	
	//add this once to the product page to enable ajax on add to cart button
	static function AddToCartScript() {
	 ?>
 <script>
 //$('document').on('click', '.submit', function(){
  jQuery('.ssccart>form').submit(function() {
  		$ele = this;

		jQuery.ajax({
			type : "post",
			dataType : "json",
			cache: false,
			url : "<?php echo admin_url( 'admin-ajax.php' ); ?>",
			data : jQuery($ele).serialize() + "&action=ssc_cart", 
			success: function(response,strText) {
													jQuery($ele).parent().html(response.addtocart);
													jQuery('.cart-menu').html(response.cartmenu);
															  },
			error: function(){
							 alert('failure');
						  }													
															  
		});  

  return false;
});
</script> 
	 <?php
	}
	
	//output html for just this product
	function Html ($detail=false) {
	
	?>
		<? if ( $detail == true) { ?>
		<h3><a href="<?php  echo get_permalink($this->ID); ?>"><?php echo $this->Title; ?></a></h3>
		<?php } else { ?> 
		<h4><a href="<?php  echo get_permalink($this->ID); ?>"><?php echo $this->Title; ?></a></h4>
		<?php } ?>
		<div class="ssccart"><?php echo $this->AddToCartHtml(); ?></div>
		<div style="margin-top:10px;height:180px;"><img  width="200px" src=" <?php echo $this->ImageURL; ?> "/></div>
		<p><?php echo $this->Description; ?></p>	
	<?php
	return;
	}
	
	private function GetPrices() {
		$value = get_post_meta( $this->ID, $key = 'ssc_product_data', $single = true );
	
		$prices=array();
		for ($x=0; $x < PluginSettings::NumberOfPriceLevels(); $x++) {
			if (isset($value[$x]))
				$prices[$x] = $value[$x];
			else
				$prices[$x] = new Price(null,null,null);

		}

		return $prices;
	}

	private function SavePrices($value) {
	
		//copy and remove the blank entries
		$prices = array();
		
		foreach ($value as $price) {
			if ( isset($price->Quantity) && isset($price->Price) )
				$prices[] = $price;
		}
		
		//save sort by quantity
		usort($prices, function($a, $b)
		{
			 return $a->Quantity > $b->Quantity;
		});	
			
		update_post_meta( $this->ID, 'ssc_product_data', $prices );
	}
	
	//returns the number of price levels that have been entered against a product ignoring invalid objects
	public function GetTotalPriceLevels() {
		$x = 0;
		foreach ($this->GetPrices() as $price) {
			if ( isset($price->Quantity) && isset($price->Price) )
				$x++;
		}
		
		return $x;
	}
	
	private function GetPrice() {
		if ($this->GetTotalPriceLevels() > 1)
			return null;
			
		foreach ($this->GetPrices() as $price)  {
			if ( isset($price->Quantity) && isset($price->Price) )
				return $price->Price;
		}
	}	

	private function GetQuantity() {
		if ($this->GetTotalPriceLevels() > 1)
			return null;

		foreach ($this->GetPrices() as $price)  {
			if ( isset($price->Quantity) && isset($price->Price) )
				return $price->Quantity;
		}
	}
    
    private function GetType() {
		if ($this->GetTotalPriceLevels() > 1)
			return null;

		foreach ($this->GetPrices() as $price)  {
			if ( isset($price->Quantity) && isset($price->Price) )
				return $price->Type;
		}
	}	

}

class Products {

/*return an array of product objects*/
static function GetAll() {

	 $args = array('post_type'=>'dm_product' ); 
	 $my_query = new WP_Query($args); 
	
    $prods = array();
    
		while ($my_query->have_posts()) : $my_query->the_post();
		
			
			$product = new Product;
			$product->ID = get_the_id();
			$product->Title = get_the_title();
			$product->Description = get_the_content();
			//$product->Prices = $product->GetPrices());
			$product->ImageURL = self::GetImageForPost($product->ID);

			$prods[] = $product;


				                      
		endwhile;
		
	 return $prods;
}

static function GetProduct($id) {

	 $args = array('post_type'=>'dm_product', 'p'=>$id ); 
	 $my_query = new WP_Query($args); 
	
    	while ($my_query->have_posts()) : $my_query->the_post();
			$product = new Product;
			$product->ID = get_the_id();
			$product->Title = get_the_title();
			$product->Description = get_the_content();
			//$product->Prices = $product->GetPrices());
			$product->ImageURL = self::GetImageForPost($product->ID);
			
                   
		endwhile;

	 return $product;
}

static function GetImageForPost($id) {
    //first check for a featured post
    $post_thumbnail_id = get_post_thumbnail_id( $id );
    
    if ( $post_thumbnail_id != null ) {
      return wp_get_attachment_url($post_thumbnail_id);  
    }
    else {
    
	    $attachments = get_children( array('post_parent' => $id, 'post_type' => 'attachment', 'post_mime_type' =>'image') );
	    foreach ( $attachments as $attachment_id => $attachment )
		    return wp_get_attachment_url($attachment_id);
	}

}


}
?>
