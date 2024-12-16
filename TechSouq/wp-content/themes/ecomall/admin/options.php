<?php
$redux_url = '';
if( class_exists('ReduxFramework') ){
	$redux_url = ReduxFramework::$_url;
}

$logo_url 					= get_template_directory_uri() . '/images/logo.png'; 
$favicon_url 				= get_template_directory_uri() . '/images/favicon.ico';

$color_image_folder = get_template_directory_uri() . '/admin/assets/images/colors/';
$list_colors = array('default','dark','dark-2','dark-3');
$preset_colors_options = array();
foreach( $list_colors as $color ){
	$preset_colors_options[$color] = array(
					'alt'      => $color
					,'img'     => $color_image_folder . $color . '.jpg'
					,'presets' => ecomall_get_preset_color_options( $color )
	);
}

$family_fonts = array(
	"Arial, Helvetica, sans-serif"                          => "Arial, Helvetica, sans-serif"
	,"'Arial Black', Gadget, sans-serif"                    => "'Arial Black', Gadget, sans-serif"
	,"'Bookman Old Style', serif"                           => "'Bookman Old Style', serif"
	,"'Comic Sans MS', cursive"                             => "'Comic Sans MS', cursive"
	,"Courier, monospace"                                   => "Courier, monospace"
	,"Garamond, serif"                                      => "Garamond, serif"
	,"Georgia, serif"                                       => "Georgia, serif"
	,"Impact, Charcoal, sans-serif"                         => "Impact, Charcoal, sans-serif"
	,"'Lucida Console', Monaco, monospace"                  => "'Lucida Console', Monaco, monospace"
	,"'Lucida Sans Unicode', 'Lucida Grande', sans-serif"   => "'Lucida Sans Unicode', 'Lucida Grande', sans-serif"
	,"'MS Sans Serif', Geneva, sans-serif"                  => "'MS Sans Serif', Geneva, sans-serif"
	,"'MS Serif', 'New York', sans-serif"                   => "'MS Serif', 'New York', sans-serif"
	,"'Palatino Linotype', 'Book Antiqua', Palatino, serif" => "'Palatino Linotype', 'Book Antiqua', Palatino, serif"
	,"Tahoma,Geneva, sans-serif"                            => "Tahoma, Geneva, sans-serif"
	,"'Times New Roman', Times,serif"                       => "'Times New Roman', Times, serif"
	,"'Trebuchet MS', Helvetica, sans-serif"                => "'Trebuchet MS', Helvetica, sans-serif"
	,"Verdana, Geneva, sans-serif"                          => "Verdana, Geneva, sans-serif"
	,"CustomFont"                          					=> "CustomFont"
);

$header_layout_options = array();
$header_image_folder = get_template_directory_uri() . '/admin/assets/images/headers/';
for( $i = 1; $i <= 6; $i++ ){
	$header_layout_options['v' . $i] = array(
		'alt'  => sprintf(esc_html__('Header Layout %s', 'ecomall'), $i)
		,'img' => $header_image_folder . 'header_v'.$i.'.jpg'
	);
}

$product_hover_style_options = array();
$product_hover_image_folder = get_template_directory_uri() . '/admin/assets/images/products/';
for( $i = 1; $i <= 2; $i++ ){
	$product_hover_style_options['v' . $i] = array(
		'alt'  => sprintf(esc_html__('Product Hover Style %s', 'ecomall'), $i)
		,'img' => $product_hover_image_folder . 'product_v'.$i.'.jpg'
	);
}

$loading_screen_options = array();
$loading_image_folder = get_template_directory_uri() . '/images/loading/';
for( $i = 1; $i <= 10; $i++ ){
	$loading_screen_options[$i] = array(
		'alt'  => sprintf(esc_html__('Loading Image %s', 'ecomall'), $i)
		,'img' => $loading_image_folder . 'loading_'.$i.'.svg'
	);
}

$footer_block_options = ecomall_get_footer_block_options();

$breadcrumb_layout_options = array();
$breadcrumb_image_folder = get_template_directory_uri() . '/admin/assets/images/breadcrumbs/';
for( $i = 1; $i <= 3; $i++ ){
	$breadcrumb_layout_options['v' . $i] = array(
		'alt'  => sprintf(esc_html__('Breadcrumb Layout %s', 'ecomall'), $i)
		,'img' => $breadcrumb_image_folder . 'breadcrumb_v'.$i.'.jpg'
	);
}

$sidebar_options = array();
$default_sidebars = ecomall_get_list_sidebars();
if( is_array($default_sidebars) ){
	foreach( $default_sidebars as $key => $_sidebar ){
		$sidebar_options[$_sidebar['id']] = $_sidebar['name'];
	}
}

$product_loading_image = get_template_directory_uri() . '/images/prod_loading.gif';

$mailchimp_forms = array();
$args = array(
	'post_type'			=> 'mc4wp-form'
	,'post_status'		=> 'publish'
	,'posts_per_page'	=> -1
);
$forms = new WP_Query( $args );
if( !empty( $forms->posts ) && is_array( $forms->posts ) ) {
	foreach( $forms->posts as $p ) {
		$mailchimp_forms[$p->ID] = $p->post_title;
	}
}

$option_fields = array();

/*** General Tab ***/
$option_fields['general'] = array(
	array(
		'id'        => 'section-logo-favicon'
		,'type'     => 'section'
		,'title'    => esc_html__( 'Logo - Favicon', 'ecomall' )
		,'subtitle' => ''
		,'indent'   => false
	)
	,array(
		'id'        => 'ts_logo'
		,'type'     => 'media'
		,'url'      => true
		,'title'    => esc_html__( 'Logo', 'ecomall' )
		,'desc'     => ''
		,'subtitle' => esc_html__( 'Select an image file for the main logo', 'ecomall' )
		,'readonly' => false
		,'default'  => array( 'url' => $logo_url )
	)
	,array(
		'id'        => 'ts_logo_mobile'
		,'type'     => 'media'
		,'url'      => true
		,'title'    => esc_html__( 'Mobile Logo', 'ecomall' )
		,'desc'     => ''
		,'subtitle' => esc_html__( 'Display this logo on mobile', 'ecomall' )
		,'readonly' => false
		,'default'  => array( 'url' => '' )
	)
	,array(
		'id'        => 'ts_logo_sticky'
		,'type'     => 'media'
		,'url'      => true
		,'title'    => esc_html__( 'Sticky Logo', 'ecomall' )
		,'desc'     => ''
		,'subtitle' => esc_html__( 'Display this logo on sticky header', 'ecomall' )
		,'readonly' => false
		,'default'  => array( 'url' => '' )
	)
	,array(
		'id'        => 'ts_logo_menu_mobile'
		,'type'     => 'media'
		,'url'      => true
		,'title'    => esc_html__( 'Mobile Menu Logo', 'ecomall' )
		,'desc'     => ''
		,'subtitle' => esc_html__( 'Display this logo on mobile menu', 'ecomall' )
		,'readonly' => false
		,'default'  => array( 'url' => '' )
	)
	,array(
		'id'        => 'ts_logo_width'
		,'type'     => 'text'
		,'url'      => true
		,'title'    => esc_html__( 'Logo Width', 'ecomall' )
		,'desc'     => ''
		,'subtitle' => esc_html__( 'Set width for logo (in pixels)', 'ecomall' )
		,'default'  => '160'
	)
	,array(
		'id'        => 'ts_device_logo_width'
		,'type'     => 'text'
		,'url'      => true
		,'title'    => esc_html__( 'Logo Width on Device', 'ecomall' )
		,'desc'     => ''
		,'subtitle' => esc_html__( 'Set width for logo (in pixels)', 'ecomall' )
		,'default'  => '135'
	)
	,array(
		'id'        => 'ts_favicon'
		,'type'     => 'media'
		,'url'      => true
		,'title'    => esc_html__( 'Favicon', 'ecomall' )
		,'desc'     => ''
		,'subtitle' => esc_html__( 'Select a PNG, GIF or ICO image', 'ecomall' )
		,'readonly' => false
		,'default'  => array( 'url' => $favicon_url )
	)
	,array(
		'id'        => 'ts_text_logo'
		,'type'     => 'text'
		,'title'    => esc_html__( 'Text Logo', 'ecomall' )
		,'subtitle' => ''
		,'desc'     => ''
		,'default'  => 'Ecomall'
	)

	,array(
		'id'        => 'section-layout-style'
		,'type'     => 'section'
		,'title'    => esc_html__( 'Layout Style', 'ecomall' )
		,'subtitle' => ''
		,'indent'   => false
	)
	,array(
		'id'        => 'ts_layout_fullwidth'
		,'type'     => 'switch'
		,'title'    => esc_html__( 'Layout Fullwidth', 'ecomall' )
		,'subtitle' => ''
		,'default'  => false
	)
	,array(
		'id'        => 'ts_header_layout_fullwidth'
		,'type'     => 'switch'
		,'title'    => esc_html__( 'Header Layout Fullwidth', 'ecomall' )
		,'subtitle' => ''
		,'default'  => false
		,'required'	=> array( 'ts_layout_fullwidth', 'equals', '1' )
	)
	,array(
		'id'        => 'ts_main_content_layout_fullwidth'
		,'type'     => 'switch'
		,'title'    => esc_html__( 'Main Content Layout Fullwidth', 'ecomall' )
		,'subtitle' => ''
		,'default'  => false
		,'required'	=> array( 'ts_layout_fullwidth', 'equals', '1' )
	)
	,array(
		'id'        => 'ts_footer_layout_fullwidth'
		,'type'     => 'switch'
		,'title'    => esc_html__( 'Footer Layout Fullwidth', 'ecomall' )
		,'subtitle' => ''
		,'default'  => false
		,'required'	=> array( 'ts_layout_fullwidth', 'equals', '1' )
	)
	,array(
		'id'       	=> 'ts_layout_style'
		,'type'     => 'select'
		,'title'    => esc_html__( 'Layout Style', 'ecomall' )
		,'subtitle' => esc_html__( 'You can override this option for the individual page', 'ecomall' )
		,'desc'     => ''
		,'options'  => array(
			'boxed' 	=> 'Boxed'
			,'wide' 	=> 'Wide'
		)
		,'default'  => 'wide'
		,'select2'	=> array('allowClear' => false, 'minimumResultsForSearch' => 'Infinity')
		,'required'	=> array( 'ts_layout_fullwidth', 'equals', '0' )
	)
	
	,array(
		'id'        => 'section-rtl'
		,'type'     => 'section'
		,'title'    => esc_html__( 'Right To Left', 'ecomall' )
		,'subtitle' => ''
		,'indent'   => false
	)
	,array(
		'id'        => 'ts_enable_rtl'
		,'type'     => 'switch'
		,'title'    => esc_html__( 'Enable Right To Left', 'ecomall' )
		,'subtitle' => ''
		,'default'  => false
	)
	
	,array(
		'id'        => 'section-smooth-scroll'
		,'type'     => 'section'
		,'title'    => esc_html__( 'Smooth Scroll', 'ecomall' )
		,'subtitle' => ''
		,'indent'   => false
	)
	,array(
		'id'        => 'ts_smooth_scroll'
		,'type'     => 'switch'
		,'title'    => esc_html__( 'Enable Smooth Scroll', 'ecomall' )
		,'subtitle' => ''
		,'default'  => false
	)
	
	,array(
		'id'        => 'section-back-to-top-button'
		,'type'     => 'section'
		,'title'    => esc_html__( 'Back To Top Button', 'ecomall' )
		,'subtitle' => ''
		,'indent'   => false
	)
	,array(
		'id'        => 'ts_back_to_top_button'
		,'type'     => 'switch'
		,'title'    => esc_html__( 'Enable Back To Top Button', 'ecomall' )
		,'subtitle' => ''
		,'default'  => true
	)
	,array(
		'id'        => 'ts_back_to_top_button_on_mobile'
		,'type'     => 'switch'
		,'title'    => esc_html__( 'Enable Back To Top Button On Mobile', 'ecomall' )
		,'subtitle' => ''
		,'default'  => true
	)
	
	,array(
		'id'        => 'section-slider-options'
		,'type'     => 'section'
		,'title'    => esc_html__( 'Slider Options', 'ecomall' )
		,'subtitle' => esc_html__( 'These options are used for sliders which are not added in post/page content. Ex: related products, related blogs, ...', 'ecomall' )
		,'indent'   => false
	)
	,array(
		'id'        => 'ts_slider_loop'
		,'type'     => 'switch'
		,'title'    => esc_html__( 'Slider Loop', 'ecomall' )
		,'subtitle' => ''
		,'default'  => true
	)
	,array(
		'id'        => 'ts_slider_autoplay'
		,'type'     => 'switch'
		,'title'    => esc_html__( 'Slider Autoplay', 'ecomall' )
		,'subtitle' => ''
		,'default'  => true
	)
	,array(
		'id'        => 'ts_slider_nav'
		,'type'     => 'switch'
		,'title'    => esc_html__( 'Slider Navigation', 'ecomall' )
		,'subtitle' => ''
		,'default'  => true
	)
	,array(
		'id'        => 'ts_slider_dots'
		,'type'     => 'switch'
		,'title'    => esc_html__( 'Slider Bullets', 'ecomall' )
		,'subtitle' => ''
		,'default'  => false
	)
	
	,array(
		'id'        => 'section-page-not-found'
		,'type'     => 'section'
		,'title'    => esc_html__( '404 Page', 'ecomall' )
		,'subtitle' => ''
		,'indent'   => false
	)
	,array( 
		'id'       	=> 'ts_404_page' 
		,'type'     => 'select' 
		,'title'    => esc_html__( '404 Page', 'ecomall' ) 
		,'subtitle' => esc_html__( 'Select the page which displays the 404 page', 'ecomall' ) 
		,'desc'     => ''
		,'data'     => 'pages'
		,'default'	=> ''
	)
	,array(
		'id'        => 'section-loading-screen'
		,'type'     => 'section'
		,'title'    => esc_html__( 'Loading Screen', 'ecomall' )
		,'subtitle' => ''
		,'indent'   => false
	)
	,array(
		'id'        => 'ts_loading_screen'
		,'type'     => 'switch'
		,'title'    => esc_html__( 'Loading Screen', 'ecomall' )
		,'subtitle' => ''
		,'default'  => false
	)
	,array(
		'id'        => 'ts_loading_image'
		,'type'     => 'image_select'
		,'title'    => esc_html__( 'Loading Image', 'ecomall' )
		,'subtitle' => ''
		,'desc'     => ''
		,'options'  => $loading_screen_options
		,'default'  => '1'
	)
	,array(
		'id'        => 'ts_custom_loading_image'
		,'type'     => 'media'
		,'url'      => true
		,'title'    => esc_html__( 'Custom Loading Image', 'ecomall' )
		,'desc'     => ''
		,'subtitle' => ''
		,'readonly' => false
		,'default'  => array( 'url' => '' )
	)
	,array(
		'id'       	=> 'ts_display_loading_screen_in'
		,'type'     => 'select'
		,'title'    => esc_html__( 'Display Loading Screen In', 'ecomall' )
		,'subtitle' => ''
		,'desc'     => ''
		,'options'  => array(
			'all-pages' 		=> esc_html__( 'All Pages', 'ecomall' )
			,'homepage-only' 	=> esc_html__( 'Homepage Only', 'ecomall' )
			,'specific-pages' 	=> esc_html__( 'Specific Pages', 'ecomall' )
		)
		,'default'  => 'all-pages'
		,'select2'	=> array('allowClear' => false, 'minimumResultsForSearch' => 'Infinity')
	)
	,array(
		'id'       	=> 'ts_loading_screen_exclude_pages'
		,'type'     => 'select'
		,'title'    => esc_html__( 'Exclude Pages', 'ecomall' )
		,'subtitle' => ''
		,'desc'     => ''
		,'data'     => 'pages'
		,'multi'    => true
		,'default'	=> ''
		,'required'	=> array( 'ts_display_loading_screen_in', 'equals', 'all-pages' )
	)
	,array(
		'id'       	=> 'ts_loading_screen_specific_pages'
		,'type'     => 'select'
		,'title'    => esc_html__( 'Specific Pages', 'ecomall' )
		,'subtitle' => ''
		,'desc'     => ''
		,'data'     => 'pages'
		,'multi'    => true
		,'default'	=> ''
		,'required'	=> array( 'ts_display_loading_screen_in', 'equals', 'specific-pages' )
	)
);

