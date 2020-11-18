<?php

/**
 * application\controllers\Admin.php
 *
 * Admin controller for manipulation with databse.
 *
 */

class PageNotFound extends UP_Controller {


    function __construct()
    {
		$this->app_mode = 'main';
		parent::__construct();
		log_message('debug', 'Page Not Found controller has initialized.');
		
		if($this->user->isDefinite()){
			redirect(base_url().'myprofile');
		} else {
			redirect(base_url());
		}		
	} // and of function
	
	
} //end of class

/* End of file Admin.php */
/* Location: application/controllers/Admin.php */