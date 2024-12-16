<?php
/*************************************************
* WooCommerce Custom Hook                        *
**************************************************/

/*** Shop - Category ***/

/* Remove hook */
function ecomall_woocommerce_remove_shop_loop_default_hooks(){
	remove_action('woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
	remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0);

	remove_action('woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);

	remove_action('woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10 );
	remove_action('woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10);

	remove_action('woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10);

	remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10);
	remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5);

	remove_action('woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10);
	remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10);
	remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5);

	remove_action('woocommerce_before_subcategory', 'woocommerce_template_loop_category_link_open', 10);
	remove_action('woocommerce_after_subcategory', 'woocommerce_template_loop_category_link_close', 10);
}

ecomall_woocommerce_remove_shop_loop_default_hooks();

add_action('load-post.php', 'ecomall_woocommerce_remove_shop_loop_default_hooks', 20); /* Elementor editor */
/* Add new hook */
add_action('woocommerce_before_shop_loop_item_title', 'ecomall_template_loop_product_thumbnail', 10);

add_action('init', 'ecomall_add_loop_product_label_hook');
function ecomall_add_loop_product_label_hook( $label_pos = 'default' ){
	if( $label_pos == 'default' ){
		$label_pos = ecomall_get_theme_options('ts_product_label_pos');
	}
	
	if( $label_pos == 'after-thumbnail' ){
		remove_action('woocommerce_after_shop_loop_item_title', 'ecomall_template_loop_product_label', 1);
		add_action('woocommerce_after_shop_loop_item', 'ecomall_template_loop_product_label', 1);
	}
	else{
		remove_action('woocommerce_after_shop_loop_item', 'ecomall_template_loop_product_label', 1);
		add_action('woocommerce_after_shop_loop_item_title', 'ecomall_template_loop_product_label', 1);
	}
}

add_action('woocommerce_after_shop_loop_item', 'ecomall_template_loop_product_sku', 5);
add_action('woocommerce_after_shop_loop_item', 'ecomall_template_loop_categories', 10);
add_action('woocommerce_after_shop_loop_item', 'ecomall_template_loop_product_title', 15);
add_action('woocommerce_after_shop_loop_item', 'ecomall_template_loop_brands', 20);
add_action('woocommerce_after_shop_loop_item', 'ecomall_template_star_rating', 25);
add_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_price', 30);
add_action('woocommerce_after_shop_loop_item', 'ecomall_template_loop_short_description', 62);

add_action('woocommerce_before_shop_loop', 'ecomall_add_gridlist_toggle', 20);
add_action('woocommerce_before_shop_loop', 'ecomall_add_filter_button', 15);
add_action('woocommerce_before_shop_loop', 'ecomall_product_on_sale_form', 25);
add_action('woocommerce_before_shop_loop', 'ecomall_product_per_page_form', 60);

add_filter('loop_shop_per_page', 'ecomall_change_products_per_page_shop'); 

add_filter('loop_shop_post_in', 'ecomall_show_only_products_on_sales');

add_action('woocommerce_after_shop_loop', 'ecomall_shop_load_more_html', 20);

add_filter('woocommerce_get_stock_html', 'ecomall_empty_woocommerce_stock_html', 10, 2);

function ecomall_shop_top_product_categories(){
	if( 'both' === woocommerce_get_loop_display_mode() ){
		$theme_options = ecomall_get_theme_options();
		$columns = $theme_options['ts_top_cat_columns'];
		
		$classes = array();
		$classes[] = 'list-categories';
		$classes[] = $theme_options['ts_top_cat_image_type'];
		
		$styles = array();
		$styles[] = '--ts-img-gap: ' . esc_attr($theme_options['ts_top_cat_image_gap']) . 'px';
		$styles[] = '--ts-columns: ' . esc_attr($columns);
		
		$is_slider = $theme_options['ts_top_cat_slider'];
		if( $is_slider ){
			$product_categories = woocommerce_get_product_subcategories( is_product_category() ? get_queried_object_id() : 0 );
			
			if( is_array( $product_categories ) && count( $product_categories ) < $columns ){
				$is_slider = false;
			}
		}
		
		$before = '<div class="'.esc_attr(implode(' ', $classes)).'" style="'.esc_attr(implode(';', $styles)).'"><div class="container">';
		if( $is_slider ){
			$data_attr = array();
			$data_attr[] = 'data-nav="'.(int)$theme_options['ts_slider_nav'].'"';
			$data_attr[] = 'data-autoplay="'.(int)$theme_options['ts_slider_autoplay'].'"';
			$data_attr[] = 'data-columns="'.$columns.'"';
			$data_attr[] = 'data-loop="'.(int)$theme_options['ts_slider_loop'].'"';
			
			$before .= '<div class="ts-product-category-wrapper ts-product ts-shortcode woocommerce ts-slider" '.implode(' ', $data_attr).'><div class="content-wrapper loading">';
		}
		$before .= '<div class="products">';
		
		$after = '</div></div></div>';
		if( $is_slider ){
			$after .= '</div></div>';
		}
		
		if( $theme_options['ts_top_cat_image_type'] == 'icon' ){
			remove_action( 'woocommerce_before_subcategory_title', 'woocommerce_subcategory_thumbnail', 10 );
			add_action( 'woocommerce_before_subcategory_title', 'ecomall_woocommerce_subcategory_icon', 10 );
		}
		
		woocommerce_output_product_categories(
			array(
				'before' => $before
				,'after' => $after
				,'parent_id' => is_product_category() ? get_queried_object_id() : 0
			)
		);
		
		if( $theme_options['ts_top_cat_image_type'] == 'icon' ){
			add_action( 'woocommerce_before_subcategory_title', 'woocommerce_subcategory_thumbnail', 10 );
			remove_action( 'woocommerce_before_subcategory_title', 'ecomall_woocommerce_subcategory_icon', 10 );
		}
		
		remove_filter( 'woocommerce_product_loop_start', 'woocommerce_maybe_show_product_subcategories' );
	}
}

function ecomall_woocommerce_subcategory_icon( $category ){
	$icon_id = get_term_meta($category->term_id, 'icon_id', true);
	
	if( $icon_id ){
		echo wp_get_attachment_image( $icon_id, 'full', false, array('alt' => $category->name) );
	}else{
		echo wc_placeholder_img();
	}
}

function ecomall_shop_top_product_brands(){
	if( !taxonomy_exists('ts_product_brand') || is_tax('ts_product_brand') || !woocommerce_products_will_display() ){
		return;
	}
	
	$theme_options = ecomall_get_theme_options();
	if( !$theme_options['ts_top_brand'] ){
		return;
	}
	
	$based_on_selection = $theme_options['ts_top_brand_based_selection'];
	$columns = (int) $theme_options['ts_top_brand_columns'];
	
	$args = array(
			'taxonomy'	  	=> 'ts_product_brand'
			,'orderby'   	=> 'name'
			,'order'     	=> 'ASC'
			,'hide_empty' 	=> true
			,'pad_counts'  	=> true
			,'number'      	=> $theme_options['ts_top_brand_limit']
			,'hierarchical' => false
		);
		
	$brands = get_terms( $args );
	
	if( !$brands ){
		return;
	}
	
	if( $based_on_selection && function_exists('wc_get_container') && class_exists('Automattic\WooCommerce\Internal\ProductAttributesLookup\Filterer') ){
		add_filter('option_woocommerce_attribute_lookup_enabled', 'ecomall_disable_woocommerce_attribute_lookup');
			
		$term_counts = wc_get_container()->get( Automattic\WooCommerce\Internal\ProductAttributesLookup\Filterer::class )->get_filtered_term_product_counts( wp_list_pluck( $brands, 'term_id' ), 'ts_product_brand', 'or' );
		
		remove_filter('option_woocommerce_attribute_lookup_enabled', 'ecomall_disable_woocommerce_attribute_lookup');
		
		$term_ids_in_selection = array_keys($term_counts);
		foreach( $brands as $key => $brand ){
			if( !in_array($brand->term_id, $term_ids_in_selection) ){
				unset( $brands[$key] );
			}
		}
		
		if( !$brands ){
			return;
		}
	}
	
	$is_slider = count( $brands ) >= $columns ? true : false;
	$classes = array('ts-product-brand-wrapper ts-product ts-shortcode woocommerce');
	$classes[] = 'columns-' . $columns;
	
	$data_attr = array();
	
	if( $is_slider ){
		$classes[] = 'ts-slider';
		
		$data_attr[] = 'data-nav="'.(int)$theme_options['ts_slider_nav'].'"';
		$data_attr[] = 'data-autoplay="'.(int)$theme_options['ts_slider_autoplay'].'"';
		$data_attr[] = 'data-columns="'.$columns.'"';
		$data_attr[] = 'data-loop="'.(int)$theme_options['ts_slider_loop'].'"';
	}
	?>
	<div class="list-brands">
		<div class="container">
			<div class="<?php echo esc_attr( implode(' ', $classes) ); ?>" style="--ts-columns: <?php echo esc_attr( $columns ); ?>" <?php echo implode(' ', $data_attr); ?>>
				<div class="content-wrapper items <?php echo esc_attr( $is_slider ? 'loading' : '' ); ?>">
					<?php 
					foreach( $brands as $brand ){
						$thumbnail_id = absint( get_term_meta( $brand->term_id, 'thumbnail_id', true ) );
						?>
						<div class="item">
							<a href="<?php echo esc_url( get_term_link($brand, 'ts_product_brand') ) ?>">
								<?php
								if( $thumbnail_id ){
									echo wp_get_attachment_image($thumbnail_id, 'woocommerce_thumbnail');
								}
								else{
									echo wc_placeholder_img();
								}
								?>
							</a>
							<?php do_action('ecomall_top_brands_bottom_item', $brand); ?>
						</div>
						<?php
					}
					?>
				</div>
			</div>
		</div>
	</div>	
	<?php
}

function ecomall_disable_woocommerce_attribute_lookup(){
	return 'no';
}

add_filter('woocommerce_pagination_args', 'ecomall_woocommerce_pagination_args');
function ecomall_woocommerce_pagination_args( $args ){
	$args['prev_text'] = esc_html__('Previous page', 'ecomall');
	$args['next_text'] = esc_html__('Next page', 'ecomall');
	return $args;
}

add_action('init', 'ecomall_check_product_lazy_load');
function ecomall_check_product_lazy_load(){
	if( wp_doing_ajax() || ( isset($_GET['action']) && $_GET['action'] == 'elementor' ) ){
		ecomall_change_theme_options('ts_prod_lazy_load', 0);
	}
}

function ecomall_template_loop_product_label(){
	global $product;
	$theme_options = ecomall_get_theme_options();
	?>
	<div class="product-label <?php echo esc_attr( $theme_options['ts_product_label_pos'] ); ?>">
	<?php 
	if( $product->is_in_stock() ){

		/* New label */
		if( $theme_options['ts_product_show_new_label'] ){
			$now = current_time( 'timestamp', true );
			$post_date = get_post_time('U', true);
			$num_day = (int)( ( $now - $post_date ) / ( 3600*24 ) );
			$num_day_setting = absint( $theme_options['ts_product_show_new_label_time'] );
			if( $num_day <= $num_day_setting ){
				echo '<span class="new"><span>'.esc_html($theme_options['ts_product_new_label_text']).'</span></span>';
			}
		}

		/* Sale label */
		if( $product->is_on_sale() ){
			if( $theme_options['ts_show_sale_label_as'] != 'text' ){
				if( $product->get_type() == 'variable' ){
					$regular_price = $product->get_variation_regular_price('max');
					$sale_price = $product->get_variation_sale_price('min');
				}
				else{
					$regular_price = $product->get_regular_price();
					$sale_price = $product->get_price();
				}
				if( $regular_price ){
					if( $theme_options['ts_show_sale_label_as'] == 'number' ){
						$_off_price = round($regular_price - $sale_price, wc_get_price_decimals());
						$price_display = '-' . sprintf(get_woocommerce_price_format(), get_woocommerce_currency_symbol(), $_off_price);
						echo '<span class="onsale amount" data-original="'.$price_display.'"><span>'.$price_display.'</span></span>';
					}
					if( $theme_options['ts_show_sale_label_as'] == 'percent' ){
						echo '<span class="onsale percent"><span>-'.ecomall_calc_discount_percent($regular_price, $sale_price).'%</span></span>';
					}
				}
			}
			else{
				echo '<span class="onsale"><span>'.esc_html($theme_options['ts_product_sale_label_text']).'</span></span>';
			}
		}
		
		/* Hot label */
		if( $product->is_featured() ){
			echo '<span class="featured"><span>'.esc_html($theme_options['ts_product_feature_label_text']).'</span></span>';
		}

	}
	else{ /* Out of stock */
		echo '<span class="out-of-stock"><span>'.esc_html($theme_options['ts_product_out_of_stock_label_text']).'</span></span>';
	}
	?>
	</div>
	<?php
}

function ecomall_template_loop_product_thumbnail(){
	global $product;
	$lazy_load = ecomall_get_theme_options('ts_prod_lazy_load');
	$placeholder_img_src = ecomall_get_theme_options('ts_prod_placeholder_img')['url'];
	
	$prod_galleries = $product->get_gallery_image_ids();
	
	$image_size = apply_filters('ecomall_loop_product_thumbnail', 'woocommerce_thumbnail');
	
	$dimensions = wc_get_image_size( $image_size );
	
	$has_back_image = ecomall_get_theme_options('ts_effect_product');
	
	if( !is_array($prod_galleries) || ( is_array($prod_galleries) && count($prod_galleries) == 0 ) ){
		$has_back_image = false;
	}
	 
	if( wp_is_mobile() ){
		$has_back_image = false;
	}
	
	echo '<figure class="' . ($has_back_image?'has-back-image':'no-back-image') . '">';
		if( !$lazy_load ){
			echo woocommerce_get_product_thumbnail( $image_size );
			
			if( $has_back_image ){
				echo wp_get_attachment_image( $prod_galleries[0], $image_size, 0, array('class' => 'product-image-back') );
			}
		}
		else{
			$front_img_src = '';
			$alt = '';
			if( has_post_thumbnail( $product->get_id() ) ){
				$post_thumbnail_id = get_post_thumbnail_id($product->get_id());
				$image_obj = wp_get_attachment_image_src($post_thumbnail_id, $image_size, 0);
				if( isset($image_obj[0]) ){
					$front_img_src = $image_obj[0];
				}
				$alt = trim(strip_tags( get_post_meta($post_thumbnail_id, '_wp_attachment_image_alt', true) ));
			}
			else{
				$front_img_src = wc_placeholder_img_src();
			}
			
			echo '<img src="'.esc_url($placeholder_img_src).'" data-src="'.esc_url($front_img_src).'" class="attachment-shop_catalog wp-post-image ts-lazy-load" alt="'.esc_attr($alt).'" width="'.$dimensions['width'].'" height="'.$dimensions['height'].'" />';
		
			if( $has_back_image ){
				$back_img_src = '';
				$alt = '';
				$image_obj = wp_get_attachment_image_src($prod_galleries[0], $image_size, 0);
				if( isset($image_obj[0]) ){
					$back_img_src = $image_obj[0];
					$alt = trim(strip_tags( get_post_meta($prod_galleries[0], '_wp_attachment_image_alt', true) ));
				}
				else{
					$back_img_src = wc_placeholder_img_src();
				}
				
				echo '<img src="'.esc_url($placeholder_img_src).'" data-src="'.esc_url($back_img_src).'" class="product-image-back ts-lazy-load" alt="'.esc_attr($alt).'" width="'.$dimensions['width'].'" height="'.$dimensions['height'].'" />';
			}
		}
	echo '</figure>';
}

function ecomall_template_loop_product_variable_color(){
	global $product;
	if( $product->get_type() == 'variable' ){
		$attribute_color = wc_attribute_taxonomy_name( 'color' ); // pa_color
		$attribute_color_name = wc_variation_attribute_name( $attribute_color ); // attribute_pa_color
		
		$color_terms = wc_get_product_terms( $product->get_id(), $attribute_color, array( 'fields' => 'all' ) );
		if( empty($color_terms) || is_wp_error($color_terms) ){
			return;
		}
		$color_term_ids = wp_list_pluck( $color_terms, 'term_id' );
		$color_term_slugs = wp_list_pluck( $color_terms, 'slug' );
		
		$color_html = array();
		$price_html = array();
		
		$added_colors = array();
		$count = 0;
		$number = apply_filters('ecomall_loop_product_variable_color_number', 3);
		
		$children = $product->get_children();
		if( is_array($children) && count($children) > 0 ){
			foreach( $children as $children_id ){
				$variation_attributes = wc_get_product_variation_attributes( $children_id );
				foreach( $variation_attributes as $attribute_name => $attribute_value ){
					if( $attribute_name == $attribute_color_name ){
						if( in_array($attribute_value, $added_colors) ){
							break;
						}
						
						$term_id = 0;
						$found_slug = array_search($attribute_value, $color_term_slugs);
						if( $found_slug !== false ){
							$term_id = $color_term_ids[ $found_slug ];
						}
						
						if( $term_id !== false && absint( $term_id ) > 0 ){
							$thumbnail_id = get_post_meta( $children_id, '_thumbnail_id', true );
							if( $thumbnail_id ){
								$image_src = wp_get_attachment_image_src($thumbnail_id, 'woocommerce_thumbnail');
								if( $image_src ){
									$thumbnail = $image_src[0];
								}
								else{
									$thumbnail = wc_placeholder_img_src();
								}
							}
							else{
								$thumbnail = wc_placeholder_img_src();
							}
							
							$color_datas = get_term_meta( $term_id, 'ts_product_color_config', true );
							if( $color_datas ){
								$color_datas = unserialize( $color_datas );	
							}else{
								$color_datas = array('ts_color_color' => '#ffffff', 'ts_color_image' => 0);
							}
							$color_datas['ts_color_image'] = absint($color_datas['ts_color_image']);
							if( $color_datas['ts_color_image'] ){
								$color_html[] = '<div class="color-image" data-thumb="'.$thumbnail.'" data-term_id="'.$term_id.'"><span>'.wp_get_attachment_image( $color_datas['ts_color_image'], 'ts_prod_color_thumb', true, array('alt' => $attribute_value) ).'</span></div>';
							}
							else{
								$color_html[] = '<div class="color" data-thumb="'.$thumbnail.'" data-term_id="'.$term_id.'"><span style="background-color: '.$color_datas['ts_color_color'].'"></span></div>';
							}
							$variation = wc_get_product( $children_id );
							$price_html[] = '<span data-term_id="'.$term_id.'">' . $variation->get_price_html() . '</span>';
							$count++;
						}
						
						$added_colors[] = $attribute_value;
						break;
					}
				}
				
				if( $count == $number ){
					break;
				}
			}
		}
		
		if( $color_html ){
			echo '<div class="color-swatch">'. implode('', $color_html) . '</div>';
			echo '<span class="variable-prices hidden">' . implode('', $price_html) . '</span>';
		}
	}
}

function ecomall_template_loop_product_title(){
	global $product;
	echo '<h3 class="heading-title product-name">';
	echo '<a href="' . esc_url($product->get_permalink()) . '">' . esc_html($product->get_title()) . '</a>';
	echo '</h3>';
}

function ecomall_template_loop_add_to_cart(){
	if( ecomall_get_theme_options('ts_enable_catalog_mode') ){
		return;
	}
	
	echo '<div class="loop-add-to-cart">';
	woocommerce_template_loop_add_to_cart();
	echo '</div>';
}

function ecomall_template_loop_product_sku(){
	global $product;
	echo '<div class="product-sku"><span>'.esc_html__('Sku: ', 'ecomall').' </span>' . esc_html($product->get_sku()) . '</div>';
}

function ecomall_template_loop_short_description(){
	global $product;
	if( !$product->get_short_description() ){
		return;
	}
	
	$limit_words = (int) ecomall_get_theme_options('ts_prod_cat_desc_words');
	?>
	<div class="short-description grid">
		<?php ecomall_the_excerpt_max_words($limit_words, '', false, '', true); ?>
	</div>
	<?php
}

function ecomall_template_loop_short_description_list_view(){
	global $product;
	if( !$product->get_short_description() ){
		return;
	}
	
	$limit_words = (int) ecomall_get_theme_options('ts_prod_cat_list_desc_words');
	?>
		<div class="short-description list">
			<?php ecomall_the_excerpt_max_words($limit_words, '', false, '', true); ?>
		</div>
	<?php
}

function ecomall_template_loop_brands(){
	global $product;
	if( taxonomy_exists('ts_product_brand') ){
		echo get_the_term_list($product->get_id(), 'ts_product_brand', '<div class="product-brands">', ', ', '</div>');
	}
}

function ecomall_template_brands(){
	global $product;
	if( taxonomy_exists('ts_product_brand') ){
		echo get_the_term_list($product->get_id(), 'ts_product_brand', '<div class="product-brands"><span>'.esc_html__('Brands:', 'ecomall').' </span><span class="brand-links">', ', ', '</span></div>');
	}
}

function ecomall_template_loop_categories(){
	global $product;
	$categories_label = esc_html__('Categories: ', 'ecomall');
	echo wc_get_product_category_list($product->get_id(), ', ', '<div class="product-categories"><span>'.$categories_label.'</span>', '</div>');
}

function ecomall_change_products_per_page_shop(){
    if( is_tax( get_object_taxonomies( 'product' ) ) || is_post_type_archive('product') ){
		if( isset($_GET['per_page']) && absint($_GET['per_page']) > 0 ){
			return absint($_GET['per_page']);
		}
		$per_page = absint( ecomall_get_theme_options('ts_prod_cat_per_page') );
        if( $per_page ){
            return $per_page;
        }
    }
}

function ecomall_product_per_page_form(){
	if( !ecomall_get_theme_options('ts_prod_cat_per_page_dropdown') ){
		return;
	}
	if( function_exists('woocommerce_products_will_display') && !woocommerce_products_will_display() ){
		return;
	}
	
	$per_page = absint( ecomall_get_theme_options('ts_prod_cat_per_page') );
	if( !$per_page ){
		return;
	}
	
	$options = array();
	for( $i = 1; $i <= 4; $i++ ){
		$options[] = $per_page * $i;
	}
	$selected = isset($_GET['per_page'])?absint($_GET['per_page']):$per_page;
	
	$action = '';
	$cat 	= get_queried_object();
	if( isset( $cat->term_id ) && isset( $cat->taxonomy ) ){
		$action = get_term_link( $cat->term_id, $cat->taxonomy );
	}
	else{
		$action = wc_get_page_permalink('shop');
	}
?>
	<form method="get" action="<?php echo esc_url($action) ?>" class="product-per-page-form">
		<span><?php esc_html_e('Show', 'ecomall'); ?></span>
		<select name="per_page" class="perpage">
			<?php foreach( $options as $option ): ?>
			<option value="<?php echo esc_attr($option) ?>" <?php selected($selected, $option) ?>><?php echo esc_html($option) ?></option>
			<?php endforeach; ?>
		</select>
		<ul class="perpage">
			<li>
				<span class="perpage-current">
					<span><?php esc_html_e('Show: ', 'ecomall'); ?></span>
					<span><?php echo esc_html($selected) ?></span>
				</span>
				<ul class="dropdown">
					<?php foreach( $options as $option ): ?>
					<li>
						<a href="#" data-perpage="<?php echo esc_attr($option) ?>" class="<?php echo esc_attr($option == $selected?'current':''); ?>">
							<span><?php echo esc_html($option) ?></span>
						</a>
					</li>
					<?php endforeach; ?>
				</ul>
			</li>
		</ul>
		<?php wc_query_string_form_fields( null, array( 'per_page', 'submit', 'paged', 'product-page' ) ); ?>
	</form>
<?php
}

function ecomall_show_only_products_on_sales( $array ){
	if( is_tax( get_object_taxonomies( 'product' ) ) || is_post_type_archive('product') ){
		if( isset($_GET['onsale']) && 'yes' == $_GET['onsale'] ){
			return array_merge($array, wc_get_product_ids_on_sale());
		}
	}
	return $array;
}

function ecomall_product_on_sale_form(){
	if( !ecomall_get_theme_options('ts_prod_cat_onsale_checkbox') ){
		return;
	}
	if( function_exists('woocommerce_products_will_display') && !woocommerce_products_will_display() ){
		return;
	}
	
	$checked = isset($_GET['onsale']) && 'yes' == $_GET['onsale'] ? true : false;
	
	$action = '';
	$cat 	= get_queried_object();
	if( isset( $cat->term_id ) && isset( $cat->taxonomy ) ){
		$action = get_term_link( $cat->term_id, $cat->taxonomy );
	}
	else{
		$action = wc_get_page_permalink('shop');
	}
	?>
	<form method="get" action="<?php echo esc_url($action); ?>" class="product-on-sale-form <?php echo esc_attr( $checked?'checked':'' ); ?>">
		<label>
			<input type="checkbox" name="onsale" value="yes" <?php echo esc_attr( $checked?'checked':'' ); ?> />
			<?php esc_html_e('Show only products on sale', 'ecomall'); ?>
		</label>
		<?php wc_query_string_form_fields( null, array( 'onsale', 'submit', 'paged', 'product-page' ) ); ?>
	</form>
	<?php
}

function ecomall_is_active_filter_area(){
	return is_active_sidebar('filter-widget-area') && ecomall_get_theme_options('ts_filter_widget_area') && woocommerce_products_will_display();
}

function ecomall_show_filter_area_by_default(){
	return !wp_is_mobile() && ecomall_get_theme_options('ts_show_filter_widget_area_by_default') && ecomall_get_theme_options('ts_filter_widget_area_style') == 'sidebar';
}

function ecomall_add_filter_button(){
	$show_filter_area = ecomall_is_active_filter_area();
	if( $show_filter_area || ( ecomall_get_theme_options('ts_prod_cat_layout') != '0-1-0' && woocommerce_products_will_display() ) ){
	?>
		<div class="filter-widget-area-button">
			<a href="#"><?php esc_html_e('Filter', 'ecomall'); ?></a>
		</div>
		<div class="overlay"></div>
	<?php
	}
	
	if( $show_filter_area ){
		$show_by_default = ecomall_show_filter_area_by_default();
	?>
		<div id="ts-filter-widget-area" class="ts-floating-sidebar <?php echo esc_attr( $show_by_default?'active':'' ); ?>">
			<div class="overlay"></div>
			<div class="ts-sidebar-content">
				<aside class="filter-widget-area">
					<div class="sidebar-header">
						<?php ecomall_product_on_sale_form(); ?>
						<span class="close"></span>
					</div>
					<?php dynamic_sidebar( 'filter-widget-area' ); ?>
				</aside>
			</div>
		</div>
		<?php
	}
}

function ecomall_add_gridlist_toggle(){
	$theme_options = ecomall_get_theme_options();
	if( !$theme_options['ts_prod_cat_grid_list_toggle'] ){
		return;
	}

	if( function_exists('woocommerce_products_will_display') && !woocommerce_products_will_display() ){
		return;
	}

	$default = $theme_options['ts_prod_grid_list_toggle_default'];
	?>
	<div class="gridlist-toggle">
		<span class="list <?php echo esc_attr( 'list' == $default ? 'active' : '' ); ?>" data-style="list"></span>
		<span class="grid <?php echo esc_attr( 'grid' == $default ? 'active' : '' ); ?>" data-style="grid"></span>
	</div>
	<?php
}

function ecomall_shop_load_more_html(){
	if( wc_get_loop_prop( 'total_pages' ) == 1 || !woocommerce_products_will_display() ){
		return;
	}
	$loading_type = ecomall_get_theme_options('ts_prod_cat_loading_type');
	if( in_array($loading_type, array('infinity-scroll', 'load-more-button')) ){
		$total = wc_get_loop_prop( 'total' );
		$per_page = wc_get_loop_prop( 'per_page' );
		$current = wc_get_loop_prop( 'current_page' );
		$showing = min($current * $per_page, $total);
	?>
	<div class="ts-shop-result-count">
		<?php 
		if( $showing < $total ){
			printf( esc_html__('Showing %s of %s results', 'ecomall'), $showing, $total );
		}
		else{
			echo esc_html__('Showing all', 'ecomall');
		}
		?>
	</div>
	<div class="ts-shop-load-more">
		<a class="load-more button"><?php esc_html_e('View more', 'ecomall'); ?></a>
	</div>
	<?php
	}
}

function ecomall_empty_woocommerce_stock_html( $html, $product ){
	if( $product->get_type() == 'simple' ){
		return '';
	}
	return $html;
}
/*** End Shop - Category ***/

/*** Single Product ***/

/* Remove hook */
remove_action('woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 10);
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_title', 5);
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10);
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_price', 10);
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20);
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40);
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_sharing', 50);

