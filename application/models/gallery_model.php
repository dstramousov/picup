<?php

define('CT_PICUP_GALLERY_STATUS_ACTIVE', 'active');
define('CT_PICUP_GALLERY_STATUS_SUSPENDED', 'suspended');
define('CT_PICUP_GALLERY_STATUS_CLOSED', 'closed');

define('CT_PICUP_GALLERY_USER_STATUS_ALL', 'all');
define('CT_PICUP_GALLERY_USER_STATUS_FRIENDS', 'friends');
define('CT_PICUP_GALLERY_USER_STATUS_NOANY', 'noany');

class Gallery_model extends UP_Model
{
    function __construct($id=NULL)
	{	
		$this->table_name = 'gallery';
        parent::__construct($this->table_name,$id);
		
        $this->primary_key   = 'id';
        $this->insert_index('created');
        $this->insert_index('user_id');
        $this->insert_index('place_id');
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
            'column'    => 'name',
            'type'      => 'varchar',
            'width'     => 100,
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
            'name'     => 'name',
            'relation' => 'like',
			'display'	=> 'По имени галлереи',
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
											"name"		=> 'Название галлереи',
											"status"			=> 'Статус',
											"user_perm"			=> 'П. статус',
											"description"			=> 'Описание',
											"place_name"		=> 'Название места',
											"internal_name"			=> array(
																			'title'=>'Системное имя', 
																			'order_by_sight'=>FALSE,
																		 ),
											"coordinates"			=> array(
																			'title'=>$this->config->item('displaycoordinatestandart'), 
																			'order_by_sight'=>FALSE,
																		 ),
										   );
    } // end of function
	
    /**
     * Call base method for output for adit/add
     */
    function write($fields = null)
    {
		$row = parent::write();
		$tmp_user	= new User_model;
		$tmp_user->fetchByID($row['user_id']);
		
		$row['user_id'] = array(
							'val'	=> '<a href="'.base_url().'admin/edit/user/'.$row['user_id'].'">'.$tmp_user->first_name.' '.$tmp_user->last_name.' /&nbsp;<b>'.$row['user_nickname'].'</b></a>',
							'class'	=> 'tb-rht',
							'width'	=> '50',
		);
		
		$row['id'] = array(
							'val'		=> $row['id'],
							'class'		=> 'tb-cnt',
							'width'		=> '10',
		);
		
		$row['created'] = array(
								'val'		=> systemFormatDateTime($row['created'], TRUE),
								'class'		=> 'tb-cnt',
								'width'		=> '150',
		);
		
		$place_obj	= new Place_model;
		$coordinates_obj	= new Coordinates_model;
		
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
		
		// status ////////////////////////////////////////////////
		if($row['status'] == CT_PICUP_GALLERY_STATUS_ACTIVE)
			$_color = 'lime';
		if($row['status'] == CT_PICUP_GALLERY_STATUS_SUSPENDED)
			$_color = 'Yellow';
		if($row['status'] == CT_PICUP_GALLERY_STATUS_CLOSED)
			$_color = 'Gray';
		$row['status'] = array(
								'val'		=> $row['status'],
								'class'		=> 'tb-cnt',
								'color'		=> $_color,
		);
		// user_perm /////////////////////////////////////////////
		if($row['user_perm'] == CT_PICUP_GALLERY_USER_STATUS_ALL)
			$_color = 'lime';
		if($row['user_perm'] == CT_PICUP_GALLERY_USER_STATUS_FRIENDS)
			$_color = 'Yellow';
		if($row['user_perm'] == CT_PICUP_GALLERY_USER_STATUS_NOANY)
			$_color = 'Gray';
		$row['user_perm'] = array(
								'val'		=> $row['user_perm'],
								'class'		=> 'tb-cnt',
								'color'		=> $_color,
		);		
				
		$row['description'] = get_shortened(($row['description']), 50);
		$row['name'] = get_shortened(($row['name']), 50);
	
		unset($row['user_first_name']);
		unset($row['user_last_name']);
		unset($row['user_nickname']);
//		unset($row['user_id']);
		unset($row['place_id']);
		
		return $row;		
	} // end of function 
	
} // end of class