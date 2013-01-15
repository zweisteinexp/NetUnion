<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// 加载 model

class MY_Model extends CI_Model
{
	private $query;
	private $col = array();
	public function __construct()
	{
		parent::__construct();
	}
	
	private function _query($sql)
	{
		if ( !$sql )
		{
			return FALSE;
		}
		
		$this->query	=	$this->db->query( $sql );
		
		if ( !$this->query )
		{
			$this->show_error("Query Is Error\n".$sql."\n");
		}
		return TRUE;
	}
	
	public function execute($sql)
	{
		return $this->_query($sql);
	}
	
	/*
	public function get_one($sql)
	{
		$this->_query( $sql );
		$row	=	$this->query->row();
		return $row && $row[0] ? $row[0] : NULL;
	}
	*/
	
	public function get_row($sql)
	{
		$this->_query( $sql );
		$row	=	$this->query->row_array();
		return $row ? $row : array();
	}
	
	public function get_all($sql)
	{
		$this->_query( $sql );
		$list	=	$this->query->result_array();
		return $list;
		
	}
	
	public function insert($table, $param)
	{
		$sql	=	"INSERT INTO {$table}(".implode(',', array_keys($param)).")".
					"VALUES(".implode(',', array_values($param)).")";
					
		return $this->_query( $sql );
	}
	
	public function update($table, $param, $where)
	{
		foreach($param as $key=>$value)
		{
			$this->col[]=$key."=".$value;
		}
		
		$sql    =   "UPDATE {$table} SET ".implode(',',$this->col)." WHERE ".implode(',', array_keys($where)).
					"= ".implode(',', array_values($where))."";
		return $this->_query( $sql );
	}
	
	public function get_affect_row($sql)
	{
		$this->_query( $sql );
		return $this->db->affected_rows(); 
	}
	
	public function get_record($sql)
	{
		/*
		while ($value = $this->query->_fetch_assoc())
		{
			$list[]	=	$value;
		}
		return $list;
		*/
	}
	
	private function _show_error($msg)
	{
		echo $msg;
		exit;
	}
}