<?php

class UP_Model extends CI_Model {

    /**
     * PK name.
     */
    var $pk = CT_PK;

    /**
     * Physical tablename.
     */
    var $table_name;
    var $class_name;  // class_name == table name (without prefix)

    /**
     * Fields for this model.
     */
    var $fields = array();
	
    /**
     * Display fields name for this model.
     */
    var $display_fields_name = array();
	
    var $where_conditions;  // hash, not array (!)
                            // has 2 indexes: ['field_name']['type']	
	
    var $select_from;      // FROM clause for SELECT query
    var $aux_select_from;  // FROM clause for update_auxilary_data() function
	
    var $table_indexes = array();    // additional table indexes array
    var $foreign_keys  = array();

    /**
     * Display name (multilanguage).
     */
    var $display_name;
	
    /**
     * Display name (multilanguage).
     */
	var $default_order_by = 'id DESC';
	
    /**
     * Allowed params for admin viewer
     */
	var $__allowed_params = array(
								'or_where', 
								'where', 
								'where_in',
								'or_where_in',
								'where_not_in',
								'or_where_not_in',
								'like',
								'or_like',
								'not_like',
								'or_not_like',
								'group_by',
								'distinct',
								'having',
								'or_having',
								'order_by',
								'limit',
							  );
							  
    /**
     * current url (used for several object function)
     */
	var $cur_url = '';
							  

    /**
     * Consctructor
     */
    function __construct($model_name=NULL, $id=NULL)
    {		
        parent::__construct();
		
        $this->fields = array();
        $this->where_conditions = array();
		
		if($model_name){
			$this->table_name = $model_name;
			$this->class_name = $model_name;
			//$this->_initInternalStructure();
			
			$this->set_indefinite();
			
			$this->insert_select_from();
			$this->insert_aux_select_from();			
		}
		
        if($id){
			$this->fetchByID($id);
        }
    } // end of function
	
    /**
     * Store FROM clause for update_auxilary_data function.
     *
     * @access public
     * @param:  string $select_from FROM clause forupdate_auxilary_data
     * @return: NULL
     */
    function insert_select_from($select_from = NULL)
	{
        $this->select_from = isset($select_from) ? $select_from : ($this->table_name . " as ".$this->table_name);
    }//end of function


    /**
     * Store FROM clause for update_auxilary_data function.
     *
     * @access public
     * @param:  string $aux_select_from FROM clause for update_auxilary_data
     * @return: NULL
     */
    function insert_aux_select_from($aux_select_from = NULL)
	{
        $this->aux_select_from =
            isset($aux_select_from) ?
            $aux_select_from :
            $this->select_from;
    }//end of function
	
	
    /**
     * Set PRIMARY KEY member variable to zero. In other words, make the object undefined.
     *
     * @access public
     * @param:  NULL
     * @return: NULL
     */
    function set_indefinite()
	{
        $pr_key_name = $this->primary_key_name();
        $this->$pr_key_name = 0;
    }//end of function
	
    /**
     * Retun name of the PRIMARY KEY column.
     *
     * @access public
     * @param:  NULL
     * @return: string $this->primary_key  OR 'id'
     */
    function primary_key_name()
	{
         return isset($this->primary_key) ? $this->primary_key : 'id';
	}//end of function 
		
