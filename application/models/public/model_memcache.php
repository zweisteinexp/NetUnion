<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * memcache类
 * @author : kaiven
 */

class Model_Memcache extends MY_Model
{
	private $class_memcache;
	public function __construct()
	{
		parent::__construct();
		
		$this->_init();
	}
	
	private function _init()
	{
		$this->class_memcache	=	new Memcache;
		$this->_connect();
	}
	
	private function _connect()
	{
		$memcache_config	=	get_config_item('memcache');
		
		if ( is_array($memcache_config) )
		{
			foreach ($memcache_config as $value)
			{
				$this->class_memcache->addServer($value['host'], $value['port']) || $this->_show_error();
			}
		}
	}
	
	public function add_value($key, $value)
	{
		$this->class_memcache->add($key, $value);
	}
	
	public function get_value($key)
	{
		$this->class_memcache->get($key);
	}
	
	public function set_value($key, $value)
	{
		$this->class_memcache->set($key, $value);
	}
	
	private function _show_error()
	{
		exit('connect memcache failed!');
	}
	
	private function _close()
	{
		$this->class_memcache->close();
	}
}