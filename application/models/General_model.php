<?php

class General_model extends CI_Model{

    function id($tablename, $id){
		$this->db->where("id", $id);
		$query = $this->db->get($tablename);
		$result = $query->result();
		if ($result) return $result[0]; else return null;
	}
	
    function ids($tablename, $ids, $order_by = "id", $order = "asc"){
		if ($ids){
			$this->db->where_in("id", $ids);
			$query = $this->db->get($tablename);
			$result = $query->result();
			return $result;
		}else return array();
	}
	
    function filter($tablename, $filter, $order_by = "", $order = "", $limit = "", $offset = ""){
		if ($filter) $this->db->where($filter);
		if ($order_by) $this->db->order_by($order_by, $order);
		$query = $this->db->get($tablename, $limit, $offset);
		$result = $query->result();
		return $result;
	}
	
	function filter_adv($tablename, $filter, $filter_in = null, $order_by = "", $order = "", $limit = "", $offset = ""){
		if ($filter) $this->db->where($filter);
		if ($filter_in) foreach($filter_in as $f) $this->db->where_in($f["field"], $f["values"]);
		if ($order_by) $this->db->order_by($order_by, $order);
		$query = $this->db->get($tablename, $limit, $offset);
		$result = $query->result();
		return $result;
	}
	
	function sum($tablename, $col, $filter = null){
		$this->db->select_sum($col);
		if ($filter) $this->db->where($filter);
		$query = $this->db->get($tablename);
		$result = $query->result();
		return $result[0];
	}
	
	function find($tablename, $field1 = null, $field2 = null, $filter = null){
		if ($field1) foreach($filter as $f) $this->db->like($field1, $f);
		if ($field2 and $filter) $this->db->or_where_in($field2, $filter);
		//$this->db->order_by("code", "asc");
		$query = $this->db->get($tablename);
		$result = $query->result();
		return $result;
	}
	
	function find_count($tablename, $field1 = [], $field2 = "", $filter = array()){
		foreach($filter as $f) $this->db->like($field1, $f);
		$this->db->or_where_in($field2, $filter);
		$query = $this->db->get($tablename);
		return $query->num_rows();
	}
	
	function all($tablename, $order_by = "id", $order = "desc", $limit = "", $offset = ""){
		if ($order_by) $this->db->order_by($order_by, $order);
		$query = $this->db->get($tablename, $limit, $offset);
		$result = $query->result();
		return $result;
	}
	
	function only($tablename, $field, $where = null){
		$this->db->select($field);
		if ($where) $this->db->where($where);
		$this->db->group_by($field);
		$query = $this->db->get($tablename);
		$result = $query->result();
		return $result;
	}
	
	function counter($tablename, $filter, $group_by = null){
		if ($filter) $this->db->where($filter);
		if ($group_by) $this->db->group_by($group_by);
		$query = $this->db->get($tablename);
		return $query->num_rows();
	}
	
	function is_available($tablename, $data, $status_ids = null, $id = null){
		if ($id) $this->db->where('id !=', $id);
		$this->db->where('doctor_id', $data["doctor_id"]);
		$this->db->group_start();
		$this->db->group_start();
		$this->db->where('schedule_from <=', $data["schedule_from"]);
		$this->db->where('schedule_to >=', $data["schedule_from"]);
		$this->db->group_end();
		$this->db->or_group_start();
		$this->db->where('schedule_from <=', $data["schedule_to"]);
		$this->db->where('schedule_to >=', $data["schedule_to"]);
		$this->db->group_end();
		$this->db->or_group_start();
		$this->db->where('schedule_from >=', $data["schedule_from"]);
		$this->db->where('schedule_to <=', $data["schedule_to"]);
		$this->db->group_end();
		$this->db->group_end();
		if ($status_ids) $this->db->where_in('status_id', $status_ids);
		$query = $this->db->get($tablename);
		if ($query->num_rows()) return false; else return true;
	}
	
	function get_by_room($tablename, $data, $status_ids = null, $id = null, $room_id){
		if ($id) $this->db->where('id !=', $id);
		$this->db->where('room_id', $room_id);
		$this->db->group_start();
		$this->db->group_start();
		$this->db->where('schedule_from <=', $data["schedule_from"]);
		$this->db->where('schedule_to >=', $data["schedule_from"]);
		$this->db->group_end();
		$this->db->or_group_start();
		$this->db->where('schedule_from <=', $data["schedule_to"]);
		$this->db->where('schedule_to >=', $data["schedule_to"]);
		$this->db->group_end();
		$this->db->or_group_start();
		$this->db->where('schedule_from >=', $data["schedule_from"]);
		$this->db->where('schedule_to <=', $data["schedule_to"]);
		$this->db->group_end();
		$this->db->group_end();
		if ($status_ids) $this->db->where_in('status_id', $status_ids);
		$query = $this->db->get($tablename);
		$result = $query->result();
		return $result;
	}
	
	function insert($tablename, $data){
		$this->db->insert($tablename, $data);
		return $this->db->insert_id();
	}
	
	function insert_multi($tablename, $data){
		return $this->db->insert_batch($tablename, $data);
	}
	
	function update($tablename, $id, $data){ 
		$this->db->where('id', $id);
		return $this->db->update($tablename, $data);
	}
	
	function update_f($tablename, $filter, $data){ 
		$this->db->where($filter);
		return $this->db->update($tablename, $data);
	}
	
	function delete($tablename, $filter){
		$this->db->where($filter);
		return $this->db->delete($tablename);
	}
	
	function delete_multi($tablename, $field, $values){
		$this->db->where_in($field, $values);
		return $this->db->delete($tablename);
	}
	
	function structure($tablename){
		$res = new stdClass();
		$aux = $this->db->list_fields($tablename);
		foreach($aux as $field) $res->$field = null;
		return $res;
	}
}
?>
