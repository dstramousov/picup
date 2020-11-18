<?php

class DefaultFrontEnd extends UP_Controller
{
    /**
     * Constructor.
     *
     * @access public
     * @return none
     */
    function __construct()
    {
		$this->app_mode = 'main';
		$this->addToTitle('PicUP');
		
		parent::__construct();
		
		log_message('debug', 'FrontEndnController controller has initialized.');		
	} // end of function 
    
    /**
     * Render auth firts page. 
     *
     * @access public
     * @return page
     */
	function home()
	{
		if(!$this->user->isDefinite()){
			redirect(base_url().'index');
		} else{
            redirect(base_url().'myprofile');
        }
    } // end of function
    
    
    /**
     * Render auth firts page. 
     *
     * @access public
     * @return page
     */
	function index()
	{    
		$this->_addAdditionalCSS('	<link href="'.base_url().'css/styles.css" rel="stylesheet" type="text/css" />
									<link href="'.base_url().'css/auth.css" rel="stylesheet" type="text/css" />
								');

		$this->_addAdditionalJS('<script type="text/javascript" src="'.base_url().'js/modernizr.js"></script> ');
		
		$this->addToTitle('лучший фото-шаринг');
		$this->up_templater->render('frontend/firstPage');
	} // end of function
	        	
    /**
     * First entry point for requested photo.
     *
     * @access public
     * @return none. call follow steps for render page
     */
	function preShowLogic($_param=NULL)
	{
		$_param = trim($_param);
		
		if(isset($_POST['newcomment'])){$this->_makeComment();}
				
		if(($_param)){
			// 1. try to fetch requested photo und understand what next 
			$requested_photo = $this->Photo_model->getByINternalName($_param);
			
			if($requested_photo){
				
				if($requested_photo->user_id){
					// register user										
					if($requested_photo->gallery_id){
						// gallery view
						$this->_reguserRenderPhotoPage($requested_photo);
					} else {
						// single photo						
						$this->_anonymousRenderPhotoPage($requested_photo);
					}					
				} else {
					// anonymous user
					$this->_anonymousRenderPhotoPage($requested_photo);
				}
				
			} else {
				redirect(base_url().'home');
			}
		} else {
			// redirect to home page
			redirect(base_url().'home');
		}
		
	} // end of function 
	
	function _makeComment()
	{
		if($this->user->isDefinite())
		{		
			$__arr = preg_split('/\//', $this->input->post('curaction'), -1, PREG_SPLIT_NO_EMPTY);
			
			if($__arr[0] == 'gallery'){
				$this->load->model('Photogallery_model');
				$obj = $this->Photogallery_model->getByINternalName($__arr[1]);
				$_f_name = 'gallery_id';
			} else {
				$obj = $this->Photo_model->getByINternalName($__arr[1]);
				$_f_name = 'photo_id';
			}
			
			if($obj)
			{							
				$comment_body = trim(strip_tags($this->input->post('newcomment')));
								
				$data = array(
					'created'  =>  date("Y-m-d H:i:s"),
					'user_id'  =>  $this->user->id,
					$_f_name   =>  $obj->id,
					'parent_id'=>  0,
					'status'   =>  'active',
					'body'     =>  get_shortened($comment_body, 4096),
				); 
				
				$this->db->insert('comments', $data); 
				redirect(base_url().$__arr[0].'/'.$__arr[1]);
			} else {
				redirect(base_url().'home');
			}	
		} else {
			redirect(base_url().'home');
		}
	} // end of function 
	
	
    /**
     * Render page for registered user photo. 
     *
     * @access private
     * @return boolean is user authorized
     */
	function _reguserRenderPhotoPage($requested_photo)
	{		
	
		$this->_addAdditionalCSS('	<link href="'.base_url().'css/null.css" rel="stylesheet" type="text/css" />
									<link href="'.base_url().'css/style.css" rel="stylesheet" type="text/css" />
									<link href="'.base_url().'css/jquery.ad-gallery.css" rel="stylesheet" type="text/css">
									<link href="'.base_url().'css/cusel.css" rel="stylesheet" type="text/css" />
									<link href="'.base_url().'css/login.css"  rel="stylesheet" type="text/css" />
								');  
  
		$this->_addAdditionalJS(
								'	<script type="text/javascript" src="'.base_url().'js/jquery.min.js"></script> 
									<script type="text/javascript" src="'.base_url().'js/jquery.ad-gallery.js"></script>
									<script type="text/javascript" src="'.base_url().'js/modernizr.custom.28468.js"></script>
									<script type="text/javascript" src="'.base_url().'js/cusel.js"></script>
									<script type="text/javascript" src="'.base_url().'js/main.js"></script>
								');
								
		// fetch gallery information 
		$this->load->model('Photogallery_model');
		$this->Photogallery_model->fetchByID($requested_photo->gallery_id);
		
		// make update for this field ////////
		$data = array('countseeit' => $this->Photogallery_model->countseeit+1);
		$this->db->where('id', $this->Photogallery_model->id);
		$this->db->update('gallery', $data);
		//////////////////////////////////////
		
		$this->up_templater->assign('code_for_insert', base_url().'code/'.$requested_photo->internal_name);
				
		// fech user information  
		$tmp_user = new User_model;
		$tmp_user->fetchByID($this->Photogallery_model->user_id);
		
		$this->addToTitle('Просмотр фотографий');
		$this->addToTitle('поьзователь '. $tmp_user->nickname);
		$this->addToTitle($this->Photogallery_model->description);
		
		// печеньки		
		$this->up_templater->assign('current_user', $tmp_user->nickname);
		$this->up_templater->assign('current_gallery', $this->Photogallery_model->internal_name);
		
		$this->up_templater->assign('needed', 'downloadgalallery/'.$this->Photogallery_model->internal_name);
		
		$this->up_templater->assign('current_entity', 'gallery/'.$this->Photogallery_model->internal_name);
				
		$this->up_templater->assign('upload_date', systemFormatDateTime($this->Photogallery_model->created));
		$this->up_templater->assign('name_of_gallery', $this->Photogallery_model->description);
		
		$this->up_templater->assign('countseeit', $this->Photogallery_model->countseeit);
				
		// fill gallery block		
		$photos = $this->Photogallery_model->getPhotos();
		$_html = '';
		$_iterator = 0;
		foreach($photos as $photo)
		{
			$_html .= 
            '<li>
              <a href="'.base_url().$this->config->item('images_users').'/'.$tmp_user->nickname.'/'.$this->Photogallery_model->internal_name.'/'.$photo['internal_name'].$photo['extension'].'">
                <img src="'.base_url().$this->config->item('images_users').'/'.$tmp_user->nickname.'/'.$this->Photogallery_model->internal_name.'/'.$this->config->item('thumb_prefix').$photo['internal_name'].$photo['extension'].'" title="'.$photo['description'].'" alt="'.$photo['description'].'" class="image'.$_iterator.'">
              </a>
            </li>';
			$_iterator++;
		}
		$this->up_templater->assign('thumbs_images', $_html);
				
		$gallery_block = $this->up_templater->setBlock('common/gallery_block');
		$this->up_templater->assign('gallery_block', $gallery_block);
		
		$this->load->model('Comment_model');
		$count_comm = $this->Comment_model->getTotalCommentByEntityID($this->Photogallery_model->id, 'gallery');
		
		$this->up_templater->assign('count_comments_w', endingsForm($count_comm, 'комментарий', 'комментария', 'комментариев'));
		$this->up_templater->assign('count_comments', $count_comm);

		//////////////////  comments ///////////////////
		$comment_block = '';
		if($count_comm > 0 ){
			// get all comments
			$comments = array();
			$comments = $this->Comment_model->getAllComments($this->Photogallery_model->id, 'gallery');
			
			$u = new User_model();
			foreach($comments as $c)
			{
				$u->fetchByID($c['user_id']);
				
				$this->up_templater->assign('commentorname', $u->nickname);
				$this->up_templater->assign('commentbody', $c['body']);

				$this->up_templater->assign('comment_date', systemFormatDateTime($c['created'], true));
				$this->up_templater->assign('useravatar', base_url().$this->config->item('preset_avatar_folder').'/'.$u->avatar);
				
				$comment_block .= $this->up_templater->setBlock('commentblock');
			}			
		}
		
		$this->up_templater->assign('comments', $comment_block);
		
		
		$this->up_templater->render('reg_gallery_picture');
	}
	
	function getUsersGallery($user_name)
	{
		$this->_insertJSHeader();
	
		$_requested_user = new User_model;
		$_requested_user->fetchByName($user_name);
		
		if(!$_requested_user->isDefinite()){
			redirect(base_url().'home');
		}
		
		$this->up_templater->assign('owner_user_nickname', $_requested_user->nickname);
		
		$this->load->model('Photogallery_model');
		$user_gallerys = $this->Photogallery_model->getAllGalleryByUser($_requested_user->id);
		
		$this->up_templater->assign('user_gallerys', $user_gallerys);
		$this->up_templater->render('users_galleries');
	} // end of function 
	
	
    /**
     * Render page for anonymous photo. 
     *
     * @access private
     * @return boolean is user authorized 
     */
	function _anonymousRenderPhotoPage($requested_photo)
	{  
		$this->_insertJSHeader();
		
		$this->up_templater->assign('countseeit', $requested_photo->countseeit);
		$this->up_templater->assign('countseeit_w', endingsForm($requested_photo->countseeit, 'раз', 'раза', 'раз'));
		
		$this->addToTitle('фотография');
		$this->addToTitle($requested_photo->description);
		
		// make update for this field ////////
		$data = array('countseeit' => $requested_photo->countseeit+1);
		$this->db->where('id', $requested_photo->id);
		$this->db->update('photo', $data); 
		//////////////////////////////////////
				
		$this->up_templater->assign('current_entity', 'image/'.$requested_photo->internal_name);
		$this->up_templater->assign('current_photo', $requested_photo->internal_name);
		$this->up_templater->assign('upload_date', systemFormatDateTime($requested_photo->created));
		$this->up_templater->assign('name_of_photo', $requested_photo->description);
		
		$this->up_templater->assign('needed', 'downloadphoto/'.$requested_photo->internal_name);
		
		$this->up_templater->assign('code_for_insert', base_url().'code/'.$requested_photo->internal_name);
		
		if($requested_photo->user_id){
		
			$__user = new User_model();
			$__user->fetchByID($requested_photo->user_id);
			
			if($__user->isDefinite()){
				$this->up_templater->assign('curent_photo_path', base_url().$this->config->item('images_users').'/'.$__user->nickname.'/'.$requested_photo->internal_name.$requested_photo->extension);
			} else {
				$this->up_templater->assign('curent_photo_path', base_url().'images/'.IMAGE_NOT_FOUND);
			}
		} else {
			$this->up_templater->assign('curent_photo_path', base_url().$this->config->item('images_anonymous').'/'.$requested_photo->foldername.'/'.$requested_photo->internal_name.$requested_photo->extension);
		}
		
		$this->load->model('Comment_model');
		$count_comm = $this->Comment_model->getTotalCommentByEntityID($requested_photo->id);
		$this->up_templater->assign('count_comments_w', endingsForm($count_comm, 'комментарий', 'комментария', 'комментариев'));
		$this->up_templater->assign('count_comments', $count_comm);

		$comment_block = '';
		if($count_comm > 0 ){
			// get all comments
			$comments = array();
			$comments = $this->Comment_model->getAllComments($requested_photo->id);
			
			$u = new User_model();
			foreach($comments as $c)
			{
				$u->fetchByID($c['user_id']);
				
				$this->up_templater->assign('commentorname', $u->nickname);
				$this->up_templater->assign('commentbody', $c['body']);
				$this->up_templater->assign('comment_date', systemFormatDateTime($c['created'], true));
				$this->up_templater->assign('useravatar', base_url().$this->config->item('preset_avatar_folder').'/'.$u->avatar);
				
				$comment_block .= $this->up_templater->setBlock('commentblock');
			}			
		}
		
		$this->up_templater->assign('comments', $comment_block);
		
		$this->up_templater->render('anonym_picture');

	} // end of function 
	
	
	function bb($_param=NULL, $_size='small')
	{
		if(!$_param){
			redirect(base_url().'home');
		}
		$_param = substr($_param, 0, 32);
		
		$this->Photo_model->getByINternalName($_param);
				
		if ($this->Photo_model->isDefinite())
		{
			$this->load->helper('download');
			
			$file_data = $this->Photo_model->getFileProperties();
			$data = file_get_contents($file_data['file_path']);
			
			$name = $this->Photo_model->internal_name.$this->Photo_model->extension;
			
			force_download($name, $data); 			
			
		} else {
			exit();
		}
	} // end of function 
	
	
	function preShowLogicGallery($_param=NULL)
	{		
		if(!$_param){
			redirect(base_url().'home');
		}
		
		if(isset($_POST['newcomment'])){$this->_makeComment();}
				
		$this->load->model('Photogallery_model');
		$this->Photogallery_model->getByINternalName($_param);
		
		$photos = $this->Photogallery_model->getPhotos();
		if($photos){		
			$requested_photo = $this->Photo_model->getByINternalName($photos[0]['internal_name']);
			if($requested_photo)
			{
				$this->_reguserRenderPhotoPage($requested_photo);
			} else { redirect(base_url().'home');}
			
		} else {
			redirect(base_url().'home');
		}
			
	} // end of function 
	
	function _insertJSHeader()
	{
		$this->_addAdditionalCSS('
									<link href="'.base_url().'css/cusel.css"  rel="stylesheet" type="text/css" />
									<link href="'.base_url().'css/slider.css" rel="stylesheet" type="text/css" />
									<link href="'.base_url().'css/null.css"   rel="stylesheet" type="text/css" />
									<link href="'.base_url().'css/style.css"  rel="stylesheet" type="text/css" />
									<link href="'.base_url().'css/login.css"  rel="stylesheet" type="text/css" />
								');  
  
		$this->_addAdditionalJS(
								'
								<script type="text/javascript" src="'.base_url().'js/main.js"></script>
								<script type="text/javascript" src="'.base_url().'js/cusel.js"></script>
								<script type="text/javascript" src="'.base_url().'js/jquery.min.js"></script>
								<script type="text/javascript" src="'.base_url().'js/slide.js"></script>
								');
	} // end of function 
		
    /**
     * Private function 
     */
	function downloadPhoto($_param)
	{
		if(strlen($_param) != 32){
			redirect(base_url().'home');
		}
		
		$this->Photo_model->getByINternalName($_param);
				
		if ($this->Photo_model->isDefinite())
		{
			$this->load->helper('download');
			
			$file_data = $this->Photo_model->getFileProperties();
			$data = file_get_contents($file_data['file_path']); // Считываем содержимое файла
			
			$name = $this->Photo_model->internal_name.$this->Photo_model->extension;
			
			force_download($name, $data); 			
			
		} else {
			exit();
		}
		
	} // end of function
	
    /**
     * Private function 
     */
	function downloadGallery($_param)
	{
		if(strlen($_param) != 32){
			redirect(base_url().'home');
		}
		
		$this->load->model('Photogallery_model');
		$gallery = new Photogallery_model();
		$gallery->getByINternalName($_param);
				
		if ($gallery->isDefinite())
		{
			$this->load->helper('download');
			$this->load->library('zip');
			
			$user = new User_model($gallery->user_id);
			$folder_name = $this->config->item('images_users').'/'.$user->nickname.'/'.$gallery->internal_name.'/';
			
			$this->zip->read_dir($folder_name);
			$this->zip->download('my_backup.zip');
			
		} else {
			exit();
		}
		
	} // end of function
	
		
	
	
    /**
     * Private function 
     */
	function _applyLastTopPhoto($__count, $__blockname)
	{
		if($__blockname == "top_photos"){
			$__photo = $this->Photo_model->getTopGrid($__count);
		} elseif($__blockname == "last_photos") {
			$__photo = $this->Photo_model->getLastUploaded($__count);
		} else {
		
			$__photo = $this->Photo_model->getTalked($__count+5);   // $__count+5 reserve for some statuses
			$__ids = array();
			
			foreach($__photo as $p){
				array_push($__ids, $p->photo_id);
			}
			
			$this->db->select('*, LEFT(created,10) as foldername', FALSE);
			$this->db->from('photo');
			$this->db->where_in('id', $__ids);
			$this->db->where('user_perm', 'all');
			$this->db->where('status', 'active');

			$this->db->order_by("countseeit", "ASC");
			$this->db->limit($__count);
			$query = $this->db->get();
			
			if ($query->num_rows() > 0)
			{
				$__photo = $query->result();
			} else {$__photo = NULL;}
			
		}
		$user = new User_model;
		
		$result_array = array();
		
		if($__photo){
			foreach($__photo as $key=>$o)
			{
				$obj = new STDClass();
				
				if($__blockname == "talked_photos"){
					$this->load->model('Comment_model');
					$obj->ccomm	= $this->Comment_model->getTotalCommentByEntityID($o->id);
					//dump($obj->ccomm);
				} elseif($__blockname == "top_photos"){
					$obj->seeit	= $o->countseeit;
				} else {
					$obj->ago	= time_since(time()-strtotime($o->created));
				}
								
				if($o->user_id)
				{
					$user->fetchByID($o->user_id);
					
					$obj->author	= $user->nickname;
					$obj->desc		= get_shortened($o->description, 30);
					$obj->wo_desc		= 0;
					$obj->wo_user		= 0;
					
					if($o->gallery_id){
						$this->Photogallery_model->fetchByID($o->gallery_id);				
						$obj->path = $this->config->item('images_users').'/'.$user->nickname.'/'.$this->Photogallery_model->internal_name.'/'.$this->config->item('thumb_prefix').$o->internal_name.$o->extension;
						$obj->href = base_url().$this->config->item('url_gallery_prefix').'/'.$this->Photogallery_model->internal_name;
					} else {
						$obj->path = $this->config->item('images_users').'/'.$user->nickname.'/'.$this->config->item('thumb_prefix').$o->internal_name.$o->extension;
						$obj->href = base_url().$this->config->item('url_image_prefix').'/'.$o->internal_name;
					}
				} else {
					$obj->wo_desc		= 1;
					$obj->wo_user		= 1;
					$obj->author		= 'anonymous';
					$obj->desc			= 'без описания';
					$obj->path = $this->config->item('images_anonymous').'/'.$o->foldername.'/'.$this->config->item('thumb_prefix').$o->internal_name.$o->extension;
					$obj->href = base_url().$this->config->item('url_image_prefix').'/'.$o->internal_name;			
				} // end if

				array_push($result_array, $obj);
			} // end foreach
		} // end if

		$this->up_templater->assign($__blockname, $result_array);
		$_code = $this->up_templater->setBlock($__blockname);
		$this->up_templater->assign($__blockname.'_block', $_code);
	} // end of function 
	
	
    /**
     * Private function 
     */
	function _fillSlider()
	{
		$_code = $this->up_templater->setBlock('slider');
		$this->up_templater->assign('slider_block', $_code);
	}
		
	private function _formatBytes($bytes, $precision = 2) {
		$units = array('B', 'KB', 'MB', 'GB', 'TB');
	 
		$bytes = max($bytes, 0);
		$pow = floor(($bytes ? log($bytes) : 0) / log(1024));
		$pow = min($pow, count($units) - 1);
	 
		$bytes /= pow(1024, $pow);
	 
		return round($bytes, $precision) . ' ' . $units[$pow];
	}	
	

    /**
     * Render tryupload page. 
     *
     * @access public
     * @return page
     */
	function tryupload()
	{
		if($_FILES['userfile']['error'] != 0){
			$newdata = array('msg' => 'Файл не выбран');
			$this->session->set_userdata($newdata);
			redirect(base_url().'home');
		}
	
		$psp = '';
		$__gallery_id = NULL;
		
		$resize_options = 0;
		if(isset($_POST['list_resize'])){$resize_options = (int)$this->input->post('list_resize');}
				
		// if user exist not needed check kaptcha
		if($this->user->isDefinite()){
			
		} else {
			if(isset($_POST['secpic'])){$psp = $this->input->post('secpic');}
			if($psp == ''){
				$newdata = array('msg' => 'Введите код указанный на картинке.');
				$this->session->set_userdata($newdata);
				redirect(base_url().'home');
			}
			
			$ssp = NULL;
			if(isset($_SESSION['secpic'])){$ssp = $_SESSION['secpic'];}
			
			if($ssp != $psp){
				$newdata = array('msg' => 'Код указанный на картинке неверный. Попробуйте еще раз.');
				$this->session->set_userdata($newdata);
				redirect(base_url().'home');
			}
		}
		
		$upl_date = date("Y-m-d");
		$upl_datetime = date("Y-m-d H:i:s");		
		
		if($this->user->isDefinite()){
			// reg user 
			
			if($this->input->post('gallery_id')){
				$__gallery_i_name = $this->input->post('gallery_id');
				$this->load->model('Photogallery_model');
				$gallery = new Photogallery_model();
				$gallery->getByINternalName($__gallery_i_name);
				
				if($gallery->isDefinite()){
					if($this->user->id == $gallery->user_id){
						$__gallery_id = $gallery->id;
					}
				}
				
				$destination_folder = $this->config->item('images_users').'/'.$this->user->nickname.'/'.$gallery->internal_name;
			} else {				
				$destination_folder = $this->config->item('images_users').'/'.$this->user->nickname;
			}
		} else {
		
			$destination_folder = $this->config->item('images_anonymous').'/'.$upl_date;
			// anonymous
			if(!is_dir($destination_folder)){
				if(!mkdir($destination_folder)){
					$newdata = array('msg' => 'Filesystem error (mkdir)');
					$this->session->set_userdata($newdata);
					redirect(base_url().'home');
				}
			}
		}
		
		$config['upload_path']		= $destination_folder;
		$config['allowed_types']	= $this->config->item('anonymous_allowed_file_extension');
		$config['max_size']			= $this->config->item('anonymous_max_file_size');
		$config['max_width']		= $this->config->item('anonymous_max_width');
		$config['max_height']		= $this->config->item('anonymous_max_height');
		
		$this->load->library('upload', $config);
		
		if ( ! $this->upload->do_upload())
		{
			$newdata = array('msg' => $this->upload->display_errors());
			$this->session->set_userdata($newdata);
			redirect(base_url().'home');
		}	
		else
		{
			// upload new file ////////////////////////////////////////////
			$upload_data = array('upload_data' => $this->upload->data());
			
			$__descr = strip_tags($this->input->post('photodescription'));
			
			if($this->user->isDefinite()){
				$__user_id = $this->user->id;
			} else {
				$__user_id = NULL;				
			}
			
			$internal_name = md5($upl_datetime.'_'.rand(1, 100000).'_'.$this->config->item('encryption_key'));
			$data = array(
				'created'       	=>  $upl_datetime,
				'internal_name'		=>  $internal_name,
				'extension'			=>	$upload_data['upload_data']['file_ext'],
				'status'  			=>  'active',
				'user_perm'    		=>  'all',
				'gallery_id'		=>	$__gallery_id,
				'user_id'			=>  $__user_id,
				'description'  		=>  get_shortened($__descr, 1024),
				'countseeit'		=> 0,
			);			

			/*
			if($this->user->isDefinite()){
				// register user
				//dump($upload_data);
				$new_path = $upload_data['upload_data']['file_path'].$internal_name.$upload_data['upload_data']['file_ext'];
			} else {
				// anonymous
				$new_path = $upload_data['upload_data']['file_path'].$internal_name.$upload_data['upload_data']['file_ext'];
			}
			
			dump($new_path);
			*/
			
			$new_path = $upload_data['upload_data']['file_path'].$internal_name.$upload_data['upload_data']['file_ext'];
			
			if(!rename($upload_data['upload_data']['full_path'], $new_path)){
				$newdata = array('msg' => 'Filesystem error (rename)');
				$this->session->set_userdata($newdata);
				redirect(base_url().'home');
			}
			
			// create thumbnail //////////////////////////////////
			$config['image_library']	= 'gd2';
			$config['source_image']		= $new_path;
			$config['create_thumb']		= TRUE;
			$config['maintain_ratio']	= TRUE;
			$config['width']			= $this->config->item('thumb_width');
			$config['height']			= $this->config->item('thumb_height');
			$this->load->library('image_lib', $config); // загружаем библиотеку 
			
			if ( ! $this->image_lib->resize())
			{
				$newdata = array('msg' => $this->image_lib->display_errors());
				$this->session->set_userdata($newdata);
				redirect(base_url().'home');
			}
			
			// there need add logic for resize image for received parameters
			///////////////////////////////////////////////////////
			
			$external_name = md5($internal_name.'_'.rand(1, 100000).'_'.$this->config->item('encryption_key'));
			$ext_table_data = array(
										'internal_name' => $internal_name,
										'external_name' => $external_name,
										'created'		=> $upl_datetime,
			);
						
			$this->db->trans_start();
			$this->db->insert('ext_links', $ext_table_data);
			$this->db->insert('photo', $data);
			$this->db->trans_complete(); 			
						
			$view_url	= '<a href="'.base_url().$this->config->item('url_image_prefix').'/'.$internal_name.'">'.$internal_name.'</a>';
			$delete_url	= '<a href="'.base_url().'deletefile/'.$external_name.'">'.$external_name.'</a>';
			
			$newdata = array('msg' => 'Ваш файл загружен. <br/>URL для просмотра: '.$view_url.'<br/>URL для удаления: '.$delete_url);
			$this->session->set_userdata($newdata);
			
			if($this->user->isDefinite()){
				if($__gallery_id){
					redirect(base_url().'editgallery/'.$gallery->internal_name);
				}
				redirect(base_url().'managephoto');				
			} else {
				redirect(base_url().'home');
			}
		}
	}
	
		
    /**
     * delete anonymous file used direct link
     *
     * @access public
     * @return page
     */
	function deleteFile($_param)
	{
		if(strlen($_param) != 32){
			redirect(base_url().'home');
		}
				
		$query = $this->db->get_where('ext_links', array('external_name ' => $_param));
		
		if ($query->num_rows() > 0)
		{
			$row = $query->row();
			
			// file found
			$photo = $this->Photo_model->getByINternalName($row->internal_name);
			
			if($photo->user_id){
			
				$tmp_user = new User_model;
				$tmp_user->fetchByID($photo->user_id);
				
				// проверим хозяин ли это данной фотографии
				if($this->user->isDefinite()){
					if($tmp_user->id != $this->user->id){
						// пользователь определен, но хочет удалить не свою фотографию
						redirect(base_url().'home');
					}
				} else {
					// пользователь не определен но хочет удалить пользовательскую фотографию
					redirect(base_url().'home');
				}
				
				if($photo->gallery_id){
					$this->load->model('Photogallery_model');
					$obj = $this->Photogallery_model->fetchByID($photo->gallery_id);
					
					$folder_file = $this->config->item('images_users').'/'.$tmp_user->nickname.'/'.$obj->internal_name;
					$file	= $folder_file.$photo->internal_name.$photo->extension;
					$thumb	= $folder_file.$this->config->item('thumb_prefix').$photo->internal_name.$photo->extension;
					
				} else {
					
					$folder_file = $this->config->item('images_users').'/'.$tmp_user->nickname.'/';
					$file	= $folder_file.$photo->internal_name.$photo->extension;
					$thumb	= $folder_file.$this->config->item('thumb_prefix').$photo->internal_name.$photo->extension;
				}
				
			} else {
			
				$folder_file = $this->config->item('images_anonymous').'/'.$photo->foldername.'/';
				$file	= $folder_file.$photo->internal_name.$photo->extension;
				$thumb	= $folder_file.$this->config->item('thumb_prefix').$photo->internal_name.$photo->extension;
			}
			
			// db remove 
			$this->db->delete('photo', array('id' => $photo->id)); 
			$this->db->delete('ext_links', array('internal_name' => $photo->internal_name));
			$this->db->delete('comments', array('photo_id' => $photo->id));
			
			// filesystem remove
			unlink($file);
			unlink($thumb);
			
			$newdata = array('msg' => 'Ваш файл удален.');
			$this->session->set_userdata($newdata);
		} 		
		
		redirect(base_url().'home');
	} // end of function 
	
    /**
     * Render register page. 
     *
     * @access public
     * @return page
     */
	function register()
	{
		if($this->input->post('firstname')){$this->up_templater->assign('val_fistname', $this->input->post('firstname'));} 				
		if($this->input->post('lastname')){$this->up_templater->assign('val_lastname', $this->input->post('lastname'));}
		
		if($this->input->post('nickname')){$this->up_templater->assign('val_nickname', $this->input->post('nickname'));}
		if($this->input->post('email')){$this->up_templater->assign('val_email', $this->input->post('email'));}

		/*
		$this->load->helper('awatar');
		$_arr = getAwatarsArray();
		$awatar_block = '';
		foreach($_arr as $aname=>$adata)
		{
			$this->up_templater->assign('aw_path', base_url().$this->config->item('preset_avatar_folder').'/'.$aname);
			$awatar_block .= $this->up_templater->setBlock('awatarblock');
		}
		$this->up_templater->assign('awatar_block', $awatar_block);
		$this->up_templater->assign('user_awatar', base_url().$this->config->item('preset_avatar_folder').'/noavatar.jpg');
		*/
		
		
		$this->up_templater->render('register');
		
	} // end of function 
	
	
    /**
     * Trying to make register for user. 
     *
     * @access public
     * @return page
     */
	function tryregister()
	{
		$psp = '';
		
		$_errors = array();		
		$_errors = $this->_validate_post_register_data_small();
		//dump($_errors);
			
			if($_errors){
				foreach($_errors as $k=>$v){
					$newdata = array('msg' => $v);
					$this->session->set_userdata($newdata);
				}
			} else {
				// all ok
				// we can insert new user and go to special "thanks page"
				
				$data = array(
					'created'       =>  date("Y-m-d H:i:s"),
					'email'         =>  $this->input->post('email'),
					'nickname'      =>  $this->input->post('nickname'),
					'first_name'    =>  NULL,
					'last_name'     =>  NULL,
					'password'      =>  md5($this->input->post('pass')),
					'status'        =>  'active',
				);
				
				$user_folder = $this->config->item('images_users').'/'.$this->input->post('nickname');		
				if(!is_dir($user_folder)){
					if(!mkdir($user_folder)){
						$newdata = array('msg' => 'Filesystem error (mkdir)');
						$this->session->set_userdata($newdata);
						redirect(base_url().'home');
					}
				}
				$this->db->insert('user', $data);
				$_id = $this->db->insert_id();
				$this->Session_model->start($_id);
				$newdata = array('msg' => 'Регистрация прошла успешно. Дополнительные сведения высланы Вам на email.');
				$this->session->set_userdata($newdata);
			}
		redirect(base_url().'home');
	} // end of function 
	
	
	
    /**
     * Trying to make register for user. 
     *
     * @access public
     * @return page
     */
	function fulltryregister()
	{
		$psp = '';
		
		if(isset($_POST['secpic'])){
			$psp = $this->input->post('secpic');
		}
		
		$ssp = NULL;
		if(isset($_SESSION['secpic'])){
			$ssp = $_SESSION['secpic'];
		}
		
		if($ssp != $psp){
			$this->up_templater->assign('sec_code', 'Введите код');
			$this->register();
		} else {
		
			$_errors = array();		
			$_errors = $this->_validate_post_register_data();
			
			if($_errors){
				foreach($_errors as $k=>$v){
					$this->up_templater->assign($k, $v);
				}
				$this->register();				
			} else {
				// all ok
				// we can insert new user and go to special "thanks page"
				
				$data = array(
					'created'       =>  date("Y-m-d H:i:s"),
					'email'         =>  $this->input->post('email'),
					'nickname'      =>  $this->input->post('nickname'),
					'first_name'    =>  $this->input->post('firstname'),
					'last_name'     =>  $this->input->post('lastname'),
					'password'      =>  md5($this->input->post('pass')),
					'status'        =>  'active',
				);
				
				$user_folder = $this->config->item('images_users').'/'.$this->input->post('nickname');		
				if(!is_dir($user_folder)){
					if(!mkdir($user_folder)){
						$newdata = array('msg' => 'Filesystem error (mkdir)');
						$this->session->set_userdata($newdata);
						redirect(base_url().'home');
					}
				}
				$this->db->insert('user', $data);
				$newdata = array('msg' => 'Регистрация прошла успешно. Теперь вы можете войти на сайт используя свои login и password.');
				$this->session->set_userdata($newdata);
				
				redirect(base_url().'home');
			}
		}
	} // end of function 
	
	function _validate_post_register_data_small()
	{
		$_ret = array();
						
		$_user = new User_model();
		
		$email = $this->input->post('email');		
		if(!preg_match("/^[_\.0-9a-zA-Z-]+@([0-9a-zA-Z][0-9a-zA-Z-]+\.)+[a-zA-Z]{2,6}$/i", $email)) {			
			$_ret['err_mess_email'] = 'Введите корректный e-mail';
		} elseif(!$_user->is_unique_user_mail($email)){
			$_ret['err_mess_email'] = 'Такой e-mail уже используется';
		}
		
		// 4 nickname
		$nickname = $this->input->post('nickname');
		//dump($_user);
		if(!$_user->is_unique_user_nick($nickname)){
			$_ret['err_mess_nickname'] = 'Такой nickname уже зарегистрирован';
		}
				
		return $_ret;
	}

	
	function _validate_post_register_data()
	{
		$_ret = array();
						
		// 1 firstname
		$firstname = $this->input->post('firstname');
		if(strlen($firstname) <= 2){
			$_ret['err_mess_firstname'] = 'Имя должно быть больше 2 символов';
		}
		
		// 2 lastname
		$lastname = $this->input->post('lastname');
		if(strlen($lastname) <= 5){
			$_ret['err_mess_lastname'] = 'Фамилия должна быть больше 5 символов';
		}
		
		// 3 email
		$_user = new User_model();
		
		$email = $this->input->post('email');		
		if(!preg_match("/^[_\.0-9a-zA-Z-]+@([0-9a-zA-Z][0-9a-zA-Z-]+\.)+[a-zA-Z]{2,6}$/i", $email)) {			
			$_ret['err_mess_email'] = 'Введите корректный e-mail';
		} elseif(!$_user->is_unique_user_mail($email)){
			$_ret['err_mess_email'] = 'Такой e-mail уже используется';
		}
		
		// 4 nickname
		$nickname = $this->input->post('nickname');
		//dump($_user);
		if(!$_user->is_unique_user_nick($nickname)){
			$_ret['err_mess_nickname'] = 'Такой nickname уже зарегистрирован';
		}
		
		// 5 pass ans cpass
		$pass = $this->input->post('pass');
		$cpass = $this->input->post('cpass');
		if(strlen($pass) < 3){
			$_ret['err_mess_pass'] = 'Длина пароля должна быть > 3';
		}
		
		if(strlen($cpass) < 3 && $cpass != $pass){
			$_ret['err_mess_pass'] = 'Пароли не совпадают';
		}
		
		return $_ret;
	}
	
	
	// half static pages /////////////////////////////////////////////////////////////////
    /**
     * Render about page. 
     *
     * @access public
     * @return page
     */
	function about()
	{
		die('in progres..........');
	} // end of function 
	
    /**
     * Render rules page. 
     *
     * @access public
     * @return page
     */
	function rules()
	{
		die('in progres..........');
	} // end of function 
	
    /**
     * Render contact page. 
     *
     * @access public
     * @return page
     */
	function contacts()
	{		
		// 1. fetch top grid photo		
		$this->_addAdditionalCSS('
									<link href="'.base_url().'css/null.css" rel="stylesheet" type="text/css" />
									<link href="'.base_url().'css/style.css" rel="stylesheet" type="text/css" />
									<link href="'.base_url().'css/jquery.ad-gallery.css" rel="stylesheet" type="text/css">
									<link href="'.base_url().'css/cusel.css" rel="stylesheet" type="text/css" />
								');  
  
		$this->_addAdditionalJS(
								'
									<script type="text/javascript" src="'.base_url().'js/jquery.min.js"></script> 
									<script type="text/javascript" src="'.base_url().'js/jquery.ad-gallery.js"></script>
									<script type="text/javascript" src="'.base_url().'js/modernizr.custom.28468.js"></script>
									<script type="text/javascript" src="'.base_url().'js/cusel.js"></script>
									<script type="text/javascript" src="'.base_url().'js/main.js"></script>
								');
		
		$this->addToTitle('связь с администрацией сайта');
		
		$this->up_templater->render('common/contactform');
	} // end of function 
	
    /**
     * Prepare mail for admin and send if this is needed. 
     *
     * @access public
     * @return page
     */
	function trycontact()
	{
	
		$this->up_templater->render('register');
	
		die('in progres..........');
	} // end of function 
	
    /**
     * Render linkus page. 
     *
     * @access public
     * @return page
     */
	function linkus()
	{
		die('in progres..........');
	} // end of function 
	
    /**
     * Render abuse page. 
     *
     * @access public
     * @return page
     */
	function abuse()
	{
		die('in progres..........');
	} // end of function 
	
	//////////////////////////////////////////////////////////////////////////////////////
	
} // end of class 

/* End of file DefaultFrontEnd.php */
/* Location: ./application/controllers/DefaultFrontEnd.php */