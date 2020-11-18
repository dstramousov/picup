<?php if (!defined('BASEPATH')) exit('Base path is not defined');

//require "YW_AJAX_Controller.php";

//class YW_Controller extends Controller {
class UP_Controller extends CI_Controller {

    /**
     * Current user who worked with application.
     */
    var $user;

    /**
     * Current mode of application run: {user/admin/setup}.
     */
    var $app_mode;

    /**
     * Array for containt browser title information.
     */
    var $browser_title = array();

    /**
     * Current language.
     */
    var $cyr_lang;

    function __construct()
    {
		parent::__construct();
		
		if($this->config->item('tech_info') == 'on'){
			$this->benchmark->mark('code_start');
		}

        $this->db->query("SET NAMES ".$this->config->item("mysql_names")."");
		$this->db->query("SET character_set_client='".$this->config->item("mysql_character_set_client")."'");
		$this->db->query("SET character_set_results='".$this->config->item("mysql_character_set_results")."'");
		$this->db->query("SET collation_connection='".$this->config->item("mysql_names_collation_connection")."'");
		$this->user = new User_model();		
        
		if($this->app_mode == 'main'){
			$this->_read_user_session();
			//$_count = $this->Photo_model->getTodayUploadPictures();			
			//$this->up_templater->assign('today_uploaded_photo_c', $_count);
			//$this->up_templater->assign('today_uploaded_photo_w', endingsForm($_count, 'изображение', 'изображения', 'изображений'));
			
			//$_count = $this->Photo_model->getTotalUploadPictures();
			//$this->up_templater->assign('total_uploaded_photo_c', $_count);
			//$this->up_templater->assign('total_uploaded_photo_w', endingsForm($_count, 'изображение', 'изображения', 'изображений'));
		}
        

		// fill html header information //////
        $this->_print_header_information();
		$this->up_templater->assign('back_url', uri_string());
		
		// check mess 
		if($this->session->userdata('msg')){
            $__t = $this->session->userdata('msg');
			$this->up_templater->assign('msg', '$.alert("'.$__t['body'].'", {type:\''.$__t['type'].'\', showtime:'.$__t['showtime'].'});');
			$this->session->unset_userdata('msg');
		}

		log_message('debug', 'UP_Controller class initialized');
    } // end of function 
	
    /**
     * Initialize pager.
     */
	function __initPaging($__per_page, $__cur_page, $table_name, $total_record)
	{		
		$this->load->library('pagination');
		
		// init top selector part 
		$_arr_selector = $this->config->item('rows_set_val');
		$this->up_templater->assign('rows_set_val', array_keys($_arr_selector));
		$this->up_templater->assign('rows_set_out', array_values($_arr_selector));
		
		// curent value per page
		$this->up_templater->assign('rows', $__per_page);
		
		$config['base_url']		= base_url().'admin/view/'.$table_name;
		$config['total_rows']	= $total_record;
		$config['per_page']		= $__per_page;
		$config['cur_page']		= $__cur_page;
//		if($this->cur_url != ''){
//			$config['url_addons']	= $this->cur_url;
//		}
				
		$config['first_link']	= 'В начало';
		$config['last_link']	= 'В конец';
				
		$config['num_links']	= $this->config->item('count_arround_links_in_to_pager');

		$this->pagination->initialize($config);
		
		$this->up_templater->assign('nav_str', $this->pagination->create_links());
	} // end of function 
	
