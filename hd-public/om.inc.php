<div class="span2"></div>
<div class="span8 well">
	<div class="span4 pull-right hidden-phone" style="margin-right: -30px;">
		<div class="btn-group">
		  <a class="btn opacity80" href="/">Forsiden</a>
		  <a class="btn opacity80" target="_blank" href="http://ukm.no/hva-er-ukm/">Mer om UKM</a>
		  <a class="btn opacity80" target="_blank" href="http://facebook.com/UKMNorge">UKM på facebook</a>
		</div>
	</div>

<?php require_once('omtekst.inc.php'); ?>
	
</div>
<div class="span2"></div>

<script language="javascript">
jQuery(document).ready(function(){
	jQuery('p.explanation').hide();
	jQuery('a.explain').attr('title', 'Klikk for å lese litt mer - du blir ikke sendt videre til en ny side :)');
	jQuery('a.explain').attr('alt', 'Klikk for å lese litt mer - du blir ikke sendt videre til en ny side :)');
	
	jQuery('a.explain').click(function(event){
		event.preventDefault();
		var show = jQuery(this).attr('data-what');
		jQuery('#'+show).siblings('p').hide();
		jQuery('#'+show).siblings('div.explanation').hide();
		jQuery('#'+show).removeClass('hidden').show();
	});
	
	jQuery('a.explanationclose').click(function(event){
		event.preventDefault();
		id = jQuery(this).parents('div.explanation').attr('id');
		jQuery('#'+id).hide();
		jQuery('#'+id).siblings('p').show();
	});
});
</script>