<?php
use Elementor\Controls_Manager;

class TS_Elementor_Widget_Products_In_Product_Type_Tabs extends TS_Elementor_Widget_Base{
	public function get_name(){
        return 'ts-products-in-product-type-tabs';
    }
	
	public function get_title(){
        return esc_html__( 'TS Products In Product Type Tabs', 'themesky' );
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
		
		$repeater = new Elementor\Repeater();
		
		$repeater->add_control(
			'heading'
			,array(
				'label' 		=> esc_html__( 'Heading', 'themesky' )
				,'type' 		=> Controls_Manager::TEXT
				,'default' 		=> 'Tab Heading'
				,'description' 	=> ''
			)
		);
		
		$repeater->add_control(
			'product_type'
			,array(
				'label' 		=> esc_html__( 'Product Type', 'themesky' )
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
				,'description' 	=> ''
			)
		);
		
		$repeater->add_control(
			'shop_more_button_link'
			,array(	
				'label'		 		=> esc_html__( 'Shop more button link', 'themesky' )
				,'type'				=> Controls_Manager::URL
				,'default'			=> array( 'url'	=> '', 'is_external' => true, 'nofollow' => true )
				,'show_external' 	=> true
			)
		);
		
		$repeater->add_control(
            'shop_more_button_text'
            ,array(
                'label' 		=> esc_html__( 'Shop more button label', 'themesky' )
                ,'type' 		=> Controls_Manager::TEXT
                ,'default' 		=> 'See All'		
                ,'description' 	=> ''
            )
        );
		
		$this->add_control(
			'tabs'
			,array(
				'label' 	=> esc_html__( 'Tabs', 'themesky' )
				,'type' 	=> Controls_Manager::REPEATER
				,'fields' 	=> $repeater->get_controls()
				,'default' 	=> array(
					array(
						'heading' 		=> 'Featured Products'
						,'product_type' => 'featured'
					)
					,array(
						'heading' 		=> 'New Products'
						,'product_type' => 'recent'
					)
				)
				,'title_field' => '{{{ heading }}}'
			)
		);
		
		$this->add_control(
            'active_tab'
            ,array(
                'label' 		=> esc_html__( 'Active tab', 'themesky' )
                ,'type' 		=> Controls_Manager::SELECT
                ,'default' 		=> '1'
				,'options'		=> array(
									'1'		=> '1'
									,'2'	=> '2'
									,'3'	=> '3'
									,'4'	=> '4'
									,'5'	=> '5'
									,'6'	=> '6'
								)			
                ,'description' 	=> ''
                ,'separator' 	=> 'before'
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
				,'sortable' 	=> false
				,'label_block' 	=> true
            )
        );
		
		$this->add_product_meta_controls();
		
		$this->add_product_color_swatch_controls();
		
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
			,'tabs'							=> array()
			,'active_tab'					=> 1
			,'product_layout' 				=> 'grid'
			,'columns' 						=> 5
			,'limit' 						=> 5
			,'product_cats'					=> array()
			,'include_children' 			=> 1
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
		
		if( empty($tabs) ){
			return;
		}
		
		if( $this->lazy_load_placeholder( $settings, 'product-tabs' ) ){
			return;
		}
		
		if( $only_slider_mobile && !wp_is_mobile() ){
			$is_slider = 0;
		}
		
		if( $active_tab > count($tabs) ){
			$active_tab = 1;
		}
		
		$product_type = $tabs[$active_tab-1]['product_type'];
		
		$product_cats = implode(',', $product_cats);
		
		$atts = compact('columns', 'rows', 'limit', 'product_cats', 'include_children', 'product_type', 'product_layout'
						,'show_image', 'show_title', 'show_sku', 'show_price', 'show_short_desc', 'show_rating', 'show_label', 'label_pos'
						,'show_categories', 'show_brands', 'show_add_to_cart', 'show_color_swatch', 'number_color_swatch', 'is_slider', 'show_nav', 'auto_play');
		
		$classes = array();
		$classes[] = 'ts-product-in-product-type-tab-wrapper ts-shortcode ts-product style-tabs-default';
		$classes[] = 'border-' . $show_border;
		$classes[] = $product_layout;
		
		if( $border_primary_color ){
			$classes[] = 'border-primary';
		}
		
		if( $show_color_swatch ){
			$classes[] = 'show-color-swatch';
		}
		
		if( $is_slider ){
			$classes[] = 'ts-slider';
			$classes[] = 'rows-' . $rows;
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
		
		$classes = array_filter($classes);
		
		$rand_id = 'ts-product-in-product-type-tab-' . mt_rand(0, 1000);
		?>
		<div class="<?php echo esc_attr(implode(' ', $classes)); ?>" id="<?php echo esc_attr($rand_id) ?>" style="--ts-columns: <?php echo esc_attr($columns) ?>" data-atts="<?php echo htmlentities(json_encode($atts)); ?>" <?php echo implode(' ', $data_attr); ?>>
			<div class="column-tabs">
				<ul class="tabs">
				<?php foreach( $tabs as $i => $tab ){ ?>
					<li class="tab-item <?php echo ($active_tab == $i + 1)?'current':''; ?>" data-id="tab-<?php echo esc_attr($tab['_id']) ?>" data-product_type="<?php echo esc_attr($tab['product_type']) ?>"><?php echo esc_html($tab['heading']) ?></li>
				<?php } ?>
				</ul>
			</div>
			
			<div class="column-content">
			
				<div class="column-products woocommerce <?php echo $product_layout; ?> columns-<?php echo esc_attr($columns) ?> <?php echo $product_type; ?> <?php echo $is_slider?'loading':''; ?>">
					<?php ts_get_product_content_in_category_tab($atts, $product_cats); ?>
				</div>
				
			</div>
			
			<?php
			foreach( $tabs as $i => $tab ){
				$this->shop_more_button( $tab['shop_more_button_link'], $tab['shop_more_button_text'], 'tab-' . $tab['_id'], $active_tab == $i + 1 );
			}
			?>
		</div>
		<?php
	}
	
	public function shop_more_button( $link, $text, $class, $current ){
		if( !$text || empty($link['url']) ){
			return;
		}
		
		$link_attr = $this->generate_link_attributes( $link );
		?>
		<div class="shop-more <?php echo esc_attr($class) ?>" style="<?php echo $current ? '' : 'display: none' ?>">
			<a class="shop-more-button" <?php echo implode( ' ', $link_attr ) ?>>
				<?php echo esc_html($text) ?>
			</a>
		</div>
		<?php
	}
}

$widgets_manager->register( new TS_Elementor_Widget_Products_In_Product_Type_Tabs() );