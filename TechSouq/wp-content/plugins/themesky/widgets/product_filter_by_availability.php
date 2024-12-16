<?php 
add_action('widgets_init', 'ts_product_filter_by_availability_load_widget');

function ts_product_filter_by_availability_load_widget()
{
	register_widget('TS_Product_Filter_By_Availability_Widget');
}

class TS_Product_Filter_By_Availability_Widget extends WP_Widget{

	function __construct(){
		$widgetOps = array('classname' => 'product-filter-by-availability', 'description' => esc_html__('Filter in stock or out of stock products', 'themesky'));
		parent::__construct('ts_product_filter_by_availability', esc_html__('TS - Product Filter By Availability', 'themesky'), $widgetOps);
		
		add_filter('woocommerce_product_query', array($this, 'woocommerce_product_query'), 9999);
	}
	
	function woocommerce_product_query( $query ){
		if( !empty($_GET['stock']) ){
			$tax_query = $query->get('tax_query');
			if( $_GET['stock'] == 'instock' ){
				$tax_query[] = array(
					'taxonomy'  => 'product_visibility'
					,'field'    => 'name'
					,'terms'    => 'outofstock'
					,'operator' => 'NOT IN'
				);
			}
			if( $_GET['stock'] == 'outofstock' ){
				$tax_query[] = array(
					'taxonomy'  => 'product_visibility'
					,'field'    => 'name'
					,'terms'    => 'outofstock'
					,'operator' => 'IN'
				);
			}
			$query->set('tax_query', $tax_query);
		}
		
        return $query;
	}
	
	function widget( $args, $instance ) {
		global $wp, $wp_the_query;
		extract($args);
		
		if( !class_exists('WooCommerce') ){
			return;
		}
		if( !is_post_type_archive( 'product' ) && !is_tax( get_object_taxonomies( 'product' ) ) ){
			return;
		}
		
		if( ! $wp_the_query->post_count ){
			return;
		}
		
		$title = apply_filters('widget_title', $instance['title']);
		
		if ( '' == get_option( 'permalink_structure' ) ) {
			$form_action = remove_query_arg( array( 'page', 'paged' ), add_query_arg( $wp->query_string, '', home_url( $wp->request ) ) );
		} else {
			$form_action = preg_replace( '%\/page/[0-9]+%', '', home_url( trailingslashit( $wp->request ) ) );
		}
		
		echo $before_widget;
			
		if( $title ){
			echo $before_title . $title . $after_title;
		}
		
		$is_instock = isset($_GET['stock']) && $_GET['stock'] == 'instock';
		$is_outofstock = isset($_GET['stock']) && $_GET['stock'] == 'outofstock';
		
		?>
		<div class="product-filter-by-availability-wrapper">
			<ul>
				<li class="<?php echo $is_instock?'selected':''; ?>">
					<input type="checkbox" id="ts-instock-checkbox" value="instock" <?php checked('instock', !empty($_GET['stock'])?$_GET['stock']:'', true) ?> />
					<label for="ts-instock-checkbox"><?php esc_html_e('In stock', 'themesky'); ?></label>
				</li>
				<li class="<?php echo $is_outofstock?'selected':''; ?>">
					<input type="checkbox" id="ts-outofstock-checkbox" value="outofstock" <?php checked('outofstock', !empty($_GET['stock'])?$_GET['stock']:'', true) ?> />
					<label for="ts-outofstock-checkbox"><?php esc_html_e('Out of stock', 'themesky'); ?></label>
				</li>
			</ul>
			
			<form method="get" action="<?php echo esc_url($form_action) ?>">
				<input type="hidden" name="stock" value="" />
				<?php wc_query_string_form_fields( null, array( 'stock', 'submit', 'paged', 'product-page' ) ); ?>
			</form>
		</div>
		<?php
		echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;		
		$instance['title'] = strip_tags($new_instance['title']);
		return $instance;
	}

	function form( $instance ) {
		
		$defaults = array(
			'title' 		=> 'Availability'
		);
	
		$instance = wp_parse_args( (array) $instance, $defaults );
	?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php esc_html_e('Title:', 'themesky'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($instance['title']); ?>" />
		</p>	
		<?php 
	}
	
}
?>