<?php 
add_action('widgets_init', 'ts_product_filter_by_color_load_widget');

function ts_product_filter_by_color_load_widget()
{
	register_widget('TS_Product_Filter_By_Color_Widget');
}

class TS_Product_Filter_By_Color_Widget extends WP_Widget{

	function __construct(){
		$widgetOps = array('classname' => 'product-filter-by-color', 'description' => esc_html__('Shows list of product colors which let you filter product when viewing product categories. You have to have product attribute with slug "color"', 'themesky'));
		parent::__construct('ts_product_filter_by_color', esc_html__('TS - Product Filter By Color','themesky'), $widgetOps);
	}
	
	function widget( $args, $instance ) {
		extract($args);
		
		if( !class_exists('WooCommerce') ){
			return;
		}
		if( !is_post_type_archive( 'product' ) && !is_tax( get_object_taxonomies( 'product' ) ) ){
			return;
		}
		
		$title = apply_filters('widget_title', $instance['title']);
		$query_type = $instance['query_type'];
		$show_post_count = empty($instance['show_post_count']) ? 0 : 1;
		
		$_chosen_attributes = WC_Query::get_layered_nav_chosen_attributes();
		
		$current_term_id = is_tax() ? get_queried_object()->term_id : '';
		$current_term_slug = is_tax() ? get_queried_object()->slug : '';
		
		$attr_slug = 'color';
		$taxonomy  = wc_attribute_taxonomy_name($attr_slug);
		if ( !taxonomy_exists($taxonomy) ){
			return;
		}
	
		$term_args = array(
			'taxonomy' 		=> $taxonomy
			,'hide_empty'	=> 1
		);

		$terms = get_terms( $term_args );

		if ( count($terms) > 0 ) {
			ob_start();
			$found = false;
			
			echo $before_widget;
			
			if( $title ){
				echo $before_title . $title . $after_title;
			}
			
			/* Force found when option is selected - do not force found on taxonomy attributes */
			if ( ! is_tax() && is_array( $_chosen_attributes ) && array_key_exists( $taxonomy, $_chosen_attributes ) ) {
				$found = true;
			}
			
			$term_counts        = $this->get_filtered_term_product_counts( wp_list_pluck( $terms, 'term_id' ), $taxonomy, $query_type );
			
			/* List display */
			echo "<ul>";

			foreach ( $terms as $term ) {

				/* Get count based on current view - uses transients */
				$current_values    = isset( $_chosen_attributes[ $taxonomy ]['terms'] ) ? $_chosen_attributes[ $taxonomy ]['terms'] : array();
				$option_is_set     = in_array( $term->slug, $current_values );

				$count = isset( $term_counts[ $term->term_id ] ) ? $term_counts[ $term->term_id ] : 0;

				// skip the term for the current archive
				if ( $current_term_id === $term->term_id ) {
					continue;
				}
				
				// Only show options with count > 0
				if ( 0 < $count ) {
					$found = true;
				} elseif ( 0 === $count && ! $option_is_set ) {
					continue;
				}

				$filter_name = 'filter_' . sanitize_title( $attr_slug );
				
				$current_filter = isset( $_GET[ $filter_name ] ) ? explode( ',', wc_clean( $_GET[ $filter_name ] ) ) : array();
				$current_filter = array_map( 'sanitize_title', $current_filter );
				
				if ( ! in_array( $term->slug, $current_filter ) ) {
					$current_filter[] = $term->slug;
				}

				$link = $this->get_page_base_url( $taxonomy );
				
				// Add current filters to URL.
				foreach ( $current_filter as $key => $value ) {
					// Exclude query arg for current term archive term
					if ( $value === $current_term_slug ) {
						unset( $current_filter[ $key ] );
					}

					// Exclude self so filter can be unset on click.
					if ( $option_is_set && $value === $term->slug ) {
						unset( $current_filter[ $key ] );
					}
				}
				
				if ( ! empty( $current_filter ) ) {
					$link = add_query_arg( $filter_name, implode( ',', $current_filter ), $link );

					// Add Query type Arg to URL
					if ( $query_type === 'or' && ! ( 1 === sizeof( $current_filter ) && $option_is_set ) ) {
						$link = add_query_arg( 'query_type_' . sanitize_title( str_replace( 'pa_', '', $taxonomy ) ), 'or', $link );
					}
				}

				$datas = get_term_meta( $term->term_id, 'ts_product_color_config', true );
				if( strlen($datas) > 0 ){
					$datas = unserialize($datas);	
				}else{
					$datas = array(
								'ts_color_color' 				=> "#ffffff"
								,'ts_color_image' 				=> 0
							);
			
				}						
					
				echo '<li class="' . ( $option_is_set ? 'chosen' : '' ) . '">';

				echo ( $count > 0 || $option_is_set ) ? '<a title="'. $term->name .'" href="' . esc_url($link) . '">' : '<span>';
				
				if( absint($datas['ts_color_image']) > 0 ){
					echo wp_get_attachment_image( absint($datas['ts_color_image']), 'ts_prod_color_thumb', true, array('title'=>$term->name, 'alt'=>$term->name) );
				}else{
					echo "<span style='background-color:{$datas['ts_color_color']}'></span>";
				}

				echo ( $count > 0 || $option_is_set ) ? '</a>' : '</span>';
				
				if( $show_post_count ){
					echo ' <small class="count">(' . $count . ')</small>';
				}
				
				echo '</li>';

			}

			echo "</ul>";
			
			echo $after_widget;
			
			if( $found ){
				echo ob_get_clean();
			}
			else{
				ob_end_clean();
			}
		}
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;		
		$instance['title'] 				= strip_tags($new_instance['title']);
		$instance['query_type'] 		= $new_instance['query_type'];	
		$instance['show_post_count'] 	= empty($new_instance['show_post_count']) ? 0 : 1;
		return $instance;
	}