/* Add hook */
add_action('woocommerce_before_single_product_summary', 'ecomall_before_single_product_summary_images', 2);
add_action('woocommerce_after_single_product_summary', 'ecomall_after_single_product_summary_images', 0);

add_action('woocommerce_product_thumbnails', 'ecomall_template_loop_product_label', 99);
add_action('woocommerce_product_thumbnails', 'ecomall_template_single_product_video_360_buttons', 99);

add_action('woocommerce_single_product_summary', 'ecomall_template_single_navigation', 1);

add_action('woocommerce_single_product_summary', 'ecomall_template_single_categories', 5);
add_action('woocommerce_single_product_summary', 'ecomall_single_product_ratings_stock_start', 14);
add_action('woocommerce_single_product_summary', 'ecomall_template_star_rating', 15);
add_action('woocommerce_single_product_summary', 'ecomall_template_single_availability', 20);
add_action('woocommerce_single_product_summary', 'ecomall_single_product_ratings_stock_end', 21);

add_action('woocommerce_single_product_summary', 'ecomall_template_single_countdown', 23);
add_action('woocommerce_single_product_summary', 'woocommerce_template_single_price', 25);
add_action('woocommerce_single_product_summary', 'ecomall_template_single_variation_price', 26);
add_action('woocommerce_single_product_summary', 'ecomall_single_product_calc_discount', 27);
add_action('woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 28);
add_action('woocommerce_single_product_summary', 'ecomall_product_summary_custom_content', 29);
add_action('woocommerce_after_variations_table', 'ecomall_product_summary_custom_content', 10);
add_action('woocommerce_single_product_summary', 'ecomall_single_product_buy_now_button', 31);

add_action('woocommerce_before_add_to_cart_form', 'ecomall_single_group_product_add_heading', 10);

add_action('woocommerce_single_product_summary', 'ecomall_single_product_buttons_sharing_start', 31);
add_action('woocommerce_single_product_summary', 'ecomall_single_product_buttons_sharing_end', 41);

add_action('woocommerce_single_product_summary', 'ecomall_template_single_meta', 77);
add_action('woocommerce_after_single_product_summary', 'ecomall_product_ads_banner', 10);

if( function_exists('ts_template_social_sharing') ){
	add_action('woocommerce_share', 'ts_template_social_sharing', 10);
}

add_action('woocommerce_product_additional_information', 'ecomall_woocommerce_product_additional_information_before', 5);
add_action('woocommerce_product_additional_information', 'ecomall_woocommerce_product_additional_information_after', 15);

add_filter('woocommerce_grouped_product_columns', 'ecomall_woocommerce_grouped_product_columns');
add_action( 'woocommerce_grouped_product_list_before_label', 'ecomall_woocommerce_grouped_product_thumbnail' );

add_filter('woocommerce_output_related_products_args', 'ecomall_output_related_products_args_filter');

add_filter('woocommerce_single_product_image_gallery_classes', 'ecomall_add_classes_to_single_product_thumbnail');
add_filter('woocommerce_gallery_thumbnail_size', 'ecomall_product_gallery_thumbnail_size');

add_filter('woocommerce_dropdown_variation_attribute_options_args', 'ecomall_variation_attribute_options_args');
add_filter('woocommerce_dropdown_variation_attribute_options_html', 'ecomall_variation_attribute_options_html', 10, 2);

add_filter('woocommerce_add_to_cart_redirect', 'ecomall_product_buy_now_redirect');

add_filter('woocommerce_available_variation', 'ecomall_add_discount_percent_to_variation', 10, 3);

add_filter('woocommerce_product_description_tab_title', 'ecomall_change_product_description_tab_title');
function ecomall_change_product_description_tab_title(){
	return esc_html__( 'Product details', 'ecomall' );
}

if( !is_admin() ){ /* Fix for WooCommerce Tab Manager plugin */
	add_filter( 'woocommerce_product_tabs', 'ecomall_product_remove_tabs', 999 );
	add_filter( 'woocommerce_product_tabs', 'ecomall_add_product_custom_tab', 90 );
}

function ecomall_calc_discount_percent($regular_price, $sale_price){
	return ( 1 - round($sale_price / $regular_price, 2) ) * 100;
}

add_action('wp_ajax_ecomall_load_product_video', 'ecomall_load_product_video_callback' );
add_action('wp_ajax_nopriv_ecomall_load_product_video', 'ecomall_load_product_video_callback' );
/*** End Product ***/

function ecomall_before_single_product_summary_images(){
	echo '<div class="product-images-summary">';
}

function ecomall_after_single_product_summary_images(){
	echo '</div>';
}

function ecomall_woocommerce_product_additional_information_before(){
	echo '<div>';
}

function ecomall_woocommerce_product_additional_information_after(){
	echo '</div>';
}

function ecomall_template_single_product_video_360_buttons(){
	if( !is_singular('product') ){
		return;
	}
	
	global $product;
	$video_url = get_post_meta($product->get_id(), 'ts_prod_video_url', true);
	if( $video_url ){
		echo '<a class="ts-product-video-button" href="#" data-product_id="'.$product->get_id().'">'.esc_html__('Video', 'ecomall').'</a>';
		add_action('wp_footer', 'ecomall_add_product_video_popup_modal', 999);
	}
	
	$gallery_360 = get_post_meta($product->get_id(), 'ts_prod_360_gallery', true);
	if( $gallery_360 ){
		$galleries = array_map('trim', explode(',', $gallery_360));
		$image_array = array();
		foreach($galleries as $gallery ){
			$image_src = wp_get_attachment_image_url($gallery, 'woocommerce_single');
			if( $image_src ){
				$image_array[] = "'" . $image_src . "'";
			}
		}
		wp_enqueue_script('threesixty');
		wp_add_inline_script('threesixty', 'var _ts_product_360_image_array = ['.implode(',', $image_array).'];');
		
		echo '<a class="ts-product-360-button" href="#">'.esc_html__('360 View', 'ecomall').'</a>';
		add_action('wp_footer', 'ecomall_add_product_360_popup_modal', 999);
	}
}

function ecomall_add_product_video_popup_modal(){
	?>
	<div id="ts-product-video-modal" class="ts-popup-modal">
		<div class="overlay"></div>
		<div class="product-video-container popup-container">
			<span class="close"><?php esc_html_e('Close ', 'ecomall'); ?></span>
			<div class="product-video-content"></div>
		</div>
	</div>
	<?php
}

function ecomall_add_product_360_popup_modal(){
	?>
	<div id="ts-product-360-modal" class="ts-popup-modal">
		<div class="overlay"></div>
		<span class="close"><?php esc_html_e('Close ', 'ecomall'); ?></span>
		<div class="product-360-container popup-container">
			<div class="product-360-content"><?php ecomall_load_product_360(); ?></div>
		</div>
	</div>
	<?php
}

function ecomall_add_product_size_chart_popup_modal(){
	?>
	<div id="ts-product-size-chart-modal" class="ts-popup-modal">
		<div class="overlay"></div>
		<div class="product-size-chart-container popup-container">
			<span class="close"><?php esc_html_e('Close ', 'ecomall'); ?></span>
			<div class="product-size-chart-content">
				<?php ecomall_product_size_chart_content(); ?>
			</div>
		</div>
	</div>
	<?php
}

function ecomall_add_classes_to_single_product_thumbnail( $classes ){
	global $product;
	$video_url = get_post_meta($product->get_id(), 'ts_prod_video_url', true);
	if( $video_url ){
		$classes[] = 'has-video';
	}
	$gallery_360 = get_post_meta($product->get_id(), 'ts_prod_360_gallery', true);
	if( $gallery_360 ){
		$classes[] = 'has-360-gallery';
	}
	
	return $classes;
}

function ecomall_product_gallery_thumbnail_size(){
	return 'woocommerce_thumbnail';
}

/* Single Product Video - Register ajax */
function ecomall_load_product_video_callback(){
	check_ajax_referer( 'ecomall-product-video-nonce', 'security' );
	
	if( empty($_POST['product_id']) ){
		die( esc_html__('Invalid Product', 'ecomall') );
	}
	
	$prod_id = absint($_POST['product_id']);

	if( $prod_id <= 0 ){
		die( esc_html__('Invalid Product', 'ecomall') );
	}
	
	$video_url = get_post_meta($prod_id, 'ts_prod_video_url', true);
	ob_start();
	if( !empty($video_url) ){
		echo do_shortcode('[ts_video src='.esc_url($video_url).']');
	}
	die( ob_get_clean() );
}

function ecomall_load_product_360(){
	?>
	<div class="threesixty ts-product-360">
		<div class="spinner">
			<span>0%</span>
		</div>
		<ol class="threesixty_images"></ol>
	</div>
	<?php
}

function ecomall_template_single_countdown(){
	if( ecomall_get_theme_options('ts_prod_count_down') && function_exists('ts_template_loop_time_deals') ){
		ts_template_loop_time_deals();
	}
}

function ecomall_template_single_navigation(){
	if( !ecomall_get_theme_options('ts_prod_next_prev_navigation') ){
		return;
	}
	$prev_post = get_adjacent_post(false, '', true, 'product_cat');
	$next_post = get_adjacent_post(false, '', false, 'product_cat');
	?>
	<div class="single-navigation">
	<?php 
		if( $prev_post ){
			$post_id = $prev_post->ID;
			$product = wc_get_product($post_id);
			?>
			<a href="<?php echo esc_url(get_permalink($post_id)); ?>" rel="prev">
				<div class="product-info prev-product-info">
					<?php echo wp_kses( $product->get_image(), 'ecomall_product_image' ); ?>
				</div>
				<span class="prev-title"><?php esc_html_e('Prev product', 'ecomall'); ?></span>
			</a>
			<?php
		}
		
		if( $next_post ){
			$post_id = $next_post->ID;
			$product = wc_get_product($post_id);
			?>
			<a href="<?php echo esc_url(get_permalink($post_id)); ?>" rel="next">
				<div class="product-info next-product-info">
					<?php echo wp_kses( $product->get_image(), 'ecomall_product_image' ); ?>
				</div>
				<span class="next-title"><?php esc_html_e('Next product', 'ecomall'); ?></span>
			</a>
			<?php
		}
	?>
	</div>
	<?php
}

function ecomall_template_single_variation_price(){
	if( ecomall_get_theme_options('ts_prod_price') ){
		echo '<div class="ts-variation-price hidden"></div>';
	}
}

function ecomall_variation_attribute_options_args( $args ){
	if( !ecomall_get_theme_options('ts_prod_attr_dropdown') ){
		$args['class'] = 'hidden hidden-1';
	}
	return $args;
}

function ecomall_get_color_variation_thumbnails(){
	global $product;
	$color_variation_thumbnails = array();
	
	$attribute_name = wc_attribute_taxonomy_name( 'color' );
	$variation_attribute_name = wc_variation_attribute_name( $attribute_name );
	
	$children = $product->get_children();
	if( is_array($children) && count($children) > 0 ){
		foreach( $children as $children_id ){
			$variation_attributes = wc_get_product_variation_attributes( $children_id );
			foreach( $variation_attributes as $attr_name => $attr_value ){
				if( $attr_name == $variation_attribute_name ){
					if( !$attr_value ){ /* Any */
						break;
					}
					if( in_array( $attr_value, array_keys($color_variation_thumbnails) ) ){
						break;
					}
					
					$thumbnail_id = get_post_meta( $children_id, '_thumbnail_id', true );
					if( $thumbnail_id ){
						$thumbnail = wp_get_attachment_image($thumbnail_id, 'woocommerce_thumbnail');
					}
					else{
						$thumbnail = wc_placeholder_img();
					}
					
					$color_variation_thumbnails[$attr_value] = $thumbnail;
					
					break;
				}
			}
		}
	}
	
	return $color_variation_thumbnails;
}

function ecomall_variation_attribute_options_html( $html, $args ){
	$theme_options = ecomall_get_theme_options();
	
	if( $theme_options['ts_prod_attr_dropdown'] ){
		return $html;
	}
	
	global $product;
	
	$attr_color_text = $theme_options['ts_prod_attr_color_text'];
	$use_variation_thumbnail = $theme_options['ts_prod_attr_color_variation_thumbnail'];
	
	$options = $args['options'];
	$attribute_name = $args['attribute'];
	
	ob_start();
	
	if( $theme_options['ts_prod_size_chart'] && is_singular('product') ){
		if( strpos( sanitize_title( $attribute_name ), 'size' ) !== false && ecomall_get_product_size_chart_id() ){
			echo '<a class="ts-product-size-chart-button" href="#">' . esc_html__('Size guide', 'ecomall') . '</a>';
			add_action('wp_footer', 'ecomall_add_product_size_chart_popup_modal', 999);
			wp_cache_set('ts_size_chart_is_showed', true);
		}
	}
	
	if( is_array( $options ) ){
	?>
		<div class="ts-product-attribute">
		<?php 
		$selected_key = 'attribute_' . sanitize_title( $attribute_name );
		
		$selected_value = isset( $_REQUEST[ $selected_key ] ) ? wc_clean( wp_unslash( $_REQUEST[ $selected_key ] ) ) : $product->get_variation_default_attribute( $attribute_name );
		
		// Get terms if this is a taxonomy - ordered
		if( taxonomy_exists( $attribute_name ) ){
			
			$class = 'option';
			$is_attr_color = false;
			$attribute_color = wc_sanitize_taxonomy_name( 'color' );
			if( $attribute_name == wc_attribute_taxonomy_name( $attribute_color ) ){
				if( !$attr_color_text ){
					$is_attr_color = true;
					$class .= ' color';
					
					if( $use_variation_thumbnail ){
						$color_variation_thumbnails = ecomall_get_color_variation_thumbnails();
					}
				}
				else{
					$class .= ' text';
				}
			}
			$terms = wc_get_product_terms( $product->get_id(), $attribute_name, array( 'fields' => 'all' ) );

			foreach ( $terms as $term ) {
				if ( ! in_array( $term->slug, $options ) ) {
					continue;
				}
				$term_name = apply_filters( 'woocommerce_variation_option_name', $term->name );
				
				if( $is_attr_color && !$use_variation_thumbnail ){
					$datas = get_term_meta( $term->term_id, 'ts_product_color_config', true );
					if( $datas ){
						$datas = unserialize( $datas );	
					}else{
						$datas = array(
									'ts_color_color' 				=> "#ffffff"
									,'ts_color_image' 				=> 0
								);
					}
				}
				
				$selected_class = sanitize_title( $selected_value ) == sanitize_title( $term->slug ) ? 'selected' : '';
				
				echo '<div data-value="' . esc_attr( $term->slug ) . '" class="'. $class .' '. $selected_class .'">';
				
				if( $is_attr_color ){
					if( $use_variation_thumbnail ){
						if( isset($color_variation_thumbnails[$term->slug]) ){
							echo '<a href="#">' . $color_variation_thumbnails[$term->slug] . '<span class="ts-tooltip button-tooltip">' . $term_name . '</span></a>';
						}
					}
					else{
						if( absint($datas['ts_color_image']) > 0 ){
							echo '<a href="#">' . wp_get_attachment_image( absint($datas['ts_color_image']), 'ts_prod_color_thumb', true, array('title' => $term_name, 'alt' => $term_name) ) . '<span class="ts-tooltip button-tooltip">' . $term_name . '</span></a>';
						}
						else{
							echo '<a href="#" style="background-color:' . $datas['ts_color_color'] . '"><span class="ts-tooltip button-tooltip">' . $term_name . '</span></a>';
						}
					}
				}
				else{
					echo '<a href="#">' . $term_name . '</a>';
				}
				
				echo '</div>';
			}

		} else {
			foreach( $options as $option ){
				$class = 'option';
				$class .= sanitize_title( $selected_value ) == sanitize_title( $option ) ? ' selected' : '';
				echo '<div data-value="' . esc_attr( $option ) . '" class="' . $class . '"><a href="#">' . esc_html( apply_filters( 'woocommerce_variation_option_name', $option ) ) . '</a></div>';
			}
		}
		?>
	</div>
	<?php
	}
	
	return ob_get_clean() . $html;
}

function ecomall_single_product_ratings_stock_start(){
	?>
	<div class="ts-product-ratings-stock">
	<?php
}

function ecomall_single_product_ratings_stock_end(){
	?>
	</div>
	<?php
}

function ecomall_template_single_availability(){
	global $product;
	$product_stock = $product->get_availability();
	$availability_text = empty($product_stock['availability'])?__('In stock', 'ecomall'):$product_stock['availability'];
	?>
		<div class="availability stock <?php echo esc_attr($product_stock['class']); ?>" data-original="<?php echo esc_attr( $availability_text ); ?>" data-class="<?php echo esc_attr($product_stock['class']) ?>">
			<span class="availability-text"><?php echo esc_html($availability_text); ?></span>
		</div>
	<?php
}

function ecomall_single_product_calc_discount(){
	if( !ecomall_get_theme_options('ts_prod_price') || !ecomall_get_theme_options('ts_prod_discount_percent') ){
		return;
	}
	
	global $product;
	$percent = '';
	if( $product->is_on_sale() ){
		if( $product->get_type() == 'variable' ){
			$percent = '-1'; /* add html but hide */
		}
		else{
			$regular_price = $product->get_regular_price();
			$sale_price = $product->get_price();
			
			if( $regular_price && $sale_price ){
				$percent = ecomall_calc_discount_percent($regular_price, $sale_price);
			}
		}
	}
	
	if( $percent ){
		echo '<span class="ts-discount-percent '.($percent == '-1' ? 'hidden': '').'">(-<span>'.$percent.'</span>%)</span>';
	}
}

function ecomall_add_discount_percent_to_variation( $attributes, $variable, $variation ){
	if( ecomall_get_theme_options('ts_prod_price') && ecomall_get_theme_options('ts_prod_discount_percent') ){
		if( $variation->is_on_sale() && $variation->get_regular_price() ){
			$attributes['discount_percent'] = (string)ecomall_calc_discount_percent($variation->get_regular_price(), $variation->get_price());
		}
	}
	return $attributes;
}

function ecomall_single_group_product_add_heading(){
	global $product;
	$heading = ecomall_get_theme_options('ts_prod_group_heading');
	if( $heading && $product->get_type() == 'grouped' ){
		echo '<h4 class="group-product-heading">' . esc_html($heading) . '</h4>';
	}
}

function ecomall_woocommerce_grouped_product_thumbnail( $product_child ){
    ?>
    <td class="woocommerce-grouped-product-list-item__thumbnail">
        <?php echo wp_kses( $product_child->get_image(), 'ecomall_product_image' ); ?>
    </td>
    <?php
}

function ecomall_single_product_buy_now_button(){
	if( ecomall_get_theme_options('ts_enable_catalog_mode') ){
		return;
	}

	global $product;
	if( ecomall_get_theme_options('ts_prod_buy_now') && in_array( $product->get_type(), array('simple', 'variable') ) && $product->is_purchasable() && $product->is_in_stock() ){
	?>
		<a href="#" class="button ts-buy-now-button"><?php esc_html_e('Buy now', 'ecomall'); ?></a>
	<?php
	}
}

function ecomall_product_buy_now_redirect( $url ){
	if( isset($_REQUEST['ts_buy_now']) && $_REQUEST['ts_buy_now'] == 1 ){
		return apply_filters( 'ecomall_product_buy_now_redirect_url', wc_get_checkout_url() );
	}
	return $url;
}

function ecomall_template_single_sku(){
	global $product;
	if( wc_product_sku_enabled() && ( $product->get_sku() || $product->is_type( 'variable' ) ) ){
		echo '<div class="sku-wrapper product_meta"><span>' . esc_html__( 'SKU: ', 'ecomall' ) . '</span><span class="sku">' . (( $sku = $product->get_sku()) ? $sku : esc_html__( 'N/A', 'ecomall' )) . '</span></div>';
	}
}

function ecomall_template_single_categories(){
	global $product;
	
	if( ecomall_get_theme_options('ts_prod_cat') ){
		echo wc_get_product_category_list( $product->get_id(), ', ', '<div class="cats-link"><span class="cat-links">', '</span></div>' );
	}
}

function ecomall_template_single_meta(){
	global $product;
	$theme_options = ecomall_get_theme_options();
	
	echo '<div class="meta-content">';
		do_action( 'woocommerce_product_meta_start' );
		
		if( $theme_options['ts_prod_sku'] ){
			ecomall_template_single_sku();
		}
		
		if( $theme_options['ts_prod_brand'] ){
			ecomall_template_brands();
		}

		if( $theme_options['ts_prod_tag'] ){
			echo wc_get_product_tag_list( $product->get_id(), ', ', '<div class="tags-link"><span>' . esc_html__( 'Tags: ', 'ecomall' ) . '</span><span class="tag-links">', '</span></div>' );	
		}
		
		if( $theme_options['ts_prod_sharing'] ){
			woocommerce_template_single_sharing();
		}

		do_action( 'woocommerce_product_meta_end' );
	echo '</div>';
}

/************************************* 
* Group single product buttons sharing 
* Start div 31
* Wishlist 31
* Compare 35
* Close div buttons 41
*************************************/
function ecomall_single_product_buttons_sharing_start(){
	?>
	<div class="single-product-buttons">
	<?php
}

function ecomall_single_product_buttons_sharing_end(){
	?>
	</div>
	<?php
}

function ecomall_mysql_version_greater_8(){
	if( function_exists('wc_get_server_database_version') ){
		$database_version = wc_get_server_database_version();
		$number = isset($database_version['number']) ? $database_version['number'] : '';
		if( $number ){
			if( version_compare( $number, '8.0.0', '>=' ) ){
				return true;
			}
		}
	}
	return false;
}

/*** Product size chart ***/
function ecomall_get_product_size_chart_id(){
	global $product;
	$product_id = $product->get_id();
	$cache_key = 'ecomall_size_chart_id_of_' . $product_id;
	$size_chart_id = wp_cache_get($cache_key);
	if( false !== $size_chart_id ){
		return $size_chart_id;
	}
	$size_chart_id = get_post_meta($product_id, 'ts_prod_size_chart', true);
	if( $size_chart_id ){
		wp_cache_set($cache_key, $size_chart_id);
		return $size_chart_id;
	}
	$product_cats = wc_get_product_term_ids( $product_id, 'product_cat' );
	if( !empty($product_cats) && is_array($product_cats) ){
		$args = array(
                    'posts_per_page'         => 1,
                    'order'                  => 'DESC',
                    'post_type'              => 'ts_size_chart',
                    'post_status'            => 'publish',
                    'no_found_rows'          => true,
                    'update_post_term_cache' => false,
                    'fields'                 => 'ids',
                );
				
		if( count( $product_cats ) > 1 ){
			$args['meta_query']['relation'] = 'OR';
		}
		
		foreach( $product_cats as $product_cat ){
			$args['meta_query'][] = array(
				'key'     => 'ts_chart_categories',
				'value'   => ecomall_mysql_version_greater_8() ? "\\b{$product_cat}\\b" : "[[:<:]]{$product_cat}[[:>:]]",
				'compare' => 'RLIKE',
			);
		}
		
		$size_charts = new WP_Query( $args );
		if( $size_charts->have_posts() ){
			foreach( $size_charts->posts as $id ){
				$size_chart_id = $id;
			}
		}
		wp_reset_postdata();
	}
	wp_cache_set($cache_key, $size_chart_id);
	
	return $size_chart_id;
}

function ecomall_product_size_chart_content(){
	$chart_id = ecomall_get_product_size_chart_id();
	$chart_content = get_the_content( null, false, $chart_id );
	$chart_label = get_post_meta( $chart_id, 'ts_chart_label', true );
	$chart_image = get_post_meta( $chart_id, 'ts_chart_image', true );
	$chart_table = get_post_meta( $chart_id, 'ts_chart_table', true );
	
	if( $chart_table ){
		$chart_table = json_decode( $chart_table, true );
		if( is_array($chart_table) ){
			$chart_table = array_filter($chart_table, function($v, $k){
				return is_array($v) && array_filter($v);
			}, ARRAY_FILTER_USE_BOTH);
		}
	}
	
	$classes = array();
	if( $chart_image ){
		$classes[] = 'has-image';
	}
	
	if( !empty($chart_table) && is_array($chart_table) ){
		$classes[] = 'has-table';
	}
	?>
	<h2><?php echo esc_html__('Size guide', 'ecomall'); ?></h2>
	<div class="ts-size-chart-content <?php echo implode(' ', $classes); ?>">
		<?php
		if( $chart_label ){
			echo '<h5 class="chart-label">'.esc_html($chart_label).'</h5>';
		}
		
		if( $chart_content ){
			echo '<div class="chart-content">';
				echo wp_kses_post( $chart_content ); /* Allowed html as post content */
			echo '</div>';
		}
		
		if( $chart_image ){
			echo '<div class="chart-image">';
				echo '<img src="'.esc_url($chart_image).'" alt="'.esc_attr($chart_label).'" />';
			echo '</div>';
		}
		
		if( !empty($chart_table) && is_array($chart_table) ){
			echo '<table class="chart-table"><tbody>';
			foreach( $chart_table as $row ){
				echo '<tr>';
				foreach( $row as $col ){
					echo '<td>'.esc_html($col).'</td>';
				}
				echo '</tr>';
			}
			echo '</tbody></table>';
		}
		?>
	</div>
	<?php
}

/* Summary Custom Content */
function ecomall_product_summary_custom_content(){
	global $product;
	
	if( $product->get_type() == 'variable' ){
		$show_in_variation = apply_filters('ecomall_show_summary_custom_content_in_variation', true);
		
		static $flag = 0;
		$flag++;
		
		if( ( $show_in_variation && $flag == 1 ) || ( !$show_in_variation && $flag == 2 ) ){
			return;
		}
	}
	
	$content = get_post_meta( $product->get_id(), 'ts_prod_summary_custom_content', true );
	if( !$content ){
		$content = ecomall_get_theme_options('ts_prod_summary_custom_content');
	}
	
	if( $content ){
		echo '<div class="ts-summary-custom-content">';
		echo do_shortcode( $content );
		echo '</div>';
	}
}

/*** Product tab ***/
function ecomall_product_remove_tabs( $tabs = array() ){
	if( !ecomall_get_theme_options('ts_prod_tabs') ){
		return array();
	}
	return $tabs;
}

function ecomall_add_product_custom_tab( $tabs = array() ){
	global $post;
	$theme_options = ecomall_get_theme_options();
	$override_custom_tab = get_post_meta( $post->ID, 'ts_prod_custom_tab', true );
	
	if( $theme_options['ts_prod_custom_tab'] || $override_custom_tab ){
		if( $override_custom_tab ){
			$custom_tab_title = get_post_meta( $post->ID, 'ts_prod_custom_tab_title', true );
			$custom_tab_content = get_post_meta( $post->ID, 'ts_prod_custom_tab_content', true );
		}
		else{
			$custom_tab_title = $theme_options['ts_prod_custom_tab_title'];
			$custom_tab_content = $theme_options['ts_prod_custom_tab_content'];
		}

		if( $custom_tab_content ){
			add_filter('ecomall_woocommerce_custom_tab_content', function($arg) use ($custom_tab_content) {
				return $custom_tab_content;
			});
		}

		if( $custom_tab_title || $custom_tab_content ){
			$tabs['ts_custom'] = array(
				'title'					=> esc_html( $custom_tab_title ) 
				,'priority' 			=> 25
				,'callback' 			=> 'ecomall_product_custom_tab_content'
				,'callback_parameters' 	=> $custom_tab_title
			);
		}
	}
	
	if( $theme_options['ts_prod_size_chart'] && ecomall_get_product_size_chart_id() && wp_cache_get('ts_size_chart_is_showed') === false ){
		$tabs['ts_size_chart'] = array(
				'title'					=> esc_html__('Size guide', 'ecomall')
				,'priority' 			=> 28
				,'callback' 			=> 'ecomall_product_size_chart_content'
			);
	}
	
	$dimensions = get_post_meta( $post->ID, 'ts_prod_dimensions', true );
	if( $dimensions ){
		$dimensions = json_decode( $dimensions, true );
		if( is_array($dimensions) ){
			$dimensions = array_filter($dimensions, function($v, $k){
				return is_array($v) && array_filter($v);
			}, ARRAY_FILTER_USE_BOTH);
			
			if( !empty($dimensions) ){
				$tabs['ts_dimensions'] = array(
					'title'					=> esc_html__('Specifications', 'ecomall')
					,'priority' 			=> 15
					,'callback' 			=> 'ecomall_product_dimensions_content'
					,'dimensions' 			=> $dimensions
				);
			}
		}
	}

	return $tabs;
}

function ecomall_product_dimensions_content( $name, $tab ){
	echo '<h2>' . esc_html__('Specifications', 'ecomall') . '</h2>';
	echo '<div class="ts-dimensions-content">';
		echo '<ul>';
		foreach( $tab['dimensions'] as $row ){
			echo '<li>';
			foreach( $row as $col ){
				echo '<span>' . esc_html($col) . '</span>';
			}
			echo '</li>';
		}
		echo '</ul>';
	echo '</div>';
}

function ecomall_product_custom_tab_content($name, $tab){
	$custom_tab_content = apply_filters( 'ecomall_woocommerce_custom_tab_content', '' );

	if( $tab['callback_parameters'] ){
		echo '<h2>' . esc_html( $tab['callback_parameters'] ) . '</h2>';
	}
	
	if( $custom_tab_content ){
		echo '<div class="custom-tab-content">'. do_shortcode( $custom_tab_content ) .'</div>';
	}
}

/* Ads Banner */
function ecomall_product_ads_banner(){
	if( ecomall_get_theme_options('ts_prod_ads_banner') ){
		echo '<div class="ads-banner">';
		echo do_shortcode( ecomall_get_theme_options('ts_prod_ads_banner_content') );
		echo '</div>';
	}
}

/* Related Products */
function ecomall_output_related_products_args_filter( $args ){
	$args['posts_per_page'] = 6;
	$args['columns'] = 5;
	return $args;
}

/* Change grouped product columns */
function ecomall_woocommerce_grouped_product_columns( $columns ){
	$columns = array('label', 'price', 'quantity');
	return $columns;
}


/*** General hook ***/

/*************************************************************
* Custom group button on product (quickshop, wishlist, compare) 
* Begin tag: 	10000
* Wishlist: 	10001
* Quickshop:  	10002 
* Compare:   	10003 
* Add To Cart: 	10004
* End tag:   	10005
**************************************************************/
function ecomall_product_group_button_start(){	
	echo '<div class="product-group-button">';
}

function ecomall_product_group_button_end(){
	echo '</div>';
}

add_action('init', 'ecomall_wrap_product_group_button', 20);
function ecomall_wrap_product_group_button(){
	$theme_options = ecomall_get_theme_options();
	
	add_action('woocommerce_after_shop_loop_item_title', 'ecomall_product_group_button_start', 10000);
	add_action('woocommerce_after_shop_loop_item_title', 'ecomall_product_group_button_end', 10005);
	
	add_action('woocommerce_after_shop_loop_item', 'ecomall_template_loop_add_to_cart', 60);
	
	if( $theme_options['ts_product_hover_style'] == 'v1' ){
		add_action('woocommerce_after_shop_loop_item_title', 'ecomall_template_loop_add_to_cart', 10004);
	}
}

/* Wishlist */
if( class_exists('YITH_WCWL') ){
	function ecomall_add_wishlist_button_to_product_list(){
		echo '<div class="button-in wishlist">';
		echo do_shortcode('[yith_wcwl_add_to_wishlist]');
		echo '</div>';
	}
	
	if( 'yes' == get_option( 'yith_wcwl_show_on_loop', 'no' ) ){
		add_action( 'woocommerce_after_shop_loop_item_title', 'ecomall_add_wishlist_button_to_product_list', 10001 );

		add_filter( 'yith_wcwl_loop_positions', '__return_empty_array' ); /* Remove button which added by plugin */
	}

	add_filter('yith_wcwl_add_to_wishlist_params', 'ecomall_yith_wcwl_add_to_wishlist_params');
	function ecomall_yith_wcwl_add_to_wishlist_params( $additional_params ){
		if( isset($additional_params['container_classes']) && $additional_params['exists'] ){
			$additional_params['container_classes'] .= ' added';
		}
		$additional_params['label'] = '<span class="ts-tooltip button-tooltip" data-title="'.esc_attr__('Add to wishlist', 'ecomall').'">' . esc_html__('Wishlist', 'ecomall') . '</span>';
		return $additional_params;
	}
	
	add_filter('yith_wcwl_browse_wishlist_label', 'ecomall_yith_wcwl_browse_wishlist_label', 10, 2);
	function ecomall_yith_wcwl_browse_wishlist_label( $text = '', $product_id = 0 ){
		if( $product_id ){
			return '<span class="ts-tooltip button-tooltip" data-title="'.esc_attr__('Add to wishlist', 'ecomall').'">' . esc_html__('Wishlist', 'ecomall') . '</span>';
		}
		return $text;
	}
}

/* Compare */
if( class_exists('YITH_Woocompare') ){
	add_action('init', 'ecomall_yith_compare_handle', 30);
	function ecomall_yith_compare_handle(){
		global $yith_woocompare;
		$is_ajax = ( defined( 'DOING_AJAX' ) && DOING_AJAX );
		if( $yith_woocompare->is_frontend() || $is_ajax ){
			if( get_option('yith_woocompare_compare_button_in_products_list') == 'yes' ){
				if( $is_ajax ){
					if( defined('YITH_WOOCOMPARE_DIR') && !class_exists('YITH_Woocompare_Frontend') ){
						$compare_frontend_class = YITH_WOOCOMPARE_DIR . 'includes/class.yith-woocompare-frontend.php';
						if( file_exists($compare_frontend_class) ){
							require_once $compare_frontend_class;
						}
						$yith_woocompare->obj = new YITH_Woocompare_Frontend();
					}
				}
				remove_action( 'woocommerce_after_shop_loop_item', array( $yith_woocompare->obj, 'add_compare_link' ), 20 );
				
				add_action( 'woocommerce_after_shop_loop_item_title', 'ecomall_add_compare_button_to_product_list', 10003 );
			}
			
			add_filter( 'option_yith_woocompare_button_text', 'ecomall_compare_button_text_filter', 99 );
		}
	}
	
	function ecomall_add_compare_button_to_product_list(){
		global $yith_woocompare, $product;
		echo '<div class="button-in compare">';
		echo '<a class="compare" href="'.$yith_woocompare->obj->add_product_url( $product->get_id() ).'" data-product_id="'.$product->get_id().'">'.get_option('yith_woocompare_button_text').'</a>';
		echo '</div>';
	}
	
	function ecomall_compare_button_text_filter( $button_text ){
		return '<span class="ts-tooltip button-tooltip" data-title="'.esc_attr__('Add to compare', 'ecomall').'">'.esc_html($button_text).'</span>';
	}
}

/*************************************************************
* Group button on product meta (add to cart, wishlist, compare) 
* Begin tag: 59
* Add to cart: 60
* End tag: 70
*************************************************************/
add_action('woocommerce_after_shop_loop_item', 'ecomall_product_group_button_meta_start', 59);
add_action('woocommerce_after_shop_loop_item', 'ecomall_product_group_button_meta_end', 70);
function ecomall_product_group_button_meta_start(){
	echo '<div class="product-group-button-meta">';
}

function ecomall_product_group_button_meta_end(){
	echo '</div>';
}
/*** End General hook ***/

/*** Star Rating Template ***/
function ecomall_template_star_rating(){
	global $product;
	$count = 0;
	$review_count = $product->get_review_count();

	if( ! wc_review_ratings_enabled() ){
		return;
	}

	$rating = $product->get_average_rating();

	if( 0 < $rating ){
		$label = sprintf( __( 'Rated %s out of 5', 'ecomall' ), $rating );
	} else {
		$label = __( 'Rate this product:', 'ecomall' );
		$rating = 0;
	}

	echo '<div class="woocommerce-product-rating">';
		echo '<div class="star-rating" role="img" aria-label="' . esc_attr( $label ) . '">' . wc_get_star_rating_html( $rating, $count ) . '</div>';
		if( $rating ){
			echo '<span class="average-rating">('. esc_html($rating) .')</span>';
		}
		echo '<span class="review-count">'. sprintf( _n( '%s Review', '%s Reviews', $review_count, 'ecomall' ), $review_count ) .'</span>';
	echo '</div>';
}

/*** Quantity Input hooks ***/
add_action('woocommerce_before_quantity_input_field', 'ecomall_before_quantity_input_field', 1);
function ecomall_before_quantity_input_field(){
	?>
	<div class="number-button">
		<input type="button" value="-" class="minus" />
	<?php
}

add_action('woocommerce_after_quantity_input_field', 'ecomall_after_quantity_input_field', 99);
function ecomall_after_quantity_input_field(){
	?>
		<input type="button" value="+" class="plus" />
	</div>
	<?php
}

/*** Cart - Checkout hooks ***/
remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cross_sell_display', 10 );
add_action( 'woocommerce_after_cart', 'woocommerce_cross_sell_display', 10 );

add_action('woocommerce_cart_actions', 'ecomall_empty_cart_button');
function ecomall_empty_cart_button(){
?>
	<button type="submit" class="button empty-cart-button" name="ts_empty_cart" value="<?php esc_attr_e('Empty cart', 'ecomall'); ?>"><?php esc_html_e('Empty cart', 'ecomall'); ?></button>
<?php
}

add_action('init', 'ecomall_empty_woocommerce_cart');
function ecomall_empty_woocommerce_cart(){
	if( isset($_POST['ts_empty_cart']) ){
		WC()->cart->empty_cart();
	}
}

add_action('woocommerce_before_checkout_form', 'ecomall_before_checkout_form_start', 1);
add_action('woocommerce_before_checkout_form', 'ecomall_before_checkout_form_end', 999);
function ecomall_before_checkout_form_start(){
	echo '<div class="checkout-login-coupon-wrapper">';
}
function ecomall_before_checkout_form_end(){
	echo '</div>';
}

remove_action('woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10);
add_action('woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 20);

remove_action('woocommerce_before_checkout_form', 'woocommerce_output_all_notices', 10);
add_action('woocommerce_before_checkout_form', 'woocommerce_output_all_notices', 1000);

if( !( is_user_logged_in() || 'no' === get_option( 'woocommerce_enable_checkout_login_reminder' ) ) ){
	add_action('woocommerce_before_checkout_form', function(){
		echo '<div class="checkout-login-wrapper">';
	}, 9);
	add_action('woocommerce_before_checkout_form', function(){
		echo '</div>';
	}, 11);
}

if( function_exists('wc_coupons_enabled') && wc_coupons_enabled() ){
	add_action('woocommerce_before_checkout_form', function(){
		echo '<div class="checkout-coupon-wrapper">';
	}, 19);
	add_action('woocommerce_before_checkout_form', function(){
		echo '</div>';
	}, 21);
}

add_filter( 'woocommerce_gallery_image_size', 'ecomall_woocommerce_gallery_image_size' );
function ecomall_woocommerce_gallery_image_size( $size ){
	if( ecomall_get_theme_options('ts_prod_gallery_layout') == 'grid' ){
		$size = 'woocommerce_single';
	}
	return $size;
}

add_action( 'woocommerce_no_products_found', 'ecomall_woocommerce_no_products_found', 1 );
function ecomall_woocommerce_no_products_found(){
	if( is_search() ){
		echo '<div class="search-no-results-wrapper">';
			echo '<p>'. esc_html__('No products were found matching your selection. Check the spelling or use a different word or phrase.', 'ecomall'). '</p>';
			echo '<div class="search--form">';
				get_search_form();
			echo '</div>';
		echo '</div>';
		
		remove_action( 'woocommerce_no_products_found', 'wc_no_products_found' );
	}
}

add_filter( 'woocommerce_catalog_orderby', 'ecomall_woocommerce_catalog_orderby' );
function ecomall_woocommerce_catalog_orderby(){
    return array(
        'menu_order' 	=> esc_html__( 'Default', 'ecomall' )
		,'date'       	=> esc_html__( 'Latest', 'ecomall' )
        ,'popularity' 	=> esc_html__( 'Best Selling', 'ecomall' )
		,'rating'     	=> esc_html__( 'Best Rated', 'ecomall' )
		,'price' 		=> esc_html__( 'Price Low', 'ecomall' )
        ,'price-desc'   => esc_html__( 'Price High', 'ecomall' )
    );
}