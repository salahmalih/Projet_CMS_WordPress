<?php
if( !class_exists('\Elementor\Base_Data_Control') ){
	return;
}

class TS_Elementor_AutoComplete_Control extends \Elementor\Base_Data_Control{
	public function get_type(){
		return 'ts_autocomplete';
	}
	
	protected function get_default_settings(){
		return array(
			'options'			=> array()
			,'multiple'			=> true
			,'select2options'	=> array()
			,'sortable'			=> true
			,'autocomplete'		=> array(
				'type'	=> 'post' /* post or taxonomy */
				,'name'	=> 'post' /* post, page, ... */
			)
		);
	}
	
	public function enqueue(){
		$js_dir = plugin_dir_url( __DIR__ ) . 'js';
		
		wp_enqueue_script( 'ts-elementor-autocomplete', $js_dir . '/elementor-autocomplete.js', array('jquery'), THEMESKY_VERSION, true );
		
		$data = array(
			'ajaxurl'	=> admin_url( 'admin-ajax.php', 'relative' )
			,'nonce'	=> wp_create_nonce( 'ts-elementor-autocomplete-nonce' )
		);
		wp_localize_script( 'ts-elementor-autocomplete', 'ts_autocomplete_params', $data );
	}
	
	public function content_template(){		
		$control_uid = $this->get_control_uid();
		?>
		<div class="elementor-control-field">
			<# if ( data.label ) {#>
				<label for="<?php echo $control_uid; ?>" class="elementor-control-title">{{{ data.label }}}</label>
			<# } #>
			<div class="elementor-control-input-wrapper elementor-control-unit-5">
				<# var multiple = ( data.multiple ) ? 'multiple' : ''; #>
				<# var sortable = ( data.sortable ) ? '1' : '0'; #>
				<# var selected_values = _.isArray(data.controlValue) ? data.controlValue.join() : data.controlValue; #>
				<select id="<?php echo $control_uid; ?>" class="elementor-select2" type="select2" {{ multiple }} data-setting="{{ data.name }}" data-selected_values="{{ selected_values }}" data-query_type="{{ data.autocomplete.type }}" data-query_name="{{ data.autocomplete.name }}" data-sortable="{{ sortable }}">
					<# _.each( data.options, function( option_title, option_value ) {
						var value = data.controlValue;
						if ( typeof value == 'string' ) {
							var selected = ( option_value === value ) ? 'selected' : '';
						} else if ( null !== value ) {
							var value = _.values( value );
							var selected = ( -1 !== value.indexOf( option_value ) ) ? 'selected' : '';
						}
						#>
					<option {{ selected }} value="{{ option_value }}">{{{ option_title }}}</option>
					<# } ); #>
				</select>
			</div>
		</div>
		<# if ( data.description ) { #>
			<div class="elementor-control-field-description">{{{ data.description }}}</div>
		<# } #>
		<?php
	}
}
