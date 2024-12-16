<?php
use Elementor\Controls_Manager;

class TS_Elementor_Widget_Banner extends TS_Elementor_Widget_Base{
	public function get_name(){
        return 'ts-banner';
    }
	
	public function get_title(){
        return esc_html__( 'TS Banner', 'themesky' );
    }
	
	public function get_categories(){
        return array( 'ts-elements', 'general' );
    }
	
	public function get_icon(){
		return 'eicon-image';
	}
	
	protected function register_controls(){
		
		$this->start_controls_section(
            'section_bg'
            ,array(
                'label' 	=> esc_html__( 'BACKGROUND', 'themesky' )
                ,'tab'   	=> Controls_Manager::TAB_CONTENT
            )
        );
		
		$this->add_responsive_control(
			'banner_height'
			,array(
				'label' => esc_html__( 'Banner Height(px)', 'themesky' )
				,'type' => Controls_Manager::NUMBER
				,'min' => 0
				,'max' => 1000
				,'step' => 1
				,'default' => 450
				,'selectors' => array(
					'{{WRAPPER}} .banner-wrapper' => 'height: {{VALUE}}px;'
				)
			)
		);
		
		$this->add_responsive_control(
            'banner_bg'
            ,array(
                'label' 		=> esc_html__( 'Background Image', 'themesky' )
                ,'type' 		=> Controls_Manager::MEDIA
                ,'default' 		=> array( 'id' => '', 'url' => '' )		
                ,'description' 	=> ''
				,'selectors'	=> array(
					'{{WRAPPER}} .banner-wrapper:before' => 'background-image: url("{{URL}}");'
				)
            )
        );
		
		$this->add_responsive_control(
            'bg_size'
            ,array(
                'label' 		=> esc_html__( 'Background Size', 'themesky' )
                ,'type' 		=> Controls_Manager::SELECT
                ,'description' 	=> ''
                ,'default' 		=> ''
				,'options'		=> array(
									'' => esc_html__( 'Default', 'themesky' )
									,'auto' => esc_html__( 'Auto', 'themesky' )
									,'cover' => esc_html__( 'Cover', 'themesky' )
									,'contain' => esc_html__( 'Contain', 'themesky' )
								)
				,'selectors'	=> array(
					'{{WRAPPER}} .banner-wrapper:before' => 'background-size: {{VALUE}};'
				)
            )
        );
		
		$this->add_responsive_control(
            'bg_position'
            ,array(
                'label' 		=> esc_html__( 'Background Position', 'themesky' )
                ,'type' 		=> Controls_Manager::SELECT
                ,'description' 	=> ''
                ,'default' 		=> ''
				,'options'		=> array(
									'' => esc_html__( 'Default', 'themesky' )
									,'top left' => esc_html__( 'Top Left', 'themesky' )
									,'top center' => esc_html__( 'Top Center', 'themesky' )
									,'top right' => esc_html__( 'Top Right', 'themesky' )
									,'center left' => esc_html__( 'Center Left', 'themesky' )
									,'center center' => esc_html__( 'Center Center', 'themesky' )
									,'center right' => esc_html__( 'Center Right', 'themesky' )
									,'bottom left' => esc_html__( 'Bottom Left', 'themesky' )
									,'bottom center' => esc_html__( 'Bottom Center', 'themesky' )
									,'bottom right' => esc_html__( 'Bottom Right', 'themesky' )
								)
				,'selectors'	=> array(
					'{{WRAPPER}} .banner-wrapper:before' => 'background-position: {{VALUE}};',
				)
            )
        );
		
		$this->end_controls_section();

		$this->start_controls_section(
            'section_heading'
            ,array(
                'label' 	=> esc_html__( 'HEADING', 'themesky' )
                ,'tab'   	=> Controls_Manager::TAB_CONTENT
            )
        );
		
		$this->add_control(
            'heading_title'
            ,array(
                'label' 		=> esc_html__( 'Content', 'themesky' )
                ,'type' 		=> Controls_Manager::TEXTAREA
                ,'default' 		=> ''		
                ,'description' 	=> ''
            )
        );
		
		$this->add_responsive_control(
			'heading_bottom'
			,array(
				'label' => esc_html__( 'Margin Bottom(px)', 'themesky' )
				,'type' => Controls_Manager::NUMBER
				,'min' => 0
				,'max' => 100
				,'step' => 1
				,'default' => 14
				,'selectors' => array(
					'{{WRAPPER}} .box-content h2' => 'margin-bottom: {{VALUE}}px;'
				)
			)
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type()
			,array(
				'label' 	=> esc_html__( 'Typography', 'themesky' )
				,'name' 	=> 'heading_size'
				,'selector'	=> '{{WRAPPER}} .box-content h2'
				,'fields_options'	=> array(
					'font_size'	=> array(
						'default'	=> array(
							'size' 	=> '40'
							,'unit' => 'px'
						)
						,'size_units' => array( 'px', 'em', 'rem', 'vw' )
					)
					,'font_weight'  => array(
						'default' 	=> '900'
					)
				)
				,'exclude'	=> array('text_transform', 'font_style', 'text_decoration', 'word_spacing')
			)
		);
		
		$this->add_control(
            'heading_color'
            ,array(
                'label'     	=> esc_html__( 'Text Color', 'themesky' )
                ,'type'     	=> Controls_Manager::COLOR
				,'default'  	=> '#000000'
				,'selectors'	=> array(
					'{{WRAPPER}} .box-content h2' => 'color: {{VALUE}}'
				)
            )
        );

		$this->end_controls_section();
		
		$this->start_controls_section(
            'section_description'
            ,array(
                'label' 	=> esc_html__( 'TOP DESCRIPTION', 'themesky' )
                ,'tab'   	=> Controls_Manager::TAB_CONTENT
            )
        );

		$this->add_control(
            'description'
            ,array(
                'label' 		=> esc_html__( 'Content', 'themesky' )
                ,'type' 		=> Controls_Manager::TEXTAREA
                ,'default' 		=> ''		
                ,'description' 	=> ''
            )
        );
	
		$this->add_responsive_control(
			'des_bottom'
			,array(
				'label' => esc_html__( 'Margin Bottom(px)', 'themesky' )
				,'type' => Controls_Manager::NUMBER
				,'min' => 0
				,'max' => 100
				,'step' => 1
				,'default' => 10
				,'selectors' => array(
					'{{WRAPPER}} .box-content .description' => 'margin-bottom: {{VALUE}}px;'
				)
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type()
			,array(
				'label' 	=> esc_html__( 'Typography', 'themesky' )
				,'name' 	=> 'description_size'
				,'selector'	=> '{{WRAPPER}} .box-content .description'
				,'fields_options'	=> array(
					'font_size'	=> array(
						'default'	=> array(
							'size' 	=> '16'
							,'unit' => 'px'
						)
						,'size_units' => array( 'px', 'em', 'rem', 'vw' )
					)
					,'font_weight'  => array(
						'default' 	=> '400'
					)
				)
				,'exclude'	=> array('text_transform', 'font_style', 'text_decoration', 'word_spacing')
			)
		);
		
		$this->add_control(
            'des_color'
            ,array(
                'label'     	=> esc_html__( 'Text Color', 'themesky' )
                ,'type'     	=> Controls_Manager::COLOR
				,'default'  	=> '#000000'
				,'selectors'	=> array(
					'{{WRAPPER}} .box-content .description' => 'color: {{VALUE}}'
				)
            )
        );
		
		$this->add_control(
            'show_as_label'
            ,array(
                'label' 		=> esc_html__( 'Show As Label', 'themesky' )
                ,'type' 		=> Controls_Manager::SWITCHER
                ,'default' 		=> '0'
				,'return_value' => '1'
				,'label_on'		=> 	esc_html__( 'Show', 'themesky' )
				,'label_off'	=> 	esc_html__( 'Hide', 'themesky' )			
                ,'description' 	=> ''
            )
        );
		
		$this->add_control(
            'des_bg_color'
            ,array(
                'label'     	=> esc_html__( 'Label Background Color', 'themesky' )
                ,'type'     	=> Controls_Manager::COLOR
				,'default'  	=> '#dd2831'
				,'selectors'	=> array(
					'{{WRAPPER}} .box-content .description.show-as-label' => 'background-color: {{VALUE}}'
				)
				,'condition'	=> array( 'show_as_label' => '1' )
            )
        );
		
		$this->end_controls_section();
		
		$this->start_controls_section(
            'section_b_description'
            ,array(
                'label' 	=> esc_html__( 'BOTTOM DESCRIPTION', 'themesky' )
                ,'tab'   	=> Controls_Manager::TAB_CONTENT
            )
        );
		
		$this->add_control(
            'b_description'
            ,array(
                'label' 		=> esc_html__( 'Content', 'themesky' )
                ,'type' 		=> Controls_Manager::TEXTAREA
                ,'default' 		=> ''		
                ,'description' 	=> ''
            )
        );
	
		$this->add_responsive_control(
			'b_des_bottom'
			,array(
				'label' => esc_html__( 'Margin Bottom(px)', 'themesky' )
				,'type' => Controls_Manager::NUMBER
				,'min' => 0
				,'max' => 100
				,'step' => 1
				,'default' => 7
				,'selectors' => array(
					'{{WRAPPER}} .box-content .description-bottom' => 'margin-bottom: {{VALUE}}px;'
				)
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type()
			,array(
				'label' 	=> esc_html__( 'Typography', 'themesky' )
				,'name' 	=> 'b_description_size'
				,'selector'	=> '{{WRAPPER}} .box-content .description-bottom'
				,'fields_options'	=> array(
					'font_size'	=> array(
						'default'	=> array(
							'size' 	=> '16'
							,'unit' => 'px'
						)
						,'size_units' => array( 'px', 'em', 'rem', 'vw' )
					)
					,'font_weight'  => array(
						'default' 	=> '400'
					)
				)
				,'exclude'	=> array('text_transform', 'font_style', 'text_decoration', 'word_spacing')
			)
		);
		
		$this->add_control(
            'b_des_color'
            ,array(
                'label'     	=> esc_html__( 'Text Color', 'themesky' )
                ,'type'     	=> Controls_Manager::COLOR
				,'default'  	=> '#000000'
				,'selectors'	=> array(
					'{{WRAPPER}} .box-content .description-bottom' => 'color: {{VALUE}}'
				)
            )
        );
		
		$this->end_controls_section();
		
		$this->start_controls_section(
            'section_button'
            ,array(
                'label' 	=> esc_html__( 'BUTTON & EFFECTS', 'themesky' )
                ,'tab'   	=> Controls_Manager::TAB_CONTENT
            )
        );
		
		$this->add_control(
            'button_text'
            ,array(
                'label' 		=> esc_html__( 'Button Text', 'themesky' )
                ,'type' 		=> Controls_Manager::TEXT
                ,'default' 		=> ''		
                ,'description' 	=> esc_html__( 'Only working if the "Link" field is not empty', 'themesky' )
            )
        );
		
		$this->add_control(
            'link'
            ,array(
                'label'     	=> esc_html__( 'Link', 'themesky' )
                ,'type'     	=> Controls_Manager::URL
				,'default'  	=> array( 'url' => '', 'is_external' => true, 'nofollow' => true )
				,'show_external'=> true
            )
        );
		
		$this->add_control(
            'btn_color'
            ,array(
                'label'     	=> esc_html__( 'Button Text Color', 'themesky' )
                ,'type'     	=> Controls_Manager::COLOR
				,'default'  	=> ''
				,'selectors'	=> array(
					'{{WRAPPER}} .ts-banner-button .button' => 'color: {{VALUE}}'
				)
            )
        );
		
		$this->add_control(
            'btn_bg_color'
            ,array(
                'label'     	=> esc_html__( 'Button Background Color', 'themesky' )
                ,'type'     	=> Controls_Manager::COLOR
				,'default'  	=> ''
				,'selectors'	=> array(
					'{{WRAPPER}} .ts-banner-button .button' => 'background: {{VALUE}}; border-color: {{VALUE}};'
				)
            )
        );
		
		$this->add_control(
            'btn_hover_color'
            ,array(
                'label'     	=> esc_html__( 'Button Hover Text Color', 'themesky' )
                ,'type'     	=> Controls_Manager::COLOR
				,'default'  	=> ''
				,'selectors'	=> array(
					'{{WRAPPER}} .ts-banner-button .button:hover' => 'color: {{VALUE}}'
				)
            )
        );
		
		$this->add_control(
            'btn_hover_bg_color'
            ,array(
                'label'     	=> esc_html__( 'Button Hover Background Color', 'themesky' )
                ,'type'     	=> Controls_Manager::COLOR
				,'default'  	=> ''
				,'selectors'	=> array(
					'{{WRAPPER}} .ts-banner-button .button:hover' => 'background: {{VALUE}}; border-color: {{VALUE}};'
				)
            )
        );
		
		$this->add_control(
            'style_effect'
            ,array(
                'label' 		=> esc_html__( 'Style Effect', 'themesky' )
                ,'type' 		=> Controls_Manager::SELECT
                ,'default' 		=> 'eff-flash'
				,'options'		=> array(									
									'eff-flash' 				=> esc_html__('Flash', 'themesky')
									,'eff-zoom-in'				=> esc_html__('Zoom in', 'themesky')
									,'eff-gradient'				=> esc_html__('Gradient', 'themesky')
									,'eff-overlay' 				=> esc_html__('Overlay', 'themesky')
									,'no-effect' 				=> esc_html__('None', 'themesky')
								)			
                ,'description' 	=> ''
            )
        );
		
		$this->add_control(
            'flash_timing'
            ,array(
                'label'     	=> esc_html__( 'Transition Timing', 'themesky' )
                ,'type'     	=> Controls_Manager::NUMBER
				,'min' 			=> 0
				,'max' 			=> 10000
				,'step' 		=> 100
				,'default' 		=> 1200
				,'condition'	=> array( 'style_effect' => 'eff-flash' )
				,'description' 	=> esc_html__( 'Transition Timing in miliseconds for Flash Effect', 'themesky' )
				,'selectors' => array(
					'{{WRAPPER}} .eff-flash:hover .banner-wrapper:after' => 'animation-duration: {{VALUE}}ms;'
				)
            )
        );
		
		$this->add_control(
            'overlay_color'
            ,array(
                'label'     	=> esc_html__( 'Overlay Background Color', 'themesky' )
                ,'type'     	=> Controls_Manager::COLOR
				,'default'  	=> '#000000'
				,'condition'	=> array( 'style_effect' => 'eff-overlay' )
				,'description' 	=> esc_html__( 'To set the opacity, click in the color and drag color bar in color picker popup', 'themesky' )
				,'selectors'	=> array(
					'{{WRAPPER}} .eff-overlay .banner-wrapper:before' => 'background: {{VALUE}}'
				)
            )
        );
		
		$this->end_controls_section();
		
		$this->start_controls_section(
            'section_layout'
            ,array(
                'label' 	=> esc_html__( 'LAYOUT', 'themesky' )
                ,'tab'   	=> Controls_Manager::TAB_CONTENT
            )
        );
		
		$this->add_responsive_control(
			'content_width'
			,array(
				'label' => esc_html__( 'Content Max Width(%)', 'themesky' )
				,'type' => Controls_Manager::NUMBER
				,'min' => 0
				,'max' => 100
				,'step' => 1
				,'default' => 100
				,'selectors' => array(
					'{{WRAPPER}} .box-content' => 'max-width: {{VALUE}}%;'
				)
			)
		);
		
		$this->add_responsive_control(
            'h_align'
            ,array(
                'label' 		=> esc_html__( 'Horizontal Align', 'themesky' )
                ,'type' 		=> Controls_Manager::CHOOSE
				,'options' => array(
					'flex-start' => array(
						'title' => esc_html__( 'Top', 'themesky' )
						,'icon' => 'eicon-h-align-left'
					)
					,'center' => array(
						'title' => esc_html__( 'Middle', 'themesky' )
						,'icon' => 'eicon-h-align-center'
					)
					,'flex-end' => array(
						'title' => esc_html__( 'Bottom', 'themesky' )
						,'icon' => 'eicon-h-align-right'
					)
				)
				,'description' 	=> ''
				,'prefix_class' => 'h-align-%s'
				,'selectors' => array(
					'{{WRAPPER}} .banner-wrapper' => 'align-items: {{VALUE}}'
				)
            )
        );
		
		$this->add_responsive_control(
			'v_align'
			,array(
				'label' => esc_html__( 'Vertical Align', 'themesky' )
				,'type' => Controls_Manager::CHOOSE
				,'default' => 'flex-start'
				,'options' => array(
					'flex-start' => array(
						'title' => esc_html__( 'Top', 'themesky' )
						,'icon' => 'eicon-v-align-top'
					)
					,'center' => array(
						'title' => esc_html__( 'Middle', 'themesky' )
						,'icon' => 'eicon-v-align-middle'
					)
					,'flex-end' => array(
						'title' => esc_html__( 'Bottom', 'themesky' )
						,'icon' => 'eicon-v-align-bottom'
					)
				)
				,'description' 	=> ''
				,'selectors' => array(
					'{{WRAPPER}} .banner-wrapper' => 'justify-content: {{VALUE}}'
				)
			)
		);
		
		$this->add_responsive_control(
            'text_align'
            ,array(
                'label' 		=> esc_html__( 'Text Align', 'themesky' )
                ,'type' 		=> Controls_Manager::CHOOSE
				,'options' => array(
					'left' => array(
						'title' => esc_html__( 'Left', 'themesky' )
						,'icon' => 'eicon-text-align-left'
					)
					,'center' => array(
						'title' => esc_html__( 'Center', 'themesky' )
						,'icon' => 'eicon-text-align-center'
					)
					,'right' => array(
						'title' => esc_html__( 'Right', 'themesky' )
						,'icon' => 'eicon-text-align-right'
					)
				)
				,'description' 	=> ''
				,'selectors' => array(
					'{{WRAPPER}} .box-content' => 'text-align: {{VALUE}}'
				)
            )
        );
		
		$this->add_control(
			'ts_hr'
			,array(
                'type' 		=> Controls_Manager::DIVIDER
            )
		);

		$this->add_responsive_control(
			'banner_radius'
			,array(
				'type' => Controls_Manager::DIMENSIONS
				,'label' => esc_html__( 'Border Radius', 'themesky' )
				,'size_units' => array( 'px', '%', 'em', 'rem' )
				,'default' => array( 
								'top' => '10'
								,'right' => '10'
								,'bottom' => '10'
								,'left' => '10'
								,'unit' => 'px'
							)
				,'selectors' => array(
					'{{WRAPPER}} .banner-wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				)
			)
		);
		
		$this->add_responsive_control(
			'content_padding'
			,array(
				'type' => Controls_Manager::DIMENSIONS
				,'label' => esc_html__( 'Content Padding', 'themesky' )
				,'size_units' => array( 'px', '%', 'em', 'rem' )
				,'selectors' => array(
					'{{WRAPPER}} .box-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				)
			)
		);
		
		$this->end_controls_section();
	}
	
	protected function render(){
		$settings = $this->get_settings_for_display();
		
		$default = array(
			'banner_bg'							=> array( 'id' => '', 'url' => '' )
			,'heading_title'					=> ''
			,'description'						=> ''
			,'b_description'					=> ''
			,'h_align'							=> ''
			,'show_as_label'					=> false
			,'button_text'						=> ''
			,'link' 							=> array( 'url' => '', 'is_external' => true, 'nofollow' => true )
			,'style_effect'						=> 'eff-zoom-in'
		);
		
		$settings = wp_parse_args( $settings, $default );
		
		extract( $settings );
		
		$link_attr = $this->generate_link_attributes( $link );
		
		$classes = array();
		$classes[] = $style_effect;
		
		$allow_tags = array(
			'a'			=> array('href' => array(),'title' => array(),'style' => array())
			,'span'		=> array('class' => array(),'style' => array())
			,'div'		=> array('class' => array(),'style' => array())
			,'strong'	=> array('class' => array(),'style' => array())
			,'em'		=> array('class' => array(),'style' => array())
			,'br'		=> array('class' => array(),'style' => array())
		);
		?>
		<div class="ts-banner <?php echo esc_attr( implode(' ', $classes) ); ?>">
			<div class="banner-wrapper">
			
				<?php if( $link_attr && !$button_text ): ?>
				<a class="banner-link" <?php echo implode(' ', $link_attr); ?>></a>
				<?php endif;?>
					
				<div class="box-content">
				
					<?php if( $description ): ?>				
						<div class="description <?php echo ($show_as_label)?'show-as-label':'';?>"><?php echo wp_kses( $description, $allow_tags ); ?></div>
					<?php endif; ?>
					
					<?php if( $heading_title ): ?>				
						<h2><?php echo wp_kses( $heading_title, $allow_tags ); ?></h2>
					<?php endif; ?>
					
					<?php if( $b_description ): ?>				
						<div class="description-bottom"><?php echo wp_kses( $b_description, $allow_tags ); ?></div>
					<?php endif; ?>
					
					<?php if( $button_text && $link_attr ):?>
						<div class="ts-banner-button">
							<a class="button" <?php echo implode(' ', $link_attr); ?>><?php echo esc_html($button_text) ?></a>
						</div>
					<?php endif; ?>
				</div>
				
			</div>
		</div>
		<?php
	}
}

$widgets_manager->register( new TS_Elementor_Widget_Banner() );