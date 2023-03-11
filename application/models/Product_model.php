<?php

class Product_model extends CI_Model{

	function __construct(){
		parent::__construct();
		$this->tablename = 'product';
	}
  
    function id($id){
		$this->db->where('id', $id);
		$this->db->where('active', true);
		$query = $this->db->get($this->tablename);
		$result = $query->result();
		if ($result) return $result[0]; else return null;
	}
  
    function ids($ids, $order_by = "description", $order = "asc"){
		$this->db->where('active', true);
		$this->db->where_in('id', $ids);
		$this->db->order_by($order_by, $order);
		$query = $this->db->get($this->tablename);
		$result = $query->result();
		return $result;
	}
  
    function code($code){
		$this->db->where('code', $code);
		$this->db->where('active', true);
		$query = $this->db->get($this->tablename);
		$result = $query->result();
		if ($result) return $result[0]; else return null;
	}
	
	function filter($filter, $limit = "", $offset = ""){
		if ($filter) $this->db->where($filter);
		$this->db->where('active', true);
		$query = $this->db->get($this->tablename, $limit, $offset);
		$result = $query->result();
		return $result;
	}
	
	function count($filter){
		$this->db->where('active', true);
		$this->db->where($filter);
		$query = $this->db->get("product");
		return $query->num_rows();
	}
	
	function all(){
		$this->db->where('active', true);
		$this->db->order_by("category_id", "asc");
		$this->db->order_by("description", "asc");
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
	
	function change_category($from, $to){
		$this->db->where('category_id', $from);
		return $this->db->update($this->tablename, array("category_id" => $to));
	}
	
	function update_filter($filter, $data){ 
		$this->db->where($filter);
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
		$query = $this->db->get("product_category");
		$result = $query->result();
		if ($result) return $result[0]; else return null;
	}
	
	function category_all($filter = null){
		if ($filter) $this->db->where($filter);
		$this->db->order_by("name", "asc");
		$query = $this->db->get("product_category");
		$result = $query->result();
		return $result;
	}
	
	function category_insert($data){ 
		$this->db->insert("product_category", $data);
		return $this->db->insert_id();
	}
	
	function category_update($id, $data){ 
		$this->db->where('id', $id);
		return $this->db->update("product_category", $data);
	}
	
	function category_delete($id){
		$this->db->where('id', $id);
		return $this->db->delete("product_category");
	}
	
	//image handle
	function image($id){
		$this->db->where('id', $id);
		$query = $this->db->get("product_image");
		$result = $query->result();
		if ($result) return $result[0]; else return null;
	}
	
	function images($product_id){
		$this->db->where('product_id', $product_id);
		$query = $this->db->get("product_image");
		$result = $query->result();
		return $result;
	}
	
	function image_insert($data){
		$this->db->insert("product_image", $data);
		return $this->db->insert_id();
	}
	
	function image_count($product_id){
		$this->db->where('product_id', $product_id);
		$query = $this->db->get("product_image");
		return $query->num_rows();
	}
	
	function image_update($id, $data){
		$this->db->where('id', $id);
		return $this->db->update("product_image", $data);
	}
	
	function image_delete($id){
		$this->db->where('id', $id);
		return $this->db->delete("product_image");
	}
}
?>