	function form( $instance ) {
		
		$defaults = array(
			'title' 			=> 'Filter By Color' 
			,'query_type' 		=> 'and'
			,'show_post_count'	=> 0
		);
	
		$instance = wp_parse_args( (array) $instance, $defaults );
		
		extract( $instance );
	?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php esc_html_e('Title', 'themesky'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('query_type'); ?>"><?php esc_html_e('Query type', 'themesky'); ?></label>
			<select class="widefat" id="<?php echo $this->get_field_id('query_type'); ?>" name="<?php echo $this->get_field_name('query_type'); ?>">
				<option value="and" <?php selected($query_type, 'and'); ?>><?php esc_html_e( 'AND', 'themesky' ); ?></option>
				<option value="or" <?php selected($query_type, 'or'); ?>><?php esc_html_e( 'OR', 'themesky' ); ?></option>
			</select>
		</p>
		<p>
			<input type="checkbox" value="1" id="<?php echo $this->get_field_id('show_post_count'); ?>" name="<?php echo $this->get_field_name('show_post_count'); ?>" <?php checked($show_post_count, 1); ?> />
			<label for="<?php echo $this->get_field_id('show_post_count'); ?>"><?php esc_html_e('Show product count', 'themesky'); ?></label>
		</p>		
		<?php 
	}
	
	/**
	 * Count products within certain terms, taking the main WP query into consideration.
	 * @param  array $term_ids
	 * @param  string $taxonomy
	 * @param  string $query_type
	 * @return array
	 */
	protected function get_filtered_term_product_counts( $term_ids, $taxonomy, $query_type ) {
		global $wpdb;

		$tax_query  = WC_Query::get_main_tax_query();
		$meta_query = WC_Query::get_main_meta_query();

		if ( 'or' === $query_type ) {
			foreach ( $tax_query as $key => $query ) {
				if ( is_array( $query ) && $taxonomy === $query['taxonomy'] ) {
					unset( $tax_query[ $key ] );
				}
			}
		}

		$meta_query      = new WP_Meta_Query( $meta_query );
		$tax_query       = new WP_Tax_Query( $tax_query );
		$meta_query_sql  = $meta_query->get_sql( 'post', $wpdb->posts, 'ID' );
		$tax_query_sql   = $tax_query->get_sql( $wpdb->posts, 'ID' );

		// Generate query
		$query           = array();
		$query['select'] = "SELECT COUNT( DISTINCT {$wpdb->posts}.ID ) as term_count, terms.term_id as term_count_id";
		$query['from']   = "FROM {$wpdb->posts}";
		$query['join']   = "
			INNER JOIN {$wpdb->term_relationships} AS term_relationships ON {$wpdb->posts}.ID = term_relationships.object_id
			INNER JOIN {$wpdb->term_taxonomy} AS term_taxonomy USING( term_taxonomy_id )
			INNER JOIN {$wpdb->terms} AS terms USING( term_id )
			" . $tax_query_sql['join'] . $meta_query_sql['join'];
		$query['where']   = "
			WHERE {$wpdb->posts}.post_type IN ( 'product' )
			AND {$wpdb->posts}.post_status = 'publish'
			" . $tax_query_sql['where'] . $meta_query_sql['where'] . "
			AND terms.term_id IN (" . implode( ',', array_map( 'absint', $term_ids ) ) . ")
		";
		
		$search = WC_Query::get_main_search_query_sql();
		if ( $search ) {
			$query['where'] .= ' AND ' . $search;
		}
		
		$query['group_by'] = "GROUP BY terms.term_id";
		$query             = apply_filters( 'woocommerce_get_filtered_term_product_counts_query', $query );
		$query             = implode( ' ', $query );
		
		// We have a query - let's see if cached results of this query already exist.
		$query_hash    = md5( $query );

		// Maybe store a transient of the count values.
		$cache = apply_filters( 'woocommerce_layered_nav_count_maybe_cache', true );
		if ( true === $cache ) {
			$cached_counts = (array) get_transient( 'wc_layered_nav_counts_' . $taxonomy );	
		} else {
			$cached_counts = array();
		}

		if ( ! isset( $cached_counts[ $query_hash ] ) ) {
			$results                      = $wpdb->get_results( $query, ARRAY_A );
			$counts                       = array_map( 'absint', wp_list_pluck( $results, 'term_count', 'term_count_id' ) );
			$cached_counts[ $query_hash ] = $counts;
			if ( true === $cache ) {
				set_transient( 'wc_layered_nav_counts_' . $taxonomy, $cached_counts, DAY_IN_SECONDS );
			}
		}

		return array_map( 'absint', (array) $cached_counts[ $query_hash ] );
	}
	
	/**
	 * Get current page URL for layered nav items.
	 * @return string
	 */
	protected function get_page_base_url( $taxonomy ) {
		if ( defined( 'SHOP_IS_ON_FRONT' ) ) {
			$link = home_url();
		} elseif ( is_post_type_archive( 'product' ) || is_page( wc_get_page_id( 'shop' ) ) ) {
			$link = get_post_type_archive_link( 'product' );
		} elseif ( is_product_category() ) {
			$link = get_term_link( get_query_var( 'product_cat' ), 'product_cat' );
		} elseif ( is_product_tag() ) {
			$link = get_term_link( get_query_var( 'product_tag' ), 'product_tag' );
		} else {
			$link = get_term_link( get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
		}
		
		if( !empty($_GET) ){
			$get = $_GET;
			unset($get['filter_color'], $get['query_type_color']);
			if( !empty($get) ){
				$link = add_query_arg( $get, $link );
			}
		}

		return $link;
	}
	
}
?>