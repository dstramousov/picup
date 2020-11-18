<?php

define('CT_PICUP_P_DESCRIPTION_STATUS_ACTIVE', 'active');
define('CT_PICUP_P_DESCRIPTION_STATUS_SUSPENDED', 'suspended');
define('CT_PICUP_P_DESCRIPTION_STATUS_CLOSED', 'closed');

class Placedescription_model extends UP_Model
{
    function __construct($id=NULL)
	{	
		$this->table_name = 'placedescription';
        parent::__construct($this->table_name, $id);
		
		// PK Index definition
        $this->primary_key = 'id';
        $this->insert_index('created');
		
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
            'column' => "status",
            'type'   => "enum",
            'width'  => 1,
            'values' => array(
                CT_PICUP_P_DESCRIPTION_STATUS_ACTIVE		=> "Доступно для показа",
				CT_PICUP_P_DESCRIPTION_STATUS_SUSPENDED		=> "Временно закрыта",
				CT_PICUP_P_DESCRIPTION_STATUS_CLOSED		=> "Закрыта администратором",
            ),
            'value'  => CT_PICUP_P_DESCRIPTION_STATUS_ACTIVE,
            'null'   => 0,
            'info_field_txt' => FALSE,
            'info_field_ico' => FALSE,
			'dom_width'  => 'width:40px;',
			'display'	=> 'Статус фотографии',
        ));
		
		
		
        $this->insert_field(array(
            'column'    => 'description',
            'type'      => 'varchar',
            'width'     => 500,
            'write'      => 0,
			'value'     => '',
			'display'	=> 'Описание фотографии',
        ));
		
        $this->insert_where_condition(array(
            'name'     => 'created',
            'relation' => 'like',
			'display'	=> 'По дате создания',
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

        $this->insert_where_condition(array(
            'name'     => 'place_name',
            'relation' => 'like',
			'display'	=> 'По названию места',
        ));

        $this->insert_where_condition(array(
            'name'     => 'description',
            'relation' => 'like',
			'display'	=> 'По тексту описания',
        ));
		
		$this->default_order_by = 'created DESC';
		
		$this->display_fields_name = array(
											"id"				=> '#',
											"created"			=> 'Дата создания',
											"description"		=> 'Описание',
											"status"			=> 'Статус',
											"user_id"			=> 'Пользователь / nickname',
											"place_name"		=> 'Название места',
										   );		
    } // end of function
	
    /**
     * Call base method for output for adit/add
     */
    function write($fields = null)
    {
		$row = parent::write();
		
		$tmp_user	= new User_model;
		
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
		
		// status ////////////////////////////////////////////////
		if($row['status'] == CT_PICUP_P_DESCRIPTION_STATUS_ACTIVE)
			$_color = 'lime';
		if($row['status'] == CT_PICUP_P_DESCRIPTION_STATUS_SUSPENDED)
			$_color = 'Yellow';
		if($row['status'] == CT_PICUP_P_DESCRIPTION_STATUS_CLOSED)
			$_color = 'Gray';
		$row['status'] = array(
								'val'		=> $row['status'],
								'class'		=> 'tb-cnt',
								'color'		=> $_color,
								'width'	=> '30',
		);
		
		$tmp_user->fetchByID($row['user_id']);
		$row['user_id'] = array(
								'val'	=> '<a href="'.base_url().'admin/edit/user/'.$row['user_id'].'">'.$tmp_user->first_name.' '.$tmp_user->last_name.' /&nbsp;<b>'.$row['user_nickname'].'</b></a>',
								'class'	=> 'tb-rht',
								'width'	=> '50',
								'color' => $tmp_user->getColorByUserType(),
		);		
		
		$row['description'] = array(
							'val'		=> get_shortened(($row['description']), 150),
							'class'		=> 'tb-cnt',
							'width'		=> '250',
		);
		
		unset($row['user_first_name']);
		unset($row['user_last_name']);
		unset($row['user_nickname']);
		unset($row['place_id']);
		
		return $row;
	} // end of function 
	
	
} // end of class