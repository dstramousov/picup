<?php
class Karma_model extends UP_Model
{
    function __construct($id=NULL)
	{	
		$this->table_name = 'karma';
        parent::__construct($this->table_name,$id);
		
		// PK Index definition
        $this->primary_key = 'id';
        $this->insert_index('updated');
		
		// Fields definition
		$this->insert_field(array(
			"column" => "id",
			"type"   => "integer",
			"attr"   => "auto_increment",
            'value'  => '',			
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
            'column'    => 'value',
            'type'      => 'int',
            'width'     => 7,
			'value'     => 0,
            'validate'  => 'validate_int',
        ));		
		
        $this->insert_field(array(
            'column' => 'updated',
            'type'   => 'datetime',
            "value"  => $this->mysql_now_datetime(),
			'calendar_options'	=> 'format: "'.$this->get_datetime_format().'"',
            'read'   => 0,
            'update' => 0,
			'display'	=> 'Дата изменения',
        ));
		
        $this->insert_where_condition(array(
            'name'     => 'updated',
            'relation' => 'like',
			'display'	=> 'По дате изменения',
        ));
		
        $this->insert_where_condition(array(
            'name'     => 'value',
            'relation' => 'like',
			'display'	=> 'По значению',
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
											"updated"			=> 'Дата обновления',
											"value"				=> 'UP Radius (км.)',
											"user_id"			=> 'Пользователь / nickname',
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
		
		$row['updated'] = array(
								'val'		=> systemFormatDateTime($row['updated'], TRUE),
								'class'		=> 'tb-cnt',
								'width'		=> '150',
		);
		
		$row['user_id'] = array(
									'val'	=> '<a href="'.base_url().'admin/edit/user/'.$row['user_id'].'">'.$row['user_first_name'].' '.$row['user_last_name'].' /&nbsp;<b>'.$row['user_nickname'].'</b></a>',
									'class'	=> 'tb-rht',
									'width'	=> '50',
		);
		
		unset($row['user_first_name']);
		unset($row['user_last_name']);
		unset($row['user_nickname']);
		
		return $row;
	} // end of function
	
    function getByUserID($id)
	{
		$_ret = false;

		if(!$id){return $_ret;}
	
		$query = $this->db->query('SELECT * FROM '.$this->table_name.' WHERE user_id='.$id.' LIMIT 1');
		$row = $query->row();
		$query->free_result();
			
	    return $row;
	} // end of function 
	
	
} // end of class