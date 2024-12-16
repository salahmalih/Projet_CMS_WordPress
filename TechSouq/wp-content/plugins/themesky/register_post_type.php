<?php
/*** TS Team ***/
if( !class_exists('TS_Team_Members') ){
	class TS_Team_Members{
	
		public $post_type;
		public $thumb_size_name;
		public $thumb_size_array;
		
		function __construct(){
			$this->post_type = 'ts_team';
			$this->thumb_size_name = 'ts_team_thumb';
			$this->thumb_size_array = array(330, 360);
			$this->add_image_size();
			
			add_action('init', array($this, 'register_post_type'));
			
			if( is_admin() ){
				add_filter('enter_title_here', array($this, 'enter_title_here'));
				add_filter('manage_'.$this->post_type.'_posts_columns', array($this, 'custom_column_headers'), 10);
				add_action('manage_'.$this->post_type.'_posts_custom_column', array($this, 'custom_columns'), 10, 2);
			}
		}
		
		function add_image_size(){
			global $_wp_additional_image_sizes;
			if( !isset($_wp_additional_image_sizes[$this->thumb_size_name]) ){
				add_image_size($this->thumb_size_name, $this->thumb_size_array[0], $this->thumb_size_array[1], true);
			}
		}
		
		function register_post_type(){
			register_post_type($this->post_type, array(
				'labels' => array(
							'name' 					=> esc_html_x('Team Members', 'post type general name','themesky'),
							'singular_name' 		=> esc_html_x('Team Members', 'post type singular name','themesky'),
							'all_items' 			=> esc_html__('All Team Members', 'themesky'),
							'add_new' 				=> esc_html_x('Add Member', 'Team','themesky'),
							'add_new_item' 			=> esc_html__('Add Member','themesky'),
							'edit_item' 			=> esc_html__('Edit Member','themesky'),
							'new_item' 				=> esc_html__('New Member','themesky'),
							'view_item' 			=> esc_html__('View Member','themesky'),
							'search_items' 			=> esc_html__('Search Member','themesky'),
							'not_found' 			=> esc_html__('No Member found','themesky'),
							'not_found_in_trash' 	=> esc_html__('No Member found in Trash','themesky'),
							'parent_item_colon' 	=> '',
							'menu_name' 			=> esc_html__('Team Members','themesky'),
				)
				,'singular_label' 		=> esc_html__('Team','themesky')
				,'public' 				=> false
				,'publicly_queryable' 	=> true
				,'exclude_from_search' 	=> true
				,'show_ui' 				=> true
				,'show_in_menu' 		=> true
				,'capability_type' 		=> 'page'
				,'hierarchical' 		=> false
				,'supports'  			=> array('title', 'custom-fields', 'editor', 'thumbnail')
				,'has_archive' 			=> false
				,'rewrite' 				=> array('slug' => str_replace('ts_', '', $this->post_type), 'with_front' => true)
				,'query_var' 			=> false
				,'can_export' 			=> true
				,'show_in_nav_menus' 	=> false
				,'menu_position' 		=> 5
				,'menu_icon' 			=> ''
			));	
		}
		
		function enter_title_here( $title ) {
			if( get_post_type() == $this->post_type ) {
				$title = esc_html__('Enter the member name here', 'themesky');
			}
			return $title;
		}
		
		function get_team_members( $args = array() ){
			$defaults = array(
				'limit' => 5
				,'orderby' => 'menu_order'
				,'order' => 'DESC'
				,'id' => 0
				,'size' => $this->thumb_size_name
			);

			$args = wp_parse_args($args, $defaults);

			$query_args = array();
			$query_args['post_type'] = $this->post_type;
			$query_args['posts_per_page'] = $args['limit'];
			$query_args['orderby'] = $args['orderby'];
			$query_args['order'] = $args['order'];

			if ( is_numeric($args['id']) && (intval($args['id']) > 0) ) {
				$query_args['p'] = intval( $args['id'] );
			}

			/* Whitelist checks */
			if ( !in_array($query_args['orderby'], array( 'none', 'ID', 'author', 'title', 'date', 'modified', 'parent', 'rand', 'comment_count', 'menu_order', 'meta_value', 'meta_value_num' )) ) {
				$query_args['orderby'] = 'date';
			}

			if ( !in_array( $query_args['order'], array( 'ASC', 'DESC' ) ) ) {
				$query_args['order'] = 'DESC';
			}

			if ( !in_array( $query_args['post_type'], get_post_types() ) ) {
				$query_args['post_type'] = $this->post_type;
			}

			/* The Query */
			$query = get_posts( $query_args );

			/* The Display */
			if ( !is_wp_error( $query ) && is_array( $query ) && count( $query ) > 0 ) {
				foreach ( $query as $k => $v ) {
					$meta = get_post_custom( $v->ID );

					/* Get the image */
					$query[$k]->image = $this->get_image( $v->ID, $args['size'] );

					/* Get custom meta data */
				}
			} else {
				$query = false;
			}

			return $query;
		}
		
		function get_image( $id, $size = '' ){
			$response = '';

			if ( has_post_thumbnail( $id ) ) {
				if ( ( is_int( $size ) || ( 0 < intval( $size ) ) ) && ! is_array( $size ) ) {
					$size = array( intval( $size ), intval( $size ) );
				} elseif ( ! is_string( $size ) && ! is_array( $size ) ) {
					$size = $this->thumb_size_array;
				}
				$response = get_the_post_thumbnail( intval( $id ), $size );
			}

			return $response;
		}
		
		function custom_columns( $column_name, $id ){
			global $wpdb, $post;

			$meta = get_post_custom( $id );
			switch ( $column_name ) {
				case 'image':
					$value = '';
					$value = $this->get_image( $id, 40 );
					echo $value;
				break;
				case 'role':
					if( isset($meta['_role'][0]) ){
						echo $meta['_role'][0];
					}
					else{
						echo '';
					}
				break;
				default:
				break;

			}
		}
		
		function custom_column_headers( $defaults ){
			$new_columns = array( 'image' => esc_html__( 'Image', 'themesky' ), 'role' => esc_html__( 'Role', 'themesky' ) );
			$last_item = '';
			if( isset($defaults['title']) ) { $defaults['title'] = esc_html__('Member name', 'themesky'); }
			if( isset($defaults['date']) ) { unset($defaults['date']); }
			if( count($defaults) > 2 ) {
				$last_item = array_slice($defaults, -1);
				array_pop($defaults);
			}
			
			$defaults = array_merge($defaults, $new_columns);
			if( $last_item != '' ) {
				foreach ( $last_item as $k => $v ) {
					$defaults[$k] = $v;
					break;
				}
			}

			return $defaults;
		}
		
	}
}
global $ts_team_members;
$ts_team_members = new TS_Team_Members();

