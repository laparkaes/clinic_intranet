<?php

class Account_model extends CI_Model{

	function __construct(){
		parent::__construct();
		$this->tablename = 'account';
	}
  
    function id($id){
		$this->db->where('id', $id);
		//$this->db->where('active', true);
		$query = $this->db->get($this->tablename);
		$result = $query->result();
		if ($result) return $result[0]; else return null;
	}
  
    function email($email){
		$this->db->where('email', $email);
		$this->db->where('active', true);
		$query = $this->db->get($this->tablename);
		$result = $query->result();
		if ($result) return $result[0]; else return null;
	}
	
	function filter($filter, $limit = "", $offset = ""){
		if ($filter) $this->db->where($filter);
		//$this->db->where('active', true);
		$query = $this->db->get($this->tablename, $limit, $offset);
		$result = $query->result();
		return $result;
	}
	
	function all($order_by = "registed_at", $order = "desc"){
		//$this->db->where('active', true);
		$this->db->order_by($order_by, $order);
		$query = $this->db->get($this->tablename);
		$result = $query->result();
		return $result;
	}
	
	function counter($filter){
		if ($filter) $this->db->where($filter);
		$this->db->where('active', true);
		$query = $this->db->get($this->tablename);
		return $query->num_rows();
	}
	
	function insert($data){ 
		$this->db->insert($this->tablename, $data);
		return $this->db->insert_id();		
	}
	
	function update($id, $data){ 
		$this->db->where('id', $id);	
		return $this->db->update($this->tablename, $data);
	}
	
	function delete($id){		
		$this->db->where('id', $id);	
		return $this->db->delete($this->tablename);		
	}
}
?>
