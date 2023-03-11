<?php

class Status_model extends CI_Model{

	function __construct(){
		parent::__construct();
		$this->tablename = 'status';
	}
  
    function id($id){
		$this->db->where('id', $id);
		$query = $this->db->get($this->tablename);
		$result = $query->result();
		if ($result) return $result[0]; else return null;
	}
  
    function code($code){
		$this->db->where('code', $code);
		$query = $this->db->get($this->tablename);
		$result = $query->result();
		if ($result) return $result[0]; else return null;
	}
  
    function filter($arr = null){
		if ($arr) $this->db->where_in('code', $arr);
		$query = $this->db->get($this->tablename);
		$result = $query->result();
		return $result;
	}
	
	function all($order_by = "id", $order = "asc"){
		$this->db->order_by($order_by, $order);
		$query = $this->db->get($this->tablename);
		$result = $query->result();
		return $result;
	}
}
?>