    /**
     * Return an array with given fields stored in it for future use in a page template.
     */
    function write_form($fields_to_write = NULL)
	{
        $h = array();
        $date_counter = 1;	
		$date_field_names = array();
		
		$input_extra = $this->config->item('default_form_input_extra');
		
		$_display_lable = '';
		foreach($this->fields as $f)
		{
			if($f['column'] == CT_PK){continue;}
			
            $value			= isset($this->$f['column']) ? $this->$f['column'] : '';
            $pname			= $this->table_name . '_' . $f['column'];
            $value			= htmlspecialchars($value);
			$type			= $f['type'];
			$_display_lable	= isset($f['display']) ? $f['display'] : $f['column'];
			
			if(!$f['write'])  {continue;}
			if($f['virtual']) {continue;}
			
            $h[$pname] = $value;

            $h[$pname . '_input'] = array(
											'display_lable' => $_display_lable,
											'input'			=> "<input type=\"$type\" name=\"$pname\" value=\"$value\" $input_extra>"
										  );

            $h[$pname . '_hidden'] =
                "<input type=\"hidden\" name=\"$pname\" value=\"$value\" $input_extra>";

            switch($type) {

            case 'datetime':
            case 'timestamp':
                $date_regexp = '/^(\d+)-(\d+)-(\d+) (\d+):(\d+):(\d+)$/';

                if (preg_match($date_regexp, $value, $date_values)) {
                    $year   = $date_values[1];
                    $month  = $date_values[2];
                    $day    = $date_values[3];

                    $hh     = $date_values[4];
                    $mi     = $date_values[5];
                    $se     = $date_values[6];

                    $t = mktime($hh, $mi, $se, $month, $day, $year);
				}

				$h[$pname . '_input'] = array(
											   'display_lable'	=> $_display_lable,
											   'input' 			=>'<input type="text" name="'.$pname.'" id="'.$pname.'" value="'.$value.'" />'
											);
				$date_field_names[$pname] = array('name'=>$pname, 'type'=>$f['calendar_options']);	
				$date_counter ++;

            	break;

            case 'date':
                $date_regexp = '/^(\d+)-(\d+)-(\d+)$/';
                $date_values = array();

                if (preg_match($date_regexp, $value, $date_values)) {
                    $year   = $date_values[1];
                    $month  = $date_values[2];
                    $day    = $date_values[3];
                    $t = mktime(0, 0, 0, $month, $day, $year);
                    $value = ($t != -1) ? strftime($this->get_app_date_format(), $t) : 'N/A';
				}

				$h[$pname . '_input'] = array(
											   'display_lable'	=> $_display_lable,
											   'input' =>'<input type="text" name="'.$pname.'" id="'.$pname.'" value="'.$value.'" />'
											   );
				$date_field_names[$pname] = array('name'=>$pname, 'type'=>$f['calendar_options']);	
				$date_counter ++;

            	break;

            case 'integer':
            case 'int':
                if (isset($f['input'])) {
                    if (is_array($f['input'])) {
                        $input = $f['input'];
                        switch($input['type']) {
                        case 'select':
                            if ($this->fields[$name]['type'] == 'enum') {
                                $items = $this->fields[$name]['values'];

                            } else if (isset($input['values'])) {
                                $items = $input['values'];

                            } else if (isset($input['items_callback'])) {
                                $items = $this->$input['items_callback']();  // NB! Variable function

                            } else {
                                $from     = $input['from'];
                                $data     = $input['data'];
                                $caption  = $input['caption'];
                                $query_ex =
                                    isset($input['query_ex']) ?
                                    $input['query_ex'] :
                                    array();

                                $obj = $this->app->create_object($from);
                                $items = $obj->get_items($data, $caption, $query_ex);
                            }

                            if (
                                isset($input['nonset_id']) &&
                                isset($input['nonset_name'])
                            ) {
                                $items = array_merge(
                                    array(array(
                                        'id' => $input['nonset_id'],
                                        'name' => $input['nonset_name']
                                    )), $items);
                            }

                            $options = make_options($items, $value);

                            $h[$pname . '_input'] = array(
															'display_lable'	=> $_display_lable,
															'input'			=> "<select name=\"$pname\" $input_extra>$options</select>"
															);
                            break;

                        default:
                            $h["{$pname}_input"] = array(
															'display_lable'	=> $_display_lable,
															'input' => "<input type=\"$input[type]\" name=\"$pname\" value=\"$value\" $input_extra>"
														 );
                        }

                    } else {  // COMPATIBILITY

                        switch($f['input']) {
                        case 'checkbox':
                            $input_text = $value ? 'checked' : '';
                            $h[$pname . '_input'] = array(
															'display_lable'	=> $_display_lable,
															'input' 		=> "<input type=\"checkbox\""."id=\"$pname\" name=\"$pname\" value=\"1\" $input_text $input_extra>"
														  );
                            break;

                        case 'select':
                            if (isset($f['join'])) {  // COMPATIBILITY
                                $options = $this->get_options($name, $f['join']['table']);
                                $h[$pname . '_input'] = array(
																'display_lable'	=> $_display_lable,
																'input' => "<select name=\"$pname\" $input_extra>$options</select>" 
															  );

                            } else if (isset($f['link'])) {  // COMPATIBILITY
                                $options = $this->get_options($name, $f['link']);
                                $h[$pname . '_input'] = array(
											   'display_lable'	=> $_display_lable,
												'input'=> "<select name=\"$pname\" $input_extra>$options</select>"
												);
                            }
                            break;
                        }
                    }
                }
                break;

            case 'enum':
                if (isset($f['values'][$value])) {
                    $h[$pname . '_name'] = $f['values'][$value];
                }
                $options = $this->write_options($f['values'], $f['value']);
                $h[$pname . '_input'] = array(
											   'display_lable'	=> $_display_lable,
											   'input'			=> "<select name=\"$pname\" $input_extra>$options</select>"
											  );
                break;

            case 'double':
                $h[$pname] = $this->get_app_double_value($value, 2);
                $orig_value = $this->get_app_double_value($value, $f["prec"]);
                $h[$pname . "_orig"] = $orig_value;
                $h[$pname . "_long"] = $this->get_app_double_value($value, 5);

                $h[$pname . "_input"] = array(
											   'display_lable'	=> $_display_lable,
											   'input'			=> "<input type=\"text\" name=\"$pname\" value=\"{$orig_value}\" $input_extra>"
											  );
                $h[$pname . "_hidden"] =
                    "<input type=\"hidden\" name=\"$pname\" value=\"{$orig_value}\">";
                break;

            case "varchar":
                if (isset($f["values"])) {
                    $options = write_options($f["values"], $value);
                    $h[$pname . "_input"] = array(
											   'display_lable'	=> $_display_lable,
											   'input' 			=> "<select name=\"$pname\" $input_extra>$options</select>"
											   );
                }
                break;

            case 'text':
            case 'mediumtext':

	                $cols = 60;
	                $rows = 5;
	                if (isset($f['input']) && is_array($f['input'])) {
	                    $input = $f['input'];
	                    $cols  = isset($input['cols']) ? $input['cols'] : $cols;
	                    $rows  = isset($input['rows']) ? $input['rows'] : $rows;
	                }
	                $h[$pname . "_input"] = array(
											   'display_lable'	=> $_display_lable,
											   'input'=> "<textarea name=\"{$pname}\" cols=\"{$cols}\" " .
															"rows=\"{$rows}\"  $input_extra>{$value}</textarea>");
                break;
            }
        }
		
		if(count($date_field_names) != NULL)
		{
			$h["js_date_logic"] = '<script language="JavaScript" src="'.base_url().'js/right/right/calendar.js"></script>
			<script type="text/javascript">';
			
			$date_counter = 1;
			
			foreach($date_field_names as $fn)
			{
				$h["js_date_logic"] .= 'var cal'.$date_counter.' = new Calendar({'.$fn['type'].', showButtons: true}).assignTo(\''.$fn['name'].'\');';
				$date_counter ++;
			}

			$h["js_date_logic"] .= '</script>';
		}
		
		return $h;
	} // end of function 
	
