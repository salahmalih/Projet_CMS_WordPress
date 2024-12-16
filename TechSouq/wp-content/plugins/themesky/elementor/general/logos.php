<?php
use Elementor\Controls_Manager;

class TS_Elementor_Widget_Logos extends TS_Elementor_Widget_Base{
	public function get_name(){
        return 'ts-logos';
    }
	
	public function get_title(){
        return esc_html__( 'TS Logos', 'themesky' );
    }
	
	public function get_categories(){
        return array( 'ts-elements', 'general' );
    }
	
	public function get_icon(){
		return 'eicon-logo';
	}
	
	protected function register_controls(){
		$this->start_controls_section(
            'section_general'
            ,array(
                'label' 	=> esc_html__( 'General', 'themesky' )
                ,'tab'   	=> Controls_Manager::TAB_CONTENT
            )
        );
		
		$this->add_lazy_load_controls( array( 'thumb-height' => 50 ) );
		
		$this->add_control(
            'layout'
            ,array(
                'label' => esc_html__( 'Layout', 'themesky' )
                ,'type' => Controls_Manager::SELECT
                ,'default' => 'slider'
				,'options'	=>array(
							'slider'	=> esc_html__( 'Slider', 'themesky' )
							,'grid'		=> esc_html__( 'Grid', 'themesky' )
							)			
                ,'description' => ''
            )
        );
		
		$this->add_control(
            'limit'
            ,array(
                'label'     => esc_html__( 'Limit', 'themesky' )
                ,'type'     => Controls_Manager::NUMBER
				,'default'  => 8
				,'min'      => 1
            )
        );
		
		$this->add_control(
            'rows'
            ,array(
                'label'     => esc_html__( 'Rows', 'themesky' )
                ,'type'     => Controls_Manager::NUMBER
				,'default'  => 1
				,'min'      => 1
				,'mix'      => 4
				,'condition'=> array( 'layout' => 'slider' )
            )
        );
		
		$this->add_control(
            'columns'
            ,array(
                'label' => esc_html__( 'Columns', 'themesky' )
                ,'type' => Controls_Manager::SELECT
                ,'default' 	=> '6'
				,'options'	=>array(
							'1'		=> '1'
							,'2'	=> '2'
							,'3'	=> '3'
							,'4'	=> '4'
							,'5'	=> '5'
							,'6'	=> '6'
							,'7'	=> '7'
							,'8'	=> '8'
							,'9'	=> '9'
							,'10'	=> '10'
							)
				,'condition'=> array( 'layout' => 'grid' )
            )
        );
		
		$this->add_control(
            'categories'
            ,array(
                'label' 		=> esc_html__( 'Categories', 'themesky' )
                ,'type' 		=> 'ts_autocomplete'
                ,'default' 		=> array()
				,'options'		=> array()
				,'autocomplete'	=> array(
					'type'		=> 'taxonomy'
					,'name'		=> 'ts_logo_cat'
				)
				,'multiple' 	=> true
				,'sortable' 	=> false
				,'label_block' 	=> true
            )
        );
		
		$this->add_control(
            'has_border'
            ,array(
                'label' => esc_html__( 'Has Border', 'themesky' )
                ,'type' 		=> Controls_Manager::SWITCHER
                ,'default' 		=> '0'
				,'return_value' => '1'
				,'label_on'		=> 	esc_html__( 'Yes', 'themesky' )
				,'label_off'	=> 	esc_html__( 'No', 'themesky' )			
                ,'description' => ''
            )
        );
		
		$this->add_control(
            'active_link'
            ,array(
                'label' => esc_html__( 'Activate link', 'themesky' )
                ,'type' 		=> Controls_Manager::SWITCHER
                ,'default' 		=> '1'
				,'return_value' => '1'
				,'label_on'		=> 	esc_html__( 'Yes', 'themesky' )
				,'label_off'	=> 	esc_html__( 'No', 'themesky' )			
                ,'description' => ''
            )
        );
		
		$this->add_control(
            'show_nav'
            ,array(
                'label' => esc_html__( 'Show navigation', 'themesky' )
                ,'type' 		=> Controls_Manager::SWITCHER
                ,'default' 		=> '1'
				,'return_value' => '1'
				,'label_on'		=> 	esc_html__( 'Yes', 'themesky' )
				,'label_off'	=> 	esc_html__( 'No', 'themesky' )				
                ,'description' 	=> ''
				,'condition'=> array( 'layout' => 'slider' )
            )
        );
		
		$this->add_control(
            'auto_play'
            ,array(
                'label' => esc_html__( 'Auto play', 'themesky' )
                ,'type' 		=> Controls_Manager::SWITCHER
                ,'default' 		=> '0'
				,'return_value' => '1'
				,'label_on'		=> 	esc_html__( 'Yes', 'themesky' )
				,'label_off'	=> 	esc_html__( 'No', 'themesky' )			
                ,'description' => ''
				,'condition'=> array( 'layout' => 'slider' )
            )
        );
		
		$this->end_controls_section();
	}
	
