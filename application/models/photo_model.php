<?php

define('CT_PICUP_PHOTO_STATUS_ACTIVE', 'active');
define('CT_PICUP_PHOTO_STATUS_SUSPENDED', 'suspended');
define('CT_PICUP_PHOTO_STATUS_CLOSED', 'closed');

define('CT_PICUP_PHOTO_USER_STATUS_ALL', 'all');
define('CT_PICUP_PHOTO_USER_STATUS_FRIENDS', 'friends');
define('CT_PICUP_PHOTO_USER_STATUS_NOANY', 'noany');

class Photo_model extends UP_Model {

    function __construct($id=NULL)
	{	
		$this->table_name = 'photo';
        parent::__construct($this->table_name,$id);
		
		// PK Index definition
        $this->primary_key   = 'id';
        $this->insert_index('created');
        $this->insert_index('user_id');
        $this->insert_index('place_id');
        $this->insert_index('gallery_id');
        $this->insert_index('name');
        $this->insert_index('status');
        $this->insert_index('user_perm');
        $this->insert_index('internal_name');
		
		// Fields definition
		$this->insert_field(array(
			"column" => "id",
			"type"   => "integer",
			"attr"   => "auto_increment",
            'value'  => '',			
		));
		
        $this->insert_field(array(
            'column' => 'created',
            'type'   => 'datetime',
            "value"  => $this->mysql_now_datetime(),
			'calendar_options'	=> 'format: "'.$this->get_datetime_format().'"',
            'read'   => 0,
            'update' => 0,
			'display'	=> 'Дата добавления',
        ));

		$this->insert_field(array(
			"column" => "user_id",
			"type"   => "tinyint",
            "null"   => 0,
			'write'  => 0,
            'value'  => '',
            'join'   => array(
                'mode'   => 'left',
                'table'  => 'user',
                'column' => 'id',
            ),
            'input'  => array(
                'type'          => 'select',
                'from'          => 'user',
                'data'          => 'id',
                'caption'       => 'name',
                'nonset_id'     => '',
                'nonset_name'   => '',
            ),
            'validate'  => 'custom_userid_validate',
		));
		
        $this->insert_field(array(
            'table'             => 'user',
            'column'            => 'first_name',
            'type'              => 'varchar',
            'width'             => 45,
            'title'             => '',
            'read'              => 0,
            'write'              => 0,
            'null'              => 0,
            'info_field_txt'    => false,
            'info_field_ico'    => false,
            'virtual'           => true,
        ));
		
        $this->insert_field(array(
            'table'             => 'user',
            'column'            => 'last_name',
            'type'              => 'varchar',
            'width'             => 45,
            'write'              => 0,
            'title'             => '',
            'read'              => 0,
            'null'              => 0,
            'info_field_txt'    => false,
            'info_field_ico'    => false,
            'virtual'           => true,
        ));
		
        $this->insert_field(array(
            'table'             => 'user',
            'column'            => 'nickname',
            'type'              => 'varchar',
            'width'             => 45,
            'write'              => 0,
            'title'             => '',
            'read'              => 0,
            'null'              => 0,
            'info_field_txt'    => false,
            'info_field_ico'    => false,
            'virtual'           => true,
        ));
		
		$this->insert_field(array(
			"column" => "place_id",
			"type"   => "tinyint",
            "null"   => 0,
			'write'  => 0,
            'value'  => '',
            'join'   => array(
                'mode'   => 'left',
                'table'  => 'place',
                'column' => 'id',
            ),
		));
		
        $this->insert_field(array(
            'table'             => 'place',
            'column'            => 'name',
            'type'              => 'varchar',
            'width'             => 500,
            'title'             => '',
            'read'              => 0,
            'write'              => 0,
            'null'              => 0,
            'info_field_txt'    => false,
            'info_field_ico'    => false,
            'virtual'           => true,
        ));
		
		$this->insert_field(array(
			"column" => "gallery_id",
			"type"   => "tinyint",
            "null"   => 0,
			'write'  => 0,
            'value'  => '',
            'join'   => array(
                'mode'   => 'left',
                'table'  => 'gallery',
                'column' => 'id',
            ),
		));
		
        $this->insert_field(array(
            'table'             => 'gallery',
            'column'            => 'internal_name',
            'type'              => 'char',
            'width'             => 32,
            'title'             => '',
            'read'              => 0,
            'write'              => 0,
            'null'              => 0,
            'info_field_txt'    => false,
            'info_field_ico'    => false,
            'virtual'           => true,
        ));
		
        $this->insert_field(array(
            'table'             => 'gallery',
            'column'            => 'name',
            'type'              => 'char',
            'width'             => 32,
            'title'             => '',
            'read'              => 0,
            'write'              => 0,
            'null'              => 0,
            'info_field_txt'    => false,
            'info_field_ico'    => false,
            'virtual'           => true,
        ));
		
		
        $this->insert_field(array(
            'column'    => 'name',
            'type'      => 'varchar',
            'width'     => 100,
			'value'     => '',
			'display'	=> 'Название ',
        ));
		
        $this->insert_field(array(
            'column'    => 'extension',
            'type'      => 'char',
            'width'     => 5,
			'value'     => '',
			'display'	=> 'Название ',
        ));
		
		
        $this->insert_field(array(
            'column' => "status",
            'type'   => "enum",
            'width'  => 1,
            'values' => array(
                CT_PICUP_PHOTO_STATUS_ACTIVE		=> "Доступна для показа",
				CT_PICUP_PHOTO_STATUS_SUSPENDED		=> "Временно закрыта",
				CT_PICUP_PHOTO_STATUS_CLOSED		=> "Закрыта администратором",
            ),
            'value'  => CT_PICUP_PHOTO_STATUS_ACTIVE,
            'null'   => 0,
            'info_field_txt' => FALSE,
            'info_field_ico' => FALSE,
			'dom_width'  => 'width:40px;',
			'display'	=> 'Статус фотографии',
        ));
		//dump($this);
		
        $this->insert_field(array(
            'column' => "user_perm",
            'type'   => "enum",
            'width'  => 1,
            'values' => array(
				CT_PICUP_PHOTO_USER_STATUS_ALL		=> "Для всех",
				CT_PICUP_PHOTO_USER_STATUS_FRIENDS	=> "Только для друзей",
				CT_PICUP_PHOTO_USER_STATUS_NOANY	=> "Ни для кого",
            ),
            'value'  => CT_PICUP_PHOTO_USER_STATUS_ALL,
            'null'   => 0,
            'info_field_txt' => FALSE,
            'info_field_ico' => FALSE,
			'dom_width'  => 'width:40px;',
			'display'	=> 'Статус фотографии',
        ));
		
        $this->insert_field(array(
            'column'    => 'description',
            'type'      => 'varchar',
            'width'     => 250,
            'write'      => 0,
			'value'     => '',
			'display'	=> 'Описание фотографии',
        ));
		
        $this->insert_field(array(
            'column'    => 'internal_name',
            'type'      => 'char',
            'width'     => 32,
            'write'      => 0,
			'value'     => '',
			'display'	=> 'Системное имя',
        ));
		
        $this->insert_where_condition(array(
            'name'     => 'created',
            'relation' => 'like',
			'display'	=> 'Дата создания',
        ));
		
        $this->insert_where_condition(array(
            'name'     => 'user_nickname',
            'relation' => 'like',
			'display'	=> 'Nickname',
        ));
		
        $this->insert_where_condition(array(
            'name'     => 'user_first_name',
            'relation' => 'like',
			'display'	=> 'Имя пользователя',
        ));
		
        $this->insert_where_condition(array(
            'name'     => 'user_last_name',
            'relation' => 'like',
			'display'	=> 'Фамилия пользователя',
        ));
		
        $this->insert_where_condition(array(
            'name'     => 'place_name',
            'relation' => 'like',
			'display'	=> 'По названию места',
        ));
		
        $this->insert_where_condition(array(
            'name'     => 'gallery_name',
            'relation' => 'like',
			'display'	=> 'По имени галлереи',
        ));
		
        $this->insert_where_condition(array(
            'name'     => 'name',
            'relation' => 'like',
			'display'	=> 'По имени фотографии',
        ));
		
        $this->insert_where_condition(array(
            'name'     => 'description',
            'relation' => 'like',
			'display'	=> 'По описанию',
        ));
		
        $this->insert_where_condition(array(
            'name'     => 'internal_name',
            'relation' => 'like',
			'display'	=> 'Системное имя',
        ));
		
		$this->default_order_by = 'created DESC';
		
		$this->display_fields_name = array(
											"id"				=> '#',
											"created"			=> 'Дата загрузки',
											"user_id"			=> 'Пользователь / nickname',
											"place_name"		=> 'Название места',
											"coordinates"			=> array(
																			'title'=>$this->config->item('displaycoordinatestandart'), 
																			'order_by_sight'=>FALSE,
																		 ),
											
											"gallery_name"		=> 'Галлерея',
											"name"				=> 'Название',
											"status"			=> 'Статус',
											"user_perm"			=> 'Пользовательский статус',
											"description"			=> 'Описание',
											"internal_name"	=> 'Системное имя',
										   );
    } // end of function 
	
