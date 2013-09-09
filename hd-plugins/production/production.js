jQuery(document).ready(function(){
	jQuery('.submitform').click(function(){
		jQuery(this).parents('form').submit();
	});
	
	jQuery('#clarification_order').sortable({
		handle: 'div.handle',
		update: function(event, ui) {
			jQuery.post(HDajax, {action: 'clar_order', data: jQuery('#clarification_order').sortable('toArray')}, 
				function(response){});
		}
	});
	jQuery('#clarification_lists_order').sortable({
		update: function(event, ui) {
			jQuery.post(HDajax, {action: 'clar_lists_order', data: jQuery('#clarification_lists_order').sortable('toArray')}, 
				function(response){});
		}
	});
	
	jQuery('input.datepicker').datepicker({
		dateFormat: "dd.mm.yy",
		firstDay: 1,
	});
	
	
	jQuery('span.inputlink').click(function(){
		inputfield = jQuery(this).parent('div').find('input');
		if(inputfield.val() != '' && inputfield.val() != ' ') {
			window.open(inputfield.attr('data-linkbase') + inputfield.val());
		}
	});
	
	
	jQuery('span.criticaleval i.true').live('click', function(){
		jQuery(this).parents('li').find('input.criticalval').val('false');
		jQuery(this).removeClass('hidden').hide();
		jQuery(this).siblings('i').removeClass('hidden').show();
	});

	jQuery('span.criticaleval i.false').live('click', function(){
		jQuery(this).parents('li').find('input.criticalval').val('true');
		jQuery(this).removeClass('hidden').hide();
		jQuery(this).siblings('i').removeClass('hidden').show();
	});
	
	jQuery('textarea').autoResize({
		animate: {
			enabled:	true,
			duration:	'fast',
			complete:	function() {},
			step:	function(now, fx) {}
		},
		maxHeight: '800px',
		minHeight: '100px',
	});
});
