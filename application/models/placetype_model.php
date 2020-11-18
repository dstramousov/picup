<?php
class Placetype_model extends UP_Model
{
    function __construct($id=NULL)
	{	
		$this->table_name = 'placetype';
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
            'column'    => 'name',
            'type'      => 'varchar',
            'width'     => 500,
			'value'     => '',
			'display'	=> 'Название типа',
        ));
		
        $this->insert_field(array(
            'column'    => 'code',
            'type'      => 'varchar',
            'width'     => 10,
			'value'     => '',
			'display'	=> 'GeoNames Feature Codes',
        ));
		
        $this->insert_field(array(
            'column'    => 'e_desc',
            'type'      => 'varchar',
            'width'     => 300,
			'value'     => '',
			'display'	=> 'Описание длинное',
        ));

        $this->insert_field(array(
            'column'    => 's_desc',
            'type'      => 'varchar',
            'width'     => 200,
			'value'     => '',
			'display'	=> 'Описание короткое',
        ));
		

        $this->insert_field(array(
            'column'    => 'icon',
            'type'      => 'char',
            'width'     => 20,
			'value'     => '',
			'display'	=> 'Системная картинка',
        ));
		
        $this->insert_where_condition(array(
            'name'     => 'name',
            'relation' => 'like',
			'display'	=> 'По типу',
        ));
		
		$this->default_order_by = 'id DESC';
		
		$this->display_fields_name = array(
											"id"			=> '#',
											"name"			=> 'Название места',
											"code"			=> 'GeoName code',
											"s_desc"		=> 'Короткое описание',
											"e_desc"		=> 'Длинное описание',
											"icon"			=> array(
																			'title'=>'Системная картинка', 
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
		
		$row['name'] = array(
								'val'		=> get_shortened(($row['name']), 350),
								'class'		=> 'tb-rht',
								'width'		=> '40',
		);
		
		$row['s_desc'] = array(
								'val'		=> get_shortened(($row['s_desc']), 150),
								'class'		=> 'tb-rht',
								'width'		=> '140',
		);
		
		$row['e_desc'] = array(
								'val'		=> get_shortened(($row['e_desc']), 350),
								'class'		=> 'tb-rht',
								'width'		=> '200',
		);
		
		
		return $row;
	} // end of function 
	
    /**
     * Get all pairs code->name
     */
	function getAllPairs($_show_only_not_empty_category=TRUE)
	{
		$ret = array();
		
		if($_show_only_not_empty_category){
			// get only not empty categories
			$this->load->model('Place_model');
			$this->db->select('placetype_code');
			$this->db->distinct();
			$query = $this->db->get($this->Place_model->table_name);
			
			foreach($query->result_array() as $row){
				$__ret = $this->_fetchByCode($row['placetype_code']);
				if($__ret){
					$ret[$__ret->code] = $__ret->name;
				}
			}
		} else {
			// get all categories
		
			$this->db->select('code, name');		
			$query = $this->db->get($this->table_name);

			foreach ($query->result() as $row)
			{
				if(strlen($row->name) > 100){
					$row->name = get_shortened($row->name, 100);
				}
				$ret[$row->code] = $row->name;
			}
			
			//$this->ChangePairs();
			asort($ret);			
		}
		
		return $ret;
	} // end of function 
	
	
	function _fetchByCode($code)
	{		
		$this->db->where('code', $code); 
		$this->db->limit(1);
		$query = $this->db->get($this->table_name);
		if ($query->num_rows() > 0)
		{		
			return ($query->row());
		}
		
		return FALSE;
	} // end of function 
	
} // end of class