    function mysql_now_date() {
        return $this->mysql_date(time());
    }

    function mysql_now_datetime() {
        return $this->mysql_datetime(time());
    }

    function mysql_date($ts) {
        return date('Y-m-d', $ts);
    }

    function mysql_datetime($ts) {
    	return date('Y-m-d H:i:s', $ts);
    }
	
    /**
     * @access public
     * @input:  
     * @output: string
     */
	function get_datetime_format()
	{
		return $this->config->item('display_datetime');
	}
	
    /**
     * @access public
     * @input:  
     * @output: string
     */
	function get_date_format()
	{
		return $this->config->item('display_date');
	}
	
    /**
     * @access public
     * @input:  
     * @output: string
     */
	function get_time_format()
	{
		return $this->config->item('display_time');
	}
	
    /**
     * Prepare and write form for edit object.
     *
     * @access public
     * @input:  
     * @output: true/false
     */
	function writeForm()
	{
		$CI = &get_instance();
		$this->load->helper('html');
	
		$h = array();

			$counter_fileds = 1;
			foreach($this->fields as $field)
			{			
				$field_name = $field['name'];
			
				//$value = isset($this->$f['name']) ? $this->$f['name'] : '' ;
				//$pname = $this->table_name . '_' . $f['name'];
				//$value = htmlspecialchars($value);
				//$type = $f['type'];
			//foreach($this->fields as $field_name=>$field)
			//{
				// skip primary key
				if($field_name == CT_PK){
	//				continue;
				}
				// skip timestamp vield type
				if($field['type'] == 'timestamp'){
//					continue;
				}

	            $pname	= $this->table_name . '_' . $field_name;
				$translated_pname = $this->lang->line($pname);
				$type	= $field['type'];
				$value	= htmlspecialchars($this->$field['name']);

				switch($type) {

					case 'int':
					case 'integer':
					case 'tinyint':
					case 'smallint':
					case 'mediumint':
					case 'bigint':
					
						array_push($h, array(
												'name'		=> $translated_pname,
												'input' 	=> "<input class=\"text\" type=\"text\" id=\"$pname\" name=\"$pname\" value=\"".$this->$field_name."\" />",
												'selector'	=> $pname,
												'extra'		=> CT_EMPTY_STR
											 ));

					break;

					case 'date':
					case 'datetime':
						$__in_html	= dateHtmlGen($field, $this->$field_name);
						$add_js		= '
						<script src="'.server_path().'js/right/right-calendar-min.js" type="text/javascript"></script>
						<script src="'.server_path().'js/right/i18n/right-ui-i18n-ru.js" type="text/javascript"></script>
						<script type="text/javascript">
// <![CDATA[
new Calendar({listYears: true, showButtons: true}).assignTo("'.$pname.'");
// , format: "%d %B %Y %H:%M"
// ]]>
</script>
';
						array_push($h, array(
												'name' => $translated_pname,
												'input' => $__in_html,
												'selector'	=> $pname,
												'extra'		=> $add_js
											 ));
					break;
					
					case 'enum':
						//dump($this);
						
						$__in_html	= enumHtmlGen($this->table_name, $field, $this->$field_name);
											 
						//dump(base_url());
						$add_js		= '<script src="'.base_url().'js/right/right-selectable-min.js" type="text/javascript"></script>';
						array_push($h, array(
												'name' => $translated_pname,
												'input' => $__in_html,
												'selector'	=> $pname,
												'extra'		=> $add_js
											 ));
											 
											 
					break;
					
					
					case 'char':
					case 'varchar':
					
						
						array_push($h, array(
												'name' => $translated_pname,
												'input' => "<input class=\"text\" type=\"text\" id=\"$pname\" name=\"$pname\" value=\"".$this->$field_name."\" />",
												'selector'	=> $pname,
												'extra'		=> CT_EMPTY_STR
											 ));
					break;					
					
					case 'tinytext':
					case 'text':
					case 'mediumtext':
					case 'longtext':
						// fckeditor logic
						$CI->load->library('yw_fckeditor');
						
						$config = array();
						$config['toolbar'] = array(
							array( 'Source', '-', 'Save','-', 'Bold', 'Italic', 'Underline', 'Strike' ),
							array( 'Image', 'Link', 'Unlink', 'Anchor' )
						);
						
						$editor_html = $CI->yw_fckeditor->editor($pname, $this->$field_name, $config);
						array_push($h, array(
												'name'	=> $translated_pname,
												'input'	=> $editor_html,
												'selector'	=> $pname,
												'extra'		=> CT_EMPTY_STR
											 ));

					break;

					default:
						array_push($h, array(
												'name' => $translated_pname,
												'input' => "<input class=\"text\" type=\"$type\" id=\"$pname\" name=\"$pname\" value=\"".$this->$field_name."\" />",
												'selector'	=> $pname,
												'extra'		=> CT_EMPTY_STR
											 ));
											 
				} // end of switch
				
			} // end if fireach 

		dump($h);

        return $h;
	}
	
	function write_options($items, $select = NULL)
	{
		return $this->make_options($items, $select) ;
	}


