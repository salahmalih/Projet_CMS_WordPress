<?php
use Elementor\Controls_Manager;

class TS_Elementor_Widget_Coupon extends TS_Elementor_Widget_Base{
	public function get_name(){
        return 'ts-coupon';
    }
	
	public function get_title(){
        return esc_html__( 'TS Coupon', 'themesky' );
    }
	
	public function get_categories(){
        return array( 'ts-elements', 'general' );
    }
	
	public function get_icon(){
		return 'eicon-flash';
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
            'coupon_code'
            ,array(
                'label' 		=> esc_html__( 'Coupon Code', 'themesky' )
                ,'type' 		=> Controls_Manager::TEXT
                ,'default' 		=> ''
                ,'description' 	=> ''
            )
        );
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type()
			,array(
				'label' 			=> esc_html__( 'Typography', 'themesky' )
				,'name' 			=> 'coupon_typography'
				,'selector'			=> '{{WRAPPER}} .coupon-code'
				,'fields_options'	=> array(
					'font_size'			=> array(
						'default'		=> array(
							'size' 		=> '18'
							,'unit' 	=> 'px'
						)
						,'size_units' 	=> array( 'px', 'em', 'rem', 'vw' )
					)
				)
				,'exclude'	=> array('text_decoration', 'text_transform', 'font_style', 'word_spacing')
			)
		);
		
		$this->add_control(
            'text_color'
            ,array(
                'label'     	=> esc_html__( 'Text Color', 'themesky' )
                ,'type'     	=> Controls_Manager::COLOR
				,'default'  	=> ''
				,'selectors'	=> array(
					'{{WRAPPER}} .ts-coupon-wrapper' => '--c: {{VALUE}}'
				)
            )
        );
		
		$this->add_control(
            'text_color_hover'
            ,array(
                'label'     	=> esc_html__( 'Text Hover Color', 'themesky' )
                ,'type'     	=> Controls_Manager::COLOR
				,'default'  	=> ''
				,'selectors'	=> array(
					'{{WRAPPER}} .ts-coupon-wrapper' => '--hc: {{VALUE}}'
				)
            )
        );
		
		$this->end_controls_section();
	}
	
	protected function render(){
		$settings = $this->get_settings_for_display();
		
		$default = array(
			'coupon_code'				=> ''
		);
		
		$settings = wp_parse_args( $settings, $default );
		
		extract( $settings );
		
		if( !$coupon_code ){
			return;
		}
		?>
		<div class="ts-coupon-wrapper">
			<div class="coupon-code ts-copy-button" data-copy="<?php echo esc_attr($coupon_code); ?>">
				<span><?php echo esc_html($coupon_code); ?></span>
				<span class="copy-message"><?php esc_html_e('Copied!', 'themesky'); ?></span>
			</div>
		</div>
		<?php
	}
}

$widgets_manager->register( new TS_Elementor_Widget_Coupon() );