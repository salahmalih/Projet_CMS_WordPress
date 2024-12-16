<?php
if( !isset($data) ){
	$data = ecomall_get_theme_options();
}

$default_options = array(
				'ts_layout_fullwidth'			=> 0
				,'ts_logo_width'				=> "160"
				,'ts_device_logo_width'			=> "120"
				,'ts_prod_title_truncate' 		=> 1
				,'ts_prod_title_truncate_row' 	=> 2
				,'ts_top_cat_image_gap' 		=> 5
				,'ts_top_cat_content_gap' 		=> 2
				,'ts_custom_font_ttf'			=> array( 'url' => '' )
		);
		
foreach( $default_options as $option_name => $value ){
	if( isset($data[$option_name]) ){
		$default_options[$option_name] = $data[$option_name];
	}
}

extract($default_options);
		
$default_colors = array(
				'ts_main_content_background_color'				=>	'#ffffff'
				,'ts_primary_color'								=>	'#0068c8'
				,'ts_text_color_in_bg_primary'					=>	'#ffffff'
				,'ts_text_color'								=>	'#000000'
				,'ts_heading_color'								=>	'#000000'
				,'ts_gray_color'								=>	'#848484'
				,'ts_hightlight_color'							=>	'#fcc904'
				,'ts_dropdown_bg_color'							=>	'#ffffff'
				,'ts_dropdown_color'							=>	'#000000'
				,'ts_link_color'								=>	'#0068c8'
				,'ts_link_color_hover'							=>	'#0068c8'
				,'ts_border_color'								=>	'#ebebeb'
				
				,'ts_tags_color'								=>	'#848484'
				,'ts_tags_bg_color'								=>	'#transparent'
				,'ts_tags_border'								=>	'#ebebeb'
				
				,'ts_blockquote_bg'								=>	'#e8f3fe'
				,'ts_blockquote_color'							=>	'#000000'
				,'ts_blockquote_icon'							=>	'#000000'
				
				,'ts_input_color'								=>	'#000000'
				,'ts_input_bg'									=>	'#ffffff'
				,'ts_input_border'								=>	'#ebebeb'
				
				,'ts_btn_color'									=>	'#ffffff'
				,'ts_btn_bg'									=>	'#0068c8'
				,'ts_btn_border'								=>	'#0068c8'
				,'ts_btn_hover_color'							=>	'#0068c8'
				,'ts_btn_hover_bg'								=>	'transparent'
				,'ts_btn_hover_border'							=>	'#0068c8'
				
				,'ts_btn_special_color'							=>	'#000000'
				,'ts_btn_special_bg'							=>	'#e4f2ff'
				,'ts_btn_special_border'						=>	'#e4f2ff'
				,'ts_btn_special_hover_color'					=>	'#000000'
				,'ts_btn_special_hover_bg'						=>	'transparent'
				,'ts_btn_special_hover_border'					=>	'#0068c8'
				
				,'ts_btn_thumb_color'							=>	'#000000'
				,'ts_btn_thumb_bg'								=>	'#ffffff'
				,'ts_btn_thumb_border'							=>	'#ebebeb'
				,'ts_btn_thumb_hover_color'						=>	'#000000'
				,'ts_btn_thumb_hover_bg'						=>	'#e8f3fe'
				,'ts_btn_thumb_hover_border'					=>	'#e8f3fe'
				
				,'ts_product_bg_color'							=>	'#ffffff'
				,'ts_brand_bg_color'							=>	'#f4f4f4'
				,'ts_rating_color'								=>	'#c3c3c3'
				,'ts_rated_color'								=>	'#fdc904'
				,'ts_price_color'								=>	'#dd2831'
				,'ts_sale_price_color'							=>	'#848484'
				,'ts_product_sale_label_text_color'				=>	'#ffffff'
				,'ts_product_sale_label_background_color'		=>	'#0068c8'
				,'ts_product_new_label_text_color'				=>	'#ffffff'
				,'ts_product_new_label_background_color'		=>	'#000000'
				,'ts_product_feature_label_text_color'			=>	'#ffffff'
				,'ts_product_feature_label_background_color'	=>	'#dd2831'
				,'ts_product_outstock_label_text_color'			=>	'#ffffff'
				,'ts_product_outstock_label_background_color'	=>	'#919191'
				
				,'ts_breadcrumb_background_color'				=>	'#f4f4f4'
				,'ts_breadcrumb_text_color'						=>	'#000000'
				,'ts_breadcrumb_link_color'						=>	'#848484'
				,'ts_breadcrumb_v3_text_color'					=>	'#ffffff'
				,'ts_breadcrumb_v3_link_color'					=>	'#ffffff'
				
				,'ts_header_top_background_color' 				=>	'#1b1b1b'
				,'ts_header_top_text_color' 					=>	'#ffffff'
				,'ts_header_top_border_color' 					=>	'#1b1b1b'
				,'ts_header_middle_background_color' 			=>	'#ffffff'
				,'ts_header_middle_text_color' 					=>	'#000000'
				,'ts_header_middle_border_color' 				=>	'#ebebeb'
				,'ts_header_bottom_background_color' 			=>	'#ffffff'
				,'ts_header_bottom_text_color' 					=>	'#000000'
				,'ts_header_bottom_border_color' 				=>	'#ebebeb'
				,'ts_header_cart_count_background_color' 		=>	'#0068c8'
				,'ts_header_cart_count_text_color' 				=>	'#ffffff'
				,'ts_header_top_link_hover_color'				=>	'#0068c8'
				,'ts_header_middle_link_hover_color' 			=>	'#0068c8'
				,'ts_header_bottom_link_hover_color' 			=>	'#0068c8'
				,'ts_header_input_bg_color' 					=>	'#f8f8f8'
				
				,'ts_footer_background_color' 					=>	'#ffffff'
				,'ts_footer_text_color' 						=>	'#000000'
				,'ts_footer_heading_color' 						=>	'#000000'
				,'ts_footer_link_color' 						=>	'#dd2831'
				,'ts_footer_link_hover_color'					=>	'#0068c8'
);

