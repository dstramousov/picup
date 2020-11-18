<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('dateHtmlGen'))
{
	/**
	* Get HTML string for column editing.
	*
	* select, multiselect, combobox, multicombobox, radio, multiradio
	* 
	* @access public
	* @input:  $field_data array - tottal column information.
	* @input:  $current_value array - current value for this column.
	* @output: $_ret string - HTML string for input.
	*/
	function dateHtmlGen($field_data, $current_value=NULL)
	{
		$_ret = '';
			
        //$CI =& get_instance();
		$_ret =  '<input class="text" type="text" id="'.$field_data['table'].'_'.$field_data['column'].'" name="'.$field_data['table'].'_'.$field_data['column'].'" value="'.$current_value.'" />';
		
		return $_ret;
		
	} // end of function dateHtmlGen
	
}



if ( ! function_exists('enumHtmlGen'))
{
	/**
	* Get HTML string for column editing.
	*
	* select, multiselect, combobox, multicombobox, radio, multiradio
	* 
	* @access public
	* @input:  $field_data array - tottal column information.
	* @input:  $current_value array - current value for this column.
	* @output: $_ret string - HTML string for input.
	*/
	function enumHtmlGen($table_name, $field_data, $current_value = array())
	{
		$_ret = '';
        $CI =& get_instance();
		
		$selected = CT_EMPTY_STR;
		
		if(isset($field_data['input'])){
			$__input = $field_data['input'];
		} else {
			$__input = 'select';
		}
		
		switch($__input) {
		
			case 'select':
			
				$_ret = '<select id="'.$table_name.'_'.$field_data['name'].'" name="'.$table_name.'_'.$field_data['name'].'" class="right-selectable">';
				
//				$translated_chioser = $CI->lang->getMessage($field_data['value']);
//				if(!$translated_chioser){$translated_chioser = $field_data['value'];}
				
				//$_ret .= '<option disabled>'.$translated_chioser.'</option>'; 
				//dump($field_data);
				foreach($field_data['value'] as $val=>$displayed){

					if($current_value == $val){$selected = "selected";} else {$selected = CT_EMPTY_STR;}
					$_ret .= '<option value="'.$val.'" '.$selected.'>'.$displayed.'</option>';
				}
				$_ret .= '</select>';				
			break;
			
			case 'multiselect':
			break;
			
			case 'combobox':
			break;
			
			case 'multicombobox':
			break;
			
			case 'radio':
			break;
			
			case 'multiradio':
			break;
			
		} // end of switch
		
		return $_ret;
		
	} // end of function enumHtmlGen
}