/*** TS Testimonial ***/
if( !class_exists('TS_Testimonials') ){
	class TS_Testimonials{
		public $post_type;
		public $thumb_size_name;
		public $thumb_size_array;
		
		function __construct(){
			$this->post_type = 'ts_testimonial';
			$this->thumb_size_name = 'ts_testimonial_thumb';
			$this->thumb_size_array = array(180, 180);
			$this->add_image_size();
			
			add_action('init', array($this, 'register_post_type'));
			add_action('init', array($this, 'register_taxonomy'));
			
			if( is_admin() ){
				add_filter('enter_title_here', array($this, 'enter_title_here'));
				add_filter('manage_'.$this->post_type.'_posts_columns', array($this, 'custom_column_headers'), 10);
				add_action('manage_'.$this->post_type.'_posts_custom_column', array($this, 'custom_columns'), 10, 2);
			}
		}
		
		function add_image_size(){
			global $_wp_additional_image_sizes;
			if( !isset($_wp_additional_image_sizes[$this->thumb_size_name]) ){
				add_image_size($this->thumb_size_name, $this->thumb_size_array[0], $this->thumb_size_array[1], true);
			}
		}
		
		function register_post_type(){
			$labels = array(
				'name' 				=> esc_html_x( 'Testimonials', 'post type general name', 'themesky' ),
				'singular_name' 	=> esc_html_x( 'Testimonial', 'post type singular name', 'themesky' ),
				'add_new' 			=> esc_html_x( 'Add New', 'testimonial', 'themesky' ),
				'add_new_item' 		=> esc_html__( 'Add New Testimonial', 'themesky' ),
				'edit_item' 		=> esc_html__( 'Edit Testimonial', 'themesky' ),
				'new_item' 			=> esc_html__( 'New Testimonial', 'themesky' ),
				'all_items' 		=> esc_html__( 'All Testimonials', 'themesky' ),
				'view_item' 		=> esc_html__( 'View Testimonial', 'themesky' ),
				'search_items' 		=> esc_html__( 'Search Testimonials', 'themesky' ),
				'not_found' 		=> esc_html__( 'No Testimonials Found', 'themesky' ),
				'not_found_in_trash'=> esc_html__( 'No Testimonials Found In Trash', 'themesky' ),
				'parent_item_colon' => '',
				'menu_name' 		=> esc_html__( 'Testimonials', 'themesky' )
			);
			$args = array(
				'labels' 			=> $labels,
				'public' 			=> false,
				'publicly_queryable'=> true,
				'show_ui' 			=> true,
				'show_in_menu' 		=> true,
				'query_var' 		=> true,
				'rewrite' 			=> array( 'slug' => $this->post_type ),
				'capability_type' 	=> 'post',
				'has_archive' 		=> 'ts_testimonials',
				'hierarchical' 		=> false,
				'supports' 			=> array( 'title', 'editor', 'thumbnail', 'page-attributes' ),
				'menu_position' 	=> 5,
				'menu_icon' 		=> ''
			);
			register_post_type( $this->post_type, $args );
		}
		
		function register_taxonomy(){
			$args = array(
					'labels' => array(
								'name'                => esc_html_x( 'Categories', 'taxonomy general name', 'themesky' ),
								'singular_name'       => esc_html_x( 'Category', 'taxonomy singular name', 'themesky' ),
								'search_items'        => esc_html__( 'Search Categories', 'themesky' ),
								'all_items'           => esc_html__( 'All Categories', 'themesky' ),
								'parent_item'         => esc_html__( 'Parent Category', 'themesky' ),
								'parent_item_colon'   => esc_html__( 'Parent Category:', 'themesky' ),
								'edit_item'           => esc_html__( 'Edit Category', 'themesky' ),
								'update_item'         => esc_html__( 'Update Category', 'themesky' ),
								'add_new_item'        => esc_html__( 'Add New Category', 'themesky' ),
								'new_item_name'       => esc_html__( 'New Category Name', 'themesky' ),
								'menu_name'           => esc_html__( 'Categories', 'themesky' )
								)
					,'public' 				=> true
					,'hierarchical' 		=> true
					,'show_ui' 				=> true
					,'show_admin_column' 	=> true
					,'query_var' 			=> true
					,'show_in_nav_menus' 	=> false
					,'show_tagcloud' 		=> false
					);
			register_taxonomy('ts_testimonial_cat', $this->post_type, $args);
		}
		
		function enter_title_here( $title ) {
			if( get_post_type() == $this->post_type ) {
				$title = esc_html__('Enter the customer\'s name here', 'themesky');
			}
			return $title;
		}
		
		function get_image( $id, $size = '' ){
			$response = '';
			if( $size == '' ){
				$size = $this->thumb_size_array[0];
			}
			if ( has_post_thumbnail( $id ) ) {
				if ( ( is_int( $size ) || ( 0 < intval( $size ) ) ) && ! is_array( $size ) ) {
					$size = array( intval( $size ), intval( $size ) );
				} elseif ( ! is_string( $size ) && ! is_array( $size ) ) {
					$size = $this->thumb_size_array;
				}
				$response = get_the_post_thumbnail( intval( $id ), $size );
			} else {
				$gravatar_email = get_post_meta( $id, 'ts_gravatar_email', true );
				if ( '' != $gravatar_email && is_email( $gravatar_email ) ) {
					$response = get_avatar( $gravatar_email, $size );
				}
			}

			return $response;
		}
		
		function custom_columns( $column_name, $id ){
			global $wpdb, $post;

			$meta = get_post_custom( $id );
			switch ( $column_name ) {
				case 'image':
					$value = '';
					$value = $this->get_image( $id, 40 );
					echo $value;
				break;
				default:
				break;

			}
		}
		
		function custom_column_headers( $defaults ){
			$new_columns = array( 'image' => esc_html__( 'Image', 'themesky' ) );
			$last_item = '';
			
			if( isset($defaults['date']) ) { unset($defaults['date']); }
			if( count($defaults) > 2 ) {
				$last_item = array_slice($defaults, -1);
				array_pop($defaults);
			}
			
			$defaults = array_merge($defaults, $new_columns);
			if( $last_item != '' ) {
				foreach ( $last_item as $k => $v ) {
					$defaults[$k] = $v;
					break;
				}
			}

			return $defaults;
		}
	}
}
global $ts_testimonials;
$ts_testimonials = new TS_Testimonials();

