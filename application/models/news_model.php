<?php
class News_model extends UP_Model
{

    function __construct($id=NULL)
	{	
		$this->table_name = 'news';
        parent::__construct($this->table_name,$id);		
		
		// PK Index definition
        $this->primary_key = 'id';
        $this->insert_index('date_start');
        $this->insert_index('date_stop');
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
            'column' => 'datestart',
            'type'   => 'datetime',
            "value"  => $this->mysql_now_datetime(),
			'calendar_options'	=> 'format: "'.$this->get_datetime_format().'"',
            'read'   => 0,
            'update' => 0,
			'display'	=> 'Дата начала показа',
        ));
		
        $this->insert_field(array(
            'column' => 'datestop',
            'type'   => 'datetime',
            "value"  => $this->mysql_now_datetime(),
			'calendar_options'	=> 'format: "'.$this->get_datetime_format().'"',
            'read'   => 0,
            'update' => 0,
			'display'	=> 'Дата окончания показа',
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
			"column" => "coordinate_id",
			"type"   => "tinyint",
            "null"   => 0,
			'write'  => 0,
            'value'  => '',
            'join'   => array(
                'mode'   => 'left',
                'table'  => 'coordinates',
                'column' => 'id',
            ),
		));
		
        $this->insert_field(array(
            'table'             => 'coordinates',
            'column'            => 'latitude',
            'type'              => 'varchar',
            'width'             => 20,
            'write'              => 0,
            'title'             => '',
            'read'              => 0,
            'null'              => 0,
            'info_field_txt'    => false,
            'info_field_ico'    => false,
            'virtual'           => true,
        ));
		
        $this->insert_field(array(
            'table'             => 'coordinates',
            'column'            => 'longitude',
            'type'              => 'varchar',
            'width'             => 20,
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
		
        $this->insert_where_condition(array(
            'name'     => 'created',
            'relation' => 'like',
			'display'	=> 'По дате создания',
        ));
		
        $this->insert_where_condition(array(
            'name'     => 'datestart',
            'relation' => 'like',
			'display'	=> 'По дате начала показа',
        ));
		
        $this->insert_where_condition(array(
            'name'     => 'datestop',
            'relation' => 'like',
			'display'	=> 'По дате окончания показа',
        ));
		
        $this->insert_where_condition(array(
            'name'     => 'body',
            'relation' => 'like',
			'display'	=> 'Текст новости',
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
											"created"			=> 'Созданна',
											"datestart"			=> 'Старт',
											"datestop"			=> 'Стоп',
											"body"		=> 'Тело новости',
											"radius"			=> 'UP Radius (км.)',
											"user_id"			=> 'Пользователь / nickname',
											"coordinates"		=> array(
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
								'width'		=> '150',
		);
		
		$row['datestart'] = array(
								'val'		=> systemFormatDateTime($row['datestart'], TRUE),
								'class'		=> 'tb-cnt',
								'width'		=> '150',
		);
		$row['datestop'] = array(
								'val'		=> systemFormatDateTime($row['datestop'], TRUE),
								'class'		=> 'tb-cnt',
								'width'		=> '150',
		);
		
		$tmp_user->fetchByID($row['user_id']);
		$row['radius'] = array(
								'val'		=> getUsersRadius($tmp_user->id),
								'class'		=> 'tb-cnt',
								'width'		=> '30',
		);
		
		$row['user_id'] = array(
									'val'	=> '<a href="'.base_url().'admin/edit/user/'.$row['user_id'].'">'.$tmp_user->first_name.' '.$tmp_user->last_name.' /&nbsp;<b>'.$row['user_nickname'].'</b></a>',
									'class'	=> 'tb-rht',
									'width'	=> '50',
		);
		
		$__coo_type = $this->config->item('displaycoordinatestandart');
		$__lo	= number_format($row['coordinates_longitude'], 14, '.', '');
		$__la	= number_format($row['coordinates_latitude'], 14, '.', '');
					
		if($__coo_type == "WGS84"){
			$_coord	= $__lo. ', '.$__la;
		} else {
			$__l	= converWGS2NAD($row['coordinates_longitude'], $row['coordinates_latitude']);
			$_coord = $__l['N']. 'N&nbsp;&nbsp;'.$__l['E'].'E';
		}
		
		$row['coordinates'] = array(
							'val'		=> '<a href="'.base_url().'admin/showcoord/'.$__lo.'/'.$__la.'">'.$_coord.'</a>',
							'class'		=> 'tb-rht',
							'width'		=> '140',
		);
		
		$color = 'Silver';
		if($this->isShowed()){
			$color = 'Lime';
		}
		
		$row['body'] = array(
								'val'		=> get_shortened(($row['body']), 150),
								'class'		=> 'tb-rht',
								'width'		=> '400',
								'color'		=> $color,
		);
		
		unset($row['coordinate_id']);
		unset($row['user_first_name']);
		unset($row['user_last_name']);
		unset($row['user_nickname']);
		unset($row['coordinates_latitude']);
		unset($row['coordinates_longitude']);

		return $row;
		
	} // end of function 
	
	
	function isShowed($_id = NULL)
	{	
		$_ret = FALSE;
		
		if($_id)
			$this->fetchByID($_id);
		
		$now		= new DateTime("now");
		$datestop	= new DateTime($this->datestop);
		$datestart	= new DateTime($this->datestart);
		
		if($datestart < $now && $now < $datestop)
			$_ret = TRUE;
		
		return $_ret;
		
	} // end of function
	
    /**
     * Override base method
     */
	function getByInterval($_from, $_to)
	{
		$ret = array();
		
		$this->db->select('field1, field2');
		$this->db->from($this->table_name);
		$this->db->where('datestart > ' . $_from . ' AND datestop <' . $_to);
		
		
		
		
		
		return $ret;
	} // end of function
	
	
	
    /**
     * Override base method
     */
	function getStatistic()
	{
		$ret = array();
		
		// total news
		$total = $this->db->count_all($this->table_name);
		$ret['total'] = $this->db->count_all($this->table_name);
		
		// active 
		//$active = $this->db->query('SELECT count(8) FROM '.$this->table_name.' WHERE ');
		$active = 23;
		$ret['active'] = $active;
		
		// suspended 
		$ret['suspended'] = $total - $active;
				
		return $ret;
	} // end of function 
		
    /**
     * Override base method
     */
	function _postProcess($__ret)
	{	
		$tmp_user = new User_model;
		
		foreach($__ret as $_i=>$_r)
		{
			// fetch user by id and prepare UF name
			$tmp_user->fetchByID($_r['user_id']);
			$__ret[$_i]['user_id'] = '<a href="'.base_url().'admin/edit/user/'.$_r['user_id'].'">'.$tmp_user->first_name.' '.$tmp_user->last_name.'</a>';
			
			
			// strip body 
			$__ret[$_i]['body'] = get_shortened($_r['body'], 100);
		}
	
	
		return $__ret;
	} // end of function 
	
} // end of class