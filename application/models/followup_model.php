<?php
class Coordinates_model extends UP_Model
{
    function __construct($id=NULL)
	{	
		$this->table_name = 'coordinates';
        parent::__construct($this->table_name,$id);
    } // end of function
} // end of class