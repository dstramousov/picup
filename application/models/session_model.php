<?php

class Session_model extends UP_Model
{
	var $id;
	var $uid;
	var $stored;
	var $updated;
	var $host;

    var $max_session;
    var $idle_session;

    function __construct($id=NULL)
	{	
		$this->table_name = 'session';
        parent::__construct($this->table_name,$id);

		$CI =& get_instance();
		$this->max_session	= $CI->config->item('sess_expiration');
		$this->idle_session	= $CI->config->item('sess_time_to_update');

		log_message('debug', 'Model Session_model has initialized.');
    }
	
    // Cookie functions:
    /**
     * Reads session data from cookie, returns true of the session is ok.
     *
     * @access public
     * @return bool
     */
    function read()
    {

        $this->delete_expired_sessions();

        // read cookie:
        $this->id = $this->param_cookie('session_id');
        if ($this->id == '') {
			log_message('debug', 'Model Session_model "No cookie found"');
            return false;
        }

		$query = $this->db->query("SELECT id, user_id, stored, updated, host FROM ".$this->table_name." WHERE id=".$this->db->escape($this->id));
		//dump("SELECT id, user_id, stored, updated, host FROM ".$this->table_name." WHERE id=".$this->db->escape($this->id));

		if ($query->num_rows() > 0){

			foreach ($query->result() as $row) {

				$this->id		= $row->id;
				$this->user_id	= $row->user_id;
				$this->stored	= $row->stored;
				$this->updated	= $row->updated;
				$this->host		= $row->host;

				$data = array('updated' => now());
	
				$this->db->where('id', $this->id);
				$this->db->update($this->table_name, $data); 

				$ret = true;
			}

		} else {
	        $ret = false;
		}

		$query->free_result();

		return $ret;
    }

    /**
     * Delete current session from DB.
     *
     * @access public
     */
    function del(){
		log_message('debug', 'Model Session_model delete session with id='.$this->id);
		$this->db->delete($this->table_name, array('id' => $this->id)); 
    }


    /**
     * Starts new session for given user ID.
     *
     * Start new session for given user ID.
     * Store data in database and in cookie.
     *
     * @access public
     */
    function start($uid)
    {
        $host = $this->input->ip_address();

        list($msec, $sec) = explode(' ', microtime());
        $code = md5($uid . $host . $sec . $msec);

        $this->id = $code;

		$data = array(
		               'id'		=> $code,
		               'user_id'	=> $uid,
		               'host'	=> $host,
		               'stored'	=> now(),
						'updated'	=> now(),
					 );

		$this->db->insert($this->table_name, $data);
		//dump(1);
        $this->write_cookie();
		log_message('debug', 'Session started. id='.$this->id);
    }

    /**
     * Delete stale sessions from database.
     *
     * @access public
     */
    function delete_expired_sessions()
    {
        $query_str =
            "delete from ".$this->table_name." where" .
            " stored < date_sub(now(), interval $this->max_session  minute)" .
            " or updated < date_sub(now(), interval $this->idle_session minute)";

        $this->db->query($query_str);
    }

	function param_cookie($name){

		$_c = get_cookie('session_id');
	    return $_c ? $_c : '';

	    //return isset($_COOKIE[$name]) ? $_COOKIE[$name] : '';
	}

    /**
     * Write code to cookie.
     *
     * @access public
     */
    function write_cookie()
    {
        //setcookie('session_id', $this->id, (time() + 60*60*24));  // 24 hours

		$cookie = array(
					'name'   => 'session_id',
					'value'  => $this->id,
					'expire' => (time() + 60*60*24),
               );

		set_cookie($cookie); 
    }

} // end of class

/* End of file session_model.php */
/* Location: ./system/application/models/session_model.php */	