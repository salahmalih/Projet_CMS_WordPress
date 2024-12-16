<?php
add_action('widgets_init', 'ts_social_icons_load_widgets');

function ts_social_icons_load_widgets()
{
	register_widget('TS_Social_Icons_Widget');
}

if( !class_exists('TS_Social_Icons_Widget') ){
	class TS_Social_Icons_Widget extends WP_Widget {

		function __construct() {
			$widgetOps = array('classname' => 'ts-social-icons', 'description' => esc_html__('Display Your Social Icons','themesky'));
			parent::__construct('ts_social_icons', esc_html__('TS - Social Icons','themesky'), $widgetOps);
		}

		function widget( $args, $instance ) {
			extract($args);
			
			$defaults = $this->get_default_values();
		
			$instance = wp_parse_args( $instance, $defaults );
			
			extract($instance);

			$logo = '';
			if( $show_logo && function_exists('ecomall_get_theme_options') ){
				$theme_options = ecomall_get_theme_options();
				$logo_image = is_array($theme_options['ts_logo'])?$theme_options['ts_logo']['url']:$theme_options['ts_logo'];
				$logo_text = $theme_options['ts_text_logo'];

				if( !$logo_text ){
					$logo_text = get_bloginfo('name');
				}

				if( $logo_image ){ 
					$logo = '<img src="'. esc_url( $logo_image ) .'" alt="'. esc_attr( $logo_text ) .'" title="'. esc_attr( $logo_text ) .'" class="normal-logo" loading="lazy" />';
				}
			}
			
			$title = apply_filters( 'widget_title', $title );
			
			echo $before_widget;
			if( $show_logo && $logo ){
				echo $before_title . $logo . $after_title;
			}

			if( $title && !$show_logo ){
				echo $before_title . $title . $after_title;
			}
			
			$classes = array();
			$classes[] = 'social-icons';
			$classes[] = $show_tooltip?'show-tooltip':'';
			$classes[] = $text_light?'text-light':'';
			?>
			<div class="<?php echo esc_attr( implode(' ', $classes) ); ?>">
				<?php if( $desc ): ?>
				<div class="social-desc">
					<?php echo esc_html($desc); ?>
				</div>
				<?php endif; ?>
				<ul class="list-icons">
					<?php if( $facebook_url ): ?>
						<li class="facebook"><a href="<?php echo esc_url($facebook_url); ?>" target="_blank" title="<?php echo (!$show_tooltip)?esc_html__('Become our fan', 'themesky'):''; ?>" ><i class="tb-icon-brand-facebook"></i><span class="ts-tooltip social-tooltip"><?php esc_html_e('Facebook', 'themesky'); ?></span></a></li>				
					<?php endif; ?>
					<?php if( $youtube_url ): ?>
						<li class="youtube"><a href="<?php echo esc_url($youtube_url); ?>" target="_blank" title="<?php echo (!$show_tooltip)?esc_html__('Watch Us', 'themesky'):''; ?>" ><i class="tb-icon-brand-youtube"></i><span class="ts-tooltip social-tooltip"><?php esc_html_e('Youtube', 'themesky'); ?></span></a></li>
					<?php endif; ?>
					<?php if( $twitter_url ): ?>
						<li class="twitter"><a href="<?php echo esc_url($twitter_url); ?>" target="_blank" title="<?php echo (!$show_tooltip)?esc_html__('Follow us', 'themesky'):''; ?>" ><i class="tb-icon-brand-twitter"></i><span class="ts-tooltip social-tooltip"><?php esc_html_e('Twitter', 'themesky'); ?></span></a></li>
					<?php endif; ?>
					<?php if( $instagram_url ): ?>
						<li class="instagram"><a href="<?php echo esc_url($instagram_url); ?>" target="_blank" title="<?php echo (!$show_tooltip)?esc_html__('See Us', 'themesky'):''; ?>" ><i class="tb-icon-brand-instagram"></i><span class="ts-tooltip social-tooltip"><?php esc_html_e('Instagram', 'themesky'); ?></span></a></li>
					<?php endif; ?>
					<?php if( $pinterest_url ): ?>
						<li class="pinterest"><a href="<?php echo esc_url($pinterest_url); ?>" target="_blank" title="<?php echo (!$show_tooltip)?esc_html__('See Us', 'themesky'):''; ?>" ><i class="tb-icon-brand-pinterest"></i><span class="ts-tooltip social-tooltip"><?php esc_html_e('Pinterest', 'themesky'); ?></span></a></li>
					<?php endif; ?>
					<?php if( $flickr_url ): ?>
						<li class="flickr"><a href="<?php echo esc_url($flickr_url); ?>" target="_blank" title="<?php echo (!$show_tooltip)?esc_html__('See Us', 'themesky'):''; ?>" ><i class="tb-icon-brand-flickr"></i><span class="ts-tooltip social-tooltip"><?php esc_html_e('Flickr', 'themesky'); ?></span></a></li>
					<?php endif; ?>
					<?php if( $vimeo_url ): ?>
						<li class="vimeo"><a href="<?php echo esc_url($vimeo_url); ?>" target="_blank" title="<?php echo (!$show_tooltip)?esc_html__('Watch Us', 'themesky'):''; ?>" ><i class="tb-icon-brand-vimeo"></i><span class="ts-tooltip social-tooltip"><?php esc_html_e('Vimeo', 'themesky'); ?></span></a></li>
					<?php endif; ?>
					<?php if( $linkedin_url ): ?>
						<li class="linkedin"><a href="<?php echo esc_url($linkedin_url); ?>" target="_blank" title="<?php echo (!$show_tooltip)?esc_html__('See Us', 'themesky'):''; ?>" ><i class="tb-icon-brand-linkedin"></i><span class="ts-tooltip social-tooltip"><?php esc_html_e('Linked in', 'themesky'); ?></span></a></li>
					<?php endif; ?>
					<?php if( $viber_number ): ?>
						<li class="viber"><a href="viber://<?php echo esc_attr($viber_number); ?>" target="_blank" title="<?php echo (!$show_tooltip)?esc_html__('Call Us', 'themesky'):''; ?>" ><i class="tb-icon-phone-call"></i><span class="ts-tooltip social-tooltip"><?php esc_html_e('Viber', 'themesky'); ?></span></a></li>
					<?php endif; ?>
					<?php if( $skype_username ): ?>
						<li class="skype"><a href="skype:<?php echo esc_attr($skype_username); ?>?chat" target="_blank" title="<?php echo (!$show_tooltip)?esc_html__('Chat With Us', 'themesky'):''; ?>" ><i class="tb-icon-brand-skype"></i><span class="ts-tooltip social-tooltip"><?php esc_html_e('Skype', 'themesky'); ?></span></a></li>
					<?php endif; ?>	
					<?php if( $custom_link ): ?>
						<li class="custom"><a href="<?php echo esc_url($custom_link); ?>" target="_blank" title="<?php echo (!$show_tooltip)?$custom_text:''; ?>" ><i class="<?php echo esc_attr($custom_font); ?>"></i><span class="ts-tooltip social-tooltip"><?php echo esc_html($custom_text); ?></span></a></li>
					<?php endif; ?>
				</ul>
			</div>

			<?php
			echo $after_widget;
		}

		function update( $new_instance, $old_instance ) {
			$instance = $old_instance;		
			$instance['title'] 				= $new_instance['title'];
			$instance['desc'] 				=  $new_instance['desc'];
			$instance['facebook_url'] 		=  $new_instance['facebook_url'];
			$instance['twitter_url'] 		=  $new_instance['twitter_url'];			
			$instance['flickr_url'] 		=  $new_instance['flickr_url'];		
			$instance['vimeo_url'] 			=  $new_instance['vimeo_url'];		
			$instance['youtube_url'] 		=  $new_instance['youtube_url'];
			$instance['viber_number'] 		=  $new_instance['viber_number'];		
			$instance['skype_username'] 	=  $new_instance['skype_username'];		
			$instance['instagram_url'] 		=  $new_instance['instagram_url'];
			$instance['pinterest_url'] 		=  $new_instance['pinterest_url'];
			$instance['linkedin_url'] 		=  $new_instance['linkedin_url'];
			$instance['custom_link'] 		=  $new_instance['custom_link'];		
			$instance['custom_text'] 		=  $new_instance['custom_text'];		
			$instance['custom_font'] 		=  $new_instance['custom_font'];		
			$instance['show_tooltip'] 		=  empty($new_instance['show_tooltip']) ? 0 : 1;
			$instance['show_logo'] 			=  empty($new_instance['show_logo']) ? 0 : 1;
			$instance['text_light'] 		=  empty($new_instance['text_light']) ? 0 : 1;
			return $instance;
		}
		
		function get_default_values(){
			return array(
						'title'				=> ''
						,'desc'				=> ''
						,'facebook_url' 	=> ''
						,'twitter_url' 		=> ''
						,'flickr_url' 		=> ''
						,'vimeo_url'		=> '' 
						,'youtube_url'		=> ''
						,'viber_number'		=> ''
						,'skype_username'	=> ''
						,'instagram_url'	=> ''
						,'pinterest_url'	=> ''
						,'linkedin_url'		=> ''
						,'custom_link' 		=> ''
						,'custom_text' 		=> ''
						,'custom_font' 		=> ''
						,'show_tooltip'		=> 1
						,'show_logo'		=> 0
						,'text_light'		=> 0
					);
		}

		function form( $instance ) {
			
			$defaults = $this->get_default_values();
		
			$instance = wp_parse_args( (array) $instance, $defaults );	
		?>
			<p>
				<label for="<?php echo $this->get_field_id('title'); ?>"><?php esc_html_e('Enter your title', 'themesky'); ?></label>
				<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($instance['title']); ?>" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('desc'); ?>"><?php esc_html_e('Enter description about your social icons', 'themesky'); ?></label>
				<textarea rows="3" cols="30" class="widefat" id="<?php echo $this->get_field_id('desc'); ?>" name="<?php echo $this->get_field_name('desc'); ?>"><?php echo esc_html($instance['desc']); ?></textarea>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('facebook_url'); ?>"><?php esc_html_e('Facebook URL', 'themesky'); ?></label>
				<input class="widefat" type="text" id="<?php echo $this->get_field_id('facebook_url'); ?>" name="<?php echo $this->get_field_name('facebook_url'); ?>" value="<?php echo esc_attr($instance['facebook_url']); ?>" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('youtube_url'); ?>"><?php esc_html_e('Youtube URL', 'themesky'); ?></label>
				<input class="widefat" type="text" id="<?php echo $this->get_field_id('youtube_url'); ?>" name="<?php echo $this->get_field_name('youtube_url'); ?>" value="<?php echo esc_attr($instance['youtube_url']); ?>" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('twitter_url'); ?>"><?php esc_html_e('Twitter URL', 'themesky'); ?></label>
				<input class="widefat" type="text" id="<?php echo $this->get_field_id('twitter_url'); ?>" name="<?php echo $this->get_field_name('twitter_url'); ?>" value="<?php echo esc_attr($instance['twitter_url']); ?>" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('instagram_url'); ?>"><?php esc_html_e('Instagram URL', 'themesky'); ?></label>
				<input class="widefat" type="text" id="<?php echo $this->get_field_id('instagram_url'); ?>" name="<?php echo $this->get_field_name('instagram_url'); ?>" value="<?php echo esc_attr($instance['instagram_url']); ?>" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('pinterest_url'); ?>"><?php esc_html_e('Pinterest URL', 'themesky'); ?></label>
				<input class="widefat" type="text" id="<?php echo $this->get_field_id('pinterest_url'); ?>" name="<?php echo $this->get_field_name('pinterest_url'); ?>" value="<?php echo esc_attr($instance['pinterest_url']); ?>" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('linkedin_url'); ?>"><?php esc_html_e('Linked in URL', 'themesky'); ?></label>
				<input class="widefat" type="text" id="<?php echo $this->get_field_id('linkedin_url'); ?>" name="<?php echo $this->get_field_name('linkedin_url'); ?>" value="<?php echo esc_attr($instance['linkedin_url']); ?>" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('vimeo_url'); ?>"><?php esc_html_e('Vimeo URL', 'themesky'); ?></label>
				<input class="widefat" type="text" id="<?php echo $this->get_field_id('vimeo_url'); ?>" name="<?php echo $this->get_field_name('vimeo_url'); ?>" value="<?php echo esc_attr($instance['vimeo_url']); ?>" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('flickr_url'); ?>"><?php esc_html_e('Flickr URL', 'themesky'); ?></label>
				<input class="widefat" type="text" id="<?php echo $this->get_field_id('flickr_url'); ?>" name="<?php echo $this->get_field_name('flickr_url'); ?>" value="<?php echo esc_attr($instance['flickr_url']); ?>" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('viber_number'); ?>"><?php esc_html_e('Viber Number', 'themesky'); ?></label>
				<input class="widefat" type="text" id="<?php echo $this->get_field_id('viber_number'); ?>" name="<?php echo $this->get_field_name('viber_number'); ?>" value="<?php echo esc_attr($instance['viber_number']); ?>" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('skype_username'); ?>"><?php esc_html_e('Skype Username', 'themesky'); ?></label>
				<input class="widefat" type="text" id="<?php echo $this->get_field_id('skype_username'); ?>" name="<?php echo $this->get_field_name('skype_username'); ?>" value="<?php echo esc_attr($instance['skype_username']); ?>" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('custom_link'); ?>"><?php esc_html_e('Custom Link', 'themesky'); ?></label>
				<input class="widefat" type="text" id="<?php echo $this->get_field_id('custom_link'); ?>" name="<?php echo $this->get_field_name('custom_link'); ?>" value="<?php echo esc_attr($instance['custom_link']); ?>" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('custom_text'); ?>"><?php esc_html_e('Custom Text - Show on tooltip', 'themesky'); ?></label>
				<input class="widefat" type="text" id="<?php echo $this->get_field_id('custom_text'); ?>" name="<?php echo $this->get_field_name('custom_text'); ?>" value="<?php echo esc_attr($instance['custom_text']); ?>" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('custom_font'); ?>"><?php esc_html_e('Custom Font - Use Font Icon class', 'themesky'); ?></label>
				<input class="widefat" type="text" id="<?php echo $this->get_field_id('custom_font'); ?>" name="<?php echo $this->get_field_name('custom_font'); ?>" value="<?php echo esc_attr($instance['custom_font']); ?>" />
			</p>
			<p>
				<input type="checkbox" id="<?php echo $this->get_field_id('show_logo'); ?>" name="<?php echo $this->get_field_name('show_logo'); ?>" value="1" <?php checked($instance['show_logo'], 1); ?> />
				<label for="<?php echo $this->get_field_id('show_logo'); ?>"><?php esc_html_e('Show Logo Instead Of Title', 'themesky'); ?></label>
			</p>
			<p>
				<input type="checkbox" id="<?php echo $this->get_field_id('text_light'); ?>" name="<?php echo $this->get_field_name('text_light'); ?>" value="1" <?php checked($instance['text_light'], 1); ?> />
				<label for="<?php echo $this->get_field_id('text_light'); ?>"><?php esc_html_e('Text Light', 'themesky'); ?></label>
			</p>
			<p>
				<input type="checkbox" id="<?php echo $this->get_field_id('show_tooltip'); ?>" name="<?php echo $this->get_field_name('show_tooltip'); ?>" value="1" <?php checked($instance['show_tooltip'], 1); ?> />
				<label for="<?php echo $this->get_field_id('show_tooltip'); ?>"><?php esc_html_e('Show Tooltip', 'themesky'); ?></label>
			</p>
			<?php 
		}
	}
}