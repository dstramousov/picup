<?php

define('CT_PICUP_USER_STATUS_ACTIVE', 'active');
define('CT_PICUP_USER_STATUS_SUSPENDED', 'suspended');
define('CT_PICUP_USER_STATUS_CLOSED', 'closed');

define('CT_PICUP_USER_TYPE_NORMAL', 'normal');
define('CT_PICUP_USER_TYPE_SILVER', 'silver');
define('CT_PICUP_USER_TYPE_GOLD', 'gold');
define('CT_PICUP_USER_TYPE_PLATINUM', 'platinum');

define('CT_PICUP_USER_GENDER_MALE', 'male');
define('CT_PICUP_USER_GENDER_FEMALE', 'female');
define('CT_PICUP_USER_GENDER_UNSET', 'unset');


class User_model extends UP_Model {

    function __construct($id=NULL)
	{	
		$this->table_name = 'user';
        parent::__construct($this->table_name,$id);
		
		// PK Index definition
        $this->primary_key   = 'id';
        $this->insert_index('nickname');
        $this->insert_index('first_name');
        $this->insert_index('last_name');
		
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
            'column'    => 'nickname',
            'type'      => 'varchar',
            'width'     => 50,
			'value'     => '',
            'validate'  => 'validate_user_nickname',
			'display'	=> 'Nickname пользователя',
        ));
		
        $this->insert_field(array(
            'column'    => 'first_name',
            'type'      => 'char',
            'width'     => 50,
			'value'     => '',
			'display'	=> 'Имя',
        ));
		
        $this->insert_field(array(
            'column'    => 'last_name',
            'type'      => 'char',
            'width'     => 100,
			'value'     => '',
			'display'	=> 'Фамилия',
        ));
		
        $this->insert_field(array(
            'column'    => 'email',
            'type'      => 'varchar',
            'width'     => 100,
			'value'     => '',
			'display'	=> 'E-mail',
        ));
        
		
        $this->insert_field(array(
            'column' => "gender",
            'type'   => "enum",
            'width'  => 1,
            'values' => array(
                CT_PICUP_USER_GENDER_UNSET		=> "Не определен",
				CT_PICUP_USER_GENDER_MALE	    => "Мужской",
				CT_PICUP_USER_GENDER_FEMALE		=> "Женский",
            ),
            'value'  => CT_PICUP_USER_GENDER_UNSET,
            'null'   => 0,
            'info_field_txt' => FALSE,
            'info_field_ico' => FALSE,
			'dom_width'  => 'width:40px;',
			'display'	=> 'Пол',
        ));

		$this->insert_field(array(
			"column" => "relation_id",
			"type"   => "tinyint",
            "null"   => 0,
            'value'  => '',
            'join'   => array(
                'mode'   => 'left',
                'table'  => 'relations',
                'column' => 'id',
            ),
		));
		
        
		
        $this->insert_field(array(
            'column' => "status",
            'type'   => "enum",
            'width'  => 1,
            'values' => array(
                CT_PICUP_USER_STATUS_ACTIVE		=> "Активный",
				CT_PICUP_USER_STATUS_SUSPENDED	=> "Приостановлен",
				CT_PICUP_USER_STATUS_CLOSED		=> "Закрыт",
            ),
            'value'  => CT_PICUP_USER_STATUS_ACTIVE,
            'null'   => 0,
            'info_field_txt' => FALSE,
            'info_field_ico' => FALSE,
			'dom_width'  => 'width:40px;',
			'display'	=> 'Статус пользователя',
        ));

        $this->insert_field(array(
            'column' => "usertype",
            'type'   => "enum",
            'width'  => 1,
            'values' => array(
                CT_PICUP_USER_TYPE_NORMAL		=> "Normal",
                CT_PICUP_USER_TYPE_SILVER		=> "Silver",
                CT_PICUP_USER_TYPE_GOLD			=> "Gold",
                CT_PICUP_USER_TYPE_PLATINUM		=> "Vip",
            ),
            'value'  => CT_PICUP_USER_TYPE_NORMAL,
            'null'   => 0,
            'info_field_txt' => FALSE,
            'info_field_ico' => FALSE,
			'dom_width'  => 'width:40px;',
			'display'	=> 'Тип пользователя',
        ));
		
		$this->insert_field(array(
			"column" => "last_coordinate_id",
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
            'type'      		=> 'varchar',
            'width'     		=> 20,
            'title'             => '',
            'read'              => 0,
            'write'              => 0,
            'null'              => 0,
            'info_field_txt'    => false,
            'info_field_ico'    => false,
            'virtual'           => true,
        ));

        $this->insert_field(array(
            'table'             => 'coordinates',
            'column'            => 'longitude',
            'type'      		=> 'varchar',
            'width'     		=> 20,
            'title'             => '',
            'read'              => 0,
            'write'              => 0,
            'null'              => 0,
            'info_field_txt'    => false,
            'info_field_ico'    => false,
            'virtual'           => true,
        ));

        $this->insert_field(array(
            'column' => 'last_checkin',
            'type'   => 'datetime',
            "value"  => $this->mysql_now_datetime(),
			'calendar_options'	=> 'format: "'.$this->get_datetime_format().'"',
            'read'   => 0,
            'update' => 0,
			'display'	=> 'Дата последнего checkin',
        ));
		
        $this->insert_field(array(
            'column' => 'last_login',
            'type'   => 'datetime',
            "value"  => $this->mysql_now_datetime(),
			'calendar_options'	=> 'format: "'.$this->get_datetime_format().'"',
            'read'   => 0,
            'update' => 0,
			'display'	=> 'Дата последнего login',
        ));
		
        $this->insert_field(array(
            'column'    => 'last_ip',
            'type'      => 'char',
            'width'     => 100,
            'read'      => 0,
            'store'     => 0,
			'value'     => '',
			'display'	=> 'Последний пользуемый IP',
        ));
		
        $this->insert_field(array(
            'column'    => 'last_ip',
            'type'      => 'char',
            'width'     => 32,
            'write'      => 0,
			'value'     => '',
			'display'	=> 'Пароль',
        ));
		
        $this->insert_field(array(
            'column'    => 'avatar',
            'type'      => 'char',
            'width'     => 50,
            'write'      => 0,
			'value'     => '',
			'display'	=> 'Картинка пользователя',
        ));
		
        $this->insert_where_condition(array(
            'name'     => 'created',
            'relation' => 'like',
			'display'	=> 'Дата создания',
        ));
		
        $this->insert_where_condition(array(
            'name'     => 'nickname',
            'relation' => 'like',
			'display'	=> 'Nickname',
        ));
		
        $this->insert_where_condition(array(
            'name'     => 'first_name',
            'relation' => 'like',
			'display'	=> 'Имя',
        ));
		
        $this->insert_where_condition(array(
            'name'     => 'last_name',
            'relation' => 'like',
			'display'	=> 'Фамилия',
        ));
		
        $this->insert_where_condition(array(
            'name'     => 'email',
            'relation' => 'like',
			'display'	=> 'E-mail',
        ));
		
        $this->insert_where_condition(array(
            'name'     => 'last_checkin',
            'relation' => 'like',
			'display'	=> 'Дата последнего checkin',
        ));
		
        $this->insert_where_condition(array(
            'name'     => 'last_login',
            'relation' => 'like',
			'display'	=> 'Дата последнего login',
        ));
		
        $this->insert_where_condition(array(
            'name'     => 'last_ip',
            'relation' => 'like',
			'display'	=> 'Последний IP',
        ));
		
		
		$this->default_order_by = 'created DESC';
		
		$this->display_fields_name = array(
											"id"				=> '#',
											"created"			=> 'Дата создания',
											"user_id"			=> 'Пользователь / nickname',
											"upradius"		=> array(
																			'title'=>'UP Radius (км.)', 
																			'order_by_sight'=>FALSE,
																		 ),
											"email"				=> 'E-Mail',
											"status"			=> 'Статус',
											"usertype"				=> 'Тип пользователя',
											"last_coordinate_id"		=> array(
																			'title'=>'Последний checkin в', 
																			'order_by_sight'=>FALSE,
																		 ),
											"last_checkin"		=> 'Последнего checkin', 
											"last_login"			=> 'Последний вход',
											"last_ip"	=> 'Последний IP',
										   );
    } // end of function 
	
	
    /**
     * Call base method for output for adit/add
     */
    function write($fields = null)
    {
		$row = parent::write();
		
		$row['upradius'] = array(
							'val'		=> getUsersRadius($row['id']),
							'class'		=> 'tb-cnt',
							'width'		=> '20',
		);
		
		$row['user_id'] = array(
									'val'	=> '<a href="'.base_url().'admin/edit/user/'.$row['id'].'">'.$row['first_name'].' '.$row['last_name'].' /&nbsp;<b>'.$row['nickname'].'</b></a>',
									'class'	=> 'tb-rht',
									'width'	=> '50',
									'color' => $this->getColorByUserType(),
		);
		
		
		$row['id'] = array(
							'val'		=> $row['id'],
							'class'		=> 'tb-cnt',
							'width'		=> '10',
		);
		
		// coordinates
		$__coo_type = $this->config->item('displaycoordinatestandart');
		$__lo	= number_format($row['coordinates_longitude'], 14, '.', '');
		$__la	= number_format($row['coordinates_latitude'], 14, '.', '');
			
		if($__coo_type == "WGS84"){
			$_coord	= $__lo. ', '.$__la;
		} else {
			$__l	= converWGS2NAD($row['coordinates_longitude'], $row['coordinates_latitude']);
			$_coord = $__l['N']. 'N&nbsp;&nbsp;'.$__l['E'].'E';
		} 
			
		$row['last_coordinate_id'] = array(
									'val'		=> '<a href="'.base_url().'admin/showcoord/'.$__lo.'/'.$__la.'">'.$_coord.'</a>',
									'class'		=> 'tb-rht',
									'width'		=> '140',
		);
		
		$row['created'] = array(
								'val'		=> systemFormatDateTime($row['created'], TRUE),
								'class'		=> 'tb-cnt',
								'width'		=> '150',
		);
		
		$row['last_checkin'] = array(
								'val'		=> systemFormatDateTime($row['last_checkin'], TRUE),
								'class'		=> 'tb-cnt',
								'width'		=> '150',
		);

		$row['last_login'] = array(
								'val'		=> systemFormatDateTime($row['last_login'], TRUE),
								'class'		=> 'tb-cnt',
								'width'		=> '150',
		);
		
		// status /////////////////////////////////////////////////////
		if($row['status'] == CT_PICUP_USER_STATUS_ACTIVE)
			$_color = 'lime';
		
		if($row['status'] == CT_PICUP_USER_STATUS_SUSPENDED)
			$_color = 'Yellow';
			
		if($row['status'] == CT_PICUP_USER_STATUS_CLOSED)
			$_color = 'Gray';
		
		$row['status'] = array(
								'val'		=> $row['status'],
								'class'		=> 'tb-cnt',
								'color'		=> $_color,
		);
		
		// usertype ///////////////////////////////////////////////////
		if($row['usertype'] == CT_PICUP_USER_TYPE_NORMAL)
			$_color = NULL;
		if($row['usertype'] == CT_PICUP_USER_TYPE_SILVER)
			$_color = 'Silver';
		if($row['usertype'] == CT_PICUP_USER_TYPE_GOLD)
			$_color = 'Olive';
		if($row['usertype'] == CT_PICUP_USER_TYPE_PLATINUM)
			$_color = 'Aqua';
		$row['usertype'] = array(
								'val'		=> $row['usertype'],
								'class'		=> 'tb-cnt',
								'color'		=> $_color,
		);
		
		unset($row['nickname']);
		unset($row['first_name']);
		unset($row['last_name']);
		unset($row['avatar']);
		unset($row['coordinates_latitude']);
		unset($row['coordinates_longitude']);
		
		return $row;
	}
	
	function getColorByUserType($_user_id = NULL)
	{
		if($_user_id)
			$this->fetchByID($_user_id);

		if($this->usertype == CT_PICUP_USER_TYPE_NORMAL)
			$_color = NULL;
		if($this->usertype == CT_PICUP_USER_TYPE_SILVER)
			$_color = 'Silver';
		if($this->usertype == CT_PICUP_USER_TYPE_GOLD)
			$_color = 'Olive';
		if($this->usertype == CT_PICUP_USER_TYPE_PLATINUM)
			$_color = 'Aqua';			
			
		return $_color;
	} // end of function 
		
    /**
     * Try to fetch by name.
     *
     * @access public
     * @input:  $_POST(user_login), $_POST(user_password)
     * @output: string or empty
     */
	function fetchByName($_name)
	{
		$row = array();
		$_name = trim(strip_tags($_name));
		$query = $this->db->get_where($this->table_name, array('nickname' => $_name));
		if($query->result()){
			$row = $query->row();
			$this->_mapper($row);
		}
	} // end of function
	
	
	
	
	
    /**
     * trying to save private data.
     *
     * @access public
     * @input:  $_POST
     * @output: TRUE or FALSE (depending of scusesfull save data in to DB)
     */
	function savePrivateData()
	{
		$ret = FALSE;
		
		$lastname = $this->input->post('lastname');
		$firstname = $this->input->post('firstname');
		$pass = $this->input->post('pass');
		$cpass = $this->input->post('cpass');
		
		$data = array(
						'first_name'	=> $firstname,
						'last_name'		=> $lastname,
					  );
					 
		if($pass){
			$data['password'] = md5($pass);
		}
		
		$this->db->where('id', $this->user->id);
		$ret = $this->db->update('user', $data); 
		
		return $ret;		
	} // end of function 
	
	
		

    /**
     * Try login.
     *
     * @access public
     * @input:  $_POST(user_login), $_POST(user_password)
     * @output: string or empty
     */
    function login()
    {

        $login    = $this->input->get_post('login');
        $password = $this->input->get_post('pass');

        if(!$login) {			
			redirect(base_url().'home');
        }

        $this->login = $login;
		$query = $this->db->query('SELECT * FROM '.$this->table_name.' WHERE '.'binary(email) = ' . $this->db->escape($login) .' OR binary(nickname) = '.$this->db->escape($login).' LIMIT 1');
		
		$row = $query->row();

		$ret = CT_EMPTY_STR;
		if($row){

			if(md5($password) == $row->password) {
            	$this->_mapper($row);
			} else {
				$ret = ERR_WRONG_PASS;
            }

		} else {
            $ret = ERR_USER_NOT_FOUND;
		}

		$query->free_result();
        
        return $ret;
    }
	
	
    function is_unique_user_mail($mail)
    {
		$ret = true;
	    $query = $this->db->get_where($this->table_name, array('email'=>$mail));
        $row = $query->row();

	    if($row){ $ret = false;}
		$query->free_result();

		return $ret;    	
    }

    function is_unique_user_nick($nick)
    {
		$ret  = true;
	    $query = $this->db->get_where($this->table_name, array('nickname'=>$nick));
        $row = $query->row();

	    if($row){ $ret = false;}
		$query->free_result();

		return $ret;    	
    }
	
	
} // end of class