<?php
get_header();

$theme_options = ecomall_get_theme_options();

$page_column_class = ecomall_page_layout_columns_class( $theme_options['ts_blog_layout'], $theme_options['ts_blog_left_sidebar'], $theme_options['ts_blog_right_sidebar'] );

$show_breadcrumb = apply_filters('ecomall_show_breadcrumb_on_archive_post', true);
$show_page_title = apply_filters('ecomall_show_page_title_on_archive_post', true);
$page_title = '';
$extra_class = 'columns-' . $theme_options['ts_blog_columns'];
if( $show_breadcrumb || $show_page_title ){
	$extra_class .= ' show_breadcrumb_'.$theme_options['ts_breadcrumb_layout'];
}

if( $show_page_title ){
	switch( true ){
		case is_day():
			$page_title = esc_html__( 'Day: ', 'ecomall' ) . get_the_date();
		break;
		case is_month():
			$page_title = esc_html__( 'Month: ', 'ecomall' ) . get_the_date( esc_html_x( 'F Y', 'monthly archives date format', 'ecomall' ) );
		break;
		case is_year():
			$page_title = esc_html__( 'Year: ', 'ecomall' ) . get_the_date( esc_html_x( 'Y', 'yearly archives date format', 'ecomall' ) );
		break;
		case is_search():
			$page_title = esc_html__( 'Search Results for: ', 'ecomall' ) . get_search_query();
		break;
		case is_tag():
			$page_title = esc_html__( 'Tag: ', 'ecomall' ) . single_tag_title( '', false );
		break;
		case is_category():
			$page_title = esc_html__( 'Category: ', 'ecomall' ) . single_cat_title( '', false );
		break;
		case is_404():
			$page_title = esc_html__( 'OOPS! FILE NOT FOUND', 'ecomall' );
		break;
		default:
			$page_title = esc_html__( 'Archives', 'ecomall' );
		break;
	}
}

ecomall_breadcrumbs_title($show_breadcrumb, $show_page_title, $page_title);
?>

<div class="page-container page-template archive-template <?php echo esc_attr($extra_class) ?> <?php echo esc_attr($page_column_class['main_class']); ?>">
	
	<!-- Left Sidebar -->
	<?php if( $page_column_class['left_sidebar'] ): ?>
		<div id="left-sidebar" class="ts-sidebar">
			<aside>
				<?php dynamic_sidebar( $theme_options['ts_blog_left_sidebar'] ); ?>
			</aside>
		</div>
	<?php endif; ?>	
	
	<!-- Main Content -->
	<div id="main-content">	
		<div id="primary" class="site-content">
		
			<?php	
				if( have_posts() ):
					echo '<div class="list-posts">';
					while( have_posts() ) : the_post();
						get_template_part( 'content', get_post_format() ); 
					endwhile;
					echo '</div>';
				else:
					echo '<div class="alert alert-error">'.esc_html__('Sorry. There are no posts to display', 'ecomall').'</div>';
				endif;
				
				ecomall_pagination();
			?>
			
		</div>
	</div>
	
	<!-- Right Sidebar -->
	<?php if( $page_column_class['right_sidebar'] ): ?>
		<div id="right-sidebar" class="ts-sidebar">
			<aside>
				<?php dynamic_sidebar( $theme_options['ts_blog_right_sidebar'] ); ?>
			</aside>
		</div>
	<?php endif; ?>
	
</div>

<?php
get_footer();
