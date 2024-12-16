<?php  
class TS_Shortcodes{
	
	function __construct(){
		$this->add_shortcodes();
		
		add_filter('the_content', array($this, 'remove_extra_p_tag'));
		add_filter('widget_text', array($this, 'remove_extra_p_tag'));
		
		add_action('wp_enqueue_scripts', array($this, 'register_scripts'));
		add_action('admin_enqueue_scripts', array($this, 'register_admin_scripts'));
	}
	
	function add_shortcodes(){
		add_shortcode('ts_video', array($this, 'video_shortcode'));
		
		add_shortcode('ts_soundcloud', array($this, 'soundcloud_shortcode'));
	}
	
	function video_shortcode( $atts ){
		extract( shortcode_atts(array(
					'src' 		=> '',
					'height' 	=> '450',
					'width' 	=> '800'
				), $atts
			));
		if( $src == '' ){
			return;
		}
		
		$extra_class = '';
		if( !isset($atts['height']) || !isset($atts['width']) ){
			$extra_class = 'auto-size';
		}
		
		$src = $this->parse_video_link($src);
		ob_start();
		?>
			<div class="ts-video <?php echo esc_attr($extra_class); ?>" style="width:<?php echo esc_attr($width) ?>px; height:<?php echo esc_attr($height) ?>px;">
				<iframe width="<?php echo esc_attr($width) ?>" height="<?php echo esc_attr($height) ?>" src="<?php echo esc_url($src); ?>" allowfullscreen></iframe>
			</div>
		<?php
		return ob_get_clean();
	}
	
	function parse_video_link( $video_url ){
		if( strstr($video_url, 'youtube.com') || strstr($video_url, 'youtu.be') ){
			preg_match('%(?:youtube\.com/(?:user/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $video_url, $match);
			if( count($match) >= 2 ){
				return '//www.youtube.com/embed/' . $match[1];
			}
		}
		elseif( strstr($video_url, 'vimeo.com') ){
			preg_match('~^http://(?:www\.)?vimeo\.com/(?:clip:)?(\d+)~', $video_url, $match);
			if( count($match) >= 2 ){
				return '//player.vimeo.com/video/' . $match[1];
			}
			else{
				$video_id = explode('/', $video_url);
				if( is_array($video_id) && !empty($video_id) ){
					$video_id = $video_id[count($video_id) - 1];
					return '//player.vimeo.com/video/' . $video_id;
				}
			}
		}
		return $video_url;
	}
	
	function soundcloud_shortcode( $atts, $content ){
		extract(shortcode_atts(array(
			'params'		=> "color=ff5500&auto_play=false&hide_related=false&show_comments=true&show_user=true&show_reposts=false"
			,'url'			=> ''
			,'width'		=> '100%'
			,'height'		=> '166'
			,'iframe'		=> 1
		),$atts));
		
		$atts = compact( 'params', 'url', 'width', 'height', 'iframe' );
		
		if( $iframe ){
			return $this->soundcloud_iframe_widget( $atts );
		}
		else{ 
			return $this->soundcloud_flash_widget( $atts );
		}
	}
	
	function soundcloud_iframe_widget($options) {
		$url = 'https://w.soundcloud.com/player/?url=' . $options['url'] . '&' . $options['params'];
		$unique_class = 'ts-soundcloud-'.rand();
		$style = '.'.$unique_class.' iframe{width: '.$options['width'].'; height:'.$options['height'].'px;}';
		$style = '<style type="text/css" scoped>'.$style.'</style>';
		return '<div class="ts-soundcloud '.$unique_class.'">'.$style.'<iframe src="'.esc_url( $url ).'"></iframe></div>';
	}

	function soundcloud_flash_widget( $options ){
		$url = 'https://player.soundcloud.com/player.swf?url=' . $options['url'] . '&' . $options['params'];
		
		return preg_replace('/\s\s+/', '', sprintf('<div class="ts-soundcloud"><object width="%s" height="%s">
								<param name="movie" value="%s"></param>
								<param name="allowscriptaccess" value="always"></param>
								<embed width="%s" height="%s" src="%s" allowscriptaccess="always" type="application/x-shockwave-flash"></embed>
							  </object></div>', $options['width'], $options['height'], esc_url( $url ), $options['width'], $options['height'], esc_url( $url )));
	}
	
	function remove_extra_p_tag( $content ){
	
		$block = join("|", array('ts_button'));
		/* opening tag */
		$rep = preg_replace("/(<p>)?\[($block)(\s[^\]]+)?\](<\/p>|<br \/>)?/","[$2$3]",$content);
			
		/* closing tag */
		$rep = preg_replace("/(<p>)?\[\/($block)](<\/p>|<br \/>)?/","[/$2]",$rep);
	 
		return $rep;
	}
	
	function register_scripts(){
		$js_dir = plugin_dir_url( __FILE__ ) . 'js';
		$css_dir = plugin_dir_url( __FILE__ ) . 'css';
		
		wp_enqueue_style( 'ts-style', $css_dir . '/themesky.css', array(), THEMESKY_VERSION );

		wp_deregister_style( 'swiper' ); /* remove swiper css from other plugins */
		wp_dequeue_style( 'swiper' );

		wp_enqueue_style( 'swiper', $css_dir . '/swiper-bundle.min.css', array(), THEMESKY_VERSION );
		
		wp_enqueue_script( 'ts-script', $js_dir . '/themesky.js', array('jquery'), THEMESKY_VERSION, true );
		
		wp_enqueue_script( 'swiper', $js_dir . '/swiper-bundle.min.js', array(), THEMESKY_VERSION, true );
		
		wp_register_script( 'isotope', $js_dir . '/isotope.min.js', array(), THEMESKY_VERSION, true );
		
		if( defined('ICL_LANGUAGE_CODE') ){
			$ajax_uri = admin_url('admin-ajax.php?lang='.ICL_LANGUAGE_CODE, 'relative');
		}
		else{
			$ajax_uri = admin_url('admin-ajax.php', 'relative');
		}
		$data = array(
			'ajax_uri'						=> $ajax_uri
			,'blog_nonce'					=> wp_create_nonce( 'ts-blog-nonce' )
			,'product_tabs_nonce'			=> wp_create_nonce( 'ts-product-tabs-nonce' )
			,'elementor_lazy_load_nonce'	=> wp_create_nonce( 'ts-elementor-lazy-load-nonce' )
		);
		wp_localize_script('ts-script', 'themesky_params', $data);
	}
	
	function register_admin_scripts(){
		global $post_type;
		$css_dir = plugin_dir_url( __FILE__ ).'css';
		
		wp_enqueue_style( 'ts-admin-style', $css_dir . '/admin.css', array(), THEMESKY_VERSION );
		
		if( !empty($post_type) && is_string($post_type) && 'ts_size_chart' == $post_type ){
			wp_enqueue_style( 'select2', $css_dir . '/select2.css', array(), THEMESKY_VERSION );
		}
	}
}
new TS_Shortcodes();
?>