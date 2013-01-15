<?php

/**
 * memcacheÀà
 * @author : kaiven
 */

class Class_Memcache
{
	private $class_memcache;
	public function __construct()
	{
		$this->_init();
	}
	
	private function _init()
	{
		if ( ! class_exists('Memcache') )
		{
			$this->_show_error();
		}
		
		$this->class_memcache	=	new Memcache();
	}
	
	public function add_server($memcache_config)
	{
		if ( ! $this->_connect($memcache_config) )
		{
			$this->_show_error();
		}
	}
	
	private function _connect($memcache_config)
	{
		if ( is_array($memcache_config) )
		{
			foreach ($memcache_config as $value)
			{
				if ( ! $this->class_memcache->addServer($value['host'], $value['port']) )
				{
					$this->_show_error();
				}
			}
		}
		
		return TRUE;
	}
	
	public function add_value($key, $value)
	{
		if ( ! $this->class_memcache->add($key, $value) )
		{
			return FALSE;
		}
		return TRUE;
	}
	
	public function get_value($key)
	{
		if ( $return = $this->class_memcache->get($key) )
		{
			return $return;
		}
		else
		{
			return FALSE;
		}
	}
	
	public function set_value($key, $value)
	{
		if ( ! $this->class_memcache->set($key, $value) )
		{
			return FALSE;
		}
		return TRUE;
	}
	
	public function del_value($key)
	{
		if ( ! $this->class_memcache->delete($key) )
		{
			return FALSE;
		}
		return TRUE;
	}
	
	public function increment($key, $value)
	{
		return $this->class_memcache->increment($key, $value);
	}
	
	private function _show_error()
	{
		exit('connect memcache failed!');
	}
	
	public function _close()
	{
		$this->class_memcache->close();
	}
}