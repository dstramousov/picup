<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * system\application\libraries\YW_Constants.php
 *
 * This class contain and define some constants for all application.
 *
 * @version 1.0
 * @package YW_Constants
 */


class UP_Constants {

	/**
	 * Define constants for all application.
	 */
    function __construct()
    {

		/**
		 * Empty string.
		 */
		define('CT_EMPTY_STR', '');		

    
		/**
		 * Name of primary key in any table.
		 */
		define('CT_PK', 'id');		


		define('ERR_WRONG_PASS', 'ERR_WRONG_PASS');		
		define('ERR_USER_NOT_FOUND', 'ERR_USER_NOT_FOUND');		
		
		/**
		 * Message type notice.
		 */
		define('CT_MESSAGE_NOTICE', 'notice');
    
		/**
		 * Message type success.
		 */
		define('CT_MESSAGE_SUCCESS', 'success');
    
		/**
		 * Message type error.
		 */
		define('CT_MESSAGE_ERROR', 'error');
    
		/**
		 * Oper message must be show.
		 */
		define('CT_OPER_MESSAGE_SHOW', '1');

		/**
		 * Oper message must be show.
		 */
		define('CT_OPER_MESSAGE_NOTSHOW', '0');
		
		
		/**
		 * Oper message must be show.
		 */
		define('IMAGE_NOT_FOUND', 'image-not-found.gif');
		
		

    }
}