/*** Color Scheme Tab ***/
$option_fields['color-scheme'] = array(
	array(
		'id'          => 'ts_color_scheme'
		,'type'       => 'image_select'
		,'presets'    => true
		,'full_width' => false
		,'title'      => esc_html__( 'Select Color Scheme of Theme', 'ecomall' )
		,'subtitle'   => ''
		,'desc'       => ''
		,'options'    => $preset_colors_options
		,'default'    => 'default'
		,'class'      => ''
	)
	,array(
		'id'        => 'section-general-colors'
		,'type'     => 'section'
		,'title'    => esc_html__( 'Main Colors', 'ecomall' )
		,'subtitle' => ''
		,'indent'   => false
	)
	,array(
		'id'       => 'ts_primary_color'
		,'type'     => 'color_rgba'
		,'title'    => esc_html__( 'Primary Color', 'ecomall' )
		,'subtitle' => ''
		,'default'  => array(
			'color' 	=> '#0068c8'
			,'alpha'	=> 1
		)
	)
	,array(
		'id'       => 'ts_text_color_in_bg_primary'
		,'type'     => 'color_rgba'
		,'title'    => esc_html__( 'Text Color In Background Primary Color', 'ecomall' )
		,'subtitle' => ''
		,'default'  => array(
			'color' 	=> '#ffffff'
			,'alpha'	=> 1
		)
	)
	,array(
		'id'       => 'ts_main_content_background_color'
		,'type'     => 'color_rgba'
		,'title'    => esc_html__( 'Main Content Background Color', 'ecomall' )
		,'subtitle' => ''
		,'default'  => array(
			'color' 	=> '#ffffff'
			,'alpha'	=> 1
		)
	)
	,array(
		'id'       => 'ts_text_color'
		,'type'     => 'color_rgba'
		,'title'    => esc_html__( 'Text Color', 'ecomall' )
		,'subtitle' => ''
		,'default'  => array(
			'color' 	=> '#000000'
			,'alpha'	=> 1
		)
	)
	,array(
		'id'       => 'ts_heading_color'
		,'type'     => 'color_rgba'
		,'title'    => esc_html__( 'Heading Color', 'ecomall' )
		,'subtitle' => ''
		,'default'  => array(
			'color' 	=> '#000000'
			,'alpha'	=> 1
		)
	)
	,array(
		'id'       => 'ts_gray_color'
		,'type'     => 'color_rgba'
		,'title'    => esc_html__( 'Gray Text Color', 'ecomall' )
		,'subtitle' => ''
		,'default'  => array(
			'color' 	=> '#848484'
			,'alpha'	=> 1
		)
	)
	,array(
		'id'       => 'ts_hightlight_color'
		,'type'     => 'color_rgba'
		,'title'    => esc_html__( 'Hightlight Color', 'ecomall' )
		,'subtitle' => ''
		,'default'  => array(
			'color' 	=> '#fcc904'
			,'alpha'	=> 1
		)
	)
	,array(
		'id'       => 'ts_dropdown_bg_color'
		,'type'     => 'color_rgba'
		,'title'    => esc_html__( 'Dropdown/Sidebar Background Color', 'ecomall' )
		,'subtitle' => ''
		,'default'  => array(
			'color' 	=> '#ffffff'
			,'alpha'	=> 1
		)
	)
	,array(
		'id'       => 'ts_dropdown_color'
		,'type'     => 'color_rgba'
		,'title'    => esc_html__( 'Dropdown/Sidebar Text Color', 'ecomall' )
		,'subtitle' => ''
		,'default'  => array(
			'color' 	=> '#000000'
			,'alpha'	=> 1
		)
	)
	,array(
		'id'       => 'ts_border_color'
		,'type'     => 'color_rgba'
		,'title'    => esc_html__( 'Border Color', 'ecomall' )
		,'subtitle' => ''
		,'default'  => array(
			'color' 	=> '#ebebeb'
			,'alpha'	=> 1
		)
	)
	,array(
		'id'       => 'ts_link_color'
		,'type'     => 'color_rgba'
		,'title'    => esc_html__( 'Link Color', 'ecomall' )
		,'subtitle' => ''
		,'default'  => array(
			'color' 	=> '#0068c8'
			,'alpha'	=> 1
		)
	)
	,array(
		'id'       => 'ts_link_color_hover'
		,'type'     => 'color_rgba'
		,'title'    => esc_html__( 'Link Color Hover', 'ecomall' )
		,'subtitle' => ''
		,'default'  => array(
			'color' 	=> '#0068c8'
			,'alpha'	=> 1
		)
	)
	,array(
		'id'      => 'info-blockquote-colors'
		,'type'   => 'info'
		,'notice' => false
		,'title'  => esc_html__( 'Blockquote', 'ecomall' )
		,'desc'   => ''
	)
	,array(
		'id'       => 'ts_blockquote_bg'
		,'type'     => 'color_rgba'
		,'title'    => esc_html__( 'Blockquote Background Color', 'ecomall' )
		,'subtitle' => ''
		,'default'  => array(
			'color' 	=> '#e8f3fe'
			,'alpha'	=> 1
		)
	)
	,array(
		'id'       => 'ts_blockquote_color'
		,'type'     => 'color_rgba'
		,'title'    => esc_html__( 'Blockquote Text Color', 'ecomall' )
		,'subtitle' => ''
		,'default'  => array(
			'color' 	=> '#000000'
			,'alpha'	=> 1
		)
	)
	,array(
		'id'       => 'ts_blockquote_icon'
		,'type'     => 'color_rgba'
		,'title'    => esc_html__( 'Blockquote Icon Color', 'ecomall' )
		,'subtitle' => ''
		,'default'  => array(
			'color' 	=> '#000000'
			,'alpha'	=> 1
		)
	)
	,array(
		'id'      => 'info-tags-colors'
		,'type'   => 'info'
		,'notice' => false
		,'title'  => esc_html__( 'Tags', 'ecomall' )
		,'desc'   => ''
	)
	,array(
		'id'       => 'ts_tags_color'
		,'type'     => 'color_rgba'
		,'title'    => esc_html__( 'Tags Text Color', 'ecomall' )
		,'subtitle' => ''
		,'default'  => array(
			'color' 	=> '#848484'
			,'alpha'	=> 1
		)
	)
	,array(
		'id'       => 'ts_tags_bg_color'
		,'type'     => 'color_rgba'
		,'title'    => esc_html__( 'Tags Background Color', 'ecomall' )
		,'subtitle' => ''
		,'default'  => array(
			'color' 	=> '#ffffff'
			,'alpha'	=> 0
		)
	)
	,array(
		'id'       => 'ts_tags_border'
		,'type'     => 'color_rgba'
		,'title'    => esc_html__( 'Tags Border Color', 'ecomall' )
		,'subtitle' => ''
		,'default'  => array(
			'color' 	=> '#ebebeb'
			,'alpha'	=> 1
		)
	)
	,array(
		'id'      => 'info-input-colors'
		,'type'   => 'info'
		,'notice' => false
		,'title'  => esc_html__( 'Input', 'ecomall' )
		,'desc'   => ''
	)
	,array(
		'id'       => 'ts_input_color'
		,'type'     => 'color_rgba'
		,'title'    => esc_html__( 'Input Text Color', 'ecomall' )
		,'subtitle' => ''
		,'default'  => array(
			'color' 	=> '#000000'
			,'alpha'	=> 1
		)
	)
	,array(
		'id'       => 'ts_input_bg'
		,'type'     => 'color_rgba'
		,'title'    => esc_html__( 'Input Background Color', 'ecomall' )
		,'subtitle' => ''
		,'default'  => array(
			'color' 	=> '#ffffff'
			,'alpha'	=> 1
		)
	)
	,array(
		'id'       => 'ts_input_border'
		,'type'     => 'color_rgba'
		,'title'    => esc_html__( 'Input Border Color', 'ecomall' )
		,'subtitle' => ''
		,'default'  => array(
			'color' 	=> '#ebebeb'
			,'alpha'	=> 1
		)
	)
	,array(
		'id'        => 'section-buttons-color'
		,'type'     => 'section'
		,'title'    => esc_html__( 'Buttons Color', 'ecomall' )
		,'subtitle' => ''
		,'indent'   => false
	)
	,array(
		'id'       => 'ts_btn_color'
		,'type'     => 'color_rgba'
		,'title'    => esc_html__( 'Button Text Color', 'ecomall' )
		,'subtitle' => ''
		,'default'  => array(
			'color' 	=> '#ffffff'
			,'alpha'	=> 1
		)
	)
	,array(
		'id'       => 'ts_btn_bg'
		,'type'     => 'color_rgba'
		,'title'    => esc_html__( 'Button Background Color', 'ecomall' )
		,'subtitle' => ''
		,'default'  => array(
			'color' 	=> '#0068c8'
			,'alpha'	=> 1
		)
	)
	,array(
		'id'       => 'ts_btn_border'
		,'type'     => 'color_rgba'
		,'title'    => esc_html__( 'Button Border Color', 'ecomall' )
		,'subtitle' => ''
		,'default'  => array(
			'color' 	=> '#0068c8'
			,'alpha'	=> 1
		)
	)
	,array(
		'id'       => 'ts_btn_hover_color'
		,'type'     => 'color_rgba'
		,'title'    => esc_html__( 'Button Text Hover Color', 'ecomall' )
		,'subtitle' => ''
		,'default'  => array(
			'color' 	=> '#0068c8'
			,'alpha'	=> 1
		)
	)
	,array(
		'id'       => 'ts_btn_hover_bg'
		,'type'     => 'color_rgba'
		,'title'    => esc_html__( 'Button Background Hover Color', 'ecomall' )
		,'subtitle' => ''
		,'default'  => array(
			'color' 	=> '#ffffff'
			,'alpha'	=> 0
		)
	)
	,array(
		'id'       => 'ts_btn_hover_border'
		,'type'     => 'color_rgba'
		,'title'    => esc_html__( 'Button Border Hover Color', 'ecomall' )
		,'subtitle' => ''
		,'default'  => array(
			'color' 	=> '#0068c8'
			,'alpha'	=> 1
		)
	)
	,array(
		'id'      => 'info-special-button'
		,'type'   => 'info'
		,'notice' => false
		,'title'  => esc_html__( 'Special Button', 'ecomall' )
		,'desc'   => ''
	)
	,array(
		'id'       => 'ts_btn_special_color'
		,'type'     => 'color_rgba'
		,'title'    => esc_html__( 'Button Special Text Color', 'ecomall' )
		,'subtitle' => ''
		,'default'  => array(
			'color' 	=> '#000000'
			,'alpha'	=> 1
		)
	)
	,array(
		'id'       => 'ts_btn_special_bg'
		,'type'     => 'color_rgba'
		,'title'    => esc_html__( 'Button Special Background Color', 'ecomall' )
		,'subtitle' => ''
		,'default'  => array(
			'color' 	=> '#e4f2ff'
			,'alpha'	=> 1
		)
	)
	,array(
		'id'       => 'ts_btn_special_border'
		,'type'     => 'color_rgba'
		,'title'    => esc_html__( 'Button Special Border Color', 'ecomall' )
		,'subtitle' => ''
		,'default'  => array(
			'color' 	=> '#e4f2ff'
			,'alpha'	=> 1
		)
	)
	,array(
		'id'       => 'ts_btn_special_hover_color'
		,'type'     => 'color_rgba'
		,'title'    => esc_html__( 'Button Special Text Hover Color', 'ecomall' )
		,'subtitle' => ''
		,'default'  => array(
			'color' 	=> '#000000'
			,'alpha'	=> 1
		)
	)
	,array(
		'id'       => 'ts_btn_special_hover_bg'
		,'type'     => 'color_rgba'
		,'title'    => esc_html__( 'Button Special Background Hover Color', 'ecomall' )
		,'subtitle' => ''
		,'default'  => array(
			'color' 	=> '#ffffff'
			,'alpha'	=> 0
		)
	)
	,array(
		'id'       => 'ts_btn_special_hover_border'
		,'type'     => 'color_rgba'
		,'title'    => esc_html__( 'Button Special Border Hover Color', 'ecomall' )
		,'subtitle' => ''
		,'default'  => array(
			'color' 	=> '#0068c8'
			,'alpha'	=> 1
		)
	)
	,array(
		'id'      => 'info-button-thumbnails-colors'
		,'type'   => 'info'
		,'notice' => false
		,'title'  => esc_html__( 'Buttons Icon On Product Thumbnail', 'ecomall' )
		,'desc'   => ''
	)
	,array(
		'id'       => 'ts_btn_thumb_color'
		,'type'     => 'color_rgba'
		,'title'    => esc_html__( 'Button Thumbnail Text Color', 'ecomall' )
		,'subtitle' => ''
		,'default'  => array(
			'color' 	=> '#000000'
			,'alpha'	=> 1
		)
	)
	,array(
		'id'       => 'ts_btn_thumb_bg'
		,'type'     => 'color_rgba'
		,'title'    => esc_html__( 'Button Thumbnail Background Color', 'ecomall' )
		,'subtitle' => ''
		,'default'  => array(
			'color' 	=> '#ffffff'
			,'alpha'	=> 1
		)
	)
	,array(
		'id'       => 'ts_btn_thumb_border'
		,'type'     => 'color_rgba'
		,'title'    => esc_html__( 'Button Thumbnail Border Color', 'ecomall' )
		,'subtitle' => ''
		,'default'  => array(
			'color' 	=> '#ebebeb'
			,'alpha'	=> 1
		)
	)
	,array(
		'id'       => 'ts_btn_thumb_hover_color'
		,'type'     => 'color_rgba'
		,'title'    => esc_html__( 'Button Thumbnail Text Hover Color', 'ecomall' )
		,'subtitle' => ''
		,'default'  => array(
			'color' 	=> '#000000'
			,'alpha'	=> 1
		)
	)
	,array(
		'id'       => 'ts_btn_thumb_hover_bg'
		,'type'     => 'color_rgba'
		,'title'    => esc_html__( 'Button Thumbnail Background Hover Color', 'ecomall' )
		,'subtitle' => ''
		,'default'  => array(
			'color' 	=> '#e8f3fe'
			,'alpha'	=> 0
		)
	)
	,array(
		'id'       => 'ts_btn_thumb_hover_border'
		,'type'     => 'color_rgba'
		,'title'    => esc_html__( 'Button Thumbnail Border Hover Color', 'ecomall' )
		,'subtitle' => ''
		,'default'  => array(
			'color' 	=> '#e8f3fe'
			,'alpha'	=> 1
		)
	)
	,array(
		'id'        => 'section-product-colors'
		,'type'     => 'section'
		,'title'    => esc_html__( 'Product', 'ecomall' )
		,'subtitle' => ''
		,'indent'   => false
	)
	,array(
		'id'       => 'ts_product_bg_color'
		,'type'     => 'color_rgba'
		,'title'    => esc_html__( 'Product Brand Background Color', 'ecomall' )
		,'subtitle' => ''
		,'default'  => array(
			'color' 	=> '#ffffff'
			,'alpha'	=> 1
		)
	)
	,array(
		'id'       => 'ts_brand_bg_color'
		,'type'     => 'color_rgba'
		,'title'    => esc_html__( 'Product Brand Background Color', 'ecomall' )
		,'subtitle' => ''
		,'default'  => array(
			'color' 	=> '#f4f4f4'
			,'alpha'	=> 1
		)
	)
	,array(
		'id'      => 'info-product-price-color'
		,'type'   => 'info'
		,'notice' => false
		,'title'  => esc_html__( 'Price', 'ecomall' )
		,'desc'   => ''
	)
	,array(
		'id'       => 'ts_price_color'
		,'type'     => 'color_rgba'
		,'title'    => esc_html__( 'Product Price Color', 'ecomall' )
		,'subtitle' => ''
		,'default'  => array(
			'color' 	=> '#dd2831'
			,'alpha'	=> 1
		)
	)
	,array(
		'id'       => 'ts_sale_price_color'
		,'type'     => 'color_rgba'
		,'title'    => esc_html__( 'Product Price Sale Color', 'ecomall' )
		,'subtitle' => ''
		,'default'  => array(
			'color' 	=> '#848484'
			,'alpha'	=> 1
		)
	)
	,array(
		'id'      => 'info-product-rating-color'
		,'type'   => 'info'
		,'notice' => false
		,'title'  => esc_html__( 'Rating', 'ecomall' )
		,'desc'   => ''
	)
	,array(
		'id'       => 'ts_rating_color'
		,'type'     => 'color_rgba'
		,'title'    => esc_html__( 'Product Rating Color', 'ecomall' )
		,'subtitle' => ''
		,'default'  => array(
			'color' 	=> '#c3c3c3'
			,'alpha'	=> 1
		)
	)
	,array(
		'id'       => 'ts_rated_color'
		,'type'     => 'color_rgba'
		,'title'    => esc_html__( 'Product Reated Color', 'ecomall' )
		,'subtitle' => ''
		,'default'  => array(
			'color' 	=> '#fdc904'
			,'alpha'	=> 1
		)
	)
	,array(
		'id'      => 'info-product-label-color'
		,'type'   => 'info'
		,'notice' => false
		,'title'  => esc_html__( 'Product Label', 'ecomall' )
		,'desc'   => ''
	)
	,array(
		'id'       => 'ts_product_sale_label_text_color'
		,'type'     => 'color_rgba'
		,'title'    => esc_html__( 'Sale Label Text Color', 'ecomall' )
		,'subtitle' => ''
		,'default'  => array(
			'color' 	=> '#ffffff'
			,'alpha'	=> 1
		)
	)
	,array(
		'id'       => 'ts_product_sale_label_background_color'
		,'type'     => 'color_rgba'
		,'title'    => esc_html__( 'Sale Label Background Color', 'ecomall' )
		,'subtitle' => ''
		,'default'  => array(
			'color' 	=> '#000000'
			,'alpha'	=> 1
		)
	)
	,array(
		'id'       => 'ts_product_new_label_text_color'
		,'type'     => 'color_rgba'
		,'title'    => esc_html__( 'New Label Text Color', 'ecomall' )
		,'subtitle' => ''
		,'default'  => array(
			'color' 	=> '#ffffff'
			,'alpha'	=> 1
		)
	)
	,array(
		'id'       => 'ts_product_new_label_background_color'
		,'type'     => 'color_rgba'
		,'title'    => esc_html__( 'New Label Background Color', 'ecomall' )
		,'subtitle' => ''
		,'default'  => array(
			'color' 	=> '#ffa632'
			,'alpha'	=> 1
		)
	)
	,array(
		'id'       => 'ts_product_feature_label_text_color'
		,'type'     => 'color_rgba'
		,'title'    => esc_html__( 'Feature Label Text Color', 'ecomall' )
		,'subtitle' => ''
		,'default'  => array(
			'color' 	=> '#ffffff'
			,'alpha'	=> 1
		)
	)
	,array(
		'id'       => 'ts_product_feature_label_background_color'
		,'type'     => 'color_rgba'
		,'title'    => esc_html__( 'Feature Label Background Color', 'ecomall' )
		,'subtitle' => ''
		,'default'  => array(
			'color' 	=> '#dd2831'
			,'alpha'	=> 1
		)
	)
	,array(
		'id'       => 'ts_product_outstock_label_text_color'
		,'type'     => 'color_rgba'
		,'title'    => esc_html__( 'OutStock Label Text Color', 'ecomall' )
		,'subtitle' => ''
		,'default'  => array(
			'color' 	=> '#ffffff'
			,'alpha'	=> 1
		)
	)
	,array(
		'id'       => 'ts_product_outstock_label_background_color'
		,'type'     => 'color_rgba'
		,'title'    => esc_html__( 'OutStock Label Background Color', 'ecomall' )
		,'subtitle' => ''
		,'default'  => array(
			'color' 	=> '#919191'
			,'alpha'	=> 1
		)
	)
	,array(
		'id'      => 'info-breadcrumb-colors'
		,'type'   => 'info'
		,'notice' => false
		,'title'  => esc_html__( 'Breadcrumb Colors', 'ecomall' )
		,'desc'   => ''
	)
	,array(
		'id'       => 'ts_breadcrumb_background_color'
		,'type'     => 'color_rgba'
		,'title'    => esc_html__( 'Breadcrumb Background Color', 'ecomall' )
		,'subtitle' => ''
		,'default'  => array(
			'color' 	=> '#f4f4f4'
			,'alpha'	=> 1
		)
	)
	,array(
		'id'       => 'ts_breadcrumb_text_color'
		,'type'     => 'color_rgba'
		,'title'    => esc_html__( 'Breadcrumb Text Color', 'ecomall' )
		,'subtitle' => ''
		,'default'  => array(
			'color' 	=> '#000000'
			,'alpha'	=> 1
		)
	)
	,array(
		'id'       => 'ts_breadcrumb_link_color'
		,'type'     => 'color_rgba'
		,'title'    => esc_html__( 'Breadcrumb Link Color', 'ecomall' )
		,'subtitle' => ''
		,'default'  => array(
			'color' 	=> '#848484'
			,'alpha'	=> 1
		)
	)
	,array(
		'id'       => 'ts_breadcrumb_v3_text_color'
		,'type'     => 'color_rgba'
		,'title'    => esc_html__( 'Breadcrumb v3 Text Color', 'ecomall' )
		,'subtitle' => ''
		,'default'  => array(
			'color' 	=> '#ffffff'
			,'alpha'	=> 1
		)
	)
	,array(
		'id'       => 'ts_breadcrumb_v3_link_color'
		,'type'     => 'color_rgba'
		,'title'    => esc_html__( 'Breadcrumb v3 Link Color', 'ecomall' )
		,'subtitle' => ''
		,'default'  => array(
			'color' 	=> '#ffffff'
			,'alpha'	=> 1
		)
	)
	,array(
		'id'        => 'section-header-colors'
		,'type'     => 'section'
		,'title'    => esc_html__( 'HEADER', 'ecomall' )
		,'subtitle' => ''
		,'indent'   => false
	)
	,array(
		'id'       => 'ts_header_cart_count_background_color'
		,'type'     => 'color_rgba'
		,'title'    => esc_html__( 'Cart/Wishlist Count Number Background Color', 'ecomall' )
		,'subtitle' => ''
		,'default'  => array(
			'color' 	=> '#0068c8'
			,'alpha'	=> 1
		)
	)
	,array(
		'id'       => 'ts_header_cart_count_text_color'
		,'type'     => 'color_rgba'
		,'title'    => esc_html__( 'Cart/Wishlist Count Number Text Color', 'ecomall' )
		,'subtitle' => ''
		,'default'  => array(
			'color' 	=> '#ffffff'
			,'alpha'	=> 1
		)
	)
	,array(
		'id'      => 'info-hd-top-colors'
		,'type'   => 'info'
		,'notice' => false
		,'title'  => esc_html__( 'Header Top', 'ecomall' )
		,'desc'   => ''
	)
	,array(
		'id'       => 'ts_header_top_background_color'
		,'type'     => 'color_rgba'
		,'title'    => esc_html__( 'Header Top Background Color', 'ecomall' )
		,'subtitle' => ''
		,'default'  => array(
			'color' 	=> '#1b1b1b'
			,'alpha'	=> 1
		)
	)
	,array(
		'id'       => 'ts_header_top_text_color'
		,'type'     => 'color_rgba'
		,'title'    => esc_html__( 'Header Top Text Color', 'ecomall' )
		,'subtitle' => ''
		,'default'  => array(
			'color' 	=> '#ffffff'
			,'alpha'	=> 1
		)
	)
	,array(
		'id'       => 'ts_header_top_border_color'
		,'type'     => 'color_rgba'
		,'title'    => esc_html__( 'Header Top Border Color', 'ecomall' )
		,'subtitle' => esc_html__( 'Only available on some header layouts', 'ecomall' )
		,'default'  => array(
			'color' 	=> '#1b1b1b'
			,'alpha'	=> 1
		)
	)
	,array(
		'id'       => 'ts_header_top_link_hover_color'
		,'type'     => 'color_rgba'
		,'title'    => esc_html__( 'Header Top Link Hover Color', 'ecomall' )
		,'subtitle' => ''
		,'default'  => array(
			'color' 	=> '#0068c8'
			,'alpha'	=> 1
		)
	)
	,array(
		'id'      => 'info-hd-middle-colors'
		,'type'   => 'info'
		,'notice' => false
		,'title'  => esc_html__( 'Header Middle', 'ecomall' )
		,'desc'   => ''
	)
	,array(
		'id'       => 'ts_header_middle_background_color'
		,'type'     => 'color_rgba'
		,'title'    => esc_html__( 'Header Middle Background Color', 'ecomall' )
		,'subtitle' => ''
		,'default'  => array(
			'color' 	=> '#ffffff'
			,'alpha'	=> 1
		)
	)
	,array(
		'id'       => 'ts_header_middle_text_color'
		,'type'     => 'color_rgba'
		,'title'    => esc_html__( 'Header Middle Text Color', 'ecomall' )
		,'subtitle' => ''
		,'default'  => array(
			'color' 	=> '#000000'
			,'alpha'	=> 1
		)
	)
	,array(
		'id'       => 'ts_header_middle_border_color'
		,'type'     => 'color_rgba'
		,'title'    => esc_html__( 'Header Middle Border Color', 'ecomall' )
		,'subtitle' => esc_html__( 'Only available on some header layouts', 'ecomall' )
		,'default'  => array(
			'color' 	=> '#ebebeb'
			,'alpha'	=> 1
		)
	)
	,array(
		'id'       => 'ts_header_middle_link_hover_color'
		,'type'     => 'color_rgba'
		,'title'    => esc_html__( 'Header Middle Link Hover Color', 'ecomall' )
		,'subtitle' => ''
		,'default'  => array(
			'color' 	=> '#0068c8'
			,'alpha'	=> 1
		)
	)
	,array(
		'id'      => 'info-hd-bottom-colors'
		,'type'   => 'info'
		,'notice' => false
		,'title'  => esc_html__( 'Header Bottom', 'ecomall' )
		,'desc'   => ''
	)
	,array(
		'id'       => 'ts_header_bottom_background_color'
		,'type'     => 'color_rgba'
		,'title'    => esc_html__( 'Header Bottom Background Color', 'ecomall' )
		,'subtitle' => ''
		,'default'  => array(
			'color' 	=> '#ffffff'
			,'alpha'	=> 1
		)
	)
	,array(
		'id'       => 'ts_header_bottom_text_color'
		,'type'     => 'color_rgba'
		,'title'    => esc_html__( 'Header Bottom Text Color', 'ecomall' )
		,'subtitle' => ''
		,'default'  => array(
			'color' 	=> '#000000'
			,'alpha'	=> 1
		)
	)
	,array(
		'id'       => 'ts_header_bottom_border_color'
		,'type'     => 'color_rgba'
		,'title'    => esc_html__( 'Header Bottom Border Color', 'ecomall' )
		,'subtitle' => esc_html__( 'Only available on some header layouts', 'ecomall' )
		,'default'  => array(
			'color' 	=> '#ebebeb'
			,'alpha'	=> 1
		)
	)
	,array(
		'id'       => 'ts_header_bottom_link_hover_color'
		,'type'     => 'color_rgba'
		,'title'    => esc_html__( 'Header Bottom Link Hover Color', 'ecomall' )
		,'subtitle' => ''
		,'default'  => array(
			'color' 	=> '#0068c8'
			,'alpha'	=> 1
		)
	)
	,array(
		'id'        => 'section-footer-colors'
		,'type'     => 'section'
		,'title'    => esc_html__( 'Footer Colors', 'ecomall' )
		,'subtitle' => ''
		,'indent'   => false
	)
	,array(
		'id'       => 'ts_footer_background_color'
		,'type'     => 'color_rgba'
		,'title'    => esc_html__( 'Footer Background Color', 'ecomall' )
		,'subtitle' => ''
		,'default'  => array(
			'color' 	=> '#ffffff'
			,'alpha'	=> 1
		)
	)
	,array(
		'id'       => 'ts_footer_text_color'
		,'type'     => 'color_rgba'
		,'title'    => esc_html__( 'Footer Text Color', 'ecomall' )
		,'subtitle' => ''
		,'default'  => array(
			'color' 	=> '#000000'
			,'alpha'	=> 1
		)
	)
	,array(
		'id'       => 'ts_footer_heading_color'
		,'type'     => 'color_rgba'
		,'title'    => esc_html__( 'Footer Heading Color', 'ecomall' )
		,'subtitle' => ''
		,'default'  => array(
			'color' 	=> '#000000'
			,'alpha'	=> 1
		)
	)
	,array(
		'id'       => 'ts_footer_link_color'
		,'type'     => 'color_rgba'
		,'title'    => esc_html__( 'Footer Link Hover Color', 'ecomall' )
		,'subtitle' => ''
		,'default'  => array(
			'color' 	=> '#dd2831'
			,'alpha'	=> 1
		)
	)
	,array(
		'id'       => 'ts_footer_link_hover_color'
		,'type'     => 'color_rgba'
		,'title'    => esc_html__( 'Footer Link Hover Color', 'ecomall' )
		,'subtitle' => ''
		,'default'  => array(
			'color' 	=> '#0068c8'
			,'alpha'	=> 1
		)
	)
);