    /**
     * Call base method for output for adit/add
     */
    function write($fields = null)
    {
		$row = parent::write();
		
		$place_obj	= new Place_model;
		$coordinates_obj	= new Coordinates_model;
		$tmp_user	= new User_model;
		
		$tmp_user->fetchByID($row['user_id']);
		if($tmp_user->isDefinite()){
			$row['user_id'] = array(
									'val'	=> '<a href="'.base_url().'admin/edit/user/'.$row['user_id'].'">'.$tmp_user->first_name.' '.$tmp_user->last_name.' /&nbsp;<b>'.$row['user_nickname'].'</b></a>',
									'class'	=> 'tb-rht',
									'width'	=> '50',
			);
		} else {
			$row['user_id'] = array(
									'val'	=> 'anonymous',
									'class'	=> 'tb-rht',
									'width'	=> '50',
			);
		}
				
		$row['id'] = array(
							'val'		=> $row['id'],
							'class'		=> 'tb-cnt',
							'width'		=> '10',
		);
		
		if($row['place_id']){
			$place_obj->fetchByID($row['place_id']);
			
			if($place_obj->coord_id){
				$coordinates_obj->fetchByID($place_obj->coord_id);
				
				$__coo_type = $this->config->item('displaycoordinatestandart');
				$__lo	= number_format($coordinates_obj->longitude, 14, '.', '');
				$__la	= number_format($coordinates_obj->latitude, 14, '.', '');
					
				if($__coo_type == "WGS84"){
					$_coord	= $__lo. ', '.$__la;
				} else {
					$__l	= converWGS2NAD($coordinates_obj->longitude, $coordinates_obj->latitude);
					$_coord = $__l['N']. 'N&nbsp;&nbsp;'.$__l['E'].'E';
				}
					
				$row['place_name'] = get_shortened(($row['place_name']), 50);
					
					
				$row['coordinates'] = array(
											'val'		=> '<a href="'.base_url().'admin/showcoord/'.$__lo.'/'.$__la.'">'.$_coord.'</a>',
											'class'		=> 'tb-rht',
											'width'		=> '140',
				);
			}
		} else {
			$row['place_name']	= 'Место не определенно';
			$row['coordinates']	= 'Координаты не определенны';
		}
		
		$row['created'] = array(
								'val'		=> systemFormatDateTime($row['created'], TRUE),
								'class'		=> 'tb-cnt',
								'width'		=> '150',
		);
		
		/*
		$tmp_user->fetchByID($row['user_id']);
		$row['user_nickname'] = array(
									'val'	=> '<a href="'.base_url().'admin/edit/user/'.$row['user_id'].'">'.'<b>'.$row['user_nickname'].'</b></a>',
									'class'	=> 'tb-cnt',
									'width'	=> '50',
									'color' => $tmp_user->getColorByUserType(),
		);
		*/
		
		// status ////////////////////////////////////////////////
		if($row['status'] == CT_PICUP_PHOTO_STATUS_ACTIVE)
			$_color = 'lime';
		if($row['status'] == CT_PICUP_PHOTO_STATUS_SUSPENDED)
			$_color = 'Yellow';
		if($row['status'] == CT_PICUP_PHOTO_STATUS_CLOSED)
			$_color = 'Gray';
		$row['status'] = array(
								'val'		=> $row['status'],
								'class'		=> 'tb-cnt',
								'color'		=> $_color,
		);
		// user_perm /////////////////////////////////////////////
		if($row['user_perm'] == CT_PICUP_PHOTO_USER_STATUS_ALL)
			$_color = 'lime';
		if($row['user_perm'] == CT_PICUP_PHOTO_USER_STATUS_FRIENDS)
			$_color = 'Yellow';
		if($row['user_perm'] == CT_PICUP_PHOTO_USER_STATUS_NOANY)
			$_color = 'Gray';
		$row['user_perm'] = array(
								'val'		=> $row['user_perm'],
								'class'		=> 'tb-cnt',
								'color'		=> $_color,
		);
		
		
		$row['description'] = get_shortened(($row['description']), 50);
		$row['name'] = get_shortened(($row['name']), 50);
		
		$row['gallery_name'] = array(
									'val'		=> '<a href="'.base_url().'admin/edit/gallery/'.$row['gallery_id'].'">'.get_shortened(($row['gallery_name']), 50).'</a>',
									'class'		=> 'tb-rht',
									'width'		=> '240',
		);
		
		unset($row['user_first_name']);
		unset($row['user_last_name']);
		unset($row['user_nickname']);
		unset($row['place_id']);
		unset($row['gallery_id']);
		unset($row['gallery_internal_name']);
		unset($row['extension']);
		
		return $row;		
	} // end of function 
	
