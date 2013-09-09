</div>
</body>
<script>
	var HDajax = '<?= HD_CORE_URL ?>hd-ajax.php';
</script>
<script src="<?= HD_CORE_URL ?>js/ajax.js"></script>
<?php
if(is_array($hd_jsfiles['footer'])) { ?>
<script language="javascript">
	<?php
	foreach($hd_jsfiles['footer'] as $snippets){
		echo $snippets;
	} ?>
</script>
<?php 
} ?>
</html>