/*** Typography Tab ***/
$option_fields['typography'] = array(
	array(
		'id'        => 'section-fonts'
		,'type'     => 'section'
		,'title'    => esc_html__( 'FONT FAMILY', 'ecomall' )
		,'subtitle' => ''
		,'indent'   => false
	)
	,array(
		'id'       			=> 'ts_body_font'
		,'type'     		=> 'typography'
		,'title'    		=> esc_html__( 'Body Font', 'ecomall' )
		,'subtitle' 		=> ''
		,'units'			=> 'em'
		,'google'   		=> true
		,'font-style'   	=> false
		,'text-align'   	=> false
		,'color'   			=> false
		,'font-size'  		=> false
		,'letter-spacing' 	=> true
		,'all_styles'   	=> true
		,'preview'			=> array('always_display' => true)
		,'default'  		=> array(
			'font-family'  		=> 'Mulish'
			,'font-weight' 		=> '500'
			,'line-height' 		=> '1.6em'
			,'letter-spacing' 	=> '0.025em'
			,'google'	   		=> true
		)
		,'fonts'	=> $family_fonts
		,'select2'	=> array('allowClear' => false, 'minimumResultsForSearch' => 20)
	)
	,array(
		'id'       			=> 'ts_heading_font'
		,'type'     		=> 'typography'
		,'title'    		=> esc_html__( 'Heading Font', 'ecomall' )
		,'subtitle' 		=> ''
		,'units'			=> 'em'
		,'google'   		=> true
		,'font-style'   	=> false
		,'text-align'   	=> false
		,'color'   			=> false
		,'font-size'  		=> false
		,'letter-spacing' 	=> true
		,'all_styles'   	=> true
		,'preview'			=> array('always_display' => true)
		,'default'  		=> array(
			'font-family'  		=> 'Mulish'
			,'font-weight' 		=> '700'
			,'line-height' 		=> '1.2em'
			,'letter-spacing' 	=> '0.025em'
			,'google'	   		=> true
		)
		,'fonts'	=> $family_fonts
		,'select2'	=> array('allowClear' => false, 'minimumResultsForSearch' => 20)
	)
	,array(
		'id'       			=> 'ts_button_font'
		,'type'     		=> 'typography'
		,'title'    		=> esc_html__( 'Button Font', 'ecomall' )
		,'subtitle' 		=> ''
		,'units'			=> 'em'
		,'google'   		=> true
		,'font-style'   	=> false
		,'text-align'   	=> false
		,'color'   			=> false
		,'font-size'  		=> false
		,'letter-spacing' 	=> true
		,'all_styles'   	=> true
		,'preview'			=> array('always_display' => true)
		,'default'  		=> array(
			'font-family'  		=> 'Mulish'
			,'font-weight' 		=> '800'
			,'line-height' 		=> '1em'
			,'letter-spacing' 	=> '0.05em'
			,'google'	   		=> true
		)
		,'fonts'	=> $family_fonts
		,'select2'	=> array('allowClear' => false, 'minimumResultsForSearch' => 20)
	)
	,array(
		'id'       			=> 'ts_menu_font'
		,'type'     		=> 'typography'
		,'title'    		=> esc_html__( 'Menu Font', 'ecomall' )
		,'subtitle' 		=> ''
		,'units'			=> 'em'
		,'google'   		=> true
		,'font-style'   	=> false
		,'text-align'   	=> false
		,'color'   			=> false
		,'font-size'  		=> false
		,'letter-spacing' 	=> true
		,'line-height' 		=> false
		,'all_styles'   	=> true
		,'preview'			=> array('always_display' => true)
		,'default'  		=> array(
			'font-family'  		=> 'Mulish'
			,'font-weight' 		=> '800'
			,'letter-spacing' 	=> '0.05em'
			,'google'	   		=> true
		)
		,'fonts'	=> $family_fonts
		,'select2'	=> array('allowClear' => false, 'minimumResultsForSearch' => 20)
	)
	,array(
		'id'       			=> 'ts_vertical_menu_font'
		,'type'     		=> 'typography'
		,'title'    		=> esc_html__( 'Vertical Menu Font', 'ecomall' )
		,'subtitle' 		=> ''
		,'units'			=> 'em'
		,'google'   		=> true
		,'font-style'   	=> false
		,'text-align'   	=> false
		,'color'   			=> false
		,'font-size'  		=> false
		,'letter-spacing' 	=> true
		,'line-height' 		=> false
		,'all_styles'   	=> true
		,'preview'			=> array('always_display' => true)
		,'default'  		=> array(
			'font-family'  		=> 'Mulish'
			,'font-weight' 		=> '700'
			,'letter-spacing' 	=> '0.05em'
			,'google'	   		=> true
		)
		,'fonts'	=> $family_fonts
		,'select2'	=> array('allowClear' => false, 'minimumResultsForSearch' => 20)
	)
	,array(
		'id'        => 'section-font-size'
		,'type'     => 'section'
		,'title'    => esc_html__( 'FONT SIZE', 'ecomall' )
		,'subtitle' => ''
		,'indent'   => false
	)
	,array(
		'id'       			=> 'ts_fs_body_font'
		,'type'     		=> 'typography'
		,'title'    		=> esc_html__( 'Body', 'ecomall' )
		,'subtitle' 		=> ''
		,'class' 			=> 'typography-no-preview'
		,'google'   		=> false
		,'font-family'  	=> false
		,'font-weight'  	=> false
		,'font-style'   	=> false
		,'letter-spacing' 	=> false
		,'line-height' 		=> false
		,'text-align'  	 	=> false
		,'color'   			=> false
		,'preview'			=> array('always_display' => false)
		,'default'  		=> array(
			'font-family'  		=> ''
			,'font-weight' 		=> ''
			,'font-size'   		=> '15px'
			,'google'	   		=> false
		)
	)
	,array(
		'id'       			=> 'ts_fs_menu_font'
		,'type'     		=> 'typography'
		,'title'    		=> esc_html__( 'Menu', 'ecomall' )
		,'subtitle' 		=> ''
		,'class' 			=> 'typography-no-preview'
		,'google'   		=> false
		,'font-family'  	=> false
		,'font-weight'  	=> false
		,'font-style'   	=> false
		,'letter-spacing' 	=> false
		,'line-height' 		=> false
		,'text-align'  	 	=> false
		,'color'   			=> false
		,'preview'			=> array('always_display' => false)
		,'default'  		=> array(
			'font-family'  		=> ''
			,'font-weight' 		=> ''
			,'font-size'   		=> '15px'
			,'google'	   		=> false
		)
	)
	,array(
		'id'       			=> 'ts_fs_vertical_menu_font'
		,'type'     		=> 'typography'
		,'title'    		=> esc_html__( 'Vertical Menu', 'ecomall' )
		,'subtitle' 		=> ''
		,'class' 			=> 'typography-no-preview'
		,'google'   		=> false
		,'font-family'  	=> false
		,'font-weight'  	=> false
		,'font-style'   	=> false
		,'letter-spacing' 	=> false
		,'line-height' 		=> false
		,'text-align'  	 	=> false
		,'color'   			=> false
		,'preview'			=> array('always_display' => false)
		,'default'  		=> array(
			'font-family'  		=> ''
			,'font-weight' 		=> ''
			,'font-size'   		=> '15px'
			,'google'	   		=> false
		)
	)
	,array(
		'id'       			=> 'ts_fs_button_font'
		,'type'     		=> 'typography'
		,'title'    		=> esc_html__( 'Button', 'ecomall' )
		,'subtitle' 		=> ''
		,'class' 			=> 'typography-no-preview'
		,'google'   		=> false
		,'font-family'  	=> false
		,'font-weight'  	=> false
		,'font-style'   	=> false
		,'letter-spacing' 	=> false
		,'line-height' 		=> false
		,'text-align'  	 	=> false
		,'color'   			=> false
		,'preview'			=> array('always_display' => false)
		,'default'  		=> array(
			'font-family'  		=> 'Mulish'
			,'font-weight' 		=> ''
			,'font-size'   		=> '14px'
			,'google'	   		=> false
		)
	)
	,array(
		'id'       			=> 'ts_h1_font'
		,'type'     		=> 'typography'
		,'title'    		=> esc_html__( 'H1', 'ecomall' )
		,'subtitle' 		=> ''
		,'class' 			=> 'typography-no-preview'
		,'google'   		=> false
		,'font-family'  	=> false
		,'font-weight'  	=> false
		,'font-style'   	=> false
		,'letter-spacing' 	=> false
		,'line-height' 		=> false
		,'text-align'  	 	=> false
		,'color'   			=> false
		,'preview'			=> array('always_display' => false)
		,'default'  		=> array(
			'font-family'  		=> ''
			,'font-weight' 		=> ''
			,'font-size'   		=> '48px'
			,'google'	   		=> false
		)
	)
	,array(
		'id'       			=> 'ts_h2_font'
		,'type'     		=> 'typography'
		,'title'    		=> esc_html__( 'H2', 'ecomall' )
		,'subtitle' 		=> ''
		,'class' 			=> 'typography-no-preview'
		,'google'   		=> false
		,'font-family'  	=> false
		,'font-weight'  	=> false
		,'font-style'   	=> false
		,'letter-spacing' 	=> false
		,'line-height' 		=> false
		,'text-align'  	 	=> false
		,'color'   			=> false
		,'preview'			=> array('always_display' => false)
		,'default'  		=> array(
			'font-family'  		=> ''
			,'font-weight' 		=> ''
			,'font-size'   		=> '35px'
			,'google'	   		=> false
		)
	)
	,array(
		'id'       			=> 'ts_h3_font'
		,'type'     		=> 'typography'
		,'title'    		=> esc_html__( 'H3', 'ecomall' )
		,'subtitle' 		=> ''
		,'class' 			=> 'typography-no-preview'
		,'google'   		=> false
		,'font-family'  	=> false
		,'font-weight'  	=> false
		,'font-style'   	=> false
		,'letter-spacing' 	=> false
		,'line-height' 		=> false
		,'text-align'  	 	=> false
		,'color'   			=> false
		,'preview'			=> array('always_display' => false)
		,'default'  		=> array(
			'font-family'  		=> ''
			,'font-weight' 		=> ''
			,'font-size'   		=> '30px'
			,'google'	   		=> false
		)
	)
	,array(
		'id'       			=> 'ts_h4_font'
		,'type'     		=> 'typography'
		,'title'    		=> esc_html__( 'H4', 'ecomall' )
		,'subtitle' 		=> ''
		,'class' 			=> 'typography-no-preview'
		,'google'   		=> false
		,'font-family'  	=> false
		,'font-weight'  	=> false
		,'font-style'   	=> false
		,'letter-spacing' 	=> false
		,'line-height' 		=> false
		,'text-align'  	 	=> false
		,'color'   			=> false
		,'preview'			=> array('always_display' => false)
		,'default'  		=> array(
			'font-family'  		=> ''
			,'font-weight' 		=> ''
			,'font-size'   		=> '24px'
			,'google'	   		=> false
		)
	)
	,array(
		'id'       			=> 'ts_h5_font'
		,'type'     		=> 'typography'
		,'title'    		=> esc_html__( 'H5', 'ecomall' )
		,'subtitle' 		=> ''
		,'class' 			=> 'typography-no-preview'
		,'google'   		=> false
		,'font-family'  	=> false
		,'font-weight'  	=> false
		,'font-style'   	=> false
		,'letter-spacing' 	=> false
		,'line-height' 		=> false
		,'text-align'  	 	=> false
		,'color'   			=> false
		,'preview'			=> array('always_display' => false)
		,'default'  		=> array(
			'font-family'  		=> ''
			,'font-weight' 		=> ''
			,'font-size'   		=> '20px'
			,'google'	   		=> false
		)
	)
	,array(
		'id'       			=> 'ts_h6_font'
		,'type'     		=> 'typography'
		,'title'    		=> esc_html__( 'H6', 'ecomall' )
		,'subtitle' 		=> ''
		,'class' 			=> 'typography-no-preview'
		,'google'   		=> false
		,'font-family'  	=> false
		,'font-weight'  	=> false
		,'font-style'   	=> false
		,'letter-spacing' 	=> false
		,'line-height' 		=> false
		,'text-align'  	 	=> false
		,'color'   			=> false
		,'preview'			=> array('always_display' => false)
		,'default'  		=> array(
			'font-family'  		=> ''
			,'font-weight' 		=> ''
			,'font-size'   		=> '18px'
			,'google'	   		=> false
		)
	)
	,array(
		'id'        => 'section-font-sizes-responsive'
		,'type'     => 'section'
		,'title'    => esc_html__( 'RESPONSIVE FONT SIZE', 'ecomall' )
		,'subtitle' => ''
		,'indent'   => false
	)
	,array(
		'id'      => 'info-font-size-tablet'
		,'type'   => 'info'
		,'notice' => false
		,'title'  => esc_html__( 'Tablet', 'ecomall' )
		,'desc'   => ''
	)
	,array(
		'id'       			=> 'ts_fs_body_ipad_font'
		,'type'     		=> 'typography'
		,'title'    		=> esc_html__( 'Body', 'ecomall' )
		,'subtitle' 		=> ''
		,'class' 			=> 'typography-no-preview'
		,'google'   		=> false
		,'font-family'  	=> false
		,'font-weight'  	=> false
		,'font-style'   	=> false
		,'letter-spacing' 	=> false
		,'line-height' 		=> false
		,'text-align'  	 	=> false
		,'color'   			=> false
		,'preview'			=> array('always_display' => false)
		,'default'  		=> array(
			'font-family'  		=> ''
			,'font-weight' 		=> ''
			,'font-size'   		=> '14px'
			,'google'	   		=> false
		)
	)
	,array(
		'id'       			=> 'ts_fs_button_ipad_font'
		,'type'     		=> 'typography'
		,'title'    		=> esc_html__( 'Button', 'ecomall' )
		,'subtitle' 		=> ''
		,'class' 			=> 'typography-no-preview'
		,'google'   		=> false
		,'font-family'  	=> false
		,'font-weight'  	=> false
		,'font-style'   	=> false
		,'letter-spacing' 	=> false
		,'line-height' 		=> false
		,'text-align'  	 	=> false
		,'color'   			=> false
		,'preview'			=> array('always_display' => false)
		,'default'  		=> array(
			'font-family'  		=> 'Mulish'
			,'font-weight' 		=> ''
			,'font-size'   		=> '13px'
			,'google'	   		=> false
		)
	)
	,array(
		'id'       			=> 'ts_h1_ipad_font'
		,'type'     		=> 'typography'
		,'title'    		=> esc_html__( 'H1', 'ecomall' )
		,'subtitle' 		=> ''
		,'class' 			=> 'typography-no-preview'
		,'google'   		=> false
		,'font-family'  	=> false
		,'font-weight'  	=> false
		,'font-style'   	=> false
		,'letter-spacing' 	=> false
		,'line-height' 		=> false
		,'text-align'  	 	=> false
		,'color'   			=> false
		,'preview'			=> array('always_display' => false)
		,'default'  		=> array(
			'font-family'  		=> ''
			,'font-weight' 		=> ''
			,'font-size'   		=> '35px'
			,'google'	   		=> false
		)
	)
	,array(
		'id'       			=> 'ts_h2_ipad_font'
		,'type'     		=> 'typography'
		,'title'    		=> esc_html__( 'H2', 'ecomall' )
		,'subtitle' 		=> ''
		,'class' 			=> 'typography-no-preview'
		,'google'   		=> false
		,'font-family'  	=> false
		,'font-weight'  	=> false
		,'font-style'   	=> false
		,'letter-spacing' 	=> false
		,'line-height' 		=> false
		,'text-align'  	 	=> false
		,'color'   			=> false
		,'preview'			=> array('always_display' => false)
		,'default'  		=> array(
			'font-family'  		=> ''
			,'font-weight' 		=> ''
			,'font-size'   		=> '30px'
			,'google'	   		=> false
		)
	)
	,array(
		'id'       			=> 'ts_h3_ipad_font'
		,'type'     		=> 'typography'
		,'title'    		=> esc_html__( 'H3', 'ecomall' )
		,'subtitle' 		=> ''
		,'class' 			=> 'typography-no-preview'
		,'google'   		=> false
		,'font-family'  	=> false
		,'font-weight'  	=> false
		,'font-style'   	=> false
		,'letter-spacing' 	=> false
		,'line-height' 		=> false
		,'text-align'  	 	=> false
		,'color'   			=> false
		,'preview'			=> array('always_display' => false)
		,'default'  		=> array(
			'font-family'  		=> ''
			,'font-weight' 		=> ''
			,'font-size'   		=> '24px'
			,'google'	   		=> false
		)
	)
	,array(
		'id'       			=> 'ts_h4_ipad_font'
		,'type'     		=> 'typography'
		,'title'    		=> esc_html__( 'H4', 'ecomall' )
		,'subtitle' 		=> ''
		,'class' 			=> 'typography-no-preview'
		,'google'   		=> false
		,'font-family'  	=> false
		,'font-weight'  	=> false
		,'font-style'   	=> false
		,'letter-spacing' 	=> false
		,'line-height' 		=> false
		,'text-align'  	 	=> false
		,'color'   			=> false
		,'preview'			=> array('always_display' => false)
		,'default'  		=> array(
			'font-family'  		=> ''
			,'font-weight' 		=> ''
			,'font-size'   		=> '20px'
			,'google'	   		=> false
		)
	)
	,array(
		'id'       			=> 'ts_h5_ipad_font'
		,'type'     		=> 'typography'
		,'title'    		=> esc_html__( 'H5', 'ecomall' )
		,'subtitle' 		=> ''
		,'class' 			=> 'typography-no-preview'
		,'google'   		=> false
		,'font-family'  	=> false
		,'font-weight'  	=> false
		,'font-style'   	=> false
		,'letter-spacing' 	=> false
		,'line-height' 		=> false
		,'text-align'  	 	=> false
		,'color'   			=> false
		,'preview'			=> array('always_display' => false)
		,'default'  		=> array(
			'font-family'  		=> ''
			,'font-weight' 		=> ''
			,'font-size'   		=> '18px'
			,'google'	   		=> false
		)
	)
	,array(
		'id'       			=> 'ts_h6_ipad_font'
		,'type'     		=> 'typography'
		,'title'    		=> esc_html__( 'H6', 'ecomall' )
		,'subtitle' 		=> ''
		,'class' 			=> 'typography-no-preview'
		,'google'   		=> false
		,'font-family'  	=> false
		,'font-weight'  	=> false
		,'font-style'   	=> false
		,'letter-spacing' 	=> false
		,'line-height' 		=> false
		,'text-align'  	 	=> false
		,'color'   			=> false
		,'preview'			=> array('always_display' => false)
		,'default'  		=> array(
			'font-family'  		=> ''
			,'font-weight' 		=> ''
			,'font-size'   		=> '16px'
			,'google'	   		=> false
		)
	)
	,array(
		'id'      => 'info-font-size-mobile'
		,'type'   => 'info'
		,'notice' => false
		,'title'  => esc_html__( 'Mobile', 'ecomall' )
		,'desc'   => ''
	)
	,array(
		'id'       			=> 'ts_h1_mobile_font'
		,'type'     		=> 'typography'
		,'title'    		=> esc_html__( 'H1', 'ecomall' )
		,'subtitle' 		=> ''
		,'class' 			=> 'typography-no-preview'
		,'google'   		=> false
		,'font-family'  	=> false
		,'font-weight'  	=> false
		,'font-style'   	=> false
		,'letter-spacing' 	=> false
		,'line-height' 		=> false
		,'text-align'  	 	=> false
		,'color'   			=> false
		,'preview'			=> array('always_display' => false)
		,'default'  		=> array(
			'font-family'  		=> ''
			,'font-weight' 		=> ''
			,'font-size'   		=> '30px'
			,'google'	   		=> false
		)
	)
	,array(
		'id'       			=> 'ts_h2_mobile_font'
		,'type'     		=> 'typography'
		,'title'    		=> esc_html__( 'H2', 'ecomall' )
		,'subtitle' 		=> ''
		,'class' 			=> 'typography-no-preview'
		,'google'   		=> false
		,'font-family'  	=> false
		,'font-weight'  	=> false
		,'font-style'   	=> false
		,'letter-spacing' 	=> false
		,'line-height' 		=> false
		,'text-align'  	 	=> false
		,'color'   			=> false
		,'preview'			=> array('always_display' => false)
		,'default'  		=> array(
			'font-family'  		=> ''
			,'font-weight' 		=> ''
			,'font-size'   		=> '25px'
			,'google'	   		=> false
		)
	)
	,array(
		'id'       			=> 'ts_h3_mobile_font'
		,'type'     		=> 'typography'
		,'title'    		=> esc_html__( 'H3', 'ecomall' )
		,'subtitle' 		=> ''
		,'class' 			=> 'typography-no-preview'
		,'google'   		=> false
		,'font-family'  	=> false
		,'font-weight'  	=> false
		,'font-style'   	=> false
		,'letter-spacing' 	=> false
		,'line-height' 		=> false
		,'text-align'  	 	=> false
		,'color'   			=> false
		,'preview'			=> array('always_display' => false)
		,'default'  		=> array(
			'font-family'  		=> ''
			,'font-weight' 		=> ''
			,'font-size'   		=> '20px'
			,'google'	   		=> false
		)
	)
	,array(
		'id'       			=> 'ts_h4_mobile_font'
		,'type'     		=> 'typography'
		,'title'    		=> esc_html__( 'H4', 'ecomall' )
		,'subtitle' 		=> ''
		,'class' 			=> 'typography-no-preview'
		,'google'   		=> false
		,'font-family'  	=> false
		,'font-weight'  	=> false
		,'font-style'   	=> false
		,'letter-spacing' 	=> false
		,'line-height' 		=> false
		,'text-align'  	 	=> false
		,'color'   			=> false
		,'preview'			=> array('always_display' => false)
		,'default'  		=> array(
			'font-family'  		=> ''
			,'font-weight' 		=> ''
			,'font-size'   		=> '18px'
			,'google'	   		=> false
		)
	)
	,array(
		'id'       			=> 'ts_h5_mobile_font'
		,'type'     		=> 'typography'
		,'title'    		=> esc_html__( 'H5', 'ecomall' )
		,'subtitle' 		=> ''
		,'class' 			=> 'typography-no-preview'
		,'google'   		=> false
		,'font-family'  	=> false
		,'font-weight'  	=> false
		,'font-style'   	=> false
		,'letter-spacing' 	=> false
		,'line-height' 		=> false
		,'text-align'  	 	=> false
		,'color'   			=> false
		,'preview'			=> array('always_display' => false)
		,'default'  		=> array(
			'font-family'  		=> ''
			,'font-weight' 		=> ''
			,'font-size'   		=> '16px'
			,'google'	   		=> false
		)
	)
	,array(
		'id'       			=> 'ts_h6_mobile_font'
		,'type'     		=> 'typography'
		,'title'    		=> esc_html__( 'H6', 'ecomall' )
		,'subtitle' 		=> ''
		,'class' 			=> 'typography-no-preview'
		,'google'   		=> false
		,'font-family'  	=> false
		,'font-weight'  	=> false
		,'font-style'   	=> false
		,'letter-spacing' 	=> false
		,'line-height' 		=> false
		,'text-align'  	 	=> false
		,'color'   			=> false
		,'preview'			=> array('always_display' => false)
		,'default'  		=> array(
			'font-family'  		=> ''
			,'font-weight' 		=> ''
			,'font-size'   		=> '15px'
			,'google'	   		=> false
		)
	)
	,array(
		'id'        => 'section-custom-font'
		,'type'     => 'section'
		,'title'    => esc_html__( 'CUSTOM FONT', 'ecomall' )
		,'subtitle' => esc_html__( 'If you get the error message \'Sorry, this file type is not permitted for security reasons\', you can add this line define(\'ALLOW_UNFILTERED_UPLOADS\', true); to the wp-config.php file', 'ecomall' )
		,'indent'   => false
	)
	,array(
		'id'        => 'ts_custom_font_ttf'
		,'type'     => 'media'
		,'url'      => true
		,'preview'  => false
		,'title'    => esc_html__( 'Custom Font ttf', 'ecomall' )
		,'desc'     => ''
		,'subtitle' => esc_html__( 'Upload the .ttf font file. To use it, you select CustomFont in the Standard Fonts group', 'ecomall' )
		,'default'  => array( 'url' => '' )
		,'mode'		=> 'application'
	)
);