	function deletePhoto()
	{
		$_ret = FALSE;
		
		// 1. delete file and thumb 		
		if($this->user_id)
		{
			$user = new User_model($this->user_id);			
			
			if($user->id != $this->user->id){
				// user try to delete file who is not owner
				return $_ret;
			}
						
			if($this->gallery_id)
			{			
				$this->load->model('Photogallery_model');
				$gallery = new Photogallery_model($this->gallery_id);
				
				$folder_file = $this->config->item('images_users').'/'.$this->user->nickname.'/'.$gallery->internal_name.'/';
			} else {
				$folder_file = $this->config->item('images_users').'/'.$this->user->nickname;
			}			
		} else {
			$folder_file = $this->config->item('images_anonymous').'/';
		}

		// folder not exist 
		if(!is_dir($folder_file)){return $_ret;}
		
		$file	= $folder_file.'/'.$this->internal_name.$this->extension;
		$thumb	= $folder_file.'/'.$this->config->item('thumb_prefix').$this->internal_name.$this->extension;
			
		// db remove 
		$this->db->delete($this->table_name, array('id' => $this->id)); 
		$this->db->delete('ext_links', array('internal_name' => $this->internal_name));
		$this->db->delete('comments', array('photo_id' => $this->id));
			
		// filesystem remove
		unlink($file);
		unlink($thumb);
		
		return TRUE;
	}
	
