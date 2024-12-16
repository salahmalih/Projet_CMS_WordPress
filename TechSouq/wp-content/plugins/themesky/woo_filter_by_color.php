<?php 
class TS_Product_Filter_By_Color{
	public $attr_slug = 'pa_color';
	public function __construct(){
		$this->init_handle();
		add_action( 'init', array($this, 'add_image_size') );
		add_action( 'admin_enqueue_scripts', array($this, 'register_admin_scripts') );
	}
	
	function init_handle(){
		add_action( $this->attr_slug . '_edit_form_fields', array($this, 'edit_color_attribute'), 1000, 2 );
		add_action( $this->attr_slug . '_add_form_fields', array($this, 'add_color_attribute'), 1000 );
		
		add_action( 'created_term', array($this, 'save_color_fields'), 10,3 );
		add_action( 'edit_term', array($this, 'save_color_fields'), 10,3 );
		add_action( 'delete_term', array($this, 'remove_color_fields'), 10,3 );
	}
	
	function add_image_size(){
		if( !class_exists('WooCommerce') ){
			return;
		}
		$attribute_name_array = wc_get_attribute_taxonomy_names();
		$taxonomy_exists = in_array($this->attr_slug, $attribute_name_array);
		if( $taxonomy_exists ){
			add_image_size('ts_prod_color_thumb', 46, 46, true);
		}
	}
	
	function register_admin_scripts(){
		wp_enqueue_style( 'wp-color-picker');
		wp_enqueue_script( 'wp-color-picker');
	}
	
	function edit_color_attribute( $term, $taxonomy ){
		$placeholder = wc_placeholder_img_src();
		$datas = get_term_meta($term->term_id, 'ts_product_color_config', true);
		if( strlen($datas) > 0 ){
			$datas = unserialize($datas);	
		}else{
			$datas = array(
						'ts_color_color' 				=> "#ffffff"
						,'ts_color_image' 				=> 0
					);
		}
		
		if( absint($datas['ts_color_image']) > 0 ){
			$image = wp_get_attachment_thumb_url( $datas['ts_color_image'] );
		}
		else{
			$image = $placeholder;
		}
		?>
		<tr class="form-field">
			<th scope="row" valign="top"><label><?php esc_html_e( 'Color', 'themesky' ); ?></label></th>
			<td>
				<input name="ts_color_color" class="ts-color-picker" data-default-color="<?php echo esc_attr($datas['ts_color_color']);?>" type="text" value="<?php echo esc_attr($datas['ts_color_color']);?>" size="40" aria-required="true">
				<p class="description"><?php esc_html_e( 'Use color picker to pick one color.', 'themesky' ); ?></p>
			</td>
		</tr>

		<tr class="form-field">
			<th scope="row" valign="top"><label><?php esc_html_e( 'Thumbnail Image', 'themesky' ); ?></label></th>
			<td>
				<input name="ts_color_image" type="hidden" class="ts_color_image" value="<?php echo absint($datas['ts_color_image']);?>" />
				<img src="<?php echo esc_url( $image ); ?>" class="ts_color_preview_image" data-placeholder="<?php echo esc_url($placeholder) ?>" /><br />
				<input class="ts_color_upload_image_button button" type="button" size="40" value="<?php esc_attr_e('Choose Image', 'themesky') ?>" />
				<input class="ts_color_remove_image_button button" type="button" size="40" value="<?php esc_attr_e('Remove Image', 'themesky') ?>" />
			</td>
		</tr>
		<script type="text/javascript">
			jQuery(function($){
				"use strict";
				
				if( $('input.ts_color_image').val() == '0' ){
					$('.ts_color_remove_image_button').hide();
				}

				var file_frame;

				$(document).on( 'click', '.ts_color_upload_image_button', function( event ){

					event.preventDefault();

					if ( file_frame ) {
						file_frame.open();
						return;
					}

					file_frame = wp.media.frames.downloadable_file = wp.media({
						title: '<?php esc_html_e( 'Choose an image', 'themesky' ); ?>',
						button: {
							text: '<?php esc_html_e( 'Use image', 'themesky' ); ?>',
						},
						multiple: false
					});

					file_frame.on( 'select', function() {
						var attachment = file_frame.state().get('selection').first().toJSON();

						$('input.ts_color_image').val( attachment.id );
						$('.ts_color_preview_image').attr('src', attachment.url );
						$('.ts_color_remove_image_button').show();
					});

					file_frame.open();
				});

				$(document).on( 'click', '.ts_color_remove_image_button', function( event ){
					$('.ts_color_preview_image').attr('src', $('.ts_color_preview_image').data('placeholder'));
					$('input.ts_color_image').val('');
					$('.ts_color_remove_image_button').hide();
					return false;
				});
			});
		</script>
		<?php
	}
	