/*** Header Tab ***/
$option_fields['header'] = array(
	array(
		'id'        => 'section-header-options'
		,'type'     => 'section'
		,'title'    => esc_html__( 'Header Options', 'ecomall' )
		,'subtitle' => ''
		,'indent'   => false
	)
	,array(
		'id'        => 'ts_header_layout'
		,'type'     => 'image_select'
		,'title'    => esc_html__( 'Header Layout', 'ecomall' )
		,'subtitle' => ''
		,'desc'     => ''
		,'options'  => $header_layout_options
		,'default'  => 'v1'
	)
	,array(
		'id'        => 'ts_enable_sticky_header'
		,'type'     => 'switch'
		,'title'    => esc_html__( 'Sticky Header', 'ecomall' )
		,'subtitle' => ''
		,'default'  => true
		,'on'		=> esc_html__( 'Enable', 'ecomall' )
		,'off'		=> esc_html__( 'Disable', 'ecomall' )
	)
	,array(
		'id'      => 'info-header-notice'
		,'type'   => 'info'
		,'notice' => false
		,'title'  => esc_html__( 'Store Notice', 'ecomall' )
		,'desc'   => ''
	)
	,array(
		'id'        => 'ts_header_store_notice'
		,'type'     => 'textarea'
		,'title'    => esc_html__( 'Header Notice', 'ecomall' )
		,'subtitle' => ''
		,'validate'	=> 'html'
		,'desc'     => ''
		,'default'  => ''
	)
	,array(
		'id'      => 'info-header-language-currency'
		,'type'   => 'info'
		,'notice' => false
		,'title'  => esc_html__( 'Language & Currency', 'ecomall' )
		,'desc'   => ''
	)
	,array(
		'id'        => 'ts_header_currency'
		,'type'     => 'switch'
		,'title'    => esc_html__( 'Header Currency', 'ecomall' )
		,'subtitle' => esc_html__( 'If you don\'t install WooCommerce Multilingual plugin, it may display demo html', 'ecomall' )
		,'default'  => false
		,'on'		=> esc_html__( 'Enable', 'ecomall' )
		,'off'		=> esc_html__( 'Disable', 'ecomall' )
	)
	,array(
		'id'        => 'ts_header_language'
		,'type'     => 'switch'
		,'title'    => esc_html__( 'Header Language', 'ecomall' )
		,'subtitle' => esc_html__( 'If you don\'t install WPML plugin, it may display demo html', 'ecomall' )
		,'default'  => false
		,'on'		=> esc_html__( 'Enable', 'ecomall' )
		,'off'		=> esc_html__( 'Disable', 'ecomall' )
	)
	,array(
		'id'      => 'info-header-other'
		,'type'   => 'info'
		,'notice' => false
		,'title'  => esc_html__( 'Search/Wishlist/Account', 'ecomall' )
		,'desc'   => ''
	)
	,array(
		'id'        => 'ts_enable_search'
		,'type'     => 'switch'
		,'title'    => esc_html__( 'Search', 'ecomall' )
		,'subtitle' => ''
		,'default'  => true
		,'on'		=> esc_html__( 'Enable', 'ecomall' )
		,'off'		=> esc_html__( 'Disable', 'ecomall' )
	)
	,array(
		'id'        => 'ts_search_by_category'
		,'type'     => 'switch'
		,'title'    => esc_html__( 'Search By Category', 'ecomall' )
		,'subtitle' => ''
		,'default'  => true
		,'on'		=> esc_html__( 'Enable', 'ecomall' )
		,'off'		=> esc_html__( 'Disable', 'ecomall' )
	)
	,array(
		'id'        => 'ts_enable_tiny_wishlist'
		,'type'     => 'switch'
		,'title'    => esc_html__( 'Wishlist', 'ecomall' )
		,'subtitle' => ''
		,'default'  => true
		,'on'		=> esc_html__( 'Enable', 'ecomall' )
		,'off'		=> esc_html__( 'Disable', 'ecomall' )
	)
	,array(
		'id'        => 'ts_enable_tiny_account'
		,'type'     => 'switch'
		,'title'    => esc_html__( 'My Account', 'ecomall' )
		,'subtitle' => ''
		,'default'  => true
		,'on'		=> esc_html__( 'Enable', 'ecomall' )
		,'off'		=> esc_html__( 'Disable', 'ecomall' )
	)
	,array(
		'id'      => 'info-header-cart'
		,'type'   => 'info'
		,'notice' => false
		,'title'  => esc_html__( 'Header Cart', 'ecomall' )
		,'desc'   => ''
	)
	,array(
		'id'        => 'ts_enable_tiny_shopping_cart'
		,'type'     => 'switch'
		,'title'    => esc_html__( 'Shopping Cart', 'ecomall' )
		,'subtitle' => ''
		,'default'  => true
		,'on'		=> esc_html__( 'Enable', 'ecomall' )
		,'off'		=> esc_html__( 'Disable', 'ecomall' )
	)
	,array(
		'id'        => 'ts_shopping_cart_sidebar'
		,'type'     => 'switch'
		,'title'    => esc_html__( 'Shopping Cart Sidebar', 'ecomall' )
		,'subtitle' => esc_html__( 'Show shopping cart as sidebar instead of dropdown. You have to update cart after changing', 'ecomall' )
		,'default'  => false
		,'on'		=> esc_html__( 'Enable', 'ecomall' )
		,'off'		=> esc_html__( 'Disable', 'ecomall' )
		,'required'	=> array( 'ts_enable_tiny_shopping_cart', 'equals', '1' )
	)
	,array(
		'id'        => 'ts_show_shopping_cart_after_adding'
		,'type'     => 'switch'
		,'title'    => esc_html__( 'Show Shopping Cart After Adding Product To Cart', 'ecomall' )
		,'subtitle' => esc_html__( 'You have to enable Ajax add to cart in WooCommerce > Settings > Products', 'ecomall' )
		,'default'  => false
		,'on'		=> esc_html__( 'Enable', 'ecomall' )
		,'off'		=> esc_html__( 'Disable', 'ecomall' )
		,'required'	=> array( 'ts_shopping_cart_sidebar', 'equals', '1' )
	)
	,array(
		'id'        => 'ts_add_to_cart_effect'
		,'type'     => 'select'
		,'title'    => esc_html__( 'Add To Cart Effect', 'ecomall' )
		,'subtitle' => esc_html__( 'You have to enable Ajax add to cart in WooCommerce > Settings > Products. If "Show Shopping Cart After Adding Product To Cart" is enabled, this option will be disabled', 'ecomall' )
		,'options'  => array(
			'0'				=> esc_html__( 'None', 'ecomall' )
			,'fly_to_cart'	=> esc_html__( 'Fly To Cart', 'ecomall' )
			,'show_popup'	=> esc_html__( 'Show Popup', 'ecomall' )
		)
		,'default'  => '0'
		,'select2'	=> array('allowClear' => false, 'minimumResultsForSearch' => 'Infinity')
	)
	,array(
		'id'      => 'info-today-deal'
		,'type'   => 'info'
		,'notice' => false
		,'title'  => esc_html__( 'Today\'s Deal', 'ecomall' )
		,'desc'   => ''
	)
	,array(
		'id'        => 'ts_enable_today_deal'
		,'type'     => 'switch'
		,'title'    => esc_html__( 'Enable Today\'s Deal', 'ecomall' )
		,'subtitle' => esc_html__( 'Only available in some header layouts', 'ecomall' )
		,'default'  => true
		,'on'		=> esc_html__( 'Enable', 'ecomall' )
		,'off'		=> esc_html__( 'Disable', 'ecomall' )
	)
	,array(
		'id'        => 'ts_today_deal_text'
		,'type'     => 'text'
		,'title'    => esc_html__( 'Today\'s Deal Text', 'ecomall' )
		,'subtitle' => ''
		,'desc'     => ''
		,'default'  => ''
		,'required'	=> array( 'ts_enable_today_deal', 'equals', '1' )
	)
	,array(
		'id'        => 'ts_today_deal_label'
		,'type'     => 'text'
		,'title'    => esc_html__( 'Today\'s Deal Label', 'ecomall' )
		,'subtitle' => ''
		,'default'  => ''
		,'required'	=> array( 'ts_enable_today_deal', 'equals', '1' )
	)
	,array(
		'id'        => 'ts_today_deal_url'
		,'type'     => 'text'
		,'title'    => esc_html__( 'Today\'s Deal Link', 'ecomall' )
		,'subtitle' => ''
		,'desc'     => ''
		,'default'  => '#'
		,'required'	=> array( 'ts_enable_today_deal', 'equals', '1' )
	)
	,array(
		'id'      => 'info-hotline'
		,'type'   => 'info'
		,'notice' => false
		,'title'  => esc_html__( 'Hotline', 'ecomall' )
		,'desc'   => ''
	)
	,array(
		'id'        => 'ts_enable_hotline'
		,'type'     => 'switch'
		,'title'    => esc_html__( 'Enable Hotline', 'ecomall' )
		,'subtitle' => esc_html__( 'Only available in some header layouts', 'ecomall' )
		,'default'  => true
		,'on'		=> esc_html__( 'Enable', 'ecomall' )
		,'off'		=> esc_html__( 'Disable', 'ecomall' )
	)
	,array(
		'id'        => 'ts_hotline_text'
		,'type'     => 'text'
		,'title'    => esc_html__( 'Hotline Text', 'ecomall' )
		,'subtitle' => ''
		,'desc'     => ''
		,'default'  => ''
		,'required'	=> array( 'ts_enable_hotline', 'equals', '1' )
	)
	,array(
		'id'        => 'ts_hotline_number'
		,'type'     => 'text'
		,'title'    => esc_html__( 'Hotline Number', 'ecomall' )
		,'subtitle' => ''
		,'desc'     => ''
		,'default'  => ''
		,'required'	=> array( 'ts_enable_hotline', 'equals', '1' )
	)
	,array(
		'id'        => 'section-header-tablet'
		,'type'     => 'section'
		,'title'    => esc_html__( 'Tablet Header', 'ecomall' )
		,'subtitle' => ''
		,'indent'   => false
	)
	,array(
		'id'        => 'ts_tablet_show_notice'
		,'type'     => 'switch'
		,'title'    => esc_html__( 'Show Notice on Device', 'ecomall' )
		,'subtitle' => ''
		,'default'  => false
		,'on'		=> esc_html__( 'Enable', 'ecomall' )
		,'off'		=> esc_html__( 'Disable', 'ecomall' )
	)
	,array(
		'id'        => 'ts_tablet_show_hotline'
		,'type'     => 'switch'
		,'title'    => esc_html__( 'Show Hotline on Device', 'ecomall' )
		,'subtitle' => ''
		,'default'  => false
		,'on'		=> esc_html__( 'Enable', 'ecomall' )
		,'off'		=> esc_html__( 'Disable', 'ecomall' )
	)

	,array(
		'id'        => 'section-breadcrumb-options'
		,'type'     => 'section'
		,'title'    => esc_html__( 'Breadcrumb Options', 'ecomall' )
		,'subtitle' => ''
		,'indent'   => false
	)
	,array(
		'id'        => 'ts_breadcrumb_layout'
		,'type'     => 'image_select'
		,'title'    => esc_html__( 'Breadcrumb Layout', 'ecomall' )
		,'subtitle' => ''
		,'desc'     => ''
		,'options'  => $breadcrumb_layout_options
		,'default'  => 'v1'
	)
	,array(
		'id'        => 'ts_enable_breadcrumb_background_image'
		,'type'     => 'switch'
		,'title'    => esc_html__( 'Enable Breadcrumbs Background Image', 'ecomall' )
		,'subtitle' => esc_html__( 'You can set background color by going to Color Scheme tab > Breadcrumb Colors section', 'ecomall' )
		,'default'  => true
	)
	,array(
		'id'        => 'ts_bg_breadcrumbs'
		,'type'     => 'media'
		,'url'      => true
		,'title'    => esc_html__( 'Breadcrumbs Background Image', 'ecomall' )
		,'desc'     => ''
		,'subtitle' => esc_html__( 'Select a new image to override the default background image', 'ecomall' )
		,'readonly' => false
		,'default'  => array( 'url' => '' )
	)
	,array(
		'id'        => 'ts_breadcrumb_bg_parallax'
		,'type'     => 'switch'
		,'title'    => esc_html__( 'Enable Breadcrumbs Background Parallax', 'ecomall' )
		,'subtitle' => ''
		,'default'  => false
	)
	,array(
		'id'        => 'ts_breadcrumb_product_taxonomy_description'
		,'type'     => 'switch'
		,'title'    => esc_html__( 'Product Taxonomy Description In Breadcrumbs', 'ecomall' )
		,'subtitle' => esc_html__( 'Show product taxonomy description (category, tags, ...) in breadcrumbs area on the product taxonomy page', 'ecomall' )
		,'default'  => false
	)
);

