<?php 
$options = array();

$product_categories = get_terms(
							array(
								'taxonomy'		=> 'product_cat'
								,'hide_empty'	=> false
								,'fields'		=> 'id=>name'
							)
						);
if( !is_array($product_categories) ){
	$product_categories = array();
}

$options[] = array(
				'id'		=> 'chart_label'
				,'label'	=> esc_html__('Chart Label', 'themesky')
				,'desc'		=> ''
				,'type'		=> 'text'
			);
			
$options[] = array(
				'id'		=> 'chart_categories'
				,'label'	=> esc_html__('Chart Categories', 'themesky')
				,'desc'		=> esc_html__('Display size chart for products in these categories', 'themesky')
				,'type'		=> 'multi_select'
				,'options'	=> $product_categories
			);
			
$options[] = array(
				'id'		=> 'chart_image'
				,'label'	=> esc_html__('Chart Image', 'themesky')
				,'desc'		=> ''
				,'type'		=> 'upload'
			);
			
$options[] = array(
				'id'		=> 'chart_table'
				,'label'	=> esc_html__('Chart Table', 'themesky')
				,'desc'		=> ''
				,'type'		=> 'table'
			);