/*** TS Logos ***/
if( !class_exists('TS_Logos') ){
	class TS_Logos{
		public $post_type;
		public $thumb_size_name;
		public $thumb_size_array;
		
		function __construct(){
			$this->post_type = 'ts_logo';
			$this->thumb_size_name = 'ts_logo_thumb';
			$size_options = get_option('ts_logo_setting', array());
			$logo_width = isset($size_options['size']['width'])?$size_options['size']['width']:180;
			$logo_height = isset($size_options['size']['height'])?$size_options['size']['height']:60;
			$crop = isset($size_options['size']['crop']) && !$size_options['size']['crop']?false:true;
			$this->thumb_size_array = array($logo_width, $logo_height, $crop);
			$this->add_image_size();
			
			add_action('init', array($this, 'register_post_type'));
			add_action('init', array($this, 'register_taxonomy'));
			
			if( is_admin() ){
				add_action('admin_menu', array( $this, 'register_setting_page' ));
			}
		}
		
		function add_image_size(){
			global $_wp_additional_image_sizes;
			if( !isset($_wp_additional_image_sizes[$this->thumb_size_name]) ){
				add_image_size($this->thumb_size_name, $this->thumb_size_array[0], $this->thumb_size_array[1], $this->thumb_size_array[2]);
			}
		}
		
		function register_post_type(){
			$labels = array(
				'name' 				=> esc_html_x( 'Logos', 'post type general name', 'themesky' ),
				'singular_name' 	=> esc_html_x( 'Logo', 'post type singular name', 'themesky' ),
				'add_new' 			=> esc_html_x( 'Add New', 'logo', 'themesky' ),
				'add_new_item' 		=> esc_html__( 'Add New Logo', 'themesky' ),
				'edit_item' 		=> esc_html__( 'Edit Logo', 'themesky' ),
				'new_item' 			=> esc_html__( 'New Logo', 'themesky' ),
				'all_items' 		=> esc_html__( 'All Logos', 'themesky' ),
				'view_item' 		=> esc_html__( 'View Logo', 'themesky' ),
				'search_items' 		=> esc_html__( 'Search Logos', 'themesky' ),
				'not_found' 		=> esc_html__( 'No Logos Found', 'themesky' ),
				'not_found_in_trash'=> esc_html__( 'No Logos Found In Trash', 'themesky' ),
				'parent_item_colon' => '',
				'menu_name' 		=> esc_html__( 'Logos', 'themesky' )
			);
			$args = array(
				'labels' 			=> $labels,
				'public' 			=> false,
				'publicly_queryable'=> true,
				'show_ui' 			=> true,
				'show_in_menu' 		=> true,
				'query_var' 		=> true,
				'rewrite' 			=> array( 'slug' => str_replace('ts_', '', $this->post_type) ),
				'capability_type' 	=> 'post',
				'has_archive' 		=> false,
				'hierarchical' 		=> false,
				'supports' 			=> array( 'title', 'thumbnail' ),
				'menu_position' 	=> 5,
				'menu_icon' 		=> ''
			);
			register_post_type( $this->post_type, $args );
		}
		
		function register_taxonomy(){
			$args = array(
					'labels' => array(
								'name'                => esc_html_x( 'Categories', 'taxonomy general name', 'themesky' ),
								'singular_name'       => esc_html_x( 'Category', 'taxonomy singular name', 'themesky' ),
								'search_items'        => esc_html__( 'Search Categories', 'themesky' ),
								'all_items'           => esc_html__( 'All Categories', 'themesky' ),
								'parent_item'         => esc_html__( 'Parent Category', 'themesky' ),
								'parent_item_colon'   => esc_html__( 'Parent Category:', 'themesky' ),
								'edit_item'           => esc_html__( 'Edit Category', 'themesky' ),
								'update_item'         => esc_html__( 'Update Category', 'themesky' ),
								'add_new_item'        => esc_html__( 'Add New Category', 'themesky' ),
								'new_item_name'       => esc_html__( 'New Category Name', 'themesky' ),
								'menu_name'           => esc_html__( 'Categories', 'themesky' )
								)
					,'public' 				=> true
					,'hierarchical' 		=> true
					,'show_ui' 				=> true
					,'show_admin_column' 	=> true
					,'query_var' 			=> true
					,'show_in_nav_menus' 	=> false
					,'show_tagcloud' 		=> false
					);
			register_taxonomy('ts_logo_cat', $this->post_type, $args);
		}
		
		function register_setting_page(){
			add_submenu_page('edit.php?post_type='.$this->post_type, esc_html__('Logo Settings','themesky'), 
						esc_html__('Settings','themesky'), 'manage_options', 'ts-logo-settings', array($this, 'setting_page_content'));
		}
		
		function setting_page_content(){
			$options_default = array(
							'size' => array(
								'width' => 180
								,'height' => 60
								,'crop' => 1
							)
							,'responsive' => array(
								'break_point'	=> array(0, 300, 450, 600, 715, 900)
								,'item'			=> array(1, 2, 3, 4, 5, 6)
							)
						);
						
			$options = get_option('ts_logo_setting', $options_default);
			if(isset($_POST['ts_logo_save_setting'])){
				$options['size']['width'] = $_POST['width'];
				$options['size']['height'] = $_POST['height'];
				$options['size']['crop'] = $_POST['crop'];
				$options['responsive']['break_point'] = $_POST['responsive']['break_point'];
				$options['responsive']['item'] = $_POST['responsive']['item'];
				update_option('ts_logo_setting', $options);
			}
			if( isset($_POST['ts_logo_reset_setting']) ){
				update_option('ts_logo_setting', $options_default);
				$options = $options_default;
			}
			?>
			<h2 class="ts-logo-settings-page-title"><?php esc_html_e('Logo Settings','themesky'); ?></h2>
			<div id="ts-logo-setting-page-wrapper">
				<form method="post">
					<h3><?php esc_html_e('Image Size', 'themesky'); ?></h3>
					<p class="description"><?php esc_html_e('You must regenerate thumbnails after changing','themesky'); ?></p>
					<table class="form-table">
						<tbody>
							<tr>
								<th scope="row"><label><?php esc_html_e('Image width','themesky'); ?></label></th>
								<td>
									<input type="number" min="1" step="1" name="width" value="<?php echo esc_attr($options['size']['width']); ?>" />
									<p class="description"><?php esc_html_e('Input image width (In pixels)','themesky'); ?></p>
								</td>
							</tr>
							<tr>
								<th scope="row"><label><?php esc_html_e('Image height','themesky'); ?></label></th>
								<td>
									<input type="number" min="1" step="1" name="height" value="<?php echo esc_attr($options['size']['height']); ?>" />
									<p class="description"><?php esc_html_e('Input image height (In pixels)','themesky'); ?></p>
								</td>
							</tr>
							<tr>
								<th scope="row"><label><?php esc_html_e('Crop','themesky'); ?></label></th>
								<td>
									<select name="crop">
										<option value="1" <?php echo ($options['size']['crop']==1)?'selected':''; ?>>Yes</option>
										<option value="0" <?php echo ($options['size']['crop']==0)?'selected':''; ?>>No</option>
									</select>
									<p class="description"><?php esc_html_e('Crop image after uploading','themesky'); ?></p>
								</td>
							</tr>
						</tbody>
					</table>
					<h3><?php esc_html_e('Slider Responsive Options', 'themesky'); ?></h3>
					<div class="responsive-options-wrapper">
						<ul>
							<?php foreach( $options['responsive']['break_point'] as $k => $break){ ?>
							<li>
								<label><?php esc_html_e('Breakpoint from','themesky'); ?></label>
								<input name="responsive[break_point][]" type="number" min="0" step="1" value="<?php echo (int)$break; ?>" class="small-text" />
								<span>px</span>
								<input name="responsive[item][]" type="number" min="0" step="1" value="<?php echo (int)$options['responsive']['item'][$k]; ?>" class="small-text" />
								<label><?php esc_html_e('Items','themesky'); ?></label>
							</li>
							<?php } ?>
						</ul>
					</div>
					
					<input type="submit" name="ts_logo_save_setting" value="<?php esc_html_e('Save changes','themesky'); ?>" class="button button-primary" />
					<input type="submit" name="ts_logo_reset_setting" value="<?php esc_html_e('Reset','themesky'); ?>" class="button" />
				</form>
			</div>
			<script type="text/javascript">
				jQuery(function($){
					"use strict";
					$('input[name="ts_logo_reset_setting"]').on('click', function(e){
						var ok = confirm('Do you want to reset all settings?');
						if( !ok ){
							e.preventDefault();
						}
					});
				});
			</script>
			<?php
		}
	}
}
new TS_Logos();

