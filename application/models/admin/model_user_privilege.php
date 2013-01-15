<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Model_User_Privilege extends MY_Model
{
	private $_table	=	'`union_user_privilege`';
	public function __construct()
	{
		parent::__construct();
	}
	
	public function get_privilege($user_id)
	{
		$sql		=	"SELECT * FROM ".$this->_table." WHERE user_id = ".$user_id;
		$privilege	=	$this->get_row( $sql );
		return $privilege;
	}
	
	public function save_privilege($user_id, $privilege = NULL)
	{
		$sql	=	"SELECT * FROM ".$this->_table." WHERE user_id = ".$user_id;
		$user	=	$this->get_row($sql);
		if ( !$user )
		{
			$sql	=	"INSERT INTO ".$this->_table."(user_id, privilege)".
						"VALUES(".$user_id.", '".$privilege."')";
			return $this->execute($sql);
		}
		else
		{
			$sql	=	"UPDATE ".$this->_table." SET privilege = '".$privilege."' WHERE user_id = ".$user_id;
			return $this->execute($sql);
		}
	}
}