	function printCollectionObjects($obj, $aspect, $where_str, $default_order_by = NULL, $__add_params = NULL)
	{
    	$_where	= $where_str;
        $name	= $obj->class_name;
		
		// begin !!!!!!!!!!!!!!!!!!!!!
        $query	= $obj->get_select_query();		
        $query->expand(array(
            'where' => $where_str,
        ));

        // Read filtering (WHERE) and ordering (ORDER_BY) conditions: ///////////////////////////////////
        $obj->read_where_cool();
        list ($where_str, $having_str) = $obj->get_where_condition();
        $where_params = $obj->get_where_params();
		if($where_params){
			foreach($where_params as $k=>$v){
				$filter_selected_name = $k;
				$filter_val = $v;
			}
			$this->up_templater->assign('filter', $filter_val);
			$this->up_templater->assign('filter_by', $filter_selected_name);
		}

        if(is_null($default_order_by)) {$default_order_by = $obj->primary_key_name();}
        list($order_by, $order_by_params) = $obj->read_order_by($default_order_by);
		if(isset($__add_params['order_by'])){
			$order_by = $__add_params['order_by'];
		}
		/////////////////////////////////////////////////////////////////////////////////////////////////
        $query->expand(array(
            'where'		=> $where_str,
            'order_by'	=> $order_by,
        ));
		$this->up_templater->assign('order_by', $order_by);

		$main_res = $this->db->query($query->str());
		$total_record = $main_res->num_rows();
		
		// LIMIT ////////////////////////////////////////////////////////////////////////////////////////
		//$__pp_session = $this->session->userdata('per_page');
		if(isset($__add_params['limit']))
		{
			$__params = preg_split('/,/', $__add_params['limit']);
			if(count($__params)>1){
				$__cur_page = $__params[0];
				$__per_page = $__params[1];
			} else {
				$__per_page = $this->config->item('admin_default_rows_per_page');			
				$__cur_page = $value;
			}
		} else {
			$__cur_page = 1;
			$__per_page = $this->config->item('admin_default_rows_per_page');			
		}
		
		if($this->input->post('action') == 'filtratoraction')
		{
			$__per_page = $this->input->post('rows');
			$this->session->set_userdata('per_page', $__per_page);
		}
		$this->__initPaging($__per_page, $__cur_page, $obj->table_name, $total_record);
		/////////////////////////////////////////////////////////////////////////////////////////////////
	      if($total_record > 0) {
	        $res = $obj->get_expanded_result($query, array(
				'where'		=> $where_str,
				'order_by'	=> $order_by,
//                'group_by'	=> $group_by,
			  'limit'		=> ($__cur_page*$__per_page)-$__per_page.", $__per_page",
    	    ));
			
            // Fill the table with selected items:
            $i = 0;
			$__ret = array();
			foreach ($res->result_array() as $row)
			{
                $obj->fetch_row($row);
				array_push($__ret, $obj->write());
                $i++;
            }
			$this->up_templater->assign('several_objects_data', $__ret);
        }
		$obj->_postProcessHeaderTable();
		
	} // end of function 
	
