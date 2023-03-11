<?php

class Appointment_model extends CI_Model{

	function __construct(){
		parent::__construct();
		$this->tablename = 'appointment';
	}
  
    function id($id){
		$this->db->where('id', $id);
		$query = $this->db->get($this->tablename);
		$result = $query->result();
		if ($result) return $result[0]; else return null;
	}
	
	function filter($filter, $limit = "", $offset = "", $order_by = "schedule_from", $order = "desc"){
		if ($filter) $this->db->where($filter);
		$this->db->order_by($order_by, $order);
		$query = $this->db->get($this->tablename, $limit, $offset);
		$result = $query->result();
		return $result;
	}
	
	function doctor($doctor_id, $date, $status_ids = null){
		$this->db->where('doctor_id', $doctor_id);
		$this->db->where('schedule_from >=', $date." 00:00:00");
		$this->db->where('schedule_from <=', $date." 23:59:59");
		$this->db->where_in('status_id', $status_ids);
		$this->db->order_by("schedule_from", "asc");
		$query = $this->db->get($this->tablename);
		$result = $query->result();
		return $result;
	}
	
	function check_available($app, $status_ids = null){
		$this->db->where('doctor_id', $app["doctor_id"]);
		$this->db->group_start();
		$this->db->group_start();
		$this->db->where('schedule_from <=', $app["schedule_from"]);
		$this->db->where('schedule_to >=', $app["schedule_from"]);
		$this->db->group_end();
		$this->db->or_group_start();
		$this->db->where('schedule_from <=', $app["schedule_to"]);
		$this->db->where('schedule_to >=', $app["schedule_to"]);
		$this->db->group_end();
		$this->db->or_group_start();
		$this->db->where('schedule_from >=', $app["schedule_from"]);
		$this->db->where('schedule_to <=', $app["schedule_to"]);
		$this->db->group_end();
		$this->db->group_end();
		if ($status_ids) $this->db->where_in('status_id', $status_ids);
		$this->db->order_by("schedule_from", "asc");
		$query = $this->db->get($this->tablename);
		$result = $query->result();
		return $result;
	}
	
	function all($order_by = "registed_at", $order = "desc"){
		$this->db->order_by($order_by, $order);
		$query = $this->db->get($this->tablename);
		$result = $query->result();
		return $result;
	}
	
	function counter($filter, $group_by = null){
		if ($filter) $this->db->where($filter);
		if ($group_by) $this->db->group_by($group_by);
		$query = $this->db->get($this->tablename);
		return $query->num_rows();
	}
	
	function insert($data){ 
		$this->db->insert($this->tablename, $data);
		return $this->db->insert_id();		
	}
	
	function insert_multi($data){
		return $this->db->insert_batch($this->tablename, $data);
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
