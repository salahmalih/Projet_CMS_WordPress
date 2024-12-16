<?php
use Elementor\Controls_Manager;

class TS_Elementor_Widget_Products_In_Category_Tabs extends TS_Elementor_Widget_Base{
	public function get_name(){
        return 'ts-products-in-category-tabs';
    }
	
	public function get_title(){
        return esc_html__( 'TS Products In Category Tabs', 'themesky' );
    }
	
	public function get_categories(){
        return array( 'ts-elements', 'woocommerce-elements' );
    }
	
	public function get_icon(){
		return 'eicon-product-tabs';
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
		
		$this->end_controls_section();
		
		$this->start_controls_section(
            'section_banners'
            ,array(
                'label' 	=> esc_html__( 'Banners', 'themesky' )
                ,'tab'   	=> Controls_Manager::TAB_CONTENT
            )
        );
		
		$this->add_control(
            'banner_pos'
            ,array(
                'label' 		=> esc_html__( 'Banner Position', 'themesky' )
                ,'type' 		=> Controls_Manager::SELECT
                ,'default' 		=> 'top'
				,'options'		=> array(
									'top' 			=> esc_html__('Top', 'themesky')
									,'inside' 		=> esc_html__('Inside Products', 'themesky')
									,'bottom' 		=> esc_html__('Bottom', 'themesky')
								)		
                ,'description' 	=> esc_html__( 'Inside Products is not available in Slider', 'themesky' )
            )
        );
		
		$repeater = new Elementor\Repeater();
		
		$repeater->add_control(
			'banner_img'
			,array(
				'label' 		=> esc_html__( 'Banner Image', 'themesky' )
				,'type' 		=> Controls_Manager::MEDIA
				,'default' 		=> array( 'id' => '', 'url' => '' )
				,'description' 	=> esc_html__( 'If tab does not have banner, just leave blank', 'themesky' )
			)
		);
		
		$repeater->add_control(
			'banner_img_ipad'
			,array(
				'label' 		=> esc_html__( 'Banner Image Tablet', 'themesky' )
				,'type' 		=> Controls_Manager::MEDIA
				,'default' 		=> array( 'id' => '', 'url' => '' )
				,'description' 	=> esc_html__( 'Leave blank to use image from larger screen', 'themesky' )
			)
		);
		
		$repeater->add_control(
			'banner_img_mobile'
			,array(
				'label' 		=> esc_html__( 'Banner Image Mobile', 'themesky' )
				,'type' 		=> Controls_Manager::MEDIA
				,'default' 		=> array( 'id' => '', 'url' => '' )
				,'description' 	=> esc_html__( 'Leave blank to use image from larger screen', 'themesky' )
			)
		);
		
		$repeater->add_control(
            'banner_link'
            ,array(
                'label'     	=> esc_html__( 'Banner Link', 'themesky' )
                ,'type'     	=> Controls_Manager::URL
				,'default'  	=> array( 'url' => '', 'is_external' => true, 'nofollow' => true )
				,'show_external'=> true
				,'options'		=> false
            )
        );
		
		$this->add_control(
			'banners'
			,array(
				'label' 		=> esc_html__( 'Banners', 'themesky' )
				,'type' 		=> Controls_Manager::REPEATER
				,'fields' 		=> $repeater->get_controls()
				,'default' 		=> array()
				,'prevent_empty'=> false
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
									,'sale' 		=> esc_html__('Sale', 'themesky')
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
				,'default'  	=> 5
				,'min'      	=> 1
            )
        );
		
		$this->add_control(
            'limit'
            ,array(
                'label'     	=> esc_html__( 'Limit', 'themesky' )
                ,'type'     	=> Controls_Manager::NUMBER
				,'default'  	=> 5
				,'min'      	=> 1
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
				,'label_block' 	=> true
            )
        );
		
		$this->add_control(
            'parent_cat'
            ,array(
                'label' 		=> esc_html__( 'Parent category', 'themesky' )
                ,'type' 		=> 'ts_autocomplete'
                ,'default' 		=> array()
				,'options'		=> array()
				,'autocomplete'	=> array(
					'type'		=> 'taxonomy'
					,'name'		=> 'product_cat'
				)
				,'multiple' 	=> false
				,'sortable' 	=> false
				,'label_block' 	=> true
				,'description' 	=> esc_html__( 'Each tab will be a sub category of this category. This option is available when the Product categories option is empty', 'themesky' )
            )
        );
		
		$this->add_control(
            'include_children'
            ,array(
                'label' 		=> esc_html__( 'Include children', 'themesky' )
                ,'type' 		=> Controls_Manager::SWITCHER
                ,'default' 		=> '1'
				,'return_value' => '1'
				,'label_on'		=> 	esc_html__( 'Yes', 'themesky' )
				,'label_off'	=> 	esc_html__( 'No', 'themesky' )				
                ,'description' 	=> esc_html__( 'Load the products of sub categories in each tab', 'themesky' )
            )
        );

		$this->add_control(
			'ts_hr_2'
			,array(
                'type' 		=> Controls_Manager::DIVIDER
            )
		);
		
		$this->add_product_meta_controls();
		
		$this->add_control(
			'ts_hr_3'
			,array(
                'type' 		=> Controls_Manager::DIVIDER
            )
		);
		
		$this->add_product_color_swatch_controls();
		
		$this->add_control(
			'ts_hr_4'
			,array(
                'type' 		=> Controls_Manager::DIVIDER
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
            'shop_more_button_text'
            ,array(
                'label' 		=> esc_html__( 'Shop more button label', 'themesky' )
                ,'type' 		=> Controls_Manager::TEXT
                ,'default' 		=> 'See All'		
                ,'description' 	=> ''
            )
        );
		
		$this->add_control(
            'show_general_tab'
            ,array(
                'label' 		=> esc_html__( 'Show general tab', 'themesky' )
                ,'type' 		=> Controls_Manager::SWITCHER
                ,'default' 		=> '0'
				,'return_value' => '1'
				,'label_on'		=> 	esc_html__( 'Yes', 'themesky' )
				,'label_off'	=> 	esc_html__( 'No', 'themesky' )			
                ,'description' 	=> esc_html__( 'Get products from all categories or sub categories', 'themesky' )
            )
        );
		
		$this->add_control(
            'general_tab_heading'
            ,array(
                'label' 		=> esc_html__( 'General tab heading', 'themesky' )
                ,'type' 		=> Controls_Manager::TEXT
                ,'default' 		=> ''		
                ,'description' 	=> ''
            )
        );
		
		$this->add_control(
            'product_type_general_tab'
            ,array(
                'label' 		=> esc_html__( 'Product type of general tab', 'themesky' )
                ,'type' 		=> Controls_Manager::SELECT
                ,'default' 		=> 'recent'
				,'options'		=> array(
									'recent' 		=> esc_html__('Recent', 'themesky')
									,'sale' 		=> esc_html__('Sale', 'themesky')
									,'featured' 	=> esc_html__('Featured', 'themesky')
									,'best_selling' => esc_html__('Best Selling', 'themesky')
									,'top_rated' 	=> esc_html__('Top Rated', 'themesky')
									,'mixed_order' 	=> esc_html__('Mixed Order', 'themesky')
								)		
                ,'description' 	=> esc_html__( 'Select type of product', 'themesky' )
            )
        );
		
		$this->add_control(
            'show_shop_more_general_tab'
            ,array(
                'label' 		=> esc_html__( 'Show shop more button in general tab', 'themesky' )
                ,'type' 		=> Controls_Manager::SWITCHER
                ,'default' 		=> '0'
				,'return_value' => '1'
				,'label_on'		=> 	esc_html__( 'Yes', 'themesky' )
				,'label_off'	=> 	esc_html__( 'No', 'themesky' )			
                ,'description' 	=> ''
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
			'lazy_load'						=> 0
			,'title'						=> ''
			,'banner_pos'					=> 'top'
			,'banners'						=> array()
			,'product_layout' 				=> 'grid'
			,'product_type'					=> 'recent'
			,'columns' 						=> 5
			,'limit' 						=> 5
			,'product_cats'					=> array()
			,'parent_cat' 					=> array()
			,'include_children' 			=> 0
			,'show_general_tab' 			=> 0
			,'general_tab_heading' 			=> ''
			,'product_type_general_tab' 	=> 'recent'
			,'show_image' 					=> 1
			,'show_title' 					=> 1
			,'show_sku' 					=> 0
			,'show_price' 					=> 1
			,'show_short_desc'  			=> 0
			,'show_rating' 					=> 0
			,'show_label' 					=> 1
			,'label_pos' 					=> 'default'
			,'show_categories'				=> 0
			,'show_brands'					=> 1			
			,'show_add_to_cart' 			=> 1
			,'show_border' 					=> 'default'
			,'border_primary_color' 		=> 0
			,'show_color_swatch' 			=> 0
			,'number_color_swatch' 			=> 3
			,'show_shop_more_button' 		=> 0
			,'show_shop_more_general_tab' 	=> 0
			,'shop_more_button_text' 		=> 'See all'
			,'is_slider' 					=> 0
			,'only_slider_mobile'			=> 0
			,'rows' 						=> 1
			,'show_nav' 					=> 0
			,'show_dots' 					=> 0
			,'auto_play' 					=> 0
			,'loop'							=> 1
			,'disable_slider_responsive'	=> 0
		);
		
		$settings = wp_parse_args( $settings, $default );
		
		extract( $settings );
		
		if ( !class_exists('WooCommerce') ){
			return;
		}
		
		$is_elementor_editor = ( isset($_GET['action']) && $_GET['action'] == 'elementor' ) || wp_doing_ajax();
		
		$product_cats = implode(',', $product_cats);
		$parent_cat = is_array($parent_cat) ? implode('', $parent_cat) : $parent_cat;
		
		if( !$product_cats && !$parent_cat ){
			if( $is_elementor_editor ){
				esc_html_e( 'Please select at least one product category', 'themesky' );
			}
			return;
		}
		
		if( !$product_cats ){
			$args = array(
				'taxonomy'	=> 'product_cat'
				,'parent'	=> $parent_cat
				,'fields'	=> 'ids'
				,'orderby'	=> 'none'
			);

			$sub_cats = get_terms($args);

			if( is_array($sub_cats) && !empty($sub_cats) ){
				$product_cats = implode(',', $sub_cats);
			}
			else{
				if( $is_elementor_editor ){
					esc_html_e( 'The selected parent category does not have children', 'themesky' );
				}
				return;
			}
		}
		else{
			$parent_cat = '';
		}
		
		if( $this->lazy_load_placeholder( $settings, 'product-tabs' ) ){
			return;
		}
		
		/* Banners */
		$has_banner = false;
		if( $banners ){
			$list_banners = array();
			foreach( $banners as $i => $banner ){
				$list_banners[] = $banner['banner_img']['id'] . '|' . $banner['banner_img_ipad']['id'] . '|' . $banner['banner_img_mobile']['id'] . '|' . $banner['banner_link']['url'];
				if( 0 == $i && $banner['banner_img']['id'] ){
					$has_banner = true;
				}
			}
			$tab_banners = implode(',', $list_banners);
		}
		else{
			$tab_banners = '';
		}
		
		if( $only_slider_mobile && !wp_is_mobile() ){
			$is_slider = 0;
		}
		
		if( $banner_pos == 'inside' && $is_slider ){
			$banner_pos = 'bottom';
		}
		
		$atts = compact('product_type', 'banner_pos', 'columns', 'rows', 'limit' ,'product_cats', 'tab_banners', 'include_children', 'product_layout'
						,'show_image', 'show_title', 'show_sku', 'show_price', 'show_short_desc', 'show_rating', 'show_label', 'label_pos', 'show_categories', 'show_brands', 'show_add_to_cart', 'show_color_swatch', 'number_color_swatch'
						,'show_shop_more_button', 'show_shop_more_general_tab', 'show_general_tab', 'product_type_general_tab', 'is_slider', 'show_nav', 'auto_play');
		
		$classes = array();
		$classes[] = 'ts-product-in-category-tab-wrapper ts-shortcode ts-product woocommerce';
		$classes[] = $product_type;
		$classes[] = $product_layout;
		$classes[] = 'banner-' . $banner_pos;
		$classes[] = 'border-' . $show_border;
		
		if( $border_primary_color ){
			$classes[] = 'border-primary';
		}
		
		if( $has_banner ){
			$classes[] = 'has-banner';
		}
		
		if( $show_color_swatch ){
			$classes[] = 'show-color-swatch';
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

		$data_attr = array();
		if( $is_slider ){
			$data_attr[] = 'data-nav="'.$show_nav.'"';
			$data_attr[] = 'data-dots="'.$show_dots.'"';
			$data_attr[] = 'data-autoplay="'.$auto_play.'"';
			$data_attr[] = 'data-loop="'.$loop.'"';
			$data_attr[] = 'data-columns="'.$columns.'"';
			$data_attr[] = 'data-disable_responsive="'.$disable_slider_responsive.'"';
		}

		$current_cat = '';
		$is_general_tab = false;
		$shop_more_link = '#';
		
		$rand_id = 'ts-product-in-category-tab-' . mt_rand(0, 1000);
		?>
		<div class="<?php echo esc_attr(implode(' ', $classes)); ?>" id="<?php echo esc_attr($rand_id) ?>" style="--ts-columns: <?php echo esc_attr($columns) ?>" data-atts="<?php echo htmlentities(json_encode($atts)); ?>" <?php echo implode(' ', $data_attr); ?>>
			
			<div class="column-tabs">
				
				<div class="list-categories">
				
					<?php if( $title ): ?>
					<header class="heading-tab">
						<h3 class="heading-title">
							<?php echo esc_html($title); ?>
						</h3>
					</header>
					<?php endif; ?>
					
					<ul class="tabs">
					<?php
					if( $show_general_tab ){
						if( $parent_cat ){
							$current_cat = $parent_cat;
							$shop_more_link = get_term_link((int)$parent_cat, 'product_cat');
							if( is_wp_error($shop_more_link) ){
								$shop_more_link = wc_get_page_permalink('shop');
							}
						}
						else{
							$current_cat = $product_cats;
							$shop_more_link = wc_get_page_permalink('shop');
						}

						$is_general_tab = true;
					?>
						<li class="tab-item general-tab current" data-product_cat="<?php echo esc_attr( $current_cat ); ?>" data-link="<?php echo esc_url($shop_more_link) ?>">
							<span><?php echo esc_html($general_tab_heading) ?></span>
						</li>
					<?php
					}

					$product_cats = array_map('trim', explode(',', $product_cats));
					foreach( $product_cats as $k => $product_cat ):
						$term = get_term_by( 'term_id', $product_cat, 'product_cat');
						if( !isset($term->name) ){
							continue;
						}
						
						$current_tab = false;
						if( $current_cat == '' ){
							$current_tab = true;
							$current_cat = $product_cat;
							$shop_more_link = get_term_link($term, 'product_cat');
						}
					?>
						<li class="tab-item <?php echo ($current_tab)?'current':''; ?>" data-product_cat="<?php echo esc_attr($product_cat) ?>" data-link="<?php echo esc_url(get_term_link($term, 'product_cat')) ?>">
							<span><?php echo esc_html($term->name) ?></span>
						</li>
					<?php
					endforeach;
					?>
					</ul>
				</div>
			</div>

			<div class="column-content">
			
				<div class="column-products woocommerce columns-<?php echo esc_attr($columns) ?> <?php echo $is_slider?'loading':''; ?>">
					<?php 
						ts_get_product_content_in_category_tab($atts, $current_cat, $is_general_tab);
					?>
				</div>
				
				<?php if( $show_shop_more_button ): ?>
				<div class="shop-more">
					<a class="shop-more-button" href="<?php echo esc_url($shop_more_link) ?>"><?php echo esc_html($shop_more_button_text) ?></a>
				</div>
				<?php endif; ?>
				
			</div>
			
		</div>
		<?php
	}
}

$widgets_manager->register( new TS_Elementor_Widget_Products_In_Category_Tabs() );