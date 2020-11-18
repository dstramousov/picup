<?php

/**
 * application\controllers\Admin.php
 *
 * Admin controller for manipulation with databse.
 *
 */

class Admin extends UP_Controller {

	/**
	 * Array with models list. 
	 */
	var $models_list = array('product');

    function __construct()
    {
		$this->app_mode = 'admin';

		parent::__construct();

		$this->_create_current_user();

		//$this->load->helper('admin_menu');
		//$this->yw_templater->assign('admin_menu', getAdminMenu());
		$this->_addcommonlogic();

		log_message('debug', 'Admin controller has initialized.');
	}
	
	function _addcommonlogic(){
		$this->_addAdditionalCSS('
								<link href="'.base_url().'css/admin_menu.css"  rel="stylesheet" type="text/css" />
								<link href="'.base_url().'css/admin.css"  rel="stylesheet" type="text/css" />
								 ');
		$this->_addAdditionalJS(
								'<script type="text/javascript" src="'.base_url().'js/stuHover.js"></script>
								<script type="text/javascript" src="'.base_url().'js/right/right.js"></script>
								');
	}

	/**
	 * Try to make HTTP authorize logic.
     *
     * @access private
     * @return NULL.
	 */
    function _create_current_user() {
        $this->authorize();
    }// end of function	
	
	
	/**
	 * Add new coordinates by using Google API
     *
     * @access public
     * @return NULL.
	 */
    function gpsAddFormApi()
	{	
		$CI =& get_instance();
		
		$this->up_jsmanager->addIncludes(base_url().'js/jquery.min.js');
		$this->up_jsmanager->addIncludes('http://maps.google.com/maps?file=api&amp;v='.$this->config->item('google_map_ver').'&amp;key='.$this->config->item('google_api_key'));
		
		$this->up_jsmanager->addComponent('initialize();');

		// prepare global card for showing 
		$this->up_templater->assign('current_position', $this->config->item('default_latitude').','.$this->config->item('default_longitude'));
		
		// get select with placement type
		$this->load->model('Placetype_model');
		
		$string = str_replace('"', '\"', form_dropdown('placetype', $this->Placetype_model->getAllPairs(), 'P.PPL'));
		$this->up_templater->assign('placement_type', $string);
		
		$_block = $this->up_templater->setBlock('admin/coordinates/add_coordinates_api');
		
		$this->up_templater->assign('content', $_block);
		$this->up_templater->render('admin/main_page');
    } // end of function
	
	
	function _utf8_urldecode($str)
	{
		$str = preg_replace("/%u([0-9a-f]{3,4})/i","&#x\\1;",urldecode($str));
		return html_entity_decode($str,null,'UTF-8');;
	} // end of function
	
	/**
	 * Add new place and coordinates logic
     *
     * @access admin
	 */
#	function savePlace($name_place, $type_place, $lat_place, $lng_place)
	function savePlace($name_place)
	{		
		$__NAME = $this->_utf8_urldecode($_GET['name']);
		
		// 1. check input data.		
		$name_place	= $__NAME ;
		$type_place	= $_GET['type'];
		$lat_place	= $_GET['lat'];
		$lng_place	= $_GET['lng'];
	
		$now = new DateTime("now");
		
		$_USER_ID = 1;
		$__date = $now->format('Y-m-d H:i:s');
		
//		$this->load->model('Coordinates_model');
//		$this->load->model('Place_model');
		// 2. insert coordinates.
		$_i_data = array(
			'created'			=> $__date,
			'trusted'			=> 'yes',
			'user_id'			=> $_USER_ID,
			'latitude'			=> $lat_place,
			'longitude'			=> $lng_place,
			'altitude'			=> 0,
			'maxapproximation'	=> 10,
		);
		$this->db->insert('coordinates', $_i_data);
		$_COORD_ID = $this->db->insert_id();
		
		// 3. insert place.
		$data = array(
					'created'       		=> $__date,
					'coord_id'       		=> $_COORD_ID,
					'user_id'				=> $_USER_ID,
					'placetype_code'		=> $type_place,
					'geonameid'				=> 1111,
					'name'					=> $this->db->escape_str($__NAME),
					'asciiname'				=> $this->db->escape_str($__NAME),
					'alternatenames'		=> $this->db->escape_str($__NAME),
//					'country_code'			=> ,
//					'cc2'					=> $this->db->escape_str($arr[9]),
//					'admin1_code'			=> $this->db->escape_str($arr[10]),
//					'admin2_code'			=> $this->db->escape_str($arr[11]),
//					'admin3_code'			=> $this->db->escape_str($arr[12]),
//					'admin4_code'			=> $this->db->escape_str($arr[13]),
//					'population'			=> $this->db->escape_str($arr[14]),
//					'elevation'				=> $this->db->escape_str($arr[16]),
//					'dem'					=> $this->db->escape_str($arr[16]),
//					'timezone'				=> $this->db->escape_str($arr[17]),
		);
		
		$this->db->insert('place', $data);
	} // end of function
	
	
	
	
	/**
	 * Main action for Admin part.
     *
     * @access public
	 */
	function _parse_add_params($_parse_add_params)
	{
		$_ret = array();
		$_params = preg_split('/&/', $_parse_add_params);
		
		foreach($_params as $p){
			$_val = preg_split('/=/', $p);			
			if(count($_val) > 1){
				$_ret[$_val[0]] = $_val[1];
			}
		}
		return $_ret;
	}// end of function	
	
	/**
	 * Main action for Admin part.
     *
     * @access public
	 */
	function mainEditor($modelname=null, $id=null)
	{
		$__m_name = $modelname.'_model';
		
		if(file_exists(APPPATH.'models/'.$__m_name.".php")){
			$this->load->model($modelname.'_model');
		} else {	
			redirect(base_url().'admin');
		}
				
		$this->up_templater->assign('current_model', $modelname);
		
		if($id){ $this->{$__m_name}->fetchByID($id); }
		
		$_h		= $this->{$__m_name}->write_form();
		$__h	= array();
		
		// need assign in to form only _input keys
		foreach($_h as $k=>$v)
		{
			$pattern = '/_input$/';
			preg_match($pattern, $k, $matches);
			if($matches){
				$__h[$k] = $v;
			}
		}
		
		$this->up_templater->assign('obj', $__h);
		$this->up_templater->assign('obj_js', $_h['js_date_logic']);
		
		$_block = $this->up_templater->setBlock('admin/'.$modelname.'/edit.tpl');
		$this->up_templater->assign('content', $_block);		
		$this->up_templater->render('admin/main_page');		
	} // end of function 
	
	
	/**
	 * Main action for Admin part.
     *
     * @access public
	 */
	function mainViewer($modelname=null, $add_params=null)
	{	
		if(!$modelname){
			redirect(base_url().'admin');
		}
		
		$__add_params = NULL;
		if($add_params){
			$__add_params = $this->_parse_add_params($add_params);
		} 
		//echo print_r($__add_params);
		
		$__m_name = $modelname.'_model';
		
		if(file_exists(APPPATH.'models/'.$__m_name.".php")){
			$this->load->model($modelname.'_model');
		} else {
			redirect(base_url().'admin');
		}
				
		// tech info		
		$this->up_templater->assign('current_model', $modelname);
		
		if(isset($__add_params['order_by'])){
			$order_by = $__add_params['order_by'];
		} else {
			$order_by = $this->{$__m_name}->default_order_by;
		}
		$this->printCollectionObjects($this->{$__m_name}, "", "1", $order_by, $__add_params);

		$_block = $this->up_templater->setBlock('admin/several_viewer');
		$this->up_templater->assign('content', $_block);
		
		$this->up_templater->render('admin/main_page');
	} // end of function
	

	/**
	 * Main action for Admin part.
     *
     * @access public
	 */
	function index()
	{		
		//$this->up_templater->assign('comments', $comment_block);
		$this->up_templater->render('admin/main_page');
	} // end of function 
	
	
	function showCoordinatesInToMap($_lo, $_la)
	{
		$_POST['latitude'] = $_la;
		$_POST['longitude'] = $_lo;
		$this->googleMapApiSearch();
	} // end of function 
	
	
	function googleMapApiSearch()
	{
		$data = array();
		
		if(isset($_POST['addresssearch'])){
			$data['addresssearch'] = trim($_POST['addresssearch']);			
			$this->up_templater->assign('val_addresssearch', $data['addresssearch']);
		}
		
		if($_POST['latitude']){
			$data['latitude'] = trim($_POST['latitude']);
			$this->up_templater->assign('val_latitude', $data['latitude']);
		}
		
		if($_POST['longitude']){
			$data['longitude'] = trim($_POST['longitude']);
			$this->up_templater->assign('val_longitude', $data['longitude']);			
		}
		
		$this->googleMapApi($data);
	} // end of function 
	
	
	/**
	 * Show all our places on the wold map with sorting by type
     *
     * @access public
	 */
	function userMapCheckout()
	{
		$CI =& get_instance();
		
		$this->up_jsmanager->addIncludes(base_url().'js/jq.js');
		$this->up_jsmanager->addIncludes('http://maps.google.com/maps?file=api&amp;v='.$this->config->item('google_map_ver').'&amp;key='.$this->config->item('google_api_key'));
		
		// prepare global card for showing 
		//$this->config->item('default_latitude').','.$this->config->item('default_longitude');
		// 49.55372551347579, 31.1572265625  center of Ukraine
		$this->up_templater->assign('current_position', '49.55372551347579, 31.1572265625');
		
		// get select with placement type
		$this->load->model('Placetype_model');
		
		$pairs = array(
						24		=> 'За сегодня',
						3*24	=> 'За 3 дня',
						7*24	=> 'За неделю',
						30*24	=> 'За месяц',
						90*24	=> 'За 3 месяца',
						0	=> 'Все',
					   );
		$out = '';
		foreach($pairs as $code => $value){
			$out .= '<li style="padding:0px;margin:0px;list-style:none;"><label for="'.$code.'"><input id="'.$code.'hbox" type="checkbox" value="'.$code.'h" />'.$value.'</label></li>';
		} 
		$this->up_templater->assign('types', $out);
		
		$_block = $this->up_templater->setBlock('admin/user/show_all_by_type');
		
		$this->up_templater->assign('content', $_block);		
		$this->up_templater->render('admin/main_page');
		
	} // end of function
	
	
	/**
	 * Show all our places on the wold map with sorting by type
     *
     * @access public
	 */
	function userMapCheckoutByInterval($count_hours)
	{
	
		$_count_hours = substr($count_hours, 0, -1);
	
		if(!is_numeric($_count_hours)){
			echo '{"status": "false"}';
		}
				
		$user = new User_model;
		
		$query	= $user->get_select_query();
		if($_count_hours != '0'){
			$query->expand(array(
				'where'		=> $user->table_name.'.last_checkin BETWEEN '.$this->db->escape(changeNowByHour($_count_hours)).' AND '. $this->db->escape(now()),
			) );
		} // end if 
		
		$res = $this->db->query($query->str());
		$markers = array();		
		$_arr = $res->result_array();
		if($_arr)
		{		
			$this->load->model('Place_model');
			$place = new Place_model;
			foreach($_arr as $par)
			{
				$query_pl	= $place->get_select_query();
			
				if(!$par['last_coordinate_id']) continue;
								
				$query_pl->expand(array(
					'where'		=> $place->table_name.'.coord_id = '.$par['last_coordinate_id'],
					'limit'		=> 1,
				) );
				$res_pl = $this->db->query($query_pl->str());
				$_arr_pl = $res_pl->row();
				if(!isset($_arr_pl->id)){
					dump($_arr_pl);
				}
//				dump($_arr_pl);
				
				array_push($markers, array(
											'mname'		=> $par['nickname'],
											'name'		=> $par['first_name']. ' '. $par['last_name'],
											'email'		=>  $par['email'],
											'created'		=> systemFormatDateTime($par['created'], FALSE),
											'lastcheckin'	=> systemFormatDateTime($par['last_checkin'], FALSE),
											'lastlogin'	=> systemFormatDateTime($par['last_login'], FALSE),											
											'type'		=> $count_hours, 
											'last_ip'	=> $par['last_ip'],
											'lat'		=> $par['coordinates_latitude'],
											'lon'		=> $par['coordinates_longitude'],
											'avatar' 	=> base_url().$this->config->item('preset_avatar_folder').'/'.$par['avatar'],
											'userhref'	=> base_url().'admin/edit/user/'.$par['id'],
											'placeref'	=> base_url().'admin/edit/place/'.$_arr_pl->id,
											'pname'		=> $_arr_pl->name,
											'ptype'		=> $_arr_pl->placetype_name, 
										)
							);
			} // end of foreach
			
			$ret['markers']	= $markers;
			$ret['mimg']	= "award_star_gold_1.png";
			$ret['status']	= 'OK';

			echo json_encode($ret);
 
		} else {
			echo '{"status": "false"}';
		}
	} // end of function 
	
	
	/**
	 * Show all our places on the wold map with sorting by type
     *
     * @access public
	 */
	function addPlaceFromMap(){
	
		$CI =& get_instance();
		
		$this->up_jsmanager->addIncludes(base_url().'js/jquery.min.js');
		$this->up_jsmanager->addIncludes('http://maps.google.com/maps?file=api&amp;v='.$this->config->item('google_map_ver').'&amp;key='.$this->config->item('google_api_key'));
		
		// prepare global card for showing 
		$__init_coords = getUserApproxCoordByIP();
		$this->up_templater->assign('current_position', $__init_coords['lon'].', '.$__init_coords['lat']);
		$this->up_templater->assign('current_zoom', $this->config->item('default_zoomlevel_found'));

		$this->load->model('Placetype_model');
		$string = str_replace('"', '\"', form_dropdown('placetype', $this->Placetype_model->getAllPairs(FALSE), 'P.PPL'));
		$this->up_templater->assign('placement_type', $string);
		
		
		$_block = $this->up_templater->setBlock('admin/places/add_place_by_map');
		
		$this->up_templater->assign('content', $_block);		
		$this->up_templater->render('admin/main_page');				
	
		// this assigning for EDIT mode
		/*
		if($mode){
			$this->up_templater->assign('listener_block', $this->up_templater->setBlock('admin/places/addlistener'));
			$this->up_templater->assign('draggable_opt', '');			
		}
	
		$this->ShowPlacesOnTheMap(TRUE);
		*/
	}
	
	/**
	 * Show all our news on the wold map with sorting checkboxes and radiuses
     * 
     * @access public
	 */
	function getNewsInMap()
	{
		$CI =& get_instance();
		
		$this->up_jsmanager->addIncludes(base_url().'js/jquery.min.js');
		$this->up_jsmanager->addIncludes('http://maps.google.com/maps?file=api&amp;v='.$this->config->item('google_map_ver').'&amp;key='.$this->config->item('google_api_key'));
		
		// prepare global card for showing 
		$__init_coords = getUserApproxCoordByIP();
		$this->up_templater->assign('current_position', $__init_coords['lon'].', '.$__init_coords['lat']);
		$this->up_templater->assign('current_zoom', $this->config->item('default_zoomlevel_found'));
		
		// get select with placement type
		$this->load->model('News_model');
		
		$stat_arr = $this->News_model->getStatistic();
		$this->up_templater->assign('stat_array', $stat_arr);
		
		$out = '';
		//foreach($pairs as $code => $value){
			//$out .= '<li style="padding:0px;margin:0px;list-style:none;"><label for="'.$code.'"><input id="'.$code.'box" type="checkbox" value="'.$code.'" />'.$value.'</label></li>';
		//} 
		//$this->up_templater->assign('types', $out);
		
		$_block = $this->up_templater->setBlock('admin/news/show_all_by_filter');
		
		$this->up_templater->assign('content', $_block);
		$this->up_templater->render('admin/main_page');						
	} // end of function 
	
	/**
	 * Show all our places on the wold map with sorting by type
     * 
     * @access public
	 */
	function ShowPlacesOnTheMap()
	{
		$CI =& get_instance();
		
		$this->up_jsmanager->addIncludes(base_url().'js/jquery.min.js');
		$this->up_jsmanager->addIncludes('http://maps.google.com/maps?file=api&amp;v='.$this->config->item('google_map_ver').'&amp;key='.$this->config->item('google_api_key'));
		
		// prepare global card for showing 
		$__init_coords = getUserApproxCoordByIP();
		$this->up_templater->assign('current_position', $__init_coords['lon'].', '.$__init_coords['lat']);
		$this->up_templater->assign('current_zoom', $this->config->item('default_zoomlevel_found'));
		
		// get select with placement type
		$this->load->model('Placetype_model');
		
		$pairs = $this->Placetype_model->getAllPairs();
		
		$out = '';
		foreach($pairs as $code => $value){
			$out .= '<li style="padding:0px;margin:0px;list-style:none;"><label for="'.$code.'"><input id="'.$code.'box" type="checkbox" value="'.$code.'" />'.$value.'</label></li>';
		} 
		$this->up_templater->assign('types', $out);
		
		$_block = $this->up_templater->setBlock('admin/places/show_all_by_type');
		
		$this->up_templater->assign('content', $_block);		
		$this->up_templater->render('admin/main_page');				
	} // end of function 
	
	/**
	 * Get needed places and return markers by AJAX
     *
     * @access public
	 */
	function getPlacesByType($type)
	{	
		$ret = array();
		$this->load->model('Place_model');
		
		$places_info = $this->Place_model->fetchPlacesByType($type);
		
		$markers = array();
		if($places_info)
		{
			foreach($places_info as $par)
			{
				array_push($markers, array(
											'mname'		=> $par['name'],
											'created'	=> systemFormatDateTime($par['created'], FALSE),
											'address'	=> 'dimas',
											'user'		=> $par['user_nickname'],
											'type'		=> $type, 
											'lat'		=> $par['coordinates_latitude'],
											'lon'		=> $par['coordinates_longitude'],
										)
							);
			} // end of foreach
			
			$ret['markers']	= $markers;
			$ret['mimg']	= "mm_20_red.png";
			$ret['status']	= 'OK';

			echo json_encode($ret);
 
		} else {
			echo '{"status": "false"}';
		}
	} // end of function
	
	/**
	 * Goole map api.
     *
     * @access public
	 */
	function googleMapApi($data = NULL)
	{
	
//		dump($data);
		$this->load->library('GoogleMapAPI');
								
		$map = new GoogleMapAPI('map');
		$map->setAPIKey('AIzaSyBGw5s0tNhuv4dXc3lP0PD19bkqkWtOaJE');
		$map->setWidth('1100px');
		$map->setHeight('500px');
		$map->setControlSize('small');
		$map->setMapType('satellite');
		$map->setZoomLevel(12);
//		$map->setZoomLevel($this->config->item('default_zoomlevel_found'));
		/*
		$map->addPolyLineByCoords(-96.67,40.8279,-16.7095,42.8149,'#eeeeee',5,50);
		
$map->addPolyLineByAddress(
            '3457 Holdrege St Lincoln NE 68502',
            'Донецк','#eeeeee',5,50);		
			
			*/

		if($data)
		{
			if(isset($data['addresssearch'])){
				$map->addMarkerByAddress($data['addresssearch'],$data['addresssearch'],$data['addresssearch']);
				
				$__tmp = $map->getGeocode($data['addresssearch']);
				$this->up_templater->assign('val_latitude', $__tmp['lat']);
				$this->up_templater->assign('val_longitude', $__tmp['lon']);			
			}
			
			if(isset($data['latitude']) && isset($data['longitude'])) {
				$map->addMarkerByCoords($data['longitude'], $data['latitude'], 'Поиск по координатам');
				//dump($map);

				/*
				$this->load->library('googlePlaces');
					
				$apiKey = 'AIzaSyBGw5s0tNhuv4dXc3lP0PD19bkqkWtOaJE';
				$googlePlaces = new googlePlaces($apiKey);

				// Set the longitude and the latitude of the location you want to search the surronds of
				$latitude = '48';
				$longitude = '37';
				$googlePlaces->setLocation($latitude . ',' . $longitude);


				$googlePlaces->setRadius(500);
				$googlePlaces->setLanguage('ru');
				$results = $googlePlaces->search();
				//dump($results);
				*/
			}
						
		} else {
		
			$__lo = $this->config->item('default_longitude');
			$__la = $this->config->item('default_latitude');
		
			$map->addMarkerByCoords($__lo, $__la, 'Вы находитесь тут');
		}
		
		$_block = $this->up_templater->setBlock('admin/searchplace');
		$this->up_templater->assign('content', $_block);
				
		$this->up_templater->assign('map', $map);
		$this->up_templater->render('admin/googleapi');
	} // end of function
	
	
	/**
	 * Find and add google place.
     *
     * @access public
	 */
	function gpsAddForm($data = NULL)
	{
		
		$_block = $this->up_templater->setBlock('admin/searchplace');
		$this->up_templater->assign('content', $_block);
		
		$this->up_templater->render('admin/main_page');
	} // end of function 
	
	
	/**
	 * Find and add google place.
     *
     * @access public
	 */
	function gpsAddFormSubmit($data = NULL)
	{
		
		
	} // end of function 
	
	
	
}

/* End of file Admin.php */
/* Location: application/controllers/Admin.php */