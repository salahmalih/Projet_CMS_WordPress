jQuery(function($){
	"use strict";

	ts_register_carousel(null, $);

	/* Elementor Lazy Load */
	if( $('.ts-elementor-lazy-load').length ){
		var ts_elementor_lazy_load_loaded_widget = []; /* prevent double load same widget */
		$(window).on('scroll ts_elementor_lazy_load', function(){
			var scroll_top = $(this).scrollTop();
			var window_height = $(this).height();
			var number_request = 0;
			$('.ts-elementor-lazy-load:not(.loaded)').each(function(i, e){
				if( $(e).offset().top > scroll_top + window_height + 600 ){
					return false;
				}
				var timeout = number_request * 200 + 10; /* dont show many requests same time */
				number_request++;
				var el = $(e);
				var widget_id = el.closest('.elementor-element[data-id]').attr('data-id');
				var widget = $('.elementor-element[data-id="' + widget_id + '"]'); /* may added many */
				widget.find('.ts-elementor-lazy-load').addClass('loaded');
				var post_id = el.parents('[data-elementor-id]').attr('data-elementor-id');
				
				setTimeout(function(){
					if( ts_elementor_lazy_load_loaded_widget.includes( widget_id ) ){
						return;
					}
					ts_elementor_lazy_load_loaded_widget.push( widget_id );
					
					$.ajax({
						type : "POST",
						timeout : 30000,
						url : themesky_params.ajax_uri,
						data : {action: 'ts_elementor_lazy_load', widget_id: widget_id, post_id: post_id, security: themesky_params.elementor_lazy_load_nonce},
						error: function(xhr,err){
							
						},
						success: function( response ){
							if( response ){
								widget.replaceWith( response.data );
								
								/* Generate slider */
								ts_register_carousel( null, $ );
								
								widget = $('.elementor-element[data-id="' + widget_id + '"]'); /* new widget */
								/* Countdown */
								if( widget.find('.counter-wrapper').length ){
									ts_counter( widget.find('.counter-wrapper') );
								}
								
								/* Blog Masonry */
								if( widget.find('.ts-blogs-wrapper.ts-masonry').length ){
									ts_register_masonry( null, $ );
								}
								
								/* Tabs shop more button */
								var el_tabs = widget.find('.ts-product-in-category-tab-wrapper');
								if( el_tabs.length ){
									ts_product_in_category_tab_shop_more_handle( el_tabs, el_tabs.data('atts') );
								}
								
								$(window).trigger('ts_elementor_lazy_load_loaded', [widget_id]);
							}
						}
					});
				}, timeout);
			});
		});
		
		if( $('#main .ts-elementor-lazy-load').length && $('#main .ts-elementor-lazy-load:first').offset().top < $(window).scrollTop() + $(window).height() ){
			$(window).trigger('ts_elementor_lazy_load');
		}
		
		if( $('.menu-item.ts-megamenu .ts-elementor-lazy-load').length ){
			$('.menu-item.ts-megamenu').on('mouseenter', function(){
				$(window).trigger('ts_elementor_lazy_load');
			});
		}
	}

	/*** Load Products In Category Tab ***/
	var ts_product_in_category_tab_data = [];

	/* Change tab */
	$(document).on('click', '.ts-product-in-category-tab-wrapper .column-tabs .tab-item, .ts-product-in-product-type-tab-wrapper .column-tabs .tab-item', function(){
		var element = $(this).parents('.ts-product-in-category-tab-wrapper');
		var is_product_type_tab = false;
		if( element.length == 0 ){
			element = $(this).parents('.ts-product-in-product-type-tab-wrapper');
			is_product_type_tab = true;
		}

		var element_top = element.offset().top;
		if( element_top > $(window).scrollTop() ){
			var admin_bar_height = $('#wpadminbar').length > 0 ? $('#wpadminbar').outerHeight() : 0;
			var sticky_height = $('.is-sticky .header-sticky').length > 0 ? $('.is-sticky .header-sticky').outerHeight() : 0;
			$('body, html').animate({
				scrollTop: element_top - sticky_height - admin_bar_height - 20
			}, 500);
		}

		if( $(this).hasClass('current') || element.find('.column-products').hasClass('loading') ){
			return;
		}

		element.removeClass('generated-slider');
		
		var tab_index = $(this).index();

		var element_id = element.attr('id');
		var atts = element.data('atts');
		if( !is_product_type_tab ){
			var product_cat = $(this).data('product_cat');
			var shop_more_link = $(this).data('link');
			var is_general_tab = $(this).hasClass('general-tab') ? 1 : 0;
			
			var banners = atts.tab_banners;
			if( banners ){
				banners = banners.split(',');
				if( typeof banners[tab_index] != 'undefined' ){
					var banner = banners[tab_index].split('|');
					if( typeof banner[0] != 'undefined' && banner[0] ){
						element.addClass('has-banner');
					}
					else{
						element.removeClass('has-banner');
					}
				}
			}
		}
		else{
			var product_cat = atts.product_cats;
			var is_general_tab = 0;
			atts.product_type = $(this).data('product_type');
			element.find('.column-products').removeClass('recent sale featured best_selling top_rated mixed_order').addClass(atts.product_type);
			element.find('.shop-more').attr('style', 'display: none');
			$('.shop-more.' + $(this).data('id')).attr('style', '');
		}

		if( !is_product_type_tab && element.find('a.shop-more-button').length > 0 ){
			element.find('a.shop-more-button').attr('href', shop_more_link);
		}

		element.find('.column-tabs .tab-item').removeClass('current');
		$(this).addClass('current');

		/* Check cache */
		var tab_data_index = element_id + '-' + product_cat.toString().split(',').join('-');
		if( is_product_type_tab ){
			tab_data_index += '-' + atts.product_type;
		}
		if( ts_product_in_category_tab_data[tab_data_index] != undefined ){
			element.find('.column-products .products').remove();
			element.find('.column-products .tab-banner').remove();
			element.find('.column-products').append(ts_product_in_category_tab_data[tab_data_index]).hide().fadeIn(600);

			/* Shop more button handle */
			if( !is_product_type_tab ){
				ts_product_in_category_tab_shop_more_handle(element, atts);
			}

			/* Generate slider */
			ts_register_carousel(element.parent(), $);

			return;
		}

		element.find('.column-products').addClass('loading');

		$.ajax({
			type: "POST",
			timeout: 30000,
			url: themesky_params.ajax_uri,
			data: { action: 'ts_get_product_content_in_category_tab', atts: atts, product_cat: product_cat, is_general_tab: is_general_tab, tab_index: tab_index, security: themesky_params.product_tabs_nonce },
			error: function(xhr, err){

			},
			success: function(response){
				if( response ){
					element.find('.column-products .products').remove();
					element.find('.column-products .tab-banner').remove();
					element.find('.column-products').append(response).hide().fadeIn(600);
					/* save cache */
					if( element.find('.counter-wrapper').length == 0 ){
						ts_product_in_category_tab_data[tab_data_index] = response;
					}
					else{
						ts_counter(element.find('.counter-wrapper'));
					}
					/* Shop more button handle */
					if( !is_product_type_tab ){
						ts_product_in_category_tab_shop_more_handle(element, atts);
					}
					/* Generate slider */
					ts_register_carousel(element.parent(), $);
				}

				element.find('.column-products').removeClass('loading');

			}
		});
	});

	function ts_product_in_category_tab_shop_more_handle(element, atts){
		var hide_shop_more = element.find('.hide-shop-more').length;
		element.find('.hide-shop-more').remove();

		if( element.find('.tab-item.current').hasClass('general-tab') && atts.show_shop_more_general_tab == 0 ){
			hide_shop_more = true;
		}

		if( element.find('.products .product').length == 0 ){
			hide_shop_more = true;
		}

		if( atts.show_shop_more_button == 1 ){
			if( hide_shop_more ){
				element.find('.shop-more').addClass('hidden');
				element.removeClass('has-shop-more-button');
			}
			else{
				element.find('.shop-more').removeClass('hidden');
				element.addClass('has-shop-more-button');
			}
		}
	}

	$('.ts-product-in-category-tab-wrapper').each(function(){
		var element = $(this);
		var atts = element.data('atts');
		ts_product_in_category_tab_shop_more_handle(element, atts);
	});

	/*** Blog ***/
	$(document).on('click', '.ts-blogs-wrapper .load-more', function(){
		var element = $(this).parents('.ts-blogs-wrapper');
		var atts = element.data('atts');

		var is_masonry = typeof $.fn.isotope == 'function' && element.hasClass('ts-masonry') ? true : false;
	
		var button = $(this);
		if( button.hasClass('loading') ){
			return false;
		}

		button.addClass('loading');
		var paged = button.attr('data-paged');
		var total_pages = button.attr('data-total_pages');
		var posts_per_page = button.attr('data-posts_per_page');

		$.ajax({
			type: "POST",
			timeout: 30000,
			url: themesky_params.ajax_uri,
			data: { action: 'ts_blogs_load_items', paged: paged, atts: atts, security: themesky_params.blog_nonce },
			error: function(xhr, err){

			},
			success: function( response ){
				if( paged == total_pages ){
					button.parent().remove();
				}
				else{
					button.removeClass('loading');
					button.prev().find('span').html(paged * posts_per_page);
					button.attr('data-paged', ++paged);
				}
				if( response != 0 && response != '' ){
					if( is_masonry ){
						element.find('.blogs').isotope('insert', $(response));
						setTimeout(function(){
							element.find('.blogs').isotope('layout');
						}, 500);
					}
					else{
						element.find('.blogs').append(response);
					}

					ts_register_carousel(element.parent(), $);
				}
				else{ /* No results */
					button.parent().remove();
				}
			}
		});

		return false;
	});
	
	/*** Copy coupon ***/
	$('.ts-copy-button').on('click', function(){
		if( typeof navigator.clipboard != 'undefined' ){
			navigator.clipboard.writeText( $(this).data('copy') );
			var this_button = $(this);
			this_button.addClass('loading');
			setTimeout(function(){
				this_button.removeClass('loading');
			}, 2000);
		}
	});

	/*** Counter ***/
	function ts_counter(elements){
		if( elements.length > 0 ){
			var interval = setInterval(function(){
				elements.each(function(index, element){
					var wrapper = jQuery(element);
					var second = parseInt(wrapper.find('.seconds .number').text());
					if( second > 0 ){
						second--;
						second = (second < 10) ? zeroise(second, 2) : second.toString();
						wrapper.find('.seconds .number').text(second);
						return;
					}

					var delta = 0;
					var time_day = 60 * 60 * 24;
					var time_hour = 60 * 60;
					var time_minute = 60;

					var day = parseInt(wrapper.find('.days .number').text());
					var hour = parseInt(wrapper.find('.hours .number').text());
					var minute = parseInt(wrapper.find('.minutes .number').text());

					if( day != 0 || hour != 0 || minute != 0 || second != 0 ){
						delta = (day * time_day) + (hour * time_hour) + (minute * time_minute) + second;
						delta--;

						day = Math.floor(delta / time_day);
						delta -= day * time_day;

						hour = Math.floor(delta / time_hour);
						delta -= hour * time_hour;

						minute = Math.floor(delta / time_minute);
						delta -= minute * time_minute;

						second = delta > 0 ? delta : 0;

						day = (day < 10) ? zeroise(day, 2) : day.toString();
						hour = (hour < 10) ? zeroise(hour, 2) : hour.toString();
						minute = (minute < 10) ? zeroise(minute, 2) : minute.toString();
						second = (second < 10) ? zeroise(second, 2) : second.toString();

						wrapper.find('.days .number').text(day);
						wrapper.find('.hours .number').text(hour);
						wrapper.find('.minutes .number').text(minute);
						wrapper.find('.seconds .number').text(second);
					}
				});
			}, 1000);
		}
	}

	ts_counter($('.product .counter-wrapper, .ts-countdown .counter-wrapper'));
	
	/*** Product Gallery ***/
	$(document).on('click', '.products .product .ts-product-galleries > div', function(){
		$(this).addClass('active').siblings().removeClass('active');
		$(this).closest('.product').find('figure img:first').attr('src', $(this).data('thumb')).removeAttr('srcset sizes');
	});
	
	/*** Widgets ***/
	/* Custom WP Widget Categories Dropdown */
	$('.widget_categories > ul').each(function(index, ele){
		var _this = $(ele);
		var icon_toggle_html = '<span class="icon-toggle"></span>';
		var ul_child = _this.find('ul.children');
		ul_child.hide();
		ul_child.closest('li').addClass('cat-parent');
		ul_child.before(icon_toggle_html);
	});

	$('.widget_categories span.icon-toggle').on('click', function(){
		var parent_li = $(this).parent('li.cat-parent');
		if( !parent_li.hasClass('active') ){
			parent_li.find('ul.children:first').slideDown();
			parent_li.addClass('active');
		}
		else{
			parent_li.find('ul.children').slideUp();
			parent_li.removeClass('active');
			parent_li.find('li.cat-parent').removeClass('active');
		}
	});

	$('.widget_categories li.current-cat').parents('ul.children').siblings('.icon-toggle').trigger('click');
	$('.widget_categories li.current-cat.cat-parent > .icon-toggle').trigger('click');

	/* Product Categories widget */
	$('.ts-product-categories-widget .icon-toggle').on('click', function(){
		var parent_li = $(this).parent('li.cat-parent');
		if( !parent_li.hasClass('active') ){
			parent_li.addClass('active');
			parent_li.find('ul.children:first').slideDown();
		}
		else{
			parent_li.find('ul.children').slideUp();
			parent_li.removeClass('active');
			parent_li.find('li.cat-parent').removeClass('active');
		}
	});

	$('.ts-product-categories-widget').each(function(){
		var element = $(this);
		element.find('ul.children').parent('li').addClass('cat-parent');
		element.find('li.current.cat-parent > .icon-toggle').trigger('click');
		element.find('li.current').parents('ul.children').siblings('.icon-toggle').trigger('click');
	});

	/* Product Filter By Availability */
	$('.product-filter-by-availability-wrapper > ul input[type="checkbox"]').on('change', function(){
		$(this).parent('li').siblings('li').find('input[type="checkbox"]').attr('checked', false);
		var val = '';
		if( $(this).is(':checked') ){
			val = $(this).val();
		}
		var form = $(this).closest('ul').siblings('form');
		if( val != '' ){
			form.find('input[name="stock"]').val(val);
		}
		else{
			form.find('input[name="stock"]').remove();
		}
		form.submit();
	});

	/* Product Filter By Price */
	$('.product-filter-by-price-wrapper li').on('click', function(){
		var form = $(this).closest('ul').siblings('form');
		if( !$(this).hasClass('chosen') ){
			var min_price = $(this).data('min');
			var max_price = $(this).data('max');

			if( min_price !== '' ){
				form.find('input[name="min_price"]').val(min_price);
			}
			else{
				form.find('input[name="min_price"]').remove();
			}
			if( max_price !== '' ){
				form.find('input[name="max_price"]').val(max_price);
			}
			else{
				form.find('input[name="max_price"]').remove();
			}
		}
		else{
			form.find('input[name="min_price"]').remove();
			form.find('input[name="max_price"]').remove();
		}
		form.submit();
	});

	/* Product Filter By Brand */
	$('.product-filter-by-brand-wrapper ul input[type="checkbox"]').on('change', function(){
		var wrapper = $(this).parents('.product-filter-by-brand-wrapper');
		var query_type = wrapper.find('> .query-type').val();
		var checked = $(this).is(':checked');
		var val = new Array();
		if( query_type == 'or' ){
			wrapper.find('ul input[type="checkbox"]').attr('checked', false);
			if( checked ){
				$(this).off('change');
				$(this).attr('checked', true);
				val.push($(this).val());
			}
		}
		else{
			wrapper.find('ul input[type="checkbox"]:checked').each(function(index, ele){
				val.push($(ele).val());
			});
		}
		val = val.join(',');
		var form = wrapper.find('form');
		if( val != '' ){
			form.find('input[name="product_brand"]').val(val);
		}
		else{
			form.find('input[name="product_brand"]').remove();
		}
		form.submit();
	});
});

