<?php
add_action('widgets_init', 'ts_products_load_widgets');

function ts_products_load_widgets()
{
	register_widget('TS_Products_Widget');
}

if( !class_exists('TS_Products_Widget') ){
	class TS_Products_Widget extends WP_Widget {

		function __construct() {
			$widgetOps = array('classname' => 'ts-products-widget', 'description' => esc_html__('Display your products on site','themesky'));
			parent::__construct('ts_products', esc_html__('TS - Products','themesky'), $widgetOps);
		}

		function widget( $args, $instance ) {
			
			if( !class_exists('WooCommerce') ){
				return;
			}
			
			extract($args);

			$defaults = $this->get_default_values();
			
			$instance = wp_parse_args( $instance, $defaults );
			
			extract($instance);
			
			$title = apply_filters( 'widget_title', $title );
			
			$product_cats = is_array( $product_cats ) ? $product_cats : array();
			
			if( $limit == $row ){
				$is_slider = false;
			}
			
			$args = array(
				'post_type'				=> 'product'
				,'post_status' 			=> 'publish'
				,'posts_per_page' 		=> $limit
				,'orderby' 				=> 'date'
				,'order' 				=> 'desc'
				,'meta_query' 			=> WC()->query->get_meta_query()
				,'tax_query'           	=> WC()->query->get_tax_query()
			);
			
			switch( $product_type ){
				case 'sale':
					$args['post__in'] = array_merge( array( 0 ), wc_get_product_ids_on_sale() );
				break;
				case 'featured':
					$args['tax_query'][] = array(
						'taxonomy' => 'product_visibility',
						'field'    => 'name',
						'terms'    => 'featured',
						'operator' => 'IN',
					);
				break;
				case 'best_selling':
					$args['meta_key'] 	= 'total_sales';
					$args['orderby'] 	= 'meta_value_num';
					$args['order'] 		= 'desc';
				break;
				case 'top_rated':		
					$args['meta_key'] 	= '_wc_average_rating';
					$args['orderby'] 	= 'meta_value_num';
					$args['order'] 		= 'desc';
				break;
				case 'mixed_order':
					$args['orderby'] 	= 'rand';
				break;
				default: /* Recent */
					$args['orderby'] 	= 'date';
					$args['order'] 		= 'desc';
				break;
			}
			
			if( is_array($product_cats) && count($product_cats) > 0 ){
				$args['tax_query'][] = array(
											'taxonomy' 	=> 'product_cat'
											,'terms' 	=> $product_cats
											,'field' 	=> 'term_id'
										);
			}
			
			global $post, $product;
			
			$lazy_load = !wp_doing_ajax() && function_exists('ecomall_get_theme_options')?ecomall_get_theme_options('ts_prod_lazy_load'):false;
			$placeholder_img_src = $lazy_load && function_exists('ecomall_get_theme_options')?ecomall_get_theme_options('ts_prod_placeholder_img')['url']:'';
			
			$products = new WP_Query($args);
			if( $products->have_posts() ){
				$count = 0;
				$num_posts = $products->post_count;
				if( $num_posts <= $row ){
					$is_slider = false;
				}
				if( !$is_slider ){
					$row = $num_posts;
				}
				
				if( $is_slider && $show_nav ){
					$before_widget = str_replace('ts-products-widget', 'ts-products-widget has-nav', $before_widget);
				}
				
				echo $before_widget;
				if( $title ){
					echo $before_title . $title . $after_title;
				}
				
				$classes = array();
				$classes[] = 'ts-products-widget-wrapper woocommerce';
				$classes[] = $is_slider?'ts-slider loading':'';
				$classes = array_filter($classes);
				?>
				
				<div class="<?php echo esc_attr(implode(' ', $classes)); ?>" data-nav="<?php echo esc_attr($show_nav) ?>" data-autoplay="<?php echo esc_attr($auto_play) ?>">
					<?php while( $products->have_posts() ): $products->the_post(); $product = wc_get_product( $post->ID ); ?>
						<?php if( $count % $row == 0 ): ?>
						<div class="per-slide">
							<ul class="product_list_widget">
						<?php endif; ?>
							<li>
								<a class="ts-wg-thumbnail" href="<?php echo esc_url( get_permalink($product->get_id()) ); ?>" title="<?php echo esc_attr( $product->get_title() ); ?>">
									<?php  
										if( $show_label && function_exists( 'ecomall_template_loop_product_label' ) ){
											ecomall_template_loop_product_label();
										}

										if ( $show_thumbnail ) {
											if( !$lazy_load ){
												echo $product->get_image();
											}
											else{
												$image_size = 'woocommerce_thumbnail';
												$img_src = '';
												$alt = '';
												$dimensions = wc_get_image_size( $image_size );
												if( has_post_thumbnail($product->get_id()) ){
													$post_thumbnail_id = get_post_thumbnail_id($product->get_id());
													$image_obj = wp_get_attachment_image_src($post_thumbnail_id, $image_size, 0);
													if( isset($image_obj[0]) ){
														$img_src = $image_obj[0];
													}
													$alt = trim(strip_tags( get_post_meta($post_thumbnail_id, '_wp_attachment_image_alt', true) ));
												}
												else if( wc_placeholder_img_src() ){
													$img_src = wc_placeholder_img_src();
												}
												
												echo '<img src="'.esc_url($placeholder_img_src).'" data-src="'.esc_url($img_src).'" alt="'.esc_attr($alt).'" class="attachment-shop_thumbnail wp-post-image ts-lazy-load" width="'.$dimensions['width'].'" height="'.$dimensions['height'].'" />';
											}
										}
									?>
								</a>
								
								<div class="ts-wg-meta">
									<?php 
										if( $show_brand && function_exists('ecomall_template_loop_brands') ) {
											ecomall_template_loop_brands();
										}
									?>
									<?php
									if( $show_categories && function_exists('ecomall_template_loop_categories') ){ 
										ecomall_template_loop_categories(); 
									}
									?>
									<?php if( $show_product_title ){ ?>
									<a href="<?php echo esc_url( $product->get_permalink() ); ?>" title="<?php echo esc_attr( $product->get_title() ); ?>">
										<?php echo esc_html( $product->get_title() ); ?>
									</a>
									<?php } ?>
									<?php
									if( $show_rating && function_exists('ecomall_template_star_rating') ){
										ecomall_template_star_rating();
									}
									
									if( $show_price ){
										woocommerce_template_loop_price();
									}
									
									if( $show_add_to_cart ){
									?>
									<div class="loop-add-to-cart">
									<?php woocommerce_template_loop_add_to_cart(); ?>
									</div>
									<?php } ?>
								</div>
							</li>
						<?php if( $count % $row == $row - 1 || $count == $num_posts - 1 ): ?>	
							</ul>
						</div>
						<?php endif; ?>
					<?php $count++; endwhile; ?>
				</div>
				<?php
				echo $after_widget;
			}
			
			wp_reset_postdata();
		}

		function update( $new_instance, $old_instance ) {
			$instance = $old_instance;		
			$instance['title'] 				= strip_tags($new_instance['title']);
			$instance['product_type'] 		= $new_instance['product_type'];
			$instance['product_cats'] 		= isset($new_instance['product_cats']) ? $new_instance['product_cats'] : array();
			$instance['row'] 				= absint($new_instance['row']);
			$instance['limit'] 				= absint($new_instance['limit']);
			$instance['show_thumbnail'] 	= empty($new_instance['show_thumbnail']) ? 0 : 1;
			$instance['show_categories'] 	= empty($new_instance['show_categories']) ? 0 : 1;
			$instance['show_product_title'] = empty($new_instance['show_product_title']) ? 0 : 1;
			$instance['show_price'] 		= empty($new_instance['show_price']) ? 0 : 1;
			$instance['show_rating'] 		= empty($new_instance['show_rating']) ? 0 : 1;
			$instance['show_brand'] 		= empty($new_instance['show_brand']) ? 0 : 1;
			$instance['show_label'] 		= empty($new_instance['show_label']) ? 0 : 1;
			$instance['show_add_to_cart'] 	= empty($new_instance['show_add_to_cart']) ? 0 : 1;
			$instance['is_slider'] 			= empty($new_instance['is_slider']) ? 0 : 1;
			$instance['show_nav'] 			= empty($new_instance['show_nav']) ? 0 : 1;
			$instance['auto_play'] 			= empty($new_instance['auto_play']) ? 0 : 1;
			
			if( $instance['row'] > $instance['limit'] ){
				$instance['row'] = $instance['limit'];
			}
			return $instance;
		}
		
		function get_default_values(){
			return array(
						'title'					=> 'Recent Products'
						,'product_type'			=> 'recent'
						,'product_cats'			=> array()
						,'row'					=> '4'
						,'limit'				=> '8'
						,'show_thumbnail' 		=> 1
						,'show_categories' 		=> 0
						,'show_product_title' 	=> 1
						,'show_price' 			=> 1
						,'show_rating' 			=> 1
						,'show_brand' 			=> 1
						,'show_label' 			=> 1
						,'show_add_to_cart'		=> 0
						,'is_slider'			=> 0
						,'show_nav' 			=> 1
						,'auto_play' 			=> 1
					);
		}

		function form( $instance ) {
			$defaults = $this->get_default_values();
		
			$instance = wp_parse_args( (array) $instance, $defaults );	
			
			$categories = $this->get_list_categories(0);
			if( !is_array($instance['product_cats']) ){
				$instance['product_cats'] = array();
			}
			
		?>
			<p>
				<label for="<?php echo $this->get_field_id('title'); ?>"><?php esc_html_e('Enter your title', 'themesky'); ?> </label>
				<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($instance['title']); ?>" />
			</p>
			
			<p>
				<label for="<?php echo $this->get_field_id('product_type'); ?>"><?php esc_html_e('Product type', 'themesky'); ?> </label>
				<select class="widefat" id="<?php echo $this->get_field_id('product_type'); ?>" name="<?php echo $this->get_field_name('product_type'); ?>">
					<option value="recent" <?php selected($instance['product_type'], 'recent'); ?>><?php esc_html_e('Recent', 'themesky'); ?></option>
					<option value="sale" <?php selected($instance['product_type'], 'sale'); ?>><?php esc_html_e('Sale', 'themesky'); ?></option>
					<option value="featured" <?php selected($instance['product_type'], 'featured'); ?>><?php esc_html_e('Featured', 'themesky'); ?></option>
					<option value="best_selling" <?php selected($instance['product_type'], 'best_selling'); ?>><?php esc_html_e('Best selling', 'themesky'); ?></option>
					<option value="top_rated" <?php selected($instance['product_type'], 'top_rated'); ?>><?php esc_html_e('Top rated', 'themesky'); ?></option>
					<option value="mixed_order" <?php selected($instance['product_type'], 'mixed_order'); ?>><?php esc_html_e('Mixed order', 'themesky'); ?></option>
				</select>
			</p>
			
			<p>
				<label><?php esc_html_e('Select categories', 'themesky'); ?></label>
				<div class="categorydiv">
					<div class="tabs-panel">
						<ul class="categorychecklist">
							<?php foreach($categories as $cat){ ?>
							<li>
								<label>
									<input type="checkbox" name="<?php echo $this->get_field_name('product_cats'); ?>[<?php esc_attr($cat->term_id); ?>]" value="<?php echo esc_attr($cat->term_id); ?>" <?php echo (in_array($cat->term_id,$instance['product_cats']))?'checked':''; ?> />
									<?php echo esc_html($cat->name); ?>
								</label>
								<?php $this->get_list_sub_categories($cat->term_id, $instance); ?>
							</li>
							<?php } ?>
						</ul>
					</div>
				</div>
			</p>
			
			<p>
				<label for="<?php echo $this->get_field_id('row'); ?>"><?php esc_html_e('Number of rows - in carousel slider', 'themesky'); ?> </label>
				<input class="widefat" id="<?php echo $this->get_field_id('row'); ?>" name="<?php echo $this->get_field_name('row'); ?>" type="number" min="0" value="<?php echo esc_attr($instance['row']); ?>" />
			</p>
			
			<p>
				<label for="<?php echo $this->get_field_id('limit'); ?>"><?php esc_html_e('Number of posts to show', 'themesky'); ?> </label>
				<input class="widefat" id="<?php echo $this->get_field_id('limit'); ?>" name="<?php echo $this->get_field_name('limit'); ?>" type="number" min="0" value="<?php echo esc_attr($instance['limit']); ?>" />
			</p>
			
			<p>
				<input type="checkbox" id="<?php echo $this->get_field_id('show_thumbnail'); ?>" name="<?php echo $this->get_field_name('show_thumbnail'); ?>" value="1" <?php echo ($instance['show_thumbnail'])?'checked':''; ?> />
				<label for="<?php echo $this->get_field_id('show_thumbnail'); ?>"><?php esc_html_e('Show thumbnail', 'themesky'); ?></label>
			</p>
			
			<p>
				<input type="checkbox" id="<?php echo $this->get_field_id('show_categories'); ?>" name="<?php echo $this->get_field_name('show_categories'); ?>" value="1" <?php echo ($instance['show_categories'])?'checked':''; ?> />
				<label for="<?php echo $this->get_field_id('show_categories'); ?>"><?php esc_html_e('Show categories', 'themesky'); ?></label>
			</p>
			
			<p>
				<input type="checkbox" id="<?php echo $this->get_field_id('show_product_title'); ?>" name="<?php echo $this->get_field_name('show_product_title'); ?>" value="1" <?php echo ($instance['show_product_title'])?'checked':''; ?> />
				<label for="<?php echo $this->get_field_id('show_product_title'); ?>"><?php esc_html_e('Show product title', 'themesky'); ?></label>
			</p>
			
			<p>
				<input type="checkbox" id="<?php echo $this->get_field_id('show_price'); ?>" name="<?php echo $this->get_field_name('show_price'); ?>" value="1" <?php echo ($instance['show_price'])?'checked':''; ?> />
				<label for="<?php echo $this->get_field_id('show_price'); ?>"><?php esc_html_e('Show price', 'themesky'); ?></label>
			</p>
			
			<p>
				<input type="checkbox" id="<?php echo $this->get_field_id('show_rating'); ?>" name="<?php echo $this->get_field_name('show_rating'); ?>" value="1" <?php echo ($instance['show_rating'])?'checked':''; ?> />
				<label for="<?php echo $this->get_field_id('show_rating'); ?>"><?php esc_html_e('Show rating', 'themesky'); ?></label>
			</p>

			<p>
				<input type="checkbox" id="<?php echo $this->get_field_id('show_brand'); ?>" name="<?php echo $this->get_field_name('show_brand'); ?>" value="1" <?php echo ($instance['show_brand'])?'checked':''; ?> />
				<label for="<?php echo $this->get_field_id('show_brand'); ?>"><?php esc_html_e('Show brand', 'themesky'); ?></label>
			</p>

			<p>
				<input type="checkbox" id="<?php echo $this->get_field_id('show_label'); ?>" name="<?php echo $this->get_field_name('show_label'); ?>" value="1" <?php echo ($instance['show_label'])?'checked':''; ?> />
				<label for="<?php echo $this->get_field_id('show_label'); ?>"><?php esc_html_e('Show label', 'themesky'); ?></label>
			</p>

			<p>
				<input type="checkbox" id="<?php echo $this->get_field_id('show_add_to_cart'); ?>" name="<?php echo $this->get_field_name('show_add_to_cart'); ?>" value="1" <?php echo ($instance['show_add_to_cart'])?'checked':''; ?> />
				<label for="<?php echo $this->get_field_id('show_add_to_cart'); ?>"><?php esc_html_e('Show add to cart', 'themesky'); ?></label>
			</p>
			
			<hr/>
			
			<p>
				<input type="checkbox" id="<?php echo $this->get_field_id('is_slider'); ?>" name="<?php echo $this->get_field_name('is_slider'); ?>" value="1" <?php echo ($instance['is_slider'])?'checked':''; ?> />
				<label for="<?php echo $this->get_field_id('is_slider'); ?>"><?php esc_html_e('Show in a carousel slider', 'themesky'); ?></label>
			</p>
			
			<p>
				<input type="checkbox" id="<?php echo $this->get_field_id('show_nav'); ?>" name="<?php echo $this->get_field_name('show_nav'); ?>" value="1" <?php echo ($instance['show_nav'])?'checked':''; ?> />
				<label for="<?php echo $this->get_field_id('show_nav'); ?>"><?php esc_html_e('Show navigation button', 'themesky'); ?></label>
			</p>
			
			<p>
				<input type="checkbox" id="<?php echo $this->get_field_id('auto_play'); ?>" name="<?php echo $this->get_field_name('auto_play'); ?>" value="1" <?php echo ($instance['auto_play'])?'checked':''; ?> />
				<label for="<?php echo $this->get_field_id('auto_play'); ?>"><?php esc_html_e('Auto play', 'themesky'); ?></label>
			</p>
			
			<?php 
		}
		
		function get_list_categories( $cat_parent_id ){
			if ( !class_exists('WooCommerce') ) {
				return array();
			}
			$args = array(
					'taxonomy' 			=> 'product_cat'
					,'hierarchical'		=> 1
					,'parent'			=> $cat_parent_id
					,'title_li'			=> ''
					,'child_of'			=> 0
				);
			$cats = get_categories($args);
			return $cats;
		}
		
		function get_list_sub_categories( $cat_parent_id, $instance ){
			$sub_categories = $this->get_list_categories($cat_parent_id); 
			if( count($sub_categories) > 0){
			?>
				<ul class="children">
					<?php foreach( $sub_categories as $sub_cat ){ ?>
						<li>
							<label>
								<input type="checkbox" name="<?php echo $this->get_field_name('product_cats'); ?>[<?php esc_attr($sub_cat->term_id); ?>]" value="<?php echo esc_attr($sub_cat->term_id); ?>" <?php echo (in_array($sub_cat->term_id,$instance['product_cats']))?'checked':''; ?> />
								<?php echo esc_html($sub_cat->name); ?>
							</label>
							<?php $this->get_list_sub_categories($sub_cat->term_id, $instance); ?>
						</li>
					<?php } ?>
				</ul>
			<?php }
		}
		
	}
}

