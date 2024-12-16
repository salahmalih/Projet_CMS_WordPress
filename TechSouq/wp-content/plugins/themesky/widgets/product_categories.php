<?php
add_action('widgets_init', 'ts_product_categories_load_widgets');

function ts_product_categories_load_widgets()
{
	register_widget('TS_Product_Categories_Widget');
}

if(!class_exists('TS_Product_Categories_Widget')){
	class TS_Product_Categories_Widget extends WP_Widget {

		function __construct() {
			$widgetOps = array('classname' => 'ts-product-categories-widget', 'description' => esc_html__('Display Your Product Categories', 'themesky'));
			parent::__construct('ts_product_categories', esc_html__('TS - Product Categories', 'themesky'), $widgetOps);
		}

		function widget( $args, $instance ) {

			if( !class_exists('WooCommerce') ){
				return;
			}
			
			extract($args);
			
			$defaults = $this->get_default_values();
			
			$instance = wp_parse_args( $instance, $defaults );
			
			extract( $instance );
			
			$title 				= apply_filters( 'widget_title', $title );		
			
			$include_cat 		= is_array($include_cat) ? $include_cat : array();	
			
			$current_cat = (isset($_GET['product_cat']) && $_GET['product_cat']!='')?$_GET['product_cat']:get_query_var('product_cat', '');

			echo $before_widget;

			echo $before_title . $title . $after_title;

			?>
			<div class="ts-product-categories-widget-wrapper">
				<?php 
					$args = array(
						'taxonomy'     	=> 'product_cat'
						,'orderby'      => $orderby
						,'order'        => $order
						,'parent'       => 0
						,'title_li'     => ''
						,'hide_empty'   => $hide_empty
						,'include'		=> $include_cat
					);
					
					$all_categories = get_categories( $args );
					
					if( $all_categories ){
						if( $orderby == 'rand' ){
							shuffle($all_categories);
						}
						echo '<ul class="product-categories">';
						foreach( $all_categories as $cat ){
							$current_class = ( $current_cat == $cat->slug ) ? 'current' : ''; 
							echo '<li class="cat-item '.$current_class.'">';
							$category_id = $cat->term_id;
							echo '<span class="icon-toggle"></span>';
							echo '<a href="'. get_term_link($cat->slug, 'product_cat') .'">';
							echo $cat->name;
							if( $show_post_count ){
								echo '<span class="count">('. $cat->count .')</span>';
							}
							echo '</a>';
							
							if( $show_sub_cat ){
								$this->get_sub_categories($category_id, $instance, $current_cat);
							}
							echo '</li>';
						}
						echo '</ul>';
					}

				?>
				<div class="clear"></div>
			</div>

			<?php
			echo $after_widget;
		}
		

		function update( $new_instance, $old_instance ) {
			$instance = $old_instance;	
			$instance['title'] 				= strip_tags($new_instance['title']);
			$instance['show_post_count'] 	= empty($new_instance['show_post_count']) ? 0 : 1;
			$instance['show_sub_cat'] 		= empty($new_instance['show_sub_cat']) ? 0 : 1;
			$instance['hide_empty'] 		= empty($new_instance['hide_empty']) ? 0 : 1;
			$instance['orderby'] 			= $new_instance['orderby'];
			$instance['order'] 				= $new_instance['order'];	
			$instance['include_cat'] 		= isset($new_instance['include_cat']) ? $new_instance['include_cat'] : array();
			
			return $instance;
		}
		
		function get_default_values(){
			return array( 
						'title' 				=> 'Categories'
						,'show_post_count'		=> 1
						,'show_sub_cat'			=> 1
						,'hide_empty'			=> 0
						,'orderby'				=> 'name'
						,'order'				=> 'asc'
						,'include_cat'			=> array()
					);
		}

		function form( $instance ) { 
			$defaults = $this->get_default_values();
			
			$instance = wp_parse_args( (array) $instance, $defaults );

			$categories = $this->get_list_categories(0);
			if( !is_array($instance['include_cat']) ){
				$instance['include_cat'] = array();
			}
			?>
			<p>
				<label for="<?php echo $this->get_field_id('title'); ?>"><?php esc_html_e('Enter your title','themesky'); ?> </label>
				<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($instance['title']); ?>" />
			</p>
			<p>
				<input type="checkbox" value="1" id="<?php echo $this->get_field_id('show_post_count'); ?>" name="<?php echo $this->get_field_name('show_post_count'); ?>" <?php checked($instance['show_post_count'], 1); ?> />
				<label for="<?php echo $this->get_field_id('show_post_count'); ?>"><?php esc_html_e('Show post count', 'themesky'); ?></label>
			</p>
			<p>
				<input type="checkbox" value="1" id="<?php echo $this->get_field_id('show_sub_cat'); ?>" name="<?php echo $this->get_field_name('show_sub_cat'); ?>" <?php checked($instance['show_sub_cat'], 1); ?> />
				<label for="<?php echo $this->get_field_id('show_sub_cat'); ?>"><?php esc_html_e('Show sub categories', 'themesky'); ?></label>
			</p>
			<p>
				<input type="checkbox" value="1" id="<?php echo $this->get_field_id('hide_empty'); ?>" name="<?php echo $this->get_field_name('hide_empty'); ?>" <?php checked($instance['hide_empty'], 1); ?> />
				<label for="<?php echo $this->get_field_id('hide_empty'); ?>"><?php esc_html_e('Hide empty categories', 'themesky'); ?></label>
			</p>
			<p>
				<label><?php esc_html_e('Select categories', 'themesky'); ?></label>
				<div class="categorydiv">
					<div class="tabs-panel">
						<ul class="categorychecklist">
							<?php foreach($categories as $cat){ ?>
							<li>
								<label>
									<input type="checkbox" name="<?php echo $this->get_field_name('include_cat'); ?>[<?php $cat->term_id; ?>]" value="<?php echo $cat->term_id; ?>" <?php echo (in_array($cat->term_id,$instance['include_cat']))?'checked':''; ?> />
									<?php echo esc_html($cat->name); ?>
								</label>
								<?php $this->get_list_sub_categories($cat->term_id, $instance); ?>
							</li>
							<?php } ?>
						</ul>
					</div>
				</div>
				<span class="description"><?php esc_html_e('Dont select to show all', 'themesky'); ?></span>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('orderby'); ?>"><?php esc_html_e('Order by', 'themesky'); ?></label>
				<select class="widefat" id="<?php echo $this->get_field_id('orderby'); ?>" name="<?php echo $this->get_field_name('orderby'); ?>" >
					<option value="name" <?php selected($instance['orderby'], 'name'); ?> ><?php esc_html_e('Name', 'themesky'); ?></option>
					<option value="slug" <?php selected($instance['orderby'], 'slug'); ?> ><?php esc_html_e('Slug', 'themesky'); ?></option>
					<option value="count" <?php selected($instance['orderby'], 'count'); ?> ><?php esc_html_e('Number product', 'themesky'); ?></option>
					<option value="rand" <?php selected($instance['orderby'], 'rand'); ?> ><?php esc_html_e('Random', 'themesky'); ?></option>
					<option value="none" <?php selected($instance['orderby'], 'none'); ?> ><?php esc_html_e('None', 'themesky'); ?></option>
				</select>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('order'); ?>"><?php esc_html_e('Order', 'themesky'); ?></label>
				<select class="widefat" id="<?php echo $this->get_field_id('order'); ?>" name="<?php echo $this->get_field_name('order'); ?>" >
					<option value="asc" <?php selected($instance['order'], 'asc'); ?> ><?php esc_html_e('Ascending', 'themesky'); ?></option>
					<option value="desc" <?php selected($instance['order'], 'desc'); ?> ><?php esc_html_e('Descending', 'themesky'); ?></option>
				</select>
			</p>
			
			<?php 
		}
		
		function get_sub_categories( $category_id, $instance, $current_cat ){
			$args = array(
			   'taxonomy'      => 'product_cat'
			   ,'child_of'     => 0
			   ,'parent'       => $category_id
			   ,'orderby'      => $instance['orderby']
			   ,'order'        => $instance['order']
			   ,'hierarchical' => 0
			   ,'title_li'     => ''
			   ,'hide_empty'   => $instance['hide_empty']
			   ,'include'	   => $instance['include_cat']
			);
			$sub_cats = get_categories( $args );
			if( $sub_cats ) {
				if( $instance['orderby'] == 'rand' ){
					shuffle($sub_cats);
				}
				echo '<ul class="children" style="display: none">';
				foreach($sub_cats as $sub_category) {
					$current_class = ($current_cat == $sub_category->slug)?'current':'';
					echo '<li class="cat-item '.$current_class.'">';
					echo '<span class="icon-toggle"></span>';
					echo '<a href="'. get_term_link($sub_category, 'product_cat') .'">';
					echo $sub_category->name;
					if( $instance['show_post_count'] ){
						echo '<span class="count">(' . $sub_category->count . ')</span>';
					}
					echo '</a>';
					
					$this->get_sub_categories($sub_category->term_id, $instance, $current_cat);
					echo '</li>';
				}
				echo '</ul>';

			}
		}
		
		function get_list_categories( $cat_parent_id ){
			if ( !class_exists('WooCommerce') ) {
				return array();
			}
			$args = array(
					'taxonomy' 			=> 'product_cat'
					,'hierarchical'		=> 1
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
								<input type="checkbox" name="<?php echo $this->get_field_name('include_cat'); ?>[<?php esc_attr($sub_cat->term_id); ?>]" value="<?php echo esc_attr($sub_cat->term_id); ?>" <?php echo (in_array($sub_cat->term_id,$instance['include_cat']))?'checked':''; ?> />
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