	function make_options($items, $select = NULL)
	{
		$s = "\n";

		foreach($items as $i => $item)
		{
			if (is_array( $item) ) {
				$id   = $item['id'];
				$name = $item['name'];

			} else {
				// compatibility mode:
				$id   = $i;
				$name = $item;
			}
			if (is_array( $select) ) {
				$sel = in_array($id, $select) ? ' selected' : '';
			} else {
				$sel = (isset($select) && $id == $select) ? ' selected' : '';
			}
			$s .= "<option value=\"$id\"$sel>$name</option>\n";
		}

		return $s;
	} // end of function
	
	
    /**
     * Return string with select query 
     */
    function get_select_query($fields_to_select = NULL)
	{
        $field_names = isset($fields_to_select) ?
            $fields_to_select : array_keys($this->fields);

			
        $select = '';
        $comma = '';

        reset($field_names);
        while (list($i, $name) = each($field_names)) {
            $f = $this->fields[$name];
			//dump($f);

            if (!isset($f['select'])) {
				dump(1);
                continue;
            }

            $select .= $comma;
            $comma = ', ';

            $select .= $f['select'] . " as $name";
        }

        $query = new SelectQuery(array(
            'select' => $select,
            'from'   => $this->select_from,
        ));

        return $query;
    } // end of function 
	
	
    function read_where($fields_to_read = NULL) {
        // Return list ($where, $params).
        // $where --
        //     with 'WHERE' clause, read from CGI and tested to be valid.
        // $params --
        //     valid read parameters for use in pager-generated links.

        $where = '1';
        $params = array();

        $field_names = isset($fields_to_read) ?
            $fields_to_read : array_keys($this->fields);

        reset($field_names);
        while (list($i, $name) = each($field_names)) {
            $f = $this->fields[$name];

            /*
            if (!$f['read']) {
                continue;
            }
            */

            $type  = $f['type'];
            $pname = $this->class_name . '_' . $name;

            switch($type) {
            case 'datetime':
            case 'date':
                $less = param("{$pname}_less");
                if ($less) {
                    $where .= " and $f[select] <= ". $this->qw($less);
                    $params["{$pname}_less"] = $less;
                }
                $greater = param("{$pname}_greater");
                if ($greater) {
                    $where .= " and $f[select] >= " . $this->qw($greater);
                    $params["{$pname}_greater"] = $greater;
                }
                break;

            case 'integer':
                $less = param("{$pname}_less");
                if ($less) {
                    $where .= " and $f[select] <= " . intval($less);
                    $params["{$pname}_less"] = $less;
                }

                $greater = param("{$pname}_greater");
                if ($greater) {
                    $where .= " and $f[select] >= " . intval($greater);
                    $params["{$pname}_greater"] = $greater;
                }

                $equal = param("{$pname}_equal");
                if ($equal) {
                    $where .= " and $f[select] = " . intval($equal);
                    $params["{$pname}_equal"] = $equal;
                }

                break;

            case 'double':
                $less = param("{$pname}_less");
                if ($less) {
                    $where .= " and $f[select] <= " . doubleval($less);
                    $params["{$pname}_less"] = $less;
                }

                $greater = param("{$pname}_greater");
                if ($greater) {
                    $where .= " and $f[select] >= " . doubleval($greater);
                    $params["{$pname}_greater"] = $greater;
                }

                $equal = param("{$pname}_equal");
                if ($equal) {
                    $where .= " and $f[select] = " . doubleval($equal);
                    $params["{$pname}_equal"] = $equal;
                }

                break;

            case 'varchar':
            case 'enum':
                // Max: "not so cool."

                $like = param("{$pname}_like");
                if ($like != '') {
                    $where .= " and $f[select] like " . $this->qw("%$like%");
                    $params["{$pname}_like"] = $like;
                }

                // NB! no 'break' here.

                $equal = param("{$pname}_equal");
                if ($equal != '') {
                    $where .= " and $f[select] = " . $this->qw($equal);
                    $params["{$pname}_equal"] = $equal;
                }

                break;
            }
        }

        return array($where, $params);
    }
	
    /**
     * Read where conditions (from search form).
     */
    function get_where_condition()
	{
        $where_str = "1";
        $havings = array();

        foreach($this->where_conditions as $name => $field_conditions) {
            $type   = $this->fields[$name]['type'];
            $select = $this->fields[$name]['select'];

            foreach($field_conditions as $relation => $cond) {
                $nonset_value =
                    (isset($cond["input"]["nonset_id"])) ? $cond["input"]["nonset_id"] : "";

                if (!isset($cond['value']) || $cond['value'] == $nonset_value) {
                    continue;
                }
                $value = $cond['value'];

                switch($type) {
                case 'integer':
                    if (is_array($value)) {
                        $value_str = array();
                        foreach($value as $val) {
                            $value_str[] = intval($val);
                        }
                    } else {
                        $value_str = intval($value);
                    }
                    break;

                case 'double':
                    if (is_array($value)) {
                        $value_str = array();
                        foreach($value as $val) {
                            $value_str[] = double($val);
                        }
                    } else {
                        $value_str = double($value);
                    }
                    break;

                case 'date':
                    if (is_array($value)) {
                        $value_str = array();
                        foreach($value as $val) {
                            $value_str[] = $this->qw($this->app2mysql_date($val));
                        }
                    } else {
                        $value_str = $this->qw($this->app2mysql_date($value));
                    }
                    break;

                default:
                    if (is_array($value)) {
                        $value_str = array();
                        foreach($value as $val) {
                            $value_str[] = $this->qw($val);
                        }
                    } else {
                        $value_str = $this->qw($value);
                    }
                }

                switch($relation) {
                case 'less':
                    $where_str .= " and $select <= $value_str";
                    break;

                case 'greater':
                    $where_str .= " and $select >= $value_str";
                    break;

                case 'equal':

                    if (is_array($value)) {
                        $where_arr = array();
                        foreach($value_str as $val) {
                            $where_arr[] = "$select = $val";
                        }
                        $where_str .= ' and (' . join(' or ', $where_arr) . ')';
                    } else {
                        $where_str .= " and $select = $value_str";
                    }

                    break;

                case 'like':
                    $where_str .= " and $select like concat('%', $value_str, '%')";
                    break;

                case "having_equal":
                    $havings[] = "({$select} = {$value_str})";
                    break;

                case "having_less":
                    $havings[] = "({$select} <= {$value_str})";
                    break;

                case "having_greater":
                    $havings[] = "({$select} >= {$value_str})";
                    break;

                default:
                    if (is_array($value)) {
                        $where_arr = array();
                        foreach($value_str as $val) {
                            $where_arr[] = "$select = $val";
                        }
                        $where_str .= ' and (' . join(' or ', $where_arr) . ')';
                    } else {
                        $where_str .= " and $select = $value_str";
                    }

                    break;
                }
            }
        }
		
        $having_str = join(" and ", $havings);
        return array($where_str, $having_str);
    } // end of function 
	