/*** Footer Tab ***/
$option_fields['footer'] = array(
	array(
		'id'       	=> 'ts_footer_block'
		,'type'     => 'select'
		,'title'    => esc_html__( 'Footer Block', 'ecomall' )
		,'subtitle' => ''
		,'desc'     => ''
		,'options'  => $footer_block_options
		,'default'  => '0'
		,'select2'	=> array('allowClear' => false, 'minimumResultsForSearch' => 'Infinity')
	)
);

/*** Menu Tab ***/
$option_fields['menu'] = array(
	array(
		'id'             => 'ts_menu_thumb_width'
		,'type'          => 'slider'
		,'title'         => esc_html__( 'Menu Thumbnail Width', 'ecomall' )
		,'subtitle'      => ''
		,'desc'          => esc_html__( 'Min: 5, max: 60, step: 1, default value: 54', 'ecomall' )
		,'default'       => 54
		,'min'           => 5
		,'step'          => 1
		,'max'           => 60
		,'display_value' => 'text'
	)
	,array(
		'id'             => 'ts_menu_thumb_height'
		,'type'          => 'slider'
		,'title'         => esc_html__( 'Menu Thumbnail Height', 'ecomall' )
		,'subtitle'      => ''
		,'desc'          => esc_html__( 'Min: 5, max: 60, step: 1, default value: 54', 'ecomall' )
		,'default'       => 54
		,'min'           => 5
		,'step'          => 1
		,'max'           => 60
		,'display_value' => 'text'
	)
	,array(
		'id'        => 'ts_enable_menu_overlay'
		,'type'     => 'switch'
		,'title'    => esc_html__( 'Menu Background Overlay', 'ecomall' )
		,'subtitle' => ''
		,'default'  => false
		,'on'		=> esc_html__( 'Enable', 'ecomall' )
		,'off'		=> esc_html__( 'Disable', 'ecomall' )
	)
);

/*** Blog Tab ***/
$option_fields['blog'] = array(
	array(
		'id'        => 'section-blog'
		,'type'     => 'section'
		,'title'    => esc_html__( 'Blog', 'ecomall' )
		,'subtitle' => ''
		,'indent'   => false
	)
	,array(
		'id'        => 'ts_blog_layout'
		,'type'     => 'image_select'
		,'title'    => esc_html__( 'Blog Layout', 'ecomall' )
		,'subtitle' => esc_html__( 'This option is available when Front page displays the latest posts', 'ecomall' )
		,'desc'     => ''
		,'options'  => array(
			'0-1-0' => array(
				'alt'  => esc_html__('Fullwidth', 'ecomall')
				,'img' => $redux_url . 'assets/img/1col.png'
			)
			,'1-1-0' => array(
				'alt'  => esc_html__('Left Sidebar', 'ecomall')
				,'img' => $redux_url . 'assets/img/2cl.png'
			)
			,'0-1-1' => array(
				'alt'  => esc_html__('Right Sidebar', 'ecomall')
				,'img' => $redux_url . 'assets/img/2cr.png'
			)
			,'1-1-1' => array(
				'alt'  => esc_html__('Left & Right Sidebar', 'ecomall')
				,'img' => $redux_url . 'assets/img/3cm.png'
			)
		)
		,'default'  => '0-1-1'
	)
	,array(
		'id'       	=> 'ts_blog_left_sidebar'
		,'type'     => 'select'
		,'title'    => esc_html__( 'Left Sidebar', 'ecomall' )
		,'subtitle' => ''
		,'desc'     => ''
		,'options'  => $sidebar_options
		,'default'  => 'blog-sidebar'
		,'select2'	=> array('allowClear' => false, 'minimumResultsForSearch' => 'Infinity')
	)
	,array(
		'id'       	=> 'ts_blog_right_sidebar'
		,'type'     => 'select'
		,'title'    => esc_html__( 'Right Sidebar', 'ecomall' )
		,'subtitle' => ''
		,'desc'     => ''
		,'options'  => $sidebar_options
		,'default'  => 'blog-sidebar'
		,'select2'	=> array('allowClear' => false, 'minimumResultsForSearch' => 'Infinity')
	)
	,array(
		'id'       	=> 'ts_blog_columns'
		,'type'     => 'select'
		,'title'    => esc_html__( 'Blog Columns', 'ecomall' )
		,'subtitle' => ''
		,'desc'     => ''
		,'options'  => array(
			1	=> 1
			,2	=> 2
			,3	=> 3
		)
		,'default'  => '1'
		,'select2'	=> array('allowClear' => false, 'minimumResultsForSearch' => 'Infinity')
	)
	,array(
		'id'        => 'ts_blog_thumbnail'
		,'type'     => 'switch'
		,'title'    => esc_html__( 'Blog Thumbnail', 'ecomall' )
		,'subtitle' => ''
		,'default'  => true
		,'on'		=> esc_html__( 'Show', 'ecomall' )
		,'off'		=> esc_html__( 'Hide', 'ecomall' )
	)
	,array(
		'id'        => 'ts_blog_date'
		,'type'     => 'switch'
		,'title'    => esc_html__( 'Blog Date', 'ecomall' )
		,'subtitle' => ''
		,'default'  => true
		,'on'		=> esc_html__( 'Show', 'ecomall' )
		,'off'		=> esc_html__( 'Hide', 'ecomall' )
	)
	,array(
		'id'        => 'ts_blog_title'
		,'type'     => 'switch'
		,'title'    => esc_html__( 'Blog Title', 'ecomall' )
		,'subtitle' => ''
		,'default'  => true
		,'on'		=> esc_html__( 'Show', 'ecomall' )
		,'off'		=> esc_html__( 'Hide', 'ecomall' )
	)
	,array(
		'id'        => 'ts_blog_author'
		,'type'     => 'switch'
		,'title'    => esc_html__( 'Blog Author', 'ecomall' )
		,'subtitle' => ''
		,'default'  => true
		,'on'		=> esc_html__( 'Show', 'ecomall' )
		,'off'		=> esc_html__( 'Hide', 'ecomall' )
	)
	,array(
		'id'        => 'ts_blog_comment'
		,'type'     => 'switch'
		,'title'    => esc_html__( 'Blog Comment', 'ecomall' )
		,'subtitle' => ''
		,'default'  => false
		,'on'		=> esc_html__( 'Show', 'ecomall' )
		,'off'		=> esc_html__( 'Hide', 'ecomall' )
	)
	,array(
		'id'        => 'ts_blog_read_more'
		,'type'     => 'switch'
		,'title'    => esc_html__( 'Blog Read More Button', 'ecomall' )
		,'subtitle' => ''
		,'default'  => false
		,'on'		=> esc_html__( 'Show', 'ecomall' )
		,'off'		=> esc_html__( 'Hide', 'ecomall' )
	)
	,array(
		'id'        => 'ts_blog_categories'
		,'type'     => 'switch'
		,'title'    => esc_html__( 'Blog Categories', 'ecomall' )
		,'subtitle' => ''
		,'default'  => false
		,'on'		=> esc_html__( 'Show', 'ecomall' )
		,'off'		=> esc_html__( 'Hide', 'ecomall' )
	)
	,array(
		'id'        => 'ts_blog_excerpt'
		,'type'     => 'switch'
		,'title'    => esc_html__( 'Blog Excerpt', 'ecomall' )
		,'subtitle' => ''
		,'default'  => true
		,'on'		=> esc_html__( 'Show', 'ecomall' )
		,'off'		=> esc_html__( 'Hide', 'ecomall' )
	)
	,array(
		'id'        => 'ts_blog_excerpt_strip_tags'
		,'type'     => 'switch'
		,'title'    => esc_html__( 'Blog Excerpt Strip All Tags', 'ecomall' )
		,'subtitle' => esc_html__( 'Strip all html tags in Excerpt', 'ecomall' )
		,'default'  => false
	)
	,array(
		'id'        => 'ts_blog_excerpt_max_words'
		,'type'     => 'text'
		,'title'    => esc_html__( 'Blog Excerpt Max Words', 'ecomall' )
		,'subtitle' => esc_html__( 'Input -1 to show full excerpt', 'ecomall' )
		,'desc'     => ''
		,'default'  => '-1'
	)

	,array(
		'id'        => 'section-blog-details'
		,'type'     => 'section'
		,'title'    => esc_html__( 'Blog Details', 'ecomall' )
		,'subtitle' => ''
		,'indent'   => false
	)
	,array(
		'id'        => 'ts_blog_details_layout'
		,'type'     => 'image_select'
		,'title'    => esc_html__( 'Blog Details Layout', 'ecomall' )
		,'subtitle' => ''
		,'desc'     => ''
		,'options'  => array(
			'0-1-0' => array(
				'alt'  => esc_html__('Fullwidth', 'ecomall')
				,'img' => $redux_url . 'assets/img/1col.png'
			)
			,'1-1-0' => array(
				'alt'  => esc_html__('Left Sidebar', 'ecomall')
				,'img' => $redux_url . 'assets/img/2cl.png'
			)
			,'0-1-1' => array(
				'alt'  => esc_html__('Right Sidebar', 'ecomall')
				,'img' => $redux_url . 'assets/img/2cr.png'
			)
			,'1-1-1' => array(
				'alt'  => esc_html__('Left & Right Sidebar', 'ecomall')
				,'img' => $redux_url . 'assets/img/3cm.png'
			)
		)
		,'default'  => '0-1-0'
	)
	,array(
		'id'       	=> 'ts_blog_details_left_sidebar'
		,'type'     => 'select'
		,'title'    => esc_html__( 'Left Sidebar', 'ecomall' )
		,'subtitle' => ''
		,'desc'     => ''
		,'options'  => $sidebar_options
		,'default'  => 'blog-detail-sidebar'
		,'select2'	=> array('allowClear' => false, 'minimumResultsForSearch' => 'Infinity')
	)
	,array(
		'id'       	=> 'ts_blog_details_right_sidebar'
		,'type'     => 'select'
		,'title'    => esc_html__( 'Right Sidebar', 'ecomall' )
		,'subtitle' => ''
		,'desc'     => ''
		,'options'  => $sidebar_options
		,'default'  => 'blog-detail-sidebar'
		,'select2'	=> array('allowClear' => false, 'minimumResultsForSearch' => 'Infinity')
	)
	,array(
		'id'        => 'ts_blog_details_thumbnail'
		,'type'     => 'switch'
		,'title'    => esc_html__( 'Blog Thumbnail', 'ecomall' )
		,'subtitle' => ''
		,'default'  => true
		,'on'		=> esc_html__( 'Show', 'ecomall' )
		,'off'		=> esc_html__( 'Hide', 'ecomall' )
	)
	,array(
		'id'        => 'ts_blog_details_date'
		,'type'     => 'switch'
		,'title'    => esc_html__( 'Blog Date', 'ecomall' )
		,'subtitle' => ''
		,'default'  => true
		,'on'		=> esc_html__( 'Show', 'ecomall' )
		,'off'		=> esc_html__( 'Hide', 'ecomall' )
	)
	,array(
		'id'        => 'ts_blog_details_title'
		,'type'     => 'switch'
		,'title'    => esc_html__( 'Blog Title', 'ecomall' )
		,'subtitle' => ''
		,'default'  => true
		,'on'		=> esc_html__( 'Show', 'ecomall' )
		,'off'		=> esc_html__( 'Hide', 'ecomall' )
	)
	,array(
		'id'        => 'ts_blog_details_author'
		,'type'     => 'switch'
		,'title'    => esc_html__( 'Blog Author', 'ecomall' )
		,'subtitle' => ''
		,'default'  => true
		,'on'		=> esc_html__( 'Show', 'ecomall' )
		,'off'		=> esc_html__( 'Hide', 'ecomall' )
	)
	,array(
		'id'        => 'ts_blog_details_comment'
		,'type'     => 'switch'
		,'title'    => esc_html__( 'Blog Comment', 'ecomall' )
		,'subtitle' => ''
		,'default'  => false
		,'on'		=> esc_html__( 'Show', 'ecomall' )
		,'off'		=> esc_html__( 'Hide', 'ecomall' )
	)
	,array(
		'id'        => 'ts_blog_details_content'
		,'type'     => 'switch'
		,'title'    => esc_html__( 'Blog Content', 'ecomall' )
		,'subtitle' => ''
		,'default'  => true
		,'on'		=> esc_html__( 'Show', 'ecomall' )
		,'off'		=> esc_html__( 'Hide', 'ecomall' )
	)
	,array(
		'id'        => 'ts_blog_details_tags'
		,'type'     => 'switch'
		,'title'    => esc_html__( 'Blog Tags', 'ecomall' )
		,'subtitle' => ''
		,'default'  => false
		,'on'		=> esc_html__( 'Show', 'ecomall' )
		,'off'		=> esc_html__( 'Hide', 'ecomall' )
	)
	,array(
		'id'        => 'ts_blog_details_categories'
		,'type'     => 'switch'
		,'title'    => esc_html__( 'Blog Categories', 'ecomall' )
		,'subtitle' => ''
		,'default'  => false
		,'on'		=> esc_html__( 'Show', 'ecomall' )
		,'off'		=> esc_html__( 'Hide', 'ecomall' )
	)
	,array(
		'id'        => 'ts_blog_details_sharing'
		,'type'     => 'switch'
		,'title'    => esc_html__( 'Blog Sharing', 'ecomall' )
		,'subtitle' => ''
		,'default'  => false
		,'on'		=> esc_html__( 'Show', 'ecomall' )
		,'off'		=> esc_html__( 'Hide', 'ecomall' )
	)
	,array(
		'id'        => 'ts_blog_details_sharing_sharethis'
		,'type'     => 'switch'
		,'title'    => esc_html__( 'Blog Sharing - Use ShareThis', 'ecomall' )
		,'subtitle' => esc_html__( 'Use share buttons from sharethis.com. You need to add key below', 'ecomall')
		,'default'  => true
		,'required'	=> array( 'ts_blog_details_sharing', 'equals', '1' )
	)
	,array(
		'id'        => 'ts_blog_details_sharing_sharethis_key'
		,'type'     => 'text'
		,'title'    => esc_html__( 'Blog Sharing - ShareThis Key', 'ecomall' )
		,'subtitle' => esc_html__( 'You get it from script code. It is the value of "property" attribute', 'ecomall' )
		,'desc'     => ''
		,'default'  => ''
		,'required'	=> array( 'ts_blog_details_sharing', 'equals', '1' )
	)
	,array(
		'id'        => 'ts_blog_details_author_box'
		,'type'     => 'switch'
		,'title'    => esc_html__( 'Blog Author Box', 'ecomall' )
		,'subtitle' => ''
		,'default'  => false
		,'on'		=> esc_html__( 'Show', 'ecomall' )
		,'off'		=> esc_html__( 'Hide', 'ecomall' )
	)
	,array(
		'id'        => 'ts_blog_details_navigation'
		,'type'     => 'switch'
		,'title'    => esc_html__( 'Blog Navigation', 'ecomall' )
		,'subtitle' => ''
		,'default'  => false
		,'on'		=> esc_html__( 'Show', 'ecomall' )
		,'off'		=> esc_html__( 'Hide', 'ecomall' )
	)
	,array(
		'id'        => 'ts_blog_details_related_posts'
		,'type'     => 'switch'
		,'title'    => esc_html__( 'Blog Related Posts', 'ecomall' )
		,'subtitle' => ''
		,'default'  => false
		,'on'		=> esc_html__( 'Show', 'ecomall' )
		,'off'		=> esc_html__( 'Hide', 'ecomall' )
	)
	,array(
		'id'        => 'ts_blog_details_comment_form'
		,'type'     => 'switch'
		,'title'    => esc_html__( 'Blog Comment Form', 'ecomall' )
		,'subtitle' => ''
		,'default'  => true
		,'on'		=> esc_html__( 'Show', 'ecomall' )
		,'off'		=> esc_html__( 'Hide', 'ecomall' )
	)
);

