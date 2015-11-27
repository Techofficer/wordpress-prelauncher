<?php
/**
 * The Header template for Prelauncher theme
 *
 *
 * @package Prelauncher
 * @subpackage Twenty_Thirteen
 * @since Prelauncher 1.0
 */
?>
<!DOCTYPE html>
<html itemscope='' itemtype='http://schema.org/Article'>
	<head>
		<meta name="viewport" content="width=device-width">
		<?php wp_head(); ?>
	</head>
	<body id="campaign">
		<?php while ( have_posts() ) : the_post(); ?>
			<?php the_content(); ?>
		<?php endwhile; ?>	
	</body>
</html>