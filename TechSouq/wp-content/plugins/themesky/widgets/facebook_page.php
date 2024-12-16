<?php
add_action('widgets_init', 'ts_facebook_page_load_widgets');

function ts_facebook_page_load_widgets()
{
	register_widget('TS_Facebook_Page_Widget');
}

if( !class_exists('TS_Facebook_Page_Widget') ){
	class TS_Facebook_Page_Widget extends WP_Widget {

		function __construct() {
			$widgetOps = array('classname' => 'ts-facebook-page-widget', 'description' => esc_html__('Display the Facebook Page', 'themesky'));
			parent::__construct('ts_facebook_page', esc_html__('TS - Facebook Page', 'themesky'), $widgetOps);
		}

		function widget( $args, $instance ) {
			
			extract($args);
			
			$defaults = $this->get_default_values();
							
			$instance = wp_parse_args( $instance, $defaults );
			
			extract($instance);
			
			$title = apply_filters( 'widget_title', $title );
			if( !$url ){
				return;
			}
			
			$show_faces 		= !$show_faces ? 'false' : 'true';
			$show_posts 		= !$show_posts ? 'false' : 'true';
			$hide_cover_photo 	= !$show_cover_photo ? 'true' : 'false';
			$small_header 		= !$small_header ? 'false' : 'true';
			
			echo $before_widget;
			
			if( $title ){
				echo $before_title . $title . $after_title; 
			}
			?>
			<div class="ts-facebook-page-wrapper">
				<div class="fb-page" data-href="<?php echo esc_url($url) ?>" data-small-header="<?php echo esc_attr($small_header) ?>" data-adapt-container-width="true" data-height="<?php echo esc_attr($box_height) ?>" 
					data-hide-cover="<?php echo esc_attr($hide_cover_photo) ?>" data-show-facepile="<?php echo esc_attr($show_faces) ?>" data-show-posts="<?php echo esc_attr($show_posts) ?>">
				</div>
			</div>
			
			<div id="fb-root"></div>
			<script>(function(d, s, id) {
			  var js, fjs = d.getElementsByTagName(s)[0];
			  if (d.getElementById(id)) return;
			  js = d.createElement(s); js.id = id;
			  js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.5";
			  fjs.parentNode.insertBefore(js, fjs);
			}(document, 'script', 'facebook-jssdk'));</script>
			<?php
			echo $after_widget;
		}

		function update( $new_instance, $old_instance ) {
			$instance = $old_instance;	
			$instance['title'] 					=  strip_tags($new_instance['title']);
			$instance['url'] 					=  $new_instance['url'];
			$instance['show_faces'] 			=  empty($new_instance['show_faces']) ? 0 : 1;
			$instance['show_posts'] 			=  empty($new_instance['show_posts']) ? 0 : 1;									
			$instance['show_cover_photo'] 		=  empty($new_instance['show_cover_photo']) ? 0 : 1;																																	
			$instance['small_header'] 			=  empty($new_instance['small_header']) ? 0 : 1;																																	
			$instance['box_height'] 			=  absint($new_instance['box_height']);															
			return $instance;
		}
		
		function get_default_values(){
			return array(
						'title'					=> 'Find us on Facebook'
						,'url'					=> ''
						,'show_faces'			=> 1
						,'show_posts'			=> 0
						,'show_cover_photo'		=> 1
						,'small_header'			=> 0
						,'box_height'			=> 250
					);
		}

		function form( $instance ) {
			$defaults = $this->get_default_values();
							
			$instance = wp_parse_args( (array) $instance, $defaults );
		?>
			<p>
				<label for="<?php echo $this->get_field_id('title'); ?>"><?php esc_html_e('Enter your title', 'themesky'); ?> </label>
				<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($instance['title']); ?>" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('url'); ?>"><?php esc_html_e('Facebook page URL', 'themesky'); ?> </label>
				<input class="widefat" id="<?php echo $this->get_field_id('url'); ?>" name="<?php echo $this->get_field_name('url'); ?>" type="text" value="<?php echo esc_attr($instance['url']); ?>" />
			</p>
			<p>
				<input value="1" class="" type="checkbox" id="<?php echo $this->get_field_id('show_faces'); ?>" name="<?php echo $this->get_field_name('show_faces'); ?>" <?php checked($instance['show_faces'], 1); ?> />
				<label for="<?php echo $this->get_field_id('show_faces'); ?>"><?php esc_html_e('Show Faces', 'themesky'); ?></label>
			</p>
			<p>
				<input value="1" class="" type="checkbox" id="<?php echo $this->get_field_id('show_posts'); ?>" name="<?php echo $this->get_field_name('show_posts'); ?>" <?php checked($instance['show_posts'], 1); ?> />
				<label for="<?php echo $this->get_field_id('show_posts'); ?>"><?php esc_html_e('Show Posts', 'themesky'); ?></label>
			</p>
			<p>
				<input value="1" class="" type="checkbox" id="<?php echo $this->get_field_id('show_cover_photo'); ?>" name="<?php echo $this->get_field_name('show_cover_photo'); ?>" <?php checked($instance['show_cover_photo'], 1); ?> />
				<label for="<?php echo $this->get_field_id('show_cover_photo'); ?>"><?php esc_html_e('Show cover photo', 'themesky'); ?></label>
			</p>
			<p>
				<input value="1" class="" type="checkbox" id="<?php echo $this->get_field_id('small_header'); ?>" name="<?php echo $this->get_field_name('small_header'); ?>" <?php checked($instance['small_header'], 1); ?> />
				<label for="<?php echo $this->get_field_id('small_header'); ?>"><?php esc_html_e('Small header', 'themesky'); ?></label>
			</p>
			
			<p>
				<label for="<?php echo $this->get_field_id('box_height'); ?>"><?php esc_html_e('Box height', 'themesky'); ?> </label>
				<input class="widefat" id="<?php echo $this->get_field_id('box_height'); ?>" name="<?php echo $this->get_field_name('box_height'); ?>" type="number" value="<?php echo esc_attr($instance['box_height']); ?>" />
			</p>
			
		<?php
		}
	}
}