/*** TS Footer Blocks ***/
if( !class_exists('TS_Footer_Blocks') ){
	class TS_Footer_Blocks{
		public $post_type;
		
		function __construct(){
			$this->post_type = 'ts_footer_block';
			add_action('init', array($this, 'register_post_type'));
		}
		
		function register_post_type(){
			$labels = array(
				'name' 				=> esc_html_x( 'Footer Blocks', 'post type general name', 'themesky' ),
				'singular_name' 	=> esc_html_x( 'Footer Block', 'post type singular name', 'themesky' ),
				'add_new' 			=> esc_html_x( 'Add New', 'logo', 'themesky' ),
				'add_new_item' 		=> esc_html__( 'Add New', 'themesky' ),
				'edit_item' 		=> esc_html__( 'Edit Footer Block', 'themesky' ),
				'new_item' 			=> esc_html__( 'New Footer Block', 'themesky' ),
				'all_items' 		=> esc_html__( 'All Footer Blocks', 'themesky' ),
				'view_item' 		=> esc_html__( 'View Footer Block', 'themesky' ),
				'search_items' 		=> esc_html__( 'Search Footer Block', 'themesky' ),
				'not_found' 		=> esc_html__( 'No Footer Blocks Found', 'themesky' ),
				'not_found_in_trash'=> esc_html__( 'No Footer Blocks Found In Trash', 'themesky' ),
				'parent_item_colon' => '',
				'menu_name' 		=> esc_html__( 'Footer Blocks', 'themesky' )
			);
			$args = array(
				'labels' 			=> $labels,
				'public' 			=> true,
				'publicly_queryable'=> true,
				'show_ui' 			=> true,
				'show_in_menu' 		=> true,
				'query_var' 		=> true,
				'rewrite' 			=> array( 'slug' => $this->post_type ),
				'capability_type' 	=> 'post',
				'has_archive' 		=> false,
				'hierarchical' 		=> false,
				'supports' 			=> array( 'title', 'editor' ),
				'menu_position' 	=> 5,
			);
			register_post_type( $this->post_type, $args );
		}
	}
}
new TS_Footer_Blocks();

