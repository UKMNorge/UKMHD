function function_exists(object) {
	var getType = {};
	return object+'()' && getType.toString.call(object+'()') == '[object Function]';
}
jQuery(document).ready(function(){
	jQuery('.hd-submit').click(function(){
		var action = jQuery(this).attr('data-action');
		var handle = jQuery(this).attr('data-handle');
		var precall= 'HDprecall_'+action+'_'+handle;
		var callback='HDcallback_'+action+'_'+handle;

		if(function_exists(precall)){
			eval(precall+'()');
		}
		
		var data = 'action='+action
				 + '&handle='+handle
				 + '&'+jQuery(this).parents('form').serialize();
		jQuery.post(HDajax, data, function(response){
			if(function_exists(callback)){
				eval(callback+'(\''+response+'\')');
			}
		});
	});
});