	// Quote and escape string for mysql.
	function qw($str) {
		if ( is_null($str) ) {
			return 'null';
		} elseif ( is_numeric($str) ) {
			return $str;
		} else {
			return "'" . mysql_escape_string($str) . "'" ;
		}
	} // end of function
	
    function read_order_by($default_order_by = 'id asc', $additional = array())
	{
        list($res_field, $res_dir) = explode(' ', "$default_order_by asc");
        $a = explode(' ', param('order_by'), 2);

        // check, if given field exists:
        if (isset($this->fields[$a[0]]) || in_array($a[0], $additional)) {
            $res_field = $a[0];
            $res_dir = 'asc';
            if (isset($a[1]) && preg_match( '/^(asc|desc)$/i', $a[1])) {
                $res_dir = $a[1];
            }
        }

        $order_by = "$res_field $res_dir";
        $params = array(
            'order_by' => $order_by,
        );
		
        return array($order_by, $params);
    } // end of function
	
	
    /**
     * Read where conditions (from search form).
     */
    function get_where_params()
	{
        $params = array();

        foreach($this->where_conditions as $name => $field_conditions) {
            foreach($field_conditions as $relation => $cond) {
			
                if (isset($cond['value'])) {
                    $pname = "{$this->class_name}_{$name}_{$relation}";
                    $params[$pname] = $cond['value'];
                }
            }
        }
	
        return $params;
    } // end of function
	
    /**
     * Store all parameters of the table field (column) in hash $fields .
     */
    function insert_where_condition($condition)
	{
        $name     = $condition['name'];
        $relation = $condition['relation'];

        $this->where_conditions[$name][$relation] = $condition;
    } // end of function
	

    /**
     * Read where conditions (from search form).
     */
    function read_where_cool($conditions_to_read = NULL)
	{
        $condition_names = isset($conditions_to_read) ?
            $conditions_to_read : array_keys($this->where_conditions);

        $filter_by  = param('filter_by');
        $filter     = param('filter');
		
        foreach($this->where_conditions as $name => $field_conditions) {
            if (in_array($name, $condition_names)) {
                foreach($field_conditions as $relation => $cond) {
                    $pname = "{$this->class_name}_{$name}_{$relation}";
                    $value = param($pname);
                    if (!is_null($value)) {
                        $this->where_conditions[$name][$relation]['value'] = $value;
                    } elseif ( isset($filter_by) && isset($filter) ) {
                    	if ( $filter_by == $pname && trim($filter) != '' ) {
                    		$this->where_conditions[$name][$relation]['value'] = $filter;
                    	}
                    }
                }
            }
        }
    } //end of function	
	
    // Even more generalized object functions:
    // Print table to view several objects.
	
   function print_view_several_objects_page($_query_options=NULL)	
//    function print_view_several_objects_page($where_str=NULL, $default_order_by = NULL, $order_by = NULL, $group_by = NULL, $assign_var = array())
    {
        //$name = $this->table_name;
		
		if($_query_options){		
			$this->cur_url = implode("&", $_query_options);
			dump($this->cur_url);
			
		} else {
			// need set 2 mandatory parameters for query limit and order_by
			$__cur_page = 1;
			$__per_page = $this->config->item('admin_default_rows_per_page');
			$where_str = '';
		}
		$this->__initPaging($__per_page, $__cur_page);
		
    	$_where	= $where_str;
        $name	= $this->class_name;
        $query	= $this->get_select_query();

        $query->expand(array(
            'where'		=> $where_str,
            'order_by'	=> $default_order_by,
        ) );
//		dump($query);
		
		$main_res = $this->db->query($query->str());
		$total_record = $main_res->num_rows();
		
		
        if($total_record > 0) {

	        $res = $this->get_expanded_result($query, array(
				'where'		=> $_where,
				'order_by'	=> $order_by,
                'group_by'	=> $group_by,
//                'limit'		=> SHIN_Core::$_libs['pagination']->get_limit_clause(),
    	    ));
			
//			dump($res->result_array());
			
            // Fill the table with selected items:
            $i = 0;
			$__ret = array();
			foreach ($res->result_array() as $row)
			{
                $this->fetch_row($row);
				
				array_push($__ret, $this->write());

				//$this->up_templater->append('several_objects_data', $this->write());
				//$this->up_templater->append($this->write());
				/*
                SHIN_Core::$_libs['templater']->append($this->write());
                SHIN_Core::$_libs['templater']->append(array(
                    "row_parity" => $i % 2,
                    "row_style" => ($i % 2 == 0) ? "table-row-even" : "table-row-odd",
                ));
				*/
                $i++;
            }
			$this->up_templater->assign('several_objects_data', $__ret);
						
			//$__arr = SHIN_Core::$_libs['pagination']->create_links();
			
			//SHIN_Core::$_libs['templater']->assign(array('nav_str_up'		=> $__arr[0]));
			//SHIN_Core::$_libs['templater']->assign(array('nav_str_down'		=> $__arr[1]));
        }
		$this->_postProcessHeaderTable();
    } // end of function
	
