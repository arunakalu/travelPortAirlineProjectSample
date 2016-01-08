<?php
class ibeGetTablesDataModel extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    public function getDataFromDatabase($tablename)
    {
         
        $sql = "SELECT * FROM $tablename";
        $stmt = $this->db->conn_id->prepare($sql);
        $stmt->execute();
        $data=$stmt->fetchAll(PDO::FETCH_ASSOC);
       //var_dump($data);
        return $data;
    }
    
    public function getTablesName()
    {
        $sql = "show tables from fmfdatabase like '%ufls%'";
        $stmt = $this->db->conn_id->prepare($sql);
        $stmt->execute();
        $data=$stmt->fetchAll(PDO::FETCH_ASSOC);
       //var_dump($data);
        return $data;
    }
    
    
    
}