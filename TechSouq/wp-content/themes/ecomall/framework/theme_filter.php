<?php
/*** Comment ***/
function ecomall_list_comments( $comment, $args, $depth ){
	switch ( $comment->comment_type ) :
		case '' :
		case 'comment' :
	?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
		<div id="comment-<?php comment_ID(); ?>"  class="comment-wrapper">
			<div class="avatar">
				<?php echo get_avatar( $comment, 150, 'mystery' ); ?>
			</div>
			<div class="comment-detail">
			
				<?php if ( $comment->comment_approved == '0' ) : ?>
				<em class="comment-awaiting-moderation"><?php esc_html_e( 'Your comment is awaiting moderation.', 'ecomall' ); ?></em>
				<?php endif; ?>
				
				<div class="comment-text"><?php comment_text(); ?></div>
				
				<div class="comment-meta-actions">
					<div class="comment-meta">
						<span class="author">
							<span><?php esc_html_e( 'BY', 'ecomall' ); ?></span>
							<?php echo sprintf( '<a href="%1$s" rel="external nofollow" class="url">%2$s</a>', get_comment_author_url(), get_comment_author() ); ?>
						</span>
						<span class="date-time"><?php echo get_comment_date(); ?></span>
					</div>
					
					<div class="comment-actions">
						<?php if( is_user_logged_in() ): ?>
						<span class="edit"><?php edit_comment_link( esc_html__( 'Edit', 'ecomall' ), '' ); ?></span>
						<?php endif;?>
						<span class="reply"><?php comment_reply_link( array_merge( $args, array( 'add_below' => 'comment', 'depth' => $depth, 'max_depth' => $args['max_depth'], 'respond_id' => 'comment-wrapper' ) ) ); ?></span>
					</div>
				</div>
			</div>
		</div>

	<?php
			break;
		case 'pingback'  :
		case 'trackback' :
	?>
	<li class="post pingback">
		<p><?php esc_html_e( 'Pingback:', 'ecomall' ); ?> <?php comment_author_link(); ?><?php edit_comment_link( esc_html__( '(Edit)', 'ecomall' ), ' ' ); ?></p>
	<?php
			break;
	endswitch;
}

