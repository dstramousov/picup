<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @package		ShinPHP framework
 * @author		
 * @copyright	
 * @license		
 * @link		
 * @since		Version 0.1
 * @filesource  shinfw/libraries/SHIN_Image.php
 */


/**
 * ShinPHP framework wrapper for Smarty->templater.
 *
 * @package		ShinPHP framework
 * @subpackage	Library
 * @author		
 * @link		shinfw/libraries/SHIN_Image.php
 */

//define('SMARTY_LIBRARY_FOLDER', 'smarty3');
define('SMARTY_LIBRARY_FOLDER', 'application/libraries/smarty');

require SMARTY_LIBRARY_FOLDER."/Smarty.class.php";

class UP_Templater extends Smarty
{
    protected $_globals = array();
	
	
	var $config;
    
	/**
	 * Init smarty. 
	 *
	 * @param string $_mode base folder prefix for trying to find tempaltes. 
	 * @return void
	 */
     
     function __construct()
     {
        parent::__construct();

        log_message('debug', 'Templater has initialized.');
		
		$config =& get_config();
		
		$this->template_dir	= $config['smarty_template_dir'];
		$this->compile_dir	= $config['smarty_compile_dir'];
		$this->cache_dir	= $config['smarty_compile_dir'];
		
        if (function_exists('base_url')) {
            $this->assign("base_url", base_url()); 
        }
     }

    /**
     * Render some page.
     *
     * @access public
     * @param string $template_id template file name
     * @param integer $cache_id cache id
     * @return void
     */
    function render($template_name = 'index', $cache_id = null)
    {	
		$CI =& get_instance();
		
		if($CI->up_jsmanager->needed_js_include)
		{
			$this->assign('additional_js', $CI->up_jsmanager->renderIncludes());
		}
	
		if($CI->up_jsmanager->domready_components)
		{
			$this->assign('additional_doomready_js', $CI->up_jsmanager->renderDocReady());
		}

/*		
		if(self::$_jsmanager){
			self::$_libs['templater']->assign('jsincludes', self::$_jsmanager->renderIncludes());
			self::$_libs['templater']->assign('jsnondocready', self::$_jsmanager->renderOutDocReady());
			self::$_libs['templater']->assign('jsdocready', self::$_jsmanager->renderDocReady());
		}

		if(self::$_cssmanager){			
			self::$_cssmanager->addIncludes(self::$_config['core']['shinfw_base_url'].'/'.self::$_config['core']['shinfw_folder']."/themes/".self::$_theme.'/css/'.self::$_config['theme']['general_css_file']);
			self::$_libs['templater']->assign('cssincludes', self::$_cssmanager->renderIncludes());
		}
*/
        if (strpos($template_name, '.') === false) {
            $template_name .= '.tpl';
        }

        $__sep = array("\\", '/');
		
		$CI =& get_instance();
		if($CI->browser_title){
			parent::assign('title', implode(' :: ', $CI->browser_title));
		}
		parent::assign('version', $CI->config->item('appversion'));
		

        if (is_array($this->_globals) AND sizeof($this->_globals) > 0){
            foreach ($this->_globals as $k => $v){
                parent::assignByRef($k, $v);
            }
        }
		
		$memory	 = ( ! function_exists('memory_get_usage')) ? '0' : round(memory_get_usage()/1024/1024, 2).'MB';
		$this->assign('memory_usage', $memory);
		
		global $BM;
		$elapsed = $BM->elapsed_time('total_execution_time_start', 'total_execution_time_end');
		$this->assign('elapsed_time', $elapsed);
		
        return parent::display($template_name, $cache_id);
    }
    
	/**
	 * Set block to the template
	 *
	 * @param string $resource_name name of template
	 * @param string $variable name of variable uses as container
	 * @param string $cache_id cache id 
	 * @return void or parsed templater content
	 */
	function setBlock($resource_name, $variable = NULL, $cache_id = null)
	{                              
        if (strpos($resource_name, '.') === false) {
			$resource_name .= '.tpl';
		}
        
        if ($variable){
            $content = parent::fetch($theme.'/'.$resource_name, $cache_id);
            
            $this->_globals[$variable] = $content;
            
            parent::clear_all_assign();

        } else {
		

            return parent::fetch($resource_name, $cache_id);
        }
	}

	/**
	 * Just parse template and show to display. After that application will be die.
	 *
	 * @param string $template_name Template name 
	 * @return void
	 */
	public function displayFile($template_name)
	{
		$this->render($template_name);
		exit();
	}


    /**
     * Return pointer for this class.
     *
     * @access public
     * @param NULL
     * @return pointer for this class.
     */
	public function get_instance()
	{
		return $this;
	}

} // End of class 


/* End of file SHIN_Templater.php */
/* Location: shifw/library/SHIN_Templater.php */