<?php 
class PluginSettings {

	static function Expiration() {
		return WEEK_IN_SECONDS;
	}
	
	static function PluginName() {
		return 'dm_cart';
	}	
	
	static function ProductPostType() {
		return 'dm_product';
	}

	static function OrderPostType() {
		return 'dm_order';
	}
	
	static function CompanyName() {
		$value = get_option('company_name');
	    if ( $value == false )
	        return 'My Company Name';
		else
		    return $value;
	}	
	
	static function CurrencySymbol() {
		return '&pound;';
	}	
	
	static function NumberOfPriceLevels() {
	    $value = get_option('pricing_level');
	    if ( $value == false )
	        return 4;
		else
		    return $value;
	}	
}
 ?>