/*** TS Mega Menu ***/
if( !class_exists('TS_Mega_Menus') ){
	class TS_Mega_Menus{
		public $post_type = 'ts_mega_menu';
		
		function __construct(){
			add_action( 'init', array($this, 'register_post_type') );
		}
		
		function register_post_type(){
			$labels = array(
				'name' 				=> esc_html_x( 'Mega Menus', 'post type general name', 'themesky' ),
				'singular_name' 	=> esc_html_x( 'Mega Menu', 'post type singular name', 'themesky' ),
				'add_new' 			=> esc_html_x( 'Add New', 'mega_menu', 'themesky' ),
				'add_new_item' 		=> esc_html__( 'Add New', 'themesky' ),
				'edit_item' 		=> esc_html__( 'Edit Mega Menu', 'themesky' ),
				'new_item' 			=> esc_html__( 'New Mega Menu', 'themesky' ),
				'all_items' 		=> esc_html__( 'All Mega Menus', 'themesky' ),
				'view_item' 		=> esc_html__( 'View Mega Menu', 'themesky' ),
				'search_items' 		=> esc_html__( 'Search Mega Menu', 'themesky' ),
				'not_found' 		=> esc_html__( 'No Mega Menus Found', 'themesky' ),
				'not_found_in_trash'=> esc_html__( 'No Mega Menus Found In Trash', 'themesky' ),
				'parent_item_colon' => '',
				'menu_name' 		=> esc_html__( 'Mega Menus', 'themesky' )
			);
			$args = array(
				'labels' 			=> $labels,
				'public' 			=> true,
				'publicly_queryable'=> true,
				'show_ui' 			=> true,
				'show_in_menu' 		=> true,
				'query_var' 		=> true,
				'rewrite' 			=> array( 'slug' => $this->post_type ),
				'capability_type' 	=> 'post',
				'has_archive' 		=> false,
				'hierarchical' 		=> false,
				'supports' 			=> array( 'title', 'editor' ),
				'menu_position' 	=> 5,
			);
			register_post_type( $this->post_type, $args );
		}
	}
}
new TS_Mega_Menus();