	function _processUserMenu()
	{
		
		$segment = $this->uri->segment(1);
		
		switch($segment)
		{
			case 'gallery':
			case 'managegallery':
			case 'user':
			case 'editgallery':			
			case 'addgallery':				
				$this->up_templater->assign('li_um_gal', 'class="active"');
				$this->up_templater->assign('li_um_pht', '');
				$this->up_templater->assign('li_um_scn', '');
				$this->up_templater->assign('li_um_prf', '');
				$this->up_templater->assign('li_um_sta', '');
				
				$this->up_templater->assign('div_um_gal', 'class="tab_right active"');
				$this->up_templater->assign('div_um_pht', 'class="tab_right"');
				$this->up_templater->assign('div_um_scn', 'class="tab_right"');
				$this->up_templater->assign('div_um_prf', 'class="tab_right"');
				$this->up_templater->assign('div_um_sta', 'class="tab_right"');
				
				$this->up_templater->assign('logged_user_menu_2_level', '
						<li><a href="'.base_url().'managegallery">Управление галереями</a></li>
						<li><a href="'.base_url().'addgallery">Добавить галерею</a></li>'
						);
				
			break;
						
			case 'home':
			case 'managephoto':
			case 'addphoto':
			case 'editphoto':			
				$this->up_templater->assign('li_um_gal', '');
				$this->up_templater->assign('li_um_pht', 'class="active"');
				$this->up_templater->assign('li_um_scn', '');
				$this->up_templater->assign('li_um_prf', '');
				$this->up_templater->assign('li_um_sta', '');
				
				$this->up_templater->assign('div_um_gal', 'class="tab_right"');
				$this->up_templater->assign('div_um_pht', 'class="tab_right active"');
				$this->up_templater->assign('div_um_scn', 'class="tab_right"');
				$this->up_templater->assign('div_um_prf', 'class="tab_right"');
				$this->up_templater->assign('div_um_sta', 'class="tab_right"');
				
				$this->up_templater->assign('logged_user_menu_2_level', '
						<li><a href="'.base_url().'managephoto">Управление фотографиями</a></li>
						<li><a href="'.base_url().'addphoto">Добавить фотографию</a></li>'
						);
			break;
			
			case 'managesocial':
			case 'social':
				$this->up_templater->assign('li_um_gal', '');
				$this->up_templater->assign('li_um_pht', '');
				$this->up_templater->assign('li_um_scn', 'class="active"');
				$this->up_templater->assign('li_um_prf', '');
				$this->up_templater->assign('li_um_sta', '');
				
				$this->up_templater->assign('div_um_gal', 'class="tab_right"');
				$this->up_templater->assign('div_um_pht', 'class="tab_right"');
				$this->up_templater->assign('div_um_scn', 'class="tab_right active"');
				$this->up_templater->assign('div_um_prf', 'class="tab_right"');
				$this->up_templater->assign('div_um_sta', 'class="tab_right"');
			break;
			
			
			case 'pdata':
				$this->up_templater->assign('li_um_gal', '');
				$this->up_templater->assign('li_um_pht', '');
				$this->up_templater->assign('li_um_scn', '');
				$this->up_templater->assign('li_um_prf', 'class="active"');
				$this->up_templater->assign('li_um_sta', '');
				
				$this->up_templater->assign('div_um_gal', 'class="tab_right"');
				$this->up_templater->assign('div_um_pht', 'class="tab_right"');
				$this->up_templater->assign('div_um_scn', 'class="tab_right"');
				$this->up_templater->assign('div_um_prf', 'class="tab_right active"');
				$this->up_templater->assign('div_um_sta', 'class="tab_right"');
			break;
			
			
			case 'showstatistics':
				$this->up_templater->assign('li_um_gal', '');
				$this->up_templater->assign('li_um_pht', '');
				$this->up_templater->assign('li_um_scn', '');
				$this->up_templater->assign('li_um_prf', '');
				$this->up_templater->assign('li_um_sta', 'class="active"');
				
				$this->up_templater->assign('div_um_gal', 'class="tab_right"');
				$this->up_templater->assign('div_um_pht', 'class="tab_right"');
				$this->up_templater->assign('div_um_scn', 'class="tab_right"');
				$this->up_templater->assign('div_um_prf', 'class="tab_right"');
				$this->up_templater->assign('div_um_sta', 'class="tab_right active"');
			break;
			
			default:
			break;
		}
	} // end of function 
	
	
    /**
     * Prepare breadcumb infiormation 
     * @access private
     * @return NULL
     */
	function _fillBread()
	{
		$html_code = '';
		
		$segment = $this->uri->segment(1);
		switch($segment)
		{
			case 'pdata':
					$html_code .= '<li><a href="'.base_url().'user/'.$this->user->nickname.'">'.$this->user->nickname.'</a></li>';
					$html_code .= '<li class="active"><a href="'.base_url().'pdata'.'">Управление личными данными</a></li>';
			break;
			
				
			case 'user':
				$user_name = $this->uri->segment(2);
				if(strlen($user_name) > 20){
					return $html_code;
				}
				
				$user = new User_model();
				$user->fetchByName($user_name);
				
				if($user->isDefinite())
				{
					$html_code .= '<li class="active"><a href="'.base_url().'user/'.$user->nickname.'">'.$user->nickname.'</a></li>';
				}
			break;
			
			case 'gallery':
				$gallery_name = $this->uri->segment(2);
				if(strlen($gallery_name) != 32){
					return $html_code;
				}
				
				$this->load->model('Photogallery_model');
				
				$gallery = new Photogallery_model();
				$gallery->getByINternalName($gallery_name);
				if($gallery->isDefinite())
				{
					$user = new User_model($gallery->user_id);
					$html_code .= '<li><a href="'.base_url().'user/'.$user->nickname.'">'.$user->nickname.'</a></li>';					
					$html_code .= '<li class="active"><a href="'.base_url().'gallery/'.'">'.$gallery->internal_name.'</a></li>';					
				}
				
			break;
					
			case 'image':
				$photo_name = $this->uri->segment(2);
			
				if(strlen($photo_name) != 32){
					return $html_code;
				}
				
				$this->Photo_model->getByINternalName($photo_name);
				
				if($this->Photo_model->isDefinite()){
					
					if($this->Photo_model->user_id)
					{
						$user = new User_model($this->Photo_model->user_id);
						$html_code .= '<li><a href="'.base_url().'user/'.$user->nickname.'">'.$user->nickname.'</a></li>';
						
						if($this->Photo_model->gallery_id){
							$this->load->model('Photogallery_model');
							$gallery = new Photogallery_model($this->gallery_id);
							
							$html_code .= '<li><a href="'.base_url().'gallery/'.'">'.$gallery->internal_name.'</a></li>';
						}
					}
				}
				
				$html_code .= '<li class="active"><a href="'.base_url().'image/'.$this->Photo_model->internal_name.'">'.$this->Photo_model->internal_name.'</a></li>';
			break;
						
			default:
			break;
		}
		
		$this->up_templater->assign('our_breadcrumbs', $html_code);		
	} // end of function 
	
    /**
     * Prepare and fill all information about HTML header. 
     * Take all information from application\config\header_info.php
     * @access private
     * @return NULL
     */
	function _print_header_information()
	{
		$this->config->load('header_info');
                        
        $this->up_templater->assign('site_charset',      $this->config->item('site_charset'));
		$this->up_templater->assign('logo_full',         $this->config->item('logo_full'));
		$this->up_templater->assign('site_keywords',     $this->config->item('site_keywords'));
		$this->up_templater->assign('site_add_keywords', $this->config->item('site_add_keywords'));
		$this->up_templater->assign('author',            $this->config->item('author'));
		$this->up_templater->assign('copyright',         $this->config->item('copyright'));
		$this->up_templater->assign('favicon',           $this->config->item('favicon'));
		$this->up_templater->assign('cssname',           $this->app_mode);
		
		$this->up_templater->assign('appversion', $this->config->item('appversion'));
		$this->up_templater->assign('sitename', $this->config->item('sitename'));
	}
	
