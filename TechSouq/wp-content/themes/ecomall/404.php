<?php 
get_header();

$theme_options = ecomall_get_theme_options();
$classes = array();
$classes[] = 'show_breadcrumb_' . $theme_options['ts_breadcrumb_layout'];

ecomall_breadcrumbs_title(false, false, '');
?>
	<div class="page-container <?php echo esc_attr(implode(' ', $classes)); ?>">
		<div id="main-content">	
			<div id="primary" class="site-content">
				<article>
					<div class="not-found">
						<div class="image-404">
							<div class="text-clipping"><?php esc_html_e('404', 'ecomall') ?></div>
						</div>
						<h1><?php esc_html_e('The page you\'re looking for doesn\'t exist or probably moved somewhere...', 'ecomall'); ?></h1>
						<p><?php esc_html_e('Please back to homepage or check our offer', 'ecomall'); ?></p>
						<a href="<?php echo esc_url( home_url('/') ) ?>" class="button"><?php esc_html_e('Back to homepage', 'ecomall'); ?></a>
					</div>
				</article>
			</div>
		</div>
	</div>
<?php
get_footer();