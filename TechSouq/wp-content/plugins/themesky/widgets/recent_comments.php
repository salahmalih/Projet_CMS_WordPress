<?php
add_action('widgets_init', 'ts_recent_comments_load_widgets');

function ts_recent_comments_load_widgets()
{
	register_widget('TS_Recent_Comments_Widget');
}

if( !class_exists('TS_Recent_Comments_Widget') ){
	class TS_Recent_Comments_Widget extends WP_Widget {

		function __construct() {
			$widgetOps = array('classname' => 'ts-recent-comments-widget', 'description' => esc_html__('Display recent comments on site', 'themesky'));
			parent::__construct('ts_recent_comments', esc_html__('TS - Recent Comments', 'themesky'), $widgetOps);
		}

		function widget( $args, $instance ) {
			
			extract($args);
			
			$defaults = $this->get_default_values();
		
			$instance = wp_parse_args( $instance, $defaults );
			
			extract($instance);
			
			$title 				= apply_filters( 'widget_title', $title );
			
			$args = array( 
				'number' 		=> $limit
				,'status' 		=> 'approve'
				,'post_status'	=> 'publish'
				,'comment_type' => ''
			);
			if( $post_type != 'all' ){
				$args['post_type'] = $post_type;
			}
			$comments = get_comments( $args );
			
			if( $comments ):
				$count = 0;
				$num_posts = count($comments);
				if( $num_posts <= $row ){
					$is_slider = false;
				}
				if( !$is_slider ){
					$row = $num_posts;
				}
				
				if( $is_slider && $show_nav ){
					$before_widget = str_replace('widget-container', 'widget-container has-nav', $before_widget);
				}
				echo $before_widget;
				if( $title ){
					echo $before_title . $title . $after_title;
				}
				
				$extra_class = '';
				$extra_class .= ($is_slider)?'ts-slider loading':'';
				?>
				<div class="ts-recent-comments-widget-wrapper <?php echo esc_attr($extra_class); ?>" data-nav="<?php echo esc_attr($show_nav) ?>" data-autoplay="<?php echo esc_attr($auto_play) ?>">
					<?php foreach( (array) $comments as $comment ): $GLOBALS['comment'] = $comment; ?>
						<?php if( $count % $row == 0 ): ?>
						<div class="per-slide">
							<ul class="comment_list_widget">
						<?php endif; ?>
							<li>
								
								<?php if( $show_comment && function_exists('ecomall_string_limit_words') ): ?>
								<blockquote class="comment-body"><?php echo ecomall_string_limit_words(wp_strip_all_tags(get_comment_text()), 15); ?></blockquote>
								<?php endif; ?>
								
								<?php if( $show_author || $show_date ): ?>
								<div class="comment-meta">
								
									<?php if( $show_author ): ?>
									<span class="author">
										<span><?php esc_html_e('BY', 'themesky'); ?></span>
										<a href="<?php echo (get_comment_author_url() == '')?'javascript: void(0)':get_comment_author_url(); ?>" rel="external nofollow"><?php echo esc_html($comment->comment_author); ?></a>
									</span>
									<?php endif; ?>
									
									<?php if( $show_date ): ?>
									<span class="date-time">
										<?php comment_date(); ?>
									</span>
									<?php endif; ?>
									
								</div>
								<?php endif; ?>
							</li>
						<?php if( $count % $row == $row - 1 || $count == $num_posts - 1 ): ?>	
							</ul>
						</div>
						<?php endif; ?>
					<?php $count++; endforeach; ?>
				</div>
				<?php
				echo $after_widget;
			endif;
		}

		function update( $new_instance, $old_instance ) {
			$instance = $old_instance;		
			$instance['title'] 				= strip_tags($new_instance['title']);		
			$instance['limit'] 				= absint($new_instance['limit']);		
			$instance['post_type'] 			= $new_instance['post_type'];		
			$instance['show_date'] 			= empty($new_instance['show_date']) ? 0 : 1;
			$instance['show_author'] 		= empty($new_instance['show_author']) ? 0 : 1;		
			$instance['show_comment'] 		= empty($new_instance['show_comment']) ? 0 : 1;		
			$instance['is_slider'] 			= empty($new_instance['is_slider']) ? 0 : 1;	
			$instance['row'] 				= absint($new_instance['row']);			
			$instance['show_nav'] 			= empty($new_instance['show_nav']) ? 0 : 1;		
			$instance['auto_play'] 			= empty($new_instance['auto_play']) ? 0 : 1;	
			
			if( $instance['row'] > $instance['limit'] ){
				$instance['row'] = $instance['limit'];
			}
			return $instance;
		}
		
		function get_default_values(){
			return array(
						'title' 				=> 'Recent Comments'
						,'limit'				=> 4
						,'post_type'			=> 'all'
						,'show_date' 			=> 1
						,'show_author' 			=> 1
						,'show_comment'			=> 1
						,'is_slider' 			=> 1
						,'row'					=> 2
						,'show_nav' 			=> 1
						,'auto_play' 			=> 1
					);
		}

		function form( $instance ) {
			
			$defaults = $this->get_default_values();
		
			$instance = wp_parse_args( (array) $instance, $defaults );
			
			$post_types = get_post_types();
			foreach( $post_types as $key => $post_type ){
				if( !post_type_supports($key, 'comments') ){
					unset( $post_types[$key] );
				}
			}
			$post_types = array_merge(array('all'=>esc_html__('All Posts', 'themesky')), $post_types);
			
		?>
			<p>
				<label for="<?php echo $this->get_field_id('title'); ?>"><?php esc_html_e('Enter your title', 'themesky'); ?> </label>
				<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($instance['title']); ?>" />
			</p>
			
			<p>
				<label for="<?php echo $this->get_field_id('post_type'); ?>"><?php echo esc_html_e('Post type', 'themesky'); ?> </label>
				<select class="widefat" name="<?php echo $this->get_field_name('post_type'); ?>" id="<?php echo $this->get_field_id('post_type'); ?>">
					<?php foreach( $post_types as $key => $post_type ){ ?>
						<option value="<?php echo esc_attr($key); ?>" <?php selected($instance['post_type'], $key); ?>><?php echo esc_attr($post_type); ?></option>
					<?php } ?>
				</select>
			</p>
			
			<p>
				<label for="<?php echo $this->get_field_id('limit'); ?>"><?php esc_html_e('Number of comments to show', 'themesky'); ?> </label>
				<input class="widefat" id="<?php echo $this->get_field_id('limit'); ?>" name="<?php echo $this->get_field_name('limit'); ?>" type="number" min="0" value="<?php echo esc_attr($instance['limit']); ?>" />
			</p>
			
			<p>
				<input type="checkbox" id="<?php echo $this->get_field_id('show_date'); ?>" name="<?php echo $this->get_field_name('show_date'); ?>" value="1" <?php echo ($instance['show_date'])?'checked':''; ?> />
				<label for="<?php echo $this->get_field_id('show_date'); ?>"><?php esc_html_e('Show comment date', 'themesky'); ?></label>
			</p>
			
			<p>
				<input type="checkbox" id="<?php echo $this->get_field_id('show_author'); ?>" name="<?php echo $this->get_field_name('show_author'); ?>" value="1" <?php echo ($instance['show_author'])?'checked':''; ?> />
				<label for="<?php echo $this->get_field_id('show_author'); ?>"><?php esc_html_e('Show comment author', 'themesky'); ?></label>
			</p>
			
			<p>
				<input type="checkbox" id="<?php echo $this->get_field_id('show_comment'); ?>" name="<?php echo $this->get_field_name('show_comment'); ?>" value="1" <?php echo ($instance['show_comment'])?'checked':''; ?> />
				<label for="<?php echo $this->get_field_id('show_comment'); ?>"><?php esc_html_e('Show comment content', 'themesky'); ?></label>
			</p>
			
			<hr/>
			
			<p>
				<input type="checkbox" id="<?php echo $this->get_field_id('is_slider'); ?>" name="<?php echo $this->get_field_name('is_slider'); ?>" value="1" <?php echo ($instance['is_slider'])?'checked':''; ?> />
				<label for="<?php echo $this->get_field_id('is_slider'); ?>"><?php esc_html_e('Show in a carousel slider', 'themesky'); ?></label>
			</p>
			
			<p>
				<label for="<?php echo $this->get_field_id('row'); ?>"><?php esc_html_e('Number of rows - in carousel slider', 'themesky'); ?> </label>
				<input class="widefat" id="<?php echo $this->get_field_id('row'); ?>" name="<?php echo $this->get_field_name('row'); ?>" type="number" min="0" value="<?php echo esc_attr($instance['row']); ?>" />
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

