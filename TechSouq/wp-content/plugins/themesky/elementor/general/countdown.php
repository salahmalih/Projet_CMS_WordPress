<?php
use Elementor\Controls_Manager;

class TS_Elementor_Widget_Countdown extends TS_Elementor_Widget_Base{
	public function get_name(){
        return 'ts-countdown';
    }
	
	public function get_title(){
        return esc_html__( 'TS Countdown', 'themesky' );
    }
	
	public function get_categories(){
        return array( 'ts-elements', 'general' );
    }
	
	public function get_icon(){
		return 'eicon-countdown';
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
            'date'
            ,array(
                'label' 			=> esc_html__( 'Date', 'themesky' )
                ,'type' 			=> Controls_Manager::DATE_TIME
                ,'default' 			=> date( 'Y-m-d', strtotime('+1 day') )
            )
        );
		
		$this->add_responsive_control(
            'alignment'
            ,array(
                'label' 		=> esc_html__( 'Alignment', 'themesky' )
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
				,'prefix_class' => 'ts-align%s'
				,'description' 	=> ''
            )
        );
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type()
			,array(
				'label' 	=> esc_html__( 'Typography', 'themesky' )
				,'name' 	=> 'counter_typography'
				,'selector'	=> '{{WRAPPER}} .counter-wrapper > div'
				,'fields_options'	=> array(
					'font_size'	=> array(
						'default'	=> array(
							'size' 	=> '30'
							,'unit' => 'px'
						)
						,'size_units' => array( 'px', 'em', 'rem', 'vw' )
					)
				)
				,'exclude'	=> array('font_family', 'font_weight', 'text_transform', 'font_style', 'text_decoration', 'line_height', 'letter_spacing', 'word_spacing')
			)
		);
		
		$this->add_control(
            'background_color'
            ,array(
                'label'     	=> esc_html__( 'Background Color', 'themesky' )
                ,'type'     	=> Controls_Manager::COLOR
				,'default'  	=> ''
				,'selectors'	=> array(
					'{{WRAPPER}} .counter-wrapper > div' => 'background: {{VALUE}}'
				)
            )
        );
		
		$this->add_control(
            'text_color'
            ,array(
                'label'     	=> esc_html__( 'Text Color', 'themesky' )
                ,'type'     	=> Controls_Manager::COLOR
				,'default'  	=> ''
				,'selectors'	=> array(
					'{{WRAPPER}} .counter-wrapper > div' => 'color: {{VALUE}}'
				)
            )
        );
		
		$this->end_controls_section();
	}
	
	protected function render(){
		$settings = $this->get_settings_for_display();
		
		if( empty($settings['date']) ){
			return;
		}
		
		$time = strtotime($settings['date']);
		
		if( $time === false ){
			return;
		}
		
		$current_time = current_time('timestamp');
		
		if( $time < $current_time ){
			return;
		}
		
		$settings['seconds'] = $time - $current_time;
		$settings['title']	= 0;
		
		ts_countdown( $settings );
	}
}

$widgets_manager->register( new TS_Elementor_Widget_Countdown() );