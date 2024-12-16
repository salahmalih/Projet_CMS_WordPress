<?php 
$options = array();

$options[] = array(
				'id'		=> 'logo_url'
				,'label'	=> esc_html__('Logo URL', 'themesky')
				,'desc'		=> ''
				,'type'		=> 'text'
			);
			
$options[] = array(
				'id'		=> 'logo_target'
				,'label'	=> esc_html__('Target', 'themesky')
				,'desc'		=> ''
				,'type'		=> 'select'
				,'options'	=> array(
							'_self'		=> esc_html__('Self', 'themesky')
							,'_blank'	=> esc_html__('New Window Tab', 'themesky')
						)
			);
?>