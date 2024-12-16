<?php
use Elementor\Controls_Manager;

class TS_Elementor_Widget_Product_Deals extends TS_Elementor_Widget_Base{
	public function get_name(){
        return 'ts-product-deals';
    }
	
	public function get_title(){
        return esc_html__( 'TS Product Deals', 'themesky' );
    }
	
	public function get_categories(){
        return array( 'ts-elements', 'woocommerce-elements' );
    }
	
	public function get_icon(){
		return 'eicon-product-upsell';
	}
	
	protected function register_controls(){
		$this->start_controls_section(
            'section_general'
            ,array(
                'label' 	=> esc_html__( 'General', 'themesky' )
                ,'tab'   	=> Controls_Manager::TAB_CONTENT
            )
        );
		
		$this->add_lazy_load_controls( array( 'thumb-height' => 260 ) );
		
		$this->add_title_and_style_controls();

		$this->add_control(
            'heading_pos'
            ,array(
                'label' 		=> esc_html__( 'Heading Position', 'themesky' )
                ,'type' 		=> Controls_Manager::SELECT
                ,'default' 		=> 'top'
				,'options'		=> array(
									'top' 		=> esc_html__('Top', 'themesky')
									,'left' 	=> esc_html__('Left Of Products', 'themesky')
									,'center' 	=> esc_html__('Inner Of Products', 'themesky')
								)		
                ,'description' 	=> ''
            )
        );
		
		$this->add_control(
            'sub_title'
            ,array(
                'label' 		=> esc_html__( 'Sub Title', 'themesky' )
                ,'type' 		=> Controls_Manager::TEXT
                ,'default' 		=> ''		
                ,'description' 	=> ''
				,'condition'	=> array( 'heading_pos!' => 'top' )
            )
        );
		
		$this->add_control(
            'sub_title_color'
            ,array(
                'label'     	=> esc_html__( 'Sub Title Color', 'themesky' )
                ,'type'     	=> Controls_Manager::COLOR
				,'default'  	=> ''
				,'selectors'	=> array(
					'{{WRAPPER}} .shortcode-heading-wrapper .sub-title' => 'color: {{VALUE}}'
				)
				,'condition'	=> array( 'heading_pos!' => 'top' )
            )
        );
		
		$this->add_control(
            'description'
            ,array(
                'label' 		=> esc_html__( 'Description', 'themesky' )
                ,'type' 		=> Controls_Manager::TEXT
                ,'default' 		=> ''		
                ,'description' 	=> ''
				,'condition'	=> array( 'heading_pos!' => 'top' )
            )
        );
		
		$this->add_control(
            'description_color'
            ,array(
                'label'     	=> esc_html__( 'Description Color', 'themesky' )
                ,'type'     	=> Controls_Manager::COLOR
				,'default'  	=> ''
				,'selectors'	=> array(
					'{{WRAPPER}} .shortcode-heading-wrapper .description' => 'color: {{VALUE}}'
				)
				,'condition'	=> array( 'heading_pos!' => 'top' )
            )
        );
		
		$this->add_responsive_control(
            'bg_img'
            ,array(
                'label' 		=> esc_html__( 'Background Image', 'themesky' )
                ,'type' 		=> Controls_Manager::MEDIA
                ,'default' 		=> array( 'id' => '', 'url' => '' )		
                ,'description' 	=> ''
				,'selectors'	=> array(
					'{{WRAPPER}} .shortcode-heading-wrapper' => 'background-image: url("{{URL}}");border: 0;'
				)
				,'condition'	=> array( 'heading_pos!' => 'top' )
            )
        );

		$this->end_controls_section();
		
		$this->start_controls_section(
            'section_counter'
            ,array(
                'label' 	=> esc_html__( 'Counter', 'themesky' )
                ,'tab'   	=> Controls_Manager::TAB_CONTENT
            )
        );	

		$this->add_control(
            'show_counter'
            ,array(
                'label' 		=> esc_html__( 'Show counter', 'themesky' )
                ,'type' 		=> Controls_Manager::SWITCHER
                ,'default' 		=> '1'
				,'return_value' => '1'
				,'label_on'		=> 	esc_html__( 'Yes', 'themesky' )
				,'label_off'	=> 	esc_html__( 'No', 'themesky' )			
                ,'description' 	=> esc_html__( 'Show counter on each product', 'themesky' )
            )
        );

		$this->add_control(
            'show_counter_today'
            ,array(
                'label' 		=> esc_html__( 'Show counter today', 'themesky' )
                ,'type' 		=> Controls_Manager::SWITCHER
                ,'default' 		=> '0'
				,'return_value' => '1'
				,'label_on'		=> 	esc_html__( 'Yes', 'themesky' )
				,'label_off'	=> 	esc_html__( 'No', 'themesky' )			
                ,'description' 	=> esc_html__( 'Show the counter beside heading title. The counter of each product will be hidden', 'themesky' )
            )
        );
		
		$this->add_control(
            'counter_bg_color'
            ,array(
                'label'     	=> esc_html__( 'Counter Background Color', 'themesky' )
                ,'type'     	=> Controls_Manager::COLOR
				,'default'  	=> ''
				,'selectors'	=> array(
					'{{WRAPPER}} .heading-top.show-counter-today .counter-wrapper > div, {{WRAPPER}} .counter-wrapper .number-wrapper' => 'background: {{VALUE}}'
				)
            )
        );
		
		$this->add_control(
            'counter_color'
            ,array(
                'label'     	=> esc_html__( 'Counter Text Color', 'themesky' )
                ,'type'     	=> Controls_Manager::COLOR
				,'default'  	=> ''
				,'selectors'	=> array(
					'{{WRAPPER}} .counter-wrapper > div' => 'color: {{VALUE}}'
				)
            )
        );
		
		$this->add_control(
            'counter_number_color'
            ,array(
                'label'     	=> esc_html__( 'Counter Number Color', 'themesky' )
                ,'type'     	=> Controls_Manager::COLOR
				,'default'  	=> ''
				,'selectors'	=> array(
					'{{WRAPPER}} .counter-wrapper > div .number-wrapper, {{WRAPPER}} .heading-top.show-counter-today .counter-wrapper > div .number-wrapper' => 'color: {{VALUE}}'
				)
            )
        );
		
		$this->end_controls_section();
		
		$this->start_controls_section(
            'section_products'
            ,array(
                'label' 	=> esc_html__( 'Products', 'themesky' )
                ,'tab'   	=> Controls_Manager::TAB_CONTENT
            )
        );
		
		$this->add_control(
            'product_layout'
            ,array(
                'label' 		=> esc_html__( 'Product Layout', 'themesky' )
                ,'type' 		=> Controls_Manager::SELECT
                ,'default' 		=> 'grid'
				,'options'		=> array(
									'grid' 	=> esc_html__('Grid', 'themesky')
									,'list' => esc_html__('List', 'themesky')
								)		
                ,'description' 	=> ''
            )
        );
		
		$this->add_control(
            'product_type'
            ,array(
                'label' 		=> esc_html__( 'Product type', 'themesky' )
                ,'type' 		=> Controls_Manager::SELECT
                ,'default' 		=> 'recent'
				,'options'		=> array(
									'recent' 		=> esc_html__('Recent', 'themesky')
									,'featured' 	=> esc_html__('Featured', 'themesky')
									,'best_selling' => esc_html__('Best Selling', 'themesky')
									,'top_rated' 	=> esc_html__('Top Rated', 'themesky')
									,'mixed_order' 	=> esc_html__('Mixed Order', 'themesky')
								)		
                ,'description' 	=> esc_html__( 'Select type of product', 'themesky' )
            )
        );
		
		$this->add_control(
            'columns'
            ,array(
                'label'     	=> esc_html__( 'Columns', 'themesky' )
                ,'type'     	=> Controls_Manager::NUMBER
				,'default'  	=> 4
				,'min'      	=> 1
				,'condition'	=> array( 'heading_pos!' => 'center' )
            )
        );
		
		$this->add_control(
            'limit'
            ,array(
                'label'     	=> esc_html__( 'Limit', 'themesky' )
                ,'type'     	=> Controls_Manager::NUMBER
				,'default'  	=> 4
				,'min'      	=> 1
				,'description' 	=> esc_html__( 'Number of Products', 'themesky' )
            )
        );
		
		$this->add_control(
            'product_cats'
            ,array(
                'label' 		=> esc_html__( 'Product categories', 'themesky' )
                ,'type' 		=> 'ts_autocomplete'
                ,'default' 		=> array()
				,'options'		=> array()
				,'autocomplete'	=> array(
					'type'		=> 'taxonomy'
					,'name'		=> 'product_cat'
				)
				,'multiple' 	=> true
				,'sortable' 	=> false
				,'label_block' 	=> true
            )
        );

		$this->add_control(
			'filter_products'
			,array(
                'label' 		=> esc_html__( 'Filter products', 'themesky' )
                ,'type' 		=> Controls_Manager::SELECT
                ,'default' 		=> '0'
				,'options'		=> array(
									'specific_products'	=> esc_html__( 'Specific products', 'themesky' )
									,'except_products'		=> esc_html__( 'Except for products', 'themesky' )
								)			
                ,'description' 	=> ''
            )
		);

		$this->add_control(
            'ids'
            ,array(
                'label' 		=> esc_html__( 'Specific products', 'themesky' )
                ,'type' 		=> 'ts_autocomplete'
                ,'default' 		=> array()
				,'options'		=> array()
				,'autocomplete'	=> array(
					'type'		=> 'post'
					,'name'		=> 'product'
				)
				,'multiple' 	=> true
				,'sortable' 	=> false
				,'label_block' 	=> true
				,'condition'	=> array( 'filter_products' => 'specific_products' )
            )
        );
		
		$this->add_control(
			'except_products'
			,array(
				'label'			=> esc_html__( 'Except for Products', 'themesky' )
				,'type'			=> 'ts_autocomplete'
				,'default'		=> array()
				,'options'		=> array()
				,'autocomplete'	=> array(
					'type'		=> 'post'
					,'name'		=> 'product'
				)
				,'multiple' 	=> true
				,'sortable' 	=> false
				,'label_block' 	=> true
				,'condition'	=> array( 'filter_products' => 'except_products' )
			)
		);

		$this->add_product_meta_controls();
		
		$this->add_control(
            'show_availability_bar'
            ,array(
                'label' 		=> esc_html__( 'Show availability bar', 'themesky' )
                ,'type' 		=> Controls_Manager::SWITCHER
                ,'default' 		=> '1'
				,'return_value' => '1'
				,'label_on'		=> 	esc_html__( 'Yes', 'themesky' )
				,'label_off'	=> 	esc_html__( 'No', 'themesky' )			
                ,'description' 	=> ''
            )
        );
		
		$this->add_product_color_swatch_controls();
		
		$this->add_control(
            'show_gallery'
            ,array(
                'label' 		=> esc_html__( 'Product Galleries', 'themesky' )
                ,'type' 		=> Controls_Manager::SWITCHER
                ,'default' 		=> '0'
				,'return_value' => '1'
				,'label_on'		=> esc_html__( 'Show', 'themesky' )
				,'label_off'	=> esc_html__( 'Hide', 'themesky' )
				,'description' 	=> esc_html__( 'Please note that many images may make your site slower', 'themesky' )
            )
        );
		
		$this->add_control(
            'number_gallery'
            ,array(
                'label'     	=> esc_html__( 'Number of Product Galleries', 'themesky' )
                ,'type'     	=> Controls_Manager::NUMBER
				,'default'  	=> 4
				,'min'      	=> -1
				,'condition' 	=> array( 'show_gallery' => '1' )
            )
        );
		
		$this->add_control(
            'show_shop_more_button'
            ,array(
                'label' 		=> esc_html__( 'Show shop more button', 'themesky' )
                ,'type' 		=> Controls_Manager::SWITCHER
                ,'default' 		=> '0'
				,'return_value' => '1'
				,'label_on'		=> 	esc_html__( 'Yes', 'themesky' )
				,'label_off'	=> 	esc_html__( 'No', 'themesky' )	
                ,'description' 	=> ''
            )
        );
		
		$this->add_control(
			'shop_more_button_link'
			,array(	
				'label'		 		=> esc_html__( 'Shop more button link', 'themesky' )
				,'type'				=> Controls_Manager::URL
				,'default'			=> array( 'url'	=> '', 'is_external' => true, 'nofollow' => true )
				,'show_external' 	=> true
				,'condition'		=> array( 'show_shop_more_button' => '1' )
			)
		);
		
		$this->add_control(
            'shop_more_button_text'
            ,array(
                'label' 		=> esc_html__( 'Shop more button label', 'themesky' )
                ,'type' 		=> Controls_Manager::TEXT
                ,'default' 		=> 'See All'		
                ,'description' 	=> ''
				,'condition'	=> array( 'show_shop_more_button' => '1' )
            )
        );
		
		$this->end_controls_section();
		
		$this->start_controls_section(
            'section_slider'
            ,array(
                'label' 	=> esc_html__( 'Slider', 'themesky' )
                ,'tab'   	=> Controls_Manager::TAB_CONTENT
            )
        );
		
		$this->add_product_slider_controls();

		$this->end_controls_section();
	}
	
