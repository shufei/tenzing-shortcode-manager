jQuery(document).ready(function () {
    
//jQuery("span[data-shortcode=tabs]").css("display","none");
	
	jQuery("#tsm_click").click(function(e){
		jQuery(".tsm_items").each(function(){
			if(jQuery(this).is(':checked')) {
				var data_shortcode=jQuery(this).attr("id");
				
				
				jQuery("span[data-shortcode="+data_shortcode+"]").css("display","none");
				//alert(jQuery("span[data-shortcode=tabs]").html());
			}else{
				var data_shortcode=jQuery(this).attr("value");
				jQuery("span[data-shortcode="+data_shortcode+"]").css("display","none");
			}
		});
		
		
	})
	
	
});