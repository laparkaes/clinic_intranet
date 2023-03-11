<?php

class Image_model extends CI_Model{

	function __construct(){
		parent::__construct();
		$this->tablename = 'image';
	}
  
    function id($id){
		$this->db->where('id', $id);
		$query = $this->db->get($this->tablename);
		$result = $query->result();
		if ($result) return $result[0]; else return null;
	}
  
    function ids($ids, $order_by = "name", $order = "asc"){
		if ($ids){
			$this->db->where_in('id', $ids);
			$this->db->order_by($order_by, $order);
			$query = $this->db->get($this->tablename);
			$result = $query->result();
			return $result;	
		}else return null;
	}
	
	function filter($filter, $limit = "", $offset = "", $order_by = "name", $order = "asc"){
		if ($filter) $this->db->where($filter);
		$query = $this->db->get($this->tablename, $limit, $offset);
		$result = $query->result();
		return $result;
	}
	
	function all($order_by = "name", $order = "asc"){
		$this->db->order_by($order_by, $order);
		$query = $this->db->get($this->tablename);
		$result = $query->result();
		return $result;
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
	
	//category handle
	function category($id = null, $name = null){
		if ($id) $this->db->where('id', $id);
		if ($name) $this->db->where('name', $name);
		$query = $this->db->get("image_category");
		$result = $query->result();
		if ($result) return $result[0]; else return null;
	}
	
	function category_all($filter = null){
		if ($filter) $this->db->where($filter);
		$this->db->order_by("name", "asc");
		$query = $this->db->get("image_category");
		$result = $query->result();
		return $result;
	}
}
?>
