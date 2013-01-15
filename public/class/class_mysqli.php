<?php

/**
 * mysqli类
 * @author : kaiven
 */

class Class_MySQLi
{
	/* 定义名柄 */
	private $db;
	private $_query;
	
	public function __construct()
	{
		$this->_init();
	}
	
	private function _init()
	{
		if ( ! class_exists('mysqli') )
		{
			$this->_show_error('对不起,系统不支持Mysqli扩展');
		}
		$config	=	$GLOBALS['config'];
		$db	=	new mysqli($config['web']['host'], $config['web']['user'], $config['web']['pass'], $config['web']['name']);
		
		if ( $db->connect_error )
		{
			die($db->connect_error);
		}
		
		$this->db	=	$db;
	}
	
	public function get_all($sql)
	{
		$this->execute($sql);
		$result	=	array();
		while ($value = $this->_query->fetch_array(MYSQLI_ASSOC))
		{
			$result[]	=	$value;
		}
		return $result;
	}
	
	public function get_row($sql)
	{
		$this->execute($sql);
		$result	=	$this->_query->fetch_array();
		return $result;
	}
	
	public function execute($sql)
	{
		$this->file_write(LOG, "-- ".date("Y-m-d H:i:s")." --\n".$sql.";\n\n", 'ab+');
		if ( ! $this->_query = $this->db->query($sql) )
		{
			$this->file_write('sql_error.log', $sql."\n\n", 'ab+');
			$this->_show_error('Sql执行失败!');
		}
		return TRUE;
	}
	private function _show_error($msg)
	{
		exit($msg);
	}
	
	private function file_write($file_name, $content, $method = 'w')
	{
		if (!$file_name || !$content)
		{
			return FALSE;
		}
		if (!file_exists($file_name))
		{
			touch($file_name);
		}
		
		$fp		=	fopen($file_name, $method);
		flock($fp, LOCK_EX);
		fwrite($fp, $content);
		flock($fp, LOCK_UN);
		fclose($fp);
	}
}