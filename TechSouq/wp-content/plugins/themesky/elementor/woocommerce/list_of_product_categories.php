<?php
use Elementor\Controls_Manager;

class TS_Elementor_Widget_List_Of_Product_Categories extends TS_Elementor_Widget_Base{
	public function get_name(){
        return 'ts-list-of-product-categories';
    }
	
	public function get_title(){
        return esc_html__( 'TS List Of Product Categories', 'themesky' );
    }
	
	public function get_categories(){
        return array( 'ts-elements', 'woocommerce-elements' );
    }
	
	public function get_icon(){
		return 'eicon-editor-list-ul';
	}
	
	protected function register_controls(){
		$this->start_controls_section(
            'section_general'
            ,array(
                'label' 	=> esc_html__( 'General', 'themesky' )
                ,'tab'   	=> Controls_Manager::TAB_CONTENT
            )
        );
		
		$this->add_control(
            'title'
            ,array(
                'label' 		=> esc_html__( 'Title', 'themesky' )
                ,'type' 		=> Controls_Manager::TEXT
                ,'default' 		=> ''		
                ,'description' 	=> ''
            )
        );
		
		$this->add_control(
			'cat_img'
			,array(
				'label' 		=> esc_html__( 'Image', 'themesky' )
				,'type' 		=> Controls_Manager::MEDIA
				,'default' 		=> array( 'id' => '', 'url' => '' )
				,'description' 	=> ''
			)
		);
		
		$this->add_responsive_control(
            'direction'
            ,array(
                'label' 		=> esc_html__( 'Layout', 'themesky' )
                ,'type' 		=> Controls_Manager::CHOOSE
				,'options' => array(
					'column' => array(
						'title' => esc_html__( 'Vertical', 'themesky' )
						,'icon' => 'eicon-arrow-down'
					)
					,'row' => array(
						'title' => esc_html__( 'Horizontal', 'themesky' )
						,'icon' => 'eicon-arrow-right'
					)
				)
				,'default' 		=> 'row'
				,'description' 	=> esc_html__( 'Only work if Image field is not empty', 'themesky' )
				,'prefix_class' => 'direction-%s'
				,'selectors'	=> array(
					'{{WRAPPER}} .ts-list-of-product-categories-wrapper' => 'flex-direction: {{VALUE}}'
				)
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
            'count'
            ,array(
                'label' 		=> esc_html__( 'Count', 'themesky' )
                ,'type' 		=> Controls_Manager::SWITCHER
                ,'default' 		=> '0'
				,'return_value' => '1'
				,'label_on'		=> 	esc_html__( 'Show', 'themesky' )
				,'label_off'	=> 	esc_html__( 'Hide', 'themesky' )
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
				,'description' 	=> esc_html__( 'Get children of this category', 'themesky' )
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
            'direct_child'
            ,array(
                'label' 		=> esc_html__( 'Direct Children', 'themesky' )
                ,'type' 		=> Controls_Manager::SWITCHER
                ,'default' 		=> '1'
				,'return_value' => '1'
				,'label_on'		=> 	esc_html__( 'Yes', 'themesky' )
				,'label_off'	=> 	esc_html__( 'No', 'themesky' )			
                ,'description' 	=> esc_html__( 'Get direct children of Parent or all children', 'themesky' )
            )
        );
		
		$this->add_control(
            'include_parent'
            ,array(
                'label' 		=> esc_html__( 'Include parent', 'themesky' )
                ,'type' 		=> Controls_Manager::SWITCHER
                ,'default' 		=> '0'
				,'return_value' => '1'
				,'label_on'		=> 	esc_html__( 'Yes', 'themesky' )
				,'label_off'	=> 	esc_html__( 'No', 'themesky' )			
                ,'description' 	=> esc_html__( 'Show parent category at the first of list', 'themesky' )
            )
        );
		
		$this->add_control(
            'hide_empty'
            ,array(
                'label' 		=> esc_html__( 'Hide empty product categories', 'themesky' )
                ,'type' 		=> Controls_Manager::SWITCHER
                ,'default' 		=> '1'
				,'return_value' => '1'
				,'label_on'		=> 	esc_html__( 'Yes', 'themesky' )
				,'label_off'	=> 	esc_html__( 'No', 'themesky' )			
                ,'description' 	=> ''
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
                ,'default' 		=> 'Shop More'		
                ,'description' 	=> ''
				,'condition'	=> array( 'show_shop_more_button' => '1' )
            )
        );
		
		$this->add_control(
            'text_color'
            ,array(
                'label'     	=> esc_html__( 'Text Color', 'themesky' )
                ,'type'     	=> Controls_Manager::COLOR
				,'default'  	=> ''
				,'selectors'	=> array(
					'{{WRAPPER}} .list-categories ul li a' => 'color: {{VALUE}}'
				)
            )
        );
		
		$this->add_control(
            'text_hover_color'
            ,array(
                'label'     	=> esc_html__( 'Text Hover Color', 'themesky' )
                ,'type'     	=> Controls_Manager::COLOR
				,'default'  	=> ''
				,'selectors'	=> array(
					'{{WRAPPER}} .list-categories ul li a:hover' => 'color: {{VALUE}}'
				)
            )
        );
		
		$this->end_controls_section();
	}
	
	protected function render(){
		$settings = $this->get_settings_for_display();
		
		$default = array(
			'title' 						=> ''
			,'cat_img' 						=> array( 'id' => '', 'url' => '' )
			,'direction'					=> 'row'
			,'limit'						=> 5
			,'count'						=> 0
			,'parent'						=> array()
			,'direct_child'					=> 1
			,'include_parent'				=> 1
			,'ids'							=> array()
			,'hide_empty'					=> 1
			,'show_shop_more_button'		=> 0
			,'shop_more_button_link'		=> array( 'url' => '', 'is_external' => true, 'nofollow' => true )
			,'shop_more_button_text' 		=> 'Shop More'
		);
		
		$settings = wp_parse_args( $settings, $default );
		
		extract( $settings );
		
		if( !class_exists('WooCommerce') ){
			return;
		}

		$link_attr = $this->generate_link_attributes( $shop_more_button_link );
		
		if( is_array($parent) ){
			$parent = implode( '', $parent );
		}
		
		if( $parent && $include_parent ){
			$limit = absint($limit) - 1;
		}
		
		$args = array(
			'taxonomy'		=> 'product_cat'
			,'hide_empty'	=> $hide_empty
			,'number'		=> $limit
		);
		
		if( $parent ){
			if( $direct_child ){
				$args['parent'] = $parent;
			}
			else{
				$args['child_of'] = $parent;
			}
		}
		
		if( $ids ){
			$args['include'] = $ids;
			$args['orderby'] = 'include';
		}
		
		$list_categories = get_terms( $args );
		
		if( !is_array($list_categories) || empty($list_categories) ){
			return;
		}
		
		$classes = array( 'ts-list-of-product-categories-wrapper' );
		if( $show_shop_more_button ){
			$classes[] = 'has-shop-more-button';
		}
		else{
			$classes[] = 'no-shop-more-button';
		}

		?>
		<div class="<?php echo esc_attr( implode(' ', $classes) ); ?>">
			<div class="cat-img"><?php echo wp_get_attachment_image($cat_img['id'], 'full', 0, array('class'=>'img')); ?></div>
			<div class="list-categories">
				<?php if( $title ): ?>		
				<h3 class="heading-title">
					<?php echo esc_html($title) ?>
				</h3>
				<?php endif; ?>
			
				<ul>
					<?php 
					if( $parent && $include_parent ){
						$parent_obj = get_term($parent, 'product_cat');
						if( isset($parent_obj->name) ){
					?>
						<li><a href="<?php echo get_term_link($parent_obj, 'product_cat'); ?>"><?php echo esc_html($parent_obj->name); ?></a></li>
					<?php
						}
					}
					?>
					
					<?php foreach( $list_categories as $category ){?>
					<li><a href="<?php echo get_term_link($category, 'product_cat'); ?>">
						<?php echo esc_html($category->name); ?>
						<?php if($count){ ?>
							(<?php echo esc_html($category->count); ?>)
						<?php } ?>
					</a></li>
					<?php } ?>
					<?php if( $show_shop_more_button ){ ?>
					<li> 
						<a class="shop-more-button" <?php echo implode( ' ', $link_attr ); ?>><?php echo esc_html($shop_more_button_text) ?></a>
					</li>
					<?php } ?>
				</ul>
			</div>
		</div>
		<?php
	}
}

$widgets_manager->register( new TS_Elementor_Widget_List_Of_Product_Categories() );