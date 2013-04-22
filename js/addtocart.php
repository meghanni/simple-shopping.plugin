<?php $adminurl = $_GET['adminurl'] ?>

jQuery('.ssccart>form').submit(function() {
  		$ele = this;

		jQuery.ajax({
			type : "post",
			dataType : "json",
			cache: false,
			url : "<?php echo $adminurl ; ?>",
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