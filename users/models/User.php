<?php

require_once("../../ws/inc/error.inc.php");
require_once("../../ws/inc/database.inc.php");

class User {
	
	private $dbh;
	
	public function __construct()	{		
		$this->dbh = pgConnection();
	}

	public function getUsers(){				
		//$sth = $this->dbh->prepare("SELECT u.id,u.name,(select r.label from r_role r where r.id=u.role_id) as role,u.email FROM nz.\"user\" u");
		$sth = $this->dbh->prepare("SELECT * FROM nz.\"user\" u WHERE role_id<>1 ORDER BY name");
		$sth->execute();
		return json_encode($sth->fetchAll());
	}

	public function add($user){		
		$sth = $this->dbh->prepare("INSERT INTO nz.\"user\" (name, email, password, role_id) VALUES (?, ?, ?,2)");
		$sth->execute(array($user->name, $user->email, $user->passwd));		
		return json_encode($this->dbh->lastInsertId());
	}
	
	public function delete($user){				
		$sth = $this->dbh->prepare("DELETE FROM nz.\"user\" WHERE id=?");
		$sth->execute(array($user->id));
		return json_encode(1);
	}
	
	public function updateValue($user){		
		$sth = $this->dbh->prepare("UPDATE nz.\"user\" SET ". $user->field ."=? WHERE id=?");
		$sth->execute(array($user->newvalue, $user->id));
		return json_encode(1);	
	}
}
?>