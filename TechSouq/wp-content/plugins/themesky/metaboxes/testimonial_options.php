<?php 
$options = array();

$options[] = array(
				'id'		=> 'gravatar_email'
				,'label'	=> esc_html__('Gravatar Email Address', 'themesky')
				,'desc'		=> esc_html__('Enter an e-mail address to display Gravatar profile image instead of using the "Featured Image". You have to remove the "Featured Image".', 'themesky')
				,'type'		=> 'text'
			);
			
$options[] = array(
				'id'		=> 'byline'
				,'label'	=> esc_html__('Byline', 'themesky')
				,'desc'		=> esc_html__('Enter a byline for the customer giving this testimonial. For example: CEO of Theme-Sky', 'themesky')
				,'type'		=> 'text'
			);
			
$options[] = array(
				'id'		=> 'url'
				,'label'	=> esc_html__('URL', 'themesky')
				,'desc'		=> esc_html__('Enter an URL that applies to this customer. For example: http://theme-sky.com/', 'themesky')
				,'type'		=> 'text'
			);
			
$options[] = array(
				'id'		=> 'rating'
				,'label'	=> esc_html__('Rating', 'themesky')
				,'desc'		=> ''
				,'type'		=> 'select'
				,'options'	=> array(
						'-1'	=> esc_html__('no rating', 'themesky')
						,'1'	=> esc_html__('1 star', 'themesky')
						,'1.5'	=> esc_html__('1.5 star', 'themesky')
						,'2'	=> esc_html__('2 stars', 'themesky')
						,'2.5'	=> esc_html__('2.5 stars', 'themesky')
						,'3'	=> esc_html__('3 stars', 'themesky')
						,'3.5'	=> esc_html__('3.5 stars', 'themesky')
						,'4'	=> esc_html__('4 stars', 'themesky')
						,'4.5'	=> esc_html__('4.5 stars', 'themesky')
						,'5'	=> esc_html__('5 stars', 'themesky')
				)
			);
?>