function zeroise(str, max){
	str = str.toString();
	return str.length < max ? zeroise('0' + str, max) : str;
}

class TS_Carousel {
	register($scope, $){
		var carousel = this;

		/* [wrapper selector, slider selector, slider options (remove dynamic columns at last)] */
		var data = [
			['.ts-product-wrapper.grid, .ts-product-in-category-tab-wrapper.grid, .ts-product-in-product-type-tab-wrapper.grid, .ts-product-deals-wrapper.grid', '.products', { breakpoints: { 0: { slidesPerView: 1 }, 305: { slidesPerView: 2 }, 630: { slidesPerView: 3 }, 750: { slidesPerView: 4 }, 1200: { slidesPerView: 5 } } }]
			,['.ts-product-wrapper.list, .ts-product-in-category-tab-wrapper.list, .ts-product-in-product-type-tab-wrapper.list, .ts-product-deals-wrapper.list', '.products', { breakpoints: { 0: { slidesPerView: 1 }, 580: { slidesPerView: 2 } } }]
			, ['.list-categories .ts-product-category-wrapper, .ts-product-category-wrapper.ts-image-position-left, .ts-product-category-wrapper.ts-image-position-right', '.products', { breakpoints: { 0: { slidesPerView: 1 }, 280: { slidesPerView: 2 }, 600: { slidesPerView: 3 }, 900: { slidesPerView: 4 }} }]
			, ['.ts-product-category-wrapper.ts-image-position-top', '.products', { breakpoints: { 0: { slidesPerView: 1 }, 280: { slidesPerView: 2 }, 450: { slidesPerView: 3 }, 600: { slidesPerView: 4 }, 900: { slidesPerView: 5 }} }]
			, ['.ts-product-brand-wrapper', '.content-wrapper', { breakpoints: { 0: { slidesPerView: 1 }, 300: { slidesPerView: 2 }, 360: { slidesPerView: 3 }, 600: { slidesPerView: 4 }, 715: { slidesPerView: 5 }, 900: { slidesPerView: 6 } } }]
			, ['.ts-products-widget-wrapper', null, { spaceBetween: 10, breakpoints: { 0: { slidesPerView: 1 } } }]
			, ['.ts-blogs-wrapper', '.blogs', { breakpoints: { 0: { slidesPerView: 1 }, 760: { slidesPerView: 2 }, 1200: { slidesPerView: 3 } } }]
			, ['.ts-logo-slider-wrapper', '.items', { breakpoints: { 0: { slidesPerView: 1 }, 300: { slidesPerView: 2 }, 450: { slidesPerView: 3 }, 600: { slidesPerView: 4 }, 715: { slidesPerView: 5 }, 900: { slidesPerView: 6 } } }]
			, ['.ts-team-members', '.items', { breakpoints: { 0: { slidesPerView: 1 }, 500: { slidesPerView: 2 }, 620: { slidesPerView: 3 }, 1000: { slidesPerView: 4 } } }]
			, ['.ts-instagram-wrapper', null, { breakpoints: { 0: { slidesPerView: 1 }, 300: { slidesPerView: 2 }, 560: { slidesPerView: 3 }, 720: { slidesPerView: 4 }, 1000: { slidesPerView: 5 }, 1200: { slidesPerView: 6 } } }]
			, ['.ts-testimonial-wrapper', '.items', { breakpoints: { 0: { slidesPerView: 1 }, 600: { slidesPerView: 2 } } }]
			, ['.ts-blogs-widget-wrapper', null, { spaceBetween: 10, breakpoints: { 0: { slidesPerView: 1 } } }]
			, ['.ts-recent-comments-widget-wrapper', null, { spaceBetween: 10, breakpoints: { 0: { slidesPerView: 1 } } }]
			, ['.ts-blogs-wrapper .thumbnail.gallery', 'figure', { effect: 'fade', fadeEffect: { crossFade: true }, spaceBetween: 10, breakpoints: { 0: { slidesPerView: 1 } }, simulateTouch: false, allowTouchMove: false, autoplay: true }]
		];

		$.each(data, function(index, value){
			carousel.run(value, $);
		});
	}

