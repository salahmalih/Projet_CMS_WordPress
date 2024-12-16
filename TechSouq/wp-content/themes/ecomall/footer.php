<?php 
	$ecomall_theme_options = ecomall_get_theme_options();
?>
</div><!-- #main .wrapper -->
	<?php if( !is_page_template('page-templates/blank-page-template.php') && $ecomall_theme_options['ts_footer_block'] ): ?>
	<footer id="colophon" class="footer-container footer-area loading">
		<?php ecomall_get_footer_content( $ecomall_theme_options['ts_footer_block'] ); ?>
	</footer>
	<?php endif; ?>
</div><!-- #page -->

<?php if( !is_page_template('page-templates/blank-page-template.php') ): ?>
		
	<!-- Group Header Button -->
	<div id="group-icon-header" class="ts-floating-sidebar">
		<div class="overlay"></div>
		<div class="ts-sidebar-content <?php echo esc_attr( ( has_nav_menu( 'vertical' ) ) ? '' : 'no-tab' ); ?>">
		
			<div class="sidebar-content">
				<ul class="tab-mobile-menu">
					<li id="main-menu" class="active"><span><?php esc_html_e('Menu', 'ecomall'); ?></span></li>
					<?php if( has_nav_menu( 'vertical' ) ): ?>
						<li id="vertical-menu"><span><?php echo wp_get_nav_menu_name('vertical'); ?></span></li>
					<?php endif; ?>
					<li class="close"></li>
				</ul>
				
				<h6 class="menu-title"><span><?php esc_html_e('Menu', 'ecomall'); ?></span></h6>
				
				<div class="mobile-menu-wrapper ts-menu tab-menu-mobile">
					<div class="menu-main-mobile">
						<?php 
						if( has_nav_menu( 'mobile' ) ){
							wp_nav_menu( array( 'container' => 'nav', 'container_class' => 'mobile-menu', 'theme_location' => 'mobile', 'walker' => new Ecomall_Walker_Nav_Menu() ) );
						}else{
							if( has_nav_menu( 'primary' ) ){
								wp_nav_menu( array( 'container' => 'nav', 'container_class' => 'mobile-menu', 'theme_location' => 'primary', 'walker' => new Ecomall_Walker_Nav_Menu() ) );
							}else{
								wp_nav_menu( array( 'container' => 'nav', 'container_class' => 'mobile-menu' ) );
							}
						}
						?>
					</div>
				</div>
				
				<?php if( has_nav_menu( 'vertical' ) ): ?>
					<div class="mobile-menu-wrapper ts-menu tab-vertical-menu">
						<div class="vertical-menu-wrapper">			
							<?php
								wp_nav_menu( array( 'container' => 'nav', 'container_class' => 'vertical-menu', 'theme_location' => 'vertical', 'walker' => new Ecomall_Walker_Nav_Menu() ) );							?>
						</div>
					</div>
				<?php endif; ?>
				
				<div class="group-button-header">
					<?php if( ( class_exists('YITH_WCWL') && $ecomall_theme_options['ts_enable_tiny_wishlist'] ) || ( $ecomall_theme_options['ts_enable_today_deal'] && $ecomall_theme_options['ts_today_deal_text'] ) ): ?>
					<div class="meta-bottom">
						<?php if( class_exists('YITH_WCWL') && $ecomall_theme_options['ts_enable_tiny_wishlist'] ): ?>
							<div class="my-wishlist-wrapper visible-phone"><?php echo ecomall_tini_wishlist(); ?></div>
						<?php endif; ?>
						<?php ecomall_today_deal(); ?>
					</div>
					<?php endif; ?>
					
					<?php if( $ecomall_theme_options['ts_header_language'] || $ecomall_theme_options['ts_header_currency'] || ( $ecomall_theme_options['ts_enable_hotline'] && $ecomall_theme_options['ts_hotline_number'] ) ): ?>
					<div class="meta-bottom">
						<?php if( $ecomall_theme_options['ts_header_language'] ): ?>
						<div class="header-language"><?php ecomall_wpml_language_selector(); ?></div>
						<?php endif; ?>
						
						<?php if( $ecomall_theme_options['ts_header_currency'] ): ?>
						<div class="header-currency"><?php ecomall_woocommerce_multilingual_currency_switcher(); ?></div>
						<?php endif; ?>
						
						<?php ecomall_hotline(); ?>
					</div>
					<?php endif; ?>
				</div>
			</div>	
		</div>
	</div>
		
<?php endif; ?>

<!-- Search Sidebar -->
<?php if( $ecomall_theme_options['ts_enable_search'] ): ?>
	
	<div id="ts-search-sidebar" class="ts-floating-sidebar">
		<div class="overlay"></div>
		<div class="ts-sidebar-content">
			<span class="close"></span>
			<?php if( $ecomall_theme_options['ts_enable_search'] ): ?>
				<?php ecomall_get_search_form_by_category(); ?>
			<?php endif; ?>
			
			<div class="ts-search-result-container"></div>
		</div>
	</div>

<?php endif; ?>

<!-- Shopping Cart Floating Sidebar -->
<?php if( class_exists('WooCommerce') && $ecomall_theme_options['ts_enable_tiny_shopping_cart'] && $ecomall_theme_options['ts_shopping_cart_sidebar'] && !is_cart() && !is_checkout() ): ?>
<div id="ts-shopping-cart-sidebar" class="ts-floating-sidebar">
	<div class="overlay"></div>
	<div class="ts-sidebar-content">
		<span class="close"></span>
		<div class="ts-tiny-cart-wrapper"></div>
	</div>
</div>
<?php endif; ?>

<?php 
if( ( !wp_is_mobile() && $ecomall_theme_options['ts_back_to_top_button'] ) || ( wp_is_mobile() && $ecomall_theme_options['ts_back_to_top_button_on_mobile'] ) ): 
?>
<div id="to-top" class="scroll-button">
	<a class="scroll-button" href="javascript:void(0)" title="<?php esc_attr_e('Back to Top', 'ecomall'); ?>"><?php esc_html_e('Back to Top', 'ecomall'); ?></a>
</div>
<?php endif; ?>

<?php 
wp_footer(); ?>
</body>
</html>