	protected function render(){
		$settings = $this->get_settings_for_display();
		
		$default = array(
			'lazy_load'				=> 0
			,'layout'				=> 'slider'
			,'categories' 			=> array()
			,'limit' 				=> 8
			,'rows' 				=> 1
			,'columns' 				=> 6
			,'has_border'			=> 0
			,'active_link'			=> 1
			,'show_nav' 			=> 1
			,'auto_play' 			=> 0
			,'loop'					=> 1
		);
		
		$settings = wp_parse_args( $settings, $default );
		
		extract( $settings );
		
		if( !class_exists('TS_Logos') ){
			return;
		}
		
		if( $this->lazy_load_placeholder( $settings, 'logo' ) ){
			return;
		}
		
		if( is_null( $columns ) ){
			$columns = 6;
		}
		
		$args = array(
			'post_type'				=> 'ts_logo'
			,'post_status'			=> 'publish'
			,'posts_per_page' 		=> $limit
			,'orderby' 				=> 'date'
			,'order' 				=> 'desc'
		);
		
		if( is_array($categories) && count($categories) > 0 ){
			$args['tax_query'] = array(
									array(
										'taxonomy' => 'ts_logo_cat'
										,'terms' => $categories
										,'field' => 'term_id'
										,'include_children' => false
									)
								);
		}
		
		$logos = new WP_Query($args);
		
		global $post;
		
		if( $logos->have_posts() ){
			$count_posts = $logos->post_count;
			
			$classes = array();
			$classes[] = 'ts-logo-slider-wrapper use-logo-setting ts-shortcode';
			
			if( $has_border ){
				$classes[] = 'has-border';
			}
			
			if( $layout == 'slider' ){
				$classes[] = 'ts-slider';
				if( $count_posts > 1 && $count_posts > $rows ){
					$classes[] = 'loading';
				}
				if( $show_nav ){
					$classes[] = 'show-nav nav-middle nav-center';
				}
			}
			$classes[] = 'columns-' . $columns;
			
			$data_attr = array();
			if( $layout == 'slider' ){
				$settings_option = get_option('ts_logo_setting', array());
				$data_break_point = isset($settings_option['responsive']['break_point'])?$settings_option['responsive']['break_point']:array();
				$data_item = isset($settings_option['responsive']['item'])?$settings_option['responsive']['item']:array();
				
				$data_attr[] = 'data-columns="'.$columns.'"';
				$data_attr[] = 'data-nav="'.$show_nav.'"';
				$data_attr[] = 'data-autoplay="'.$auto_play.'"';
				$data_attr[] = 'data-loop="'.$loop.'"';
				$data_attr[] = 'data-rows="'.$rows.'"';
				$data_attr[] = 'data-break_point="'.htmlentities(json_encode( $data_break_point )).'"';
				$data_attr[] = 'data-item="'.htmlentities(json_encode( $data_item )).'"';
			}
			?>
			<div class="<?php echo esc_attr( implode(' ', $classes) ); ?>" style="--ts-columns: <?php echo esc_attr($columns) ?>" <?php echo implode(' ', $data_attr); ?>>
				<div class="content-wrapper">
					<div class="items">
					<?php 
					$count = 0;
					while( $logos->have_posts() ): $logos->the_post(); 
						if( $rows > 1 && $count % $rows == 0 ){
							echo '<div class="logo-group">';
						}
					?>
						<div class="item">
							<?php if( $active_link ):
							$logo_url = get_post_meta($post->ID, 'ts_logo_url', true);
							$logo_target = get_post_meta($post->ID, 'ts_logo_target', true);
							?>
								<a href="<?php echo esc_url($logo_url); ?>" target="<?php echo esc_attr($logo_target); ?>">
							<?php endif; ?>
								<?php 
								if( has_post_thumbnail() ){
									the_post_thumbnail('ts_logo_thumb');
								}
								?>
							<?php if( $active_link ): ?>
								</a>
							<?php endif; ?>
						</div>
					<?php 
						if( $rows > 1 && ($count % $rows == $rows - 1 || $count == $count_posts - 1) ){
							echo '</div>';
						}
						$count++;
					endwhile; 
					?>
					</div>
				</div>
			</div>
		<?php
		}
		wp_reset_postdata();
	}
}

$widgets_manager->register( new TS_Elementor_Widget_Logos() );