	function getFileProperties($_file_id = NULL)
	{
		$_ret = array();
		
		if($_file_id){
			$this->fetchByID($_file_id);
		}
		
		if($this->isDefinite()){
		
			if($this->user_id)
			{
				$user = new User_model($this->user_id);			
				
				if($this->gallery_id)
				{			
					$this->load->model('Photogallery_model');
					$gallery = new Photogallery_model($this->gallery_id);
					
					$folder_name = $this->config->item('images_users').'/'.$user->nickname.'/'.$gallery->internal_name.'/';
				} else {
					$folder_name = $this->config->item('images_users').'/'.$user->nickname.'/';
				}			
			} else {
				$folder_name = $this->config->item('images_anonymous').'/'.$this->foldername.'/';
			}
		
			$_ret['folder_name']	= $folder_name;
			$_ret['file_path']		= $folder_name.$this->internal_name.$this->extension;
			$_ret['thumb_path']		= $folder_name.$this->config->item('thumb_prefix').$this->internal_name.$this->extension;		
		} 
		
		return $_ret;
	}
	
	function getLastUploaded($__count)
	{
		$where = ' WHERE user_perm="all" AND status="active" ';
		$query = $this->db->query('SELECT *, LEFT(created,10) as foldername FROM '.$this->table_name.$where.' ORDER BY created DESC LIMIT '.$__count); 
		if ($query->num_rows() > 0)
		{
			return $query->result();
		}
		
		return NULL;
	} // end of function 
	
	
	function getTalked($__count)
	{		
		$this->db->select('*, count(*) as sum');
		$this->db->from('comments');
		$this->db->order_by("sum", "desc"); 
		$this->db->group_by("photo_id"); 				
		$this->db->where("photo_id !=''");
		
		
		$this->db->limit($__count);
		$query = $this->db->get();
				
		if ($query->num_rows() > 0)
		{			
			return $query->result();
		}
		return NULL;
	} // end of function 
	
	
	function getTopGrid($count = NULL)
	{
		$where = ' WHERE user_perm="all" AND status="active" ';
		
		if(!$count){
			$count = $this->config->item('top_grid_photo');
		}
		
		$query = $this->db->query('SELECT *, LEFT(created,10) as foldername FROM '.$this->table_name.$where.' ORDER BY countseeit DESC LIMIT '.$count);
		if ($query->num_rows() > 0)
		{
			return $query->result();
		}
		return NULL;
	}
	
