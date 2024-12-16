<?php 
$theme_options = ecomall_get_theme_options();
$facebook_url = $theme_options['ts_facebook_url'];
$twitter_url = $theme_options['ts_twitter_url'];
$youtube_url = $theme_options['ts_youtube_url'];
$instagram_url = $theme_options['ts_instagram_url'];
$pinterest_url = $theme_options['ts_pinterest_url'];
$linkedin_url = $theme_options['ts_linkedin_url'];
$custom_url = $theme_options['ts_custom_social_url'];
$custom_class = $theme_options['ts_custom_social_class'];
$custom_text = '';
?>
<div class="social-icons">
	<ul>
		<?php do_action('ts_header_social_icons_before'); ?>
			
		<?php if( $facebook_url ): ?>
		<li class="tb-icon-brand-facebook">
			<a href="<?php echo esc_url($facebook_url); ?>" target="_blank"><?php esc_html_e('Facebook', 'themesky'); ?></a>
		</li>
		<?php endif; ?>

		<?php if( $twitter_url ): ?>
		<li class="tb-icon-brand-twitter">
			<a href="<?php echo esc_url($twitter_url); ?>" target="_blank"><?php esc_html_e('Twitter', 'themesky'); ?></a>
		</li>
		<?php endif; ?>
		
		<?php if( $instagram_url ): ?>
		<li class="tb-icon-brand-instagram">
			<a href="<?php echo esc_url($instagram_url); ?>" target="_blank"><?php esc_html_e('Instagram', 'themesky'); ?></a>
		</li>
		<?php endif; ?>
		
		<?php if( $pinterest_url ): ?>
		<li class="tb-icon-brand-pinterest">
			<a href="<?php echo esc_url($pinterest_url); ?>" target="_blank"><?php esc_html_e('Pinterest', 'themesky'); ?></a>
		</li>
		<?php endif; ?>
		
		<?php if( $youtube_url ): ?>
		<li class="tb-icon-brand-youtube">
			<a href="<?php echo esc_url($youtube_url); ?>" target="_blank"><?php esc_html_e('Youtube', 'themesky'); ?></a>
		</li>
		<?php endif; ?>
		
		<?php if( $linkedin_url ): ?>
		<li class="tb-icon-brand-linkedin">
			<a href="<?php echo esc_url($linkedin_url); ?>" target="_blank"><?php esc_html_e('LinkedIn', 'themesky'); ?></a>
		</li>
		<?php endif; ?>
		
		<?php if( $custom_url ): ?>
		<li class="<?php echo esc_attr($custom_class) ?>">
			<a href="<?php echo esc_url($custom_url); ?>" target="_blank"><?php echo esc_html($custom_text); ?></a>
		</li>
		<?php endif; ?>
		
		<?php do_action('ts_header_social_icons_after'); ?>
	</ul>
</div>