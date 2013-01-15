<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 菜单类
 * @author: kaiven
 */

class Model_Menu extends MY_Model
{
	private $_table	=	'`union_menu`';
	public function __construct()
	{
		parent::__construct();
	}
	
	public function get_menu($conditions = array(), $limit = NULL)
	{
		$where_sql	=	$conditions ? " WHERE ".implode(' AND ', $conditions) : "";
		$where_sql	.=	"  ORDER BY id ASC ";
		if ( $limit )
		{
			$where_sql	.=	" LIMIT ".$limit;
		}
		
		$sql	=	"SELECT * FROM {$this->_table} " . $where_sql;
		
		$menu	=	$limit == 1 ? $this->get_row( $sql ) : $this->get_all( $sql );
		return $menu;
	}
	
	public function get_menu_by_id($menu_id)
	{
		$where_sql	=	"";
		switch (gettype($menu_id))
		{
			case 'integer':
				$where_sql	=	" id = ".$menu_id;
				$sql		=	"SELECT * FROM {$this->_table} WHERE ".$where_sql." AND display = 1";
				$menu_list	=	$this->get_row($sql);
				break;
			case 'array':
				$where_sql	=	" id IN (".implode(',', $menu_id).")";
				$sql		=	"SELECT * FROM {$this->_table} WHERE ".$where_sql." AND display = 1";
				$menu_list	=	$this->get_all($sql);
				break;
			case 'string':
				if ( $menu_id != 'ALL' )
				{
					return array();
				}
				$sql		=	"SELECT * FROM {$this->_table} WHERE display = 1";
				$menu_list	=	$this->get_all($sql);
				
				break;
			default:
				return array();
				break;
		}
		
		return $menu_list;
	}
	
	public function execute_sql($sql)
	{
		return $this->execute($sql);
	}
}