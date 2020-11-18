<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed 1');
/**
 * @package		ShinPHP framework
 * @author		
 * @copyright	
 * @license		
 * @link		
 * @since		Version 0.1
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * ShinPHP JavaScript Manager
 *
 * @package		ShinPHP framework
 * @subpackage	Core
 * @category	JSManager
 * @author		
 * @link		
 */
class UP_JSManager
{

	/**
	 * Contain all requested js. 
	 */
	var $needed_js_include = array();


	/**
	 * Contain all added conmponents in to doomready function. 
	 */
	var $domready_components = array();

	/**
	 * Relation between some components. 
	 */
	var $glue_component_js = array();
	
	/**
	 * Who is very real inject in to the page. 
	 */
	var $real_injected = array();
	
	/**
	 * Not in the docready array. 
	 */
	var $not_in_docready = array();
	
	/**
	 * Not in the docready array. 
	 */
	var $crudObjects = array();
	/**
	 * Constructor
	 *
	 * @access  public
	 * @return  NULL
	 */
	function __construct()
	{
		//SHIN_Core::log('debug', 'SHIN_JSManager component initialized .');
		//Console::logSpeed('|CC| SHIN_JSManager begin work, Time taken to get to line: '.__FILE__.'::'.__LINE__);
	}

	/**
	 * Add needed includes for some component.
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
				array_push($this->needed_js_include, $p);
			}

		} else {
			array_push($this->needed_js_include, $param); 
		}
    }
		
	/**
	 * Add needed includes for some component NOT IN domready.
     *
     * @access public
     * @params param:string OR array.
     * @return NULL.
	 */
	public function addIncludesOutDomready($param)
	{   
		if(is_array($param))
		{
			foreach($param as $p)
			{
				array_push($this->not_in_docready, $p);
			}

		} else {
			array_push($this->not_in_docready, $param); 
		}
	}
	
	

	/**
	 * Add for including some custom JavaScript code
     * Example: SHIN_Core::$_jsmanager->insertJSFromFile(array(SHIN_Core::$_config['core']['base_url'] . 'js/test.js'));
     * 
     * @access public
     * @params NULL.
	 
     * @return param:string.
	 */
	function insertJSFromFile($source_file)
	{
		$this->addIncludes($source_file);
	}
	
	/**
	 * Add js for make relation between 2 components.
     *
     * @access public
     * @params $js_glue_component_code string for custom js code.
	 
     * @return param:string.
	 */
	function addGlueJS($js_glue_component_code)
	{
		array_push($this->glue_component_js, $js_glue_component_code);
	}

	/**
	 * Render and return all includes of JS.
     *
     * @access public
     * @params NULL.
     * @return param:string.
	 */
	public function renderIncludes()
	{
		$_ret = "\n";
		$already_used = array();
		
		//dump($this->needed_js_include);
		foreach($this->needed_js_include as $i)
		{
			if (!in_array($i, $already_used))
			{
				$_ret .= '	<script src="'.$i.'" type="text/javascript"></script>'."\n";
				array_push($already_used, $i);
			}	
		}
		
		return $_ret;
	}


	/**
	 * Way for add some JS code in any place of the script.
     *
     * @access public
     * @params custom_code:string.
     * @return NULL.
	 */
	public function renderCustomCode($custom_code)
	{
		//Console::logMemory($custom_code, '|CC| SHIN_JSManager::renderCustomCode(). Size of inserted part before render JS insjection:');
		return '<script type="text/javascript">'.$custom_code.'</script>'."\n";
	}



	/**
	 * Add in to domready.
     *
     * @access public
     * @params custom_code:string.
     * @return NULL.
	 */
	public function addOutDomReadyComponent($component_code)
	{
		array_push($this->not_in_docready, $component_code);
	}

	/**
	 * Add new component.
     *
     * @access public
     * @params custom_code:string.
     * @return NULL.
	 */
	public function addComponent($component_code)
	{
		array_push($this->domready_components, $component_code);
	}


	/**
	 * Generate right document ready injection for html page.
     *
     * @access public
     * @params NULL.
     * @return _ret:string.
	 */
	public function renderOutDocReady()
	{
		Console::logMemory($this->not_in_docready, '|CC| SHIN_JSManager::renderOutDocReady(). Size of inserted part before render JS insjection:');

		$_ret = '';
		if($this->not_in_docready == NULL){return '';}
	
		$_ret .= '<script type="text/javascript" language="javascript">';
		foreach($this->not_in_docready as $c)
		{
			$_ret .= "\n		".$c."\n";			
		}
		
		$_ret .=	'

	</script>'; 

		return $_ret;
	}
	
	/**
	 * Generate right document ready injection for html page.
     *
     * @access public
     * @params NULL.
     * @return _ret:string.
	 */
	public function renderDocReady()
	{		

		if($this->domready_components == NULL){return '';}

		$_ret = "\n\n".'<script type="text/javascript" language="javascript">

	$(document).ready(function(){'."\n\n";

		foreach($this->domready_components as $c)
		{
			$_ret .= "\n		".$c."\n";			
		}
		
		foreach($this->glue_component_js as $c)
		{
			$_ret .= "\n		".$c."\n";			
		}

		$_ret .=	'});

	</script>'; 

		return $_ret;
	}
	
	
	/**
	 * Add in to the page all js components.
     *
     * @access public
     * @params custom_code:string.
     * @return NULL.
	 */
	function finalRender()
	{
		$ret = '';
		
		$ret  = $this->renderOutDocReady();
		$ret .= $this->renderDocReady();
		
		return $ret;
	}


} // END SHIN_JSManager class

/* End of file SHIN_JSManager.php */
/* Location: ./core/SHIN_JSManager.php */