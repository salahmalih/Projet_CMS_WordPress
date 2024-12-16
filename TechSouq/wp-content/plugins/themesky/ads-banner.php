<?php
add_action('redux/page/ecomall_theme_options/form/before', 'ts_ads_banner_html');
add_action('redux/page/ecomall_theme_options/form/after', 'ts_ads_banner_html');
if( !function_exists('ts_ads_banner_html') ){
	function ts_ads_banner_html(){
		if( isset($_COOKIE['ts_theme_ads_banner']) ){
			return;
		}
		$theme_url = 'https://1.envato.market/GmqKPB';
		?>
		<div class="ts-theme-ads-banner">
			<div class="banner-content">
				<a href="#" class="close">x</a>
				<a href="<?php echo esc_url($theme_url); ?>" target="_blank">
					<img src="<?php echo plugin_dir_url( __FILE__ ) . 'images/ads-banner.jpg' ?>" alt="Banner" />
				</a>
			</div>
		</div>
		<?php
		if( wp_cache_get('ts_show_ads_theme_banner_script') === false ){
			wp_cache_set('ts_show_ads_theme_banner_script', 1);
			?>
			<style>
				.ts-theme-ads-banner{
					text-align: center;
					margin: 15px 0;
				}
				.ts-theme-ads-banner .banner-content{
					max-width: 800px;
					position: relative;
					display: inline-block;
					line-height: 0;
					transition: transform 0.1s linear;
				}
				.ts-theme-ads-banner .banner-content:hover{
					box-shadow: 0px 0px 8px 1px #e6e6e6;
					transform: translateY(-2px);
				}
				.ts-theme-ads-banner img{
					max-width: 100%;
				}

				.ts-theme-ads-banner .close{
					position: absolute;
					top: -5px;
					right: -5px;
					width: 15px;
					height: 15px;
					background-color: #fff;
					border-radius: 100%;
					text-align: center;
					text-decoration: none;
					line-height: 100%;
					z-index: 9;
					color: #000;
				}
				.ts-theme-ads-banner .close:hover{
					background-color: #000;
					color: #fff;
				}
				
				@media only screen and (max-width: 1400px){
					.ts-theme-ads-banner .banner-content{
						max-width: 600px;
					}
				}

				@media only screen and (max-width: 1024px){
					.ts-theme-ads-banner{
						display: none;
					}
				}
			</style>
			
			<script>
				jQuery(function($){
					$('.ts-theme-ads-banner .close').on('click', function(e){
						e.preventDefault();
						$(this).parents('.ts-theme-ads-banner').fadeOut();
						
						if( typeof $.cookie == 'function' ){
							$.cookie('ts_theme_ads_banner', 0, {expires: 30, path: '/'});
						}
					});
				});
			</script>
			<?php
		}
	}
}