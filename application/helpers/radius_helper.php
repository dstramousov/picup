<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


function getUsersRadius($_user=NULL)
{
	$CI =& get_instance();
	
	$min = $CI->config->item('karma_min_value');
	$max = $CI->config->item('karma_max_value');
	
	$__value = $min;

	if(is_object($_user)){
		dump(1);
	} else {
		$__tmp = (int)$_user;
		if(is_int($__tmp)){
			$CI->load->model('Karma_model');
			$row = $CI->Karma_model->getByUserID($__tmp);
			if($row){
				$__value = $row->value;
			}
		}
	}
	
	$__value = __post_process($__value);
	return $__value;
} // end of function 


function __post_process($__value)
{	
	return $__value;
}

/* End of file awatar_helper.php */
/* Location: helpers/awatar_helper */