	protected function render(){
		$settings = $this->get_settings_for_display();
		
		$default = array(
			'lazy_load'				=> 0
			,'title'				=> ''
			,'sub_title' 			=> ''
			,'description' 			=> ''
			,'bg_img' 				=> array( 'id' => '', 'url' => '' )
			,'heading_pos' 			=> 'top'
			,'product_layout' 		=> 'grid'
			,'product_type'			=> 'recent'
			,'columns' 				=> 5
			,'limit' 				=> 5
			,'product_cats'			=> array()
			,'filter_products'		=> 'specific_products'
			,'ids'					=> array()
			,'except_products'		=> array()
			,'show_counter'			=> 1
			,'show_counter_today'	=> 0
			,'show_availability_bar'=> 1
			,'show_image' 			=> 1
			,'show_title' 			=> 1
			,'show_sku' 			=> 0
			,'show_price' 			=> 1
			,'show_short_desc'  	=> 0
			,'show_rating' 			=> 0
			,'show_label' 			=> 1	
			,'label_pos' 			=> 'default'	
			,'show_categories'		=> 0	
			,'show_brands'			=> 1	
			,'show_add_to_cart' 	=> 1
			,'show_border' 			=> 'default'
			,'border_primary_color' => 0
			,'show_color_swatch'	=> 0
			,'number_color_swatch'	=> 3
			,'show_gallery'			=> 0
			,'number_gallery'		=> 4
			,'show_shop_more_button' => 0
			,'shop_more_button_link' => array( 'url' => '', 'is_external' => true, 'nofollow' => true )
			,'shop_more_button_text' => 'See All'
			,'is_slider' 					=> 0
			,'only_slider_mobile'			=> 0
			,'rows' 						=> 1
			,'show_dots' 					=> 0
			,'show_nav' 					=> 0
			,'auto_play' 					=> 0
			,'loop'							=> 1
			,'disable_slider_responsive'	=> 0
		);
		
		$settings = wp_parse_args( $settings, $default );
		
		extract( $settings );
		
		if( !class_exists('WooCommerce') ){
			return;
		}
		
		$product_ids_on_sale = ts_get_product_deals_ids();
		
		if( $ids ){
			$product_ids_on_sale = array_intersect($product_ids_on_sale, $ids);
		}
		
		if( $except_products ){
			$product_ids_on_sale = array_diff($product_ids_on_sale, $except_products);
		}

		if( !$product_ids_on_sale ){
			return;
		}
		
		if( $this->lazy_load_placeholder( $settings, 'product-deals' ) ){
			return;
		}
	
		if( $only_slider_mobile && !wp_is_mobile() ){
			$is_slider = false;
		}
	
		if( $show_counter_today ){
			$show_counter = 0;
		}
		
		if( $show_counter ){
			add_action('woocommerce_after_shop_loop_item', 'ts_template_loop_time_deals', 45);
		}
		
		if( $show_availability_bar ){
			add_action('woocommerce_after_shop_loop_item', 'ts_product_availability_bar', 44);
		}
		
		if( $show_gallery ){
			add_action('woocommerce_after_shop_loop_item_title', array($this, 'product_gallery'), 11000);
			add_filter('ts_loop_product_gallery_number', function() use ($number_gallery){
				return $number_gallery;
			});
		}

		/* Remove hook */
		$options = array(
				'show_image'			=> $show_image
				,'show_label'			=> $show_label
				,'label_pos'			=> $label_pos
				,'show_title'			=> $show_title
				,'show_sku'				=> $show_sku
				,'show_price'			=> $show_price
				,'show_short_desc'		=> $show_short_desc
				,'show_categories'		=> $show_categories
				,'show_brands'			=> $show_brands
				,'show_rating'			=> $show_rating
				,'show_add_to_cart'		=> $show_add_to_cart
				,'show_color_swatch'	=> $show_color_swatch
				,'number_color_swatch'	=> $number_color_swatch
				,'product_layout'		=> $product_layout
			);
		ts_remove_product_hooks( $options );

		global $post, $product;
		if( (int)$columns <= 0 ){
			$columns = 5;
		}
		
		$old_woocommerce_loop_columns = wc_get_loop_prop('columns');
		wc_set_loop_prop('columns', $columns);
		
		$args = array(
			'post_type'				=> 'product'
			,'post_status' 			=> 'publish'
			,'posts_per_page' 		=> $limit
			,'orderby' 				=> 'date'
			,'order' 				=> 'desc'
			,'post__in'				=> $product_ids_on_sale
			,'meta_query' 			=> WC()->query->get_meta_query()
			,'tax_query'           	=> WC()->query->get_tax_query()
		);
		
		ts_filter_product_by_product_type($args, $product_type);
		
		if( $product_cats ){
			$args['tax_query'][] = array(
							'taxonomy' 	=> 'product_cat'
							,'terms' 	=> $product_cats
							,'field' 	=> 'term_id'
						);
		}
		
		$link_attr = $this->generate_link_attributes( $shop_more_button_link );
		
		$products = new WP_Query($args);
		
		if( $products->have_posts() ): 
			$classes = array();
			$classes[] = 'ts-product-deals-wrapper ts-shortcode ts-product woocommerce';
			$classes[] = 'columns-' . $columns;
			$classes[] = $show_image?'':'no-thumbnail';
			$classes[] = 'heading-' . $heading_pos;
			$classes[] = $product_layout;
			$classes[] = 'border-' . $show_border;
			$classes[] = $show_counter_today?'show-counter-today':'';
			
			if( $border_primary_color ){
				$classes[] = 'border-primary';
			}
			
			if( $show_color_swatch ){
				$classes[] = 'show-color-swatch';
			}
			
			if( $show_gallery ){
				$classes[] = 'show-gallery';
			}
			
			if( $products->post_count % 2 == 1 ){
				$classes[] = 'odd';
			}
			
			if( $is_slider ){
				$classes[] = 'ts-slider';
				$classes[] = 'rows-'.$rows;
				if( $show_nav ){
					$classes[] = 'show-nav';
				}
				if( $show_dots ){
					$classes[] = 'show-dots';
				}
			}

			$classes = array_filter($classes);
			
			$data_attr = array();
			if( $is_slider ){
				$data_attr[] = 'data-nav="'.$show_nav.'"';
				$data_attr[] = 'data-dots="'.$show_dots.'"';
				$data_attr[] = 'data-autoplay="'.$auto_play.'"';
				$data_attr[] = 'data-loop="'.$loop.'"';
				$data_attr[] = 'data-columns="'.$columns.'"';
				$data_attr[] = 'data-disable_responsive="'.$disable_slider_responsive.'"';
			}			
			?>
			<div class="<?php echo esc_attr( implode(' ', $classes) ); ?>" style="--ts-columns: <?php echo esc_attr($columns) ?>" <?php echo implode(' ', $data_attr); ?>>
				
				<?php 
					if( $heading_pos != 'center' || $is_slider ){
						$this->header_html( $title, $show_counter_today, $sub_title, $description );
					}					
				?>
				
				<div class="content-wrapper <?php echo ($is_slider)?'loading':''; ?>">
					<?php woocommerce_product_loop_start(); ?>

					<?php 
						if( !$is_slider && $heading_pos == 'center' ){
							$this->header_html( $title, $show_counter_today, $sub_title, $description );
						}					
					?>

					<?php
						$count = 0;
						while( $products->have_posts() ){
							$products->the_post();
							
							if( $is_slider && $rows > 1 && $count % $rows == 0 ){
								echo '<div class="product-group">';
							}
							
							wc_get_template_part( 'content', 'product' );
							
							if( $is_slider && $rows > 1 && ($count % $rows == $rows - 1 || $count == $products->post_count - 1) ){
								echo '</div>';
							}
						
							$count++;
						}
					?>			

					<?php woocommerce_product_loop_end(); ?>
				</div>
				
				<?php if( $show_shop_more_button ): ?>
				<div class="shop-more">
					<a class="shop-more-button" <?php echo implode( ' ', $link_attr ) ?>><?php echo esc_html($shop_more_button_text); ?></a>
				</div>
				<?php endif; ?>
				
			</div>
			<?php
		endif;
		
		wp_reset_postdata();
		
		/* restore hooks */
		if( $show_counter ){
			remove_action('woocommerce_after_shop_loop_item', 'ts_template_loop_time_deals', 45);
		}
		
		if( $show_availability_bar ){
			remove_action('woocommerce_after_shop_loop_item', 'ts_product_availability_bar', 44);
		}
		
		if( $show_gallery ){
			remove_action('woocommerce_after_shop_loop_item_title', array($this, 'product_gallery'), 11000);
			remove_all_filters('ts_loop_product_gallery_number');
		}

		ts_restore_product_hooks();

		wc_set_loop_prop('columns', $old_woocommerce_loop_columns);
	}
	
