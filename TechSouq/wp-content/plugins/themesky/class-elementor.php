<?php  
if( !defined('ABSPATH') ){
    exit; // Exit if accessed directly.
}

class TS_Elementor_Addons{
	
	function __construct(){
		require_once 'elementor/ajax-functions.php';
		
		require_once 'elementor/autocomplete-control.php';
		
		require_once 'elementor/custom-icons.php';
		
		$this->add_ajax_actions();
		
		add_action( 'elementor/elements/categories_registered', array( $this, 'add_category' ) );
		
		add_action( 'elementor/widgets/register', array( $this, 'include_widgets' ) );
		
		add_action( 'elementor/editor/before_enqueue_styles', array( $this, 'editor_before_enqueue_styles' ) );
		
		add_action( 'elementor/editor/after_register_scripts', array( $this, 'editor_after_register_scripts' ) );
		
		add_action( 'elementor/controls/controls_registered', array( $this, 'register_controls' ) );
	}
	
	function editor_before_enqueue_styles(){
		$css_dir = plugin_dir_url( __FILE__ ) . 'css';
		
		wp_enqueue_style( 'ts-admin-style', $css_dir . '/admin.css', array(), THEMESKY_VERSION );
	}
	
	function editor_after_register_scripts(){
		$js_dir = plugin_dir_url( __FILE__ ) . 'js';

		wp_register_script( 'isotope', $js_dir . '/isotope.min.js', array(), THEMESKY_VERSION, true );
	}
	
	function add_ajax_actions(){
		add_action('wp_ajax_ts_blogs_load_items', 'ts_get_blog_items_content');
		add_action('wp_ajax_nopriv_ts_blogs_load_items', 'ts_get_blog_items_content');
		
		add_action('wp_ajax_ts_get_product_content_in_category_tab', 'ts_get_product_content_in_category_tab');
		add_action('wp_ajax_nopriv_ts_get_product_content_in_category_tab', 'ts_get_product_content_in_category_tab');
		
		add_action('wp_ajax_ts_elementor_autocomplete_load_options', array($this, 'autocomplete_load_options'));
		add_action('wp_ajax_nopriv_ts_elementor_autocomplete_load_options', array($this, 'autocomplete_load_options'));
		
		add_action('wp_ajax_ts_elementor_autocomplete_query', array($this, 'autocomplete_query'));
		add_action('wp_ajax_nopriv_ts_elementor_autocomplete_query', array($this, 'autocomplete_query'));
		
		add_action('wp_ajax_ts_elementor_lazy_load', array($this, 'elementor_lazy_load'));
		add_action('wp_ajax_nopriv_ts_elementor_lazy_load', array($this, 'elementor_lazy_load'));
	}
	
	function register_controls(){
		$controls_manager = \Elementor\Plugin::$instance->controls_manager;
		$controls_manager->register( new TS_Elementor_AutoComplete_Control() );
	}
	
	function add_category(){
		Elementor\Plugin::instance()->elements_manager->add_category(
            'ts-elements'
            ,array(
                'title' 	=> esc_html__( 'TS Elements', 'themesky' )
                ,'icon'  	=> 'fa fa-plug'
            )
		);
	}
	
	function include_widgets( $widgets_manager ){
		require_once 'elementor/base.php';
		
		$general_elements = array(
			'team_members'
			,'testimonial'
			,'logos'
			,'blogs'
			,'banner'
			,'countdown'
			,'coupon'
		);
		
		if( class_exists('MC4WP_MailChimp') ){
            array_push($general_elements, 'mailchimp');
        }
		
		$general_elements = apply_filters( 'ts_general_elements_array', $general_elements );
		foreach( $general_elements as $element ){
			$path = plugin_dir_path( __FILE__ ) . '/elementor/general/' . $element . '.php';
			if( file_exists( $path ) ){
				require_once $path;
			}
		}
		
		$woocommerce_elements = array(
			'products'
			,'product_deals'
			,'product_categories'
			,'product_brands'
			,'products_in_category_tabs'
			,'products_in_product_type_tabs'
			,'list_of_product_categories'
		);
		
		$woocommerce_elements = apply_filters( 'ts_woocommerce_elements_array', $woocommerce_elements );
		foreach( $woocommerce_elements as $element ){
			$path = plugin_dir_path( __FILE__ ) . '/elementor/woocommerce/' . $element . '.php';
			if( file_exists( $path ) ){
				require_once $path;
			}
		}
	}
	
