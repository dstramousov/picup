<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


function getAwatarsArray($_mode="system")
{
	$CI =& get_instance();
	if($_mode == "system"){
		$folder_name = $CI->config->item('preset_avatar_folder');
	} else {
		$folder_name = $CI->config->item('users_avatar_folder');
	}
	$CI->load->helper('file');
	$tmp = get_dir_file_info($folder_name);
	return get_dir_file_info($folder_name);
}

/* End of file awatar_helper.php */
/* Location: helpers/awatar_helper */