/*** WooCommerce Tab ***/
$option_fields['woocommerce'] = array(
	array(
		'id'        => 'section-product-label'
		,'type'     => 'section'
		,'title'    => esc_html__( 'Product Label', 'ecomall' )
		,'subtitle' => ''
		,'indent'   => false
	)
	,array(
		'id'       	=> 'ts_product_label_style'
		,'type'     => 'select'
		,'title'    => esc_html__( 'Product Label Style', 'ecomall' )
		,'subtitle' => ''
		,'desc'     => ''
		,'options'  => array(
			'rectangle' 	=> esc_html__( 'Rectangle', 'ecomall' )
			,'square' 		=> esc_html__( 'Square', 'ecomall' )
			,'circle' 		=> esc_html__( 'Circle', 'ecomall' )
		)
		,'default'  => 'rectangle'
		,'select2'	=> array('allowClear' => false, 'minimumResultsForSearch' => 'Infinity')
	)
	,array(
		'id'       	=> 'ts_product_label_pos'
		,'type'     => 'select'
		,'title'    => esc_html__( 'Product Label Position', 'ecomall' )
		,'subtitle' => ''
		,'desc'     => ''
		,'options'  => array(
			'on-thumbnail' 		=> esc_html__( 'On Thumbnail', 'ecomall' )
			,'after-thumbnail' 	=> esc_html__( 'After Thumbnail', 'ecomall' )
		)
		,'default'  => 'on-thumbnail'
		,'select2'	=> array('allowClear' => false, 'minimumResultsForSearch' => 'Infinity')
	)
	,array(
		'id'        => 'ts_product_show_new_label'
		,'type'     => 'switch'
		,'title'    => esc_html__( 'Product New Label', 'ecomall' )
		,'subtitle' => ''
		,'default'  => false
		,'on'		=> esc_html__( 'Show', 'ecomall' )
		,'off'		=> esc_html__( 'Hide', 'ecomall' )
	)
	,array(
		'id'        => 'ts_product_new_label_text'
		,'type'     => 'text'
		,'title'    => esc_html__( 'Product New Label Text', 'ecomall' )
		,'subtitle' => ''
		,'desc'     => ''
		,'default'  => 'New'
		,'required'	=> array( 'ts_product_show_new_label', 'equals', '1' )
	)
	,array(
		'id'        => 'ts_product_show_new_label_time'
		,'type'     => 'text'
		,'title'    => esc_html__( 'Product New Label Time', 'ecomall' )
		,'subtitle' => esc_html__( 'Number of days which you want to show New label since product is published', 'ecomall' )
		,'desc'     => ''
		,'default'  => '30'
		,'required'	=> array( 'ts_product_show_new_label', 'equals', '1' )
	)
	,array(
		'id'        => 'ts_product_feature_label_text'
		,'type'     => 'text'
		,'title'    => esc_html__( 'Product Feature Label Text', 'ecomall' )
		,'subtitle' => ''
		,'desc'     => ''
		,'default'  => 'Hot'
	)
	,array(
		'id'        => 'ts_product_out_of_stock_label_text'
		,'type'     => 'text'
		,'title'    => esc_html__( 'Product Out Of Stock Label Text', 'ecomall' )
		,'subtitle' => ''
		,'desc'     => ''
		,'default'  => 'Sold out'
	)
	,array(
		'id'       	=> 'ts_show_sale_label_as'
		,'type'     => 'select'
		,'title'    => esc_html__( 'Show Sale Label As', 'ecomall' )
		,'subtitle' => ''
		,'desc'     => ''
		,'options'  => array(
			'text' 		=> esc_html__( 'Text', 'ecomall' )
			,'number' 	=> esc_html__( 'Number', 'ecomall' )
			,'percent' 	=> esc_html__( 'Percent', 'ecomall' )
		)
		,'default'  => 'percent'
		,'select2'	=> array('allowClear' => false, 'minimumResultsForSearch' => 'Infinity')
	)
	,array(
		'id'        => 'ts_product_sale_label_text'
		,'type'     => 'text'
		,'title'    => esc_html__( 'Product Sale Label Text', 'ecomall' )
		,'subtitle' => ''
		,'desc'     => ''
		,'default'  => 'Sale'
	)
	
	,array(
		'id'        => 'section-product-title'
		,'type'     => 'section'
		,'title'    => esc_html__( 'Product Title In The Products List', 'ecomall' )
		,'subtitle' => ''
		,'indent'   => false
	)
	,array(
		'id'        => 'ts_prod_title_truncate'
		,'type'     => 'switch'
		,'title'    => esc_html__( 'Truncate Product Title', 'ecomall' )
		,'subtitle' => ''
		,'default'  => true
	)
	,array(
		'id'       	=> 'ts_prod_title_truncate_row'
		,'type'     => 'text'
		,'title'    => esc_html__( 'Number Of Rows', 'ecomall' )
		,'subtitle' => esc_html__( 'Number of rows to show, the remains will be replaced with ...', 'ecomall' )
		,'desc'     => ''
		,'default'  => '2'
		,'validate' => 'numeric'
		,'required'	=> array( 'ts_prod_title_truncate', 'equals', '1' )
	)
	
	,array(
		'id'        => 'section-product-short-desc'
		,'type'     => 'section'
		,'title'    => esc_html__( 'Product Short Description', 'ecomall' )
		,'subtitle' => ''
		,'indent'   => false
	)
	,array(
		'id'        => 'ts_hide_prod_desc_device'
		,'type'     => 'switch'
		,'title'    => esc_html__( 'Hide Product Short Description On Device', 'ecomall' )
		,'subtitle' => esc_html__( 'If enabled, product description in product list will be hidden on device', 'ecomall' )
		,'default'  => true
	)
	
	,array(
		'id'        => 'section-product-hover'
		,'type'     => 'section'
		,'title'    => esc_html__( 'Product Hover', 'ecomall' )
		,'subtitle' => ''
		,'indent'   => false
	)
	,array(
		'id'       	=> 'ts_product_hover_style'
		,'type'     => 'image_select'
		,'title'    => esc_html__( 'Hover Style', 'ecomall' )
		,'subtitle' => esc_html__( 'Select the style when hovering on product', 'ecomall' )
		,'desc'     => ''
		,'options'  => $product_hover_style_options
		,'default'  => 'v1'
	)
	,array(
		'id'        => 'ts_effect_product'
		,'type'     => 'switch'
		,'title'    => esc_html__( 'Back Product Image', 'ecomall' )
		,'subtitle' => esc_html__( 'Show another product image on hover. It will show an image from Product Gallery', 'ecomall' )
		,'default'  => false
	)
	,array(
		'id'        => 'ts_product_tooltip'
		,'type'     => 'switch'
		,'title'    => esc_html__( 'Product Tooltip', 'ecomall' )
		,'subtitle' => esc_html__( 'Show tooltip when hovering on buttons/icons on product', 'ecomall' )
		,'default'  => true
	)
	
	,array(
		'id'        => 'section-lazy-load'
		,'type'     => 'section'
		,'title'    => esc_html__( 'Lazy Load', 'ecomall' )
		,'subtitle' => ''
		,'indent'   => false
	)
	,array(
		'id'        => 'ts_prod_lazy_load'
		,'type'     => 'switch'
		,'title'    => esc_html__( 'Activate Lazy Load', 'ecomall' )
		,'subtitle' => ''
		,'default'  => true
	)
	,array(
		'id'        => 'ts_prod_placeholder_img'
		,'type'     => 'media'
		,'url'      => true
		,'title'    => esc_html__( 'Placeholder Image', 'ecomall' )
		,'desc'     => ''
		,'subtitle' => ''
		,'readonly' => false
		,'default'  => array( 'url' => $product_loading_image )
	)
	
	,array(
		'id'        => 'section-quickshop'
		,'type'     => 'section'
		,'title'    => esc_html__( 'Quickshop', 'ecomall' )
		,'subtitle' => ''
		,'indent'   => false
	)
	,array(
		'id'        => 'ts_enable_quickshop'
		,'type'     => 'switch'
		,'title'    => esc_html__( 'Activate Quickshop', 'ecomall' )
		,'subtitle' => ''
		,'default'  => true
	)

	,array(
		'id'        => 'section-catalog-mode'
		,'type'     => 'section'
		,'title'    => esc_html__( 'Catalog Mode', 'ecomall' )
		,'subtitle' => ''
		,'indent'   => false
	)
	,array(
		'id'        => 'ts_enable_catalog_mode'
		,'type'     => 'switch'
		,'title'    => esc_html__( 'Enable Catalog Mode', 'ecomall' )
		,'subtitle' => esc_html__( 'Hide all Add To Cart buttons on your site. You can also hide Shopping cart by going to Header tab > turn Shopping Cart option off', 'ecomall' )
		,'default'  => false
	)
	
	,array(
		'id'        => 'section-ajax-search'
		,'type'     => 'section'
		,'title'    => esc_html__( 'Ajax Search', 'ecomall' )
		,'subtitle' => ''
		,'indent'   => false
	)
	,array(
		'id'        => 'ts_ajax_search'
		,'type'     => 'switch'
		,'title'    => esc_html__( 'Enable Ajax Search', 'ecomall' )
		,'subtitle' => ''
		,'default'  => true
	)
	,array(
		'id'        => 'ts_ajax_search_number_result'
		,'type'     => 'text'
		,'title'    => esc_html__( 'Number Of Results', 'ecomall' )
		,'subtitle' => esc_html__( 'Input -1 to show all results', 'ecomall' )
		,'desc'     => ''
		,'default'  => '6'
	)
);

