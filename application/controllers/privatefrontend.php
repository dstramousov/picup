<?php

class PrivateFrontEnd extends UP_Controller  {

    /**
     * Constructor.
     *
     * @access public
     * @return none
     */
    function __construct()
    {
		$this->app_mode = 'main';
		$this->addToTitle('PiсUP');

		parent::__construct();
		
		if(!$this->user->isDefinite()){
			redirect(base_url().'home');
		}
		
		$this->up_templater->assign('logged_user_fn', $this->user->first_name);
		$this->up_templater->assign('logged_user_ln', $this->user->last_name);
        $this->up_templater->assign('logged_user_avatar', base_url().$this->config->item('preset_avatar_folder').'/'.$this->user->avatar);

		log_message('debug', 'PrivateFrontEnd controller has initialized.');
	} // end of function 
			
	function index()
	{
		$this->profile();
	} // end of function

    /**
     * First entry point for requested photo.
     *
     * @access public
     * @return none. call follow steps for render page
     */
	function myProfile($_param=NULL)
	{    
		$this->_addAdditionalCSS('	<link href="'.base_url().'css/styles.css" rel="stylesheet" type="text/css" />
									<link href="'.base_url().'css/carousel.css" rel="stylesheet" type="text/css" />
									<link href="'.base_url().'css/mCustomScrollbar.css" rel="stylesheet" type="text/css" />
								');

		$this->_addAdditionalJS('<script type="text/javascript" src="'.base_url().'js/modernizr.js"></script> ');
		
		$this->addToTitle('Личный кабинет');
		
		// time mashine 		
		$this->_initTimeMashine();
		////////////////////////////////////////
		
		$this->up_templater->render('profile/profile');
    } // end of function
    
    function profileSettings($_param=NULL){
    	$this->_addAdditionalCSS('	<link href="'.base_url().'css/styles.css" rel="stylesheet" type="text/css" />
									<link href="'.base_url().'css/carousel.css" rel="stylesheet" type="text/css" />
									<link href="'.base_url().'css/mCustomScrollbar.css" rel="stylesheet" type="text/css" />
								');
    	$this->addToTitle('Настройки');
    	$this->up_templater->render('profile/profileSettings');
	}
	
	
    /**
     * Init and apply some parameters for time mashine.
     *
     * @access private
     * @return page
     */	 
	private function _initTimeMashine()
	{	
		// 31557600 = 1 year
		$ALLOWED_MIN_DATE = mktime(0,0,0,1,1,1990);
		$ALLOWED_MAX_DATE = time()+31557600;
		//dump($ALLOWED_MIN_DATE, $ALLOWED_MAX_DATE);
	
		// 1. check session`s values
		$_checked_tm_from = FALSE;
		if($this->session->userdata('tm_from')){
			$_tmp = $this->session->userdata('tm_from');
			if($ALLOWED_MIN_DATE <= $_tmp && $_tmp <= $ALLOWED_MAX_DATE){
				$_checked_tm_from = $_tmp;
			}
		}

		$_checked_tm_to = FALSE;
		if($this->session->userdata('tm_to')){
			$_tmp = $this->session->userdata('tm_to');
			if($ALLOWED_MIN_DATE <= $_tmp && $_tmp <= $ALLOWED_MAX_DATE){
				$_checked_tm_to = $_tmp;
			}
		}

		$__tm_from	= new DateTime(date("Y-m-1 00:00:01"));
		$__tm_to	= new DateTime(date("Y-m-d H:i:s"));
		
		if($_checked_tm_from){
			$__tm_from->setTimestamp($_checked_tm_from);
		}
		if($_checked_tm_to){
			$__tm_to->setTimestamp($_checked_tm_to);
		}
		
		//echo $__tm_from->format('Y-m-d H:i:s').'<br/>';
		//echo $__tm_to->format('Y-m-d H:i:s');
		
		// 2. set in the session
		$this->session->set_userdata('tm_to', $__tm_to->getTimestamp());
		$this->session->set_userdata('tm_from', $__tm_from->getTimestamp());
		//dump($__tm_from, $__tm_to);
	
		$this->up_templater->assign('timemashine_header_from', $__tm_from->getTimestamp());
		$this->up_templater->assign('timemashine_header_to', $__tm_to->getTimestamp());
		
	
		$this->up_templater->assign('timemashine_startdate', systemFormatDateTime(date("Y-m-1 00:01:00"), TRUE));
		$this->up_templater->assign('timemashine_todaydate', systemFormatDateTime(NULL, TRUE));
		
		$this->up_templater->assign('timemashine_startdate_y', date("y"));
		$this->up_templater->assign('timemashine_startdate_m', date("m"));
		$this->up_templater->assign('timemashine_startdate_d', 1);
		$this->up_templater->assign('timemashine_startdate_h', 0);
		$this->up_templater->assign('timemashine_startdate_mm', 0);
		
		$this->up_templater->assign('timemashine_today_y', date("y"));
		$this->up_templater->assign('timemashine_today_m', date("m"));
		$this->up_templater->assign('timemashine_today_d', date("d"));
		$this->up_templater->assign('timemashine_today_h', date("H"));
		$this->up_templater->assign('timemashine_today_mm', date("i"));		
		
		// dates lent
		$this->load->helper('timemashine');
		$this->up_templater->assign('timemashine_dates_lent', getDateLent($this->user));
		//$this->up_templater->assign('timemashine_dates_lent', getNews($this->user));
	} // end of function 
	
    
    /**
     * Print add gallery form.
     *
     * @access public
     * @return page
     */
	function addgallery()
	{
		$this->addToTitle('Личный кабинет');		
		$this->addToTitle('Добавление галлереи');

		$_html = $this->up_templater->setBlock('proom/addgallery');
		$this->up_templater->assign('private_content', $_html);
		
		$this->up_templater->render('proom/privatedata');				
	}
	
    /**
     * Manage of gallery for registered user
     *
     * @access public
     * @return page
     */
	function managegallery()
	{
		$this->addToTitle('Личный кабинет');		
		$this->addToTitle('Управление галлереями');
					
		$this->load->model('Photogallery_model');
		$totalRecords = $this->Photogallery_model->getAllGalleryByUser();
			
		$this->up_templater->assign('user_gallerys', $totalRecords);
		$_html = $this->up_templater->setBlock('proom/listgallery');
		
		$this->up_templater->assign('private_content', $_html);
		
		$this->up_templater->render('proom/privatedata');				
	} // end of function 
	
    /**
     * List all users photos 
     *
     * @access public
     * @return page
     */
	function manageUserPhoto()
	{
		$this->addToTitle('Личный кабинет');		
		$this->addToTitle('Управление фотографиями');
		
		$_html = '';
		
		$array_photos = array();
		$requested_photo = $this->Photo_model->allNonGalleryPicturesByUser();
		
		if($requested_photo){
			$__tmp = array();
			foreach($requested_photo as $key=>$p)
			{
				$p['created_uf'] = systemFormatDateTime($p['created'], false);
				
				$__tmp = $this->Photo_model->getFileProperties($p['id']);
				$__tmp = array_merge($__tmp, $p);
				
				array_push($array_photos, $__tmp);
				$__tmp = array();
			}
		
			$this->up_templater->assign('user_photos', $array_photos);
		}
				
		$_html = $this->up_templater->setBlock('proom/managephoto');
		
		$this->up_templater->assign('private_content', $_html);
		
		$this->up_templater->render('proom/privatedata');
	} // end of function 
	
    /**
     * Check input data and insert new form in to DB.
     *
     * @access public
     * @return page
     */
	function tryaddgallery()
	{	
		$gallery_desc		= $this->input->post('gallerydescription');
		$privacy			= $this->input->post('privacy');
		
		if(!$privacy){$privacy = 'all';}		
		if($privacy != 'all' && $privacy != 'noany'){$privacy = 'all';}
		
		$create_datetime = date("Y-m-d H:i:s");
		$int_name = md5($this->user->id.$create_datetime.rand(1, 100000).$this->config->item('encryption_key'));
		$ext_table_data = array(
									'created'		=> $create_datetime,
									'user_id'		=> $this->user->id,
									'internal_name'	=> $int_name,
									'status'		=> 'active',
									'user_perm'		=> $privacy,
									'description'	=> $gallery_desc,
		);

		$folder_name = $this->config->item('images_users').'/'.$this->user->nickname.'/'.$int_name;
		
		if(!is_dir($folder_name)){
			if(!mkdir($folder_name)){
				$newdata = array('msg' => 'Filesystem error (mkdir)');
				$this->session->set_userdata($newdata);
				redirect(base_url().'home');
			}
		}
						
		$this->db->trans_start();
		$this->db->insert('gallery', $ext_table_data);
		$this->db->trans_complete();
	
		$newdata = array('msg' => 'Новая галлерея созданна.');
		$this->session->set_userdata($newdata);
		redirect(base_url().'managegallery');
	} // end of function 
		
    /**
     * show main form for edir gallery information 
     *
     * @access private
     * @return boolean is user authorized 
     */
	function editGallery($_param)
	{		
	
		$this->addToTitle('Личный кабинет');		
		$this->addToTitle('Управление галлереями');
		$this->addToTitle('Редактирование галлереи');
		
		$_ret = array();
		if(!$_param){redirect(base_url().'home');}
		
		$_param = substr($_param, 0, 32);
		
		$this->load->model('Photogallery_model');
		$gallery = new Photogallery_model(); 
		$gallery->getByINternalName($_param);
		
		// gallery not found
		if(!$gallery->isDefinite()){redirect(base_url().'home');}
		
		// requested user not owner for this gallery
		if($gallery->user_id != $this->user->id){redirect(base_url().'home');}
		
		$photo_arr = $gallery->getPhotos();

		if($gallery->user_perm == 'noany'){
			$this->up_templater->assign('user_perm_noany', 'checked="checked"');
		} else {
			$this->up_templater->assign('user_perm_all', 'checked="checked"');
		}
		
		$this->up_templater->assign('curgallery', $gallery->internal_name);		
		
		$this->load->model('Comment_model');
		foreach($photo_arr as $row)
		{						
			$row['edit_url']		= '<a href="'.base_url().'editphoto/'.$row['internal_name'].'">'.'<img src="'.base_url().'images/edit.png'.'" />'.'</a>';
			$row['delete_url']		= '<a href="'.base_url().'deletephoto/'.$row['internal_name'].'">'.'<img src="'.base_url().'images/delete.png'.'" />'.'</a>';

			$row['comments']		= $this->Comment_model->getTotalCommentByEntityID($row['id']);
			
			$row['picture']	= base_url().$this->config->item('images_users').'/'.$this->user->nickname.'/'.$gallery->internal_name.'/'.$this->config->item('thumb_prefix').$row['internal_name'].$row['extension'];
			array_push($_ret, $row);			
		}
		
		$_html = '';
		
		$this->up_templater->assign('countphotos', count($_ret));
		$this->up_templater->assign('photo_arr', $_ret);
		
		$this->up_templater->assign('gallery', $gallery);
		
		
		$_html = $this->up_templater->setBlock('proom/simplelistphoto');
		$this->up_templater->assign('private_content', $_html);
		
		$this->up_templater->render('proom/privatedata');		
	} // end of function 
	
	function saveGallery()
	{
		$_gallery_internal_name = $this->input->post('curgallery');
		$_privacy				= $this->input->post('privacy');
		$_gallerydescription	= trim(strip_tags($this->input->post('gallerydescription')));
		
		if($_privacy != 'all' && $_privacy != 'noany'){$_privacy = 'all';}
				
		// 1. check if for posted gallery user is owner
		$this->load->model('Photogallery_model');
		$gallery = new Photogallery_model(); 
		$gallery->getByINternalName($_gallery_internal_name);		
		if(!$gallery->isDefinite()){redirect(base_url().'home');}
		if($this->user->id != $gallery->user_id){redirect(base_url().'home');}
		
		// 2. save information about gallery
		$data = array('user_perm'=>$_privacy, 'description'=>$_gallerydescription);	
		
		// there need add logic for change user_perm for all images in to gallery 
		$photos = $gallery->getPhotos();
		foreach($photos as $photo){
			$data = array('user_perm' => $_privacy);
			$this->db->where('id', $photo['id']);
			$this->db->update('photo', $data);
		}

		$this->db->where('id', $gallery->id);
		$this->db->update($gallery->table_name, $data);
		
		$newdata = array('msg' => 'Изменения сохранены.');
		$this->session->set_userdata($newdata);
		
		redirect(base_url().'editgallery/'.$gallery->internal_name);
	}
		
    /**
     * Prepare json data for grid with gallery information 
     *
     * @access public
     * @return page
     */
	function deleteGallery($_param=NULL)
	{
		if(!$_param){redirect(base_url().'home');}
				
		$_param = substr($_param, 0, 32);
		
		// try to find needed gallery 		
		$this->load->model('Photogallery_model');
		$gallery = new Photogallery_model(); 
		$gallery->getByINternalName($_param);
		
		// gallery not found
		if(!$gallery->isDefinite()){
			redirect(base_url().'home');
		}
		
		// requested user not owner for this gallery
		if($gallery->user_id != $this->user->id){
			redirect(base_url().'home');
		}		
		
		$__count = count($gallery->getPhotos());
		$gallery->delete();
		
		$newdata = array('msg' => 'Галлерея (<b>'.get_shortened($gallery->description, 10).'</b>) и все ее фотографии (<b>'.$__count.'</b>) была удалена.');
		$this->session->set_userdata($newdata);
		redirect(base_url().'managegallery');
	}
	
    /**
     * show main form for edir gallery information 
     *
     * @access private
     * @return boolean is user authorized 
     */
	function editPhoto($_param)
	{
		$this->addToTitle('Личный кабинет');		
		$this->addToTitle('Редактирование фотографии');
	
		if(!$_param){redirect(base_url().'home');}
				
		$_param = substr($_param, 0, 32);
		
		// try to find needed gallery 		
		$photo = new Photo_model(); 
		$photo->getByINternalName($_param);
		
		// gallery not found
		if(!$photo->isDefinite()){redirect(base_url().'home');}
		
		// requested user not owner for this gallery
		if($photo->user_id != $this->user->id){redirect(base_url().'home');}
		
		if($photo->user_perm == 'noany'){
			$this->up_templater->assign('user_perm_noany', 'checked="checked"');
		} else {
			$this->up_templater->assign('user_perm_all', 'checked="checked"');
		}
		
		$this->load->model('Photogallery_model');
		$this->Photogallery_model->fetchByID($photo->gallery_id);		
		
		$this->up_templater->assign('img', base_url().$this->config->item('images_users').'/'.$this->user->nickname.'/'.$this->Photogallery_model->internal_name.'/'.$photo->internal_name.$photo->extension);
		$this->up_templater->assign('photo', $photo);
		
		$this->load->model('Comment_model');
		$count_comm = $this->Comment_model->getTotalCommentByEntityID($photo->id);
		
		//dump($this->Photogallery_model->id, $count_comm);
		$this->up_templater->assign('count_comments', $count_comm);

		//////////////////  comments ///////////////////
		$comment_block = '';
		if($count_comm > 0 ){
			// get all comments
			$comments = array();
			$comments = $this->Comment_model->getAllComments($photo->id);
			$comment_block = '<br/>Все комментарии:';
			
			$u = new User_model();
			foreach($comments as $c)
			{
				$u->fetchByID($c['user_id']);
				
				$this->up_templater->assign('commentorname', $u->nickname);
				$this->up_templater->assign('commentbody', $c['body']);
				$this->up_templater->assign('comment_date', $c['created']);
				$this->up_templater->assign('comment_id', $c['id']);
				
				$this->up_templater->assign('useravatar', base_url().$this->config->item('preset_avatar_folder').'/'.$u->avatar);
				
				$this->up_templater->assign('deletecomment', 1);
				
				$comment_block .= $this->up_templater->setBlock('proom/commentblock');
			}			
		}
		
		$this->up_templater->assign('comments', $comment_block);
		
		$_html = $this->up_templater->setBlock('proom/edit_photo_page');
		$this->up_templater->assign('private_content', $_html);
		
		$this->up_templater->render('proom/privatedata');
	} // end of function 
	
	
    /**
     * Delete comment by id 
     *
     * @access public
     * @return page
     */
	function deleteComment($_param = NULL)
	{
		if(!$_param){redirect(base_url().'home');}
		
		if (is_int((int)$_param)){
			
			$this->load->model('Comment_model');
			$this->Comment_model->fetchByID($_param);
			
			if($this->Comment_model->isDefinite()){
				
				$this->Photo_model->fetchByID($this->Comment_model->photo_id);
				if($this->Photo_model->isDefinite()){					
					if($this->Photo_model->user_id == $this->user->id){						
						$this->db->delete('comments', array('id' => $_param));
						
						$newdata = array('msg' => 'Комментарий был удален');
						$this->session->set_userdata($newdata);
						
						redirect(base_url().'editphoto/'.$this->Photo_model->internal_name);
					} else {
						redirect(base_url().'home');
					}
				} else {
					redirect(base_url().'home');
				}
			} else {
				redirect(base_url().'home');
			}
		} else {
			redirect(base_url().'home');
		}
	} // end of function 
	
    /**
     * Save information about photo.
     *
     * @access public
     * @return page
     */
	function savePhotoInformation()
	{	
		$_photo_internal_name	= $this->input->post('curphoto');
		$_photo_desc			= trim(strip_tags($this->input->post('photodescription')));
		$_privacy				= $this->input->post('privacy');
		
		// 1. check if for posted gallery user is owner
		$photo = new Photo_model(); 
		$photo->getByINternalName($_photo_internal_name);		
		if(!$photo->isDefinite()){redirect(base_url().'home');}
		if($this->user->id != $photo->user_id){redirect(base_url().'home');}
		
		if(!$_privacy){$_privacy = 'all';}		
		if($_privacy != 'all' && $_privacy != 'noany'){$_privacy = 'all';}
	
		$data = array('user_perm'=>$_privacy, 'description'=>$_photo_desc);	

		$this->db->where('id', $photo->id);
		$this->db->update($photo->table_name, $data);
		
		$newdata = array('msg' => 'Изменения сохранены');
		$this->session->set_userdata($newdata);
		
		if($photo->gallery_id)
		{		
			$this->load->model('Photogallery_model');
			$this->Photogallery_model->fetchByID($photo->gallery_id);		
			redirect(base_url().'editgallery/'.$this->Photogallery_model->internal_name);
		} else {
			redirect(base_url().'managephoto');
		}
	}
	
    /**
     * Prepare json data for grid with gallery information 
     *
     * @access public
     * @return page
     */
	function addPhotoPage($_param=NULL)
	{
		$this->addToTitle('Личный кабинет');		
		$this->addToTitle('Добавление фотографии');
				
		if($_param){
			$_param = substr($_param, 0, 32);
			// try to find needed gallery 		
			$this->load->model('Photogallery_model');
			$gallery = new Photogallery_model();
			$gallery->getByINternalName($_param);
			// gallery not found
			if(!$gallery->isDefinite()){
				redirect(base_url().'home');
			}
		
			// requested user not owner for this gallery
			if($gallery->user_id != $this->user->id){
				redirect(base_url().'home');
			}
			
			$this->up_templater->assign('gallery_internal_name', $gallery->internal_name);
			
		} 

		$_html = $this->up_templater->setBlock('proom/upload_one_file_reg');
		$this->up_templater->assign('private_content', $_html);
		
		$this->up_templater->render('proom/privatedata');
	} // end of function 
	
    /**
     * Prepare json data for grid with gallery information 
     *
     * @access public
     * @return page
     */
	function deletePhoto($_param=NULL)
	{
		
		if(!$_param){redirect(base_url().'home');}
				
		$_param = substr($_param, 0, 32);
		
		// try to find needed gallery 		
		$photo = new Photo_model(); 
		$photo->getByINternalName($_param);
		
		// gallery not found
		if(!$photo->isDefinite()){redirect(base_url().'home');}
		
		// requested user not owner for this gallery
		if($photo->user_id != $this->user->id){redirect(base_url().'home');}
				
		if($photo->gallery_id){
			$this->load->model('Photogallery_model');
			$gallery = new Photogallery_model($photo->gallery_id);			
			$back_url = base_url().'editgallery/'.$gallery->internal_name;
		} else {
			$back_url = base_url().'managephoto';
		}
		
		if($photo->deletePhoto()){
			$newdata = array('msg' => 'Фотография была удалена');
			$this->session->set_userdata($newdata);
			redirect($back_url);
		} else {
			redirect(base_url().'home');
		}
	} // end of function 
	
	
    /**
     * Prepare json data for grid with gallery information 
     *
     * @access public
     * @return page
     */
	function getGalleryList()
	{
		try {
			$curPage		= $this->input->post('page');
			$rowsPerPage	= $this->input->post('rows');
			$sortingField	= $this->input->post('sidx');
			$sortingOrder	= $this->input->post('sord');
			
			$this->load->model('Photogallery_model');
			//$this->load->model('Photo_model');
			$totalRows = $this->Photogallery_model->getTotalGallery($this->user->id);

			$firstRowIndex = $curPage * $rowsPerPage - $rowsPerPage;
			
			$rows = $this->Photogallery_model->getByParams($sortingField, $sortingOrder, $firstRowIndex, $rowsPerPage, $this->user->id);
						
			//$res = $dbh->query('SELECT * FROM users ORDER BY '.$sortingField.' '.$sortingOrder.' LIMIT '.$firstRowIndex.', '.$rowsPerPage);
						
			$response = new STDClass();
			
			$response->page = $curPage;
			$response->total = ceil($totalRows['count'] / $rowsPerPage);
			$response->records = $totalRows['count'];

			$i=0;
			$datetime_format = $this->config->item('datetimeformat');
			foreach($rows as $row)
			{
				$response->rows[$i]['id']=$row['id'];
				
				$date = strftime($datetime_format, strtotime($row['created']));
				$descf = get_shortened($row['description'], 100);
				$count_photos = $this->Photo_model->getCountByGalleryID($row['id']);
				
				$response->rows[$i]['cell']=array($row['id'], $descf, $date, $row['user_perm'], $row['status'], 0, 'CRUD');
				$i++;
			}
			
			echo json_encode($response);
		}
		catch (PDOException $e) {
			echo 'Database error: '.$e->getMessage();
			die();
		}
		
		
	}
	
	
    /**
     * Save changed private data 
     *
     * @access public
     * @return page
     */
	function _tryToSavePrivateData()
	{
		$_ret = array();
		
		//dump($_POST);
		//$this->input->post('firstname');
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
		
		$pass = $this->input->post('pass');
		$cpass = $this->input->post('cpass');
		if($pass != "" && $cpass != ""){
			if(strlen($pass) < 3){
				$_ret['err_mess_pass'] = 'Длина пароля должна быть > 3';
			}
			
			if($cpass != $pass){
				$_ret['err_mess_pass'] = 'Пароли не совпадают';
			}
		}

		if(!$_ret){
			// need write new private data for user.
			$this->user->savePrivateData();
		}
	}
	
	
    /**
     * Manage of private data for registered user 
     *
     * @access public
     * @return page
     */
	function _insertJSCSS()
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
	
	}	
	
    /**
     * Manage of private data for registered user 
     *
     * @access public
     * @return page
     */
	function pdata()
	{
		$this->addToTitle('Личный кабинет');		
		$this->addToTitle('Управление личными данными');
		
		if($_POST){
			$this->_tryToSavePrivateData();
		}
	
		$this->up_templater->assign('val_nickname', $this->user->nickname);
		$this->up_templater->assign('val_email', $this->user->email);
		$this->up_templater->assign('val_fistname', $this->user->first_name);
		$this->up_templater->assign('val_lastname', $this->user->last_name);

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
		$_html = $this->up_templater->setBlock('proom/pdata');
		$this->up_templater->assign('private_content', $_html);
		
		$this->up_templater->render('proom/privatedata');
	} // end of function 
	
	
    /**
     * Manage for registered user owners social account information
     *
     * @access public
     * @return page
     */
	function manageSocialAccounts()
	{
		$this->addToTitle('Личный кабинет');		
		$this->addToTitle('Управление аккаунтами социальных сетей');
		
		$_html = '';
		//$_html = $this->up_templater->setBlock('proom/pdata');
		$this->up_templater->assign('private_content', $_html);
		
		$this->up_templater->render('proom/privatedata');		
	} // end of function 
	
	
    /**
     * Show some statistic information for user.  (read only information)
     *
     * @access public
     * @return page
     */
	function usersStatistics()
	{
		$this->addToTitle('Личный кабинет');
		$this->addToTitle('Статистика');
		
		$_html = '';
		//$_html = $this->up_templater->setBlock('proom/pdata');
		$this->up_templater->assign('private_content', $_html);
		
		$this->up_templater->render('proom/privatedata');
	} // end of function 
	
	
	
	
} // end of class 

/* End of file DefaultFrontEnd.php */
/* Location: ./application/controllers/DefaultFrontEnd.php */