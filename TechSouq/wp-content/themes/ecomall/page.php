<?php
get_header();

$theme_options = ecomall_get_theme_options();
$page_options = ecomall_get_page_options();

$extra_class = '';

$page_column_class = ecomall_page_layout_columns_class($page_options['ts_page_layout']);

$show_breadcrumb = ( !is_home() && !is_front_page() && $page_options['ts_show_breadcrumb'] );
$show_page_title = ( !is_home() && !is_front_page() && $page_options['ts_show_page_title'] );
if( $show_breadcrumb || $show_page_title ){
	$extra_class = 'show_breadcrumb_'.ecomall_get_theme_options('ts_breadcrumb_layout');
}

if( $theme_options['ts_prod_cat_border'] ){
	$extra_class .= ' border-default';
}

ecomall_breadcrumbs_title($show_breadcrumb, $show_page_title, get_the_title());
?>
<!-- Page slider -->
<?php if( $page_options['ts_page_slider'] && $page_options['ts_page_slider_position'] == 'before_main_content' ): ?>
<div class="top-slideshow">
	<div class="top-slideshow-wrapper">
		<?php ecomall_show_page_slider(); ?>
	</div>
</div>
<?php endif; ?>

<div class="page-container <?php echo esc_attr($extra_class) ?> <?php echo esc_attr($page_column_class['main_class']); ?>">
	
	<!-- Left Sidebar -->
	<?php if( $page_column_class['left_sidebar'] ): ?>
		<div id="left-sidebar" class="ts-sidebar">
			<aside>
			<?php if( is_active_sidebar($page_options['ts_left_sidebar']) ): ?>
				<?php dynamic_sidebar($page_options['ts_left_sidebar']); ?>
			<?php endif; ?>
			</aside>
		</div>
	<?php endif; ?>	
	
	<!-- Main Content -->
	<div id="main-content">	
		<div id="primary" class="site-content">
		<?php 
			if( class_exists('WooCommerce') ){
				wc_print_notices();
			}
		?>
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<?php 
					if( have_posts() ) the_post();
					the_content();
					wp_link_pages();
				?>
			</article>
			<?php 
			/* If comments are open or we have at least one comment, load up the comment template. */
			if ( comments_open() || get_comments_number() ) :
				comments_template( '', true );
			endif;
			?>
		</div>
	</div>
	
	<!-- Right Sidebar -->
	<?php if( $page_column_class['right_sidebar'] ): ?>
		<div id="right-sidebar" class="ts-sidebar">
			<aside>
			<?php if( is_active_sidebar($page_options['ts_right_sidebar'])): ?>
				<?php dynamic_sidebar($page_options['ts_right_sidebar']); ?>
			<?php endif; ?>
			</aside>
		</div>
	<?php endif; ?>
	
</div>

<?php get_footer(); ?>