/*** Shop/Product Category Tab ***/
$option_fields['shop-product-category'] = array(
	array(
		'id'        => 'ts_prod_cat_layout'
		,'type'     => 'image_select'
		,'title'    => esc_html__( 'Shop/Product Category Layout', 'ecomall' )
		,'subtitle' => esc_html__( 'Sidebar is only available if Filter Widget Area is disabled', 'ecomall' )
		,'desc'     => ''
		,'options'  => array(
			'0-1-0' => array(
				'alt'  => esc_html__('Fullwidth', 'ecomall')
				,'img' => $redux_url . 'assets/img/1col.png'
			)
			,'1-1-0' => array(
				'alt'  => esc_html__('Left Sidebar', 'ecomall')
				,'img' => $redux_url . 'assets/img/2cl.png'
			)
			,'0-1-1' => array(
				'alt'  => esc_html__('Right Sidebar', 'ecomall')
				,'img' => $redux_url . 'assets/img/2cr.png'
			)
			,'1-1-1' => array(
				'alt'  => esc_html__('Left & Right Sidebar', 'ecomall')
				,'img' => $redux_url . 'assets/img/3cm.png'
			)
		)
		,'default'  => '0-1-0'
	)
	,array(
		'id'       	=> 'ts_prod_cat_left_sidebar'
		,'type'     => 'select'
		,'title'    => esc_html__( 'Left Sidebar', 'ecomall' )
		,'subtitle' => ''
		,'desc'     => ''
		,'options'  => $sidebar_options
		,'default'  => 'product-category-sidebar'
		,'select2'	=> array('allowClear' => false, 'minimumResultsForSearch' => 'Infinity')
	)
	,array(
		'id'       	=> 'ts_prod_cat_right_sidebar'
		,'type'     => 'select'
		,'title'    => esc_html__( 'Right Sidebar', 'ecomall' )
		,'subtitle' => ''
		,'desc'     => ''
		,'options'  => $sidebar_options
		,'default'  => 'product-category-sidebar'
		,'select2'	=> array('allowClear' => false, 'minimumResultsForSearch' => 'Infinity')
	)
	,array(
		'id'        => 'section-shop-top-categories'
		,'type'     => 'section'
		,'title'    => esc_html__( 'Top Product Categories', 'ecomall' )
		,'subtitle' => esc_html__( 'These options are only available if shop/product category page displays both categories and products', 'ecomall' )
		,'indent'   => false
	)
	,array(
		'id'       	=> 'ts_top_cat_image_type'
		,'type'     => 'select'
		,'title'    => esc_html__( 'Top Product Categories Image Type', 'ecomall' )
		,'subtitle' => esc_html__( 'Add Thumbnail/Icon in the product category editor', 'ecomall' )
		,'desc'     => ''
		,'options'  => array(
			'thumbnail'		=> esc_html__( 'Thumbnail', 'ecomall' )
			,'icon'			=> esc_html__( 'Icon', 'ecomall' )
		)
		,'default'  => 'thumbnail'
		,'select2'	=> array('allowClear' => false, 'minimumResultsForSearch' => 'Infinity')
	)
	,array(
		'id'       	=> 'ts_top_cat_columns'
		,'type'     => 'select'
		,'title'    => esc_html__( 'Top Product Categories Columns', 'ecomall' )
		,'subtitle' => ''
		,'desc'     => ''
		,'options'  => array(
			'3'			=> '3'
			,'4'		=> '4'
			,'5'		=> '5'
			,'6'		=> '6'
		)
		,'default'  => '5'
		,'select2'	=> array('allowClear' => false, 'minimumResultsForSearch' => 'Infinity')
	)
	,array(
		'id'        => 'ts_top_cat_image_gap'
		,'type'     => 'text'
		,'title'    => esc_html__( 'Category Image Gap', 'ecomall' )
		,'subtitle' => esc_html__( 'The space between category image and category name in pixel', 'ecomall' )
		,'desc'     => ''
		,'default'  => '5'
		,'validate' => 'numeric'
	)
	,array(
		'id'        => 'ts_top_cat_slider'
		,'type'     => 'switch'
		,'title'    => esc_html__( 'Top Product Categories Slider', 'ecomall' )
		,'subtitle' => esc_html__( 'Slider is only enabled if number of items is not smaller than number of columns', 'ecomall' )
		,'default'  => true
	)
	
	,array(
		'id'        => 'section-shop-top-brands'
		,'type'     => 'section'
		,'title'    => esc_html__( 'Top Product Brands', 'ecomall' )
		,'subtitle' => ''
		,'indent'   => false
	)
	,array(
		'id'        => 'ts_top_brand'
		,'type'     => 'switch'
		,'title'    => esc_html__( 'Top Product Brands', 'ecomall' )
		,'subtitle' => esc_html__( 'Show list of product brands on the shop/product taxonomy pages', 'ecomall' )
		,'default'  => false
		,'on'		=> esc_html__( 'Show', 'ecomall' )
		,'off'		=> esc_html__( 'Hide', 'ecomall' )
	)
	,array(
		'id'        => 'ts_top_brand_based_selection'
		,'type'     => 'switch'
		,'title'    => esc_html__( 'Based On Selection', 'ecomall' )
		,'subtitle' => esc_html__( 'Show product brands based on the current products', 'ecomall' )
		,'default'  => true
	)
	,array(
		'id'       	=> 'ts_top_brand_columns'
		,'type'     => 'select'
		,'title'    => esc_html__( 'Top Product Brands Columns', 'ecomall' )
		,'subtitle' => ''
		,'desc'     => ''
		,'options'  => array(
			'3'			=> '3'
			,'4'		=> '4'
			,'5'		=> '5'
			,'6'		=> '6'
		)
		,'default'  => '5'
		,'select2'	=> array('allowClear' => false, 'minimumResultsForSearch' => 'Infinity')
	)
	,array(
		'id'        => 'ts_top_brand_limit'
		,'type'     => 'text'
		,'title'    => esc_html__( 'Limit Brands', 'ecomall' )
		,'subtitle' => esc_html__( 'For performance, if you have many brands, you should limit brands to show. Leave blank to show all', 'ecomall' )
		,'desc'     => ''
		,'default'  => '10'
		,'validate' => 'numeric'
	)
	
	,array(
		'id'        => 'section-shop-filters'
		,'type'     => 'section'
		,'title'    => esc_html__( 'SORT & FILTERS', 'ecomall' )
		,'subtitle' => ''
		,'indent'   => false
	)
	,array(
		'id'       	=> 'ts_prod_cat_columns'
		,'type'     => 'select'
		,'title'    => esc_html__( 'Product Columns', 'ecomall' )
		,'subtitle' => ''
		,'desc'     => ''
		,'options'  => array(
			'3'			=> '3'
			,'4'		=> '4'
			,'5'		=> '5'
			,'6'		=> '6'
		)
		,'default'  => '5'
		,'select2'	=> array('allowClear' => false, 'minimumResultsForSearch' => 'Infinity')
	)
	,array(
		'id'        => 'ts_prod_cat_per_page'
		,'type'     => 'text'
		,'title'    => esc_html__( 'Products Per Page', 'ecomall' )
		,'subtitle' => esc_html__( 'Number of products per page', 'ecomall' )
		,'desc'     => ''
		,'default'  => '12'
	)
	,array(
		'id'        => 'ts_filter_widget_area'
		,'type'     => 'switch'
		,'title'    => esc_html__( 'Filter Widget Area', 'ecomall' )
		,'subtitle' => esc_html__( 'Display Filter Widget Area on the Shop/Product Category page. If enabled, sidebar will be removed', 'ecomall' )
		,'default'  => false
		,'on'		=> esc_html__( 'Show', 'ecomall' )
		,'off'		=> esc_html__( 'Hide', 'ecomall' )
	)
	,array(
		'id'		=> 'ts_filter_widget_area_style'
		,'type'     => 'select'
		,'title'    => esc_html__( 'Filter Widget Area Style', 'ecomall' )
		,'subtitle' => ''
		,'desc'     => ''
		,'options'  => array(
			'top'			=> esc_html__( 'Filter Top', 'ecomall' )
			,'sidebar'		=> esc_html__( 'Filter Sidebar', 'ecomall' )
		)
		,'default'  => 'top'
		,'select2'	=> array( 'allowClear' => false, 'minimumResultsForSearch' => 'Infinity' )
		,'required'	=> array( 'ts_filter_widget_area', 'equals', '1' )
	)
	,array(
		'id'		=> 'ts_show_filter_widget_area_by_default'
		,'type'		=> 'switch'
		,'title'	=> esc_html__( 'Show Filter Widget Area By Default', 'ecomall' )
		,'subtitle'	=> ''
		,'desc'		=> ''
		,'default'	=> false
		,'on'		=> esc_html__( 'Show', 'ecomall' )
		,'off'		=> esc_html__( 'Hide', 'ecomall' )
		,'required'	=> array( 'ts_filter_widget_area_style', 'equals', 'sidebar' )	
	)
	,array(
		'id'        => 'ts_prod_cat_grid_list_toggle'
		,'type'     => 'switch'
		,'title'    => esc_html__( 'Grid/List Toggle', 'ecomall' )
		,'subtitle' => ''
		,'default'  => true
		,'on'		=> esc_html__( 'Show', 'ecomall' )
		,'off'		=> esc_html__( 'Hide', 'ecomall' )
	)
	,array(
		'id'       	=> 'ts_prod_grid_list_toggle_default'
		,'type'     => 'select'
		,'title'    => esc_html__( 'Grid/List Toggle Default', 'ecomall' )
		,'subtitle' => ''
		,'desc'     => ''
		,'options'  => array(
			'grid'		=> esc_html__( 'Grid', 'ecomall' ),
			'list'		=> esc_html__( 'List', 'ecomall' )
		)
		,'default'  => 'grid'
		,'select2'	=> array('allowClear' => false, 'minimumResultsForSearch' => 'Infinity')
		,'required'	=> array('ts_prod_cat_grid_list_toggle', 'equals', '1')
	)
	,array(
		'id'        => 'ts_prod_cat_per_page_dropdown'
		,'type'     => 'switch'
		,'title'    => esc_html__( 'Products Per Page Dropdown', 'ecomall' )
		,'subtitle' => esc_html__( 'Allow users to select number of products per page', 'ecomall' )
		,'default'  => true
		,'on'		=> esc_html__( 'Show', 'ecomall' )
		,'off'		=> esc_html__( 'Hide', 'ecomall' )
	)
	,array(
		'id'        => 'ts_prod_cat_onsale_checkbox'
		,'type'     => 'switch'
		,'title'    => esc_html__( 'Products On Sale Checkbox', 'ecomall' )
		,'subtitle' => esc_html__( 'Allow users to view only the discounted products', 'ecomall' )
		,'default'  => true
		,'on'		=> esc_html__( 'Show', 'ecomall' )
		,'off'		=> esc_html__( 'Hide', 'ecomall' )
	)
	,array(
		'id'        => 'ts_prod_cat_collapse_scroll_sidebar'
		,'type'     => 'switch'
		,'title'    => esc_html__( 'Collapse And Scroll Widgets In Sidebar', 'ecomall' )
		,'subtitle' => ''
		,'default'  => true
	)
	,array(
		'id'       	=> 'ts_prod_cat_loading_type'
		,'type'     => 'select'
		,'title'    => esc_html__( 'Product Loading Type', 'ecomall' )
		,'subtitle' => ''
		,'desc'     => ''
		,'options'  => array(
			'default'			=> esc_html__( 'Default', 'ecomall' )
			,'infinity-scroll'	=> esc_html__( 'Infinity Scroll', 'ecomall' )
			,'load-more-button'	=> esc_html__( 'Load More Button', 'ecomall' )
			,'ajax-pagination'	=> esc_html__( 'Ajax Pagination', 'ecomall' )
		)
		,'default'  => 'ajax-pagination'
		,'select2'	=> array('allowClear' => false, 'minimumResultsForSearch' => 'Infinity')
	)
	,array(
		'id'        => 'section-shop-product-options'
		,'type'     => 'section'
		,'title'    => esc_html__( 'PRODUCT OPTIONS', 'ecomall' )
		,'subtitle' => ''
		,'indent'   => false
	)
	,array(
		'id'        => 'ts_prod_cat_thumbnail'
		,'type'     => 'switch'
		,'title'    => esc_html__( 'Product Thumbnail', 'ecomall' )
		,'subtitle' => ''
		,'default'  => true
		,'on'		=> esc_html__( 'Show', 'ecomall' )
		,'off'		=> esc_html__( 'Hide', 'ecomall' )
	)
	,array(
		'id'        => 'ts_prod_cat_label'
		,'type'     => 'switch'
		,'title'    => esc_html__( 'Product Label', 'ecomall' )
		,'subtitle' => ''
		,'default'  => true
		,'on'		=> esc_html__( 'Show', 'ecomall' )
		,'off'		=> esc_html__( 'Hide', 'ecomall' )
	)
	,array(
		'id'        => 'ts_prod_cat_border'
		,'type'     => 'switch'
		,'title'    => esc_html__( 'Product Border', 'ecomall' )
		,'subtitle' => esc_html__( 'Show product border by default', 'ecomall' )
		,'default'  => false
		,'on'		=> esc_html__( 'Show', 'ecomall' )
		,'off'		=> esc_html__( 'Hide', 'ecomall' )
	)
	,array(
		'id'        => 'ts_prod_cat_brand'
		,'type'     => 'switch'
		,'title'    => esc_html__( 'Product Brands', 'ecomall' )
		,'subtitle' => ''
		,'default'  => false
		,'on'		=> esc_html__( 'Show', 'ecomall' )
		,'off'		=> esc_html__( 'Hide', 'ecomall' )
	)
	,array(
		'id'        => 'ts_prod_cat_cat'
		,'type'     => 'switch'
		,'title'    => esc_html__( 'Product Categories', 'ecomall' )
		,'subtitle' => ''
		,'default'  => false
		,'on'		=> esc_html__( 'Show', 'ecomall' )
		,'off'		=> esc_html__( 'Hide', 'ecomall' )
	)
	,array(
		'id'        => 'ts_prod_cat_title'
		,'type'     => 'switch'
		,'title'    => esc_html__( 'Product Title', 'ecomall' )
		,'subtitle' => ''
		,'default'  => true
		,'on'		=> esc_html__( 'Show', 'ecomall' )
		,'off'		=> esc_html__( 'Hide', 'ecomall' )
	)
	,array(
		'id'        => 'ts_prod_cat_sku'
		,'type'     => 'switch'
		,'title'    => esc_html__( 'Product SKU', 'ecomall' )
		,'subtitle' => ''
		,'default'  => false
		,'on'		=> esc_html__( 'Show', 'ecomall' )
		,'off'		=> esc_html__( 'Hide', 'ecomall' )
	)
	,array(
		'id'        => 'ts_prod_cat_rating'
		,'type'     => 'switch'
		,'title'    => esc_html__( 'Product Rating', 'ecomall' )
		,'subtitle' => ''
		,'default'  => false
		,'on'		=> esc_html__( 'Show', 'ecomall' )
		,'off'		=> esc_html__( 'Hide', 'ecomall' )
	)
	,array(
		'id'        => 'ts_prod_cat_price'
		,'type'     => 'switch'
		,'title'    => esc_html__( 'Product Price', 'ecomall' )
		,'subtitle' => ''
		,'default'  => true
		,'on'		=> esc_html__( 'Show', 'ecomall' )
		,'off'		=> esc_html__( 'Hide', 'ecomall' )
	)
	,array(
		'id'        => 'ts_prod_cat_add_to_cart'
		,'type'     => 'switch'
		,'title'    => esc_html__( 'Product Add To Cart Button', 'ecomall' )
		,'subtitle' => ''
		,'default'  => true
		,'on'		=> esc_html__( 'Show', 'ecomall' )
		,'off'		=> esc_html__( 'Hide', 'ecomall' )
	)
	,array(
		'id'        => 'ts_prod_cat_desc'
		,'type'     => 'switch'
		,'title'    => esc_html__( 'Product Short Description - Grid View', 'ecomall' )
		,'subtitle' => esc_html__( 'Show product description on grid view', 'ecomall' )
		,'default'  => false
		,'on'		=> esc_html__( 'Show', 'ecomall' )
		,'off'		=> esc_html__( 'Hide', 'ecomall' )
	)
	,array(
		'id'        => 'ts_prod_cat_desc_words'
		,'type'     => 'text'
		,'title'    => esc_html__( 'Product Short Description - Grid View - Limit Words', 'ecomall' )
		,'subtitle' => esc_html__( 'Number of words to show product description on grid view. It is also used for product elements. To show all, input -1', 'ecomall' )
		,'desc'     => esc_html__( 'HTML is allowed. So, if your description has html, make sure that this value is large enough. If not, your layout may be broken', 'ecomall' )
		,'default'  => '-1'
	)
	,array(
		'id'        => 'ts_prod_cat_list_desc'
		,'type'     => 'switch'
		,'title'    => esc_html__( 'Product Short Description - List View', 'ecomall' )
		,'subtitle' => esc_html__( 'Show product description on list view', 'ecomall' )
		,'default'  => true
		,'on'		=> esc_html__( 'Show', 'ecomall' )
		,'off'		=> esc_html__( 'Hide', 'ecomall' )
	)
	,array(
		'id'        => 'ts_prod_cat_list_desc_words'
		,'type'     => 'text'
		,'title'    => esc_html__( 'Product Short Description - List View - Limit Words', 'ecomall' )
		,'subtitle' => esc_html__( 'Number of words to show product description on list view. It is also used for product elements. To show all, input -1', 'ecomall' )
		,'desc'     => esc_html__( 'HTML is allowed. So, if your description has html, make sure that this value is large enough. If not, your layout may be broken', 'ecomall' )
		,'default'  => '-1'
	)
	,array(
		'id'        => 'ts_prod_cat_color_swatch'
		,'type'     => 'switch'
		,'title'    => esc_html__( 'Product Color Swatches', 'ecomall' )
		,'subtitle' => esc_html__( 'Show the color attribute of variations. The slug of the color attribute has to be "color"', 'ecomall' )
		,'default'  => false
		,'on'		=> esc_html__( 'Show', 'ecomall' )
		,'off'		=> esc_html__( 'Hide', 'ecomall' )
	)
	,array(
		'id'       	=> 'ts_prod_cat_number_color_swatch'
		,'type'     => 'select'
		,'title'    => esc_html__( 'Number Of Color Swatches', 'ecomall' )
		,'subtitle' => ''
		,'desc'     => ''
		,'options'  => array(
			2	=> 2
			,3	=> 3
			,4	=> 4
			,5	=> 5
			,6	=> 6
		)
		,'default'  => '3'
		,'select2'	=> array('allowClear' => false, 'minimumResultsForSearch' => 'Infinity')
		,'required'	=> array( 'ts_prod_cat_color_swatch', 'equals', '1' )
	)
);