    /**
     * Add some additional external js file
     * @access private and from members
     * @return NULL
     */
	function _addAdditionalJS($_str)
	{
		$this->up_templater->assign('additional_js', $_str);
	}
	
    /**
     * Add some additional external js file
     * @access private and from members
     * @return NULL
     */
	function _addAdditionalCSS($_str)
	{
		$this->up_templater->assign('additional_css', $_str);
	}

    /**
     * Try to get user and user`s session.
     *
     * @access private
     * @return boolean is user authorized 
     */
    function _read_user_session()
    {
        $CI =& get_instance();
        $CI->load->model('Session_model');
        
        if (!$this->Session_model->read()) {return false;}

        if (!$this->user->fetchByID($this->Session_model->user_id)) {

            $this->user->id = 0;
            return false;
        }
				
		$this->up_templater->assign('user', $this->user);
		$this->up_templater->register_object('user', $this->user);
		
        return true;
    }

    /**
     * Try login to us system.
     *
     * @access public
     * @return void redirects if not auth
     */
    function trylogin()
    {
        $err = $this->user->login();
		$redirect_url = base_url().'index';

        if ($err) {
			$newdata = array('msg' => array(
                                                'body'      => 'Позьзователь с такими логином и паролем не найден.',
                                                'type'      => 'info',
                                                'showtime'  => 6000,
                                             )
                             );
			$this->session->set_userdata($newdata);
        } else {
            $redirect_url = base_url().'myprofile';
            $this->Session_model->start($this->user->id);

			$newdata = array('msg' => 'Добро пожаловать.');
			$this->session->set_userdata($newdata);
			
			if(isset($_POST['back_url'])){
				$redirect_url = base_url().substr($_POST['back_url'], 1);
			}
        }
        
		redirect($redirect_url);
    }

    /**
     * Logout.
     *
     * @access public
     * @return void logout user from the system
     */
    function logout()
	{
		$this->Session_model->del();
		redirect(base_url().'home');
	}

	// Title fiunctions ///////////////////////////

    /**
     * Add messages in title array.
     *
     * @access public
     * @params $mess:string
     * @return NULL
     */
	function addToTitle($mess)
	{
		if(strlen($mess)>0){
			array_push($this->browser_title, $mess);
		}
	}

    /**
     * Return ready title.
     *
     * @access public
     * @return string
     */
	function getTitle()
	{
		$ret = '';

		array_unshift($this->browser_title, $this->config->item('title_'.$this->app_mode));
		$ret = implode($this->config->item('title_separator'), $this->browser_title);

		return $ret;
	}
	//////////////////////////////////////////////
	
	
	
    /**
     * Send email with a relation information.
     *
     * @access public
     * @return string
     */
	function sendMail($_sended_data)
	{
		$this->load->library('email');

		$this->email->from('your@example.com', 'Your Name');
		$this->email->to('someone@example.com');
		$this->email->cc('another@another-example.com');
		$this->email->bcc('them@their-example.com');

		$this->email->subject('Email Test');
		$this->email->message('Testing the email class.');

		$this->email->send();	
	}
	
	

    // Auth. functions for setup/admin part /////////////////////////////////////////////
    function authorize() {
        $this->handleHttpAuth(
            $this->config->item("admin_login"),
            $this->config->item("admin_password")
        );
    }

    function handleHttpAuth(
        $login, $password, $realm = "Secure area."
    ) {
    
        if (
            (!isset($_SERVER["PHP_AUTH_USER"]) || !isset($_SERVER["PHP_AUTH_PW"])) ||
            $_SERVER["PHP_AUTH_USER"] != $login || $_SERVER["PHP_AUTH_PW"] != $password
        ) {
            $this->sendHttpAuthHeaders($realm);
            $this->sendHttpAuthErrorPage();
            exit;
        }
    }

    function sendHttpAuthErrorPage() {
        $this->up_templater->render('access_denied');
    }

    function sendHttpAuthHeaders($realm) {
        header("WWW-Authenticate: Basic realm=\"{$realm}\"");
        header("HTTP/1.0 401 Unauthorized");
    }
    /////////////////////////////////////////////////////////////////////////////////////


} // end of class


/* End of file UP_Controller.php */
/* Location: application/core/UP_Controller.php */

?>