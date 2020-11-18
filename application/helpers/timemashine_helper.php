<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


	function __calcFTI($_from=NULL, $_to=NULL)
	{
	
		$preset_default_points = date("t");
		
		$__td = new DateTime(date("Y-m-d"));
	
		if(!$_from){
			$__from = new DateTime(date("Y-m-1 00:00:01"));
		} else {
			$__from = $_from;
		}
		
		if(!$_to){
			$__to = new DateTime("now");
		} else {
			$__to = $_to;
		}
		
		// interval in days
		$interval = $__from->diff($__to);
	}
	


    /**
     * Return ready dates lent for insert in to template
     *
     * @access public
     * @return page
     */
	function getDateLent($user, $_from=NULL, $_to=NULL, $format = 'html')
	{		
		$preset_default_points = date("t");
		
		$__td = new DateTime(date("Y-m-d"));
	
		if(!$_from){
			$__from = new DateTime(date("Y-m-1 00:00:01"));
		} else {
			$__from = $_from;
		}
		
		if(!$_to){
			$__to = new DateTime("now");
		} else {
			$__to = $_to;
		}
		
		// interval in days
		$interval = $__from->diff($__to);
		
		// Если интервал < 30 то имеем стандартный инттервал для построения в 30 точек
		$__array_lent = array();
		
		//dump($interval->format('%a'),$preset_default_points);
		
		if($interval->format('%a') < $preset_default_points) {
		
			$datetime = new DateTime();
			$datetime = $__from;
			
			for($i=0;$i<$preset_default_points;$i++){
			
				if($__td->format('Y-m-d') == $datetime->format('Y-m-d')){
					$s_today = TRUE;
				} else {
					$s_today = FALSE;
				}
			
				$__array_lent[$datetime->format('Y-m-d H:i:s')] = array(
																			'day'		=> $datetime->format('j'),
																			's_today'	=> $s_today,
																			'uf'		=> systemFormatDateTime($datetime->format('Y-m-j H:i:s')),
																		);
				$datetime->modify('+1 day');
			}
		} else {
			dump('1');
		}
		
		$__array_lent = array_reverse($__array_lent);
		//dump($__array_lent);	
		
		$_ret = NULL;
		if($format = 'html'){
			$_ret = __makeHTMLLent($__array_lent);
		} elseif($format = 'json'){
			$_ret = __makeHTMLLent($__array_lent);
		} else {
			$_ret = __makeHTMLLent($__array_lent);
		}
		
		return $_ret;
	} // end of function 
	
	function __makeHTMLLent($__array_lent)
	{
		$html = '';
		$__td = new DateTime("now");
		
		$simple_day			= 'item';
		$passed_day_event	= 'pevent';
		$future_day_event	= 'fevent';
		$today				= 'today';
		
		foreach($__array_lent as $o=>$day)
		{
			$class = $simple_day;
			
			if($day['s_today']){
				$class .= ' today';
			}
			
			$html .= '<a href="#" title="'.$day['uf'].'" alt="'.$day['uf'].'" class="'.$class.'">'.$day['day'].'</a>';
		} // end of foreach
		
		return $html;
	} // end of function 

	function getNews($user, $_from=NULL, $_to=NULL, $format = 'html')
	{
		$CI =& get_instance();
		
		$ret = array();
		
		$preset_default_points = date("t");
		
		$__td = new DateTime(date("Y-m-d"));
	
		if(!$_from){
			$__from = new DateTime(date("Y-m-1 00:00:01"));
		} else {
			$__from = $_from;
		}
		
		if(!$_to){
			$__to = new DateTime("now");
		} else {
			$__to = $_to;
		}
		
		// interval in days
		//$interval = $__from->diff($__to);
		//dump($__from, $__to, $interval);
		$CI->load->model('News_model');
		
		
		
		// steps
		// 1. get not my news. 
		
		
		
		
		
		
		
		// 2. get my news 
		
		return ;
	}

/* End of file awatar_helper.php */
/* Location: helpers/awatar_helper */