    /**
     * 
     */
	function write()
	{
		return $this->curent_object;
	} //end of function
	
    /**
     * 
     */
    function fetch_row($row)
	{
		$this->curent_object = array();
        foreach($row as $name => $value) {
            $this->$name = $value;
			$this->curent_object[$name] = $value;
        }
    }//end of function 
		
    /**
     * 
     */
    function get_expanded_result($query, $clauses = array(), $fields_to_select = NULL)
	{
        $query->expand($clauses);
        $query_str = $query->str();
		return $this->db->query($query_str);
    }//end of function 
	
    /**
     * 
     */
	function _postProcessHeaderTable()
	{
		//dump(array_values($this->where_conditions));
		$several_objects_header_information_uf = array();
		$several_objects_where_information = array();
		
		foreach($this->where_conditions as $k=>$arr){
			foreach($arr as $i=>$j){
				array_push($several_objects_header_information_uf, $j['display']);
				array_push($several_objects_where_information, $this->table_name.'_'.$k.'_'.$i);
				
			}
		}
		
//		dump($several_objects_where_information);
		$this->up_templater->assign('several_objects_where_information', $several_objects_where_information);
		$this->up_templater->assign('several_objects_where_information_uf', $several_objects_header_information_uf);
		$this->up_templater->assign('several_objects_where_information_count_th', count(array_keys($this->where_conditions)));
	
		//dump(array_values($this->display_fields_name));
		$this->up_templater->assign('several_objects_header_information', array_keys($this->display_fields_name));
		$this->up_templater->assign('several_objects_header_information_uf', array_values($this->display_fields_name));
		$this->up_templater->assign('several_objects_header_information_count_th', count($this->display_fields_name));
	} // end of function 
	
	function _mapper($row)
	{
		foreach($row as $field_name => $field_value)
		{
			$this->$field_name = $field_value;
		}
	}

	// PUBLIC FUNCTIONS //////////////////////////////////

    /**
     * Fetch object by ID.
     *
     * @access public
     * @input:  id
     * @output: true/false
     */
	function fetchByID($id)
	{
	
		$_ret = false;

		if(!$id){return $_ret;}
	
		$query = $this->db->query('SELECT * FROM '.$this->table_name.' WHERE id='.$id.' LIMIT 1');
		$row = $query->row();
		if($row){
			$this->_mapper($row);
			$_ret = true;
		}

		$query->free_result();
			
	    return $_ret;

	} // end of function 

