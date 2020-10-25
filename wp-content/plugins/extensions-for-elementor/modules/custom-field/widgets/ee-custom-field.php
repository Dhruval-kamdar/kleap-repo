<?php
namespace ElementorExtensions\Modules\CustomField\Widgets;

if ( ! defined( 'ABSPATH' ) ) exit;

use ElementorExtensions\Base\Base_Widget;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Image_Size;
use ElementorExtensions\Classes\Utils;

class EE_Custom_Field extends Base_Widget {

	public $default_fields = [
		'post_title',
		'post_content'
	];

	public function get_name() {
		return $this->widget_name_prefix.'custom-field';
	}

	public function get_title() {
		return __( 'Custom Field', 'elementor-extensions' );
	}

	public function get_icon() {
		return 'eicon-text-field';
	}

	public function get_keywords() {
		return [ 'custom', 'field', 'cf', 'custom field', 'customfield', 'c' ];
	}
	
	protected function _register_controls() {

		$this->start_controls_section(
			'section_content',
			[
				'label' => __( 'Custom Field', 'elementor-extensions' ),
			]
		);

		$custom_fields = $this->get_custom_fields();
		$this->add_control(
			'custom_fields',
			[
				'label' 		=> __( 'Custom Fields', 'elementor-extensions' ),
				'type' 			=> Controls_Manager::SELECT,
				'default' 		=> 'post_title',
				'options'		=> $custom_fields['fields'],
			]
		);

		$this->add_control(
			'field_type',
			[
				'label' 		=> __( 'Field Type', 'elementor-extensions' ),
				'type' 			=> Controls_Manager::SELECT,
				'default' 		=> 'text',
				'options'		=> [
					'text'   => 'Text',
					'image'  => 'Image',
					'editor' => 'Editor',
					'link'   => 'Link',
					'date'   => 'Date',
				],
				'condition'     => [
					'custom_fields!'=> ''
				]
			]
		);

		$this->add_control(
			'field_controls_heading',
			[
				'label' => __( 'Field Controls', 'elementor-extensions' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'field_type' => ['link', 'image', 'date']
				]
			]
		);

		$this->add_control(
			'date_format',
			[
				'label' 		=> __( 'Date Format', 'elementor-extensions' ),
				'type' 			=> Controls_Manager::SELECT,
				'default'       => 'd-m-Y',				
				'options'		=> [
					'd-m-Y'   => 'dd-mm-yyyy',
					'j M Y'   => '23 Jan 2019',
					'jS F Y'   => '23rd January 2019'
				],
				'condition' => [
					'field_type' => ['date']
				]
			]
		);

		$this->add_control(
			'link_label',
			[
				'label' => __( 'Link Label', 'elementor-extensions' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Link', 'elementor-extensions' ),
				'placeholder' => __( 'Add link label', 'elementor-extensions' ),
				'condition' => [
					'field_type' => 'link',
					'custom_fields!' => ''
				]
			]
		);

		$this->add_control(
			'link_target',
			[
				'label' => __( 'Open in new window', 'elementor-extensions' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'elementor-extensions' ),
				'label_off' => __( 'No', 'elementor-extensions' ),
				'return_value' => 'yes',
				'default' => 'no',
				'condition' => [
					'field_type' => 'link',
					'custom_fields!' => ''
				]
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name' => 'feature', /* Usage: `{name}_size` and `{name}_custom_dimension`, in this case `thumbnail_size` and `thumbnail_custom_dimension`. */
				'include' => [],
				'default' => 'large',
				'condition' => [
					'field_type' => 'image',
					'custom_fields!' => ''
				]
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_general',
			[
				'label' => __( 'General', 'elementor-extensions' ),
				'tab' => Controls_Manager::TAB_STYLE
			]
		);

		$this->add_control(
			'background',
			[
				'label' => __( 'Background', 'elementor-extensions' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ee_mb_field_wrapper .ee_mb_custom_field' => 'background: {{VALUE}};',
				]
			]
		);

		$this->add_control(
			'color',
			[
				'label' => __( 'Text Color', 'elementor-extensions' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ee_mb_field_wrapper .ee_mb_custom_field, {{WRAPPER}} .ee_mb_field_wrapper .ee_mb_custom_field a' => 'color: {{VALUE}};',
				]
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'typography',
				'label' => __( 'Typography', 'elementor-extensions' ),
				'selector' => '{{WRAPPER}} .ee_mb_field_wrapper .ee_mb_custom_field, {{WRAPPER}} .ee_mb_field_wrapper .ee_mb_custom_field a',
			]
		);

		$this->add_control(
			'margin',
			[
				'label' => __( 'Margin', 'elementor-extensions' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top'    => 5,
					'right'  => 5,
					'bottom' => 5,
					'left'   => 5
				],
				'selectors' => [
					'{{WRAPPER}} .ee_mb_field_wrapper .ee_mb_custom_field' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'padding',
			[
				'label' => __( 'Content Padding', 'elementor-extensions' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top'    => 10,
					'right'  => 10,
					'bottom' => 10,
					'left'   => 10
				],
				'selectors' => [
					'{{WRAPPER}} .ee_mb_field_wrapper .ee_mb_custom_field' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'border',
				'selector' => '{{WRAPPER}} .ee_mb_field_wrapper .ee_mb_custom_field',
				'separator' => 'before'
			]
		);


		$this->add_control(
			'alignment',
			[
				'label' => __( 'Alignment', 'elementor-extensions' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'elementor-extensions' ),
						'icon' => 'fa fa-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'elementor-extensions' ),
						'icon' => 'fa fa-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'elementor-extensions' ),
						'icon' => 'fa fa-align-right',
					],
					'justify' => [
						'title' => __( 'Justified', 'elementor-extensions' ),
						'icon' => 'fa fa-align-justify',
					],
				],
				'default' => 'left',
				'selectors' => [
					'{{WRAPPER}} .ee_mb_field_wrapper .ee_mb_custom_field' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_link_style',
			[
				'label' => __( 'Link', 'elementor-extensions' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'field_type' => 'link'
				]
			]
		);

		$this->start_controls_tabs( 'link_styles' );

		$this->start_controls_tab( 'normal',
			[
				'label' => __( 'Normal', 'elementor-extensions' ),
			]
		);

		$this->add_control(
			'link_background',
			[
				'label' => __( 'Background', 'elementor-extensions' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ee_mb_field_wrapper .ee_mb_link_field.ee_mb_custom_field a' => 'background: {{VALUE}};',
				]
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'normal_link_typo',
				'label' => __( 'Typography', 'elementor-extensions' ),
				'selector' => '{{WRAPPER}} .ee_mb_field_wrapper .ee_mb_link_field.ee_mb_custom_field a',
			]
		);

		$this->add_control(
			'normal_link_color',
			[
				'label' => __( 'Color', 'elementor-extensions' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ee_mb_field_wrapper .ee_mb_link_field.ee_mb_custom_field a' => 'color: {{VALUE}}',
				]
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'link_border',
				'selector' => '{{WRAPPER}} .ee_mb_field_wrapper .ee_mb_link_field.ee_mb_custom_field a',
				'separator' => 'before'
			]
		);

		$this->add_control(
			'link_border_radius',
			[
				'label' => __( 'Border Radius', 'elementor-extensions' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .ee_mb_field_wrapper .ee_mb_link_field.ee_mb_custom_field a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 'hover',
			[
				'label' => __( 'Hover', 'elementor-extensions' ),
			]
		);

		$this->add_control(
			'hover_link_background',
			[
				'label' => __( 'Background', 'elementor-extensions' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ee_mb_field_wrapper .ee_mb_link_field.ee_mb_custom_field a:hover' => 'background: {{VALUE}};',
				]
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'hover_link_typo',
				'label' => __( 'Typography', 'elementor-extensions' ),
				'selector' => '{{WRAPPER}} .ee_mb_field_wrapper .ee_mb_link_field.ee_mb_custom_field a:hover',
			]
		);

		$this->add_control(
			'hover_link_color',
			[
				'label' => __( 'Color', 'elementor-extensions' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ee_mb_field_wrapper .ee_mb_link_field.ee_mb_custom_field a:hover' => 'color: {{VALUE}}',
				]
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'hover_link_border',
				'selector' => '{{WRAPPER}} .ee_mb_field_wrapper .ee_mb_link_field.ee_mb_custom_field a:hover',
				'separator' => 'before'
			]
		);

		$this->add_control(
			'hover_link_border_radius',
			[
				'label' => __( 'Border Radius', 'elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .ee_mb_field_wrapper .ee_mb_link_field.ee_mb_custom_field a:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'link_padding',
			[
				'label' => __( 'Padding', 'elementor-extensions' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top'    => 10,
					'right'  => 10,
					'bottom' => 10,
					'left'   => 10
				],
				'selectors' => [
					'{{WRAPPER}} .ee_mb_field_wrapper .ee_mb_link_field.ee_mb_custom_field a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

	}

	protected function render() {

		$settings = $this->get_settings_for_display();

		$field_type = (!empty($settings['field_type'])) ? $settings['field_type'] : '';
		$custom_field = (!empty($settings['custom_fields'])) ? $settings['custom_fields'] : '';
		
		$get_field_values = $this->get_custom_fields();

		$field_val = '';
		if(!empty($get_field_values['value'][$custom_field])):
			$field_val = $get_field_values['value'][$custom_field];
		endif;

		if($custom_field == 'post_title'):
			$field_val = get_the_title();
		endif;

		if($custom_field == 'post_content'):
			$field_val = get_the_content();
		endif;

		if($custom_field == 'post_excerpt'):
			$field_val = get_the_excerpt();
		endif;

		$html = '<div class="ee_mb_field_wrapper">';
		switch($field_type){
			case 'text':
					$html .= '<div class="ee_mb_text_field ee_mb_custom_field">'.$field_val.'</div>';
				break;
			
			case 'image':

					$image_size = $settings['feature_size'];
					if($settings['feature_size'] == 'custom'):
						$image_size = [];
						$dimensions = $settings['feature_custom_dimension'];
						$image_size[] = $dimensions['width'];
						$image_size[] = $dimensions['height'];
					endif;

					$feature = wp_get_attachment_image( $field_val, $image_size, "", array( "class" => "custom_field_img" ) );
					$html .= '<div class="ee_mb_img_field ee_mb_custom_field">
				              '.$feature.'
						  </div>';
				break;

			case 'editor':
					$html .= '<div class="ee_mb_editor_field ee_mb_custom_field">'.wpautop($field_val).'</div>';
				break;
			
			case 'link':
					$link_label = $settings['link_label'];

					if(empty($link_label)):
						$link_label = $field_val;
					endif;

					$target = '';
					if($settings['link_target'] == 'yes'):
						$target = "target='_blank'";
					endif;

					$html .= '<div class="ee_mb_link_field ee_mb_custom_field">
								<a href="'.$field_val.'" '.$target.'>'.$link_label.'</a>
							  </div>';
				break;
			case 'date':

				$date = $field_val;
				if(!empty($settings['date_format'])):
					$format = $settings['date_format'];
					$date = date($format, strtotime($date));
				endif;
			
				$html .= '<div class="ee_mb_date_field ee_mb_custom_field">'.$date.'</div>';
					
				break;
			case 'default':
				break;
		}

		$html .= '</div>';

		echo $html;
	}

	/*@ Build a custom field */
	public function get_custom_fields(){
	
		$custom_fields = get_post_custom();

		$exclude_fields = [
			'_edit_lock',
			'_edit_last',
			'_wp_page_template',
			'_elementor_template_type',
			'_elementor_version',
			'_elementor_elements_usage',
			'_elementor_pro_version',
			'_elementor_edit_mode',
			'_elementor_css',
			'_elementor_data'
		];

		$match_field = [
			'_thumbnail_id'
		];

		$replace_field = [
			'Featured Image'
		];

		$list = array();
		$list['fields']['post_title'] = __( 'Title', 'elementor-extensions' );
		$list['fields']['post_content'] = __( 'Content', 'elementor-extensions' );
		$list['fields']['post_excerpt'] = __( 'Excerpt', 'elementor-extensions' );

		$list['value'] = [];
		if(!empty($custom_fields)):

			foreach ($custom_fields as $key => $field):

				/*@ Remove _field from the array */
				array_push($exclude_fields, '_'.$key);

				if(!in_array($key, $exclude_fields)):

					$field_key = $key;
					$field_name = ucfirst(trim(str_replace('_', ' ', $field_key)));

					if(in_array($key, $match_field)):
						$field_name = str_replace($match_field, $replace_field, $key);
					endif;

					$list['fields'][$field_key] = __( $field_name, 'elementor-extensions' );
					$list['value'][$field_key] = (!empty($field[0])) ? $field[0] : '';
				
				endif;
			endforeach;
		endif;

		return $list;
	}

	protected function _content_template() {
		
	}
}