/*** Product Brands ***/
if( !class_exists('TS_Product_Brands') ){
	class TS_Product_Brands{
		function __construct(){
			add_action('init', array($this, 'register_taxonomy'));
			add_action('ts_product_brand_add_form_fields', array($this, 'add_brand_fields'));
			add_action('ts_product_brand_edit_form_fields', array($this, 'edit_brand_fields'), 10);
			add_action('created_term', array($this, 'save_brand_fields'), 10, 3);
			add_action('edit_term', array($this, 'save_brand_fields'), 10, 3);
			add_action('delete_term', array($this, 'delete_term'), 5);
			
			add_filter('manage_edit-ts_product_brand_columns', array($this, 'product_brand_columns'));
			add_filter('manage_ts_product_brand_custom_column', array($this, 'product_brand_column'), 10, 3);
			// Maintain hierarchy of terms
			add_filter('wp_terms_checklist_args', array($this, 'disable_checked_ontop'));

			/* Register field permalink */
			add_action('load-options-permalink.php', array($this, 'register_custom_fields'));
		}
		
		function register_taxonomy(){
			if( taxonomy_exists( 'ts_product_brand' ) ){
				return;
			}

			$taxanomy_slug = get_option('ts_product_brand_permalink');

			$args = array(
					'hierarchical'           => true
					,'label'                 => esc_html__( 'Brands', 'themesky' )
					,'labels' => array(
							'name'               => esc_html__( 'Product brands', 'themesky' )
							,'singular_name'     => esc_html__( 'Brand', 'themesky' )
							,'menu_name'         => esc_html__( 'Brands', 'themesky' )
							,'search_items'      => esc_html__( 'Search brands', 'themesky' )
							,'all_items'         => esc_html__( 'All brands', 'themesky' )
							,'parent_item'       => esc_html__( 'Parent brand', 'themesky' )
							,'parent_item_colon' => esc_html__( 'Parent brand:', 'themesky' )
							,'edit_item'         => esc_html__( 'Edit brand', 'themesky' )
							,'update_item'       => esc_html__( 'Update brand', 'themesky' )
							,'add_new_item'      => esc_html__( 'Add new brand', 'themesky' )
							,'new_item_name'     => esc_html__( 'New brand name', 'themesky' )
							,'not_found'         => esc_html__( 'No brands found', 'themesky' )
						)
					,'show_ui'               => true
					,'query_var'             => true
					,'capabilities'          => array(
						'manage_terms'  => 'manage_product_terms'
						,'edit_terms'   => 'edit_product_terms'
						,'delete_terms' => 'delete_product_terms'
						,'assign_terms' => 'assign_product_terms'
					)
					,'rewrite'          => array(
						'slug'          => 'product-brand'
						,'with_front'   => false
						,'hierarchical' => true
					)
				);
		
			if( $taxanomy_slug ){
				$args['rewrite']['slug'] = sanitize_title_with_dashes( $taxanomy_slug );
			}

			register_taxonomy( 'ts_product_brand', array( 'product' ), $args );
		}
		
		function add_brand_fields(){
			?>
			<div class="form-field term-thumbnail-wrap">
				<label><?php esc_html_e( 'Thumbnail', 'themesky' ); ?></label>
				<div id="product_brand_thumbnail" style="float: left; margin-right: 10px;"><img src="<?php echo esc_url( wc_placeholder_img_src() ); ?>" width="60px" height="60px" /></div>
				<div style="line-height: 60px;">
					<input type="hidden" id="product_brand_thumbnail_id" name="product_brand_thumbnail_id" />
					<button type="button" class="upload_image_button button"><?php esc_html_e( 'Upload/Add image', 'themesky' ); ?></button>
					<button type="button" class="remove_image_button button"><?php esc_html_e( 'Remove image', 'themesky' ); ?></button>
				</div>
				<script type="text/javascript">

					// Only show the "remove image" button when needed
					if ( ! jQuery( '#product_brand_thumbnail_id' ).val() ) {
						jQuery( '.remove_image_button' ).hide();
					}

					// Uploading files
					var file_frame;

					jQuery( document ).on( 'click', '.upload_image_button', function( event ) {

						event.preventDefault();

						// If the media frame already exists, reopen it.
						if ( file_frame ) {
							file_frame.open();
							return;
						}

						// Create the media frame.
						file_frame = wp.media.frames.downloadable_file = wp.media({
							title: '<?php esc_html_e( 'Choose an image', 'themesky' ); ?>',
							button: {
								text: '<?php esc_html_e( 'Use image', 'themesky' ); ?>'
							},
							multiple: false
						});

						// When an image is selected, run a callback.
						file_frame.on( 'select', function() {
							var attachment           = file_frame.state().get( 'selection' ).first().toJSON();
							var attachment_thumbnail = attachment.sizes.thumbnail || attachment.sizes.full;

							jQuery( '#product_brand_thumbnail_id' ).val( attachment.id );
							jQuery( '#product_brand_thumbnail' ).find( 'img' ).attr( 'src', attachment_thumbnail.url );
							jQuery( '.remove_image_button' ).show();
						});

						// Finally, open the modal.
						file_frame.open();
					});

					jQuery( document ).on( 'click', '.remove_image_button', function() {
						jQuery( '#product_brand_thumbnail' ).find( 'img' ).attr( 'src', '<?php echo esc_js( wc_placeholder_img_src() ); ?>' );
						jQuery( '#product_brand_thumbnail_id' ).val( '' );
						jQuery( '.remove_image_button' ).hide();
						return false;
					});

					jQuery( document ).ajaxComplete( function( event, request, options ) {
						if ( request && 4 === request.readyState && 200 === request.status
							&& options.data && 0 <= options.data.indexOf( 'action=add-tag' ) ) {

							var res = wpAjax.parseAjaxResponse( request.responseXML, 'ajax-response' );
							if ( ! res || res.errors ) {
								return;
							}
							// Clear Thumbnail fields on submit
							jQuery( '#product_brand_thumbnail' ).find( 'img' ).attr( 'src', '<?php echo esc_js( wc_placeholder_img_src() ); ?>' );
							jQuery( '#product_brand_thumbnail_id' ).val( '' );
							jQuery( '.remove_image_button' ).hide();
							return;
						}
					} );
				</script>
				<div class="clear"></div>
			</div>

			<?php
		}
		
		function edit_brand_fields( $term ){
			$thumbnail_id = absint(get_term_meta($term->term_id, 'thumbnail_id', true));

			if( $thumbnail_id ){
				$image = wp_get_attachment_thumb_url( $thumbnail_id );
			}else{
				$image = wc_placeholder_img_src();
			}
			?>
			<tr class="form-field">
				<th scope="row" valign="top"><label><?php esc_html_e( 'Thumbnail', 'themesky' ); ?></label></th>
				<td>
					<div id="product_brand_thumbnail" style="float: left; margin-right: 10px;"><img src="<?php echo esc_url( $image ); ?>" width="60px" height="60px" /></div>
					<div style="line-height: 60px;">
						<input type="hidden" id="product_brand_thumbnail_id" name="product_brand_thumbnail_id" value="<?php echo $thumbnail_id; ?>" />
						<button type="button" class="upload_image_button button"><?php esc_html_e( 'Upload/Add image', 'themesky' ); ?></button>
						<button type="button" class="remove_image_button button"><?php esc_html_e( 'Remove image', 'themesky' ); ?></button>
					</div>
					<script type="text/javascript">

						// Only show the "remove image" button when needed
						if ( '0' === jQuery( '#product_brand_thumbnail_id' ).val() ) {
							jQuery( '.remove_image_button' ).hide();
						}

						// Uploading files
						var file_frame;

						jQuery( document ).on( 'click', '.upload_image_button', function( event ) {

							event.preventDefault();

							// If the media frame already exists, reopen it.
							if ( file_frame ) {
								file_frame.open();
								return;
							}

							// Create the media frame.
							file_frame = wp.media.frames.downloadable_file = wp.media({
								title: '<?php esc_html_e( 'Choose an image', 'themesky' ); ?>',
								button: {
									text: '<?php esc_html_e( 'Use image', 'themesky' ); ?>'
								},
								multiple: false
							});

							// When an image is selected, run a callback.
							file_frame.on( 'select', function() {
								var attachment           = file_frame.state().get( 'selection' ).first().toJSON();
								var attachment_thumbnail = attachment.sizes.thumbnail || attachment.sizes.full;

								jQuery( '#product_brand_thumbnail_id' ).val( attachment.id );
								jQuery( '#product_brand_thumbnail' ).find( 'img' ).attr( 'src', attachment_thumbnail.url );
								jQuery( '.remove_image_button' ).show();
							});

							// Finally, open the modal.
							file_frame.open();
						});

						jQuery( document ).on( 'click', '.remove_image_button', function() {
							jQuery( '#product_brand_thumbnail' ).find( 'img' ).attr( 'src', '<?php echo esc_js( wc_placeholder_img_src() ); ?>' );
							jQuery( '#product_brand_thumbnail_id' ).val( '' );
							jQuery( '.remove_image_button' ).hide();
							return false;
						});

					</script>
					<div class="clear"></div>
				</td>
			</tr>

			<?php
		}
		
		function save_brand_fields( $term_id, $tt_id = '', $taxonomy = '' ){
			if( isset( $_POST['product_brand_thumbnail_id'] ) && 'ts_product_brand' === $taxonomy ){
				update_term_meta( $term_id, 'thumbnail_id', absint( $_POST['product_brand_thumbnail_id'] ) );
			}
		}
		
		function delete_term( $term_id ){
			delete_term_meta( $term_id, 'thumbnail_id' );
		}
		
		function product_brand_columns( $columns ){
			$new_columns = array();

			if ( isset( $columns['cb'] ) ) {
				$new_columns['cb'] = $columns['cb'];
				unset( $columns['cb'] );
			}

			$new_columns['thumb'] = esc_html__( 'Image', 'themesky' );

			$columns = array_merge( $new_columns, $columns );

			return $columns;
		}
		
		function product_brand_column( $columns, $column, $id ){
			if( 'thumb' === $column ){

				$thumbnail_id = get_term_meta( $id, 'thumbnail_id', true );

				if( $thumbnail_id ){
					$image = wp_get_attachment_thumb_url( $thumbnail_id );
				}else{
					$image = wc_placeholder_img_src();
				}

				$image    = str_replace( ' ', '%20', $image );
				$columns .= '<img src="' . esc_url( $image ) . '" alt="' . esc_attr__( 'Thumbnail', 'themesky' ) . '" class="wp-post-image" height="48" width="48" />';
			}
			return $columns;
		}
		
		function disable_checked_ontop( $args ){
			if( !empty( $args['taxonomy'] ) && 'ts_product_brand' === $args['taxonomy'] ){
				$args['checked_ontop'] = false;
			}
			return $args;
		}

		function register_custom_fields (){
			if( isset($_POST['ts_product_brand_permalink']) ){
				update_option('ts_product_brand_permalink', sanitize_title_with_dashes( $_POST['ts_product_brand_permalink'] ) );
			}

			add_settings_field('ts_product_brand_permalink', esc_html__( 'Product brand base', 'themesky') , array($this, 'permalink_field_callback'), 'permalink', 'optional');
		}

		function permalink_field_callback() {
			$option = get_option('ts_product_brand_permalink');

			echo '<input type="text" value="' . esc_attr( $option ) . '" name="ts_product_brand_permalink" id="ts_product_brand_permalink" class="regular-text" />';
		}
	}
}
new TS_Product_Brands();

