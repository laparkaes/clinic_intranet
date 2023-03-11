<?php

class Role_model extends CI_Model{

	function __construct(){
		parent::__construct();
		$this->tablename = 'role';
	}
  
    function id($id){
		$this->db->where('id', $id);
		$query = $this->db->get($this->tablename);
		$result = $query->result();
		if ($result) return $result[0]; else return null;
	}
  
    function name($name){
		$this->db->where('name', $name);
		$query = $this->db->get($this->tablename);
		$result = $query->result();
		if ($result) return $result[0]; else return null;
	}
}
?>
