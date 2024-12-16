var ts_elementor_autocomplete = elementor.modules.controls.BaseData.extend({
	onReady: function(){
		var select_obj = this.ui.select;
		
		var sortable 		= select_obj.attr('data-sortable') == 1 ? true : false;
		var selected_values = select_obj.attr('data-selected_values');
		var query_type 		= select_obj.attr('data-query_type');
		var query_name 		= select_obj.attr('data-query_name');
		
		if( typeof selected_values == 'string' && selected_values && select_obj.find('option').length == 0 ){
			jQuery('body').addClass( 'elementor-panel-loading' );
			
			jQuery.ajax({
				type : 'POST',
				timeout : 30000,
				url : ts_autocomplete_params.ajaxurl,
				data : {action: 'ts_elementor_autocomplete_load_options', selected_values: selected_values, query_type: query_type, query_name: query_name, security: ts_autocomplete_params.nonce},
				error: function(xhr,err){
					
				},
				success: function(response) {
					if( response ){
						response = JSON.parse( response );
						var ids = response['ids'];
						var names = response['names'];
						
						jQuery.each( ids, function(index, id){
							select_obj.append('<option value="' + id + '" selected>' + names[index] + '</option>');
						});
						
						select_obj.trigger('change');
					}
					jQuery('body').removeClass( 'elementor-panel-loading' );
				}
			});
		}
		
		if( typeof jQuery.fn.select2 != 'function' ){
			return;
		}
		
		select_obj.select2({
			ajax: {
				url: ts_autocomplete_params.ajaxurl
				,dataType: 'json'
				,delay: 250
				,data: function( data ){
					return {
						search_term: data.term
						,action: 'ts_elementor_autocomplete_query', query_type: query_type, query_name: query_name, security: ts_autocomplete_params.nonce
					};
				}
				,processResults: function( response ){
					return {
						results: response
					};
				}
				,cache: true
			}
			,minimumInputLength: 3
			,allowClear: true
			,placeholder: ''
		}).on("select2:select", function (e){
			var element = jQuery(this).children('option[value=' + e.params.data.id + ']');

			ts_autocomplete_move_element_to_end(element);

			jQuery(this).trigger('change');
		});
		
		if( sortable && typeof jQuery.fn.sortable == 'function' ){
			var ele = select_obj.parent().find('ul.select2-selection__rendered');
			ele.sortable({
				containment: 'parent',
				update: function() {
					ts_autocomplete_order_sorted_values( select_obj );
					select_obj.trigger('change');
				}
			});
		}
	},
	saveValue: function(){
		
	},
	onBeforeDestroy: function(){
		
	}
});

elementor.addControlView('ts_autocomplete', ts_elementor_autocomplete);

function ts_autocomplete_order_sorted_values( select_obj ){
	var value = ''
	select_obj.parent().find('ul.select2-selection__rendered').children('li[title]').each(function(i, obj){
		var element = select_obj.children('option').filter(function (){
			return jQuery(this).text() == obj.title;
		});
		ts_autocomplete_move_element_to_end(element)
	});
};

function ts_autocomplete_move_element_to_end( element ){
    var parent = element.parent();
    element.detach();
    parent.append(element);
};