	function header_html( $title = true, $counter = false, $sub_title = false, $description = false ){
		if( $title || $counter ){
			echo ($title)?'<header class="shortcode-heading-wrapper">':'<div class="shortcode-heading-wrapper">';
			if( $sub_title ){
				echo '<div class="sub-title">'. esc_html($sub_title) .'</div>';
			}
			if( $title ){
				echo '<h3 class="shortcode-title">'. esc_html($title) .'</h3>';
			}
			if( $description ){
				echo '<div class="description">'. esc_html($description) .'</div>';
			}
			if( $counter ){
				$current_time = current_time('timestamp');
				$total_seconds_of_day = 60 * 60 * 24;
				$pass_second = $current_time % $total_seconds_of_day;
				$remain_second = $total_seconds_of_day - $pass_second;
				ts_countdown(array( 'seconds' => $remain_second ));
			}
			echo ($title)?'</header>':'</div>';
		}
	}
	
	function product_gallery(){
		global $product;
		$main_image_id = $product->get_image_id();
		$galleries = $product->get_gallery_image_ids();
		if( is_array($galleries) && $main_image_id ){
			array_unshift( $galleries, $main_image_id );
		}
		
		if( empty($galleries) ){
			return;
		}
		
		$number = apply_filters('ts_loop_product_gallery_number', 4);
		
		if( $number != -1 && $number < count($galleries) ){
			$galleries = array_slice( $galleries, 0, $number );
		}
		
		$dimensions = wc_get_image_size( 'woocommerce_thumbnail' );
		
		$images = '';
		
		foreach( $galleries as $i => $id ){
			$img_url = wp_get_attachment_image_url( $id, 'woocommerce_thumbnail' );
			if( $img_url ){
				$images .= '<div data-thumb="' . esc_url($img_url) . '" class="' . ( $main_image_id && 0 == $i ? 'active' : '' ) . '">';
				$images .= '<img src="' . esc_url($img_url) . '" loading="lazy" alt="" width="'.$dimensions['width'].'" height="'.$dimensions['height'].'" />';
				$images .= '</div>';
			}
		}
		
		if( $images ){
			echo '<div class="ts-product-galleries">' . $images . '</div>';
		}
	}
}

$widgets_manager->register( new TS_Elementor_Widget_Product_Deals() );