$data = apply_filters('ecomall_custom_style_data', $data);

foreach( $default_colors as $option_name => $default_color ){
	if( isset($data[$option_name]['rgba']) ){
		$default_colors[$option_name] = $data[$option_name]['rgba'];
	}
	else if( isset($data[$option_name]['color']) ){
		$default_colors[$option_name] = $data[$option_name]['color'];
	}
}

extract( $default_colors );

/* Parse font option. Ex: if option name is ts_body_font, we will have variables below:
* ts_body_font (font-family)
* ts_body_font_weight
* ts_body_font_style
* ts_body_font_size
* ts_body_font_line_height
* ts_body_font_letter_spacing
*/
$font_option_names = array(
							'ts_body_font',
							'ts_heading_font',
							'ts_menu_font',
							'ts_vertical_menu_font',
							'ts_button_font',
							);
$font_size_option_names = array( 
							'ts_fs_body_font',
							'ts_fs_vertical_menu_font',
							'ts_fs_menu_font',
							'ts_fs_button_font',
							'ts_h1_font', 
							'ts_h2_font', 
							'ts_h3_font', 
							'ts_h4_font', 
							'ts_h5_font', 
							'ts_h6_font',
							'ts_fs_body_ipad_font',
							'ts_fs_button_ipad_font',
							'ts_h1_ipad_font', 
							'ts_h2_ipad_font', 
							'ts_h3_ipad_font', 
							'ts_h4_ipad_font', 
							'ts_h5_ipad_font', 
							'ts_h6_ipad_font',
							'ts_h1_mobile_font', 
							'ts_h2_mobile_font', 
							'ts_h3_mobile_font', 
							'ts_h4_mobile_font', 
							'ts_h5_mobile_font', 
							'ts_h6_mobile_font',
							);
