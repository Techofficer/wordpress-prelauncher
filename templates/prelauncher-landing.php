<?php
	
	echo Prelauncher::uploadLandingPage();
?>

<script>
	var prelauncher = new Prelauncher('<?php echo Prelauncher()->companyID ?>', '<?php echo Prelauncher()->token ?>', true);
	prelauncher.buildFirstPage();
</script>