function ecomall_comment_form( $args = array(), $post_id = null ){
	global $user_identity;

	if( null === $post_id ){
		$post_id = get_the_ID();
	}
	
	$allowed_html = array(
		'div'	=> array( 'class' => array() )
		,'p'	=> array( 'class' => array() )
		,'span'	=> array( 'class' => array() )
		,'a' 	=> array( 'href' => array(), 'title' => array(), 'rel' => array() )
	);

	$commenter = wp_get_current_commenter();
	$req = get_option( 'require_name_email' );
	$aria_req = ( $req ? " aria-required='true'" : '' );
	
	$comment_author = '';
	$comment_author_email = '';
	
	extract(array_filter(array(
		'comment_author'		=>	esc_attr($commenter['comment_author'])
		,'comment_author_email'	=>	esc_attr($commenter['comment_author_email'])
	)), EXTR_OVERWRITE);
	
	$fields =  array(
		'author' 	=> '<p><label for="author">' .esc_html__('Name*', 'ecomall'). '</label><input id="author" class="input-text" name="author" type="text" value="'. $comment_author .'" size="30"' . $aria_req . ' />' . '</p>'
		,'email'	=> '<p><label for="email">' .esc_html__('Email*', 'ecomall'). '</label><input id="email" class="input-text" name="email" type="text" value="'. $comment_author_email .'" size="30"' . $aria_req . '/>' . '</p>'
		,'website'	=> '<p><label for="website">' .esc_html__('Website', 'ecomall'). '</label><input id="website" class="input-text" name="website" type="text" value="" size="30"' . $aria_req . '/>' . '</p>'
	);

	$required_text = sprintf( ' ' . wp_kses( __('Required fields are marked %s','ecomall'), $allowed_html ), '<span class="required">*</span>' );
	$defaults = array(
		'fields'               	=> apply_filters( 'comment_form_default_fields', $fields )
		,'fields_before'		=> '<div class="info-wrapper">'
		,'fields_after'		    => '</div>'
		,'comment_field'        => '<div class="message-wrapper"><p><label for="comment">' .esc_html__('Comment*', 'ecomall'). '</label><textarea id="comment" name="comment" cols="45" rows="8" aria-required="true"></textarea></p></div>'
		,'must_log_in'          => '<p class="must-log-in">' .  sprintf( __( 'You must be <a href="%s">logged in</a> to post a comment.', 'ecomall' ), wp_login_url( apply_filters( 'the_permalink', get_permalink( $post_id ) ) ) ) . '</p>'
		,'logged_in_as'         => '<p class="logged-in-as">' . sprintf( __( 'Logged in as <a href="%1$s">%2$s</a>. <a href="%3$s" title="Log out of this account">Log out?</a>', 'ecomall' ), admin_url( 'profile.php' ), $user_identity, wp_logout_url( apply_filters( 'the_permalink', get_permalink( $post_id ) ) ) ) . '</p>'
		,'comment_notes_before' => '<p>'. esc_html__( 'Your email address will not be published. Required fields are marked *', 'ecomall' ) . '</p>'
		,'comment_notes_after'  => ''
		,'id_form'              => 'commentform'
		,'id_submit'            => 'submit'
		,'title_reply'          => esc_html__( 'Leave a Comment', 'ecomall' )
		,'title_reply_to'       => esc_html__( 'Leave a Reply to %s', 'ecomall')
		,'cancel_reply_link'    => esc_html__( 'Cancel reply', 'ecomall' )
		,'label_submit'         => esc_html__( 'Post comment', 'ecomall' )
	);

	$args = wp_parse_args( $args, apply_filters( 'comment_form_defaults', $defaults ) );

	?>
		<?php if ( comments_open() ) : ?>
			<?php do_action( 'comment_form_before' ); ?>
			<section id="comment-wrapper">
				<header class="heading-wrapper">
					<h2 id="reply-title" class="heading-title"><?php comment_form_title( $args['title_reply'], $args['title_reply_to'] ); ?> <small><?php cancel_comment_reply_link( $args['cancel_reply_link'] ); ?></small></h2>
					<?php 
						if ( ! is_user_logged_in() ) {
							echo wp_kses($args['comment_notes_before'], $allowed_html); 
						}
					?>
				</header>
				
				<?php 
					if( get_option( 'comment_registration' ) && !is_user_logged_in() ):
						echo wp_kses($args['must_log_in'], $allowed_html);
						do_action( 'comment_form_must_log_in_after' );
					else: 
				?>
					<form action="<?php echo site_url( '/wp-comments-post.php' ); ?>" method="post" id="<?php echo esc_attr( $args['id_form'] ); ?>">
						<?php 
							do_action( 'comment_form_top' );
							if ( is_user_logged_in() ){
								echo wp_kses( apply_filters( 'comment_form_logged_in', $args['logged_in_as'], $commenter, $user_identity ), $allowed_html );
								do_action( 'comment_form_logged_in_after', $commenter, $user_identity );
							}
							
							echo apply_filters( 'comment_form_field_comment', $args['comment_field'] );
							
							if ( !is_user_logged_in() ) {
								echo wp_kses($args['fields_before'], $allowed_html);
								do_action( 'comment_form_before_fields' );
								foreach ( (array) $args['fields'] as $name => $field ) {
									echo apply_filters( "comment_form_field_{$name}", $field ) . "\n";
								}
								echo wp_kses($args['fields_after'], $allowed_html);								
							}
							
							echo wp_kses($args['comment_notes_after'], $allowed_html);
							if ( !is_user_logged_in() ){
								do_action( 'comment_form_after_fields' );
							}
						?>
						<p class="form-submit">
							<button class="button button-2" type="submit" id="<?php echo esc_attr( $args['id_submit'] ); ?>"><?php echo esc_html( $args['label_submit'] ); ?></button>

							<?php comment_id_fields( $post_id ); ?>
						</p>
						<?php do_action( 'comment_form', $post_id ); ?>
					</form>
				<?php endif; ?>
			</section>
			<?php do_action( 'comment_form_after' ); ?>
		<?php else : ?>
			<?php do_action( 'comment_form_comments_closed' ); ?>
		<?php endif; ?>
<?php
}