    /**
     * Is defined record.
     *
     * @access public
     * @input:  null
     * @output: true/false
     */
	function isDefinite()
	{
		$ret = false; 

		if($this->{$this->pk} != 0){
			$ret = true; 
		}
		
		return $ret;

	} // end of function
	
	
    /**
     * Store all parameters of the table field (column) in hash $fields. The key of this hash is parameter 'name'.
     * 	 
     * - Supported parameters:
     * -# 'name'   (string)  Name of the class member variable.
     *                     default value == 'column'
     *                     (or 'table_column' if the field is from other table).
     *  -# 'table'  (string)  Name of the table in which the field is stored.
     *                     default value == class_name.
     *  -# 'link'	   (string)  Name of the table which will be linked.
     *  -# 'alias'  (string)  Alias for linked table.
     *  -# 'column' (string)  Name of the field in the table.
     *  -# 'type'   (string)  Type of the column (integer, double, varchar, etc.)
     *                     integer, enum, varchar, double, datetime, timestamp.
     *  -# 'values' (array)   Values for enum type.
     *  -# 'width'  (int)     Width of the stored value (for varchar and double).
     *  -# 'prec'   (int)     Precision of the stored value (for double only).
     *  -# 'attr'   (string)  Additional column attributes for CREATE TABLE query.
     *  -# 'value'  (*)       Initial value for class member variable.
     *  -# 'aux'    (string)  SQL expression for updating auxilary data.
     *  -# 'unsigned'  (bool)  Unsigned or not.
     *  -# 'create' (bool)    Must be used in CREATE TABLE query.
     *  -# 'store'  (bool)    May be stored to table.
     *  -# 'update' (bool)    May be updated in table.
     *  -# 'read'   (bool)    May be read from CGI.
     *  -# 'write'  (bool)    May be written to web page.
     *  -# 'input'  (string)  HTML form input type for this field.
     *  -# 'virtual'(bool)    Is column virtual or not
     *  -# 'validate'(bool)    Is column need make standart validate or not. Default - FALSE
		Default values:
						validate_int
						validate_date
						validate_float
						validate_bool
						validate_url
						validate_email
						validate_ip
						filter_raw
						sanitize_string
						sanitize_encoded
						sanitize_special_chars
						sanitize_email
						sanitize_url
						sanitize_number_int
						sanitize_number_float
						sanitize_magic_quotes
						flag_allow_octal
						flag_allow_hex
						flag_strip_high
						flag_encode_low
						flag_encode_high
						flag_no_encore_quotes
						flag_allow_fraction
						flag_allow_thousand
						flag_allow_scientific
						flag_scheme_required
						flag_host_required
						flag_path_required
						flag_query_required
						flag_ipv4
						flag_ipv6
						flag_no_res_range
						flag_no_priv_range
     *
     * @access public
     * @param:  $field array. See Example:
     * @return: NULL
     */	 
    function insert_field($field)
	{

        if(isset($field['table']) && !empty($field['table'])) {
            $field['virtual']   =   true;
        } else {
            $field['virtual']   =   false;    
        }
        
        // set required parameters:
        if (!isset($field['table'])) {
            $field['table'] = isset($field['name']) ? '' : $this->class_name;
        }

        // join:
        if (isset($field['join'])) {

            // Set default join mode to INNER:
            if (!isset($field['join']['mode'])) {
                $field['join']['mode'] = 'inner';
            }

            // Table name must be given:
            if (!isset($field['join']['table'])) {
                die('Table name must be given for join');
            }

            // Set alias for joined table:
            if (!isset($field['join']['as'])) {
                $field['join']['as'] = $field['join']['table'];
            }

            // On what column we make join:
            if (!isset($field['join']['column'])) {
                $field['join']['column'] = 'id';
            }

            // Expand select query:
            $mode   = $field['join']['mode'];
            $table  = $field['join']['table'];
            $alias  = $field['join']['as'];
            $column = $field['join']['column'];

            $t_name = $table;

            $this->select_from .=
                " $mode join $t_name as $alias" .
                " on $alias.$column = {$this->class_name}.$field[column]";
		}
		
        if (isset($field['link'])) {
            $table = $field['link'];

            if (!isset($field['alias'])) {
                $field['alias'] = $table;
            }
            if (!isset($field['type'])) {
                $field['type'] = 'integer';
            }
            if (!isset($field['column'])) {
                $field['column'] = $table . '_id';
            }

            // expand select query:
            $t_name = get_table_name($table);
            $this->select_from .=
                " INNER join $t_name as $field[alias]" .
                " on $field[alias].id = {$this->class_name}.$field[column]";
        }

        if (!isset($field['name'])) {
            if (!isset($field['column'])) {
				SHIN_Core::show_error("$this->class_name: Error! Field name is not specified!<br>");
            }
            $table_name = (isset($field["alias"])) ? $field["alias"] : $field['table'];
            $field['name'] =
                ($field['table'] == $this->class_name) ?
                $field['column'] :
                ("{$table_name}_{$field['column']}");
        }

        if (!isset($field['width'])) {
			if(isset($field['type'])) {
			
				switch($field['type']) {
				case 'varchar':
					$field['width'] = 255;
					break;
				case 'double':
				case 'float':
				case 'decimal':
					$field['width'] = 16;
					break;
				}
			} else {
				// try to take information about field
				foreach(SHIN_Core::$_models as $loaded_model){
					if($loaded_model->table_name == $field['table']){
						foreach($loaded_model->fields as $loaded_model_field){
							if($loaded_model_field['name'] == $field['column']){
								foreach($loaded_model_field as $property=>$value){
									if (!array_key_exists($property, $field)) {
										$field[$property] = $value;
									}
								}
							}
						}
					} else {
						SHIN_Core::log('warning', 'SHIN_Model WARNING. Virtual vield incorrect definition.');
					}
				}
			}
        }

        if (!isset($field['prec'])) {
			if (isset($field['type'])) {
				switch($field['type']) {
				case 'double':
				case 'float':
				case 'decimal':
					$field['prec'] = 2;
					break;
				}
			}
        }

        if (!isset($field['select']) && $field['table'] != '' ) {
            $table_name = (isset($field["alias"])) ? $field["alias"] : $field['table'];
            $field['select'] = " $table_name.$field[column]";
        }

        if (!isset($field['attr'])) {
            $field['attr'] = '';
        } 

		
        if (!isset($field['null'])) {
            $field['null'] = 0;
        }

        if (!isset($field['create'])) {
            $field['create'] = ($field['table'] == $this->class_name) ? 1 : 0;
        }
		
        if (!isset($field['validate'])) {
			$field['validate'] = FALSE;
		}
		

        if ($field['create'] && !isset($field['aux'])) {
            $field['aux'] = "$field[table].$field[column]";
        }
        
        if (!isset($field['store'])) {
            $field['store'] = (
                $field['table'] == $this->class_name            &&
                $field['create']                                &&
                ( $field['name'] != $this->primary_key_name() || stristr($field['attr'], 'auto_increment') === false  ) &&
                $field['type'] != 'timestamp'
           ) ? 1 : 0;
        }

        if (!isset($field['update'])) {
            $field['update'] = (
                $field['table'] == $this->class_name            &&
                $field['create']                                &&
                ( $field['name'] != $this->primary_key_name() || stristr($field['attr'], 'auto_increment') === false  ) &&
                $field['type'] != 'timestamp'
           ) ? 1 : 0;
        }

        if (!isset($field['read'])) {
			if (isset($field['type'])) {
				$field['read'] = ($field['type'] != 'timestamp') ? 1 : 0;
			}
        }

        if (isset($field['unique'])) {
            $field['unique'] = 1;
        } else {
            $field['unique'] = 0;
		}
				
        if (!isset($field['write'])) {
            $field['write'] = 1;
        }

        if (!isset($field['input'])) {
            $field['input'] = ($field['name'] == 'password') ? 'password' : 'text';
        }

        if ( isset($field['reference']) && trim($field['reference']) ) {
        	$field['reference'] = 'references '.$field['reference'];
        } else {
        	$field['reference'] = '';
        }

		
        if ( isset($field['comment']) && trim($field['comment']) ) {
        	$field['comment'] = 'comment '.SHIN_Core::$_db[SHIN_Core::$_shdb->active_group]->escape($field['comment']);
        } else {
        	$field['comment'] = '';
        }

        if ( !isset($field['validate']) ) {
        	$field['validate'] = ($field['store'] && $field['update']);
        }

        if ( !isset($field['title']) ) {
		
			// dimas by perfomance.
        	//$_title = preg_replace('/^([A-Za-z_ ]+).*$/', '$1', $field['name']);
        	//$_title = ucfirst(str_replace('_', ' ', $_title));
			
			$_title = $field['name'];
			
			if($this->lang->line($_title)){
				$__tmp_val = SHIN_Core::$_language->line($_title);
				if($__tmp_val == ''){$__tmp_val = $_title;}
			} else {
				$__tmp_val = $_title;
			}
			
			$field['title'] = $__tmp_val;
        }
		
		if($field['virtual']){
			if(!isset($field['type'])){
				
				$__model_name = $field['table'].'_model';
				if(!isset(SHIN_Core::$_models[$field['table'].'_model'])){
					// init needed libs
					$nedded_libs = array('models' => array(array($__model_name, $__model_name)));
					SHIN_Core::postInit($nedded_libs);
				}
								
				foreach(SHIN_Core::$_models[$__model_name]->fields as $loaded_model_field){

					if($loaded_model_field['name'] == $field['column']){
						foreach($loaded_model_field as $property=>$value){
							if (!array_key_exists($property, $field)) {
								$field[$property] = $value;
							} 							
						}
					}
				}
			}
		}

        $name = $field['name'];
        $this->fields[$name] = $field;

        if (isset($field['value'])) {
            $this->$name = $field['value'];  // !!!
        }
				
    } // end of function (insert_field)
	