$font_option_names = array_merge($font_option_names, $font_size_option_names);
foreach( $font_option_names as $option_name ){
	$default = array(
		$option_name 						=> 'inherit'
		,$option_name . '_weight' 			=> 'normal'
		,$option_name . '_style' 			=> 'normal'
		,$option_name . '_size' 			=> 'inherit'
		,$option_name . '_line_height' 		=> 'inherit'
		,$option_name . '_letter_spacing' 	=> 'inherit'
		,$option_name . '_transform' 		=> 'inherit'
	);
	if( is_array($data[$option_name]) ){
		if( !empty($data[$option_name]['font-family']) ){
			$default[$option_name] = $data[$option_name]['font-family'];
		}
		if( !empty($data[$option_name]['font-weight']) ){
			$default[$option_name . '_weight'] = $data[$option_name]['font-weight'];
		}
		if( !empty($data[$option_name]['font-style']) ){
			$default[$option_name . '_style'] = $data[$option_name]['font-style'];
		}
		if( !empty($data[$option_name]['font-size']) ){
			$default[$option_name . '_size'] = $data[$option_name]['font-size'];
		}
		if( !empty($data[$option_name]['line-height']) ){
			$default[$option_name . '_line_height'] = $data[$option_name]['line-height'];
		}
		if( !empty($data[$option_name]['letter-spacing']) ){
			$default[$option_name . '_letter_spacing'] = $data[$option_name]['letter-spacing'];
		}
		if( !empty($data[$option_name]['text-transform']) ){
			$default[$option_name . '_transform'] = $data[$option_name]['text-transform'];
		}
	}
	extract( $default );
}
?>	
:root{
	--ts-logo-width: <?php echo absint($ts_logo_width); ?>px;
	
	--ts-img-gap: <?php echo absint($ts_top_cat_image_gap); ?>px;
	
	--ts-pr-font-family: <?php echo esc_html($ts_body_font); ?>;
	--ts-pr-font-style: <?php echo esc_html($ts_body_font_style); ?>;
	--ts-pr-font-weight: <?php echo esc_html($ts_body_font_weight); ?>;
	--ts-pr-line-height: <?php echo esc_html($ts_body_font_line_height); ?>;
	--ts-pr-letter-spacing: <?php echo esc_html($ts_body_font_letter_spacing); ?>;
	--ts-body-font-size: <?php echo esc_html($ts_fs_body_font_size); ?>;
	
	--ts-heading-font-family: <?php echo esc_html($ts_heading_font); ?>;
	--ts-heading-font-style: <?php echo esc_html($ts_heading_font_style); ?>;
	--ts-heading-font-weight: <?php echo esc_html($ts_heading_font_weight); ?>;
	--ts-heading-line-height: <?php echo esc_html($ts_heading_font_line_height); ?>;
	--ts-heading-letter-spacing: <?php echo esc_html($ts_heading_font_letter_spacing); ?>;
	
	--ts-btn-font-family: <?php echo esc_html($ts_button_font); ?>;
	--ts-btn-font-style: <?php echo esc_html($ts_button_font_style); ?>;
	--ts-btn-font-weight: <?php echo esc_html($ts_button_font_weight); ?>;
	--ts-btn-line-height: <?php echo esc_html($ts_button_font_line_height); ?>;
	--ts-btn-letter-spacing: <?php echo esc_html($ts_button_font_letter_spacing); ?>;
	--ts-btn-font-size: <?php echo esc_html($ts_fs_button_font_size); ?>;
	
	--ts-menu-font-family: <?php echo esc_html($ts_menu_font); ?>;
	--ts-menu-font-weight: <?php echo esc_html($ts_menu_font_weight); ?>;
	--ts-menu-font-size: <?php echo esc_html($ts_fs_menu_font_size); ?>;
	--ts-menu-letter-spacing: <?php echo esc_html($ts_menu_font_letter_spacing); ?>;
	
	--ts-v-menu-font-family: <?php echo esc_html($ts_vertical_menu_font); ?>;
	--ts-v-menu-font-weight: <?php echo esc_html($ts_vertical_menu_font_weight); ?>;
	--ts-v-menu-font-size: <?php echo esc_html($ts_fs_vertical_menu_font_size); ?>;
	--ts-v-menu-letter-spacing: <?php echo esc_html($ts_vertical_menu_font_letter_spacing); ?>;
	
	--ts-btn-ipad-font-size: <?php echo esc_html($ts_fs_button_ipad_font_size); ?>;
	
	--ts-primary-color: <?php echo esc_html($ts_primary_color); ?>;
	--ts-text-in-primary-color: <?php echo esc_html($ts_text_color_in_bg_primary); ?>;
	<?php if( strpos($ts_primary_color, 'rgba') !== false ): ?>
	--ts-primary-opacity: <?php echo esc_html(str_replace('1)', '0.1)', esc_html($ts_primary_color))); ?>;
	--ts-primary-loading-color: <?php echo esc_html(str_replace('1)', '0.5)', esc_html($ts_primary_color))); ?>;
	<?php endif; ?>
	--ts-main-bg: <?php echo esc_html($ts_main_content_background_color); ?>;
	--ts-text-color: <?php echo esc_html($ts_text_color); ?>;
	--ts-heading-color: <?php echo esc_html($ts_heading_color); ?>;
	--ts-gray-color: <?php echo esc_html($ts_gray_color); ?>;
	--ts-hightlight: <?php echo esc_html($ts_hightlight_color); ?>;
	--ts-dropdown-bg: <?php echo esc_html($ts_dropdown_bg_color); ?>;
	--ts-dropdown-color: <?php echo esc_html($ts_dropdown_color); ?>;
	--ts-link-color: <?php echo esc_html($ts_link_color); ?>;
	--ts-link-hover-color: <?php echo esc_html($ts_link_color_hover); ?>;
	--ts-border: <?php echo esc_html($ts_border_color); ?>;
	
	--ts-tag-color: <?php echo esc_html($ts_tags_color); ?>;
	--ts-tag-bg: <?php echo esc_html($ts_tags_bg_color); ?>;
	--ts-tag-border: <?php echo esc_html($ts_tags_border); ?>;
	
	--ts-blockquote-bg-color: <?php echo esc_html($ts_blockquote_bg); ?>;
	--ts-blockquote-icon-color: <?php echo esc_html($ts_blockquote_color); ?>;
	--ts-blockquote-text-color: <?php echo esc_html($ts_blockquote_icon); ?>;
	
	--ts-input-color: <?php echo esc_html($ts_input_color); ?>;
	--ts-input-background-color: <?php echo esc_html($ts_input_bg); ?>;
	--ts-input-border: <?php echo esc_html($ts_input_border); ?>;
	
	--ts-btn-color: <?php echo esc_html($ts_btn_color); ?>;
	--ts-btn-bg: <?php echo esc_html($ts_btn_bg); ?>;
	--ts-btn-border: <?php echo esc_html($ts_btn_border); ?>;
	--ts-btn-hover-color: <?php echo esc_html($ts_btn_hover_color); ?>;
	--ts-btn-hover-bg: <?php echo esc_html($ts_btn_hover_bg); ?>;
	--ts-btn-hover-border: <?php echo esc_html($ts_btn_hover_border); ?>;
	<?php if( strpos($ts_btn_color, 'rgba') !== false ): ?>
	--ts-button-loading-color: <?php echo esc_html(str_replace('1)', '0.5)', esc_html($ts_btn_color))); ?>;
	<?php endif; ?>
	<?php if( strpos($ts_btn_hover_color, 'rgba') !== false ): ?>
	--ts-button-loading-hover-color: <?php echo esc_html(str_replace('1)', '0.5)', esc_html($ts_btn_hover_color))); ?>;
	<?php endif; ?>
	
	--ts-btn-special-color: <?php echo esc_html($ts_btn_special_color); ?>;
	--ts-btn-special-bg: <?php echo esc_html($ts_btn_special_bg); ?>;
	--ts-btn-special-border: <?php echo esc_html($ts_btn_special_border); ?>;
	--ts-btn-special-hover-color: <?php echo esc_html($ts_btn_special_hover_color); ?>;
	--ts-btn-special-hover-bg: <?php echo esc_html($ts_btn_special_hover_bg); ?>;
	--ts-btn-special-hover-border: <?php echo esc_html($ts_btn_special_hover_border); ?>;
	<?php if( strpos($ts_btn_special_color, 'rgba') !== false ): ?>
	--ts-button-loading-color: <?php echo esc_html(str_replace('1)', '0.5)', esc_html($ts_btn_special_color))); ?>;
	<?php endif; ?>
	<?php if( strpos($ts_btn_special_hover_color, 'rgba') !== false ): ?>
	--ts-button-loading-hover-color: <?php echo esc_html(str_replace('1)', '0.5)', esc_html($ts_btn_special_hover_color))); ?>;
	<?php endif; ?>
	<?php if( strpos($ts_btn_special_bg, 'rgba') !== false ): ?>
	--ts-spe-btn-bg: <?php echo esc_html(str_replace('1)', '0.15)', esc_html($ts_btn_special_bg))); ?>;
	<?php endif; ?>
	
	--ts-btn-thumbnail-color: <?php echo esc_html($ts_btn_thumb_color); ?>;
	--ts-btn-thumbnail-bg: <?php echo esc_html($ts_btn_thumb_bg); ?>;
	--ts-btn-thumbnail-border: <?php echo esc_html($ts_btn_thumb_border); ?>;
	--ts-btn-thumbnail-hover-color: <?php echo esc_html($ts_btn_thumb_hover_color); ?>;
	--ts-btn-thumbnail-hover-bg: <?php echo esc_html($ts_btn_thumb_hover_bg); ?>;
	--ts-btn-thumbnail-hover-border: <?php echo esc_html($ts_btn_thumb_hover_border); ?>;
	<?php if( strpos($ts_btn_thumb_color, 'rgba') !== false ): ?>
	--ts-button-loading-color: <?php echo esc_html(str_replace('1)', '0.5)', esc_html($ts_btn_thumb_color))); ?>;
	<?php endif; ?>
	<?php if( strpos($ts_btn_thumb_hover_color, 'rgba') !== false ): ?>
	--ts-button-loading-hover-color: <?php echo esc_html(str_replace('1)', '0.5)', esc_html($ts_btn_thumb_hover_color))); ?>;
	<?php endif; ?>

	--ts-product-bg-color: <?php echo esc_html($ts_product_bg_color); ?>;
	--ts-brand-bg: <?php echo esc_html($ts_brand_bg_color); ?>;
	--ts-rating-color: <?php echo esc_html($ts_rating_color); ?>;
	--ts-rated-color: <?php echo esc_html($ts_rated_color); ?>;
	--ts-product-price-color: <?php echo esc_html($ts_price_color); ?>;
	--ts-product-sale-price-color: <?php echo esc_html($ts_sale_price_color); ?>;
	--ts-sale-label-color: <?php echo esc_html($ts_product_sale_label_text_color); ?>;
	--ts-sale-label-bg: <?php echo esc_html($ts_product_sale_label_background_color); ?>;
	--ts-new-label-color: <?php echo esc_html($ts_product_new_label_text_color); ?>;
	--ts-new-label-bg: <?php echo esc_html($ts_product_new_label_background_color); ?>;
	--ts-hot-label-color: <?php echo esc_html($ts_product_feature_label_text_color); ?>;
	--ts-hot-label-bg: <?php echo esc_html($ts_product_feature_label_background_color); ?>;
	--ts-soldout-label-color: <?php echo esc_html($ts_product_outstock_label_text_color); ?>;
	--ts-soldout-label-bg: <?php echo esc_html($ts_product_outstock_label_background_color); ?>;
	
	--ts-breadcrumb-bg: <?php echo esc_html($ts_breadcrumb_background_color); ?>;
	--ts-breadcrumb-color: <?php echo esc_html($ts_breadcrumb_text_color); ?>;
	--ts-breadcrumb-link-color: <?php echo esc_html($ts_breadcrumb_link_color); ?>;
	--ts-breadcrumb-v3-color: <?php echo esc_html($ts_breadcrumb_v3_text_color); ?>;
	--ts-breadcrumb-v3-link-color: <?php echo esc_html($ts_breadcrumb_v3_link_color); ?>;
}
.ts-header{	
	--ts-hd-top-bg: <?php echo esc_html($ts_header_top_background_color); ?>;
	--ts-hd-top-color: <?php echo esc_html($ts_header_top_text_color); ?>;
	--ts-hd-top-border: <?php echo esc_html($ts_header_top_border_color); ?>;
	--ts-hd-top-link-hover: <?php echo esc_html($ts_header_top_link_hover_color); ?>;
	--ts-hd-middle-bg: <?php echo esc_html($ts_header_middle_background_color); ?>;
	--ts-hd-middle-color: <?php echo esc_html($ts_header_middle_text_color); ?>;
	--ts-hd-middle-border: <?php echo esc_html($ts_header_middle_border_color); ?>;
	--ts-hd-middle-link-hover: <?php echo esc_html($ts_header_middle_link_hover_color); ?>;
	--ts-hd-bottom-bg: <?php echo esc_html($ts_header_bottom_background_color); ?>;
	--ts-hd-bottom-color: <?php echo esc_html($ts_header_bottom_text_color); ?>;
	--ts-hd-bottom-border: <?php echo esc_html($ts_header_bottom_border_color); ?>;
	--ts-hd-bottom-link-hover: <?php echo esc_html($ts_header_bottom_link_hover_color); ?>;
	--ts-cart-count-bg: <?php echo esc_html($ts_header_cart_count_background_color); ?>;
	--ts-cart-count-color: <?php echo esc_html($ts_header_cart_count_text_color); ?>;
	--ts-input-background: <?php echo esc_html($ts_header_input_bg_color); ?>;
}
.footer-container{	
	--ts-footer-bg: <?php echo esc_html($ts_footer_background_color); ?>;
	--ts-footer-color: <?php echo esc_html($ts_footer_text_color); ?>;
	--ts-footer-heading-color: <?php echo esc_html($ts_footer_heading_color); ?>;
	--ts-footer-link-color: <?php echo esc_html($ts_footer_link_color); ?>;
	--ts-footer-link-hover-color: <?php echo esc_html($ts_footer_link_hover_color); ?>;
}
@media only screen and (max-width: 1279px){
	:root{
		--ts-logo-width: <?php echo absint($ts_device_logo_width); ?>px;
	}
	#group-icon-header .logo-wrapper{
		--ts-logo-width: <?php echo absint($ts_device_logo_width); ?>px;
	}
}

<?php
/*** Custom Font ***/
if( isset($ts_custom_font_ttf) && $ts_custom_font_ttf['url'] ):
?>
@font-face {
	font-family: 'CustomFont';
	src:url('<?php echo esc_url($ts_custom_font_ttf['url']); ?>') format('truetype');
	font-weight: normal;
	font-style: normal;
}
<?php 
endif;	

/*** Truncate Product Title ***/
if( !empty($ts_prod_title_truncate) && isset($ts_prod_title_truncate_row) ):
?>
table.group_table .woocommerce-grouped-product-list-item__label a,
.woocommerce .products .product .product-name{
	display: -webkit-box;
	-webkit-box-orient: vertical;
	-webkit-line-clamp: <?php echo absint($ts_prod_title_truncate_row); ?>;
	overflow: hidden;
}
<?php endif; ?>