	function getByINternalName($_name)
	{
		$_ret = NULL;
		$query = $this->db->query('SELECT *, LEFT(created,10) as foldername FROM '.$this->table_name.' WHERE internal_name='.$this->db->escape($_name).' LIMIT 1'); 
		
		if($query->result()){
			$_ret = $query->row();
			$this->_mapper($_ret);
		}

		return $_ret;
	}
	
	
	function getCountByGalleryID($gallery_id)
	{
		if(!$gallery_id){return 0;}
		
		$query = $this->db->get_where($this->table_name, array('gallery_id' => $gallery_id));
		return($this->db->affected_rows());
	}
	
	
	function getTodayUploadPictures()
	{
		$_ret = 0;
		$query = $this->db->query('SELECT COUNT(*) as count FROM '.$this->table_name.' WHERE created BETWEEN '.$this->db->escape(date("Y-m-d 00:00:00")).' AND '.$this->db->escape(date("Y-m-d 23:59:59")));
		$row = $query->row_array();
		return $row['count'];
	}
	
	function getTotalUploadPictures()
	{
		return $this->db->count_all($this->table_name);
	}
	
	
	function allNonGalleryPicturesByUser($_user_id = NULL)
	{
		$_ret = NULL;
		
		$__ID = $this->user->id;
		if($_user_id){
			$u = new User_model();
			$u->fetchByID($_user_id);
			$__ID = $u->id;
		}
		
		$query = $this->db->query('SELECT *, LEFT(created,10) as foldername FROM (`'.$this->table_name.'`) WHERE `user_id` = '.$__ID.'  AND `gallery_id` IS NULL ORDER BY `created` desc'); 
		
		if($query->result()){
			return $query->result_array();
		}

		return $_ret;
	}
	
	
	
	
}