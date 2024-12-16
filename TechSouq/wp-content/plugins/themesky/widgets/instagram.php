<?php
add_action('widgets_init', 'ts_instagram_load_widgets');

function ts_instagram_load_widgets()
{
	register_widget('TS_Instagram_Widget');
}

if(!class_exists('TS_Instagram_Widget')){
	class TS_Instagram_Widget extends WP_Widget {

		function __construct(){
			$widgetOps = array('classname' => 'ts-instagram-widget', 'description' => esc_html__('Display your photos from Instagram', 'themesky'));
			parent::__construct('ts_instagram', esc_html__('TS - Instagram', 'themesky'), $widgetOps);
		}

		function widget( $args, $instance ) {
			
			extract($args);
			
			$defaults = $this->get_default_values();
							
			$instance = wp_parse_args( $instance, $defaults );
			
			extract($instance);
			
			$title = apply_filters( 'widget_title', $title );
			
			if( !$access_token ){
				return;
			}

			$instagramObj = new TS_Instagram();

			$instagramObj->set_base_access_token( $access_token );
			$instagramObj->set_number( $number );

			if( $cache_time == 0 ){
				$cache_time = 12;
			}
			if( $is_slider && $show_nav ){
				$before_widget = str_replace('widget-container', 'widget-container has-nav', $before_widget);
			}
			echo $before_widget;
			
			if( $title ){
				echo $before_title . $title . $after_title; 
			}
			
			$enable_cache = !wp_doing_ajax() && !( isset($_GET['action']) && $_GET['action'] == 'elementor' );
			
			if( $enable_cache ){
				unset($instance['title']);
			
				$cache_key = 'instagram_' . md5( implode('', $instance) );
				
				$cache = get_transient($cache_key);
			}
			else{
				$cache = false;
			}

			if( $cache !== false ){
				echo $cache;
			}
			else{
				$media_array = array();		
				
				if( $instagramObj->base_access_token ){ // always refresh if added
					$instagramObj->maybe_clean_token();
					$refresh_result = $instagramObj->maybe_refresh_token();
					if( is_wp_error($refresh_result) ){
						echo esc_html( $refresh_result->get_error_message() );
						$instagramObj->base_access_token = ''; // dont get data
					}
				}
				
				if( $instagramObj->base_access_token ){
					$media_array = $instagramObj->get_data_with_token();
					if( is_wp_error( $media_array ) ){
						echo esc_html( $media_array->get_error_message() );
					}
				}
				
				if( !is_wp_error( $media_array ) && !empty( $media_array ) ){
					ob_start();
					$classes = array();
					$classes[] = 'ts-instagram-wrapper items';
					$classes[] = 'columns-' . $column;
					
					$data_attr = array();
					if( $is_slider ){
						$data_attr[] = 'data-nav="'.esc_attr($show_nav).'"';
						$data_attr[] = 'data-autoplay="'.esc_attr($auto_play).'"';
						$data_attr[] = 'data-columns="'.absint($column).'"';
						
						$classes[] = 'ts-slider loading';
					}
					?>
					<div class="<?php echo esc_attr(implode(' ', $classes)); ?>" <?php echo implode(' ', $data_attr); ?>>
						<?php foreach( $media_array as $index => $item ){
							$item_class = '';
							if( $index % $column == 0 ){
								$item_class = 'first';
							}
							elseif( $index % $column == ($column - 1) ){
								$item_class = 'last';
							}
						?>
						<div class="item <?php echo esc_attr($item_class); ?>">
							<a href="<?php echo esc_url( $item['permalink'] ) ?>" target="<?php echo esc_attr( $target ) ?>">
								<?php if( $enable_cache ){ ?>
									<img class="ts-lazy-load" loading="lazy" src="data:image/svg+xml,%3Csvg%20xmlns='http://www.w3.org/2000/svg'%20viewBox='0%200%201%201'%3E%3C/svg%3E" data-src="<?php echo esc_url( $item['media_url'] ) ?>" alt="<?php echo esc_attr( $item['caption'] ) ?>" title="<?php echo esc_attr( $item['caption'] ) ?>" />
								<?php } else { ?>
									<img loading="lazy" src="<?php echo esc_url( $item['media_url'] ) ?>" alt="<?php echo esc_attr( $item['caption'] ) ?>" title="<?php echo esc_attr( $item['caption'] ) ?>" />
								<?php } ?>
							</a>
						</div>
						<?php } ?>
					</div>
					<?php
					$output = ob_get_clean();
					echo $output;
					
					if( $enable_cache ){
						set_transient($cache_key, $output, $cache_time * HOUR_IN_SECONDS);
					}
				}
			}
			echo $after_widget;
		}

		function update( $new_instance, $old_instance ) {
			$instance = $old_instance;	
			$instance['title'] 				=  strip_tags($new_instance['title']);
			$instance['access_token'] 		=  $new_instance['access_token'];
			$instance['number'] 			=  $new_instance['number'];
			$instance['column'] 			=  $new_instance['column'];									
			$instance['target'] 			=  $new_instance['target'];									
			$instance['cache_time'] 		=  absint($new_instance['cache_time']);
			$instance['is_slider'] 			=  empty($new_instance['is_slider']) ? 0 : 1;
			$instance['show_nav'] 			=  empty($new_instance['show_nav']) ? 0 : 1;
			$instance['auto_play'] 			=  empty($new_instance['auto_play']) ? 0 : 1;
			return $instance;
		}
		
		function get_default_values(){
			return array(
						'title'			=> 'Instagram'
						,'access_token' => ''
						,'number' 		=> 9
						,'column' 		=> 3
						,'target' 		=> '_self'
						,'cache_time'	=> 12
						,'is_slider'	=> 0
						,'show_nav' 	=> 1
						,'auto_play' 	=> 1
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
				<label for="<?php echo $this->get_field_id('access_token'); ?>"><?php esc_html_e('Access Token', 'themesky'); ?> </label>
				<input class="widefat" id="<?php echo $this->get_field_id('access_token'); ?>" name="<?php echo $this->get_field_name('access_token'); ?>" type="text" value="<?php echo esc_attr($instance['access_token']); ?>" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('number'); ?>"><?php esc_html_e('Number of photos', 'themesky'); ?> </label>
				<input class="widefat" type="number" id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" value="<?php echo esc_attr($instance['number']); ?>" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('column'); ?>"><?php esc_html_e('Column', 'themesky'); ?> </label>
				<select class="widefat" id="<?php echo $this->get_field_id('column'); ?>" name="<?php echo $this->get_field_name('column'); ?>" >
					<?php for( $i = 2; $i <= 9; $i++ ):?>
					<option value="<?php echo $i; ?>" <?php selected($instance['column'], $i); ?> ><?php echo $i; ?></option>
					<?php endfor; ?>
				</select>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('target'); ?>"><?php esc_html_e('Target', 'themesky'); ?> </label>
				<select class="widefat" id="<?php echo $this->get_field_id('target'); ?>" name="<?php echo $this->get_field_name('target'); ?>" >
					<option value="_self" <?php selected($instance['target'], '_self'); ?> ><?php esc_html_e('Self', 'themesky') ?></option>
					<option value="_blank" <?php selected($instance['target'], '_blank'); ?> ><?php esc_html_e('New window tab', 'themesky') ?></option>
				</select>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('cache_time'); ?>"><?php esc_html_e('Cache time (hours)', 'themesky'); ?> </label>
				<input class="widefat" type="number" min="1" id="<?php echo $this->get_field_id('cache_time'); ?>" name="<?php echo $this->get_field_name('cache_time'); ?>" value="<?php echo esc_attr($instance['cache_time']); ?>" />
			</p>
			
			<hr/>
			
			<p>
				<input type="checkbox" id="<?php echo $this->get_field_id('is_slider'); ?>" name="<?php echo $this->get_field_name('is_slider'); ?>" value="1" <?php echo ($instance['is_slider'])?'checked':''; ?> />
				<label for="<?php echo $this->get_field_id('is_slider'); ?>"><?php esc_html_e('Show in a carousel slider', 'themesky'); ?></label>
			</p>
			
			<p>
				<input type="checkbox" id="<?php echo $this->get_field_id('show_nav'); ?>" name="<?php echo $this->get_field_name('show_nav'); ?>" value="1" <?php echo ($instance['show_nav'])?'checked':''; ?> />
				<label for="<?php echo $this->get_field_id('show_nav'); ?>"><?php esc_html_e('Show navigation button', 'themesky'); ?></label>
			</p>
			
			<p>
				<input type="checkbox" id="<?php echo $this->get_field_id('auto_play'); ?>" name="<?php echo $this->get_field_name('auto_play'); ?>" value="1" <?php echo ($instance['auto_play'])?'checked':''; ?> />
				<label for="<?php echo $this->get_field_id('auto_play'); ?>"><?php esc_html_e('Auto play', 'themesky'); ?></label>
			</p>
			
			<?php 
		}
	}
}