	function autocomplete_load_options(){
		check_ajax_referer( 'ts-elementor-autocomplete-nonce', 'security' );
		
		if( isset( $_POST['selected_values'] ) ){
			$results = array();
			
			$query_type = $_POST['query_type'];
			$query_name = $_POST['query_name'];
			$include = explode( ',', $_POST['selected_values'] );
			
			if( $query_type == 'post' ){
				$args = array(
					'post_type'			=> $query_name
					,'post_status'		=> 'publish'
					,'posts_per_page'	=> -1
					,'post__in'			=> $include
					,'orderby'			=> 'post__in'
				);
				
				$posts = new WP_Query( $args );
				if( $posts->have_posts() ){
					$results['ids'] = array();
					$results['names'] = array();
					foreach( $posts->posts as $p ){
						$results['ids'][] 	= $p->ID;
						$results['names'][] = $this->autocomplete_format_name( $p->post_title, $p->ID );
					}
					die( json_encode($results) );
				}
			}
			else{ /* taxonomy */
				$args = array(
					'taxonomy'		=> $query_name
					,'hide_empty'	=> false
					,'fields'		=> 'id=>name'
				);
				
				$args['include'] = $include;
				$args['orderby'] = 'include';
				
				$terms = get_terms( $args );
				
				if( is_array($terms) && !empty($terms) ){
					$results['ids'] = array_keys( $terms );
					$results['names'] = array_values( $terms );
					
					foreach( $results['names'] as $k => $name ){
						$results['names'][$k] = $this->autocomplete_format_name( $name, $results['ids'][$k] );
					}
					
					die( json_encode($results) );
				}
			}
		}
		
		die(0);
	}
	
	function autocomplete_query(){
		check_ajax_referer( 'ts-elementor-autocomplete-nonce', 'security' );
		
		$results = array();
		
		if( isset( $_GET['search_term'] ) ){
			$search_term = esc_sql( $_GET['search_term'] );
			$query_type = $_GET['query_type'];
			$query_name = $_GET['query_name'];

			if( $query_type == 'post' ){
				$args = array(
					'post_type'			=> $query_name
					,'post_status'		=> 'publish'
					,'posts_per_page'	=> -1
					,'s'				=> $search_term
				);
				
				$posts = new WP_Query( $args );
				if( $posts->have_posts() ){
					foreach( $posts->posts as $p ){
						$results[] 	= array( 'id' => $p->ID, 'text' => $this->autocomplete_format_name( $p->post_title, $p->ID ) );
					}
				}
			}
			else{ /* taxonomy */
				$args = array(
					'taxonomy'		=> $query_name
					,'hide_empty'	=> false
					,'fields'		=> 'id=>name'
					,'name__like'	=> $search_term
				);
				
				$terms = get_terms( $args );
				if( is_array($terms) ){
					foreach( $terms as $id => $name ){
						$results[] = array( 'id' => $id, 'text' => $this->autocomplete_format_name( $name, $id ) );
					}
				}
			}
		}
		
		die( json_encode($results) );
	}
	
	function autocomplete_format_name( $name, $id ){
		return $name . ' [' . esc_html__('ID', 'themesky') . ': ' . $id . ']';
	}
	
	function elementor_lazy_load(){
		check_ajax_referer( 'ts-elementor-lazy-load-nonce', 'security' );
		
		if( !( isset($_POST['widget_id']) && $widget_id = sanitize_text_field( $_POST['widget_id'] ) ) ){
			wp_send_json_error( esc_html__('Widget id is invalid', 'themesky') );
		}
		
		if( !( isset($_POST['post_id']) && $post_id = absint( $_POST['post_id'] ) ) ){
			wp_send_json_error( esc_html__('Post id is invalid', 'themesky') );
		}
		
		$document_data = '';

		$document = \Elementor\Plugin::instance()->documents->get( $post_id );
		
		if( $document ){
			$document_data = $document->get_elements_data();
		}
		
		if( empty( $document_data ) ){
			wp_send_json_error( esc_html__('Can not get document data', 'themesky') );
		}
		
		$findings = array();

		\Elementor\Plugin::instance()->db->iterate_data( $document_data, function( $element ) use ( $widget_id, &$findings ){
			if( $widget_id === $element['id'] ){
				$findings[] = $element;
			}
		});
		
		if( !( isset($findings[0]) && $widget_data_instance = $findings[0] ) ){
			wp_send_json_error( esc_html__('No element found in document', 'themesky') );
		}
		
		$widget_data_instance['settings']['lazy_load'] = '';
		
		$new_widget = \Elementor\Plugin::instance()->elements_manager->create_element_instance( $widget_data_instance );
		
		do_action('ts_elementor_lazy_load_get_content_before');
		
		ob_start();
		$new_widget->print_element();
		$widget_data = ob_get_clean();
		
		if( empty($widget_data) ){
			wp_send_json_error( esc_html__('Empty element data!', 'themesky') );
		}

		wp_send_json_success( $widget_data );
	}
}
new TS_Elementor_Addons();