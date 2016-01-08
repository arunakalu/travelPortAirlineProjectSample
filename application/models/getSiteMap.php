<?php

//if (!defined('BASEPATH'))
//    exit('No direct script access allowed');
class getSiteMap extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    public function getsiteMapDataFrom_fmf_link_areas()
    {
         
        $sql = "SELECT * FROM fmf_link_areas";
        $stmt = $this->db->conn_id->prepare($sql);
        $stmt->execute();
        $data=$stmt->fetchAll(PDO::FETCH_ASSOC);
      // var_dump($data);
        return $data;
    }
    
   public function getsiteMapDataFrom_fmf_site_map_links($link_area_id)
    {
         
        $sql = "SELECT * FROM fmf_site_map_links WHERE link_area_id=$link_area_id";
        $stmt = $this->db->conn_id->prepare($sql);
        $stmt->execute();
        $data=$stmt->fetchAll(PDO::FETCH_ASSOC);
      // var_dump($data);
        return $data;
    }
    
    
    
}