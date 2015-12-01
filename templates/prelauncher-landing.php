<?php
	echo Prelauncher::uploadLandingPage();
?>

<script>
	var prelauncher = new Prelauncher('<?php echo Prelauncher()->credentials["company_id"] ?>');
	prelauncher.buildFirstPage();
</script>