	function add_color_attribute(){
		$placeholder = wc_placeholder_img_src();
		?>
		<div class="form-field">
			<label><?php esc_html_e( 'Color', 'themesky' ); ?></label>
			<input name="ts_color_color" class="ts-color-picker" data-default-color="#ffffff" type="text" value="#ffffff" size="40" aria-required="true">
			<p class="description"><?php esc_html_e( 'Use color picker to pick one color.', 'themesky' ); ?></p>
		</div>

		<div class="form-field">
			<label><?php esc_html_e( 'Thumbnail Image', 'themesky' ); ?></label>
			<input name="ts_color_image" type="hidden" class="ts_color_image" value="" />
			<img src="<?php echo esc_url($placeholder) ?>" class="ts_color_preview_image" data-placeholder="<?php echo esc_url($placeholder) ?>" /><br />
			<input class="ts_color_upload_image_button button" type="button" size="40" value="<?php esc_attr_e('Choose Image', 'themesky') ?>" />
			<input class="ts_color_remove_image_button button" type="button" size="40" value="<?php esc_attr_e('Remove Image', 'themesky') ?>" />
		</div>
		
		<script type="text/javascript">
			jQuery(function($){
				"use strict";
				
				if( $('input.ts_color_image').val() == '' ){
					$('.ts_color_remove_image_button').hide();
				}

				var file_frame;

				$(document).on( 'click', '.ts_color_upload_image_button', function( event ){

					event.preventDefault();

					if ( file_frame ) {
						file_frame.open();
						return;
					}

					file_frame = wp.media.frames.downloadable_file = wp.media({
						title: '<?php esc_html_e( 'Choose an image', 'themesky' ); ?>',
						button: {
							text: '<?php esc_html_e( 'Use image', 'themesky' ); ?>',
						},
						multiple: false
					});

					file_frame.on( 'select', function() {
						var attachment = file_frame.state().get('selection').first().toJSON();

						$('input.ts_color_image').val( attachment.id );
						$('.ts_color_preview_image').attr('src', attachment.url );
						$('.ts_color_remove_image_button').show();
					});

					file_frame.open();
				});

				$(document).on( 'click', '.ts_color_remove_image_button', function( event ){
					$('.ts_color_preview_image').attr('src', $('.ts_color_preview_image').data('placeholder'));
					$('input.ts_color_image').val('');
					$('.ts_color_remove_image_button').hide();
					return false;
				});
			});
		</script>
		<?php
	}
	
	function save_color_fields( $term_id, $tt_id, $taxonomy ){
		if( isset($_POST['ts_color_color'], $_POST['ts_color_image']) ){
			$datas = array();
			$datas['ts_color_color'] = $_POST['ts_color_color'];
			$datas['ts_color_image'] = $_POST['ts_color_image'];
			update_term_meta( $term_id, 'ts_product_color_config', serialize($datas) );
		}
	}
	
	function remove_color_fields( $term_id, $tt_id, $taxonomy ){
		delete_term_meta( $term_id, 'ts_product_color_config' );
	}
}
new TS_Product_Filter_By_Color();
?>