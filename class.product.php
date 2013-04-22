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
 private $ImageLinkThumb;
 private $ImageLinkMedium;
 private $ImageLinkLarge;
 private $ImageLinkFull;
 
 public function __construct() {
     $url = urlencode(admin_url() . '/admin-ajax.php');
     wp_register_script( 'addtocart', SCC_PLUGIN_URL . "/js/addtocart.php?adminurl=$url", 'jquery', "1.00", true );
 }
 
 
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
            case 'ImageURL':
                return $this->GetImageUrl();
                break;
            case 'ImageLinkThumb':
                return $this->GetImageLink('thumb');
                break;
            case 'ImageLinkMedium':
                return $this->GetImageLink('medium');
                break;
            case 'ImageLinkLarge':
                return $this->GetImageLink('large');
                break;
            case 'ImageLinkFull':
                return $this->GetImageLink('full');
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
        if ( $this->GetTotalPriceLevels() == 1 ) {
            $view = new SSC_View('product.quantity.single');
            $view->Set('quantity', $this->GetQuantity());
            $view->Set('type', $this->GetType());
            $view->Set('price', $this->GetPrice());
        }
        else {
            $view = new SSC_View('product.quantity.list');
            $view->Set('prices', $this->GetPrices());
        }
        $view->Set('currencySymbol', PluginSettings::CurrencySymbol());
        return $view->Render();

	}
 
	function AddToCartHtml() {
        $cart = new Cart();
        
        $view = new SSC_View('product.addtocart');
        $view->Set('product', $this);
        $view->Set('cart', $cart);
        return $view->Render();
	 
	}
	
	//add this once to the product page to enable ajax on add to cart button
	static function AddToCartScript() {
        wp_enqueue_script('addtocart');
	}
	
	//output html for just this product
    function Html ($detail=false) {
        if ( $detail == true)
            $view = new SSC_View('product.detail');
        else
            $view = new SSC_View('product');
        $view->Set('product', $this);
        return $view->Render();
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
    
    private function GetImageUrl() {
        //first check for a featured post
        $post_thumbnail_id = get_post_thumbnail_id($this->ID);

        if ( $post_thumbnail_id != null ) {
          return wp_get_attachment_url($post_thumbnail_id);  
        }
        else {

            $attachments = get_children( array('post_parent' => $this->ID, 'post_type' => 'attachment', 'post_mime_type' =>'image') );
            foreach ( $attachments as $attachment_id => $attachment )
                return wp_get_attachment_url($attachment_id);
        }

    }
    
    public function GetImageLink($size) {
        //first check for a featured post
        $post_thumbnail_id = get_post_thumbnail_id($this->ID);

        if ( $post_thumbnail_id != null ) {
          return wp_get_attachment_link( $post_thumbnail_id, 'thumbnail',0);
        }
        else {

            $attachments = get_children( array('post_parent' => $this->ID, 'post_type' => 'attachment', 'post_mime_type' =>'image') );
            foreach ( $attachments as $attachment_id => $attachment )
                return wp_get_attachment_link($attachment_id, $size,0);
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

static function GetImageLinkForPost($id) {
    //first check for a featured post
    $post_thumbnail_id = get_post_thumbnail_id( $id );
    
    if ( $post_thumbnail_id != null ) {
      return wp_get_attachment_link( post_thumbnail_id, $size, $permalink, $icon, $text ); 
    }
    else {
    
	    $attachments = get_children( array('post_parent' => $id, 'post_type' => 'attachment', 'post_mime_type' =>'image') );
	    foreach ( $attachments as $attachment_id => $attachment )
		    return wp_get_attachment_url($attachment_id);
	}

}


}