/*** Product Details Tab ***/
$option_fields['product-details'] = array(
	array(
		'id'        => 'ts_prod_layout'
		,'type'     => 'image_select'
		,'title'    => esc_html__( 'Product Layout', 'ecomall' )
		,'subtitle' => ''
		,'desc'     => ''
		,'options'  => array(
			'0-1-0' => array(
				'alt'  => esc_html__('Fullwidth', 'ecomall')
				,'img' => $redux_url . 'assets/img/1col.png'
			)
			,'1-1-0' => array(
				'alt'  => esc_html__('Left Sidebar', 'ecomall')
				,'img' => $redux_url . 'assets/img/2cl.png'
			)
			,'0-1-1' => array(
				'alt'  => esc_html__('Right Sidebar', 'ecomall')
				,'img' => $redux_url . 'assets/img/2cr.png'
			)
			,'1-1-1' => array(
				'alt'  => esc_html__('Left & Right Sidebar', 'ecomall')
				,'img' => $redux_url . 'assets/img/3cm.png'
			)
		)
		,'default'  => '0-1-0'
	)
	,array(
		'id'       	=> 'ts_prod_left_sidebar'
		,'type'     => 'select'
		,'title'    => esc_html__( 'Left Sidebar', 'ecomall' )
		,'subtitle' => ''
		,'desc'     => ''
		,'options'  => $sidebar_options
		,'default'  => 'product-detail-sidebar'
		,'select2'	=> array('allowClear' => false, 'minimumResultsForSearch' => 'Infinity')
	)
	,array(
		'id'       	=> 'ts_prod_right_sidebar'
		,'type'     => 'select'
		,'title'    => esc_html__( 'Right Sidebar', 'ecomall' )
		,'subtitle' => ''
		,'desc'     => ''
		,'options'  => $sidebar_options
		,'default'  => 'product-detail-sidebar'
		,'select2'	=> array('allowClear' => false, 'minimumResultsForSearch' => 'Infinity')
	)
	,array(
		'id'       	=> 'ts_prod_layout_fullwidth'
		,'type'     => 'select'
		,'title'    => esc_html__( 'Product Layout Fullwidth', 'ecomall' )
		,'subtitle' => esc_html__( 'Override the Layout Fullwidth option in the General tab', 'ecomall' )
		,'desc'     => ''
		,'options'  => array(
			'default'	=> esc_html__( 'Default', 'ecomall' )
			,'0'		=> esc_html__( 'No', 'ecomall' )
			,'1'		=> esc_html__( 'Yes', 'ecomall' )
		)
		,'default'  => 'default'
		,'select2'	=> array('allowClear' => false, 'minimumResultsForSearch' => 'Infinity')
	)
	,array(
		'id'        => 'ts_prod_header_layout_fullwidth'
		,'type'     => 'switch'
		,'title'    => esc_html__( 'Product Header Layout Fullwidth', 'ecomall' )
		,'subtitle' => ''
		,'default'  => true
		,'required'	=> array( 'ts_prod_layout_fullwidth', 'equals', '1' )
	)
	,array(
		'id'        => 'ts_prod_main_content_layout_fullwidth'
		,'type'     => 'switch'
		,'title'    => esc_html__( 'Product Main Content Layout Fullwidth', 'ecomall' )
		,'subtitle' => ''
		,'default'  => false
		,'required'	=> array( 'ts_prod_layout_fullwidth', 'equals', '1' )
	)
	,array(
		'id'        => 'ts_prod_footer_layout_fullwidth'
		,'type'     => 'switch'
		,'title'    => esc_html__( 'Product Footer Layout Fullwidth', 'ecomall' )
		,'subtitle' => ''
		,'default'  => false
		,'required'	=> array( 'ts_prod_layout_fullwidth', 'equals', '1' )
	)
	,array(
		'id'        => 'ts_prod_breadcrumb'
		,'type'     => 'switch'
		,'title'    => esc_html__( 'Product Breadcrumb', 'ecomall' )
		,'subtitle' => ''
		,'default'  => true
	)
	,array(
		'id'        => 'ts_prod_cloudzoom'
		,'type'     => 'switch'
		,'title'    => esc_html__( 'Product Cloud Zoom', 'ecomall' )
		,'subtitle' => ''
		,'default'  => true
	)
	,array(
		'id'        => 'ts_prod_lightbox'
		,'type'     => 'switch'
		,'title'    => esc_html__( 'Product Lightbox', 'ecomall' )
		,'subtitle' => ''
		,'default'  => true
	)
	,array(
		'id'        => 'ts_prod_attr_dropdown'
		,'type'     => 'switch'
		,'title'    => esc_html__( 'Product Attribute Dropdown', 'ecomall' )
		,'subtitle' => esc_html__( 'If you turn it off, the dropdown will be replaced by image or text label', 'ecomall' )
		,'default'  => true
	)
	,array(
		'id'        => 'ts_prod_attr_color_text'
		,'type'     => 'switch'
		,'title'    => esc_html__( 'Product Color Attribute Text', 'ecomall' )
		,'subtitle' => esc_html__( 'Show text for the Color attribute instead of color/color image', 'ecomall' )
		,'default'  => false
		,'required'	=> array( 'ts_prod_attr_dropdown', 'equals', '0' )
	)
	,array(
		'id'        => 'ts_prod_attr_color_variation_thumbnail'
		,'type'     => 'switch'
		,'title'    => esc_html__( 'Product Color Attribute Variation Thumbnail', 'ecomall' )
		,'subtitle' => esc_html__( 'Use the variation thumbnail for the Color attribute. The Color slug has to be "color". You need to specify Color for variation (not any)', 'ecomall' )
		,'default'  => true
		,'required'	=> array( 'ts_prod_attr_color_text', 'equals', '0' )
	)
	,array(
		'id'        => 'ts_prod_next_prev_navigation'
		,'type'     => 'switch'
		,'title'    => esc_html__( 'Next/Prev Product Navigation', 'ecomall' )
		,'subtitle' => ''
		,'default'  => false
		,'on'		=> esc_html__( 'Show', 'ecomall' )
		,'off'		=> esc_html__( 'Hide', 'ecomall' )
	)
	,array(
		'id'        => 'ts_prod_thumbnail'
		,'type'     => 'switch'
		,'title'    => esc_html__( 'Product Thumbnail', 'ecomall' )
		,'subtitle' => ''
		,'default'  => true
		,'on'		=> esc_html__( 'Show', 'ecomall' )
		,'off'		=> esc_html__( 'Hide', 'ecomall' )
	)
	,array(
		'id'       	=> 'ts_prod_gallery_layout'
		,'type'     => 'select'
		,'title'    => esc_html__( 'Product Gallery Layout', 'ecomall' )
		,'subtitle' => ''
		,'desc'     => ''
		,'options'  => array(
			'vertical'		=> esc_html__( 'Vertical', 'ecomall' )
			,'horizontal'	=> esc_html__( 'Horizontal', 'ecomall' )
			,'grid'			=> esc_html__( 'Grid', 'ecomall' )
		)
		,'default'  => 'vertical'
		,'select2'	=> array('allowClear' => false, 'minimumResultsForSearch' => 'Infinity')
	)
	,array(
		'id'        => 'ts_prod_thumbnails_slider_mobile'
		,'type'     => 'switch'
		,'title'    => esc_html__( 'Product Thumbnails Slider On Mobile', 'ecomall' )
		,'subtitle' => esc_html__( 'If enabled, it will change all thumbnail/gallery layouts to slider on mobile', 'ecomall' )
		,'default'  => true
		,'required'	=> array('ts_prod_gallery_layout', 'equals', 'grid')
	)
	,array(
		'id'        => 'ts_prod_group_heading'
		,'type'     => 'text'
		,'title'    => esc_html__( 'Heading For Grouped Product', 'ecomall' )
		,'subtitle' => esc_html__( 'Show this heading above list of grouped products', 'ecomall' )
		,'desc'     => ''
		,'default'  => 'Part Of This Collection'
	)
	,array(
		'id'        => 'ts_prod_wfbt_in_summary'
		,'type'     => 'switch'
		,'title'    => esc_html__( 'Frequently Bought Together In Summary', 'ecomall' )
		,'subtitle' => esc_html__( 'Only available in PC', 'ecomall' )
		,'default'  => true
	)
	,array(
		'id'        => 'ts_prod_label'
		,'type'     => 'switch'
		,'title'    => esc_html__( 'Product Label', 'ecomall' )
		,'subtitle' => ''
		,'default'  => true
		,'on'		=> esc_html__( 'Show', 'ecomall' )
		,'off'		=> esc_html__( 'Hide', 'ecomall' )
	)
	,array(
		'id'        => 'ts_prod_title'
		,'type'     => 'switch'
		,'title'    => esc_html__( 'Product Title', 'ecomall' )
		,'subtitle' => ''
		,'default'  => true
		,'on'		=> esc_html__( 'Show', 'ecomall' )
		,'off'		=> esc_html__( 'Hide', 'ecomall' )
	)
	,array(
		'id'        => 'ts_prod_title_in_content'
		,'type'     => 'switch'
		,'title'    => esc_html__( 'Product Title In Content', 'ecomall' )
		,'subtitle' => esc_html__( 'Display the product title in the page content instead of above the breadcrumbs', 'ecomall' )
		,'default'  => true
	)
	,array(
		'id'        => 'ts_prod_rating'
		,'type'     => 'switch'
		,'title'    => esc_html__( 'Product Rating', 'ecomall' )
		,'subtitle' => ''
		,'default'  => true
		,'on'		=> esc_html__( 'Show', 'ecomall' )
		,'off'		=> esc_html__( 'Hide', 'ecomall' )
	)
	,array(
		'id'        => 'ts_prod_sku'
		,'type'     => 'switch'
		,'title'    => esc_html__( 'Product SKU', 'ecomall' )
		,'subtitle' => ''
		,'default'  => true
		,'on'		=> esc_html__( 'Show', 'ecomall' )
		,'off'		=> esc_html__( 'Hide', 'ecomall' )
	)
	,array(
		'id'        => 'ts_prod_availability'
		,'type'     => 'switch'
		,'title'    => esc_html__( 'Product Availability', 'ecomall' )
		,'subtitle' => ''
		,'default'  => true
		,'on'		=> esc_html__( 'Show', 'ecomall' )
		,'off'		=> esc_html__( 'Hide', 'ecomall' )
	)
	,array(
		'id'        => 'ts_prod_short_desc'
		,'type'     => 'switch'
		,'title'    => esc_html__( 'Product Short Description', 'ecomall' )
		,'subtitle' => ''
		,'default'  => false
		,'on'		=> esc_html__( 'Show', 'ecomall' )
		,'off'		=> esc_html__( 'Hide', 'ecomall' )
	)
	,array(
		'id'        => 'ts_prod_count_down'
		,'type'     => 'switch'
		,'title'    => esc_html__( 'Product Count Down', 'ecomall' )
		,'subtitle' => esc_html__( 'You have to activate ThemeSky plugin', 'ecomall' )
		,'default'  => true
		,'on'		=> esc_html__( 'Show', 'ecomall' )
		,'off'		=> esc_html__( 'Hide', 'ecomall' )
	)
	,array(
		'id'        => 'ts_prod_price'
		,'type'     => 'switch'
		,'title'    => esc_html__( 'Product Price', 'ecomall' )
		,'subtitle' => ''
		,'default'  => true
		,'on'		=> esc_html__( 'Show', 'ecomall' )
		,'off'		=> esc_html__( 'Hide', 'ecomall' )
	)
	,array(
		'id'        => 'ts_prod_discount_percent'
		,'type'     => 'switch'
		,'title'    => esc_html__( 'Product Discount Percent', 'ecomall' )
		,'subtitle' => esc_html__( 'Show discount percent next to the price', 'ecomall' )
		,'default'  => true
		,'on'		=> esc_html__( 'Show', 'ecomall' )
		,'off'		=> esc_html__( 'Hide', 'ecomall' )
		,'required'	=> array( 'ts_prod_price', 'equals', '1' )
	)
	,array(
		'id'        => 'ts_prod_add_to_cart'
		,'type'     => 'switch'
		,'title'    => esc_html__( 'Product Add To Cart Button', 'ecomall' )
		,'subtitle' => ''
		,'default'  => true
		,'on'		=> esc_html__( 'Show', 'ecomall' )
		,'off'		=> esc_html__( 'Hide', 'ecomall' )
	)
	,array(
		'id'        => 'ts_prod_ajax_add_to_cart'
		,'type'     => 'switch'
		,'title'    => esc_html__( 'Product Ajax Add To Cart', 'ecomall' )
		,'subtitle' => ''
		,'default'  => true
		,'required'	=> array( 'ts_prod_add_to_cart', 'equals', '1' )
	)
	,array(
		'id'        => 'ts_prod_buy_now'
		,'type'     => 'switch'
		,'title'    => esc_html__( 'Product Buy Now Button', 'ecomall' )
		,'subtitle' => esc_html__( 'Only support the simple and variable products', 'ecomall' )
		,'default'  => true
		,'on'		=> esc_html__( 'Show', 'ecomall' )
		,'off'		=> esc_html__( 'Hide', 'ecomall' )
	)
	,array(
		'id'        => 'ts_prod_brand'
		,'type'     => 'switch'
		,'title'    => esc_html__( 'Product Brands', 'ecomall' )
		,'subtitle' => ''
		,'default'  => true
		,'on'		=> esc_html__( 'Show', 'ecomall' )
		,'off'		=> esc_html__( 'Hide', 'ecomall' )
	)
	,array(
		'id'        => 'ts_prod_cat'
		,'type'     => 'switch'
		,'title'    => esc_html__( 'Product Categories', 'ecomall' )
		,'subtitle' => ''
		,'default'  => false
		,'on'		=> esc_html__( 'Show', 'ecomall' )
		,'off'		=> esc_html__( 'Hide', 'ecomall' )
	)
	,array(
		'id'        => 'ts_prod_tag'
		,'type'     => 'switch'
		,'title'    => esc_html__( 'Product Tags', 'ecomall' )
		,'subtitle' => ''
		,'default'  => true
		,'on'		=> esc_html__( 'Show', 'ecomall' )
		,'off'		=> esc_html__( 'Hide', 'ecomall' )
	)
	,array(
		'id'        => 'ts_prod_size_chart'
		,'type'     => 'switch'
		,'title'    => esc_html__( 'Product Size Chart', 'ecomall' )
		,'subtitle' => esc_html__( 'Size Chart Popup is only available if Attribute Dropdown is disabled and the slug of the Size attribute contain "size". Ex: taille-size', 'ecomall' )
		,'default'  => true
		,'on'		=> esc_html__( 'Show', 'ecomall' )
		,'off'		=> esc_html__( 'Hide', 'ecomall' )
	)
	,array(
		'id'        => 'ts_prod_more_less_content'
		,'type'     => 'switch'
		,'title'    => esc_html__( 'Product More/Less Content', 'ecomall' )
		,'subtitle' => esc_html__( 'Show more/less content in the Description tab', 'ecomall' )
		,'default'  => false
	)
	,array(
		'id'        => 'ts_prod_sharing'
		,'type'     => 'switch'
		,'title'    => esc_html__( 'Product Sharing', 'ecomall' )
		,'subtitle' => ''
		,'default'  => true
		,'on'		=> esc_html__( 'Show', 'ecomall' )
		,'off'		=> esc_html__( 'Hide', 'ecomall' )
	)
	,array(
		'id'        => 'ts_prod_sharing_sharethis'
		,'type'     => 'switch'
		,'title'    => esc_html__( 'Product Sharing - Use ShareThis', 'ecomall' )
		,'subtitle' => esc_html__( 'Use share buttons from sharethis.com. You need to add key below', 'ecomall' )
		,'default'  => false
		,'required'	=> array( 'ts_prod_sharing', 'equals', '1' )
	)
	,array(
		'id'        => 'ts_prod_sharing_sharethis_key'
		,'type'     => 'text'
		,'title'    => esc_html__( 'Product Sharing - ShareThis Key', 'ecomall' )
		,'subtitle' => esc_html__( 'You get it from script code. It is the value of "property" attribute', 'ecomall' )
		,'desc'     => ''
		,'default'  => ''
		,'required'	=> array( 'ts_prod_sharing', 'equals', '1' )
	)
	,array(
		'id'        => 'ts_prod_summary_custom_content'
		,'type'     => 'editor'
		,'title'    => esc_html__( 'Product Summary Custom Content', 'ecomall' )
		,'subtitle' => esc_html__( 'Add your custom content to summary area', 'ecomall' )
		,'desc'     => ''
		,'default'  => ''
		,'args'     => array(
			'wpautop'        => false
			,'media_buttons' => true
			,'textarea_rows' => 5
			,'teeny'         => false
			,'quicktags'     => true
		)
	)

	,array(
		'id'        => 'section-product-tabs'
		,'type'     => 'section'
		,'title'    => esc_html__( 'Product Tabs', 'ecomall' )
		,'subtitle' => ''
		,'indent'   => false
	)
	,array(
		'id'        => 'ts_prod_tabs'
		,'type'     => 'switch'
		,'title'    => esc_html__( 'Product Tabs', 'ecomall' )
		,'subtitle' => ''
		,'default'  => true
		,'on'		=> esc_html__( 'Show', 'ecomall' )
		,'off'		=> esc_html__( 'Hide', 'ecomall' )
	)
	,array(
		'id'       	=> 'ts_prod_tabs_position'
		,'type'     => 'select'
		,'title'    => esc_html__( 'Product Tabs Position', 'ecomall' )
		,'subtitle' => ''
		,'desc'     => ''
		,'options'  => array(
			'after_summary'				=> esc_html__( 'After Summary', 'ecomall' )
			,'inside_summary'			=> esc_html__( 'Inside Summary', 'ecomall' )
		)
		,'default'  => 'after_summary'
		,'select2'	=> array('allowClear' => false, 'minimumResultsForSearch' => 'Infinity')
	)
	,array(
		'id'       	=> 'ts_prod_tabs_accordion'
		,'type'     => 'select'
		,'title'    => esc_html__( 'Product Tabs Accordion', 'ecomall' )
		,'subtitle' => esc_html__( 'Show tabs as accordion. If you add more custom tabs, please make sure that your tab content has heading (h2) at the top', 'ecomall' )
		,'desc'     => ''
		,'options'  => array(
			'0'				=> esc_html__( 'None', 'ecomall' )
			,'desktop'		=> esc_html__( 'On Desktop', 'ecomall' )
			,'mobile'		=> esc_html__( 'On Mobile', 'ecomall' )
			,'both'			=> esc_html__( 'On All Screens', 'ecomall' )
		)
		,'default'  => 'mobile'
		,'select2'	=> array('allowClear' => false, 'minimumResultsForSearch' => 'Infinity')
	)
	,array(
		'id'        => 'ts_prod_custom_tab'
		,'type'     => 'switch'
		,'title'    => esc_html__( 'Product Custom Tab', 'ecomall' )
		,'subtitle' => ''
		,'default'  => false
		,'on'		=> esc_html__( 'Show', 'ecomall' )
		,'off'		=> esc_html__( 'Hide', 'ecomall' )
	)
	,array(
		'id'        => 'ts_prod_custom_tab_title'
		,'type'     => 'text'
		,'title'    => esc_html__( 'Product Custom Tab Title', 'ecomall' )
		,'subtitle' => ''
		,'desc'     => ''
		,'default'  => 'Custom tab'
	)
	,array(
		'id'        => 'ts_prod_custom_tab_content'
		,'type'     => 'editor'
		,'title'    => esc_html__( 'Product Custom Tab Content', 'ecomall' )
		,'subtitle' => ''
		,'desc'     => ''
		,'default'  => esc_html__( 'Your custom content goes here. You can add the content for individual product', 'ecomall' )
		,'args'     => array(
			'wpautop'        => false
			,'media_buttons' => true
			,'textarea_rows' => 5
			,'teeny'         => false
			,'quicktags'     => true
		)
	)
	
	,array(
		'id'        => 'section-ads-banner'
		,'type'     => 'section'
		,'title'    => esc_html__( 'Ads Banner', 'ecomall' )
		,'subtitle' => ''
		,'indent'   => false
	)
	,array(
		'id'        => 'ts_prod_ads_banner'
		,'type'     => 'switch'
		,'title'    => esc_html__( 'Ads Banner', 'ecomall' )
		,'subtitle' => ''
		,'default'  => false
		,'on'		=> esc_html__( 'Show', 'ecomall' )
		,'off'		=> esc_html__( 'Hide', 'ecomall' )
	)
	,array(
		'id'        => 'ts_prod_ads_banner_content'
		,'type'     => 'editor'
		,'title'    => esc_html__( 'Ads Banner Content', 'ecomall' )
		,'subtitle' => ''
		,'desc'     => ''
		,'default'  => ''
		,'args'     => array(
			'wpautop'        => false
			,'media_buttons' => true
			,'textarea_rows' => 5
			,'teeny'         => false
			,'quicktags'     => true
		)
	)
	
	,array(
		'id'        => 'section-related-up-sell-products'
		,'type'     => 'section'
		,'title'    => esc_html__( 'Related - Up-Sell', 'ecomall' )
		,'subtitle' => ''
		,'indent'   => false
	)
	,array(
		'id'        => 'ts_prod_related'
		,'type'     => 'switch'
		,'title'    => esc_html__( 'Related Products', 'ecomall' )
		,'subtitle' => ''
		,'default'  => true
		,'on'		=> esc_html__( 'Show', 'ecomall' )
		,'off'		=> esc_html__( 'Hide', 'ecomall' )
	)
	,array(
		'id'        => 'ts_prod_upsells'
		,'type'     => 'switch'
		,'title'    => esc_html__( 'Up-Sell Products', 'ecomall' )
		,'subtitle' => ''
		,'default'  => false
		,'on'		=> esc_html__( 'Show', 'ecomall' )
		,'off'		=> esc_html__( 'Hide', 'ecomall' )
	)
);

/*** Custom Code Tab ***/
$option_fields['custom-code'] = array(
	array(
		'id'        => 'ts_custom_css_code'
		,'type'     => 'ace_editor'
		,'title'    => esc_html__( 'Custom CSS Code', 'ecomall' )
		,'subtitle' => ''
		,'mode'     => 'css'
		,'theme'    => 'monokai'
		,'desc'     => ''
		,'default'  => ''
	)
	,array(
		'id'        => 'ts_custom_javascript_code'
		,'type'     => 'ace_editor'
		,'title'    => esc_html__( 'Custom Javascript Code', 'ecomall' )
		,'subtitle' => ''
		,'mode'     => 'javascript'
		,'theme'    => 'monokai'
		,'desc'     => ''
		,'default'  => ''
	)
);