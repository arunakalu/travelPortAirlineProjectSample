<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Sql_to_xmlController extends CI_Controller {

    public function index() {
        //echo 'dhfef';
        //load the flight_response_model model
        $this->load->model('getSqldataModel');
        //call the add_flight_response_to_database method located in the flight_response_model
        $res = $this->getSqldataModel->getDataFromDatabase();
        // var_dump($res);
        ///////////////////////////////////////////////
        $this->universalSqlXmalGenrarate($res);
        ///////////////////////////////////////////////
    }

    public function createXmlUsingData($res) {
        $response = "";
        var_dump($res);
        for ($i = 0; $i < sizeof($res); $i++) {
            $response.="
                <row>
                <id>" . $res[$i]["id"] . "</id>
                <name>" . $res[$i]["company_name"] . "</name>
                <address>" . $res[$i]["company_address"] . "</address>
                </row>
                ";
        }


        $xmlsample = <<<EOM
            <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/">
<soapenv:Header/>
<soapenv:Body>
<company>
 $response   
   
</company>  
</soapenv:Body>
</soapenv:Envelope>     
EOM;

        echo htmlentities($xmlsample);
    }

    public function universalSqlXmalGenrarate() {

        $this->load->model('getSqldataModel');
        //call the add_flight_response_to_database method located in the flight_response_model
        $tablesName = $this->getSqldataModel->getTablesName();
        //var_dump($tablesName);
       // die();
        for ($e = 0; $e < sizeof($tablesName); $e++) {

            $tablename = $tablesName[$e]["Tables_in_fmfdatabase (%point%)"];
            $res = NULL;
            $res = $this->getSqldataModel->getDataFromDatabase($tablename);
            $result1 = NULL;
            $result2 = NULL;
            for ($i = 0; $i < sizeof($res); $i++) {
                $ress = $res[$i];
                $res5 =NULL;
                foreach ($ress as $key => $value) {
                    //   echo $key."=>"  .$value;
                    $res5.="<" . $key . ">" . $value . "</" . $key . ">";
                }
                $result1.="<row>" . $res5 . "</row>";
            }

            $result2 = "<" . $tablename . ">" . $result1 . "</" . $tablename . ">";
            echo htmlentities($result2);
            echo "<br><br>";
        }
        //echo htmlentities($result1);
    }
    
    
    public function sitemapXml()
    {
       // $date = date('Format String', time());
        //echo $date;
        $now = new DateTime();

$nowdate=$now->format('Y-m-d H:i:s'); 


//        die();
       // echo 'fjgn';
        $middle_element="";
        $final_xml='';
         $this->load->model('getSqldataModel');
        //call the add_flight_response_to_database method located in the flight_response_model
         $tablename="fmf_link_areas";
        $tabledata = $this->getSqldataModel->getDataFromDatabase_xml($tablename);
       // $todayDate=da
       // var_dump($tabledata);
        
        foreach ($tabledata as $key => $value) {
            $middle_element.="<url><loc>".$value["url"]."</loc><lastmod>".$nowdate."</lastmod><changefreq>daily</changefreq><priority>1.0</priority></url>";
        }
        
         $tablename="fmf_site_map_links";
        $tabledata2 = $this->getSqldataModel->getDataFromDatabase_xml($tablename);
         foreach ($tabledata2 as $key => $value1) {
            $middle_element.="<url><loc>".$value1["url"]."</loc><lastmod>".$nowdate."</lastmod><changefreq>daily</changefreq><priority>0.8</priority></url>";
        }
        $final_xml.='<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation=" http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">'.$middle_element.'</urlset>';
        echo htmlentities($final_xml);
        
        
    }
    
    
    public function callbreadScrum()
    {
        $this->load->view('breadsrumView');
    }

}
