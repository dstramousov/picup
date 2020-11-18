<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


/**
* Get approximatly coordinates by user IP address
*
* @access public
*/
function getUserApproxCoordByIP($ip = NULL)
{
	$CI =& get_instance();
	$_ret = array('lat'=> $CI->config->item('default_longitude'), 'lon'=>$CI->config->item('default_latitude'));
	
	if(!$ip){
		$ip = get_user_ip();
	}
	
	if($ip){
		if($ip == '127.0.0.1'){
			// 
			return $_ret;
		}
		
		//$_ret
	}
	
	return $_ret;
} // end of function 

/**
* Get users IP
*
* @access public
*/
function get_user_ip()
{
	if ( getenv('REMOTE_ADDR') ) $user_ip = getenv('REMOTE_ADDR');
	elseif ( getenv('HTTP_FORWARDED_FOR') ) $user_ip = getenv('HTTP_FORWARDED_FOR');
	elseif ( getenv('HTTP_X_FORWARDED_FOR') ) $user_ip = getenv('HTTP_X_FORWARDED_FOR');
	elseif ( getenv('HTTP_X_COMING_FROM') ) $user_ip = getenv('HTTP_X_COMING_FROM');
	elseif ( getenv('HTTP_VIA') ) $user_ip = getenv('HTTP_VIA');
	elseif ( getenv('HTTP_XROXY_CONNECTION') ) $user_ip = getenv('HTTP_XROXY_CONNECTION');
	elseif ( getenv('HTTP_CLIENT_IP') ) $user_ip = getenv('HTTP_CLIENT_IP');
	$user_ip = trim($user_ip);
	if ( empty($user_ip) ) return false;
	if ( !preg_match("/^([1-9]|[1-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-5])(\.([0-9]|[1-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-5])){3}$/", $user_ip) ) return false;
	return $user_ip;
} // end of function 


// wgs84 vs nad83


		/*
		В.
			Например, Москва, Минская, 13 имеет координаты (55,74087 - 37,48125)
			Но на карте эти координаты представлены широтой и долготой, как: 55°44' 27.13'' - 37°28' 52.41''
			Какую формулу нужно использовать, чтобы перевести координаты WGS-84 (55,74087 - 37,48125) в стандартную широту и долготу (55°44' 27.13'' - 37°28' 52.41'') ?
			
		О.
			55,74087 - это будет 55 градусов. Далее нужно 0,74087*60=44,4522 минут - целая часть это минуты. Затем для получения секунд нужно 0,4522*60 секунд=27,132 секунд.
			В результате получили 55 градусов 44 минуты 27,132 секунды.
		*/

function converWGS2NAD($__lo, $__la, $_output_type = 'str')
{
	//	N 46° 54' 17'' E 37° 20' 47''
	//	46.90497 / 37.3466
	//dump($__lo, $__la);
	
	$_ret = array(
					'N'=>array(), 
					'E'=>array(),
				  );
		

	// start la processing  ///////////////////////////////////////////////
	$int_part		= floor($__la);
	$fract_part		= round(($__la - floor($__la)), 3);
	
	// градусы
	$__n_grad = $int_part;
	
	// минуты
	if($fract_part == 0){
		$__n_min = 0;
		$__n_sec = 0;
	} else {
		$_tmp = $fract_part*60;
		$int_part_2		= floor($_tmp);
		$fract_part_2	= round(($_tmp - floor($_tmp)), 3);
		
		$__n_min = $int_part_2;
		
		if($fract_part_2 == 0){
			$__n_sec = 0;
		}  else {
			$__n_sec = $fract_part_2*60;
		}
	} // end of la processing  ////////////////////////////////////////////////
	
	// start lo processing  ///////////////////////////////////////////////
	$int_part		= floor($__lo);
	$fract_part		= round(($__lo - floor($__lo)), 3);
	
	// градусы
	$__e_grad = $int_part;
	
	// минуты
	if($fract_part == 0){
		$__e_min = 0;
		$__e_sec = 0;
	} else {
		$_tmp = $fract_part*60;
		$int_part_2		= floor($_tmp);
		$fract_part_2	= round(($_tmp - floor($_tmp)), 3);
		
		$__e_min = $int_part_2;
		
		if($fract_part_2 == 0){
			$__e_sec = 0;
		}  else {
			$__e_sec = $fract_part_2*60;
		}
	} // end of lo processing  ////////////////////////////////////////////////
	
	if($_output_type == 'arr'){
		$_ret['N'] = array('H'=>$__n_grad, 'M'=>$__n_min, 'S'=>$__n_sec);
		$_ret['E'] = array('H'=>$__e_grad, 'M'=>$__e_min, 'S'=>$__e_sec);
	} else {
		$_ret['N'] = $__n_grad.'° '.$__n_min.'\' '.$__n_sec.'"';
		$_ret['E'] = $__e_grad.'° '.$__e_min.'\' '.$__e_sec.'"';;
	}
	
	return $_ret;
}


function dont_touch_the_precision($val, $pre = 0) {
    $val = (string) $val;
    if (strpos($val, ".") !== false) {
        $tmp = explode(".", $val);
        $val = $tmp[0] .".". substr($tmp[1], 0, $pre);
    }
    return (float) $val;
} 


function getCurrentPosition()
{
	
}


/* End of file awatar_helper.php */
/* Location: helpers/awatar_helper */