/*** TS Size Chart ***/
if( !class_exists('TS_Size_Chart') ){
	class TS_Size_Chart{
	
		public $post_type = 'ts_size_chart';

		function __construct(){
			add_action('init', array($this, 'register_post_type'));
		}
		
		function register_post_type(){
			$labels = array(
				'name' 				=> esc_html_x( 'Size Charts', 'post type general name', 'themesky' ),
				'singular_name' 	=> esc_html_x( 'Size Chart', 'post type singular name', 'themesky' ),
				'add_new' 			=> esc_html_x( 'Add Size Chart', 'size chart', 'themesky' ),
				'add_new_item' 		=> esc_html__( 'Add Size Chart', 'themesky' ),
				'edit_item' 		=> esc_html__( 'Edit Size Chart', 'themesky' ),
				'new_item' 			=> esc_html__( 'New Size Chart', 'themesky' ),
				'all_items' 		=> esc_html__( 'All Size Charts', 'themesky' ),
				'view_item' 		=> esc_html__( 'View Size Chart', 'themesky' ),
				'search_items' 		=> esc_html__( 'Search Size Charts', 'themesky' ),
				'not_found' 		=> esc_html__( 'No Size Chart Found', 'themesky' ),
				'not_found_in_trash'=> esc_html__( 'No Size Chart Found In Trash', 'themesky' ),
				'parent_item_colon' => '',
				'menu_name' 		=> esc_html__( 'Size Charts', 'themesky' )
			);
			$args = array(
				'labels' 			=> $labels,
				'public' 			=> false,
				'publicly_queryable'=> true,
				'show_ui' 			=> true,
				'show_in_menu' 		=> true,
				'query_var' 		=> true,
				'rewrite' 			=> array( 'slug' => str_replace('ts_', '', $this->post_type) ),
				'capability_type' 	=> 'post',
				'has_archive' 		=> false,
				'hierarchical' 		=> false,
				'supports' 			=> array( 'title', 'editor' ),
				'menu_position' 	=> 5,
				'menu_icon' 		=> ''
			);
			register_post_type( $this->post_type, $args );
		}
	}
}
new TS_Size_Chart();
?>