<?php
namespace ElementorExtensions\Controls;

if ( ! defined( 'ABSPATH' ) ) exit;

use Elementor\Base_Data_Control;

class EE_MB_Snazzy_Control extends Base_Data_Control {

	public function get_type() {
		return 'ee-mb-snazzy';
	}

	protected function get_default_settings() {
		
		return [
			'snazzy_options' => [
				'key' 		=> get_option( 'el_siteset_snazzy_map_key'),
				'endpoint'	=> get_option( 'el_siteset_snazzy_map_endpoint') || 'explore',
				'term'		=> 'color',
			],
			'label_block'	=> true,
		];
	}

	public function content_template() {
		$control_uid = $this->get_control_uid();
		?>
		<# 
		if ( data.snazzy_options.key ) { #>
			<div class="elementor-control-field ee-control-field">
				<label for="<?php echo $control_uid; ?>" class="elementor-control-title ee-control-field__title">{{{ data.label }}}</label>
				<div class="elementor-control-input-wrapper ee-control-field__input-wrapper">
					<select id="<?php echo $control_uid; ?>" class="elementor-select2  ee-control ee-control--select2" type="select2" data-setting="{{ data.name }}">
					<# if ( data.controlValue ) {
						var value = JSON.parse( data.controlValue ); #>
						<option selected value="{{ value.id }}">{{{ value.name }}}</option>
					<# } #>
					</select>
				</div>
			</div>
			<# if ( data.description ) { #>
				<div class="elementor-control-field-description">{{{ data.description }}}</div>
			<# } #>
		<# } else { #>
			<div class="elementor-control-field-description">
				<div class="ee-raw-html ee-raw-html__warning">
					<?php printf( __( 'Looks like you haven\'t added your Snazzy Maps API key. Click %1$shere%2$s to set it up.', 'elementor-extensions' ), '<a target="_blank" href="' . admin_url( 'admin.php?page=elementor_extensions#integration' ) . '">', '</a>' ); ?>		
				</div>
			</div>
		<# } #>
		<?php
	}
}
