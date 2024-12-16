<?php
$ecomall_theme_options = ecomall_get_theme_options();

$header_classes = array();
if( $ecomall_theme_options['ts_enable_sticky_header'] ){
	$header_classes[] = 'has-sticky';
}

if( $ecomall_theme_options['ts_tablet_show_notice'] ){
	$header_classes[] = 'device-show-notice';
}

if( $ecomall_theme_options['ts_tablet_show_hotline'] ){
	$header_classes[] = 'device-show-hotline';
}
?>

<header class="ts-header <?php echo esc_attr(implode(' ', $header_classes)); ?>">
	<div class="header-template">
		<?php if( $ecomall_theme_options['ts_header_currency'] || $ecomall_theme_options['ts_header_language'] || $ecomall_theme_options['ts_header_store_notice'] || has_nav_menu('top_header') ): ?>
		<div class="header-top hidden-phone">
			<div class="container">
				<div class="header-left">
					<?php if( $ecomall_theme_options['ts_header_language'] ): ?>
					<div class="header-language"><?php ecomall_wpml_language_selector(); ?></div>
					<?php endif; ?>
					
					<?php if( $ecomall_theme_options['ts_header_currency'] ): ?>
					<div class="header-currency"><?php ecomall_woocommerce_multilingual_currency_switcher(); ?></div>
					<?php endif; ?>
					
					<?php ecomall_store_notices(); ?>
				</div>
				
				<div class="header-right"><?php ecomall_top_header_menu(); ?></div>
			</div>
		</div>
		<?php endif; ?>
		
		<div class="header-sticky">
			<div class="header-middle">
				<div class="container">
					<div class="header-left">
						<div class="logo-wrapper"><?php ecomall_theme_logo(); ?></div>
					</div>
					
					<div class="header-center menu-wrapper hidden-phone">
						<div class="ts-menu">
						<?php 
							if ( has_nav_menu( 'primary' ) ) {
								wp_nav_menu( array( 'container' => 'nav', 'container_class' => 'main-menu pc-menu ts-mega-menu-wrapper','theme_location' => 'primary','walker' => new Ecomall_Walker_Nav_Menu() ) );
							}
							else{
								wp_nav_menu( array( 'container' => 'nav', 'container_class' => 'main-menu pc-menu ts-mega-menu-wrapper' ) );
							}
						?>
						</div>
					</div>
					
					<div class="header-right">
						<?php if( class_exists('YITH_WCWL') && $ecomall_theme_options['ts_enable_tiny_wishlist'] ): ?>
							<div class="my-wishlist-wrapper hidden-phone"><?php echo ecomall_tini_wishlist(); ?></div>
						<?php endif; ?>
						
						<?php if( $ecomall_theme_options['ts_enable_search'] ): ?>
						<div class="search-button search-icon visible-phone">
							<span class="icon"></span>
						</div>
						<?php endif; ?>
					
						<?php if( $ecomall_theme_options['ts_enable_tiny_account'] ): ?>
						<div class="my-account-wrapper">							
							<?php echo ecomall_tiny_account(); ?>
						</div>
						<?php endif; ?>
	
						<?php if( $ecomall_theme_options['ts_enable_tiny_shopping_cart'] ): ?>
						<div class="shopping-cart-wrapper">
							<?php echo ecomall_tiny_cart(); ?>
						</div>
						<?php endif; ?>
						
						<div class="ts-mobile-icon-toggle visible-phone">
							<span class="icon"></span>
						</div>
					</div>
				</div>					
			</div>
		</div>

		<div class="header-bottom hidden-phone">
			<div class="container">
				<div class="header-left">
					<?php if ( has_nav_menu( 'vertical' ) ): ?>
					<div class="vertical-menu-wrapper has-bg">			
						<div class="vertical-menu-heading"><span class="icon"></span><span><?php echo wp_get_nav_menu_name('vertical'); ?></span></div>
						<?php 
							wp_nav_menu( array( 'container' => 'nav', 'container_class' => 'vertical-menu pc-menu ts-mega-menu-wrapper','theme_location' => 'vertical','walker' => new Ecomall_Walker_Nav_Menu() ) );
						?>
					</div>
					<?php endif; ?>
				</div>
				
				<div class="header-right">
					<?php if( $ecomall_theme_options['ts_enable_search'] ): ?>
						<?php ecomall_get_search_form_by_category(); ?>
					<?php endif; ?>
				</div>
			</div>					
		</div>
	</div>	
</header>