	run(data, $){
		$(data[0]).each(function(index){
			if( !$(this).hasClass('ts-slider') || $(this).hasClass('generated-slider') ){
				return;
			}

			var wrapper = $(this);
			if( typeof data[1] != 'undefined' && data[1] != null ){
				var swiper = wrapper.find(data[1]);
			}else{
				var swiper = wrapper;
			}

			if( swiper.find('> *').length <= 1 ){
				wrapper.removeClass('loading');
				wrapper.find('.loading').removeClass('loading');

				return;
			}

			var unique_class = 'swiper-' + Math.floor(Math.random() * 10000) + '-' + index;
			var show_nav = typeof wrapper.attr('data-nav') != 'undefined' && wrapper.attr('data-nav') == 1 ? true : false;
			var show_dots = typeof wrapper.attr('data-dots') != 'undefined' && wrapper.attr('data-dots') == 1 ? true : false;
			var auto_play = typeof wrapper.attr('data-autoplay') != 'undefined' && wrapper.attr('data-autoplay') == 1 ? true : false;
			var loop = typeof wrapper.attr('data-loop') != 'undefined' && wrapper.attr('data-loop') == 1 ? true : false;
			var columns = typeof wrapper.attr('data-columns') != 'undefined' ? parseInt(wrapper.attr('data-columns')) : 5;
			var disable_responsive = typeof wrapper.attr('data-disable_responsive') != 'undefined' && wrapper.attr('data-disable_responsive') == 1 ? true : false;

			wrapper.addClass('generated-slider');
			swiper.addClass('swiper ' + unique_class);
			swiper.find('> *').addClass('swiper-slide');
			swiper.wrapInner('<div class="swiper-wrapper"></div>');

			var slider_options = {
				loop: loop
				, spaceBetween: 0
				, pauseOnMouseEnter: true
				, centeredSlides: false
				, breakpointsBase: 'container'
				, on: {
					init: function(){
						wrapper.removeClass('loading');
						wrapper.find('.loading').removeClass('loading');
						$(window).trigger('ts_slider_middle_navigation_position', [swiper]);
					}
					,resize: function(){
						$(window).trigger('ts_slider_middle_navigation_position', [swiper]);
					}
				}
			};

			/* RTL */
			if( $('body').hasClass('rtl') ){
				swiper.attr('dir', 'rtl');
			}

			/* Navigation */
			if( show_nav ){
				swiper.append('<div class="swiper-button-prev"></div><div class="swiper-button-next"></div>');

				slider_options.navigation = {
					nextEl: '.swiper-button-next'
					, prevEl: '.swiper-button-prev'
				};
			}

			/* Pagination */
			if( show_dots ){
				swiper.append('<div class="swiper-pagination"></div>');

				slider_options.pagination = {
					el: '.swiper-pagination'
					, clickable: true
				};
			}

			/* Autoplay */
			if( auto_play ){
				slider_options.autoplay = {
					delay: 4000
					, disableOnInteraction: false
					, pauseOnMouseEnter: true
				};
			}

			if( typeof data[2] != 'undefined' && data[2] != null ){
				$.extend(slider_options, data[2]);

				if( typeof data[2].breakpoints != 'undefined' ){ /* change breakpoints => add dynamic columns at last */
					switch( data[0] ){
						case '.ts-product-wrapper.grid, .ts-product-in-category-tab-wrapper.grid, .ts-product-in-product-type-tab-wrapper.grid, .ts-product-deals-wrapper.grid':
							slider_options.breakpoints[1200] = { slidesPerView: columns };
							break;
						case '.ts-product-wrapper.list, .ts-product-in-category-tab-wrapper.list, .ts-product-in-product-type-tab-wrapper.list, .ts-product-deals-wrapper.list':
							slider_options.breakpoints[1200] = { slidesPerView: columns };
							break;
						case '.ts-blogs-wrapper':
							slider_options.breakpoints[1000] = { slidesPerView: columns };
							break;
						case '.list-categories .ts-product-category-wrapper, .ts-product-category-wrapper.ts-image-position-left, .ts-product-category-wrapper.ts-image-position-right':
							slider_options.breakpoints[1200] = { slidesPerView: columns };
							break;
						case '.ts-product-category-wrapper.ts-image-position-top':
							slider_options.breakpoints[1200] = { slidesPerView: columns };
							break;
						case '.ts-product-brand-wrapper':
							slider_options.breakpoints[900] = { slidesPerView: columns };
							break;
						case '.ts-team-members':
							slider_options.breakpoints[1000] = { slidesPerView: columns };
							break;
						case '.ts-testimonial-wrapper':
							slider_options.breakpoints[992] = { slidesPerView: columns };
							break;
						case '.ts-instagram-wrapper':
							slider_options.breakpoints[1200] = { slidesPerView: columns };
							break;
						default:
					}
				}
			}

			if( wrapper.hasClass('use-logo-setting') ){ /* Product Brands - Logos */
				var break_point = wrapper.data('break_point');
				var item = wrapper.data('item');

				if( break_point.length > 0 ){
					slider_options.breakpoints = {};
					for( var i = 0; i < break_point.length; i++ ){
						slider_options.breakpoints[break_point[i]] = { slidesPerView: item[i] };
					}
				}
			}

			if( disable_responsive ){
				slider_options.breakpoints = { 0: { slidesPerView: columns } };
			}

			if( columns == 1 ){
				slider_options.breakpoints = { 0: { slidesPerView: 1 } };
			}

			if( data[0] == '.ts-blogs-wrapper' ){
				switch( columns ){
					case '1':
						slider_options.breakpoints = { 0: { slidesPerView: columns } };
						break;
					case '2':
						slider_options.breakpoints = { 0: { slidesPerView: 1 }, 700: { slidesPerView: columns } };
						break;
					default:
						slider_options.breakpoints = { 0: { slidesPerView: 1 }, 700: { slidesPerView: 2 }, 1000: { slidesPerView: columns } };
						break;
				}
			}

			new Swiper('.' + unique_class, slider_options);
		});
	}
}

function ts_register_carousel($scope, $){
	var carousel = new TS_Carousel();
	carousel.register($scope, $);
}

function ts_register_masonry($scope, $){
	if( typeof $.fn.isotope == 'function' ){
		/* Blog */
		setTimeout(function(){
			$('.ts-blogs-wrapper.ts-masonry .blogs').isotope();
		}, 10);

		$('.ts-blogs-wrapper.ts-masonry').removeClass('loading');
	}
}

jQuery(window).on('elementor/frontend/init', function(){
	var elements = ['ts-products', 'ts-product-deals', 'ts-product-categories', 'ts-product-brands', 'ts-blogs'
		, 'ts-logos', 'ts-team-members', 'ts-testimonial'
		, 'ts-products-in-category-tabs', 'ts-products-in-product-type-tabs', 'ts-special-products'
		, 'wp-widget-ts_products', 'wp-widget-ts_blogs', 'wp-widget-ts_recent_comments', 'wp-widget-ts_instagram'];
	jQuery.each(elements, function(index, name){
		elementorFrontend.hooks.addAction('frontend/element_ready/' + name + '.default', ts_register_carousel);
	});

	elementorFrontend.hooks.addAction('frontend/element_ready/ts-blogs.default', ts_register_masonry);
});