<?php 
add_action('widgets_init', 'ts_product_filter_by_brand_load_widget');

function ts_product_filter_by_brand_load_widget()
{
	register_widget('TS_Product_Filter_By_Brand_Widget');
}

if( !class_exists('TS_Product_Filter_By_Brand_Widget') ){
	class TS_Product_Filter_By_Brand_Widget extends WP_Widget{

		function __construct(){
			$widgetOps = array('classname' => 'product-filter-by-brand', 'description' => esc_html__('Filter by product brand. This widget does not appear on the Brand page', 'themesky'));
			parent::__construct('ts_product_filter_by_brand', esc_html__('TS - Product Filter By Brand', 'themesky'), $widgetOps);
			
			add_filter('woocommerce_product_query', array($this, 'woocommerce_product_query'), 9999);
		}
		
		function woocommerce_product_query( $query ){
			if( !empty($_GET['product_brand']) ){
				$brands = array_map('absint', explode(',', $_GET['product_brand']));
				$tax_query = $query->get('tax_query');
				$tax_query[] = array(
					'taxonomy' 			=> 'ts_product_brand'
					,'terms' 			=> $brands
					,'include_children' => true
				);
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
			if( is_tax('ts_product_brand') ){
				return;
			}
			if( !$wp_the_query->post_count && $instance['based_on_selection'] ){
				return;
			}
			
			$title 				= apply_filters('widget_title', $instance['title']);
			$query_type 		= $instance['query_type'];
			
			$form_action = '';
			$cat 	= get_queried_object();
			if( isset( $cat->term_id ) && isset( $cat->taxonomy ) ){
				$form_action = get_term_link( $cat->term_id, $cat->taxonomy );
			}
			else{
				$form_action = wc_get_page_permalink('shop');
			}

			echo $before_widget;
				
			if( $title ){
				echo $before_title . $title . $after_title;
			}
			
			$selected_brands = isset($_GET['product_brand'])?array_map('absint', explode(',', $_GET['product_brand'])):array();
			?>
			<div class="product-filter-by-brand-wrapper">
				<?php $this->brand_filter_html(0, $selected_brands, $instance); ?>
				
				<input type="hidden" class="query-type" value="<?php echo esc_attr($query_type) ?>" />
				
				<form method="get" action="<?php echo esc_url($form_action) ?>">
					<input type="hidden" name="product_brand" value="" />
					<?php wc_query_string_form_fields( null, array( 'product_brand', 'submit', 'paged', 'product-page' ) ); ?>
				</form>
			</div>
			<?php
			echo $after_widget;
		}
		
		function brand_filter_html( $parent_brand_id, $selected_brands, $instance ){
			$query_type 		= $instance['query_type'];
			$show_post_count 	= $instance['show_post_count'];
			$hide_empty 		= $instance['hide_empty'];
			$based_on_selection = $instance['based_on_selection'];
			$orderby 			= $instance['orderby'];
			$order 				= $instance['order'];
			$include_brand		= isset( $instance['include_brand'] ) ? $instance['include_brand'] : array();
			
			$args = array(
				'taxonomy'     	=> 'ts_product_brand'
				,'orderby'      => $orderby
				,'order'        => $order
				,'hide_empty'   => $hide_empty
				,'parent'		=> $parent_brand_id
				,'include'		=> $include_brand
			);
			
			$brands = get_categories( $args );
			
			if( $brands && $based_on_selection ){
				$term_counts = $this->get_filtered_term_product_counts( wp_list_pluck( $brands, 'term_id' ), 'ts_product_brand', $query_type );
				$term_ids_in_selection = array_keys($term_counts);
				foreach( $brands as $key => $brand ){
					if( !in_array($brand->term_id, $term_ids_in_selection) ){
						unset( $brands[$key] );
					}
				}
			}
			
			if( $brands ){
			?>
			<ul <?php echo ($parent_brand_id !== 0 )?'class="children"':''; ?>>
				<?php foreach( $brands as $brand ): ?>
				<?php $selected = in_array($brand->term_id, $selected_brands); ?>
				<li <?php echo ($selected)?'class="selected"':''; ?>>
					<input type="checkbox" id="ts-product-brand-<?php echo esc_attr($brand->term_id) ?>" value="<?php echo esc_attr($brand->term_id) ?>" <?php echo ($selected)?'checked="checked"':''; ?> />
					<label for="ts-product-brand-<?php echo esc_attr($brand->term_id) ?>">
						<?php 
						echo esc_html($brand->name);
						if( $show_post_count ): ?>
						<span class="count">(<?php echo !$based_on_selection?$brand->count:$term_counts[$brand->term_id]; ?>)</span>
						<?php endif; ?>
					</label>
					<?php $this->brand_filter_html($brand->term_id, $selected_brands, $instance); ?>
				</li>
				<?php endforeach; ?>
			</ul>
			<?php
			} elseif ( $parent_brand_id === 0 ){
			?>
				<p><?php esc_html_e('There is no brand', 'themesky'); ?></p>
			<?php
			}
		}
		
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
			
			if ( $search = WC_Query::get_main_search_query_sql() ) {
				$query['where'] .= ' AND ' . $search;
			}
			
			$query['group_by'] = "GROUP BY terms.term_id";
			$query             = apply_filters( 'woocommerce_get_filtered_term_product_counts_query', $query );
			$query             = implode( ' ', $query );
			$results           = $wpdb->get_results( $query );

			return wp_list_pluck( $results, 'term_count', 'term_count_id' );
		}

		function update( $new_instance, $old_instance ) {
			$instance = $old_instance;		
			$instance['title'] 				= strip_tags($new_instance['title']);
			$instance['query_type'] 		= $new_instance['query_type'];
			$instance['show_post_count'] 	= empty($new_instance['show_post_count']) ? 0 : 1;
			$instance['hide_empty'] 		= empty($new_instance['hide_empty']) ? 0 : 1;
			$instance['based_on_selection'] = empty($new_instance['based_on_selection']) ? 0 : 1;
			$instance['orderby'] 			= $new_instance['orderby'];
			$instance['order'] 				= $new_instance['order'];
			$instance['include_brand']		= isset($new_instance['include_brand']) ? $new_instance['include_brand'] : array();
			
			return $instance;
		}

		function form( $instance ) {
			$defaults = array(
				'title' 				=> 'Brands'
				,'query_type' 			=> 'and'
				,'show_post_count'		=> 1
				,'hide_empty'			=> 0
				,'based_on_selection'	=> 0
				,'orderby'				=> 'name'
				,'order'				=> 'asc'
				,'include_brand'		=> array()
			);
			$instance = wp_parse_args( (array) $instance, $defaults );

			$brands = $this->get_list_brands(0);

			if( !is_array($instance['include_brand']) ){
				$instance['include_brand'] = array();
			}
		?>
			<p>
				<label for="<?php echo $this->get_field_id('title'); ?>"><?php esc_html_e('Title', 'themesky'); ?></label>
				<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($instance['title']); ?>" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('query_type'); ?>"><?php esc_html_e('Query type', 'themesky'); ?></label>
				<select class="widefat" id="<?php echo $this->get_field_id('query_type'); ?>" name="<?php echo $this->get_field_name('query_type'); ?>">
					<option value="and" <?php selected($instance['query_type'], 'and'); ?>><?php esc_html_e( 'AND', 'themesky' ); ?></option>
					<option value="or" <?php selected($instance['query_type'], 'or'); ?>><?php esc_html_e( 'OR', 'themesky' ); ?></option>
				</select>
			</p>
			<p>
				<input type="checkbox" value="1" id="<?php echo $this->get_field_id('show_post_count'); ?>" name="<?php echo $this->get_field_name('show_post_count'); ?>" <?php checked($instance['show_post_count'], 1); ?> />
				<label for="<?php echo $this->get_field_id('show_post_count'); ?>"><?php esc_html_e('Show post count', 'themesky'); ?></label>
			</p>
			<p>
				<input type="checkbox" value="1" id="<?php echo $this->get_field_id('hide_empty'); ?>" name="<?php echo $this->get_field_name('hide_empty'); ?>" <?php checked($instance['hide_empty'], 1); ?> />
				<label for="<?php echo $this->get_field_id('hide_empty'); ?>"><?php esc_html_e('Hide empty brands', 'themesky'); ?></label>
			</p>
			<p>
				<input type="checkbox" value="1" id="<?php echo $this->get_field_id('based_on_selection'); ?>" name="<?php echo $this->get_field_name('based_on_selection'); ?>" <?php checked($instance['based_on_selection'], 1); ?> />
				<label for="<?php echo $this->get_field_id('based_on_selection'); ?>"><?php esc_html_e('Based on the current products', 'themesky'); ?></label>
				<div class="description clear"><?php esc_html_e('If this option is enabled, the empty brands are always hidden', 'themesky'); ?></div>
			</p>
			<p>
				<label><?php esc_html_e( 'Select brands', 'themesky' ); ?></label>
				<div class="categorydiv">
					<div class="tabs-panel">
						<ul class="categorychecklist">
							<?php foreach( $brands as $brand ){ ?>
							<li>
								<label>
									<input type="checkbox" name="<?php echo $this->get_field_name('include_brand'); ?>[<?php $brand->term_id; ?>]" value="<?php echo $brand->term_id; ?>" <?php echo (in_array($brand->term_id,$instance['include_brand']))?'checked':''; ?> />
									<?php echo esc_html($brand->name); ?>
								</label>
								<?php $this->get_list_sub_brands($brand->term_id, $instance); ?>
							</li>
							<?php } ?>
						</ul>
					</div>
				</div>
				<span class="description"><?php esc_html_e('Dont select to show all', 'themesky'); ?></span>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('orderby'); ?>"><?php esc_html_e('Order by', 'themesky'); ?></label>
				<select class="widefat" id="<?php echo $this->get_field_id('orderby'); ?>" name="<?php echo $this->get_field_name('orderby'); ?>" >
					<option value="name" <?php selected($instance['orderby'], 'name'); ?> ><?php esc_html_e('Name', 'themesky'); ?></option>
					<option value="slug" <?php selected($instance['orderby'], 'slug'); ?> ><?php esc_html_e('Slug', 'themesky'); ?></option>
					<option value="count" <?php selected($instance['orderby'], 'count'); ?> ><?php esc_html_e('Number product', 'themesky'); ?></option>
					<option value="none" <?php selected($instance['orderby'], 'none'); ?> ><?php esc_html_e('None', 'themesky'); ?></option>
				</select>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('order'); ?>"><?php esc_html_e('Order', 'themesky'); ?></label>
				<select class="widefat" id="<?php echo $this->get_field_id('order'); ?>" name="<?php echo $this->get_field_name('order'); ?>" >
					<option value="asc" <?php selected($instance['order'], 'asc'); ?> ><?php esc_html_e('Ascending', 'themesky'); ?></option>
					<option value="desc" <?php selected($instance['order'], 'desc'); ?> ><?php esc_html_e('Descending', 'themesky'); ?></option>
				</select>
			</p>
			<?php 
		}
		
		function get_list_brands( $parent_id ){
			if ( !class_exists('Woocommerce') ) {
				return array();
			}
			$args = array(
				'taxonomy'			=> 'ts_product_brand'
				,'parent'			=> $parent_id
				,'hierarchical'		=> 1
				,'title_li'			=> ''
				,'child_of'			=> 0
			);
			$brands = get_categories($args);
			return $brands;
		}

		function get_list_sub_brands( $brand_parent_id, $instance ){
			$sub_brands = $this->get_list_brands($brand_parent_id);
			if( count($sub_brands) > 0 ){
			?>
				<ul class="children">
					<?php foreach( $sub_brands as $sub_brand ){?>
						<li>
							<label>
								<input type="checkbox" name="<?php echo $this->get_field_name('include_brand'); ?>[<?php esc_attr($sub_brand->term_id); ?>]" value="<?php echo esc_attr($sub_brand->term_id); ?>" <?php echo (in_array($sub_brand->term_id,$instance['include_brand']))?'checked':''; ?> />
								<?php echo esc_html($sub_brand->name); ?>
							</label>
							<?php $this->get_list_sub_brands($sub_brand->term_id, $instance); ?>
						</li>
					<?php } ?>
				</ul>
			<?php 
			}
		}
	}
}
?>