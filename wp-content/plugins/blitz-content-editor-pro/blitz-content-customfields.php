<?php 
class CEACFGroup{ 

	static $param = '';
	static $value = '';
	static $opr = '==';

	static $ceshortcode_identifier = 'ceacffields';

	public $shortcode = false;


	public $group = array(
						'key' => 'ce_group_', 
						'title' => 'Group',						
						'fields'=>array(),
						'location' => array(
							array(
								array(
									'param' => '',
									'operator' => '==',
									'value' => '',
								),
							),
						),
						'menu_order' => 1,
						'position' => 'normal',
						'style' => 'default',
						'label_placement' => 'bottom',
						'instruction_placement' => 'label',
						'hide_on_screen' => '',
						'active' => 1,
						'description' => '',
						);

		
	/**
	*  Set location of advance custom fields
	*/
	static function setLocation($loc, $val,$opr = '==')
	{
		self::$param = $loc;
		self::$value = $val;
		self::$opr = $opr;
	}
	
		
		
	/**
	* Sets the Shortcode
	*/
	static function setShortCode($sh)
	{
		self::$ceshortcode_identifier = $sh;
	}
	
	
	
	/**
	* Enable shortcodes for custom fields
	*/
	public function enableShortCode($cond=true)
	{
		$this->shortcode = true;
	}


	/**
	* creates tab type custom field
	*/
	static function tab($id, $name, $data=array(), $alignment='top', $logic=0, $end=0)
	{
		$tab = array(
					'key' => 'field_'.$id,
					'label' => $name,
					'name' => '',
					'type' => 'tab',
					//'instructions' => '',
					'required' => 0,
					'conditional_logic' => $logic,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'placement' => $alignment,
					'endpoint' => $end,
				);
		
		array_unshift($data, $tab);
		return $data;
	}


	/**
	* creates field type flexible content
	*/
	static function flexiblecontent($id, $name, $layouts, $data=array(), $alignment='top', $logic=0, $end=0) {
				
		$tab = array(
					'key' => 'field_'.$id,
					'label' => $name,
					'name' => '',
					'type' => 'flexible_content',
					//'instructions' => '',
					'required' => 0,
					'conditional_logic' => $logic,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),					
					'placement' => $alignment,
					'endpoint' => $end,
					'layouts' => $layouts
				);
		
