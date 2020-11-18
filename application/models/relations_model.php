<?php
class Relations_model extends UP_Model
{
    function __construct($id=NULL)
	{	
        $this->table_name = 'relations';
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
            'width'     => 100,
			'value'     => '',
			'display'	=> 'Название отношения',
        ));
		
        $this->insert_where_condition(array(
            'name'     => 'name',
            'relation' => 'like',
			'display'	=> 'По типу',
        ));
		
		$this->default_order_by = 'id DESC';
		
		$this->display_fields_name = array(
											"id"			=> '#',
											"name"			=> 'Название отношения',
										   );
		
		
    } // end of function
} // end of class