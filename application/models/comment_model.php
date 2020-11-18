<?php

define('CT_PICUP_COMMENT_STATUS_ACTIVE', 'active');
define('CT_PICUP_COMMENT_STATUS_SUSPENDED', 'suspended');
define('CT_PICUP_COMMENT_STATUS_CLOSED', 'closed');


class Comment_model extends UP_Model {

    function __construct($id=NULL)
	{	
		$this->table_name = 'comments';
        parent::__construct($this->table_name,$id);
		
		// PK Index definition
        $this->primary_key = 'id';
        $this->insert_index('created');
        $this->insert_index('user_id');
		
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
            'column'    => 'body',
            'type'      => 'text',
			'value'     => '',
			'display'	=> 'Тело новости',
        ));
		
		
		$this->insert_field(array(
			"column" => "photo_id",
			"type"   => "tinyint",
            "null"   => 0,
			'write'  => 0,
            'value'  => '',
            'join'   => array(
                'mode'   => 'left',
                'table'  => 'photo',
                'column' => 'id',
            ),
            'validate'  => 'custom_userid_validate',
		));
		
        $this->insert_field(array(
            'table'             => 'photo',
            'column'            => 'name',
            'type'              => 'varchar',
            'width'             => 100,
            'write'              => 0,
            'title'             => '',
            'read'              => 0,
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
            'validate'  => 'custom_userid_validate',
		));
		
        $this->insert_field(array(
            'table'             => 'gallery',
            'column'            => 'name',
            'type'              => 'varchar',
            'width'             => 100,
            'write'              => 0,
            'title'             => '',
            'read'              => 0,
            'null'              => 0,
            'info_field_txt'    => false,
            'info_field_ico'    => false,
            'virtual'           => true,
        ));
		
        $this->insert_field(array(
            'column' => "status",
            'type'   => "enum",
            'width'  => 1,
            'values' => array(
                CT_PICUP_COMMENT_STATUS_ACTIVE		=> "Открыт",
				CT_PICUP_COMMENT_STATUS_SUSPENDED	=> "Прикрыт",
				CT_PICUP_COMMENT_STATUS_CLOSED		=> "Закрыт",
            ),
            'value'  => CT_PICUP_USER_STATUS_ACTIVE,
            'null'   => 0,
            'info_field_txt' => FALSE,
            'info_field_ico' => FALSE,
			'dom_width'  => 'width:40px;',
			'display'	=> 'Статус пользователя',
        ));
		
        $this->insert_where_condition(array(
            'name'     => 'created',
            'relation' => 'like',
			'display'	=> 'Дата создания',
        ));
		
        $this->insert_where_condition(array(
            'name'     => 'body',
            'relation' => 'like',
			'display'	=> 'Дата создания',
        ));
		
        $this->insert_where_condition(array(
            'name'     => 'photo_name',
            'relation' => 'like',
			'display'	=> 'Дата создания',
        ));
		
        $this->insert_where_condition(array(
            'name'     => 'user_nickname',
            'relation' => 'like',
			'display'	=> 'По nickname',
        ));
		
        $this->insert_where_condition(array(
            'name'     => 'user_first_name',
            'relation' => 'like',
			'display'	=> 'По имени пользователя',
        ));
		
        $this->insert_where_condition(array(
            'name'     => 'user_last_name',
            'relation' => 'like',
			'display'	=> 'По фамилии пользователя',
        ));
		
		$this->default_order_by = 'created DESC';
		
		$this->display_fields_name = array(
											"id"				=> '#',
											"created"			=> 'Дата создания',
											"body"				=> 'Тело комментария',
											"status"			=> 'Статус',
											"user"				=> 'Nickname',
											"photo_name"		=> 'К фотографии',
											"gallery_name"		=> 'К галлерее',
										   );
		
		
    } // end of function 
	
    /**
     * Call base method for output for adit/add
     */
    function write($fields = null)
    {
		$row = parent::write();
		
		$coordinates_obj	= new Coordinates_model;
		$tmp_user	= new User_model;
		
		$row['id'] = array(
							'val'		=> $row['id'],
							'class'		=> 'tb-cnt',
							'width'		=> '10',
		);
		
		$row['created'] = array(
								'val'		=> systemFormatDateTime($row['created'], TRUE),
								'class'		=> 'tb-cnt',
								'width'		=> '100',
		);
		
		$tmp_user	= new User_model;
		$tmp_user->fetchByID($row['user_id']);
		$row['user'] = array(
									'val'	=> '<a href="'.base_url().'admin/edit/user/'.$row['user_id'].'">'.$tmp_user->first_name.' '.$tmp_user->last_name.' /&nbsp;<b>'.$row['user_nickname'].'</b></a>',
									'class'	=> 'tb-rht',
									'width'	=> '100',
									'color' => $tmp_user->getColorByUserType(),
		);
		
		// status /////////////////////////////////////////////////////
		if($row['status'] == CT_PICUP_COMMENT_STATUS_ACTIVE)
			$_color = 'lime';
		
		if($row['status'] == CT_PICUP_COMMENT_STATUS_SUSPENDED)
			$_color = 'Yellow';
			
		if($row['status'] == CT_PICUP_COMMENT_STATUS_CLOSED)
			$_color = 'Gray';
		
		$row['status'] = array(
								'val'		=> $row['status'],
								'class'		=> 'tb-cnt',
								'color'		=> $_color,
								'width'		=> '15',
		);
		
		$row['photo_name'] = array(
							'val'		=> get_shortened(($row['photo_name']), 50),
							'class'		=> 'tb-cnt',
							'width'		=> '50',
		);
		
		$row['body'] = array(
							'val'		=> get_shortened(($row['body']), 100),
							'class'		=> 'tb-cnt',
							'width'		=> '130',
		);
		
		
		
		unset($row['user_id']);	
		
		unset($row['photo_id']);	
		unset($row['gallery_id']);	
		
		unset($row['user_first_name']);	
		unset($row['user_last_name']);	
		unset($row['user_nickname']);	
		
//		dump($row);
		return $row;
	} // end of function
	
	
	function getTotalCommentByEntityID($_entity_id, $_mode="photo")
	{
		if($_mode == 'photo'){$_f = 'photo_id';} else {$_f = 'gallery_id';}
		$query = $this->db->get_where($this->table_name, array($_f => $_entity_id));
		return($this->db->affected_rows());
	} // end of function 		
	
	function getAllComments($_entity_id, $_mode="photo")
	{
		$_ret = array();
		
		if($_mode == 'photo'){$_f = 'photo_id';} else {$_f = 'gallery_id';}
		
		$this->db->order_by("created", "desc"); 
		$query = $this->db->get_where($this->table_name, array($_f => $_entity_id));
		return $query->result_array();
	} // end of function 
	
} // end of class