<?php
use Elementor\Controls_Manager;

class TS_Elementor_Widget_Testimonial extends TS_Elementor_Widget_Base{
	public function get_name(){
        return 'ts-testimonial';
    }
	
	public function get_title(){
        return esc_html__( 'TS Testimonial', 'themesky' );
    }
	
	public function get_categories(){
        return array( 'ts-elements', 'general' );
    }
	
	public function get_icon(){
		return 'eicon-testimonial';
	}
	
	protected function register_controls(){
		$this->start_controls_section(
            'section_general'
            ,array(
                'label' 	=> esc_html__( 'General', 'themesky' )
                ,'tab'   	=> Controls_Manager::TAB_CONTENT
            )
        );
		
		$this->add_lazy_load_controls( array( 'thumb-height' => 50, 'thumb-label' => esc_html__( 'Lazy Load - Content Height', 'themesky' ) ) );
		
		$this->add_control(
            'categories'
            ,array(
                'label' 		=> esc_html__( 'Categories', 'themesky' )
                ,'type' 		=> 'ts_autocomplete'
                ,'default' 		=> array()
				,'options'		=> array()
				,'autocomplete'	=> array(
					'type'		=> 'taxonomy'
					,'name'		=> 'ts_testimonial_cat'
				)
				,'multiple' 	=> true
				,'sortable' 	=> false
				,'label_block' 	=> true
            )
        );
		
		$this->add_control(
            'ids'
            ,array(
                'label' 		=> esc_html__( 'Specific testimonials', 'themesky' )
                ,'type' 		=> 'ts_autocomplete'
                ,'default' 		=> array()
				,'options'		=> array()
				,'autocomplete'	=> array(
					'type'		=> 'post'
					,'name'		=> 'ts_testimonial'
				)
				,'multiple' 	=> true
				,'label_block' 	=> true
            )
        );
		
		$this->add_control(
            'columns'
            ,array(
                'label' => esc_html__( 'Columns', 'themesky' )
                ,'type' => Controls_Manager::SELECT
                ,'default' => '1'
				,'options'	=>array(
							'1'		=> esc_html__( '1', 'themesky' )
							,'2'	=> esc_html__( '2', 'themesky' )
							,'3'	=> esc_html__( '3', 'themesky' )
							,'4'	=> esc_html__( '4', 'themesky' )
							,'5'	=> esc_html__( '5', 'themesky' )
							)			
                ,'description' => ''
            )
        );
		
		$this->add_control(
            'limit'
            ,array(
                'label'     => esc_html__( 'Limit', 'themesky' )
                ,'type'     => Controls_Manager::NUMBER
				,'default'  => 3
				,'min'      => 1
            )
        );
		
		$this->add_control(
            'excerpt_words'
            ,array(
                'label'     => esc_html__( 'Number of words in excerpt', 'themesky' )
                ,'type'     => Controls_Manager::NUMBER
				,'default'  => 60
				,'min'      => '-1'
				,'description'	=> esc_html__( 'Input -1 to show all content', 'themesky' )
            )
        );
		
		$this->add_control(
            'show_avatar'
            ,array(
                'label' 		=> esc_html__( 'Avatar', 'themesky' )
                ,'type' 		=> Controls_Manager::SWITCHER
                ,'default' 		=> '1'
				,'return_value' => '1'
				,'label_on'		=> 	esc_html__( 'Show', 'themesky' )
				,'label_off'	=> 	esc_html__( 'Hide', 'themesky' )			
                ,'description' 	=> ''
                ,'separator' 	=> 'before'
            )
        );
		
		$this->add_control(
            'show_name'
            ,array(
                'label' 		=> esc_html__( 'Name', 'themesky' )
                ,'type' 		=> Controls_Manager::SWITCHER
                ,'default' 		=> '1'
				,'return_value' => '1'
				,'label_on'		=> 	esc_html__( 'Show', 'themesky' )
				,'label_off'	=> 	esc_html__( 'Hide', 'themesky' )			
                ,'description' 	=> ''
            )
        );
		
		$this->add_control(
            'show_byline'
            ,array(
                'label' 		=> esc_html__( 'Byline', 'themesky' )
                ,'type' 		=> Controls_Manager::SWITCHER
                ,'default' 		=> '0'
				,'return_value' => '1'
				,'label_on'		=> 	esc_html__( 'Show', 'themesky' )
				,'label_off'	=> 	esc_html__( 'Hide', 'themesky' )			
                ,'description' 	=> ''
            )
        );
		
		$this->add_control(
            'show_date'
            ,array(
                'label' 		=> esc_html__( 'Date', 'themesky' )
                ,'type' 		=> Controls_Manager::SWITCHER
                ,'default' 		=> '0'
				,'return_value' => '1'
				,'label_on'		=> 	esc_html__( 'Show', 'themesky' )
				,'label_off'	=> 	esc_html__( 'Hide', 'themesky' )			
                ,'description' 	=> ''
            )
        );
		
		$this->add_control(
            'show_rating'
            ,array(
                'label' 		=> esc_html__( 'Rating', 'themesky' )
                ,'type' 		=> Controls_Manager::SWITCHER
                ,'default' 		=> '1'
				,'return_value' => '1'
				,'label_on'		=> 	esc_html__( 'Show', 'themesky' )
				,'label_off'	=> 	esc_html__( 'Hide', 'themesky' )			
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
		
		$this->add_control(
            'is_slider'
            ,array(
                'label' 		=> esc_html__( 'Enable slider', 'themesky' )
                ,'type' 		=> Controls_Manager::SWITCHER
                ,'default' 		=> '0'
				,'return_value' => '1'
				,'label_on'		=> 	esc_html__( 'Yes', 'themesky' )
				,'label_off'	=> 	esc_html__( 'No', 'themesky' )			
                ,'description' => ''
            )
        );
		
		$this->add_control(
            'show_dots'
            ,array(
                'label' 		=> esc_html__( 'Show Dots', 'themesky' )
                ,'type' 		=> Controls_Manager::SWITCHER
                ,'default' 		=> '1'
				,'return_value' => '1'
				,'label_on'		=> 	esc_html__( 'Yes', 'themesky' )
				,'label_off'	=> 	esc_html__( 'No', 'themesky' )			
                ,'description' => ''
            )
        );
		
		$this->add_control(
            'auto_play'
            ,array(
                'label' 		=> esc_html__( 'Auto play', 'themesky' )
                ,'type' 		=> Controls_Manager::SWITCHER
                ,'default' 		=> '0'
				,'return_value' => '1'
				,'label_on'		=> 	esc_html__( 'Yes', 'themesky' )
				,'label_off'	=> 	esc_html__( 'No', 'themesky' )			
                ,'description' => ''
            )
        );
		
		$this->add_control(
            'loop'
            ,array(
                'label' 		=> esc_html__( 'Loop', 'themesky' )
                ,'type' 		=> Controls_Manager::SWITCHER
                ,'default' 		=> '1'
				,'return_value' => '1'
				,'label_on'		=> esc_html__( 'Yes', 'themesky' )
				,'label_off'	=> esc_html__( 'No', 'themesky' )
                ,'description' 	=> ''
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
            'content_bg_color'
            ,array(
                'label'     	=> esc_html__( 'Background Color', 'themesky' )
                ,'type'     	=> Controls_Manager::COLOR
				,'default'  	=> ''
				,'selectors'	=> array(
					'{{WRAPPER}} blockquote' => 'background-color: {{VALUE}}'
				)
            )
        );
		
		$this->add_control(
            'content_color'
            ,array(
                'label'     	=> esc_html__( 'Content Color', 'themesky' )
                ,'type'     	=> Controls_Manager::COLOR
				,'default'  	=> ''
				,'selectors'	=> array(
					'{{WRAPPER}} blockquote .content' => 'color: {{VALUE}}'
				)
            )
        );
		
		$this->add_control(
            'meta_color'
            ,array(
                'label'     	=> esc_html__( 'Meta Color', 'themesky' )
                ,'type'     	=> Controls_Manager::COLOR
				,'default'  	=> ''
				,'selectors'	=> array(
					'{{WRAPPER}} blockquote .author-role' => 'color: {{VALUE}}'
				)
            )
        );
		
		$this->add_responsive_control(
			'content_padding'
			,array(
				'type' => Controls_Manager::DIMENSIONS
				,'label' => esc_html__( 'Blockquote Padding', 'themesky' )
				,'size_units' => array( 'px', '%', 'em', 'rem' )
				,'selectors' => array(
					'{{WRAPPER}} .ts-testimonial-wrapper blockquote' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				)
			)
		);
	
		$this->end_controls_section();
	}
	
	protected function render(){
		$settings = $this->get_settings_for_display();
		
		$default = array(
			'lazy_load'				=> 0
			,'categories'			=> array()
			,'columns'				=> 1
			,'limit'				=> 3
			,'text_color_style'		=> 'text-default'
			,'ids'					=> array()
			,'show_avatar'			=> 1
			,'show_name'			=> 1
			,'show_byline'			=> 0
			,'show_date'			=> 0
			,'show_rating'			=> 1
			,'excerpt_words'		=> 60
			,'is_slider'			=> 1
			,'show_nav'				=> 0
			,'show_dots'			=> 1
			,'auto_play'			=> 0
			,'loop'					=> 1
		);
		
		$settings = wp_parse_args( $settings, $default );
		
		if( $this->lazy_load_placeholder( $settings, 'testimonial' ) ){
			return;
		}
		
		extract( $settings );
		
		$classes = array();
		$classes[] = 'columns-'.$columns;
		$classes[] = $text_color_style;
		if($is_slider){
			$classes[] = 'ts-slider';
			if( $show_dots ){
				$classes[] = 'show-dots';
			}
			if( $show_nav ){
				$classes[] = 'show-nav';
			}
		}
		
		$data_attr = array();
		if( $is_slider ){
			$data_attr[] = 'data-columns="'.$columns.'"';
			$data_attr[] = 'data-nav="'.$show_nav.'"';
			$data_attr[] = 'data-dots="'.$show_dots.'"';
			$data_attr[] = 'data-autoplay="'.$auto_play.'"';
			$data_attr[] = 'data-loop="'.$loop.'"';
		}

		global $post, $ts_testimonials;
		
		$args = array(
				'post_type'				=> 'ts_testimonial'
				,'post_status'			=> 'publish'
				,'posts_per_page' 		=> $limit
				,'orderby' 				=> 'date'
				,'order' 				=> 'desc'
			);
		
		if( is_array($categories) && count($categories) > 0 ){
			$args['tax_query'] = array(
									array(
										'taxonomy' 			=> 'ts_testimonial_cat'
										,'terms' 			=> $categories
										,'field' 			=> 'term_id'
										,'include_children' => false
									)
								);
		}
		
		if( is_array($ids) && count($ids) > 0 ){
			$args['post__in'] = $ids;
			$args['orderby'] = 'post__in';
		}
		
		$testimonials = new WP_Query($args);
		
		if( $testimonials->have_posts() ){
			if( isset($testimonials->post_count) && $testimonials->post_count <= 1 ){
				$is_slider = false;
			}
			?>
			<div class="ts-testimonial-wrapper ts-shortcode <?php echo esc_attr(implode(' ', $classes)); ?>" style="--ts-columns: <?php echo esc_attr($columns) ?>" <?php echo implode(' ', $data_attr); ?>>
		
				<div class="items <?php echo ($is_slider)?'loading':'' ?>">
				<?php
				while( $testimonials->have_posts() ){
					$testimonials->the_post();			
					
					if( $excerpt_words != -1 ){		
						if( function_exists('ecomall_the_excerpt_max_words') ){
							$content = ecomall_the_excerpt_max_words($excerpt_words, $post, true, '', false);
						}
						else{
							$content = wp_trim_words( $post->post_content, $excerpt_words );
						}
					} 

					$byline = get_post_meta($post->ID, 'ts_byline', true);
					$url = get_post_meta($post->ID, 'ts_url', true);
					if( $url == '' ){
						$url = '#';
					}
					$rating = get_post_meta($post->ID, 'ts_rating', true);
					$rating_percent = '0';
					if( $rating != '-1' && $rating != '' ){
						$rating_percent = $rating * 100 / 5;
					}
					
					$show_item_avatar = $show_avatar;
					if( $show_item_avatar ){
						$gravatar_email = get_post_meta($post->ID, 'ts_gravatar_email', true);
						if( !has_post_thumbnail() && ($gravatar_email == '' || !is_email($gravatar_email)) ){
							$show_item_avatar = false;
						}
					}
					
					?>
					<div class="item">
						<blockquote>
							<?php if( $show_item_avatar ): ?>
							<div class="image">
								<?php echo $ts_testimonials->get_image($post->ID); ?>
							</div>
							<?php endif; ?>
							
							<?php if( $show_name || ($show_byline && $byline != '') || $show_date ): ?>
							<div class="author-role">
								<?php if( $show_name ): ?>
								<span class="author">
									<a href="<?php echo esc_url($url); ?>" target="_blank"><?php echo get_the_title($post->ID); ?></a>
								</span>
								<?php endif; ?>
								
								<?php if( $show_byline && $byline != '' ): ?>
								<span class="role"><?php echo esc_html($byline); ?></span>
								<?php endif; ?>
								
								<?php if( $show_date ) : ?>
								<span class="date-time">
									<?php echo get_the_time( get_option('date_format') ); ?>
								</span>
								<?php endif; ?>
							</div>
							<?php endif; ?>
							
							<?php if( $show_rating && $rating != '-1' && $rating != ''): ?>
								<div class="rating" title="<?php printf( esc_html__('Rated %s out of 5', 'themesky'), $rating ); ?>">
									<span style="width: <?php echo $rating_percent . '%'; ?>"><?php printf( esc_html__('Rated %s out of 5', 'themesky'), $rating ); ?></span>
								</div>
							<?php endif; ?>
								
							<div class="content">
								<?php if( $excerpt_words != -1 ): ?> 
									<?php echo esc_html($content); ?>
								<?php else:
									the_content();		
								endif; ?>
							</div>
							
						</blockquote>
					</div>
					<?php
				}
				?>
				</div>
			</div>
			<?php
		}
		
		wp_reset_postdata();
	}
}

$widgets_manager->register( new TS_Elementor_Widget_Testimonial() );