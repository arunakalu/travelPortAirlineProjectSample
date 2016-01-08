<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class siteMapController extends CI_Controller {

    public function index() {
       // echo 'ewtt';
         $this->load->model('getSiteMap');
        $fmf_link_areasData=$this->getSiteMap->getsiteMapDataFrom_fmf_link_areas();
        $featureArray=array();
       // var_dump($fmf_link_areasData);
        $a="ab";
        foreach ($fmf_link_areasData as $key => $value) {
       
            $featureArray[$key]['main']=$value;
            $subFeatures= $this->getSiteMap->getsiteMapDataFrom_fmf_site_map_links($value['id']);
            $featureArray[$key]['sub']=$subFeatures;
          
        }
    
        
    
         $arr1=array();
         //$arr1[0]="ruwan";
             //var_dump($featureArray);
            $arr1["siteMapDetails"]= $featureArray;
          //  echo json_encode($arr1);
             $this->load->view('siteMapView',$arr1);
        
    }
    
    
    
    
}