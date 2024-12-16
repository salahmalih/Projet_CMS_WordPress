<?php
use Elementor\Controls_Manager;

class TS_Elementor_Widget_Team_Members extends TS_Elementor_Widget_Base{
	public function get_name(){
        return 'ts-team-members';
    }
	
	public function get_title(){
        return esc_html__( 'TS Team Members', 'themesky' );
    }
	
	public function get_categories(){
        return array( 'ts-elements', 'general' );
    }
	
	public function get_icon(){
		return 'eicon-person';
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
            'limit'
            ,array(
                'label'     => esc_html__( 'Number of members', 'themesky' )
                ,'type'     => Controls_Manager::NUMBER
				,'default'  => 6
				,'min'      => 1
            )
        );
		
		$this->add_control(
            'ids'
            ,array(
                'label'     	=> esc_html__( 'Include these members', 'themesky' )
                ,'type'      	=> 'ts_autocomplete'
                ,'default'   	=> ''
                ,'options'   	=> array()
				,'autocomplete'	=> array(
					'type'		=> 'post'
					,'name'		=> 'ts_team'
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
                ,'default' => '4'
				,'options'	=>array(
							'1'		=> '1'
							,'2'	=> '2'
							,'3'	=> '3'
							,'4'	=> '4'
							,'5'	=> '5'
							,'6'	=> '6'
							)			
                ,'description' => ''
            )
        );
		
		$this->add_control(
            'target'
            ,array(
                'label' 		=> esc_html__( 'Target', 'themesky' )
                ,'type' 		=> Controls_Manager::SELECT
                ,'default' 		=> '_blank'
				,'options'		=> array(
									'_blank'	=> esc_html__( 'New Window Tab', 'themesky' )
									,'_self'	=> esc_html__( 'Self', 'themesky' )
								)			
                ,'description' => ''
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
            'show_nav'
            ,array(
                'label' 		=> esc_html__( 'Show navigation', 'themesky' )
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
	}
	
	protected function render(){
		$settings = $this->get_settings_for_display();
		
		$default = array(
			'limit'			=> 6
			,'ids'			=> ''
			,'columns'		=> 4
			,'target'		=> '_blank'
			,'is_slider'	=> 0			
			,'show_nav'		=> 1				
			,'auto_play'	=> 0
			,'loop'			=> 1
		);
		
		$settings = wp_parse_args( $settings, $default );
		
		extract( $settings );
		
		$columns = absint($columns);
		if( !in_array( $columns, array(1, 2, 3, 4, 5, 6) ) ){
			$columns = 4;
		}
		
		global $post, $ts_team_members;
		$thumb_size_name = isset($ts_team_members->thumb_size_name)?$ts_team_members->thumb_size_name:'ts_team_thumb';
		
		$args = array(
					'post_type'				=> 'ts_team'
					,'post_status'			=> 'publish'
					,'posts_per_page'		=> $limit
				);

		if( $ids ){
			$args['post__in'] = $ids;
			$args['orderby'] = 'post__in';
		}
		
		$team = new WP_Query($args);
		
		if( $team->have_posts() ){
			$classes = array();
			$classes[] = 'ts-team-members ts-shortcode';
			$classes[] = 'columns-'. $columns;
			if( $is_slider ){
				$classes[] = 'ts-slider';
				if( $show_nav ){
					$classes[] = 'show-nav';
					$classes[] = 'nav-middle';
					$classes[] = 'middle-thumbnail';
				}
			}
			
			$data_attr = array();
			if( $is_slider ){
				$data_attr[] = 'data-nav="'.$show_nav.'"';
				$data_attr[] = 'data-autoplay="'.$auto_play.'"';
				$data_attr[] = 'data-loop="'.$loop.'"';
				$data_attr[] = 'data-columns="'.$columns.'"';
			}
			?>
			<div class="<?php echo esc_attr( implode(' ', $classes) ) ?>" <?php echo implode(' ', $data_attr); ?> style="--ts-columns: <?php echo esc_attr($columns) ?>">
				<div class="items <?php echo $is_slider?'loading':''; ?>">
			<?php
			while( $team->have_posts() ){
				$team->the_post();
				$profile_link = get_post_meta($post->ID, 'ts_profile_link', true);
				if( $profile_link == '' ){
					$profile_link = '#';
				}
				$name = get_the_title($post->ID);
				$role = get_post_meta($post->ID, 'ts_role', true);
				
				$facebook_link = get_post_meta($post->ID, 'ts_facebook_link', true);
				$twitter_link = get_post_meta($post->ID, 'ts_twitter_link', true);
				$linkedin_link = get_post_meta($post->ID, 'ts_linkedin_link', true);
				$rss_link = get_post_meta($post->ID, 'ts_rss_link', true);
				$dribbble_link = get_post_meta($post->ID, 'ts_dribbble_link', true);
				$pinterest_link = get_post_meta($post->ID, 'ts_pinterest_link', true);
				$instagram_link = get_post_meta($post->ID, 'ts_instagram_link', true);
				$flickr_link = get_post_meta($post->ID, 'ts_flickr_link', true);
				$custom_link = get_post_meta($post->ID, 'ts_custom_link', true);
				$custom_link_icon_class = get_post_meta($post->ID, 'ts_custom_link_icon_class', true);
				
				$social_content = '';
				
				if( $facebook_link ){
					$social_content .= '<a class="facebook" href="'.esc_url($facebook_link).'" target="'.$target.'"><i class="tb-icon-brand-facebook"></i></a>';
				}
				if( $twitter_link ){
					$social_content .= '<a class="twitter" href="'.esc_url($twitter_link).'" target="'.$target.'"><i class="tb-icon-brand-twitter"></i></a>';
				}
				if( $instagram_link ){
					$social_content .= '<a class="instagram" href="'.esc_url($instagram_link).'" target="'.$target.'"><i class="tb-icon-brand-instagram"></i></a>';
				}
				if( $linkedin_link ){
					$social_content .= '<a class="linked" href="'.esc_url($linkedin_link).'" target="'.$target.'"><i class="tb-icon-brand-linkedin"></i></a>';
				}
				if( $pinterest_link ){
					$social_content .= '<a class="pinterest" href="'.esc_url($pinterest_link).'" target="'.$target.'"><i class="tb-icon-brand-pinterest"></i></a>';
				}
				if( $rss_link ){
					$social_content .= '<a class="rss" href="'.esc_url($rss_link).'" target="'.$target.'"><i class="tb-icon-rss-1"></i></a>';
				}
				if( $dribbble_link ){
					$social_content .= '<a class="dribbble" href="'.esc_url($dribbble_link).'" target="'.$target.'"><i class="tb-icon-brand-dribbble"></i></a>';
				}
				if( $flickr_link ){
					$social_content .= '<a class="flickr" href="'.esc_url($flickr_link).'" target="'.$target.'"><i class="tb-icon-brand-flickr"></i></a>';
				}
				if( $custom_link ){
					$social_content .= '<a class="custom" href="'.esc_url($custom_link).'" target="'.$target.'"><i class="'.esc_attr($custom_link_icon_class).'"></i></a>';
				}
				
				?>
				<div class="item <?php echo (has_post_thumbnail())?'has-thumbnail':'' ?>">
					<div class="team-content">
						<div class="image-thumbnail">
							<?php if( has_post_thumbnail() ): ?>
							<figure>
								<a href="<?php echo esc_url($profile_link); ?>" target="<?php echo esc_attr($target) ?>">
								<?php the_post_thumbnail($thumb_size_name); ?>
								</a>
							</figure>
							<?php endif; ?>
						</div>
						
						<header class="team-info">
							<h3 class="name"><a href="<?php echo esc_url($profile_link); ?>" target="<?php echo esc_attr($target) ?>"><?php echo esc_html($name); ?></a></h3>
							<span class="member-role"><?php echo esc_html($role); ?></span>
							<?php if( $social_content ): ?>
							<span class="member-social"><?php echo $social_content; ?></span>
							<?php endif; ?>
						</header>
					</div>
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

$widgets_manager->register( new TS_Elementor_Widget_Team_Members() );