    /**
     * Add indexes for current model.
     *
     * @access public
     * @param:  string $index Index name.
     * @return: NULL
     */
    function insert_index($index)
	{
        $this->table_indexes[] = $index;
    }//end of function 
	
    /**
     * Fetch latest N records.
     *
     * @access public
     * @input:  N:integer
     * @output: Array with data.
     */
	function getLastRec($count_records)
    {
    	$ret = array();

        $query = $this->db->get($this->table_name, $count_records);
		foreach ($query->result() as $row){
			array_push($ret, $row);
		}

		$query->free_result();

        return $ret;

    } // end of function

	function deleteRec($data)
	{
		$this->db->delete($this->table_name, array($this->pk => $data[$this->pk])); 
	} // end of function
	
	function insertRec($data)
	{
		$this->db->insert($this->table_name, $data);
		return $this->db->insert_id();
	} // end of function

	function updateRec($data)
	{
		$this->db->where('id', $data['id']);
		$this->db->update($this->table_name, $data); 	
	} // end of function
	
} // end of class



class SelectQuery {

    var $select;
    var $from;
    var $where;
    var $group_by;
    var $order_by;
    var $limit;

    var $sub_queries;


    /**
     * Constructor.
     *
     * @access public
     * @input:  $q array with needed values
     * @output: NULL
     */
    function __construct($q)
	{
        $this->select   = isset($q['select'  ]) ? $q['select'  ] : '*';
        $this->from     = isset($q['from'    ]) ? $q['from'    ] : '' ;
        $this->where    = isset($q['where'   ]) ? $q['where'   ] : '1=1';
        $this->group_by = isset($q['group_by']) ? $q['group_by'] : '' ;
        $this->having   = isset($q['having'])   ? $q['having']   : '' ;
        $this->order_by = isset($q['order_by']) ? $q['order_by'] : '' ;
        $this->limit    = isset($q['limit'   ]) ? $q['limit'   ] : '' ;

        $this->sub_queries = array();

		//Console::logSpeed('SHIN_SelectQuery begin work, Time taken to get to line: '.__FILE__.'::'.__LINE__);
		//Console::logMemory($this, 'SHIN_SelectQuery. Size of class: ');
    }


    /**
     * Add new sub-query (creating temporary table with given name).
     * The query is based on given SELECT, FROM and GROUP BY clauses,
     * merged with FROM and WHERE clauses of the main query.
     * (Really, FROM in merged, while ORDER BY and LIMIT is ignored.)
     *
     * @access public
     * @input:  $t_name string Temporary name, $q array with needed values
     * @output: NULL
     */
    function add_sub_query($t_name, $q)
	{
        $str =
            "create temporary table $t_name " .
            '  (primary key(id)) ' .
            "select    $q[select]   " .
            "    from  $this->from  " . $q['from'] .
            "    where $this->where " .
            ($q['group_by'] ? " group by $q[group_by]": '') .
            ($this->limit   ? " limit    $this->limit": '');

        $this->sub_queries[$t_name] = $str;
    }


    /**
     * Return complete query string assembled from clauses.
     *
     * @access public
     * @input:  NULL
     * @output: string 
     */
    function str()
	{
        return
            "SELECT $this->select" .
            " FROM $this->from  " .
            " WHERE $this->where " .
            ($this->group_by ? " GROUP BY $this->group_by": '') .
            ($this->having   ? " HAVING $this->having"    : '') .
            ($this->order_by ? " ORDER BY $this->order_by": '') .
            ($this->limit    ? " LIMIT $this->limit": '');			
    }


    /**
     * Add more statements to the clauses using given array.
     *
     * @access public
     * @input:  NULL
     * @output: string 
     */
    function expand($q)
	{
        $this->select .= isset($q['select'  ]) ? " , $q[select] " : '';
        $this->from   .= isset($q['from'    ]) ? " $q[from] " : '';
        $this->where  .= isset($q['where'   ]) ? " AND $q[where] " : '';
        $this->limit  .= isset($q['limit'   ]) ? " $q[limit] "  : '';

        $qwote = empty($this->order_by) ? '' : ', ';
        $this->order_by .= isset($q['order_by']) ? $qwote." $q[order_by]" : '';

        $qwote = empty($this->group_by) ? '' : ', ';
        $this->group_by .= isset($q['group_by']) ? $qwote." $q[group_by]" : '';

        $qwote = empty($this->having) ? '' : ' and ';
        $this->having .= isset($q['having']) ? $qwote."$q[having]" : '';
    }
} // end of class


/* End of file UP_Model.php */
/* Location: application\core\UP_Model.php */