<?php
namespace ElementorExtensions\Modules\DocumentLibrary\Widgets;

if ( ! defined( 'ABSPATH' ) ) exit;

use ElementorExtensions\Base\Base_Widget;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Typography;

class EE_Document_Library extends Base_Widget {

	public function get_name() {
		return $this->widget_name_prefix.'document-library';
	}

	public function get_title() {
		return __( 'Document Library', 'elementor-extensions' );
	}

	public function get_icon() {
		return 'eicon-document-file';
	}

	public function get_keywords() {
		return [ 'document', 'library', 'dl', 'document library', 'documentlibrary', 'd', 'l' ];
	}

	protected function _register_controls() {
		
		$documents = $this->getDocuments();

		$this->start_controls_section(
			'content_section',
			[
				'label' => __( 'Document Library', 'elementor-extensions' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'document_header',
			[
				'label' => __( 'Add Documents or Images', 'elementor-extensions' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'add_documents',
			[
				'label' => __( 'Add Documents', 'elementor-extensions' ),
				'type' => Controls_Manager::SELECT2,
				'multiple' => true,
				'options' => $documents,
				'label_block' => true,
				'show_label' => false,
				'description' => __( 'Documents or Images should be present into the Media. Enter first latter for search.', 'elementor-extensions' ),
			]
		);

		$this->add_control(
			'is_header_sortable',
			[
				'label' => __( 'Is Header Sortable?', 'elementor-extensions' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'elementor-extensions' ),
				'label_off' => __( 'No', 'elementor-extensions' ),
				'return_value' => 'yes',
				'default' => 'label_off',
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'order_filename_asc',
			[
				'label' => __( 'Order A-Z by Filename', 'elementor-extensions' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'elementor-extensions' ),
				'label_off' => __( 'No', 'elementor-extensions' ),
				'return_value' => 'yes',
				'default' => 'label_off',
				'frontend_available' => true,
				'condition' => [
					'is_header_sortable!' => ''
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
            'table_style',
            [
                'label' => __( 'Table Styles', 'elementor-extensions' ),
                'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
            ]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'table_background',
				'label' => __( 'Background', 'elementor-extensions' ),
				'types' => [ 'classic', 'gradient', 'video' ],
				'selector' => '{{WRAPPER}} .document_library_wrapper',
			]
		);

		$this->add_control(
			'table_border_collapse',
			[
				'label' => __( 'Border Collapse', 'elementor-extensions' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'collapse' => __( 'Collapse', 'elementor-extensions' ),
					'separate' => __( 'Separate', 'elementor-extensions' ),
				],
				'default' => 'collapse',
				'selectors' => [
					'{{WRAPPER}} .document_library_wrapper table' => 'border-collapse: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(), [
				'name' => 'table_border',
				'default' => '1px',
				'selector' => '{{WRAPPER}} .document_library_wrapper table,{{WRAPPER}} .document_library_wrapper table th,{{WRAPPER}} .document_library_wrapper table td',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'border_hr',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
		);

		$this->add_responsive_control(
			'table_padding',
			[
				'label' => __( 'Padding', 'elementor-extensions' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .document_library_wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'table_width',
			[
				'label' 	=> __( 'Table Width', 'elementor-extensions' ),
				'type' 		=> Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em' ],
				'range' 	=> [
					'px' => [
						'min' => 0,
						'max' => 1000,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
					'em' => [
						'min' => 0,
						'max' => 1000,
					],
				],
				'default' => [
					'unit' => '%',
					'size' => 100,
				],
				'selectors' => [
					'{{WRAPPER}} .document_library_wrapper table' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);
		

		$this->end_controls_section();

		/*@ Table header style */
		$this->start_controls_section(
            'column_header_style',
            [
                'label' => __( 'Column Heading Styles', 'elementor-extensions' ),
                'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
            ]
		);

		$this->add_group_control(
            Group_Control_Typography::get_type(),
            [
				'label' => __( 'Typography', 'elementor-extensions' ),
                'name' => 'header_typo',
                'selector' => '{{WRAPPER}} .document_library_wrapper table > thead > tr > th',
            ]
		);

		$this->add_control(
            'header_color',
            [
                'label' => __( 'Text Color', 'elementor-extensions' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .document_library_wrapper table > thead > tr > th' => 'color: {{VALUE}};',
                ],
            ]
        );
		
		$this->add_control(
            'header_background',
            [
                'label' => __( 'Background Color', 'elementor-extensions' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .document_library_wrapper table > thead > tr > th' => 'background-color: {{VALUE}};',
                ],
            ]
		);

		$this->add_responsive_control(
			'header_align',
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
				],
				'default' => 'left',	
				'selectors' => [
					'{{WRAPPER}} .document_library_wrapper table > thead > tr > th' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'header_padding',
			[
				'label' => __( 'Padding', 'elementor-extensions' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .document_library_wrapper table > thead > tr > th' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(), [
				'name' => 'header_border',
				'default' => '1px',
				'selector' => '{{WRAPPER}} .document_library_wrapper table > thead > tr > th',
				'separator' => 'before',
			]
		);

		$this->end_controls_section();

		/*@ Table row styles */
		$this->start_controls_section(
            'row_style',
            [
                'label' => __( 'Row Styles', 'elementor-extensions' ),
                'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
            ]
		);

		$this->add_control(
            'table_odd_row_color',
            [
                'label' => __( 'Odd Row Background', 'elementor-extensions' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .document_library_wrapper > table > tbody > tr:nth-child(odd)' => 'background-color: {{VALUE}};',
                ],
            ]
        );

		$this->add_control(
            'table_even_row_color',
            [
                'label' => __( 'Even Row Background', 'elementor-extensions' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .document_library_wrapper > table > tbody > tr:nth-child(even)' => 'background-color: {{VALUE}};',
                ],
            ]
		);

		$this->add_control(
            'table_row_hover_color',
            [
                'label' => __( 'Hover Background', 'elementor-extensions' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .document_library_wrapper > table > tbody > tr:hover' => 'background-color: {{VALUE}};',
                ],
            ]
		);
		
		$this->end_controls_section();


		/*@ Table Column styles */
		$this->start_controls_section(
            'column_style',
            [
                'label' => __( 'Column Styles', 'elementor-extensions' ),
                'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
            ]
		);

		$this->add_control(
			'hide_filename_column',
			[
				'label' => __( 'Hide Filename Column', 'elementor-extensions' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'elementor-extensions' ),
				'label_off' => __( 'No', 'elementor-extensions' ),
				'return_value' => 'none',
				'default' => 'label_off',
				'selectors' => [
					'{{WRAPPER}} .document_library_wrapper table tr > :nth-child(1)' => 'display:{{VALUE}};',
                ],
			]
		);

		$this->add_control(
			'hide_filesize_column',
			[
				'label' => __( 'Hide Filesize Column', 'elementor-extensions' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'elementor-extensions' ),
				'label_off' => __( 'No', 'elementor-extensions' ),
				'return_value' => 'none',
				'default' => 'label_off',
				'selectors' => [
					'{{WRAPPER}} .document_library_wrapper table tr > :nth-child(2)' => 'display:{{VALUE}};',
                ],
			]
		);

		$this->add_control(
			'hide_filetype_column',
			[
				'label' => __( 'Hide Filetype Column', 'elementor-extensions' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'elementor-extensions' ),
				'label_off' => __( 'No', 'elementor-extensions' ),
				'return_value' => 'none',
				'default' => 'label_off',
				'selectors' => [
					'{{WRAPPER}} .document_library_wrapper table tr > :nth-child(3)' => 'display:{{VALUE}};',
                ],
			]
		);

		$this->end_controls_section();

		/*@ Table Cell styles */
		$this->start_controls_section(
            'cell_style',
            [
                'label' => __( 'Cell Styles', 'elementor-extensions' ),
                'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
            ]
		);

		$this->add_group_control(
            Group_Control_Typography::get_type(),
            [
				'label' => __( 'Typography', 'elementor-extensions' ),
                'name' => 'cell_typo',
                'selector' => '{{WRAPPER}} .document_library_wrapper table > tbody > tr > td',
            ]
		);

		$this->add_control(
            'cell_color',
            [
                'label' => __( 'Text Color', 'elementor-extensions' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .document_library_wrapper table > tbody > tr > td' => 'color: {{VALUE}};',
                ],
            ]
        );
		
		$this->add_responsive_control(
			'cell_align',
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
				],
				'default' => 'left',	
				'selectors' => [
					'{{WRAPPER}} .document_library_wrapper table > tbody > tr > td' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'cell_padding',
			[
				'label' => __( 'Cell Padding', 'elementor-extensions' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .document_library_wrapper table > tbody > tr > td' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'cell_spacing',
			[
				'label' 	=> __( 'Cell Spacing', 'elementor-extensions' ),
				'type' 		=> Controls_Manager::SLIDER,
				'condition' => [
					'table_border_collapse' => 'separate'
				],
				'size_units' => [ 'px', '%', 'em' ],
				'range' 	=> [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
					'em' => [
						'min' => 0,
						'max' => 100,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 0,
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(), [
				'name' => 'cell_border',
				'default' => '1px',
				'selector' => '{{WRAPPER}} .document_library_wrapper table > tbody > tr > td',
				'separator' => 'before',
			]
		);

		$this->end_controls_section();

		/*@ Link style*/
		$this->start_controls_section(
            'link_style',
            [
                'label' => __( 'Link Styles', 'elementor-extensions' ),
                'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
            ]
		);


		$this->add_group_control(
            Group_Control_Typography::get_type(),
            [
				'label' => __( 'Typography', 'elementor-extensions' ),
                'name' => 'link_type',
                'selector' => '{{WRAPPER}} .document_library_wrapper table > tbody > tr > td > a',
            ]
		);

		$this->add_control(
            'link_color',
            [
                'label' => __( 'Text Color', 'elementor-extensions' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .document_library_wrapper table > tbody > tr > td > a' => 'color: {{VALUE}};',
                ],
            ]
		);
		
		$this->add_control(
            'link_hover_color',
            [
                'label' => __( 'Hover Color', 'elementor-extensions' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .document_library_wrapper table > tbody > tr > td > a:hover' => 'color: {{VALUE}};',
                ],
            ]
		);
		
		$this->add_control(
			'link_open_new_tab',
			[
				'label' => __( 'Open attachments in new tab', 'elementor-extensions' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'elementor-extensions' ),
				'label_off' => __( 'No', 'elementor-extensions' ),
				'return_value' => 'yes',
				'default' => 'label_off',
			]
		);
	
		$this->end_controls_section();
	}

	protected function render() {

		$settings = $this->get_settings_for_display();

		$cellspacing = $border_spacing = '';
		if($settings['cell_spacing']['size'] !== 0):
			$size = $settings['cell_spacing']['size'];
			$unit = $settings['cell_spacing']['unit'];
			$border_spacing = "cellspacing='".$size.$unit.";'";
		endif;
		?>
		<div class="document_library_wrapper <?php echo $cellspacing; ?>"> 
			<table <?php echo $border_spacing; ?>>
				<thead>
					<tr>
						<th>File Name</th>
						<th>File Size</th>
						<th>File Type</th>
						<th>Downloads</th>
					</tr>
				</thead>

				<tbody>
			<?php

			$link_download = 'download';
			if($settings['link_open_new_tab'] == 'yes'):
				$link_download = 'target="_blank"';
			endif;

			if(!empty($settings['add_documents'])):
	            foreach ($settings['add_documents'] as $key => $document):
					
					$url = wp_get_attachment_url($document);

	              	$basename = basename($url);
	                $explodes = explode('.',$basename);
	                $name = $explodes[0];
	                $type = $explodes[1];
	                

	                $bytes = filesize( get_attached_file( $document ) );
					$size = size_format($bytes);
					
	                echo '<tr>';
	                    echo '<td>'.$name.'</td>';
	                    echo '<td>'.$size.'</td>';
	                    echo '<td>'.$type.'</td>';
	                    echo '<td><a href="'.$url.'" '.$link_download.' data-elementor-open-lightbox="no">Download<a></td>';
				endforeach;
                echo '</tr>';
			endif;
			?>
				</tbody>
			</table>
		</div>
		<div style="clear:both;">&nbsp;</div>
		<?php
	}

	public function getDocuments(){

		$media_query = get_posts(
			array(
				'post_type' => 'attachment',
				'post_status' => 'inherit',
				'posts_per_page' => -1,
			)
		);

		$list = [];
		if( !empty($media_query) ):
			foreach ($media_query as $post):
				$url = wp_get_attachment_url($post->ID);
				$name = basename($url); 
				$list[$post->ID] = __( $name, 'elementor-extensions' );
			endforeach;
		endif;

		return $list;
	}
}
