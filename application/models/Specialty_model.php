<?php

class Specialty_model extends CI_Model{

	function __construct(){
		parent::__construct();
		$this->tablename = 'specialty';
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
	
	function all($order_by = "name", $order = "asc"){
		$this->db->order_by($order_by, $order);
		$query = $this->db->get($this->tablename);
		$result = $query->result();
		return $result;
	}
}
?>
