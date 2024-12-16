<?php 
if( !class_exists('TS_Importer') ){
	class TS_Importer{
		
		function __construct(){
			add_filter( 'ocdi/plugin_page_title', array($this, 'import_notice') );
			
			add_filter( 'ocdi/plugin_page_setup', array($this, 'import_page_setup') );
			add_action( 'ocdi/before_widgets_import', array($this, 'before_widgets_import') );
			add_filter( 'ocdi/import_files', array($this, 'import_files') );
			add_filter( 'ocdi/regenerate_thumbnails_in_content_import', '__return_false' );
			add_action( 'ocdi/after_import', array($this, 'after_import_setup') );
			
			if( wp_doing_ajax() && isset($_POST['action']) && 'ocdi_import_demo_data' == $_POST['action'] ){
				add_filter('upload_mimes', array($this, 'allow_upload_font_files'));
			}
		}
		
		function import_notice( $plugin_title ){
			$allowed_html = array(
				'a' => array( 'href' => array(), 'target' => array() )
			);
			ob_start();
			?>
			<div class="ts-ocdi-notice-info">
				<p>
					<i class="fas fa-exclamation-circle"></i>
					<span><?php echo wp_kses( __('If you have any problem with importer, please read this article <a href="https://ocdi.com/import-issues/" target="_blank">https://ocdi.com/import-issues/</a> and check your hosting configuration, or contact our support team here <a href="https://skygroup.ticksy.com/" target="_blank">https://skygroup.ticksy.com/</a>.', 'themesky'), $allowed_html ); ?></span>
				</p>
			</div>
			<?php
			$plugin_title .= ob_get_clean();
			return $plugin_title;
		}
		
		function allow_upload_font_files( $existing_mimes = array() ){
			$existing_mimes['svg'] = 'font/svg';
			return $existing_mimes;
		}
		
		function import_page_setup( $default_settings ){
			$default_settings['parent_slug'] = 'themes.php';
			$default_settings['page_title']  = esc_html__( 'Ecomall - Import Demo Content' , 'themesky' );
			$default_settings['menu_title']  = esc_html__( 'Ecomall Importer' , 'themesky' );
			$default_settings['capability']  = 'import';
			$default_settings['menu_slug']   = 'ecomall-importer';
			return $default_settings;
		}
		
		function before_widgets_import(){
			global $wp_registered_sidebars;
			$file_path = dirname(__FILE__) . '/data/custom_sidebars.txt';
			if( file_exists($file_path) ){
				$file_url = plugin_dir_url(__FILE__) . 'data/custom_sidebars.txt';
				$custom_sidebars = wp_remote_get( $file_url );
				$custom_sidebars = maybe_unserialize( trim( $custom_sidebars['body'] ) );
				update_option('ts_custom_sidebars', $custom_sidebars);
				
				if( is_array($custom_sidebars) && !empty($custom_sidebars) ){
					foreach( $custom_sidebars as $name ){
						$custom_sidebar = array(
											'name' 			=> ''.$name.''
											,'id' 			=> sanitize_title($name)
											,'description' 	=> ''
											,'class'		=> 'ts-custom-sidebar'
										);
						if( !isset($wp_registered_sidebars[$custom_sidebar['id']]) ){
							$wp_registered_sidebars[$custom_sidebar['id']] = $custom_sidebar;
						}
					}
				}
			}
		}
		
		function import_files(){
			return array(
					array(
					'import_file_name'            => 'Demo Import'
					,'import_file_url'            => plugin_dir_url( __FILE__ ) . 'data/content.xml'
					,'import_widget_file_url'     => plugin_dir_url( __FILE__ ) . 'data/widget_data.wie'
					,'import_redux'               => array(
						array(
							'file_url'     => plugin_dir_url( __FILE__ ) . 'data/redux.json'
							,'option_name' => 'ecomall_theme_options'
						)
					)
				)
			);
		}
		
		function after_import_setup(){
			set_time_limit(0);
			
			$this->woocommerce_settings();
			$this->menu_locations();
			$this->set_homepage();
			$this->import_revslider();
			$this->change_url();
			$this->set_elementor_site_settings();
			$this->update_menu_homepage();
			$this->update_product_category_id_in_homepage_content();
			$this->update_mega_menu_content();
			$this->delete_transients();
			$this->update_woocommerce_lookup_table();
			$this->update_menu_term_count();
		}
		
		function get_post_by_title( $post_title, $post_type = 'page' ){
			$query = new WP_Query(
						array(
							'post_type'               => $post_type
							,'title'                  => $post_title
							,'post_status'            => 'publish'
							,'posts_per_page'         => 1
							,'no_found_rows'          => true
							,'ignore_sticky_posts'    => true
							,'update_post_term_cache' => false
							,'update_post_meta_cache' => false
							,'orderby'                => 'post_date ID'
							,'order'                  => 'ASC'
						)
					);
		 
			if( ! empty( $query->post ) ){
				return $query->post;
			}
			return null;
		}
		
		/* WooCommerce Settings */
		function woocommerce_settings(){
			$woopages = array(
				'woocommerce_shop_page_id' 			=> 'Shop'
				,'woocommerce_cart_page_id' 		=> 'Shopping Cart'
				,'woocommerce_checkout_page_id' 	=> 'Checkout'
				,'woocommerce_myaccount_page_id' 	=> 'My Account'
				,'yith_wcwl_wishlist_page_id' 		=> 'Wishlist'
			);
			foreach( $woopages as $woo_page_name => $woo_page_title ) {
				$woopage = $this->get_post_by_title( $woo_page_title );
				if( isset( $woopage->ID ) && $woopage->ID ) {
					update_option($woo_page_name, $woopage->ID);
				}
			}
			
			if( class_exists('YITH_Woocompare') ){
				update_option('yith_woocompare_compare_button_in_products_list', 'yes');
			}
			
			if( class_exists('YITH_WCWL') ){
				update_option('yith_wcwl_show_on_loop', 'yes');
			}

			if( class_exists('WC_Admin_Notices') ){
				WC_Admin_Notices::remove_notice('install');
			}
			delete_transient( '_wc_activation_redirect' );
			
			flush_rewrite_rules();
		}
		
		/* Menu Locations */
		function menu_locations(){
			$locations = get_theme_mod( 'nav_menu_locations' );
			$menus = wp_get_nav_menus();

			if( $menus ){
				foreach( $menus as $menu ){
					if( $menu->name == 'Main Menu' ){
						$locations['primary'] = $menu->term_id;
					}
					if( $menu->name == 'Top Header Menu' ){
						$locations['top_header'] = $menu->term_id;
					}
					if( $menu->name == 'Shop Categories' ){
						$locations['vertical'] = $menu->term_id;
					}
				}
			}
			set_theme_mod( 'nav_menu_locations', $locations );
		}
		
		/* Set Homepage */
		function set_homepage(){
			$homepage = $this->get_post_by_title( 'Home 1' );
			if( isset( $homepage->ID ) ){
				update_option('show_on_front', 'page');
				update_option('page_on_front', $homepage->ID);
			}
		}
		
		/* Import Revolution Slider */
		function import_revslider(){
			if ( class_exists( 'RevSliderSliderImport' ) ) {
				$rev_directory = dirname(__FILE__) . '/data/revslider/';
			
				foreach( glob( $rev_directory . '*.zip' ) as $file ){
					$import = new RevSliderSliderImport();
					$import->import_slider(true, $file);  
				}
			}
		}
		
		/* Change url */
		function change_url(){
			global $wpdb;
			$wp_prefix = $wpdb->prefix;
			$import_url = 'https://import.theme-sky.com/ecomall';
			$site_url = get_option( 'siteurl', '' );
			$wpdb->query("update `{$wp_prefix}posts` set `guid` = replace(`guid`, '{$import_url}', '{$site_url}');");
			$wpdb->query("update `{$wp_prefix}posts` set `post_content` = replace(`post_content`, '{$import_url}', '{$site_url}');");
			$wpdb->query("update `{$wp_prefix}posts` set `post_excerpt` = replace(`post_excerpt`, '{$import_url}', '{$site_url}');");
			$wpdb->query("update `{$wp_prefix}posts` set `post_title` = replace(`post_title`, '{$import_url}', '{$site_url}') where post_type='nav_menu_item';");
			$wpdb->query("update `{$wp_prefix}postmeta` set `meta_value` = replace(`meta_value`, '{$import_url}', '{$site_url}');");
			$wpdb->query("update `{$wp_prefix}postmeta` set `meta_value` = replace(`meta_value`, '" . str_replace( '/', '\\\/', $import_url ) . "', '" . str_replace( '/', '\\\/', $site_url ) . "') where `meta_key` = '_elementor_data';");
			
			$option_name = 'ecomall_theme_options';
			$option_ids = array( 
						'ts_logo'
						,'ts_logo_mobile'
						,'ts_logo_sticky'
						,'ts_logo_menu_mobile'
						,'ts_favicon'
						,'ts_custom_loading_image'
						,'ts_header_store_notice'
						,'ts_today_deal_url'
						,'ts_bg_breadcrumbs'
						,'ts_prod_placeholder_img'
						,'ts_prod_custom_tab_content'
						,'ts_prod_ads_banner_content'
						);
			$theme_options = get_option($option_name);
			if( is_array($theme_options) ){
				foreach( $option_ids as $option_id ){
					if( isset($theme_options[$option_id]) ){
						$theme_options[$option_id] = str_replace($import_url, $site_url, $theme_options[$option_id]);
					}
				}
				update_option($option_name, $theme_options);
			}
			
			/* Update Widgets */
			$widgets = array(
				'media_image' 	=> array('url', 'link_url')
				,'text' 		=> array('text')
			);
			foreach( $widgets as $base => $fields ){
				$widget_instances = get_option( 'widget_' . $base, array() );
				if( is_array($widget_instances) ){
					foreach( $widget_instances as $number => $instance ){
						if( $number == '_multiwidget' ){
							continue;
						}
						foreach( $fields as $field ){
							if( isset($widget_instances[$number][$field]) ){
								$widget_instances[$number][$field] = str_replace($import_url, $site_url, $widget_instances[$number][$field]);
							}
						}
					}
					update_option( 'widget_' . $base, $widget_instances );
				}
			}
			
			/* Update revolution slider link */
			$slides = $wpdb->get_results('select * from '.$wp_prefix.'revslider_slides');
			if( is_array($slides) ){
				foreach( $slides as $slide ){
					$layers = json_decode($slide->layers);
					
					if( is_object($layers) ){
						foreach( $layers as $key => $layer ){
							if( isset($layers->$key->actions->action) && is_array($layers->$key->actions->action) ){
								foreach( $layers->$key->actions->action as $k => $a ){
									if( isset($layers->$key->actions->action[$k]->image_link) ){
										$layers->$key->actions->action[$k]->image_link = str_replace($import_url, $site_url, $layers->$key->actions->action[$k]->image_link);
									}
								}
							}
						}
					}
					
					$layers = addslashes(json_encode($layers));
					
					$wpdb->query( "update `{$wp_prefix}revslider_slides` set `layers`='{$layers}' where `id`={$slide->id}" );
				}
			}
		}
		
		/* Set Elementor Site Settings */
		function set_elementor_site_settings(){
			$id = 0;
			
			$args = array(
				'post_type' 		=> 'elementor_library'
				,'post_status' 		=> 'public'
				,'posts_per_page'	=> 1
				,'orderby'			=> 'date'
				,'order'			=> 'ASC' /* Date is not changed when import. Use imported post */
			);
			
			$posts = new WP_Query( $args );
			if( $posts->have_posts() ){
				$id = $posts->post->ID;
				update_option('elementor_active_kit', $id);
			}
			
			if( $id ){ /* Fixed width, space, ... if query does not return the imported post */
				$page_settings = get_post_meta($id, '_elementor_page_settings', true);
			
				if( !is_array($page_settings) ){
					$page_settings = array();
				}
					
				if( !isset($page_settings['container_width']) ){
					$page_settings['container_width'] = array();
				}
				
				$page_settings['container_width']['unit'] = '%';
				$page_settings['container_width']['size'] = 100;
				$page_settings['container_width']['sizes'] = array();
				
				if( !isset($page_settings['space_between_widgets']) ){
					$page_settings['space_between_widgets'] = array();
				}
				
				$page_settings['space_between_widgets']['unit'] = 'px';
				$page_settings['space_between_widgets']['column'] = 30;
				$page_settings['space_between_widgets']['row'] = 30;
				$page_settings['space_between_widgets']['sizes'] = array();
				
				$page_settings['page_title_selector'] = 'h1.entry-title';
				
				$page_settings['stretched_section_container'] = '#main';
				
				update_post_meta($id, '_elementor_page_settings', $page_settings);
			}
			
			/* Use color, font from theme */
			update_option('elementor_disable_color_schemes', 'yes');
			update_option('elementor_disable_typography_schemes', 'yes');
			
			/* Flexbox Container */
			update_option('elementor_experiment-container', 'active'); /* check later */
		}
		
		/* Set menu for home pages */
		function update_menu_homepage(){
			$pages = array(
					'Home 3' 	=> array('ts_menu_id' => 'Main Menu Icon')
					,'Home 4' 	=> array('ts_menu_id' => 'Main Menu Stretch')
				);
			
			foreach( $pages as $page_title => $page_menus ){
				$page = $this->get_post_by_title( $page_title );
				if( is_object( $page ) ){
					foreach( $page_menus as $option_key => $menu_name ){
						$menu = get_term_by( 'name', $menu_name, 'nav_menu' );
						if( isset($menu->term_id) ){
							update_post_meta( $page->ID, $option_key, $menu->term_id );
						}
					}
				}
			}
		}
		
		/* Update Product Category Id In Homepage Content */
		function update_product_category_id_in_homepage_content(){
			global $wpdb;
			$wp_prefix = $wpdb->prefix;
			
			$pages = array(
					'Home 1'	=> array(
							array(
								'890,897,905,906,902,893'
								,array( 'Laptops', 'Games', 'Television', 'Speakers', 'Headphones', 'Smartphone' )
								,'ids'
							)
							,array(
								'893,890,911,892'
								,array( 'Smartphone', 'Laptops', 'Iwatch', 'Accessories' )
								,'product_cats'
							)
					)
					,'Home 2'	=> array(
							array(
								'905,906,896,902,911,893,897,890'
								,array( 'Television', 'Speakers', 'Tablets', 'Headphones', 'Iwatch', 'Smartphone', 'Games', 'Laptops' )
								,'ids'
							)
							,array(
								'893,896'
								,array( 'Smartphone', 'Tablets' )
								,'product_cats'
							)
							,array(
								'889'
								,array( 'Laptops & Desktops' )
								,'product_cats'
							)
							,array(
								'906'
								,array( 'Speakers' )
								,'product_cats'
							)
					)
					,'Home 3'	=> array(
							array(
								'893,890,902,892'
								,array( 'Smartphone', 'Laptops', 'Headphones', 'Accessories' )
								,'product_cats'
							)
							,array(
								'905,896,911,893,906,902,890,897,907,912,188,892'
								,array( 'Television', 'Tablets', 'Iwatch', 'Smartphone', 'Speakers', 'Headphones', 'Laptops', 'Games', 'Camera', 'Kid Electronic', 'Smart Home', 'Accessories' )
								,'ids'
							)
					)
					,'Home 4'	=> array(
							array(
								'893,902,890,892'
								,array( 'Smartphone', 'Headphones', 'Laptops', 'Accessories' )
								,'product_cats'
							)
							,array(
								'905'
								,array( 'Television' )
								,'parent'
							)
							,array(
								'902'
								,array( 'Headphones' )
								,'parent'
							)
							,array(
								'907'
								,array( 'Camera' )
								,'parent'
							)
							,array(
								'893'
								,array( 'Smartphone' )
								,'parent'
							)
							,array(
								'897'
								,array( 'Games' )
								,'parent'
							)
							,array(
								'906'
								,array( 'Speakers' )
								,'parent'
							)
					)
					,'Home 5'	=> array(
							array(
								'890'
								,array( 'Laptops' )
								,'product_cats'
							)
							,array(
								'926,896,925,894'
								,array( 'iPhone', 'Tablets', 'Samsung Galaxy', 'Unlocked Phones' )
								,'product_cats'
							)
							,array(
								'915,913,914,916'
								,array( 'Xbox Series', 'Playstation 4', 'Playstation 5', 'Accessories' )
								,'product_cats'
							)
							,array(
								'909,907,924,908'
								,array( 'Camera & Lenses', 'Camera', 'Security Cameras', 'Accessories' )
								,'product_cats'
							)
							,array(
								'905,906,896,902,911,893'
								,array( 'Television', 'Speakers', 'Tablets', 'Headphones', 'Iwatch', 'Smartphone' )
								,'ids'
							)
					)
					,'Home 6'	=> array(
							array(
								'890,893,897'
								,array( 'Laptops', 'Smartphone', 'Games' )
								,'product_cats'
							)
							,array(
								'906,896,905'
								,array( 'Speakers', 'Tablets', 'Television' )
								,'product_cats'
							)
					)
				);
			
			$loaded_categories = array();
			
			foreach( $pages as $page_title => $cat_ids_names ){
				$page = $this->get_post_by_title( $page_title );
				if( is_object( $page ) ){
					foreach( $cat_ids_names as $cat_id_name ){
						$key = isset($cat_id_name[2]) ? $cat_id_name[2] : 'ids';
						$taxonomy = isset($cat_id_name[3]) ? $cat_id_name[3] : 'product_cat';
						
						$old_ids = explode(',', $cat_id_name[0]);
						
						$new_ids = array();
						foreach( $cat_id_name[1] as $cat_name ){
							$loaded_id = array_search($cat_name, $loaded_categories);
							if( $loaded_id ){
								$new_ids[] = $loaded_id;
							}
							else{
								$cat = get_term_by('name', $cat_name, $taxonomy);
								if( isset($cat->term_id) ){
									$new_ids[] = $cat->term_id;
									$loaded_categories[$cat->term_id] = $cat_name;
								}
							}
						}
						
						if( $key == 'parent' || $key == 'parent_cat' ){ /* not multi */
							$old_string = '"' . $key . '":"' . implode('', $old_ids) . '"';
							$new_string = '"' . $key . '":"' . implode('', $new_ids) . '"';
						}
						else{
							$old_string = '"' . $key . '":["' . implode('","', $old_ids) . '"]';
							$new_string = '"' . $key . '":["' . implode('","', $new_ids) . '"]';
						}
						
						$wpdb->query("update `{$wp_prefix}postmeta` set `meta_value` = replace(`meta_value`, '" . $old_string . "', '" . $new_string . "') where `meta_key` = '_elementor_data' and post_id=" . $page->ID . ";");
					}
				}
			}
		}
		
		/* Update Mega Menu Content */
		function update_mega_menu_content(){
			global $wpdb;
			$wp_prefix = $wpdb->prefix;
			
			/* Product Categories */
			$mega_menus = array(
						'Mega menu 3'	=> array(
								array(
									'889,896,891,890,892,1076'
									, array( 'Laptops & Desktops', 'Tablets', 'Monitors', 'Laptops', 'Accessories', 'Macbook' )
									, 'ids'
								)
								,array(
									'893'
									, array( 'Smartphone' )
									, 'parent'
								)
								,array(
									'927,929,930,928,902,906'
									, array( 'Bluetooth Speakers', 'Portable Speakers', 'Professional Speakers', 'Waterproof Speakers', 'Headphones', 'Speakers' )
									, 'ids'
								)
								,array(
									'915,913,914,904,916,1068'
									, array( 'Xbox Series', 'Playstation 4', 'Playstation 5', 'Gaming Headsets', 'Accessories', 'PC Gaming' )
									, 'ids'
								)
								,array(
									'889'
									, array( 'Laptops & Desktops' )
									, 'ids'
								)
								,array(
									'893'
									, array( 'Smartphone' )
									, 'ids'
								)
								,array(
									'902'
									, array( 'Headphones' )
									, 'ids'
								)
								,array(
									'897'
									, array( 'Games' )
									, 'ids'
								)
						)
						,'Vertical 2'	=> array(
								array(
									'889'
									, array( 'Laptops & Desktops' )
									, 'parent'
								)
						)
						,'Vertical 1'	=> array(
								array(
									'889,896,891,890,892,910'
									, array( 'Laptops & Desktops', 'Tablets', 'Monitors', 'Laptops', 'Accessories', 'Drones' )
									, 'ids'
								)
								,array(
									'907'
									, array( 'Camera' )
									, 'parent'
								)
								,array(
									'893'
									, array( 'Smartphone' )
									, 'parent'
								)
								,array(
									'905'
									, array( 'Television' )
									, 'parent'
								)
						)
						,'Mega menu 2'	=> array(
								array(
									'889,896,891,890,892,1067,1068'
									, array( 'Laptops & Desktops', 'Tablets', 'Monitors', 'Laptops', 'Accessories', 'Computer Component', 'PC Gaming' )
									, 'ids'
								)
								,array(
									'893'
									, array( 'Smartphone' )
									, 'parent'
								)
						)
						,'Mega menu 1'	=> array(
								array(
									'889,896,891,890,892'
									, array( 'Laptops & Desktops', 'Tablets', 'Monitors', 'Laptops', 'Accessories' )
									, 'ids'
								)
								,array(
									'893'
									, array( 'Smartphone' )
									, 'parent'
								)
								,array(
									'927,929,930,928,902,906'
									, array( 'Bluetooth Speakers', 'Portable Speakers', 'Professional Speakers', 'Waterproof Speakers', 'Headphones', 'Speakers' )
									, 'ids'
								)
								,array(
									'915,913,914,904,916'
									, array( 'Xbox Series', 'Playstation 4', 'Playstation 5', 'Gaming Headsets', 'Accessories' )
									, 'ids'
								)
								,array(
									'907'
									, array( 'Camera' )
									, 'parent'
								)
								,array(
									'905'
									, array( 'Television' )
									, 'parent'
								)
								,array(
									'188,924,905'
									, array( 'Smart Home', 'Security Cameras', 'Television' )
									, 'ids'
								)
								,array(
									'902'
									, array( 'Headphones' )
									, 'parent'
								)
						)
				);
			
			$loaded_categories = array();
			
			foreach( $mega_menus as $title => $cat_ids_names ){
				$mega_menu_post = $this->get_post_by_title( $title, 'ts_mega_menu' );
				if( is_object( $mega_menu_post ) ){
					foreach( $cat_ids_names as $cat_id_name ){
						$old_ids = explode(',', $cat_id_name[0]);
						$key = $cat_id_name[2];
						
						$new_ids = array();
						foreach( $cat_id_name[1] as $cat_name ){
							$loaded_id = array_search($cat_name, $loaded_categories);
							if( $loaded_id ){
								$new_ids[] = $loaded_id;
							}
							else{
								$cat = get_term_by('name', $cat_name, 'product_cat');
								if( isset($cat->term_id) ){
									$new_ids[] = $cat->term_id;
									$loaded_categories[$cat->term_id] = $cat_name;
								}
							}
						}
						
						if( $key == 'parent' || $key == 'child_of' ){
							$old_string = '"' . $key . '":"' . implode('', $old_ids) . '"';
							$new_string = '"' . $key . '":"' . implode('', $new_ids) . '"';
						}
						else{
							$old_string = '"' . $key . '":["' . implode('","', $old_ids) . '"]';
							$new_string = '"' . $key . '":["' . implode('","', $new_ids) . '"]';
						}
						
						$wpdb->query("update `{$wp_prefix}postmeta` set `meta_value` = replace(`meta_value`, '" . $old_string . "', '" . $new_string . "') where `meta_key` = '_elementor_data' and post_id=" . $mega_menu_post->ID . ";");
					}
				}
			}
		}
		
		/* Delete transient */
		function delete_transients(){
			delete_transient('ts_mega_menu_custom_css');
			delete_transient('ts_product_deals_ids');
			delete_transient('wc_products_onsale');
		}
		
		/* Update WooCommerce Loolup Table */
		function update_woocommerce_lookup_table(){
			if( function_exists('wc_update_product_lookup_tables_is_running') && function_exists('wc_update_product_lookup_tables') ){
				if( !wc_update_product_lookup_tables_is_running() ){
					if( !defined('WP_CLI') ){
						define('WP_CLI', true);
					}
					wc_update_product_lookup_tables();
				}
			}
		}
		
		/* Update Menu Term Count - Keep this function until One Click Demo Import fixed */
		function update_menu_term_count(){
			$args = array(
						'taxonomy'		=> 'nav_menu'
						,'hide_empty'	=> 0
						,'fields'		=> 'ids'
					);
			$menus = get_terms( $args );
			if( is_array($menus) ){
				wp_update_term_count_now( $menus, 'nav_menu' );
			}
		}
	}
	new TS_Importer();
}
?>