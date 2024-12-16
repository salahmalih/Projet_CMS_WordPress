<?php
add_action('widgets_init', 'ts_blogs_load_widgets');

function ts_blogs_load_widgets()
{
	register_widget('TS_Blogs_Widget');
}

if( !class_exists('TS_Blogs_Widget') ){
	class TS_Blogs_Widget extends WP_Widget {

		function __construct() {
			$widgetOps = array('classname' => 'ts-blogs-widget', 'description' => esc_html__('Display blogs on site', 'themesky'));
			parent::__construct('ts_blogs', esc_html__('TS - Blogs', 'themesky'), $widgetOps);
		}

		function widget( $args, $instance ) {
			
			extract($args);
			
			$defaults = $this->get_default_values();
			
			$instance = wp_parse_args( $instance, $defaults );
			
			extract( $instance );
			
			$title 				= apply_filters('widget_title', $title);
			
			$args = array(
					'post_type'				=> 'post'
					,'ignore_sticky_posts'	=> 1
					,'post_status'			=> 'publish'
					,'posts_per_page'		=> $limit
					,'order'				=> $order
					,'orderby'				=> $orderby
			);
			
			if( is_array($categories) && count($categories) > 0 ){
				$args['category__in'] = $categories;
			}
			
			global $post;
			$posts = new WP_Query($args);
			if( $posts->have_posts() ):
				$count = 0;
				$num_posts = $posts->post_count;
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
				
				$classes = array('ts-blogs-widget-wrapper');
				$classes[] = ($is_slider)?'ts-slider loading':'';
				$classes[] = ($show_thumbnail)?'has-image':'no-image';
				
				$blog_thumb_size = 'thumbnail';
				$classes = array_filter( $classes );
				?>
				<div class="<?php echo esc_attr(implode(' ', $classes)); ?>" data-nav="<?php echo esc_attr($show_nav) ?>" data-autoplay="<?php echo esc_attr($auto_play) ?>">
					<?php while( $posts->have_posts() ): 
						$posts->the_post(); 
						$post_format = get_post_format(); /* Video, Audio, Gallery, Quote */
						if( $is_slider && $post_format == 'gallery' ){ /* Remove Slider in Slider */
							$post_format = false;
						}
					?>
						<?php if( $count % $row == 0 ): ?>
						<div class="per-slide">
							<ul class="post_list_widget">
						<?php endif; ?>
							<li class="<?php echo $post_format ?>">
								<?php if( $show_thumbnail ): ?>
									<?php if( $post_format == 'gallery' || $post_format === false || $post_format == 'standard' ){ ?>
										<a class="thumbnail <?php echo esc_attr($post_format); echo ($post_format == 'gallery')?' loading':''; ?>" href="<?php the_permalink(); ?>">
											<figure>
												<?php 
												if( $post_format == 'gallery' ){
													$gallery = get_post_meta($post->ID, 'ts_gallery', true);
													$gallery_ids = explode(',', $gallery);
													if( is_array($gallery_ids) && has_post_thumbnail() ){
														array_unshift($gallery_ids, get_post_thumbnail_id());
													}
													foreach( $gallery_ids as $gallery_id ){
														echo wp_get_attachment_image( $gallery_id, $blog_thumb_size );
													}
												}
												
												if( $post_format === false || $post_format == 'standard' ){
													if( has_post_thumbnail() ){
														the_post_thumbnail($blog_thumb_size); 
													}
												}
												?>
											</figure>
										</a>
									<?php 
										}
									
										if( $post_format == 'video' ){
											$video_url = get_post_meta($post->ID, 'ts_video_url', true);
											echo do_shortcode('[ts_video src="'.$video_url.'"]');
										}
										
										if( $post_format == 'audio' ){
											$audio_url = get_post_meta($post->ID, 'ts_audio_url', true);
											if( strlen($audio_url) > 4 ){
												$file_format = substr($audio_url, -3, 3);
												if( in_array($file_format, array('mp3', 'ogg', 'wav')) ){
													echo do_shortcode('[audio '.$file_format.'="'.$audio_url.'"]');
												}
												else{
													echo do_shortcode('[ts_soundcloud url="'.$audio_url.'" width="100%" height="122"]');
												}
											}
										}
									?>
								<?php endif; /* End thumbnail */ ?>
								
								<?php if( $post_format != 'quote' ): ?>
								
								<div class="entry-content">	
								
									<?php if( $show_author || $show_date ): ?>
									<div class="entry-meta-top <?php echo $show_author?'show-author':''; ?>">
										<?php if( $show_author ): ?>
										<span class="vcard author">
											<span><?php esc_html_e('BY', 'themesky'); ?></span>
											<?php the_author_posts_link(); ?>
										</span>
										<?php endif; ?>
										
										<?php if( $show_date ): ?>
										<span class="date-time">
											<?php the_time( get_option('date_format') ); ?>
										</span>
										<?php endif; ?>
									</div>
									<?php endif; ?>
									
									<header>									
										<?php if( $show_title ): ?>
										<h6 class="heading-title entry-title"><a href="<?php the_permalink() ?>" class="post-title"><?php the_title(); ?></a></h6>
										<?php endif; ?>
									</header>
									
									<?php if( $show_excerpt && function_exists('ecomall_the_excerpt_max_words') ): ?>
										<div class="excerpt">
											<?php ecomall_the_excerpt_max_words($excerpt_words, $post); ?>
										</div>
									<?php endif; ?>
									
								</div>
									
								<?php else: /* Post format is quote */ ?>
								
									<blockquote>
										<?php 
										$quote_content = get_the_excerpt();
										if( !$quote_content ){
											$quote_content = get_the_content();
										}
										echo do_shortcode($quote_content);
										?>
										
										<?php if( $show_author || $show_date ): ?>
										<div class="entry-meta-bottom">
											<?php if( $show_author ): ?>
											<span class="vcard author">
												<span><?php esc_html_e('BY', 'themesky'); ?></span>
												<?php the_author_posts_link(); ?>
											</span>
											<?php endif; ?>
											
											<?php if( $show_date ): ?>
											<span class="date-time">
												<?php the_time( get_option('date_format') ); ?>
											</span>
											<?php endif; ?>
										</div>
										<?php endif; ?>
										
									</blockquote>
									
								<?php endif; /* End quote */ ?>
								
							</li>
						<?php if( $count % $row == $row - 1 || $count == $num_posts - 1 ): ?>	
							</ul>
						</div>
						<?php endif; ?>
					<?php $count++; endwhile; ?>
				</div>
				
				<?php
				echo $after_widget;
			endif;
			
			wp_reset_postdata();
		}

		function update( $new_instance, $old_instance ) {
			$instance = $old_instance;		
			$instance['title'] 				= strip_tags($new_instance['title']);		
			$instance['limit'] 				= absint($new_instance['limit']);
			$instance['order'] 				= $new_instance['order'];
			$instance['orderby'] 			= $new_instance['orderby'];
			$instance['categories'] 		= isset($new_instance['categories']) ? $new_instance['categories'] : array();
			$instance['show_thumbnail'] 	= empty($new_instance['show_thumbnail']) ? 0 : 1;	
			$instance['show_title'] 		= empty($new_instance['show_title']) ? 0 : 1;		
			$instance['show_date'] 			= empty($new_instance['show_date']) ? 0 : 1;		
			$instance['show_author'] 		= empty($new_instance['show_author']) ? 0 : 1;		
			$instance['show_excerpt'] 		= empty($new_instance['show_excerpt']) ? 0 : 1;		
			$instance['excerpt_words'] 		= absint($new_instance['excerpt_words']);		
			$instance['is_slider'] 			= empty($new_instance['is_slider']) ? 0: 1;	
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
				'title' 				=> 'Recent Posts'
				,'limit'				=> 4
				,'order'				=> 'desc'
				,'orderby'				=> 'date'
				,'categories'			=> array()
				,'show_thumbnail' 		=> 1
				,'show_title' 			=> 1
				,'show_date' 			=> 1
				,'show_author' 			=> 1
				,'show_excerpt'			=> 0
				,'excerpt_words'		=> 8
				,'is_slider' 			=> 1
				,'row'					=> 2
				,'show_nav' 			=> 1
				,'auto_play' 			=> 1
			);
		}
		
		function form( $instance ) {
			
			$defaults = $this->get_default_values();
		
			$instance = wp_parse_args( (array) $instance, $defaults );	
			
			$categories = $this->get_list_categories(0);
			if( !is_array($instance['categories']) ){
				$instance['categories'] = array();
			}
			
		?>
			<p>
				<label for="<?php echo $this->get_field_id('title'); ?>"><?php esc_html_e('Enter your title', 'themesky'); ?> </label>
				<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($instance['title']); ?>" />
			</p>
			
			<p>
				<label for="<?php echo $this->get_field_id('limit'); ?>"><?php esc_html_e('Number of posts to show', 'themesky'); ?> </label>
				<input class="widefat" id="<?php echo $this->get_field_id('limit'); ?>" name="<?php echo $this->get_field_name('limit'); ?>" type="number" min="0" value="<?php echo esc_attr($instance['limit']); ?>" />
			</p>
			
			<p>
				<label for="<?php echo $this->get_field_id('orderby'); ?>"><?php esc_html_e('Order by', 'themesky'); ?> </label>
				<select class="widefat" id="<?php echo $this->get_field_id('orderby'); ?>" name="<?php echo $this->get_field_name('orderby'); ?>">
					<option value="none" <?php selected('none', $instance['orderby']) ?>><?php esc_html_e('None', 'themesky') ?></option>
					<option value="ID" <?php selected('ID', $instance['orderby']) ?>><?php esc_html_e('ID', 'themesky') ?></option>
					<option value="title" <?php selected('title', $instance['orderby']) ?>><?php esc_html_e('Title', 'themesky') ?></option>
					<option value="date" <?php selected('date', $instance['orderby']) ?>><?php esc_html_e('Date', 'themesky') ?></option>
					<option value="comment_count" <?php selected('comment_count', $instance['orderby']) ?>><?php esc_html_e('Comment count', 'themesky') ?></option>
					<option value="rand" <?php selected('rand', $instance['orderby']) ?>><?php esc_html_e('Random', 'themesky') ?></option>
				</select>
			</p>
			
			<p>
				<label for="<?php echo $this->get_field_id('order'); ?>"><?php esc_html_e('Order', 'themesky'); ?> </label>
				<select class="widefat" id="<?php echo $this->get_field_id('order'); ?>" name="<?php echo $this->get_field_name('order'); ?>">
					<option value="asc" <?php selected('asc', $instance['order']) ?>><?php esc_html_e('Ascending', 'themesky') ?></option>
					<option value="desc" <?php selected('desc', $instance['order']) ?>><?php esc_html_e('Descending', 'themesky') ?></option>
				</select>
			</p>
			
			<p>
				<label><?php esc_html_e('Select categories', 'themesky'); ?></label>
				<div class="categorydiv">
					<div class="tabs-panel">
						<ul class="categorychecklist">
							<?php foreach($categories as $cat){ ?>
							<li>
								<label>
									<input type="checkbox" name="<?php echo $this->get_field_name('categories'); ?>[<?php esc_attr($cat->term_id); ?>]" value="<?php echo esc_attr($cat->term_id); ?>" <?php echo (in_array($cat->term_id,$instance['categories']))?'checked':''; ?> />
									<?php echo esc_html($cat->name); ?>
								</label>
								<?php $this->get_list_sub_categories($cat->term_id, $instance); ?>
							</li>
							<?php } ?>
						</ul>
					</div>
				</div>
			</p>
			
			<p>
				<input type="checkbox" id="<?php echo $this->get_field_id('show_thumbnail'); ?>" name="<?php echo $this->get_field_name('show_thumbnail'); ?>" value="1" <?php echo ($instance['show_thumbnail'])?'checked':''; ?> />
				<label for="<?php echo $this->get_field_id('show_thumbnail'); ?>"><?php esc_html_e('Show post thumbnail', 'themesky'); ?></label>
			</p>
			
			<p>
				<input type="checkbox" id="<?php echo $this->get_field_id('show_title'); ?>" name="<?php echo $this->get_field_name('show_title'); ?>" value="1" <?php echo ($instance['show_title'])?'checked':''; ?> />
				<label for="<?php echo $this->get_field_id('show_title'); ?>"><?php esc_html_e('Show post title', 'themesky'); ?></label>
			</p>
			
			<p>
				<input type="checkbox" id="<?php echo $this->get_field_id('show_date'); ?>" name="<?php echo $this->get_field_name('show_date'); ?>" value="1" <?php echo ($instance['show_date'])?'checked':''; ?> />
				<label for="<?php echo $this->get_field_id('show_date'); ?>"><?php esc_html_e('Show post date', 'themesky'); ?></label>
			</p>
			
			<p>
				<input type="checkbox" id="<?php echo $this->get_field_id('show_author'); ?>" name="<?php echo $this->get_field_name('show_author'); ?>" value="1" <?php echo ($instance['show_author'])?'checked':''; ?> />
				<label for="<?php echo $this->get_field_id('show_author'); ?>"><?php esc_html_e('Show post author', 'themesky'); ?></label>
			</p>
			
			<p>
				<input type="checkbox" id="<?php echo $this->get_field_id('show_excerpt'); ?>" name="<?php echo $this->get_field_name('show_excerpt'); ?>" value="1" <?php echo ($instance['show_excerpt'])?'checked':''; ?> />
				<label for="<?php echo $this->get_field_id('show_excerpt'); ?>"><?php esc_html_e('Show post excerpt', 'themesky'); ?></label>
			</p>
			
			<p>
				<label for="<?php echo $this->get_field_id('excerpt_words'); ?>"><?php esc_html_e('Number of words in excerpt', 'themesky'); ?> </label>
				<input class="widefat" id="<?php echo $this->get_field_id('excerpt_words'); ?>" name="<?php echo $this->get_field_name('excerpt_words'); ?>" type="number" min="0" value="<?php echo esc_attr($instance['excerpt_words']); ?>" />
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
		
		function get_list_categories( $cat_parent_id ){
			$args = array(
					'hierarchical'		=> 1
					,'parent'			=> $cat_parent_id
					,'title_li'			=> ''
					,'child_of'			=> 0
				);
			$cats = get_categories($args);
			return $cats;
		}
		
		function get_list_sub_categories( $cat_parent_id, $instance ){
			$sub_categories = $this->get_list_categories($cat_parent_id); 
			if( count($sub_categories) > 0){
			?>
				<ul class="children">
					<?php foreach( $sub_categories as $sub_cat ){ ?>
						<li>
							<label>
								<input type="checkbox" name="<?php echo $this->get_field_name('categories'); ?>[<?php esc_attr($sub_cat->term_id); ?>]" value="<?php echo esc_attr($sub_cat->term_id); ?>" <?php echo (in_array($sub_cat->term_id,$instance['categories']))?'checked':''; ?> />
								<?php echo esc_html($sub_cat->name); ?>
							</label>
							<?php $this->get_list_sub_categories($sub_cat->term_id, $instance); ?>
						</li>
					<?php } ?>
				</ul>
			<?php }
		}
		
	}
}

