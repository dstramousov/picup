<?php
class Place_model extends UP_Model
{
    function __construct($id=NULL)
	{	
		$this->table_name = 'place';
        parent::__construct($this->table_name,$id);
		
		// PK Index definition
        $this->primary_key   = 'id';
        $this->insert_index('created');
        $this->insert_index('name');
		
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
			"column" => "coord_id",
			"type"   => "tinyint",
            "null"   => 0,
			'write'  => 0,
            'value'  => '',
            'join'   => array(
                'mode'   => 'left',
                'table'  => 'coordinates',
                'column' => 'id',
            ),
            'validate'  => 'custom_userid_validate',
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
            'column'    => 'name',
            'type'      => 'varchar',
            'width'     => 500,
			'value'     => '',
			'display'	=> 'Название',
        ));
		
		
		$this->insert_field(array(
			"column" => "placetype_code",
			"type"   => "varchar",
            "null"   => 0,
			'write'  => 0,
            'value'  => '',
            'join'   => array(
                'mode'   => 'left',
                'table'  => 'placetype',
                'column' => 'code',
            ),
            'validate'  => 'custom_userid_validate',
		));
		
        $this->insert_field(array(
            'table'             => 'placetype',
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
            'table'             => 'placetype',
            'column'            => 'code',
            'type'              => 'varchar',
            'width'             => 12,
            'write'              => 0,
            'title'             => '',
            'read'              => 0,
            'null'              => 0,
            'info_field_txt'    => false,
            'info_field_ico'    => false,
            'virtual'           => true,
        ));
		
        $this->insert_field(array(
            'table'             => 'placetype',
            'column'            => 'icon',
            'type'              => 'char',
            'width'             => 20,
            'write'              => 0,
            'title'             => '',
            'read'              => 0,
            'null'              => 0,
            'info_field_txt'    => false,
            'info_field_ico'    => false,
            'virtual'           => true,
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
            'name'     => 'name',
            'relation' => 'like',
			'display'	=> 'Название места',
        ));
		
        $this->insert_where_condition(array(
            'name'     => 'placetype_name',
            'relation' => 'like',
			'display'	=> 'Тип места',
        ));				
		
		$this->default_order_by = 'created DESC';
		
		$this->display_fields_name = array(
											"id"				=> '#',
											"created"			=> 'Дата загрузки',
											"name"		=> 'Название места',
											"placetype_name"		=> 'Тип места',
											"user_id"			=> 'Пользователь / nickname',
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
		
		$tmp_user	= new User_model;
		$tmp_user->fetchByID($row['user_id']);
		$row['user_id'] = array(
								'val'	=> '<a href="'.base_url().'admin/edit/user/'.$row['user_id'].'">'.$tmp_user->first_name.' '.$tmp_user->last_name.' /&nbsp;<b>'.$row['user_nickname'].'</b></a>',
								'class'	=> 'tb-rht',
								'width'	=> '50',
		);
				
		$row['name'] = array(
								'val'		=> get_shortened(($row['name']), 150),
								'class'		=> 'tb-rht',
								'width'		=> '300',
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
				
		// placetype  ///////////////////////////////////////////
		
		/////////////////////////////////////////////////////////
				
		unset($row['placetype_id']);
		unset($row['coord_id']);
		unset($row['user_first_name']);
		unset($row['user_last_name']);
		unset($row['user_nickname']);
		unset($row['placetype_icon']);
		unset($row['placetype_code']);		
		
		unset($row['coordinates_latitude']);
		unset($row['coordinates_longitude']);
		
		return $row;
	}
	
	function fetchPlaceInfoByCoordinate($__cordinate_id)
	{
		$_ret = array();
		
		if(!isset($__cordinate_id)){
			return $ret;
		}
		
		$query = $this->db->get_where($this->table_name, array('coord_id' => $__cordinate_id));
		if ($query->num_rows() > 0){
			$_ret = $query->row();	
		}
		
		return $_ret;
	} // end of function 
	
	
	function fetchPlacesByType($type = NULL)
	{
		$ret = FALSE;
		if(!$type){return $ret;}
		
		$query	= $this->get_select_query();
        $query->expand(array(
            'where'		=> $this->table_name.'.placetype_code='.$this->db->escape($type),
        ) );
		
		$res = $this->db->query($query->str());
		return $res->result_array();
	} // end of function
	
} // end of class