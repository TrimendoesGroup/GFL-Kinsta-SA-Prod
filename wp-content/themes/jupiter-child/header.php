<!DOCTYPE html>
<html <?php echo language_attributes();?> >
<head>
	<!-- Google Tag Manager -->
	<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
	new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
	j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
	'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
	})(window,document,'script','dataLayer','GTM-K2BQM8S');</script>
	<!-- End Google Tag Manager —>

	<!-- Bing Webmaster -->
	<meta name="msvalidate.01" content="5C847F01FB1773897885D17ECB5C685D" />
	<!-- END Bing Webmaster -->


    <?php wp_head(); ?>
</head>

<body <?php body_class(mk_get_body_class(global_get_post_id())); ?> <?php echo get_schema_markup('body'); ?> data-adminbar="<?php echo is_admin_bar_showing() ?>">
	<!-- Google Tag Manager (noscript) -->
	<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-K2BQM8S";
	height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
	<!-- End Google Tag Manager (noscript) -->

	<?php
		// Hook when you need to add content right after body opening tag. to be used in child themes or customisations.
		do_action('theme_after_body_tag_start');
	?>

	<!-- Target for scroll anchors to achieve native browser bahaviour + possible enhancements like smooth scrolling -->
	<div id="top-of-page"></div>

		<div id="mk-boxed-layout">

			<div id="mk-theme-container" <?php echo is_header_transparent('class="trans-header"'); ?>>

				<?php mk_get_header_view('styles', 'header-'.get_header_style());