		array_unshift($data, $tab);
		return $data;			
	}



	/**
	* Adds layouts to field type flexible content
	*/
	static function add_flexiblelayout($layout_key, $layout_name, $subfields) {   //FUNCTION TO ADD LAYOUT IN FLEXIBLE CONTENT
		 
		 $data=array();
		 
		 if( $layout_key == 'sitepageBlock') {
			$max = '';
		 } else { 
			$max= 1; 
		 }
		 
		 $layout = array(
						'key' => 'field_'.$layout_key,
						'name' => $layout_key,
						'label' => $layout_name,
						'display' => 'block',
						'sub_fields' => $subfields,
						'max' => $max
		 );
			
		array_unshift($data, $layout);
		return $data;
		
	}
	
	

	/**
	* add custom fields
	*/
	public function add($field, $label='label', $type='text', $extra = array(), $subfields,$post_id)
	{
		switch($type)
		{
			default:
			case 'text':
			$others  = array(//'instructions' => '',
							'required' => 0,
							//'conditional_logic' => 0,
							'wrapper' => array(
								'width' => '',
								'class' => '',
								'id' => '',
							),
							
							
							'maxlength' => '');

			break;

			case 'image':
			
			$others = array(					
					'instructions' => '',
					'required' => 0,
					//'conditional_logic' =>0,
					'return_format' => 'url',
					'preview_size' => 'thumbnail',
					'library' => 'all',					
				);

			break;
			
		}
		
		$others = array_merge_recursive($others, $extra);

		if (!empty($subfields))
		{
			$others['sub_fields'] = $subfields;
			$others['layout'] = 'block';
		}
		
		if (isset($others['width']))
		{
			$others['wrapper']['width'] = $others['width'];
			unset($others['width']);	
		}

		if (isset($others['width']))
		{
			$others['wrapper']['class'] = $others['class'];
			unset($others['class']);	
		}

		if (isset($others['width']))
		{
			$others['wrapper']['id'] = $others['id'];
			unset($others['id']);	
		}

		if (isset($others['default']))
		{
			if($others['default'] == '_old_')
			{
				$cls = $others['old'];
				$option = $cls->get_option($field);
				$optval =  (is_array($option)) ? $option[ $others['old_val']] : $option;
				$others['default_value'] = $optval;				
			}
			else
			{
				$others['default_value'] = $others['default'];	
			}


			unset($others['default']);
		}

		if (isset($others['old']))
		{
			$cls = $others['old'];
			$option = $cls->get_option($field);



			$optval =  (is_array($option)) ? $option[ $others['old_val']] : $option;

			$others['instructions'] = 'default: ' . $optval;
			unset($others['old']);	
		}


		if (isset($others['show_on']))
		{

			$others['conditional_logic'] = array(
				array(
					array(
						'field' => 'field_' . $others['show_on'],
						'operator' => '==',
						'value' => '1',
					),
				),
			);

		}

		if (isset($others['hide_on']))
		{

			$others['conditional_logic'] = array(
				array(
					array(
						'field' => 'field_' . $others['hide_on'],
						'operator' => '==',
						'value' => '0',
					),
				),
			);

		}
		$fieldmeta = array(	'key' => 'field_'.$field,
							'label' => $label,
							'name' => $field,
							'type' => $type,
						);

		$fieldmeta = array_merge_recursive($fieldmeta,$others);

		if ($this->shortcode && !isset($extra['no_shortcode']))
		{
			if($fieldmeta['type'] != 'repeater') {
			$fieldmeta['instructions'] = '<small>['. ( CEACFGroup::$ceshortcode_identifier) .' type='.$fieldmeta['name'].' item=' .$post_id.']</small>';
			}
		}

		$this->group['fields'][] = $fieldmeta;
	}



	/**
	* Adds true false custom field
	*/
	public function addTrueFalse($field, $label='label',$name='name', $type='text', $extra = array(), $subfields,$post_id)
	{
		switch($type)
		{
			default:
			case 'text':
			$others  = array(//'instructions' => '',
							'required' => 0,
							//'conditional_logic' => 0,
							'wrapper' => array(
								'width' => '',
								'class' => '',
								'id' => '',
							),
							
							
							'maxlength' => '');

			break;

			case 'image':
			
			$others = array(					
					'instructions' => '',
					'required' => 0,
					//'conditional_logic' =>0,
					'return_format' => 'url',
					'preview_size' => 'thumbnail',
					'library' => 'all',					
				);

			break;
			
		}
		
		$others = array_merge_recursive($others, $extra);
		
		if (!empty($subfields))
		{
			$others['sub_fields'] = $subfields;
			$others['layout'] = 'block';
		}
		
		if (isset($others['width']))
		{
			$others['wrapper']['width'] = $others['width'];
			unset($others['width']);	
		}

		if (isset($others['width']))
		{
			$others['wrapper']['class'] = $others['class'];
			unset($others['class']);	
		}

		if (isset($others['width']))
		{
			$others['wrapper']['id'] = $others['id'];
			unset($others['id']);	
		}

		if (isset($others['default']))
		{
			if($others['default'] == '_old_')
			{
				$cls = $others['old'];
				$option = $cls->get_option($field);
				$optval =  (is_array($option)) ? $option[ $others['old_val']] : $option;
				$others['default_value'] = $optval;				
			}
			else
			{
				$others['default_value'] = $others['default'];	
			}


			unset($others['default']);
		}

		if (isset($others['old']))
		{
			$cls = $others['old'];
			$option = $cls->get_option($field);



			$optval =  (is_array($option)) ? $option[ $others['old_val']] : $option;

			$others['instructions'] = 'default: ' . $optval;
			unset($others['old']);	
		}


		if (isset($others['show_on']))
		{

			$others['conditional_logic'] = array(
				array(
					array(
						'field' => 'field_' . $others['show_on'],
						'operator' => '==',
						'value' => '1',
					),
				),
			);

		}

		if (isset($others['hide_on']))
		{

			$others['conditional_logic'] = array(
				array(
					array(
						'field' => 'field_' . $others['hide_on'],
						'operator' => '==',
						'value' => '0',
					),
				),
			);

		}
		$fieldmeta = array(	'key' => 'field_'.$field,
							'label' => $label,
							'name' => $name,
							'type' => $type,
						);

		$fieldmeta = array_merge_recursive($fieldmeta,$others);

		if ($this->shortcode && !isset($extra['no_shortcode']))
		{
			if($fieldmeta['type'] != 'repeater') {
			$fieldmeta['instructions'] = 'Check to enable page';
			}
		}

		$this->group['fields'][] = $fieldmeta;
	}



	/**
	* creates field type flexible content
	*/
	public function add_field($field)
	{
		$this->group['fields'][] = $field;
	}



	/**
	* call to function
	*/
	public function __call($fn,$args)
	{
		if ($fn == 'location')
		{
			$this->group['location'][0][0]['param'] = $args[0];
			$this->group['location'][0][0]['value'] = $args[1];			
		}
		elseif ($fn == 'key')
		{
			$this->group['key'] = 	$this->group['key'] . $args[0];
		}
		else
		{
			$this->group[$fn] = $args[0];
		}

		return $this;
		
	}	



	/**
	* get field group
	*/
	public function get($style='default')
	{

		$this->group['style'] = $style;

		if ($this->group['location'][0][0]['param'] == '' && (CEACFGroup::$param != ''))
		{
			$this->group['location'][0][0]['param'] = CEACFGroup::$param;
			$this->group['location'][0][0]['value'] = CEACFGroup::$value;
			$this->group['location'][0][0]['operator'] = CEACFGroup::$opr;
		}

		//print_r($this->group);

		return $this->group;
	}

}

