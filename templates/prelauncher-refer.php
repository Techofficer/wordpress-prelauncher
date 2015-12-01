<?php
	echo Prelauncher::uploadReferPage();
?>

<script>
	var prelauncher = new Prelauncher('<?php echo Prelauncher()->credentials["company_id"] ?>');
	prelauncher.buildSecondPage('<?php echo Prelauncher()->clientID ?>');
</script>