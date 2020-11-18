<?php

define('CT_PICUP_COORDINATES_TRUSTED_YES', 'yes');
define('CT_PICUP_COORDINATES_TRUSTED_NO', 'no');

class Coordinates_model extends UP_Model
{

    function __construct($id=NULL)
	{	
		$this->table_name = 'coordinates';
		
        parent::__construct($this->table_name,$id);
		
		// PK Index definition
        $this->primary_key   = 'id';
        $this->insert_index('user_id');
        $this->insert_index('latitude');
        $this->insert_index('longitude');
		
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
            'column' => "trusted",
            'type'   => "enum",
            'width'  => 1,
            'values' => array(
                CT_PICUP_COORDINATES_TRUSTED_YES	=> "Yes",
                CT_PICUP_COORDINATES_TRUSTED_NO		=> "No",
            ),
            'value'  => CT_PICUP_COORDINATES_TRUSTED_NO,
            'null'   => 0,
            'info_field_txt' => FALSE,
            'info_field_ico' => FALSE,
			'dom_width'  => 'width:40px;',
			'display'	=> 'Доверенная координата',
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
            'column'    => 'latitude',
            'type'      => 'varchar',
            'width'     => 20,
			'title'     => 'lng_label_sys_panel_color_border_class',
			'value'     => '',
            'validate'  => 'sanitize_string',
			'display'	=> 'Широта',
        ));
		
        $this->insert_field(array(
            'column'    => 'longitude',
            'type'      => 'varchar',
            'width'     => 20,
			'title'     => 'lng_label_sys_panel_color_border_class',
			'value'     => '',
            'validate'  => 'sanitize_string',
			'display'	=> 'Долгота',
        ));
		
        $this->insert_field(array(
            'column'    => 'altitude',
            'type'      => 'int',
            'width'     => 10,
			'title'     => 'lng_label_sys_panel_column',
			'value'     => 1,
            'validate'  => 'validate_int',
			'display'	=> 'Высота',
        ));
		
        $this->insert_field(array(
            'column'    => 'maxapproximation',
            'type'      => 'smallint',
            'width'     => 10,
			'title'     => 'lng_label_sys_panel_column',
			'value'     => 1,
            'validate'  => 'validate_int',
			'display'	=> 'Приближение на карте',
        ));
		
        $this->insert_where_condition(array(
            'name'     => 'created',
            'relation' => 'like',
			'display'	=> 'Дата создания',
        ));

        $this->insert_where_condition(array(
            'name'     => 'trusted',
            'relation' => 'like',
			'display'	=> 'Trusted',
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
            'name'     => 'user_nickname',
            'relation' => 'like',
			'display'	=> 'Nickname',
        ));
		
        $this->insert_where_condition(array(
            'name'     => 'altitude',
            'relation' => 'like',
			'display'	=> 'Высота',
        ));
				
		$this->default_order_by = 'created DESC';
		
		$this->display_fields_name = array(
											"id"				=> '#',
											"created"			=> 'Дата создания',
											"trusted"			=> 'Trusted',
											"user_id"			=> 'Пользователь / nickname',
											"place_name"		=> array(
																			'title'=>'Место', 
																			'order_by_sight'=>FALSE,
																		 ),
											"longitude"			=> array(
																			'title'=>$this->config->item('displaycoordinatestandart'), 
																			'order_by_sight'=>FALSE,
																		 ),
											"altitude"			=> 'Высота',
											"maxapproximation"	=> 'Приближение',
										   );
    } // end of function
	
    /**
     * Call base method for output for adit/add
     */
    function write_form($fields = null)
    {
		$h = parent::write_form($fields);
		
		$_block = $this->up_templater->setBlock('admin/coordinates/user_info.tpl');
		$this->up_templater->assign('user_info_content', $_block);				
		
		return $h;	
	} // end of function 
	
    /**
     * Call base method for output for adit/add
     */
    function write($fields = null)
    {
		$row = parent::write();
		
		$tmp_user	= new User_model;
		$place_obj	= new Place_model;
		
		$__coo_type = $this->config->item('displaycoordinatestandart');
		$__lo	= number_format($row['longitude'], 14, '.', '');
		$__la	= number_format($row['latitude'], 14, '.', '');
			
		if($__coo_type == "WGS84"){
			$_coord	= $__lo. ', '.$__la;
		} else {
			$__l	= converWGS2NAD($row['longitude'], $row['latitude']);
			$_coord = $__l['N']. 'N&nbsp;&nbsp;'.$__l['E'].'E';
		} // 
			
		$row['longitude'] = array(
									'val'		=> '<a href="'.base_url().'admin/showcoord/'.$__lo.'/'.$__la.'">'.$_coord.'</a>',
									'class'		=> 'tb-rht',
									'width'		=> '140',
		);
		
		$_place_data = $place_obj->fetchPlaceInfoByCoordinate($row['id']);
		
		if($_place_data){
			$place_name = get_shortened(($_place_data->name), 50);
		} else {
			$place_name = 'Неопределенное место';
		}
		$row['place_name'] = array(
									'val'	=> $place_name,
									'class'	=> 'tb-rht',
									'width'	=> '350',
		);
			
		$tmp_user->fetchByID($row['user_id']);
		$row['user_id'] = array(
									'val'	=> '<a href="'.base_url().'admin/edit/user/'.$row['user_id'].'">'.$tmp_user->first_name.' '.$tmp_user->last_name.' /&nbsp;<b>'.$row['user_nickname'].'</b></a>',
									'class'	=> 'tb-rht',
									'width'	=> '50',
									'color' => $tmp_user->getColorByUserType(),
		);
		
		$row['id'] = array(
							'val'		=> $row['id'],
							'class'		=> 'tb-cnt',
							'width'		=> '10',
		);
		
		$row['altitude'] = array(
								'val'		=> $row['altitude'].' '.endingsForm($row['altitude'], 'метр', 'метра', 'метров'),
								'class'		=> 'tb-rht',
								'width'		=> '20',
		);

		if($row['trusted'] == CT_PICUP_COORDINATES_TRUSTED_YES)
			$_color = 'lime';
		if($row['trusted'] == CT_PICUP_COORDINATES_TRUSTED_NO)
			$_color = 'Yellow';
		$row['trusted'] = array(
								'val'		=> $row['trusted'],
								'class'		=> 'tb-cnt',
								'width'		=> '20',
								'color'		=> $_color,
		);
		
		$row['created'] = array(
								'val'		=> systemFormatDateTime($row['created'], TRUE),
								'class'		=> 'tb-cnt',
								'width'		=> '150',
		);
		
		unset($row['latitude']);			
		unset($row['user_first_name']);	
		unset($row['user_last_name']);	
		unset($row['user_nickname']);	
		
//		dump($row);
		
		return $row;
    } // end of function 
	
} // end of class