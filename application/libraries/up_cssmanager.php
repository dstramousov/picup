<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed 1');
/**
 * @author		
 * @copyright	
 * @license		
 * @link		
 * @since		Version 0.1
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 *
 * @subpackage	Core
 * @category	CSS_Manager
 * @author		
 * @link		
 */
class UP_CSSManager
{
	var $needed_css_include = array();

	/**
	 * Constructor
	 *
	 * @access  public
	 * @return  NULL
	 */
	function __construct()
	{
		//SHIN_Core::log('debug', 'SHIN_CSSManager component initialized.');
		//Console::logSpeed('|CC| SHIN_CSSManager begin work, Time taken to get to line: '.__FILE__.'::'.__LINE__);
	}


	/**
	 * Add needed css file includes for some component.
     *
     * @access public
     * @params param:string OR array.
     * @return NULL.
	 */
	public function addIncludes($param)
	{
		if(is_array($param))
		{
			foreach($param as $p)
			{
				array_push($this->needed_css_include, $p);
			}

		} else {
			array_push($this->needed_css_include, $param); 
		}
	}


	/**
	 * Render and return all includes of CSS.
     *
     * @access public
     * @params $media string .
     * @return param:string.
	 */
	public function renderIncludes($media = 'all')
	{
		$_ret			= "\n";
		$already_used	= array();
		
		foreach($this->needed_css_include as $i)
		{
			if (!in_array($i, $already_used))
			{
				$_ret .= '<link rel="stylesheet" type="text/css" media="'.$media.'" href="'.$i.'" />'."\n";
				array_push($already_used, $i);
			}	
		}

		return $_ret;
	} // end of function

} // END UP_CSSManager class

/* End of file UP_CSSManager.php */