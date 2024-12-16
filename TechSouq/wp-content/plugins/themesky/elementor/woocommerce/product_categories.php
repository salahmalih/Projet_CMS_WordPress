<?php
use Elementor\Controls_Manager;

class TS_Elementor_Widget_Product_Categories extends TS_Elementor_Widget_Base{
	public function get_name(){
        return 'ts-product-categories';
    }
	
	public function get_title(){
        return esc_html__( 'TS Product Categories', 'themesky' );
    }
	
	public function get_categories(){
        return array( 'ts-elements', 'woocommerce-elements' );
    }
	
	public function get_icon(){
		return 'eicon-product-categories';
	}
	
	protected function register_controls(){
		$this->start_controls_section(
            'section_general'
            ,array(
                'label' 	=> esc_html__( 'General', 'themesky' )
                ,'tab'   	=> Controls_Manager::TAB_CONTENT
            )
        );
		
		$this->add_lazy_load_controls( array( 'thumb-height' => 80 ) );
		
		$this->add_title_and_style_controls();

		$this->add_control(
            'image_type'
            ,array(
                'label' 		=> esc_html__( 'Image Type', 'themesky' )
                ,'type' 		=> Controls_Manager::SELECT
                ,'default' 		=> 'default'
				,'options'		=> array(
									'default'			=> esc_html__( 'Thumbnail', 'themesky' )
									,'icon'				=> esc_html__( 'Icon', 'themesky' )
								)			
                ,'description' 	=> ''
            )
        );
		
		$this->add_control(
			'img_position'
			,array(
				'label' => esc_html__( 'Image Position', 'themesky' )
				,'type' => Controls_Manager::CHOOSE
				,'default' => 'left'
				,'options' => array(
					'left' => array(
						'title' => esc_html__( 'Left', 'themesky' )
						,'icon' => 'eicon-h-align-left'
					)
					,'top' => array(
						'title' => esc_html__( 'Top', 'themesky' )
						,'icon' => 'eicon-v-align-top'
					)
					,'right' => array(
						'title' => esc_html__( 'Right', 'themesky' )
						,'icon' => 'eicon-h-align-right'
					)
				)
			)
		);
				
		$this->add_responsive_control(
			'image_space'
			,array(
				'label' => esc_html__( 'Image Spacing', 'themesky' )
				,'type' => Controls_Manager::SLIDER
				,'size_units' => array( 'px', '%', 'em', 'rem', 'vw', 'custom' )
				,'default' => array(
					'size' => 10
				)
				,'range' => array(
					'px' => array(
						'min' => 0
						,'max' => 100
					)
				)
				,'description' 	=> esc_html__( 'The spacing between image and content', 'themesky' )
				,'selectors' => array(
					'{{WRAPPER}} .product-category .product-wrapper' => 'gap: {{SIZE}}{{UNIT}};'
				)
			)
		);
		
		$this->add_control(
            'stretch_content'
            ,array(
                'label' 		=> esc_html__( 'Space Between', 'themesky' )
                ,'type' 		=> Controls_Manager::SWITCHER
                ,'default' 		=> '0'
				,'return_value' => '1'
				,'label_on'		=> 	esc_html__( 'Yes', 'themesky' )
				,'label_off'	=> 	esc_html__( 'No', 'themesky' )			
                ,'description' 	=> esc_html__( 'Images and Content at 2 sides of the frame', 'themesky' )
            )
        );
		
		$this->add_control(
			'ts_hr_2'
			,array(
                'type' 		=> Controls_Manager::DIVIDER
            )
		);
		
		$this->add_control(
            'columns'
            ,array(
                'label'     	=> esc_html__( 'Columns', 'themesky' )
                ,'type'     	=> Controls_Manager::NUMBER
				,'default'  	=> 6
				,'min'      	=> 1
            )
        );
		
		$this->add_control(
            'limit'
            ,array(
                'label'     	=> esc_html__( 'Limit', 'themesky' )
                ,'type'     	=> Controls_Manager::NUMBER
				,'default'  	=> 6
				,'min'      	=> 1
            )
        );
		
		$this->add_control(
			'ts_hr_3'
			,array(
                'type' 		=> Controls_Manager::DIVIDER
            )
		);
		
		$this->add_control(
            'show_title'
            ,array(
                'label' 		=> esc_html__( 'Show Category Title', 'themesky' )
                ,'type' 		=> Controls_Manager::SWITCHER
                ,'default' 		=> '1'
				,'return_value' => '1'
				,'label_on'		=> 	esc_html__( 'Show', 'themesky' )
				,'label_off'	=> 	esc_html__( 'Hide', 'themesky' )			
                ,'description' 	=> ''
            )
        );
		
		$this->add_control(
            'title_pos'
            ,array(
                'label' 		=> esc_html__( 'Category Title Position', 'themesky' )
                ,'type' 		=> Controls_Manager::SELECT
                ,'default' 		=> 'inside'
				,'options'		=> array(
									'inside'	=> esc_html__( 'Inside', 'themesky' )
									,'outside'	=> esc_html__( 'Outside', 'themesky' )
								)			
                ,'description' 	=> ''
                ,'condition' 	=> array('show_title' => '1')
            )
        );
		
		$this->add_control(
            'show_product_count'
            ,array(
                'label' 		=> esc_html__( 'Show Product Count', 'themesky' )
                ,'type' 		=> Controls_Manager::SWITCHER
                ,'default' 		=> '0'
				,'return_value' => '1'
				,'label_on'		=> 	esc_html__( 'Show', 'themesky' )
				,'label_off'	=> 	esc_html__( 'Hide', 'themesky' )			
                ,'description' 	=> ''
            )
        );
		
		$this->add_control(
            'hide_empty'
            ,array(
                'label' 		=> esc_html__( 'Hide empty product categories', 'themesky' )
                ,'type' 		=> Controls_Manager::SWITCHER
                ,'default' 		=> '1'
				,'return_value' => '1'
				,'label_on'		=> esc_html__( 'Yes', 'themesky' )
				,'label_off'	=> esc_html__( 'No', 'themesky' )			
                ,'description' 	=> ''
            )
        );

		$this->add_control(
            'first_level'
            ,array(
                'label' 		=> esc_html__( 'Only display the first level', 'themesky' )
                ,'type' 		=> Controls_Manager::SWITCHER
                ,'default' 		=> '0'
				,'return_value' => '1'			
                ,'description' 	=> ''
            )
        );
		
		$this->add_control(
            'parent'
            ,array(
                'label' 		=> esc_html__( 'Parent', 'themesky' )
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
				,'description' 	=> esc_html__( 'Get direct children of this category', 'themesky' )
				,'condition'	=> array( 'first_level!' => '1' )
            )
        );
		
		$this->add_control(
            'child_of'
            ,array(
                'label' 		=> esc_html__( 'Child of', 'themesky' )
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
				,'description' 	=> esc_html__( 'Get all descendents of this category', 'themesky' )
				,'condition'	=> array( 'first_level!' => '1' )
            )
        );
		
		$this->add_control(
            'ids'
            ,array(
                'label' 		=> esc_html__( 'Specific categories', 'themesky' )
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
			'hr_4'
			,array(
                'type' 		=> Controls_Manager::DIVIDER
            )
		);
		
		$this->add_control(
            'view_shop_button_text'
            ,array(
                'label'         => esc_html__( 'Shop Now Button Text', 'themesky' )
                ,'type'         => Controls_Manager::TEXT
                ,'default'      => ''
            )
        );
		
		$this->end_controls_section();
		
		$this->start_controls_section(
            'section_style'
            ,array(
                'label' 	=> esc_html__( 'Style', 'themesky' )
                ,'tab'   	=> Controls_Manager::TAB_STYLE
            )
        );

		$this->add_control(
            'background_color'
            ,array(
                'label'     	=> esc_html__( 'Background Color', 'themesky' )
                ,'type'     	=> Controls_Manager::COLOR
				,'default'  	=> ''
				,'selectors'	=> array(
					'{{WRAPPER}} .product-category .product-wrapper, {{WRAPPER}} .title-outside .product-category .product-wrapper > a' => 'background: {{VALUE}} !important;'
				)
				,'description' 	=> esc_html__( 'This option will overwrite background color of each category', 'themesky' )
            )
        );
		
		$this->add_control(
            'text_color'
            ,array(
                'label'     	=> esc_html__( 'Text Color', 'themesky' )
                ,'type'     	=> Controls_Manager::COLOR
				,'default'  	=> ''
				,'selectors'	=> array(
					'{{WRAPPER}} .product-category .meta-wrapper' => 'color: {{VALUE}} !important;'
				)
				,'description' 	=> ''
            )
        );
		
		$this->add_control(
			'hr_5'
			,array(
                'type' 		=> Controls_Manager::DIVIDER
            )
		);

		$this->add_responsive_control(
			'meta_space'
			,array(
				'label' => esc_html__( 'Category Meta Spacing', 'themesky' )
				,'type' => Controls_Manager::SLIDER
				,'size_units' => array( 'px', '%', 'em', 'rem', 'vw', 'custom' )
				,'default' => array(
					'size' => 5
				)
				,'range' => array(
					'px' => array(
						'min' => 0
						,'max' => 100
					)
				)
				,'selectors' => array(
					'{{WRAPPER}} .product-category .meta-wrapper' => 'gap: {{SIZE}}{{UNIT}};'
				)
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

		$this->add_product_slider_controls(false);
		
		$this->end_controls_section();
	}
	
	protected function render(){
		$settings = $this->get_settings_for_display();
		
		$default = array(
			'lazy_load'						=> 0
			,'title'						=> ''
			,'title_alignment' 				=> ''
			,'image_type' 					=> 'default'
			,'img_position'					=> 'left'
			,'stretch_content'				=> 0
			,'parents' 						=> ''
			,'is_slider'					=> 0
			,'only_slider_mobile'			=> 0
			,'per_page' 					=> 4
			,'columns' 						=> 6
			,'first_level' 					=> 0
			,'parent' 						=> ''
			,'child_of' 					=> 0
			,'ids'	 						=> ''
			,'hide_empty'					=> 1
			,'show_title'					=> 1
			,'title_pos'					=> 'inside'
			,'show_product_count'			=> 1
			,'view_shop_button_text'		=> ''
			,'show_nav' 					=> 0
			,'show_dots' 					=> 0
			,'auto_play' 					=> 0
			,'loop' 						=> 1
			,'disable_slider_responsive'	=> 0
		);
		
		$settings = wp_parse_args( $settings, $default );
		
		extract( $settings );
		
		if( !class_exists('WooCommerce') ){
			return;
		}
		
		if( $this->lazy_load_placeholder( $settings, 'product-category' ) ){
			return;
		}

		if( $only_slider_mobile && !wp_is_mobile() ){
			$is_slider = false;
		}
		
		if( is_admin() && !wp_doing_ajax() ){ /* WooCommerce does not include hook below in Elementor editor */
			add_action( 'woocommerce_before_subcategory_title', 'woocommerce_subcategory_thumbnail', 10 );
		}
		
		if( $image_type == 'icon' ){
			remove_action( 'woocommerce_before_subcategory_title', 'woocommerce_subcategory_thumbnail', 10 );
			add_action( 'woocommerce_before_subcategory_title', array($this, 'category_icon'), 10 );
		}
		
		if( $first_level ){
			$parent = $child_of = 0;
		}
	
		$parent = is_array($parent) ? implode('', $parent) : $parent;
		$child_of = is_array($child_of) ? implode('', $child_of) : $child_of;

		$args = array(
			'taxonomy'	  => 'product_cat'
			,'orderby'    => 'name'
			,'order'      => 'ASC'
			,'hide_empty' => $hide_empty
			,'pad_counts' => true
			,'parent'     => $parent
			,'child_of'   => $child_of
			,'number'     => $limit
		);
		
		if( $ids ){
			$args['include'] = $ids;
			$args['orderby'] = 'include';
		}
		
		$product_categories = get_terms( $args );
		
		$old_woocommerce_loop_columns = wc_get_loop_prop('columns');
		wc_set_loop_prop('columns', $columns);
		
		wc_set_loop_prop( 'is_shortcode', true );
		
		if( count($product_categories) > 0 ):
			$classes = array();
			$classes[] = 'ts-product-category-wrapper ts-product ts-shortcode woocommerce';
			$classes[] = 'style-' . $image_type;
			$classes[] = 'columns-' . $columns;
			$classes[] = 'ts-image-position-' . $img_position;
			$classes[] = 'title-' . $title_pos;
			$classes[] = $is_slider?'ts-slider':'grid';
			
			if($stretch_content){
				$classes[] = 'stretch-content';
			}
			
			if( $is_slider ){
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
		?>
			<div class="<?php echo esc_attr(implode(' ', $classes)) ?>" style="--ts-columns: <?php echo esc_attr($columns) ?>" <?php echo implode(' ', $data_attr); ?>>
				<?php if( $title ): ?>
				<header class="shortcode-heading-wrapper">
					<h3 class="shortcode-title"><?php echo esc_html($title); ?></h3>
				</header>
				<?php endif; ?>
			
				<div class="content-wrapper <?php echo $is_slider?'loading':''; ?>">
					<?php 
					woocommerce_product_loop_start();
					
					foreach ( $product_categories as $category ) {
						wc_get_template( 'content-product-cat.php', array(
							'category' 						=> $category
							,'show_title' 					=> $show_title
							,'show_product_count' 			=> $show_product_count
							,'view_shop_button_text' 		=> $view_shop_button_text
						) );
					}
					
					woocommerce_product_loop_end();
					?>
				</div>
			</div>
		<?php
		endif;
		
		if( $image_type == 'icon' ){
			add_action( 'woocommerce_before_subcategory_title', 'woocommerce_subcategory_thumbnail', 10 );
			remove_action( 'woocommerce_before_subcategory_title', array($this, 'category_icon'), 10 );
		}
		
		wc_set_loop_prop('columns', $old_woocommerce_loop_columns);
		
		wc_set_loop_prop( 'is_shortcode', false );
	}

	function category_icon( $category ){
		$icon_id = get_term_meta($category->term_id, 'icon_id', true);
		if( $icon_id ){
			echo wp_get_attachment_image( $icon_id, 'full', false, array('alt' => $category->name) );
		}
		else{
			echo wc_placeholder_img();
		}
	}
}

$widgets_manager->register( new TS_Elementor_Widget_Product_Categories() );