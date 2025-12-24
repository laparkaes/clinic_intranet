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
	
	function unique($tablename, $field, $value){
		$this->db->where($field, $value);
		$query = $this->db->get($tablename, 1, 0);
		$result = $query->result();
		if ($result) return $result[0]; else return null;
	}
	
	function filter($tablename, $w = null, $l = null, $w_in = null, $order_by = "", $order = "", $limit = "", $offset = ""){
		if ($w){ $this->db->group_start(); $this->db->where($w); $this->db->group_end(); }
		
		if ($l){
			$this->db->group_start();
			
			foreach($l as $item){
				foreach($item["values"] as $val){
					$this->db->or_group_start();
					if ($val) $this->db->like($item["field"], trim($val));
					$this->db->group_end();
				}
			}
			
			$this->db->group_end();
		}
		
		if ($w_in){
			$this->db->group_start();
			foreach($w_in as $item) $this->db->where_in($item["field"], $item["values"]);
			$this->db->group_end();
		}
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
	
	function only($tablename, $field, $w = null, $l = null, $w_in = null, $limit = "", $offset = ""){
		$this->db->select($field);
		if ($w){ $this->db->group_start(); $this->db->where($w); $this->db->group_end(); }
		if ($l){ $this->db->group_start(); $this->db->or_like($l); $this->db->group_end(); }
		if ($w_in){
			$this->db->group_start();
			foreach($w_in as $item) $this->db->where_in($item["field"], $item["values"]);
			$this->db->group_end();
		}
		$this->db->group_by($field);
		$query = $this->db->get($tablename);
		$result = $query->result();
		return $result;
	}
	
	function counter($tablename, $w = null, $l = null, $w_in = null, $group_by = null){
		if ($w){ $this->db->group_start(); $this->db->where($w); $this->db->group_end(); }
		if ($l){
			$this->db->group_start();
			
			foreach($l as $item){
				foreach($item["values"] as $val){
					if ($val) $this->db->or_like($item["field"], trim($val));
				}
			}
			
			$this->db->group_end();
		}
		if ($w_in){
			$this->db->group_start();
			foreach($w_in as $item) $this->db->where_in($item["field"], $item["values"]);
			$this->db->group_end();
		}
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
	
	function basic_join($maintable, $joins = null, $filter = null){
		$this->db->select("*");
		if ($filter) $this->db->where($filter);
		$this->db->from($maintable);
		if ($joins) foreach($joins as $join){
			$this->db->join($join["table"], $join["condition"]);
			$this->db->select($join["select"]);
		}
		$query = $this->db->get();
		$result = $query->result();
		return $result;
	}
	
	function get_patient_qty($doctor_id, $status_id){
		$this->db->select('COUNT(DISTINCT appointment.patient_id) as patient_qty');
		$this->db->from('appointment');
		$this->db->join('surgery', 'appointment.patient_id = surgery.patient_id', 'left');
		$this->db->where('appointment.doctor_id', $doctor_id);
		$this->db->where('appointment.status_id', $status_id);
		$this->db->or_where('surgery.doctor_id', $doctor_id);
		$this->db->where('surgery.status_id', $status_id);

		$query = $this->db->get();
		$result = $query->row();
		return $result->patient_qty;
	}
	
	function status($code){
		$this->db->where("code", $code);
		$query = $this->db->get("status");
		$result = $query->result();
		if ($result) return $result[0]; else return null;
	}
	
	function insert($tablename, $data){
		$this->db->insert($tablename, $data);
		return $this->db->insert_id();
	}
	
	function insert_multi($tablename, $data){
		return $this->db->insert_batch($tablename, $data);
	}
	
	function update($tablename, $id, $data){
		if ($id) $this->db->where('id', $id);
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
