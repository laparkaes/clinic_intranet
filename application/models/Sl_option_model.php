<?php

class Sl_option_model extends CI_Model{

	function __construct(){
		parent::__construct();
		$this->tablename = 'sl_option';
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
		return $result;
	}
  
    function codes($codes){
		$this->db->where_in('code', $codes);
		$query = $this->db->get($this->tablename);
		$result = $query->result();
		return $result;
	}
  
    function description($description){
		$this->db->where('description', $description);
		$query = $this->db->get($this->tablename);
		$result = $query->result();
		if ($result) return $result[0]; else return null;
	}
  
    function code_description($code, $description){
		$this->db->where('code', $code);
		$this->db->where('description', $description);
		$query = $this->db->get($this->tablename);
		$result = $query->result();
		if ($result) return $result[0]; else return null;
	}
}
?>