/* kses allowed html */
add_filter('wp_kses_allowed_html', 'ecomall_wp_kses_allowed_html', 10, 2);
function ecomall_wp_kses_allowed_html( $tags, $context ){
	switch( $context ){
		case 'ecomall_tgmpa':
			$tags = array(
				'a' 		=> array( 'href' => array(), 'class' => array(), 'target' => array() )
				,'p' 		=> array( 'class' => array() )
				,'span' 	=> array( 'class' => array() )
				,'strong' 	=> array()
				,'small'	=> array()
				,'br' 		=> array()
			);
		break;
		case 'ecomall_product_image':
			$tags = array(
				'img' 		=> array( 
					'width' 	=> array()
					,'height' 	=> array()
					,'src' 		=> array()
					,'class' 	=> array()
					,'id' 		=> array()
					,'alt' 		=> array()
					,'loading' 	=> array()
					,'title' 	=> array()
					,'srcset' 	=> array()
					,'sizes' 	=> array()
					,'style' 	=> array()
					,'data-*' 	=> array()
				)
			);
		break;
		case 'ecomall_product_name':
			$tags = array(
				'h3' 		=> array( 'class' => array() )
				,'h4' 		=> array( 'class' => array() )
				,'span' 	=> array( 'class' => array() )
				,'a' 		=> array( 'href' => array(), 'class' => array(), 'title' => array(), 'target' => array() )
			);
		break;
		case 'ecomall_product_price':
			$tags = array(
				'span' 		=> array( 'class' => array(), 'data-*' => array() )
				,'div' 		=> array( 'class' => array(), 'data-*' => array() )
				,'p' 		=> array( 'class' => array(), 'data-*' => array() )
				,'bdi' 		=> array()
				,'ins' 		=> array()
				,'del' 		=> array()
			);
		break;
		case 'ecomall_link':
			$tags = array(
				'a' 		=> array( 
					'href' 		=> array()
					,'target' 	=> array()
					,'class' 	=> array()
					,'style' 	=> array()
					,'title' 	=> array()
					,'rel' 		=> array()
					,'data-*' 	=> array()
				)
			);
		break;
		case 'ecomall_header_text':
			$tags = array(
				'span' 			=> array(
					'class'  	=> array()
					,'style' 	=> array()
				)
				,'i' 			=> array(
					'class' 	=> array()
				)
				,'strong' 		=> array(
					'class' 	=> array()
					,'style' 	=> array()
				)
				,'div' 			=> array(
					'class' 	=> array()
					,'style' 	=> array()
				)
				,'a' 			=> array(
					'href' 	 	=> array()
					,'class' 	=> array()
					,'title' 	=> array()
					,'style' 	=> array()
				)
				,'img' 			=> array(
					'title'  	=> array()
					,'class' 	=> array()
					,'src'   	=> array()
					,'alt'   	=> array()
					,'style' 	=> array()
				)
				,'ul' 			=> array(
					'class'		=> array()
					,'style'	=> array()
				)
				,'li'			=> array(
					'class'		=> array()
					,'style'	=> array()
				)
			);
		break;
	}
	return $tags;
}

/* Body classes filter */
add_filter('body_class', 'ecomall_body_classes_filter');
function ecomall_body_classes_filter( $classes ){
	$theme_options = ecomall_get_theme_options();
	
	if( isset($theme_options['ts_layout_fullwidth']) && $theme_options['ts_layout_fullwidth'] ){
		if( $theme_options['ts_header_layout_fullwidth'] && $theme_options['ts_main_content_layout_fullwidth'] && $theme_options['ts_footer_layout_fullwidth'] ){
			$classes[] = 'layout-fullwidth';
		}
		else{
			if( $theme_options['ts_header_layout_fullwidth'] ){
				$classes[] = 'header-fullwidth';
			}
			if( $theme_options['ts_main_content_layout_fullwidth'] ){
				$classes[] = 'main-content-fullwidth';
			}
			if( $theme_options['ts_footer_layout_fullwidth'] ){
				$classes[] = 'footer-fullwidth';
			}
		}
	}
	else if( isset($theme_options['ts_layout_style']) ){
		$classes[] = $theme_options['ts_layout_style'];
	}
	
	if( is_rtl() || ( isset($theme_options['ts_enable_rtl']) && $theme_options['ts_enable_rtl'] ) ){
		$classes[] = 'rtl';
	}
	
	if( isset($theme_options['ts_header_layout']) ){
		$classes[] = 'header-' . $theme_options['ts_header_layout'];
	}
	
	if( isset($theme_options['ts_product_label_style']) ){
		$classes[] = 'product-label-' . $theme_options['ts_product_label_style'];
	}
	
	if( isset($theme_options['ts_product_hover_style']) ){
		$classes[] = 'product-hover-style-' . $theme_options['ts_product_hover_style'];
	}
	
	if( isset($theme_options['ts_product_tooltip']) && !$theme_options['ts_product_tooltip'] ){
		$classes[] = 'product-no-tooltip';
	}

	if( $theme_options['ts_prod_cat_loading_type'] != 'default' && ( is_tax( get_object_taxonomies( 'product' ) ) || is_post_type_archive('product') ) ){
		$classes[] = $theme_options['ts_prod_cat_loading_type'];
	}
	
	if( $theme_options['ts_hide_prod_desc_device'] ){
		$classes[] = 'hide-product-description';
	}

	if( ecomall_get_page_options('ts_display_vertical_menu_by_default') ){
		$classes[] = 'display-vertical-menu';
	}

	if( !wp_is_mobile() ){
		$classes[] = 'ts_desktop';
	}
	
	global $is_safari;
	if( !empty($is_safari) ){
		$classes[] = 'is-safari';
	}
	
	return $classes;
}

?>