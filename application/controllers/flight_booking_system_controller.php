<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Flight_booking_system_controller extends CI_Controller {

    public $totalPriceArray = array();
    public $arr = "fd";

    public function index() {
        //echo 'hai';
        $this->get_reqest_details();
    }

    public function get_reqest_details() {
        //create variables for catch parameters
        $origin = "CMB";
        $destination = "SIN";
        $origin_date = "2016-01-10"; //for one way trip
        //create request
        $request1 = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/">
    <soapenv:Header/>
    <soapenv:Body>
        <LowFareSearchReq xmlns="http://www.travelport.com/schema/air_v33_0" TraceId="b1499870-36c9-4839-9bad-22b8b072f228" TargetBranch="P7026977" ReturnUpsellFare="true">
            <BillingPointOfSaleInfo xmlns="http://www.travelport.com/schema/common_v33_0" OriginApplication="uAPI" />
            <SearchAirLeg>
                <SearchOrigin>
                    <CityOrAirport xmlns="http://www.travelport.com/schema/common_v33_0" Code="' . $origin . '" PreferCity="true" />
                </SearchOrigin>
                <SearchDestination>
                    <CityOrAirport xmlns="http://www.travelport.com/schema/common_v33_0" Code="' . $destination . '" PreferCity="true" />
                </SearchDestination>
                <SearchDepTime PreferredTime="' . $origin_date . '" />
            </SearchAirLeg>
            <AirSearchModifiers>
                <PreferredProviders>
                    <Provider xmlns="http://www.travelport.com/schema/common_v33_0" Code="1P" />
                </PreferredProviders>
            </AirSearchModifiers>
            <SearchPassenger xmlns="http://www.travelport.com/schema/common_v33_0" Code="ADT" Age="40" DOB="1976-01-02" />
        </LowFareSearchReq>
    </soapenv:Body>
</soapenv:Envelope> ';


        //  echo $request1;
        // echo htmlentities($request1);
        // die();
        //create curl request
        //credintial
        $TARGETBRANCH = 'P7026977'; //'Enter the Target Branch that you received in your Welcome letter';
        $CREDENTIALS = 'Universal API/uAPI4025719287-ca2a7cc1:Si8=?Wk3Lf'; //Universal API/API1234567:Password provieded in the welcome leter';
        $Provider = '1P'; // Any provider you want to use like 1G/1P/1V/ACH

        $auth = base64_encode("$CREDENTIALS");
        $soap_do = curl_init("https://apac.universal-api.pp.travelport.com/B2BGateway/connect/uAPI/AirService");
        $header = array(
            "Content-Type: text/xml;charset=UTF-8",
            "Accept: gzip,deflate",
            "Cache-Control: no-cache",
            "Pragma: no-cache",
            "SOAPAction: \"\"",
            "Authorization: Basic $auth",
            "Content-length: " . strlen($request1),
        );
        //curl_setopt($soap_do, CURLOPT_CONNECTTIMEOUT, 30); 
        //curl_setopt($soap_do, CURLOPT_TIMEOUT, 30); 
        curl_setopt($soap_do, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($soap_do, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($soap_do, CURLOPT_POST, true);
        curl_setopt($soap_do, CURLOPT_POSTFIELDS, $request1);
        curl_setopt($soap_do, CURLOPT_HTTPHEADER, $header);
        curl_setopt($soap_do, CURLOPT_RETURNTRANSFER, true); // this will prevent the curl_exec to return result and will let us to capture output
        $return = curl_exec($soap_do);

        echo htmlentities($return);
    }

    public function fetch_first_response() {
        $totalPriceArray = array();
        $response1 = '<SOAP:Envelope xmlns:SOAP="http://schemas.xmlsoap.org/soap/envelope/">
  <SOAP:Body>
    <air:LowFareSearchRsp xmlns:air="http://www.travelport.com/schema/air_v33_0" TraceId="b1499870-36c9-4839-9bad-22b8b072f228" TransactionId="00B3629E0A0764784182CE7D423C51AE" ResponseTime="6942" DistanceUnits="MI" CurrencyType="USD">
    <air:FlightDetailsList>
        <air:FlightDetails Key="0dAAjYNZQg2dIOUhIrVVog==" Origin="CMB" Destination="MAA" DepartureTime="2016-01-10T17:05:00.000+05:30" ArrivalTime="2016-01-10T18:45:00.000+05:30" FlightTime="100" TravelTime="375" Equipment="321" DestinationTerminal="4" />
        <air:FlightDetails Key="2o20qZ3LSb6BM0kPIEZmQg==" Origin="MAA" Destination="BOM" DepartureTime="2016-01-10T21:15:00.000+05:30" ArrivalTime="2016-01-10T23:20:00.000+05:30" FlightTime="125" TravelTime="375" Equipment="319" OriginTerminal="01" DestinationTerminal="2" />
        <air:FlightDetails Key="blml8olgS/6Jk5WuPekL4w==" Origin="MAA" Destination="BOM" DepartureTime="2016-01-11T06:20:00.000+05:30" ArrivalTime="2016-01-11T08:10:00.000+05:30" FlightTime="110" TravelTime="905" Equipment="319" OriginTerminal="1" DestinationTerminal="2" />
        <air:FlightDetails Key="n9pCJm+sQd+WNnswEPedew==" Origin="CMB" Destination="BOM" DepartureTime="2016-01-10T06:10:00.000+05:30" ArrivalTime="2016-01-10T08:45:00.000+05:30" FlightTime="155" TravelTime="155" Equipment="737" DestinationTerminal="2" />
        <air:FlightDetails Key="TyWAF1xFRt2mDoMbDUtbOg==" Origin="CMB" Destination="BOM" DepartureTime="2016-01-10T23:45:00.000+05:30" ArrivalTime="2016-01-11T02:10:00.000+05:30" FlightTime="145" TravelTime="145" Equipment="320" DestinationTerminal="2" />
        <air:FlightDetails Key="8LBArPYATnSCcvTnVTEKLA==" Origin="CMB" Destination="DEL" DepartureTime="2016-01-10T08:20:00.000+05:30" ArrivalTime="2016-01-10T12:10:00.000+05:30" FlightTime="230" TravelTime="635" Equipment="321" DestinationTerminal="3" />
        <air:FlightDetails Key="3g/jPbR5T8i8otytgo5LoA==" Origin="DEL" Destination="BOM" DepartureTime="2016-01-10T16:45:00.000+05:30" ArrivalTime="2016-01-10T18:55:00.000+05:30" FlightTime="130" TravelTime="635" Equipment="77W" OriginTerminal="3" DestinationTerminal="2" />
        <air:FlightDetails Key="tqqSxKAcTD+O749h8YkNUg==" Origin="DEL" Destination="BOM" DepartureTime="2016-01-10T17:00:00.000+05:30" ArrivalTime="2016-01-10T19:20:00.000+05:30" FlightTime="140" TravelTime="660" Equipment="321" OriginTerminal="3" DestinationTerminal="2" />
        <air:FlightDetails Key="ykUhtTmgRgqpgJC5PIDMAA==" Origin="MAA" Destination="DEL" DepartureTime="2016-01-10T21:05:00.000+05:30" ArrivalTime="2016-01-10T23:45:00.000+05:30" FlightTime="160" TravelTime="790" Equipment="321" OriginTerminal="4" DestinationTerminal="3" />
        <air:FlightDetails Key="DqhbNFzVRWa1pW29NMmWQg==" Origin="DEL" Destination="BOM" DepartureTime="2016-01-11T04:00:00.000+05:30" ArrivalTime="2016-01-11T06:15:00.000+05:30" FlightTime="135" TravelTime="790" Equipment="788" OriginTerminal="3" DestinationTerminal="2" />
        <air:FlightDetails Key="qO4+gr4zRWyuW8appu44Aw==" Origin="DEL" Destination="BOM" DepartureTime="2016-01-11T07:00:00.000+05:30" ArrivalTime="2016-01-11T09:10:00.000+05:30" FlightTime="130" TravelTime="965" Equipment="321" OriginTerminal="3" DestinationTerminal="2" />
        <air:FlightDetails Key="2C6WbaE2QVSiMjVph2GKRg==" Origin="DEL" Destination="BLR" DepartureTime="2016-01-10T13:30:00.000+05:30" ArrivalTime="2016-01-10T16:15:00.000+05:30" FlightTime="165" TravelTime="665" Equipment="321" OriginTerminal="3" />
        <air:FlightDetails Key="ElVsTPUGQUagurbQ7Fjavg==" Origin="BLR" Destination="BOM" DepartureTime="2016-01-10T17:20:00.000+05:30" ArrivalTime="2016-01-10T19:25:00.000+05:30" FlightTime="125" TravelTime="665" Equipment="319" DestinationTerminal="2" />
        <air:FlightDetails Key="NYROJjt1SgyIxqzXhlyBag==" Origin="MAA" Destination="BLR" DepartureTime="2016-01-11T14:20:00.000+05:30" ArrivalTime="2016-01-11T15:20:00.000+05:30" FlightTime="60" TravelTime="1580" Equipment="319" OriginTerminal="1" />
        <air:FlightDetails Key="aanv6fNxQDWh+bvnI7Cxuw==" Origin="BLR" Destination="BOM" DepartureTime="2016-01-11T17:20:00.000+05:30" ArrivalTime="2016-01-11T19:25:00.000+05:30" FlightTime="125" TravelTime="1580" Equipment="319" DestinationTerminal="2" />
        <air:FlightDetails Key="ozOM2S5zRE6CfFQBou96TQ==" Origin="CMB" Destination="DXB" DepartureTime="2016-01-10T16:20:00.000+05:30" ArrivalTime="2016-01-10T19:55:00.000+04:00" FlightTime="305" TravelTime="710" Equipment="737" DestinationTerminal="2" />
        <air:FlightDetails Key="+e9faYpPSCaUecnJGvd4Aw==" Origin="DXB" Destination="BOM" DepartureTime="2016-01-10T23:35:00.000+04:00" ArrivalTime="2016-01-11T04:10:00.000+05:30" FlightTime="185" TravelTime="710" Equipment="737" OriginTerminal="2" DestinationTerminal="2" />
        <air:FlightDetails Key="Y42HffJkTfqTucF1l2KiCg==" Origin="CMB" Destination="BLR" DepartureTime="2016-01-10T01:15:00.000+05:30" ArrivalTime="2016-01-10T02:35:00.000+05:30" FlightTime="80" TravelTime="370" Equipment="319" />
        <air:FlightDetails Key="Rf43gpudT1Ocmdo92D056A==" Origin="BLR" Destination="BOM" DepartureTime="2016-01-10T05:50:00.000+05:30" ArrivalTime="2016-01-10T07:25:00.000+05:30" FlightTime="95" TravelTime="370" Equipment="738" DestinationTerminal="1B" />
        <air:FlightDetails Key="v1JEqA57SYOYJZZG3238aQ==" Origin="CMB" Destination="MAA" DepartureTime="2016-01-10T07:20:00.000+05:30" ArrivalTime="2016-01-10T08:40:00.000+05:30" FlightTime="80" TravelTime="355" Equipment="332" DestinationTerminal="3" />
        <air:FlightDetails Key="B2PknRGtRk+4hO+6hFp6Fw==" Origin="MAA" Destination="BOM" DepartureTime="2016-01-10T11:25:00.000+05:30" ArrivalTime="2016-01-10T13:15:00.000+05:30" FlightTime="110" TravelTime="355" Equipment="738" OriginTerminal="1" DestinationTerminal="1B" />
        <air:FlightDetails Key="GWOHk12yRHiI7edsq8D7Pw==" Origin="CMB" Destination="MAA" DepartureTime="2016-01-10T18:35:00.000+05:30" ArrivalTime="2016-01-10T19:55:00.000+05:30" FlightTime="80" TravelTime="595" Equipment="320" DestinationTerminal="3" />
        <air:FlightDetails Key="oIYp6KBoQVW7X8UAgp7D9A==" Origin="MAA" Destination="BOM" DepartureTime="2016-01-11T02:35:00.000+05:30" ArrivalTime="2016-01-11T04:30:00.000+05:30" FlightTime="115" TravelTime="595" Equipment="737" OriginTerminal="1" DestinationTerminal="1B" />
        <air:FlightDetails Key="W7AiU30YS66Cu3g5GMvl4Q==" Origin="CMB" Destination="BOM" DepartureTime="2016-01-10T21:45:00.000+05:30" ArrivalTime="2016-01-11T00:25:00.000+05:30" FlightTime="160" TravelTime="160" Equipment="737" DestinationTerminal="2" />
        <air:FlightDetails Key="iAj7T3d3T/mC+l+cGUvSkQ==" Origin="CMB" Destination="MAA" DepartureTime="2016-01-10T13:45:00.000+05:30" ArrivalTime="2016-01-10T15:05:00.000+05:30" FlightTime="80" TravelTime="575" Equipment="320" DestinationTerminal="3" />
        <air:FlightDetails Key="Vls6qSi4TByn5gWxWcdEjw==" Origin="CMB" Destination="COK" DepartureTime="2016-01-10T14:05:00.000+05:30" ArrivalTime="2016-01-10T15:25:00.000+05:30" FlightTime="80" TravelTime="580" Equipment="320" />
        <air:FlightDetails Key="MyzuH80cR6yXoA7DTMrXfg==" Origin="COK" Destination="BOM" DepartureTime="2016-01-10T22:00:00.000+05:30" ArrivalTime="2016-01-10T23:45:00.000+05:30" FlightTime="105" TravelTime="580" Equipment="320" DestinationTerminal="2" />
        <air:FlightDetails Key="O1WZG9rfQh6Z5GR29cjDEA==" Origin="BLR" Destination="BOM" DepartureTime="2016-01-10T06:45:00.000+05:30" ArrivalTime="2016-01-10T08:20:00.000+05:30" FlightTime="95" TravelTime="425" Equipment="319" DestinationTerminal="2" />
        <air:FlightDetails Key="YnaXTsJyQiyh0pUQJgGF9w==" Origin="CMB" Destination="MCT" DepartureTime="2016-01-10T19:05:00.000+05:30" ArrivalTime="2016-01-10T21:55:00.000+04:00" FlightTime="260" TravelTime="580" Equipment="320" />
        <air:FlightDetails Key="JAQeVRncQKyZAIGuxwJrsg==" Origin="MCT" Destination="BOM" DepartureTime="2016-01-11T00:20:00.000+04:00" ArrivalTime="2016-01-11T04:45:00.000+05:30" FlightTime="175" TravelTime="580" Equipment="321" DestinationTerminal="2" />
        <air:FlightDetails Key="pq5poDXrTmKm92wa33QLQQ==" Origin="MCT" Destination="BOM" DepartureTime="2016-01-11T00:25:00.000+04:00" ArrivalTime="2016-01-11T04:25:00.000+05:30" FlightTime="150" TravelTime="560" Equipment="738" DestinationTerminal="2" />
        <air:FlightDetails Key="YOfdAUXKSAuMnUcIdFRZUQ==" Origin="CMB" Destination="KUL" DepartureTime="2016-01-10T12:55:00.000+05:30" ArrivalTime="2016-01-10T19:05:00.000+08:00" FlightTime="220" TravelTime="600" Equipment="738" DestinationTerminal="M" />
        <air:FlightDetails Key="5OHeNUlBQmiqbT/Ya37VCg==" Origin="KUL" Destination="BOM" DepartureTime="2016-01-10T20:30:00.000+08:00" ArrivalTime="2016-01-10T22:55:00.000+05:30" FlightTime="295" TravelTime="600" Equipment="738" OriginTerminal="M" DestinationTerminal="2" />
        <air:FlightDetails Key="POPCdSLSRJKEpeRLoCe0bw==" Origin="CMB" Destination="BKK" DepartureTime="2016-01-10T01:30:00.000+05:30" ArrivalTime="2016-01-10T06:25:00.000+07:00" FlightTime="205" TravelTime="1230" Equipment="777" />
        <air:FlightDetails Key="qchCpgzITOStAd5PGztx3g==" Origin="BKK" Destination="BOM" DepartureTime="2016-01-10T18:55:00.000+07:00" ArrivalTime="2016-01-10T22:00:00.000+05:30" FlightTime="275" TravelTime="1230" Equipment="744" DestinationTerminal="2" />
        <air:FlightDetails Key="k2d+YuMbRWaP8Syg9pZQyg==" Origin="CMB" Destination="AUH" DepartureTime="2016-01-10T21:00:00.000+05:30" ArrivalTime="2016-01-11T00:25:00.000+04:00" FlightTime="295" TravelTime="675" Equipment="320" DestinationTerminal="1" />
        <air:FlightDetails Key="FTm/u+2xQIyQ9hQHUwnybw==" Origin="AUH" Destination="BOM" DepartureTime="2016-01-11T03:10:00.000+04:00" ArrivalTime="2016-01-11T08:15:00.000+05:30" FlightTime="215" TravelTime="675" Equipment="320" OriginTerminal="1" DestinationTerminal="2" />
        <air:FlightDetails Key="RRuv/BCKT+ySfdmpHZ0ebw==" Origin="CMB" Destination="SIN" DepartureTime="2016-01-10T01:10:00.000+05:30" ArrivalTime="2016-01-10T07:40:00.000+08:00" FlightTime="240" TravelTime="1235" Equipment="333" DestinationTerminal="0" />
        <air:FlightDetails Key="LU2oY/tvSlqqK8tO4MAywA==" Origin="SIN" Destination="BOM" DepartureTime="2016-01-10T18:55:00.000+08:00" ArrivalTime="2016-01-10T21:45:00.000+05:30" FlightTime="320" TravelTime="1235" Equipment="388" OriginTerminal="2" DestinationTerminal="2" />
        <air:FlightDetails Key="ZTqx8q2dQwGfadAzHtF9Aw==" Origin="CMB" Destination="SIN" DepartureTime="2016-01-10T01:00:00.000+05:30" ArrivalTime="2016-01-10T07:30:00.000+08:00" FlightTime="240" TravelTime="750" Equipment="320" DestinationTerminal="3" />
        <air:FlightDetails Key="KIiBDbgcQYqVlpSz9w5i1g==" Origin="SIN" Destination="BOM" DepartureTime="2016-01-10T10:30:00.000+08:00" ArrivalTime="2016-01-10T13:30:00.000+05:30" FlightTime="330" TravelTime="750" Equipment="737" OriginTerminal="3" DestinationTerminal="2" />
    </air:FlightDetailsList>
    <air:AirSegmentList>
        <air:AirSegment Key="qEqLJK1LRAGnhEwqpT9j5Q==" Group="0" Carrier="AI" FlightNumber="274" Origin="CMB" Destination="MAA" DepartureTime="2016-01-10T17:05:00.000+05:30" ArrivalTime="2016-01-10T18:45:00.000+05:30" FlightTime="100" Distance="402" ETicketability="Yes" Equipment="321" ChangeOfPlane="false" OptionalServicesIndicator="false">
            <air:AirAvailInfo ProviderCode="1P">
                <air:BookingCodeInfo BookingCounts="C4|D4|J4|Z4|Y9|B4|M4|H4|K4|Q4|V4|W4|G4|L4|U4|T4|S4|E4|N4" />
            </air:AirAvailInfo>
            <air:FlightDetailsRef Key="0dAAjYNZQg2dIOUhIrVVog==" />
        </air:AirSegment>
        <air:AirSegment Key="aZTP9qwYR9SOncCsMhoaSQ==" Group="0" Carrier="AI" FlightNumber="93" Origin="MAA" Destination="BOM" DepartureTime="2016-01-10T21:15:00.000+05:30" ArrivalTime="2016-01-10T23:20:00.000+05:30" FlightTime="125" Distance="644" ETicketability="Yes" Equipment="319" ChangeOfPlane="false" OptionalServicesIndicator="false">
            <air:AirAvailInfo ProviderCode="1P">
                <air:BookingCodeInfo BookingCounts="C4|D4|J4|Z4|Y9|B4|M4|H4|K4|Q4|V4|W4|G4|L4|U4|T4|S4|E4|N4" />
            </air:AirAvailInfo>
            <air:FlightDetailsRef Key="2o20qZ3LSb6BM0kPIEZmQg==" />
        </air:AirSegment>
        <air:AirSegment Key="K3tPr6oWRcCqgIipKphuxg==" Group="0" Carrier="AI" FlightNumber="569" Origin="MAA" Destination="BOM" DepartureTime="2016-01-11T06:20:00.000+05:30" ArrivalTime="2016-01-11T08:10:00.000+05:30" FlightTime="110" Distance="644" ETicketability="Yes" Equipment="319" ChangeOfPlane="false" OptionalServicesIndicator="false">
            <air:AirAvailInfo ProviderCode="1P">
                <air:BookingCodeInfo BookingCounts="C4|D4|J4|Z4|Y9|B4|M4|H4|K4|Q4|V4|W4|G4|L4|U4|T4|S4|E4|N4" />
            </air:AirAvailInfo>
            <air:FlightDetailsRef Key="blml8olgS/6Jk5WuPekL4w==" />
        </air:AirSegment>
        <air:AirSegment Key="cK3iLlrFTbu5CkLz3ZvIXg==" Group="0" Carrier="9W" FlightNumber="255" Origin="CMB" Destination="BOM" DepartureTime="2016-01-10T06:10:00.000+05:30" ArrivalTime="2016-01-10T08:45:00.000+05:30" FlightTime="155" Distance="948" ETicketability="Yes" Equipment="737" ChangeOfPlane="false" OptionalServicesIndicator="false">
            <air:AirAvailInfo ProviderCode="1P">
                <air:BookingCodeInfo BookingCounts="C7|J7|Z7|I7|P7|Y7|M7|T7|U7|N7|L7|Q7|S7|K7|H7|V7|O7|W3|B7" />
            </air:AirAvailInfo>
            <air:FlightDetailsRef Key="n9pCJm+sQd+WNnswEPedew==" />
        </air:AirSegment>
        <air:AirSegment Key="c/o5HezPSH2V5lhAYVyFjQ==" Group="0" Carrier="MJ" FlightNumber="2141" Origin="CMB" Destination="BOM" DepartureTime="2016-01-10T23:45:00.000+05:30" ArrivalTime="2016-01-11T02:10:00.000+05:30" FlightTime="145" Distance="948" ETicketability="Yes" Equipment="320" ChangeOfPlane="false" OptionalServicesIndicator="false">
            <air:CodeshareInfo OperatingCarrier="UL" OperatingFlightNumber="141">SRILANKAN AIRLINES</air:CodeshareInfo>
            <air:AirAvailInfo ProviderCode="1P">
                <air:BookingCodeInfo BookingCounts="J4|C4|D4|I4|Y4|B4|P4|H4|K4|W4|M4|E4|L4|R4|V4|S4|N4|Q4|O4" />
            </air:AirAvailInfo>
            <air:FlightDetailsRef Key="TyWAF1xFRt2mDoMbDUtbOg==" />
        </air:AirSegment>
        <air:AirSegment Key="ZUmSBYBuQfuE+Uh8OZOBOA==" Group="0" Carrier="UL" FlightNumber="141" Origin="CMB" Destination="BOM" DepartureTime="2016-01-10T23:45:00.000+05:30" ArrivalTime="2016-01-11T02:10:00.000+05:30" FlightTime="145" Distance="948" ETicketability="Yes" Equipment="320" ChangeOfPlane="false" OptionalServicesIndicator="false">
            <air:AirAvailInfo ProviderCode="1P">
                <air:BookingCodeInfo BookingCounts="J4|C4|D4|I3|Y7|B7|P7|H7|K7|W7|M7|E7|L7" />
            </air:AirAvailInfo>
            <air:FlightDetailsRef Key="TyWAF1xFRt2mDoMbDUtbOg==" />
        </air:AirSegment>
        <air:AirSegment Key="Wc/L/x4jSkyPLWyRz5SazA==" Group="0" Carrier="AI" FlightNumber="282" Origin="CMB" Destination="DEL" DepartureTime="2016-01-10T08:20:00.000+05:30" ArrivalTime="2016-01-10T12:10:00.000+05:30" FlightTime="230" Distance="1489" ETicketability="Yes" Equipment="321" ChangeOfPlane="false" OptionalServicesIndicator="false">
            <air:AirAvailInfo ProviderCode="1P">
                <air:BookingCodeInfo BookingCounts="C4|D4|J4|Z4|Y9|B4|M4|H4|K4|Q4|V4|W4|G4|L4|U4|T4|S4|E4|N4" />
            </air:AirAvailInfo>
            <air:FlightDetailsRef Key="8LBArPYATnSCcvTnVTEKLA==" />
        </air:AirSegment>
        <air:AirSegment Key="VkXIQBIsTLywtVjewk9X9w==" Group="0" Carrier="AI" FlightNumber="102" Origin="DEL" Destination="BOM" DepartureTime="2016-01-10T16:45:00.000+05:30" ArrivalTime="2016-01-10T18:55:00.000+05:30" FlightTime="130" Distance="708" ETicketability="Yes" Equipment="77W" ChangeOfPlane="false" OptionalServicesIndicator="false">
            <air:AirAvailInfo ProviderCode="1P">
                <air:BookingCodeInfo BookingCounts="F4|A4|C4|D4|J4|Z4|Y9|B4|M4|H4|K4|Q4|V4|W4|G4|L4|U4|T4|S4|E4|N4" />
            </air:AirAvailInfo>
            <air:FlightDetailsRef Key="3g/jPbR5T8i8otytgo5LoA==" />
        </air:AirSegment>
        <air:AirSegment Key="nBlF5r+OQ6iazAGnscYyYg==" Group="0" Carrier="AI" FlightNumber="659" Origin="DEL" Destination="BOM" DepartureTime="2016-01-10T17:00:00.000+05:30" ArrivalTime="2016-01-10T19:20:00.000+05:30" FlightTime="140" Distance="708" ETicketability="Yes" Equipment="321" ChangeOfPlane="false" OptionalServicesIndicator="false">
            <air:AirAvailInfo ProviderCode="1P">
                <air:BookingCodeInfo BookingCounts="C4|D4|J4|Z4|Y9|B4|M4|H4|K4|Q4|V4|W4|G4|L4|U4|T4|S4|E4|N4" />
            </air:AirAvailInfo>
            <air:FlightDetailsRef Key="tqqSxKAcTD+O749h8YkNUg==" />
        </air:AirSegment>
        <air:AirSegment Key="sbtLXlgsTDWsr1ltAbwdlQ==" Group="0" Carrier="AI" FlightNumber="43" Origin="MAA" Destination="DEL" DepartureTime="2016-01-10T21:05:00.000+05:30" ArrivalTime="2016-01-10T23:45:00.000+05:30" FlightTime="160" Distance="1095" ETicketability="Yes" Equipment="321" ChangeOfPlane="false" OptionalServicesIndicator="false">
            <air:AirAvailInfo ProviderCode="1P">
                <air:BookingCodeInfo BookingCounts="C4|D4|J4|Z4|Y9|B4|M4|H4|K4|Q4|V4|W4|G4|L4|U4|T4|S4|E4|N4" />
            </air:AirAvailInfo>
            <air:FlightDetailsRef Key="ykUhtTmgRgqpgJC5PIDMAA==" />
        </air:AirSegment>
        <air:AirSegment Key="8BN/J8H3SQa15oQD1YDUlA==" Group="0" Carrier="AI" FlightNumber="349" Origin="DEL" Destination="BOM" DepartureTime="2016-01-11T04:00:00.000+05:30" ArrivalTime="2016-01-11T06:15:00.000+05:30" FlightTime="135" Distance="708" ETicketability="Yes" Equipment="788" ChangeOfPlane="false" OptionalServicesIndicator="false">
            <air:AirAvailInfo ProviderCode="1P">
                <air:BookingCodeInfo BookingCounts="C4|D4|J4|Z4|Y9|B4|M4|H4|K4|Q4|V4|W4|G4|L4|U4|T4|S4|E4|N4" />
            </air:AirAvailInfo>
            <air:FlightDetailsRef Key="DqhbNFzVRWa1pW29NMmWQg==" />
        </air:AirSegment>
        <air:AirSegment Key="RLO2cT1KR0eO2ZIoWzXC7w==" Group="0" Carrier="AI" FlightNumber="657" Origin="DEL" Destination="BOM" DepartureTime="2016-01-11T07:00:00.000+05:30" ArrivalTime="2016-01-11T09:10:00.000+05:30" FlightTime="130" Distance="708" ETicketability="Yes" Equipment="321" ChangeOfPlane="false" OptionalServicesIndicator="false">
            <air:AirAvailInfo ProviderCode="1P">
                <air:BookingCodeInfo BookingCounts="C4|D4|J4|Z4|Y9|B4|M4|H4|K4|Q4|V4|W4|G4|L4|U4|T4|S4|E4|N4" />
            </air:AirAvailInfo>
            <air:FlightDetailsRef Key="qO4+gr4zRWyuW8appu44Aw==" />
        </air:AirSegment>
        <air:AirSegment Key="KVe23y4NQXaDS4C6Kdy6rA==" Group="0" Carrier="AI" FlightNumber="502" Origin="DEL" Destination="BLR" DepartureTime="2016-01-10T13:30:00.000+05:30" ArrivalTime="2016-01-10T16:15:00.000+05:30" FlightTime="165" Distance="1063" ETicketability="Yes" Equipment="321" ChangeOfPlane="false" OptionalServicesIndicator="false">
            <air:AirAvailInfo ProviderCode="1P">
                <air:BookingCodeInfo BookingCounts="C4|D4|J4|Z4|Y9|B4|M4|H4|K4|Q4|V4|W4|G4|L4|U4|T4|S4|E4|N4" />
            </air:AirAvailInfo>
            <air:FlightDetailsRef Key="2C6WbaE2QVSiMjVph2GKRg==" />
        </air:AirSegment>
        <air:AirSegment Key="Unm9TtHNTaa/LwKzJaWn7Q==" Group="0" Carrier="AI" FlightNumber="608" Origin="BLR" Destination="BOM" DepartureTime="2016-01-10T17:20:00.000+05:30" ArrivalTime="2016-01-10T19:25:00.000+05:30" FlightTime="125" Distance="519" ETicketability="Yes" Equipment="319" ChangeOfPlane="false" OptionalServicesIndicator="false">
            <air:AirAvailInfo ProviderCode="1P">
                <air:BookingCodeInfo BookingCounts="C4|D4|J4|Z4|Y9|B4|M4|H4|K4|Q4|V4|W4|G4|L4|U4|T4|S4|E4|N4" />
            </air:AirAvailInfo>
            <air:FlightDetailsRef Key="ElVsTPUGQUagurbQ7Fjavg==" />
        </air:AirSegment>
        <air:AirSegment Key="kj4kRMUESFeN9ECJnweSQg==" Group="0" Carrier="AI" FlightNumber="563" Origin="MAA" Destination="BLR" DepartureTime="2016-01-11T14:20:00.000+05:30" ArrivalTime="2016-01-11T15:20:00.000+05:30" FlightTime="60" Distance="168" ETicketability="Yes" Equipment="319" ChangeOfPlane="false" OptionalServicesIndicator="false">
            <air:AirAvailInfo ProviderCode="1P">
                <air:BookingCodeInfo BookingCounts="C4|D4|J4|Z4|Y9|B4|M4|H4|K4|Q4|V4|W4|G4|L4|U4|T4|S4|E4|N4" />
            </air:AirAvailInfo>
            <air:FlightDetailsRef Key="NYROJjt1SgyIxqzXhlyBag==" />
        </air:AirSegment>
        <air:AirSegment Key="1djiAXJLR4609QNW2KSOfQ==" Group="0" Carrier="AI" FlightNumber="608" Origin="BLR" Destination="BOM" DepartureTime="2016-01-11T17:20:00.000+05:30" ArrivalTime="2016-01-11T19:25:00.000+05:30" FlightTime="125" Distance="519" ETicketability="Yes" Equipment="319" ChangeOfPlane="false" OptionalServicesIndicator="false">
            <air:AirAvailInfo ProviderCode="1P">
                <air:BookingCodeInfo BookingCounts="C4|D4|J4|Z4|Y9|B4|M4|H4|K4|Q4|V4|W4|G4|L4|U4|T4|S4|E4|N4" />
            </air:AirAvailInfo>
            <air:FlightDetailsRef Key="aanv6fNxQDWh+bvnI7Cxuw==" />
        </air:AirSegment>
        <air:AirSegment Key="r79SJ6lwRVWGzwX/bWBfAg==" Group="0" Carrier="FZ" FlightNumber="554" Origin="CMB" Destination="DXB" DepartureTime="2016-01-10T16:20:00.000+05:30" ArrivalTime="2016-01-10T19:55:00.000+04:00" FlightTime="305" Distance="2043" ETicketability="Yes" Equipment="737" ChangeOfPlane="false" OptionalServicesIndicator="false">
            <air:AirAvailInfo ProviderCode="1P">
                <air:BookingCodeInfo BookingCounts="Z4|J4|Y4|A4|I4|E4|W4|T4" />
            </air:AirAvailInfo>
            <air:FlightDetailsRef Key="ozOM2S5zRE6CfFQBou96TQ==" />
        </air:AirSegment>
        <air:AirSegment Key="ojS+Dtb3Rey7sV32K0cYBQ==" Group="0" Carrier="FZ" FlightNumber="445" Origin="DXB" Destination="BOM" DepartureTime="2016-01-10T23:35:00.000+04:00" ArrivalTime="2016-01-11T04:10:00.000+05:30" FlightTime="185" Distance="1201" ETicketability="Yes" Equipment="737" ChangeOfPlane="false" OptionalServicesIndicator="false">
            <air:AirAvailInfo ProviderCode="1P">
                <air:BookingCodeInfo BookingCounts="Z4|J4|Y4|A4|I4|E4|W4|T4|M4|L4" />
            </air:AirAvailInfo>
            <air:FlightDetailsRef Key="+e9faYpPSCaUecnJGvd4Aw==" />
        </air:AirSegment>
        <air:AirSegment Key="QZKyfzWgR4ScjarSP+Cx6w==" Group="0" Carrier="UL" FlightNumber="173" Origin="CMB" Destination="BLR" DepartureTime="2016-01-10T01:15:00.000+05:30" ArrivalTime="2016-01-10T02:35:00.000+05:30" FlightTime="80" Distance="442" ETicketability="Yes" Equipment="319" ChangeOfPlane="false" OptionalServicesIndicator="false">
            <air:CodeshareInfo>UL USING MIHIN ACFT</air:CodeshareInfo>
            <air:AirAvailInfo ProviderCode="1P">
                <air:BookingCodeInfo BookingCounts="J4|C4|D4|I4|Y7|B7|P4|H7|K7|W4|M7|E4|L7|R4|V7|S4|N7|Q4|O4" />
            </air:AirAvailInfo>
            <air:FlightDetailsRef Key="Y42HffJkTfqTucF1l2KiCg==" />
        </air:AirSegment>
        <air:AirSegment Key="iLH+vTAsReqPFgeXHm8/uw==" Group="0" Carrier="9W" FlightNumber="410" Origin="BLR" Destination="BOM" DepartureTime="2016-01-10T05:50:00.000+05:30" ArrivalTime="2016-01-10T07:25:00.000+05:30" FlightTime="95" Distance="519" ETicketability="Yes" Equipment="738" ChangeOfPlane="false" OptionalServicesIndicator="false">
            <air:AirAvailInfo ProviderCode="1P">
                <air:BookingCodeInfo BookingCounts="C7|J7|Z7|I7|P7|Y7|M7|T7|U7|N7|L7|Q7|S7|K7|H7|V7|O7|W7|B7" />
            </air:AirAvailInfo>
            <air:FlightDetailsRef Key="Rf43gpudT1Ocmdo92D056A==" />
        </air:AirSegment>
        <air:AirSegment Key="QXjtZ2YYRgWYtNxm49SKgg==" Group="0" Carrier="UL" FlightNumber="121" Origin="CMB" Destination="MAA" DepartureTime="2016-01-10T07:20:00.000+05:30" ArrivalTime="2016-01-10T08:40:00.000+05:30" FlightTime="80" Distance="402" ETicketability="Yes" Equipment="332" ChangeOfPlane="false" OptionalServicesIndicator="false">
            <air:AirAvailInfo ProviderCode="1P">
                <air:BookingCodeInfo BookingCounts="J4|C4|D4|I4|Y7|B7|P4|H7|K7|W4|M7|E4|L7|R4|V7|S4|N7|Q4|O4" />
            </air:AirAvailInfo>
            <air:FlightDetailsRef Key="v1JEqA57SYOYJZZG3238aQ==" />
        </air:AirSegment>
        <air:AirSegment Key="CAi3hq9gS4OhxRMfL9nqzg==" Group="0" Carrier="9W" FlightNumber="460" Origin="MAA" Destination="BOM" DepartureTime="2016-01-10T11:25:00.000+05:30" ArrivalTime="2016-01-10T13:15:00.000+05:30" FlightTime="110" Distance="644" ETicketability="Yes" Equipment="738" ChangeOfPlane="false" OptionalServicesIndicator="false">
            <air:AirAvailInfo ProviderCode="1P">
                <air:BookingCodeInfo BookingCounts="C7|J7|Z7|I7|P7|Y7|M7|T7|U7|N7|L7|Q7|S7|K7|B7" />
            </air:AirAvailInfo>
            <air:FlightDetailsRef Key="B2PknRGtRk+4hO+6hFp6Fw==" />
        </air:AirSegment>
        <air:AirSegment Key="eNGo4XRzQNOFhPH/PbLV4A==" Group="0" Carrier="UL" FlightNumber="123" Origin="CMB" Destination="MAA" DepartureTime="2016-01-10T18:35:00.000+05:30" ArrivalTime="2016-01-10T19:55:00.000+05:30" FlightTime="80" Distance="402" ETicketability="Yes" Equipment="320" ChangeOfPlane="false" OptionalServicesIndicator="false">
            <air:AirAvailInfo ProviderCode="1P">
                <air:BookingCodeInfo BookingCounts="J4|C4|D4|I4|Y7|B7|P4|H7|K7|W4|M7|E4|L7|R4|V7|S4|N7|Q4|O4" />
            </air:AirAvailInfo>
            <air:FlightDetailsRef Key="GWOHk12yRHiI7edsq8D7Pw==" />
        </air:AirSegment>
        <air:AirSegment Key="6qPlNBaIT/aTnnmAlz+saQ==" Group="0" Carrier="9W" FlightNumber="7012" Origin="MAA" Destination="BOM" DepartureTime="2016-01-11T02:35:00.000+05:30" ArrivalTime="2016-01-11T04:30:00.000+05:30" FlightTime="115" Distance="644" ETicketability="Yes" Equipment="737" ChangeOfPlane="false" OptionalServicesIndicator="false">
            <air:CodeshareInfo>JETKONNECT</air:CodeshareInfo>
            <air:AirAvailInfo ProviderCode="1P">
                <air:BookingCodeInfo BookingCounts="C7|J7|Z7|I7|P7|Y7|M7|T7|U7|N7|L7|Q7|S7|K7|H7|V7" />
            </air:AirAvailInfo>
            <air:FlightDetailsRef Key="oIYp6KBoQVW7X8UAgp7D9A==" />
        </air:AirSegment>
        <air:AirSegment Key="A4YNT3OLSEakv9MoxpZxag==" Group="0" Carrier="9W" FlightNumber="251" Origin="CMB" Destination="BOM" DepartureTime="2016-01-10T21:45:00.000+05:30" ArrivalTime="2016-01-11T00:25:00.000+05:30" FlightTime="160" Distance="948" ETicketability="Yes" Equipment="737" ChangeOfPlane="false" OptionalServicesIndicator="false">
            <air:AirAvailInfo ProviderCode="1P">
                <air:BookingCodeInfo BookingCounts="C3|J2|Z1|Y7|B7" />
            </air:AirAvailInfo>
            <air:FlightDetailsRef Key="W7AiU30YS66Cu3g5GMvl4Q==" />
        </air:AirSegment>
        <air:AirSegment Key="hiuMsaq9S2yMdmDq1DB95w==" Group="0" Carrier="UL" FlightNumber="127" Origin="CMB" Destination="MAA" DepartureTime="2016-01-10T13:45:00.000+05:30" ArrivalTime="2016-01-10T15:05:00.000+05:30" FlightTime="80" Distance="402" ETicketability="Yes" Equipment="320" ChangeOfPlane="false" OptionalServicesIndicator="false">
            <air:AirAvailInfo ProviderCode="1P">
                <air:BookingCodeInfo BookingCounts="J4|C4|D4|I4|Y7|B7|P4|H7|K7|W4|M7|E4|L7|R4|V7|S4|N7|Q4|O4" />
            </air:AirAvailInfo>
            <air:FlightDetailsRef Key="iAj7T3d3T/mC+l+cGUvSkQ==" />
        </air:AirSegment>
        <air:AirSegment Key="udzuWdPxTtyfnK9bOPEVYA==" Group="0" Carrier="UL" FlightNumber="167" Origin="CMB" Destination="COK" DepartureTime="2016-01-10T14:05:00.000+05:30" ArrivalTime="2016-01-10T15:25:00.000+05:30" FlightTime="80" Distance="312" ETicketability="Yes" Equipment="320" ChangeOfPlane="false" OptionalServicesIndicator="false">
            <air:AirAvailInfo ProviderCode="1P">
                <air:BookingCodeInfo BookingCounts="J4|C4|D4|I4|Y7|B7|P4|H7|K7|W4|M7|E4|L7|R4|V7|S4|N7|Q4|O4" />
            </air:AirAvailInfo>
            <air:FlightDetailsRef Key="Vls6qSi4TByn5gWxWcdEjw==" />
        </air:AirSegment>
        <air:AirSegment Key="DZ0wJ1+bSjKwAJ71fjCaQw==" Group="0" Carrier="AI" FlightNumber="55" Origin="COK" Destination="BOM" DepartureTime="2016-01-10T22:00:00.000+05:30" ArrivalTime="2016-01-10T23:45:00.000+05:30" FlightTime="105" Distance="672" ETicketability="Yes" Equipment="320" ChangeOfPlane="false" OptionalServicesIndicator="false">
            <air:AirAvailInfo ProviderCode="1P">
                <air:BookingCodeInfo BookingCounts="Y9|B4|M4|H4|K4|Q4|V4|W4|G4|L4|U4|T4|S4|E4|N4" />
            </air:AirAvailInfo>
            <air:FlightDetailsRef Key="MyzuH80cR6yXoA7DTMrXfg==" />
        </air:AirSegment>
        <air:AirSegment Key="E1uHScknSWaYlB++/CRkxg==" Group="0" Carrier="AI" FlightNumber="640" Origin="BLR" Destination="BOM" DepartureTime="2016-01-10T06:45:00.000+05:30" ArrivalTime="2016-01-10T08:20:00.000+05:30" FlightTime="95" Distance="519" ETicketability="Yes" Equipment="319" ChangeOfPlane="false" OptionalServicesIndicator="false">
            <air:AirAvailInfo ProviderCode="1P">
                <air:BookingCodeInfo BookingCounts="C4|D4|J4|Z4|Y9|B4|M4|H4|K4|Q4|V4|W4|G4|L4|U4|T4|S4|E4|N4" />
            </air:AirAvailInfo>
            <air:FlightDetailsRef Key="O1WZG9rfQh6Z5GR29cjDEA==" />
        </air:AirSegment>
        <air:AirSegment Key="wOKHGa45QSO1k1SsLFaNxA==" Group="0" Carrier="UL" FlightNumber="2925" Origin="CMB" Destination="MCT" DepartureTime="2016-01-10T19:05:00.000+05:30" ArrivalTime="2016-01-10T21:55:00.000+04:00" FlightTime="260" Distance="1816" ETicketability="Yes" Equipment="320" ChangeOfPlane="false" OptionalServicesIndicator="false">
            <air:CodeshareInfo OperatingCarrier="MJ" OperatingFlightNumber="205">MIHIN LANKA</air:CodeshareInfo>
            <air:AirAvailInfo ProviderCode="1P">
                <air:BookingCodeInfo BookingCounts="J4|C4|D4|I4|Y7|B7|P4|H7|K7|W4|M7|E4|L7|R4|V7|S4|N7|Q4|O4" />
            </air:AirAvailInfo>
            <air:FlightDetailsRef Key="YnaXTsJyQiyh0pUQJgGF9w==" />
        </air:AirSegment>
        <air:AirSegment Key="HbwA0eNpSKSL/NK0uC6Btg==" Group="0" Carrier="AI" FlightNumber="986" Origin="MCT" Destination="BOM" DepartureTime="2016-01-11T00:20:00.000+04:00" ArrivalTime="2016-01-11T04:45:00.000+05:30" FlightTime="175" Distance="974" ETicketability="Yes" Equipment="321" ChangeOfPlane="false" OptionalServicesIndicator="false">
            <air:AirAvailInfo ProviderCode="1P">
                <air:BookingCodeInfo BookingCounts="C4|D4|J4|Z4|Y9|B4|M4|H4|K4|Q4|V4|W4|G4|L4|U4|T4|S4|E4|N4" />
            </air:AirAvailInfo>
            <air:FlightDetailsRef Key="JAQeVRncQKyZAIGuxwJrsg==" />
        </air:AirSegment>
        <air:AirSegment Key="coVQAUPmR3+iFdpAUIpMpw==" Group="0" Carrier="9W" FlightNumber="539" Origin="MCT" Destination="BOM" DepartureTime="2016-01-11T00:25:00.000+04:00" ArrivalTime="2016-01-11T04:25:00.000+05:30" FlightTime="150" Distance="974" ETicketability="Yes" Equipment="738" ChangeOfPlane="false" OptionalServicesIndicator="false">
            <air:AirAvailInfo ProviderCode="1P">
                <air:BookingCodeInfo BookingCounts="C7|J7|Z7|I6|P5|Y7|M7|T7|U7|N7|L7|Q7|S7|K7|H7|V7|O7|W7|B7" />
            </air:AirAvailInfo>
            <air:FlightDetailsRef Key="pq5poDXrTmKm92wa33QLQQ==" />
        </air:AirSegment>
        <air:AirSegment Key="TDMQdRLDTByu/J0ixzyU6g==" Group="0" Carrier="MH" FlightNumber="184" Origin="CMB" Destination="KUL" DepartureTime="2016-01-10T12:55:00.000+05:30" ArrivalTime="2016-01-10T19:05:00.000+08:00" FlightTime="220" Distance="1526" ETicketability="Yes" Equipment="738" ChangeOfPlane="false" OptionalServicesIndicator="false">
            <air:AirAvailInfo ProviderCode="1P">
                <air:BookingCodeInfo BookingCounts="J4|C4|D4|Z4|I4|Y9|B9|H9|K9|M9|L9|V9|S9|N9|Q9|O9|G9" />
            </air:AirAvailInfo>
            <air:FlightDetailsRef Key="YOfdAUXKSAuMnUcIdFRZUQ==" />
        </air:AirSegment>
        <air:AirSegment Key="nenp5FUHRSOcOrtF3eQY8Q==" Group="0" Carrier="MH" FlightNumber="194" Origin="KUL" Destination="BOM" DepartureTime="2016-01-10T20:30:00.000+08:00" ArrivalTime="2016-01-10T22:55:00.000+05:30" FlightTime="295" Distance="2241" ETicketability="Yes" Equipment="738" ChangeOfPlane="false" OptionalServicesIndicator="false">
            <air:AirAvailInfo ProviderCode="1P">
                <air:BookingCodeInfo BookingCounts="J4|C4|D4|Z4|I4|Y9|B9|H9|K9|M9|L9|V9|S9|N9|Q9|O9|G9" />
            </air:AirAvailInfo>
            <air:FlightDetailsRef Key="5OHeNUlBQmiqbT/Ya37VCg==" />
        </air:AirSegment>
        <air:AirSegment Key="yuFnXJ8MSoaBigLqycUt+g==" Group="0" Carrier="TG" FlightNumber="308" Origin="CMB" Destination="BKK" DepartureTime="2016-01-10T01:30:00.000+05:30" ArrivalTime="2016-01-10T06:25:00.000+07:00" FlightTime="205" Distance="1485" ETicketability="Yes" Equipment="777" ChangeOfPlane="false" OptionalServicesIndicator="false">
            <air:AirAvailInfo ProviderCode="1P">
                <air:BookingCodeInfo BookingCounts="C9|D9|J9|Z9|I9|R9|Y9|B9|M9|H9|Q9|T9|K9|S9|X9|V9|W9" />
            </air:AirAvailInfo>
            <air:FlightDetailsRef Key="POPCdSLSRJKEpeRLoCe0bw==" />
        </air:AirSegment>
        <air:AirSegment Key="+ZT4DTDoQQu7I/2fn7yN5w==" Group="0" Carrier="TG" FlightNumber="317" Origin="BKK" Destination="BOM" DepartureTime="2016-01-10T18:55:00.000+07:00" ArrivalTime="2016-01-10T22:00:00.000+05:30" FlightTime="275" Distance="1878" ETicketability="Yes" Equipment="744" ChangeOfPlane="false" OptionalServicesIndicator="false">
            <air:AirAvailInfo ProviderCode="1P">
                <air:BookingCodeInfo BookingCounts="C9|D7|Y9|B9|M9|H9|Q9|T9|K9|S9|X9|V9|W9" />
            </air:AirAvailInfo>
            <air:FlightDetailsRef Key="qchCpgzITOStAd5PGztx3g==" />
        </air:AirSegment>
        <air:AirSegment Key="9R3hMegXSHeNFc0r0kYvVw==" Group="0" Carrier="EY" FlightNumber="265" Origin="CMB" Destination="AUH" DepartureTime="2016-01-10T21:00:00.000+05:30" ArrivalTime="2016-01-11T00:25:00.000+04:00" FlightTime="295" Distance="2061" ETicketability="Yes" Equipment="320" ChangeOfPlane="false" OptionalServicesIndicator="false">
            <air:AirAvailInfo ProviderCode="1P">
                <air:BookingCodeInfo BookingCounts="J7|C6|D6|W5|Y7|B7|H7" />
            </air:AirAvailInfo>
            <air:FlightDetailsRef Key="k2d+YuMbRWaP8Syg9pZQyg==" />
        </air:AirSegment>
        <air:AirSegment Key="oulYHHIuR3G8t/OLW7fU3g==" Group="0" Carrier="EY" FlightNumber="212" Origin="AUH" Destination="BOM" DepartureTime="2016-01-11T03:10:00.000+04:00" ArrivalTime="2016-01-11T08:15:00.000+05:30" FlightTime="215" Distance="1237" ETicketability="Yes" Equipment="320" ChangeOfPlane="false" OptionalServicesIndicator="false">
            <air:AirAvailInfo ProviderCode="1P">
                <air:BookingCodeInfo BookingCounts="J7|C6|D6|W5|Y7|B7|H7" />
            </air:AirAvailInfo>
            <air:FlightDetailsRef Key="FTm/u+2xQIyQ9hQHUwnybw==" />
        </air:AirSegment>
        <air:AirSegment Key="6MVL9G8MT1eHNQsLr++n3g==" Group="0" Carrier="HM" FlightNumber="5523" Origin="CMB" Destination="AUH" DepartureTime="2016-01-10T21:00:00.000+05:30" ArrivalTime="2016-01-11T00:25:00.000+04:00" FlightTime="295" Distance="2061" ETicketability="Yes" Equipment="320" ChangeOfPlane="false" OptionalServicesIndicator="false">
            <air:CodeshareInfo OperatingCarrier="EY" OperatingFlightNumber="265">ETIHAD AIRWAYS</air:CodeshareInfo>
            <air:AirAvailInfo ProviderCode="1P">
                <air:BookingCodeInfo BookingCounts="J4|D4|C4|I4|Y9|B9|H9|K9|L9|W9|S9|M9|N9|V9|G9" />
            </air:AirAvailInfo>
            <air:FlightDetailsRef Key="k2d+YuMbRWaP8Syg9pZQyg==" />
        </air:AirSegment>
        <air:AirSegment Key="SIeg1x73Sw2gmoXzaOghoQ==" Group="0" Carrier="HM" FlightNumber="5320" Origin="AUH" Destination="BOM" DepartureTime="2016-01-11T03:10:00.000+04:00" ArrivalTime="2016-01-11T08:15:00.000+05:30" FlightTime="215" Distance="1237" ETicketability="Yes" Equipment="320" ChangeOfPlane="false" OptionalServicesIndicator="false">
            <air:CodeshareInfo OperatingCarrier="EY" OperatingFlightNumber="212">ETIHAD AIRWAYS</air:CodeshareInfo>
            <air:AirAvailInfo ProviderCode="1P">
                <air:BookingCodeInfo BookingCounts="J4|D4|C4|I4|Y9|B9|H9|K9|L9|W9|S9|M9|N9|V9|G9" />
            </air:AirAvailInfo>
            <air:FlightDetailsRef Key="FTm/u+2xQIyQ9hQHUwnybw==" />
        </air:AirSegment>
        <air:AirSegment Key="TIspzxT7QUKgsnoej2t8Ew==" Group="0" Carrier="SQ" FlightNumber="469" Origin="CMB" Destination="SIN" DepartureTime="2016-01-10T01:10:00.000+05:30" ArrivalTime="2016-01-10T07:40:00.000+08:00" FlightTime="240" Distance="1709" ETicketability="Yes" Equipment="333" ChangeOfPlane="false" OptionalServicesIndicator="false">
            <air:AirAvailInfo ProviderCode="1P">
                <air:BookingCodeInfo BookingCounts="Z9|C9|J9|U9|D9|Y9|B9|E9|M9|H9|Q9|N9" />
            </air:AirAvailInfo>
            <air:FlightDetailsRef Key="RRuv/BCKT+ySfdmpHZ0ebw==" />
        </air:AirSegment>
        <air:AirSegment Key="UFfsRUqWR/uJXKLJ4tKsMQ==" Group="0" Carrier="SQ" FlightNumber="424" Origin="SIN" Destination="BOM" DepartureTime="2016-01-10T18:55:00.000+08:00" ArrivalTime="2016-01-10T21:45:00.000+05:30" FlightTime="320" Distance="2437" ETicketability="Yes" Equipment="388" ChangeOfPlane="false" OptionalServicesIndicator="false">
            <air:AirAvailInfo ProviderCode="1P">
                <air:BookingCodeInfo BookingCounts="R9|F9|A8|Z9|C9|J6|S9|T9|P9|Y9|B9|E9|M9|H9|W9|Q9|N9|L9" />
            </air:AirAvailInfo>
            <air:FlightDetailsRef Key="LU2oY/tvSlqqK8tO4MAywA==" />
        </air:AirSegment>
        <air:AirSegment Key="PHdojzyGSTqpgr0CLgQf+A==" Group="0" Carrier="QF" FlightNumber="3420" Origin="CMB" Destination="SIN" DepartureTime="2016-01-10T01:00:00.000+05:30" ArrivalTime="2016-01-10T07:30:00.000+08:00" FlightTime="240" Distance="1709" ETicketability="Yes" Equipment="320" ChangeOfPlane="false" OptionalServicesIndicator="false">
            <air:CodeshareInfo OperatingCarrier="UL" OperatingFlightNumber="306">SRILANKAN AIRLINES</air:CodeshareInfo>
            <air:AirAvailInfo ProviderCode="1P">
                <air:BookingCodeInfo BookingCounts="J9|C8|D8|I6|Y9|B9|H9|K9|M9|L9|V9|S9" />
            </air:AirAvailInfo>
            <air:FlightDetailsRef Key="ZTqx8q2dQwGfadAzHtF9Aw==" />
        </air:AirSegment>
        <air:AirSegment Key="cDtsLDA8RNOB89qNWL6m5Q==" Group="0" Carrier="QF" FlightNumber="3957" Origin="SIN" Destination="BOM" DepartureTime="2016-01-10T10:30:00.000+08:00" ArrivalTime="2016-01-10T13:30:00.000+05:30" FlightTime="330" Distance="2437" ETicketability="Yes" Equipment="737" ChangeOfPlane="false" OptionalServicesIndicator="false">
            <air:CodeshareInfo OperatingCarrier="9W" OperatingFlightNumber="9">JET*AIRWAYS*INDIA*LTD</air:CodeshareInfo>
            <air:AirAvailInfo ProviderCode="1P">
                <air:BookingCodeInfo BookingCounts="J9|C9|D9|I9|Y9|B9|H9|K9" />
            </air:AirAvailInfo>
            <air:FlightDetailsRef Key="KIiBDbgcQYqVlpSz9w5i1g==" />
        </air:AirSegment>
    </air:AirSegmentList>
    <air:FareInfoList>
        <air:FareInfo Key="1spaEzfCQ6u8Gji2V32UoA==" FareBasis="LOW" PassengerTypeCode="ADT" Origin="CMB" Destination="BOM" EffectiveDate="2016-01-02T04:58:00.000+00:00" DepartureDate="2016-01-10" Amount="SGD170.00" NegotiatedFare="false" NotValidBefore="2016-01-10" NotValidAfter="2016-01-10">
            <air:BaggageAllowance>
                <air:MaxWeight Value="30" Unit="Kilograms" />
            </air:BaggageAllowance>
            <air:FareRuleKey FareInfoRef="1spaEzfCQ6u8Gji2V32UoA==" ProviderCode="1P">dir-CMB^BOM^2016010^ALL^LOW^AI^^</air:FareRuleKey>
            <air:Brand Key="NLh+a2unTsqsybUAdaRI2Q==" BrandFound="false" UpSellBrandFound="false" />
        </air:FareInfo>
        <air:FareInfo Key="pGAa6IujRsiUpF5xDl7qQw==" FareBasis="K2OWLK" PassengerTypeCode="ADT" Origin="CMB" Destination="BOM" EffectiveDate="2016-01-02T04:58:00.000+00:00" DepartureDate="2016-01-10" Amount="SGD222.00" NegotiatedFare="false" NotValidBefore="2016-01-10" NotValidAfter="2016-01-10">
            <air:FareSurcharge Key="qFpi8DNESk+RxL/4yTFkow==" Type="Weekend Surcharge" Amount="NUC20.00" />
            <air:BaggageAllowance>
                <air:MaxWeight Value="30" Unit="Kilograms" />
            </air:BaggageAllowance>
            <air:FareRuleKey FareInfoRef="pGAa6IujRsiUpF5xDl7qQw==" ProviderCode="1P">dir-CMB^BOM^2016010^ALL^K2OWLK^9W^^</air:FareRuleKey>
        </air:FareInfo>
        <air:FareInfo Key="QHe4j31/T4C5yM7vd1nNBA==" FareBasis="REFOWLK" PassengerTypeCode="ADT" Origin="CMB" Destination="BOM" EffectiveDate="2016-01-02T04:58:00.000+00:00" DepartureDate="2016-01-10" Amount="SGD219.00" NegotiatedFare="false" NotValidBefore="2016-01-10" NotValidAfter="2016-01-10">
            <air:BaggageAllowance>
                <air:MaxWeight Value="30" Unit="Kilograms" />
            </air:BaggageAllowance>
            <air:FareRuleKey FareInfoRef="QHe4j31/T4C5yM7vd1nNBA==" ProviderCode="1P">dir-CMB^BOM^2016010^ALL^REFOWLK^MJ^^</air:FareRuleKey>
        </air:FareInfo>
        <air:FareInfo Key="nkKa1jRTTJqFU6s1ZzULRA==" FareBasis="LOWLK" PassengerTypeCode="ADT" Origin="CMB" Destination="BOM" EffectiveDate="2016-01-02T04:58:00.000+00:00" DepartureDate="2016-01-10" Amount="SGD240.00" NegotiatedFare="false" NotValidBefore="2016-01-10" NotValidAfter="2016-01-10">
            <air:BaggageAllowance>
                <air:MaxWeight Value="30" Unit="Kilograms" />
            </air:BaggageAllowance>
            <air:FareRuleKey FareInfoRef="nkKa1jRTTJqFU6s1ZzULRA==" ProviderCode="1P">dir-CMB^BOM^2016010^ALL^LOWLK^UL^^</air:FareRuleKey>
        </air:FareInfo>
        <air:FareInfo Key="xg/hLJxzRrioyMfwnyKeaA==" FareBasis="TOWLKD" PassengerTypeCode="ADT" Origin="CMB" Destination="BOM" EffectiveDate="2016-01-02T04:58:00.000+00:00" DepartureDate="2016-01-10" Amount="SGD280.00" NegotiatedFare="false" NotValidBefore="2016-01-11" NotValidAfter="2016-01-11">
            <air:BaggageAllowance>
                <air:MaxWeight Value="30" Unit="Kilograms" />
            </air:BaggageAllowance>
            <air:FareRuleKey FareInfoRef="xg/hLJxzRrioyMfwnyKeaA==" ProviderCode="1P">dir-CMB^BOM^2016010^ALL^TOWLKD^AI^^</air:FareRuleKey>
            <air:Brand Key="ebl5ItYxSri4JmR6ZkMdgg==" BrandFound="false" UpSellBrandFound="false" />
        </air:FareInfo>
        <air:FareInfo Key="MrwSFEOoS2mV1F3pUYP1bQ==" FareBasis="WOWLKD" PassengerTypeCode="ADT" Origin="CMB" Destination="DEL" EffectiveDate="2016-01-02T04:58:00.000+00:00" DepartureDate="2016-01-10" Amount="SGD290.00" NegotiatedFare="false" NotValidBefore="2016-01-10" NotValidAfter="2016-01-10">
            <air:BaggageAllowance>
                <air:MaxWeight Value="30" Unit="Kilograms" />
            </air:BaggageAllowance>
            <air:FareRuleKey FareInfoRef="MrwSFEOoS2mV1F3pUYP1bQ==" ProviderCode="1P">dir-CMB^DEL^2016010^ALL^WOWLKD^AI^^</air:FareRuleKey>
            <air:Brand Key="X8FPdgCsS4yNQ4zhCCC88Q==" BrandFound="false" UpSellBrandFound="false" />
        </air:FareInfo>
        <air:FareInfo Key="Q1TNJfQlTbm5dnmMq7tOkg==" FareBasis="UIPD" PassengerTypeCode="ADT" Origin="DEL" Destination="BLR" EffectiveDate="2016-01-02T04:58:00.000+00:00" DepartureDate="2016-01-10" Amount="SGD39.00" NegotiatedFare="false" NotValidBefore="2016-01-10" NotValidAfter="2016-01-10">
            <air:BaggageAllowance>
                <air:MaxWeight Value="30" Unit="Kilograms" />
            </air:BaggageAllowance>
            <air:FareRuleKey FareInfoRef="Q1TNJfQlTbm5dnmMq7tOkg==" ProviderCode="1P">dir-DEL^BLR^2016010^ALL^UIPD^AI^^</air:FareRuleKey>
            <air:Brand Key="zMuyoF14RKqPQOu/O6BqPA==" BrandFound="false" UpSellBrandFound="false" />
        </air:FareInfo>
        <air:FareInfo Key="AoAaPfLASeGBrwjJ0OvZ6A==" FareBasis="UIPB" PassengerTypeCode="ADT" Origin="BLR" Destination="BOM" EffectiveDate="2016-01-02T04:58:00.000+00:00" DepartureDate="2016-01-10" Amount="SGD48.00" NegotiatedFare="false" NotValidBefore="2016-01-11" NotValidAfter="2016-01-11">
            <air:BaggageAllowance>
                <air:MaxWeight Value="30" Unit="Kilograms" />
            </air:BaggageAllowance>
            <air:FareRuleKey FareInfoRef="AoAaPfLASeGBrwjJ0OvZ6A==" ProviderCode="1P">dir-BLR^BOM^2016010^ALL^UIPB^AI^^</air:FareRuleKey>
            <air:Brand Key="WbWzDolGTpmW+DuqXxN92Q==" BrandFound="false" UpSellBrandFound="false" />
        </air:FareInfo>
        <air:FareInfo Key="xlzIG2AgRcmkMkLwQcBypQ==" FareBasis="WOWLKD" PassengerTypeCode="ADT" Origin="CMB" Destination="BLR" EffectiveDate="2016-01-02T04:58:00.000+00:00" DepartureDate="2016-01-10" Amount="SGD356.00" NegotiatedFare="false" NotValidBefore="2016-01-11" NotValidAfter="2016-01-11">
            <air:BaggageAllowance>
                <air:MaxWeight Value="30" Unit="Kilograms" />
            </air:BaggageAllowance>
            <air:FareRuleKey FareInfoRef="xlzIG2AgRcmkMkLwQcBypQ==" ProviderCode="1P">dir-CMB^BLR^2016010^ALL^WOWLKD^AI^^</air:FareRuleKey>
            <air:Brand Key="lTclpKz7TOiBqCrHyH6o0A==" BrandFound="false" UpSellBrandFound="false" />
        </air:FareInfo>
        <air:FareInfo Key="lHw96QzwRlyb+D2jD34mYQ==" FareBasis="EOW2LK1" PassengerTypeCode="ADT" Origin="CMB" Destination="BOM" EffectiveDate="2016-01-02T04:58:00.000+00:00" DepartureDate="2016-01-10" Amount="SGD477.00" NegotiatedFare="false">
            <air:BaggageAllowance>
                <air:MaxWeight Value="30" Unit="Kilograms" />
            </air:BaggageAllowance>
            <air:FareRuleKey FareInfoRef="lHw96QzwRlyb+D2jD34mYQ==" ProviderCode="1P">dir-CMB^BOM^2016010^ALL^EOW2LK1^FZ^^</air:FareRuleKey>
            <air:Brand Key="gVBazbISTdqOtkEd2RMJ0Q==" BrandID="5127" UpSellBrandID="5128">
                <air:UpsellBrand FareBasis="IOW2LK1" FareInfoRef="8MDLWMgQRSO7Kr3D8TlJtA==" />
            </air:Brand>
        </air:FareInfo>
        <air:FareInfo Key="K5XGlnwBSO6T1r7EYMejGA==" FareBasis="SAPOWLK" PassengerTypeCode="ADT" Origin="CMB" Destination="BLR" EffectiveDate="2016-01-02T04:58:00.000+00:00" DepartureDate="2016-01-10" Amount="SGD115.00" NegotiatedFare="false" NotValidBefore="2016-01-10" NotValidAfter="2016-01-10">
            <air:BaggageAllowance>
                <air:MaxWeight Value="30" Unit="Kilograms" />
            </air:BaggageAllowance>
            <air:FareRuleKey FareInfoRef="K5XGlnwBSO6T1r7EYMejGA==" ProviderCode="1P">dir-CMB^BLR^2016010^ALL^SAPOWLK^UL^^</air:FareRuleKey>
        </air:FareInfo>
        <air:FareInfo Key="Cl8Udzh9Sh62av+8JgLBhw==" FareBasis="Y2OW" PassengerTypeCode="ADT" Origin="BLR" Destination="BOM" EffectiveDate="2016-01-02T04:58:00.000+00:00" DepartureDate="2016-01-10" Amount="SGD436.00" NegotiatedFare="false" NotValidBefore="2016-01-10" NotValidAfter="2016-01-10">
            <air:FareSurcharge Key="JhGiq/kOR56aldQ5RFqlYQ==" Type="Other" Amount="NUC20.00" />
            <air:BaggageAllowance>
                <air:MaxWeight Value="30" Unit="Kilograms" />
            </air:BaggageAllowance>
            <air:FareRuleKey FareInfoRef="Cl8Udzh9Sh62av+8JgLBhw==" ProviderCode="1P">dir-BLR^BOM^2016010^ALL^Y2OW^9W^^</air:FareRuleKey>
        </air:FareInfo>
        <air:FareInfo Key="RsOaRIpvR0qWOrrfO27Zzg==" FareBasis="QOWLK" PassengerTypeCode="ADT" Origin="CMB" Destination="MAA" EffectiveDate="2016-01-02T04:58:00.000+00:00" DepartureDate="2016-01-10" Amount="SGD97.00" NegotiatedFare="false" NotValidBefore="2016-01-10" NotValidAfter="2016-01-10">
            <air:BaggageAllowance>
                <air:MaxWeight Value="30" Unit="Kilograms" />
            </air:BaggageAllowance>
            <air:FareRuleKey FareInfoRef="RsOaRIpvR0qWOrrfO27Zzg==" ProviderCode="1P">dir-CMB^MAA^2016010^ALL^QOWLK^UL^^</air:FareRuleKey>
        </air:FareInfo>
        <air:FareInfo Key="xlATZsktRp+2TSP/kpA9Ow==" FareBasis="Y2OW" PassengerTypeCode="ADT" Origin="MAA" Destination="BOM" EffectiveDate="2016-01-02T04:58:00.000+00:00" DepartureDate="2016-01-10" Amount="SGD472.00" NegotiatedFare="false" NotValidBefore="2016-01-11" NotValidAfter="2016-01-11">
            <air:FareSurcharge Key="kv3lElICTOeLjdT2nYXQHQ==" Type="Other" Amount="NUC20.00" />
            <air:BaggageAllowance>
                <air:MaxWeight Value="30" Unit="Kilograms" />
            </air:BaggageAllowance>
            <air:FareRuleKey FareInfoRef="xlATZsktRp+2TSP/kpA9Ow==" ProviderCode="1P">dir-MAA^BOM^2016010^ALL^Y2OW^9W^^</air:FareRuleKey>
        </air:FareInfo>
        <air:FareInfo Key="m4F05tCeS06DPWuGO1uodg==" FareBasis="Z2OWLK" PassengerTypeCode="ADT" Origin="CMB" Destination="BOM" EffectiveDate="2016-01-02T04:58:00.000+00:00" DepartureDate="2016-01-10" Amount="SGD649.00" NegotiatedFare="false" NotValidBefore="2016-01-10" NotValidAfter="2016-01-10">
            <air:FareSurcharge Key="djTVdq18QuOYeIXWRclSng==" Type="Weekend Surcharge" Amount="NUC25.00" />
            <air:BaggageAllowance>
                <air:MaxWeight Value="40" Unit="Kilograms" />
            </air:BaggageAllowance>
            <air:FareRuleKey FareInfoRef="m4F05tCeS06DPWuGO1uodg==" ProviderCode="1P">dir-CMB^BOM^2016010^ALL^Z2OWLK^9W^^</air:FareRuleKey>
        </air:FareInfo>
        <air:FareInfo Key="XKPXAyLvRyuXQyBDg1pYxQ==" FareBasis="YIF" PassengerTypeCode="ADT" Origin="CMB" Destination="BOM" EffectiveDate="2016-01-02T04:58:00.000+00:00" DepartureDate="2016-01-10" Amount="SGD759.00" NegotiatedFare="false">
            <air:BaggageAllowance>
                <air:MaxWeight Value="30" Unit="Kilograms" />
            </air:BaggageAllowance>
            <air:FareRuleKey FareInfoRef="XKPXAyLvRyuXQyBDg1pYxQ==" ProviderCode="1P">dir-CMB^BOM^2016010^ALL^YIF^YY^^</air:FareRuleKey>
        </air:FareInfo>
        <air:FareInfo Key="tCFMdIJqS4iCyh+UY7JLuw==" FareBasis="QOWMJLK" PassengerTypeCode="ADT" Origin="CMB" Destination="MCT" EffectiveDate="2016-01-02T04:58:00.000+00:00" DepartureDate="2016-01-10" Amount="SGD141.00" NegotiatedFare="false" NotValidBefore="2016-01-10" NotValidAfter="2016-01-10">
            <air:FareSurcharge Key="rlOzMjHDQqi2+/k93Rf35w==" Type="Other" Amount="NUC3.00" />
            <air:BaggageAllowance>
                <air:MaxWeight Value="30" Unit="Kilograms" />
            </air:BaggageAllowance>
            <air:FareRuleKey FareInfoRef="tCFMdIJqS4iCyh+UY7JLuw==" ProviderCode="1P">dir-CMB^MCT^2016010^ALL^QOWMJLK^UL^^</air:FareRuleKey>
        </air:FareInfo>
        <air:FareInfo Key="2kHvsa7xQBuVcFzqm5Fy1Q==" FareBasis="Y" PassengerTypeCode="ADT" Origin="MCT" Destination="BOM" EffectiveDate="2016-01-02T04:58:00.000+00:00" DepartureDate="2016-01-10" Amount="SGD705.00" NegotiatedFare="false">
            <air:BaggageAllowance>
                <air:MaxWeight Value="30" Unit="Kilograms" />
            </air:BaggageAllowance>
            <air:FareRuleKey FareInfoRef="2kHvsa7xQBuVcFzqm5Fy1Q==" ProviderCode="1P">dir-MCT^BOM^2016010^ALL^Y^YY^^</air:FareRuleKey>
            <air:Brand Key="qcWwQU9UTnu8ECczI0+KKQ==" BrandFound="false" UpSellBrandFound="false" />
        </air:FareInfo>
        <air:FareInfo Key="ZZOoOTUNRQ+Wa579FlHfeQ==" FareBasis="Y" PassengerTypeCode="ADT" Origin="MCT" Destination="BOM" EffectiveDate="2016-01-02T04:58:00.000+00:00" DepartureDate="2016-01-10" Amount="SGD705.00" NegotiatedFare="false">
            <air:BaggageAllowance>
                <air:MaxWeight Value="30" Unit="Kilograms" />
            </air:BaggageAllowance>
            <air:FareRuleKey FareInfoRef="ZZOoOTUNRQ+Wa579FlHfeQ==" ProviderCode="1P">dir-MCT^BOM^2016010^ALL^Y^YY^^</air:FareRuleKey>
        </air:FareInfo>
        <air:FareInfo Key="S50dIJiOT7ugOzLyNSWwsg==" FareBasis="YIFMH" PassengerTypeCode="ADT" Origin="CMB" Destination="KUL" EffectiveDate="2016-01-02T04:58:00.000+00:00" DepartureDate="2016-01-10" Amount="SGD698.00" NegotiatedFare="false" NotValidAfter="2017-01-10">
            <air:BaggageAllowance>
                <air:MaxWeight Value="30" Unit="Kilograms" />
            </air:BaggageAllowance>
            <air:FareRuleKey FareInfoRef="S50dIJiOT7ugOzLyNSWwsg==" ProviderCode="1P">dir-CMB^KUL^2016010^ALL^YIFMH^MH^^</air:FareRuleKey>
        </air:FareInfo>
        <air:FareInfo Key="b953pyqFRr+8y8KHc5OobQ==" FareBasis="LST1YMY" PassengerTypeCode="ADT" Origin="KUL" Destination="BOM" EffectiveDate="2016-01-02T04:58:00.000+00:00" DepartureDate="2016-01-10" Amount="SGD368.00" NegotiatedFare="false" NotValidBefore="2016-01-10" NotValidAfter="2016-01-10">
            <air:FareSurcharge Key="t03lsk/zRYmwSdDDrR6PNw==" Type="Other" Amount="NUC30.00" />
            <air:BaggageAllowance>
                <air:MaxWeight Value="30" Unit="Kilograms" />
            </air:BaggageAllowance>
            <air:FareRuleKey FareInfoRef="b953pyqFRr+8y8KHc5OobQ==" ProviderCode="1P">dir-KUL^BOM^2016010^ALL^LST1YMY^MH^^</air:FareRuleKey>
        </air:FareInfo>
        <air:FareInfo Key="m85d68MCQQ+tA/rEBCYWKw==" FareBasis="YOMTG" PassengerTypeCode="ADT" Origin="CMB" Destination="BKK" EffectiveDate="2016-01-02T04:58:00.000+00:00" DepartureDate="2016-01-10" Amount="SGD527.00" NegotiatedFare="false">
            <air:BaggageAllowance>
                <air:MaxWeight Value="30" Unit="Kilograms" />
            </air:BaggageAllowance>
            <air:FareRuleKey FareInfoRef="m85d68MCQQ+tA/rEBCYWKw==" ProviderCode="1P">dir-CMB^BKK^2016010^ALL^YOMTG^TG^^</air:FareRuleKey>
            <air:Brand Key="MF8D4N/xSHuF+71KMYSeqA==" BrandFound="false" UpSellBrandFound="false" />
        </air:FareInfo>
        <air:FareInfo Key="UisGy7vKSdiRtokN6DLZLw==" FareBasis="YOMTG" PassengerTypeCode="ADT" Origin="BKK" Destination="BOM" EffectiveDate="2016-01-02T04:58:00.000+00:00" DepartureDate="2016-01-10" Amount="SGD911.00" NegotiatedFare="false">
            <air:BaggageAllowance>
                <air:MaxWeight Value="30" Unit="Kilograms" />
            </air:BaggageAllowance>
            <air:FareRuleKey FareInfoRef="UisGy7vKSdiRtokN6DLZLw==" ProviderCode="1P">dir-BKK^BOM^2016010^ALL^YOMTG^TG^^</air:FareRuleKey>
            <air:Brand Key="dhni9Eh2R9OtKai0lU2ODw==" BrandFound="false" UpSellBrandFound="false" />
        </air:FareInfo>
        <air:FareInfo Key="wRPr5T4sR+mNHofsO8CfIQ==" FareBasis="YRTLK" PassengerTypeCode="ADT" Origin="CMB" Destination="AUH" EffectiveDate="2016-01-02T04:58:00.000+00:00" DepartureDate="2016-01-10" Amount="SGD550.00" NegotiatedFare="false" NotValidAfter="2017-01-10">
            <air:FareTicketDesignator Value="YF" />
            <air:FareSurcharge Key="WClRyMVNS0uDKm6KurPlyA==" Type="Other" Amount="NUC52.00" />
            <air:BaggageAllowance>
                <air:NumberOfPieces>2</air:NumberOfPieces>
                <air:MaxWeight />
            </air:BaggageAllowance>
            <air:FareRuleKey FareInfoRef="wRPr5T4sR+mNHofsO8CfIQ==" ProviderCode="1P">dir-CMB^AUH^2016010^ALL^YRTLK^EY^^</air:FareRuleKey>
            <air:Brand Key="SBJhN8L9TEmaWZDzZOvmgQ==" BrandFound="false" UpSellBrandFound="false" />
        </air:FareInfo>
        <air:FareInfo Key="QGzgZMKOTX6TJB+1VnF5lg==" FareBasis="YRT11" PassengerTypeCode="ADT" Origin="AUH" Destination="BOM" EffectiveDate="2016-01-02T04:58:00.000+00:00" DepartureDate="2016-01-10" Amount="SGD1284.00" NegotiatedFare="false" NotValidAfter="2017-01-10">
            <air:FareTicketDesignator Value="YF" />
            <air:BaggageAllowance>
                <air:NumberOfPieces>2</air:NumberOfPieces>
                <air:MaxWeight />
            </air:BaggageAllowance>
            <air:FareRuleKey FareInfoRef="QGzgZMKOTX6TJB+1VnF5lg==" ProviderCode="1P">dir-AUH^BOM^2016010^ALL^YRT11^EY^^</air:FareRuleKey>
            <air:Brand Key="fAYKLw0uSQCMYaswTCiTug==" BrandFound="false" UpSellBrandFound="false" />
        </air:FareInfo>
        <air:FareInfo Key="Qv5IfRX5Q1mgPIolx2q6wA==" FareBasis="YIF" PassengerTypeCode="ADT" Origin="CMB" Destination="AUH" EffectiveDate="2016-01-02T04:58:00.000+00:00" DepartureDate="2016-01-10" Amount="SGD1049.00" NegotiatedFare="false">
            <air:BaggageAllowance>
                <air:NumberOfPieces>2</air:NumberOfPieces>
                <air:MaxWeight />
            </air:BaggageAllowance>
            <air:FareRuleKey FareInfoRef="Qv5IfRX5Q1mgPIolx2q6wA==" ProviderCode="1P">dir-CMB^AUH^2016010^ALL^YIF^YY^^</air:FareRuleKey>
        </air:FareInfo>
        <air:FareInfo Key="QYyaqkhQTnmz3rYQit8vhg==" FareBasis="YIF" PassengerTypeCode="ADT" Origin="AUH" Destination="BOM" EffectiveDate="2016-01-02T04:58:00.000+00:00" DepartureDate="2016-01-10" Amount="SGD1211.00" NegotiatedFare="false">
            <air:BaggageAllowance>
                <air:NumberOfPieces>2</air:NumberOfPieces>
                <air:MaxWeight />
            </air:BaggageAllowance>
            <air:FareRuleKey FareInfoRef="QYyaqkhQTnmz3rYQit8vhg==" ProviderCode="1P">dir-AUH^BOM^2016010^ALL^YIF^YY^^</air:FareRuleKey>
        </air:FareInfo>
        <air:FareInfo Key="Iu/ZuMcRRuq7Rl8fssqXBg==" FareBasis="YRTLK6" PassengerTypeCode="ADT" Origin="CMB" Destination="SIN" EffectiveDate="2016-01-02T04:58:00.000+00:00" DepartureDate="2016-01-10" Amount="SGD618.00" NegotiatedFare="false" NotValidAfter="2017-01-10">
            <air:BaggageAllowance>
                <air:MaxWeight Value="30" Unit="Kilograms" />
            </air:BaggageAllowance>
            <air:FareRuleKey FareInfoRef="Iu/ZuMcRRuq7Rl8fssqXBg==" ProviderCode="1P">dir-CMB^SIN^2016010^ALL^YRTLK6^SQ^^</air:FareRuleKey>
            <air:Brand Key="eKHqp2esS9a26IhDR5LBrw==" BrandID="5705" UpSellBrandFound="false" />
        </air:FareInfo>
        <air:FareInfo Key="pY2MfEUUQcmdaak/SUPJ8w==" FareBasis="YIF" PassengerTypeCode="ADT" Origin="SIN" Destination="BOM" EffectiveDate="2016-01-02T04:58:00.000+00:00" DepartureDate="2016-01-10" Amount="SGD2497.00" NegotiatedFare="false" NotValidAfter="2017-01-10">
            <air:BaggageAllowance>
                <air:MaxWeight Value="30" Unit="Kilograms" />
            </air:BaggageAllowance>
            <air:FareRuleKey FareInfoRef="pY2MfEUUQcmdaak/SUPJ8w==" ProviderCode="1P">dir-SIN^BOM^2016010^ALL^YIF^YY^^</air:FareRuleKey>
            <air:Brand Key="1GGjv0L8Rqqx9ysAYKXL9w==" BrandID="5705" UpSellBrandFound="false" />
        </air:FareInfo>
        <air:FareInfo Key="BEbnxNWcQhe26FlfS+mphQ==" FareBasis="YIF" PassengerTypeCode="ADT" Origin="CMB" Destination="SIN" EffectiveDate="2016-01-02T04:58:00.000+00:00" DepartureDate="2016-01-10" Amount="SGD714.00" NegotiatedFare="false">
            <air:BaggageAllowance>
                <air:MaxWeight Value="30" Unit="Kilograms" />
            </air:BaggageAllowance>
            <air:FareRuleKey FareInfoRef="BEbnxNWcQhe26FlfS+mphQ==" ProviderCode="1P">dir-CMB^SIN^2016010^ALL^YIF^YY^^</air:FareRuleKey>
        </air:FareInfo>
        <air:FareInfo Key="GnpFB8oGRAG8mvfGJ8ofnw==" FareBasis="CIF" PassengerTypeCode="ADT" Origin="SIN" Destination="BOM" EffectiveDate="2016-01-02T04:58:00.000+00:00" DepartureDate="2016-01-10" Amount="SGD3273.00" NegotiatedFare="false">
            <air:BaggageAllowance>
                <air:MaxWeight Value="30" Unit="Kilograms" />
            </air:BaggageAllowance>
            <air:FareRuleKey FareInfoRef="GnpFB8oGRAG8mvfGJ8ofnw==" ProviderCode="1P">dir-SIN^BOM^2016010^ALL^CIF^YY^^</air:FareRuleKey>
        </air:FareInfo>
        <air:FareInfo Key="8MDLWMgQRSO7Kr3D8TlJtA==" FareBasis="IOW2LK1" PassengerTypeCode="ADT" Origin="CMB" Destination="BOM" EffectiveDate="2016-01-02T04:58:00.000+00:00" DepartureDate="2016-01-10" Amount="SGD556.00" NegotiatedFare="false">
            <air:BaggageAllowance>
                <air:MaxWeight Value="30" Unit="Kilograms" />
            </air:BaggageAllowance>
            <air:FareRuleKey FareInfoRef="8MDLWMgQRSO7Kr3D8TlJtA==" ProviderCode="1P">dir-CMB^BOM^2016010^ALL^IOW2LK1^FZ^^</air:FareRuleKey>
            <air:Brand Key="nQyxKWcMR32fhcQA9JzB7A==" BrandID="5128" UpSellBrandFound="false" />
        </air:FareInfo>
    </air:FareInfoList>
    <air:RouteList>
        <air:Route Key="oNkJPTBER0ONvY5ji/aIAw==">
            <air:Leg Key="AGeIxHoCRVKUqYadw8wubQ==" Group="0" Origin="CMB" Destination="BOM" />
        </air:Route>
    </air:RouteList>
    <air:AirPricePointList>
        <air:AirPricePoint Key="pOENU9QkTICkJdqkJ9VlMw==" TotalPrice="SGD289.00" BasePrice="LKR17000" ApproximateTotalPrice="USD206.89" ApproximateBasePrice="USD121.70" EquivalentBasePrice="SGD170.00" Taxes="SGD119.00" ApproximateTaxes="USD85.19" CompleteItinerary="true">
            <air:AirPricingInfo Key="+5EcYv7LTTm9/tftK/I4Tg==" TotalPrice="SGD289.00" BasePrice="LKR17000" ApproximateTotalPrice="USD206.89" ApproximateBasePrice="USD121.70" EquivalentBasePrice="SGD170.00" Taxes="SGD119.00" LatestTicketingTime="2016-01-10T23:59:00.000+00:00" PricingMethod="Unknown" Refundable="true" ETicketability="Yes" PlatingCarrier="AI" ProviderCode="1P">
                <air:FareInfoRef Key="1spaEzfCQ6u8Gji2V32UoA==" />
                <air:TaxInfo Category="EJ" Amount="SGD84.00" />
                <air:TaxInfo Category="LK" Amount="SGD35.00" />
                <air:FareCalc>CMB AI X/MAA AI BOM122.92NUC122.92END ROE138.30</air:FareCalc>
                <air:PassengerType Code="ADT" Age="40" />
                <air:ChangePenalty>
                    <air:Amount>SGD21.00</air:Amount>
                </air:ChangePenalty>
                <air:CancelPenalty>
                    <air:Amount>SGD29.00</air:Amount>
                </air:CancelPenalty>
                <air:FlightOptionsList>
                    <air:FlightOption LegRef="AGeIxHoCRVKUqYadw8wubQ==" Destination="BOM" Origin="CMB">
                        <air:Option Key="GXl6oJ0ITvKCP601CnvfbQ==" TravelTime="P0DT6H15M0S">
                            <air:BookingInfo BookingCode="L" CabinClass="Economy" FareInfoRef="1spaEzfCQ6u8Gji2V32UoA==" SegmentRef="qEqLJK1LRAGnhEwqpT9j5Q==" />
                            <air:BookingInfo BookingCode="V" CabinClass="Economy" FareInfoRef="1spaEzfCQ6u8Gji2V32UoA==" SegmentRef="aZTP9qwYR9SOncCsMhoaSQ==" />
                            <air:Connection SegmentIndex="0" />
                        </air:Option>
                        <air:Option Key="hU2SBJDbStK2BqQeddw4Uw==" TravelTime="P0DT15H5M0S">
                            <air:BookingInfo BookingCode="L" CabinClass="Economy" FareInfoRef="1spaEzfCQ6u8Gji2V32UoA==" SegmentRef="qEqLJK1LRAGnhEwqpT9j5Q==" />
                            <air:BookingInfo BookingCode="V" CabinClass="Economy" FareInfoRef="1spaEzfCQ6u8Gji2V32UoA==" SegmentRef="K3tPr6oWRcCqgIipKphuxg==" />
                            <air:Connection SegmentIndex="0" />
                        </air:Option>
                    </air:FlightOption>
                </air:FlightOptionsList>
            </air:AirPricingInfo>
        </air:AirPricePoint>
       
       <air:AirPricePoint Key="KoKtDGcIQZSU3nIgRV3sEg==" TotalPrice="SGD341.00" BasePrice="LKR22200" ApproximateTotalPrice="USD244.12" ApproximateBasePrice="USD158.93" EquivalentBasePrice="SGD222.00" Taxes="SGD119.00" ApproximateTaxes="USD85.19" CompleteItinerary="true">
            <air:AirPricingInfo Key="sK0/6fcXRw2jDrwy8vc1fA==" TotalPrice="SGD341.00" BasePrice="LKR22200" ApproximateTotalPrice="USD244.12" ApproximateBasePrice="USD158.93" EquivalentBasePrice="SGD222.00" Taxes="SGD119.00" LatestTicketingTime="2016-01-10T23:59:00.000+00:00" PricingMethod="Unknown" Refundable="true" ETicketability="Yes" PlatingCarrier="9W" ProviderCode="1P">
                <air:FareInfoRef Key="pGAa6IujRsiUpF5xDl7qQw==" />
                <air:TaxInfo Category="EJ" Amount="SGD84.00" />
                <air:TaxInfo Category="LK" Amount="SGD35.00" />
                <air:FareCalc>CMB 9W BOM Q20.00 140.27NUC160.27END ROE138.30</air:FareCalc>
                <air:PassengerType Code="ADT" Age="40" />
                <air:ChangePenalty>
                    <air:Amount>SGD19.00</air:Amount>
                </air:ChangePenalty>
                <air:CancelPenalty>
                    <air:Amount>SGD29.00</air:Amount>
                </air:CancelPenalty>
                <air:FlightOptionsList>
                    <air:FlightOption LegRef="AGeIxHoCRVKUqYadw8wubQ==" Destination="BOM" Origin="CMB">
                        <air:Option Key="EA8FAl8BRPSIf09H2ndNlA==" TravelTime="P0DT2H35M0S">
                            <air:BookingInfo BookingCode="K" CabinClass="Economy" FareInfoRef="pGAa6IujRsiUpF5xDl7qQw==" SegmentRef="cK3iLlrFTbu5CkLz3ZvIXg==" />
                        </air:Option>
                    </air:FlightOption>
                </air:FlightOptionsList>
            </air:AirPricingInfo>
        </air:AirPricePoint>
     
        
  
    </air:AirPricePointList>
    
</air:LowFareSearchRsp>
</SOAP:Body>
</SOAP:Envelope>';

        $response1_xml = simplexml_load_string($response1);
        $response1_xml->registerXPathNamespace('soap', "http://schemas.xmlsoap.org/soap/envelope/");
        $response1_xml->registerXPathNamespace('air', "http://www.travelport.com/schema/air_v33_0");


        $xmlobject_result = $response1_xml->xpath('/soap:Envelope/soap:Body/air:LowFareSearchRsp/air:AirPricePointList/air:AirPricePoint/air:AirPricingInfo');

        foreach ($xmlobject_result as $key => $value) {

            $result = get_object_vars($value);
            $totalPriceArray[$key] = $result["@attributes"];
        }

        // var_dump($totalPriceArray);
        $this->passData_to_interface($totalPriceArray);

        //  echo json_encode($totalPriceArray);
    }

    public function passData_to_interface($totalPriceArray) {


        $priceArray = array();
        foreach ($totalPriceArray as $key => $value) {
            //   echo $key;

            $priceArray[$key]["Key"] = $value["Key"];
            $priceArray[$key]["TotalPrice"] = $value["TotalPrice"];
            $priceArray[$key]["PlatingCarrier"] = $value["PlatingCarrier"];
        }

        $newArray = array();
        $newArray["origin"] = "CMB";
        $newArray["destination"] = "BOM";
        $newArray["prices"] = $priceArray;
        //var_dump($newArray);
        $this->load->view('bookingView1', $newArray);
        //echo json_encode($newArray);
    }

    public function getOption_details() {


        $response1 = '<SOAP:Envelope xmlns:SOAP="http://schemas.xmlsoap.org/soap/envelope/">
  <SOAP:Body>
    <air:LowFareSearchRsp xmlns:air="http://www.travelport.com/schema/air_v33_0" TraceId="b1499870-36c9-4839-9bad-22b8b072f228" TransactionId="00B3629E0A0764784182CE7D423C51AE" ResponseTime="6942" DistanceUnits="MI" CurrencyType="USD">
    <air:FlightDetailsList>
        <air:FlightDetails Key="0dAAjYNZQg2dIOUhIrVVog==" Origin="CMB" Destination="MAA" DepartureTime="2016-01-10T17:05:00.000+05:30" ArrivalTime="2016-01-10T18:45:00.000+05:30" FlightTime="100" TravelTime="375" Equipment="321" DestinationTerminal="4" />
        <air:FlightDetails Key="2o20qZ3LSb6BM0kPIEZmQg==" Origin="MAA" Destination="BOM" DepartureTime="2016-01-10T21:15:00.000+05:30" ArrivalTime="2016-01-10T23:20:00.000+05:30" FlightTime="125" TravelTime="375" Equipment="319" OriginTerminal="01" DestinationTerminal="2" />
        <air:FlightDetails Key="blml8olgS/6Jk5WuPekL4w==" Origin="MAA" Destination="BOM" DepartureTime="2016-01-11T06:20:00.000+05:30" ArrivalTime="2016-01-11T08:10:00.000+05:30" FlightTime="110" TravelTime="905" Equipment="319" OriginTerminal="1" DestinationTerminal="2" />
        <air:FlightDetails Key="n9pCJm+sQd+WNnswEPedew==" Origin="CMB" Destination="BOM" DepartureTime="2016-01-10T06:10:00.000+05:30" ArrivalTime="2016-01-10T08:45:00.000+05:30" FlightTime="155" TravelTime="155" Equipment="737" DestinationTerminal="2" />
        <air:FlightDetails Key="TyWAF1xFRt2mDoMbDUtbOg==" Origin="CMB" Destination="BOM" DepartureTime="2016-01-10T23:45:00.000+05:30" ArrivalTime="2016-01-11T02:10:00.000+05:30" FlightTime="145" TravelTime="145" Equipment="320" DestinationTerminal="2" />
        <air:FlightDetails Key="8LBArPYATnSCcvTnVTEKLA==" Origin="CMB" Destination="DEL" DepartureTime="2016-01-10T08:20:00.000+05:30" ArrivalTime="2016-01-10T12:10:00.000+05:30" FlightTime="230" TravelTime="635" Equipment="321" DestinationTerminal="3" />
        <air:FlightDetails Key="3g/jPbR5T8i8otytgo5LoA==" Origin="DEL" Destination="BOM" DepartureTime="2016-01-10T16:45:00.000+05:30" ArrivalTime="2016-01-10T18:55:00.000+05:30" FlightTime="130" TravelTime="635" Equipment="77W" OriginTerminal="3" DestinationTerminal="2" />
        <air:FlightDetails Key="tqqSxKAcTD+O749h8YkNUg==" Origin="DEL" Destination="BOM" DepartureTime="2016-01-10T17:00:00.000+05:30" ArrivalTime="2016-01-10T19:20:00.000+05:30" FlightTime="140" TravelTime="660" Equipment="321" OriginTerminal="3" DestinationTerminal="2" />
        <air:FlightDetails Key="ykUhtTmgRgqpgJC5PIDMAA==" Origin="MAA" Destination="DEL" DepartureTime="2016-01-10T21:05:00.000+05:30" ArrivalTime="2016-01-10T23:45:00.000+05:30" FlightTime="160" TravelTime="790" Equipment="321" OriginTerminal="4" DestinationTerminal="3" />
        <air:FlightDetails Key="DqhbNFzVRWa1pW29NMmWQg==" Origin="DEL" Destination="BOM" DepartureTime="2016-01-11T04:00:00.000+05:30" ArrivalTime="2016-01-11T06:15:00.000+05:30" FlightTime="135" TravelTime="790" Equipment="788" OriginTerminal="3" DestinationTerminal="2" />
        <air:FlightDetails Key="qO4+gr4zRWyuW8appu44Aw==" Origin="DEL" Destination="BOM" DepartureTime="2016-01-11T07:00:00.000+05:30" ArrivalTime="2016-01-11T09:10:00.000+05:30" FlightTime="130" TravelTime="965" Equipment="321" OriginTerminal="3" DestinationTerminal="2" />
        <air:FlightDetails Key="2C6WbaE2QVSiMjVph2GKRg==" Origin="DEL" Destination="BLR" DepartureTime="2016-01-10T13:30:00.000+05:30" ArrivalTime="2016-01-10T16:15:00.000+05:30" FlightTime="165" TravelTime="665" Equipment="321" OriginTerminal="3" />
        <air:FlightDetails Key="ElVsTPUGQUagurbQ7Fjavg==" Origin="BLR" Destination="BOM" DepartureTime="2016-01-10T17:20:00.000+05:30" ArrivalTime="2016-01-10T19:25:00.000+05:30" FlightTime="125" TravelTime="665" Equipment="319" DestinationTerminal="2" />
        <air:FlightDetails Key="NYROJjt1SgyIxqzXhlyBag==" Origin="MAA" Destination="BLR" DepartureTime="2016-01-11T14:20:00.000+05:30" ArrivalTime="2016-01-11T15:20:00.000+05:30" FlightTime="60" TravelTime="1580" Equipment="319" OriginTerminal="1" />
        <air:FlightDetails Key="aanv6fNxQDWh+bvnI7Cxuw==" Origin="BLR" Destination="BOM" DepartureTime="2016-01-11T17:20:00.000+05:30" ArrivalTime="2016-01-11T19:25:00.000+05:30" FlightTime="125" TravelTime="1580" Equipment="319" DestinationTerminal="2" />
        <air:FlightDetails Key="ozOM2S5zRE6CfFQBou96TQ==" Origin="CMB" Destination="DXB" DepartureTime="2016-01-10T16:20:00.000+05:30" ArrivalTime="2016-01-10T19:55:00.000+04:00" FlightTime="305" TravelTime="710" Equipment="737" DestinationTerminal="2" />
        <air:FlightDetails Key="+e9faYpPSCaUecnJGvd4Aw==" Origin="DXB" Destination="BOM" DepartureTime="2016-01-10T23:35:00.000+04:00" ArrivalTime="2016-01-11T04:10:00.000+05:30" FlightTime="185" TravelTime="710" Equipment="737" OriginTerminal="2" DestinationTerminal="2" />
        <air:FlightDetails Key="Y42HffJkTfqTucF1l2KiCg==" Origin="CMB" Destination="BLR" DepartureTime="2016-01-10T01:15:00.000+05:30" ArrivalTime="2016-01-10T02:35:00.000+05:30" FlightTime="80" TravelTime="370" Equipment="319" />
        <air:FlightDetails Key="Rf43gpudT1Ocmdo92D056A==" Origin="BLR" Destination="BOM" DepartureTime="2016-01-10T05:50:00.000+05:30" ArrivalTime="2016-01-10T07:25:00.000+05:30" FlightTime="95" TravelTime="370" Equipment="738" DestinationTerminal="1B" />
        <air:FlightDetails Key="v1JEqA57SYOYJZZG3238aQ==" Origin="CMB" Destination="MAA" DepartureTime="2016-01-10T07:20:00.000+05:30" ArrivalTime="2016-01-10T08:40:00.000+05:30" FlightTime="80" TravelTime="355" Equipment="332" DestinationTerminal="3" />
        <air:FlightDetails Key="B2PknRGtRk+4hO+6hFp6Fw==" Origin="MAA" Destination="BOM" DepartureTime="2016-01-10T11:25:00.000+05:30" ArrivalTime="2016-01-10T13:15:00.000+05:30" FlightTime="110" TravelTime="355" Equipment="738" OriginTerminal="1" DestinationTerminal="1B" />
        <air:FlightDetails Key="GWOHk12yRHiI7edsq8D7Pw==" Origin="CMB" Destination="MAA" DepartureTime="2016-01-10T18:35:00.000+05:30" ArrivalTime="2016-01-10T19:55:00.000+05:30" FlightTime="80" TravelTime="595" Equipment="320" DestinationTerminal="3" />
        <air:FlightDetails Key="oIYp6KBoQVW7X8UAgp7D9A==" Origin="MAA" Destination="BOM" DepartureTime="2016-01-11T02:35:00.000+05:30" ArrivalTime="2016-01-11T04:30:00.000+05:30" FlightTime="115" TravelTime="595" Equipment="737" OriginTerminal="1" DestinationTerminal="1B" />
        <air:FlightDetails Key="W7AiU30YS66Cu3g5GMvl4Q==" Origin="CMB" Destination="BOM" DepartureTime="2016-01-10T21:45:00.000+05:30" ArrivalTime="2016-01-11T00:25:00.000+05:30" FlightTime="160" TravelTime="160" Equipment="737" DestinationTerminal="2" />
        <air:FlightDetails Key="iAj7T3d3T/mC+l+cGUvSkQ==" Origin="CMB" Destination="MAA" DepartureTime="2016-01-10T13:45:00.000+05:30" ArrivalTime="2016-01-10T15:05:00.000+05:30" FlightTime="80" TravelTime="575" Equipment="320" DestinationTerminal="3" />
        <air:FlightDetails Key="Vls6qSi4TByn5gWxWcdEjw==" Origin="CMB" Destination="COK" DepartureTime="2016-01-10T14:05:00.000+05:30" ArrivalTime="2016-01-10T15:25:00.000+05:30" FlightTime="80" TravelTime="580" Equipment="320" />
        <air:FlightDetails Key="MyzuH80cR6yXoA7DTMrXfg==" Origin="COK" Destination="BOM" DepartureTime="2016-01-10T22:00:00.000+05:30" ArrivalTime="2016-01-10T23:45:00.000+05:30" FlightTime="105" TravelTime="580" Equipment="320" DestinationTerminal="2" />
        <air:FlightDetails Key="O1WZG9rfQh6Z5GR29cjDEA==" Origin="BLR" Destination="BOM" DepartureTime="2016-01-10T06:45:00.000+05:30" ArrivalTime="2016-01-10T08:20:00.000+05:30" FlightTime="95" TravelTime="425" Equipment="319" DestinationTerminal="2" />
        <air:FlightDetails Key="YnaXTsJyQiyh0pUQJgGF9w==" Origin="CMB" Destination="MCT" DepartureTime="2016-01-10T19:05:00.000+05:30" ArrivalTime="2016-01-10T21:55:00.000+04:00" FlightTime="260" TravelTime="580" Equipment="320" />
        <air:FlightDetails Key="JAQeVRncQKyZAIGuxwJrsg==" Origin="MCT" Destination="BOM" DepartureTime="2016-01-11T00:20:00.000+04:00" ArrivalTime="2016-01-11T04:45:00.000+05:30" FlightTime="175" TravelTime="580" Equipment="321" DestinationTerminal="2" />
        <air:FlightDetails Key="pq5poDXrTmKm92wa33QLQQ==" Origin="MCT" Destination="BOM" DepartureTime="2016-01-11T00:25:00.000+04:00" ArrivalTime="2016-01-11T04:25:00.000+05:30" FlightTime="150" TravelTime="560" Equipment="738" DestinationTerminal="2" />
        <air:FlightDetails Key="YOfdAUXKSAuMnUcIdFRZUQ==" Origin="CMB" Destination="KUL" DepartureTime="2016-01-10T12:55:00.000+05:30" ArrivalTime="2016-01-10T19:05:00.000+08:00" FlightTime="220" TravelTime="600" Equipment="738" DestinationTerminal="M" />
        <air:FlightDetails Key="5OHeNUlBQmiqbT/Ya37VCg==" Origin="KUL" Destination="BOM" DepartureTime="2016-01-10T20:30:00.000+08:00" ArrivalTime="2016-01-10T22:55:00.000+05:30" FlightTime="295" TravelTime="600" Equipment="738" OriginTerminal="M" DestinationTerminal="2" />
        <air:FlightDetails Key="POPCdSLSRJKEpeRLoCe0bw==" Origin="CMB" Destination="BKK" DepartureTime="2016-01-10T01:30:00.000+05:30" ArrivalTime="2016-01-10T06:25:00.000+07:00" FlightTime="205" TravelTime="1230" Equipment="777" />
        <air:FlightDetails Key="qchCpgzITOStAd5PGztx3g==" Origin="BKK" Destination="BOM" DepartureTime="2016-01-10T18:55:00.000+07:00" ArrivalTime="2016-01-10T22:00:00.000+05:30" FlightTime="275" TravelTime="1230" Equipment="744" DestinationTerminal="2" />
        <air:FlightDetails Key="k2d+YuMbRWaP8Syg9pZQyg==" Origin="CMB" Destination="AUH" DepartureTime="2016-01-10T21:00:00.000+05:30" ArrivalTime="2016-01-11T00:25:00.000+04:00" FlightTime="295" TravelTime="675" Equipment="320" DestinationTerminal="1" />
        <air:FlightDetails Key="FTm/u+2xQIyQ9hQHUwnybw==" Origin="AUH" Destination="BOM" DepartureTime="2016-01-11T03:10:00.000+04:00" ArrivalTime="2016-01-11T08:15:00.000+05:30" FlightTime="215" TravelTime="675" Equipment="320" OriginTerminal="1" DestinationTerminal="2" />
        <air:FlightDetails Key="RRuv/BCKT+ySfdmpHZ0ebw==" Origin="CMB" Destination="SIN" DepartureTime="2016-01-10T01:10:00.000+05:30" ArrivalTime="2016-01-10T07:40:00.000+08:00" FlightTime="240" TravelTime="1235" Equipment="333" DestinationTerminal="0" />
        <air:FlightDetails Key="LU2oY/tvSlqqK8tO4MAywA==" Origin="SIN" Destination="BOM" DepartureTime="2016-01-10T18:55:00.000+08:00" ArrivalTime="2016-01-10T21:45:00.000+05:30" FlightTime="320" TravelTime="1235" Equipment="388" OriginTerminal="2" DestinationTerminal="2" />
        <air:FlightDetails Key="ZTqx8q2dQwGfadAzHtF9Aw==" Origin="CMB" Destination="SIN" DepartureTime="2016-01-10T01:00:00.000+05:30" ArrivalTime="2016-01-10T07:30:00.000+08:00" FlightTime="240" TravelTime="750" Equipment="320" DestinationTerminal="3" />
        <air:FlightDetails Key="KIiBDbgcQYqVlpSz9w5i1g==" Origin="SIN" Destination="BOM" DepartureTime="2016-01-10T10:30:00.000+08:00" ArrivalTime="2016-01-10T13:30:00.000+05:30" FlightTime="330" TravelTime="750" Equipment="737" OriginTerminal="3" DestinationTerminal="2" />
    </air:FlightDetailsList>
    <air:AirSegmentList>
        <air:AirSegment Key="qEqLJK1LRAGnhEwqpT9j5Q==" Group="0" Carrier="AI" FlightNumber="274" Origin="CMB" Destination="MAA" DepartureTime="2016-01-10T17:05:00.000+05:30" ArrivalTime="2016-01-10T18:45:00.000+05:30" FlightTime="100" Distance="402" ETicketability="Yes" Equipment="321" ChangeOfPlane="false" OptionalServicesIndicator="false">
            <air:AirAvailInfo ProviderCode="1P">
                <air:BookingCodeInfo BookingCounts="C4|D4|J4|Z4|Y9|B4|M4|H4|K4|Q4|V4|W4|G4|L4|U4|T4|S4|E4|N4" />
            </air:AirAvailInfo>
            <air:FlightDetailsRef Key="0dAAjYNZQg2dIOUhIrVVog==" />
        </air:AirSegment>
        <air:AirSegment Key="aZTP9qwYR9SOncCsMhoaSQ==" Group="0" Carrier="AI" FlightNumber="93" Origin="MAA" Destination="BOM" DepartureTime="2016-01-10T21:15:00.000+05:30" ArrivalTime="2016-01-10T23:20:00.000+05:30" FlightTime="125" Distance="644" ETicketability="Yes" Equipment="319" ChangeOfPlane="false" OptionalServicesIndicator="false">
            <air:AirAvailInfo ProviderCode="1P">
                <air:BookingCodeInfo BookingCounts="C4|D4|J4|Z4|Y9|B4|M4|H4|K4|Q4|V4|W4|G4|L4|U4|T4|S4|E4|N4" />
            </air:AirAvailInfo>
            <air:FlightDetailsRef Key="2o20qZ3LSb6BM0kPIEZmQg==" />
        </air:AirSegment>
        <air:AirSegment Key="K3tPr6oWRcCqgIipKphuxg==" Group="0" Carrier="AI" FlightNumber="569" Origin="MAA" Destination="BOM" DepartureTime="2016-01-11T06:20:00.000+05:30" ArrivalTime="2016-01-11T08:10:00.000+05:30" FlightTime="110" Distance="644" ETicketability="Yes" Equipment="319" ChangeOfPlane="false" OptionalServicesIndicator="false">
            <air:AirAvailInfo ProviderCode="1P">
                <air:BookingCodeInfo BookingCounts="C4|D4|J4|Z4|Y9|B4|M4|H4|K4|Q4|V4|W4|G4|L4|U4|T4|S4|E4|N4" />
            </air:AirAvailInfo>
            <air:FlightDetailsRef Key="blml8olgS/6Jk5WuPekL4w==" />
        </air:AirSegment>
        <air:AirSegment Key="cK3iLlrFTbu5CkLz3ZvIXg==" Group="0" Carrier="9W" FlightNumber="255" Origin="CMB" Destination="BOM" DepartureTime="2016-01-10T06:10:00.000+05:30" ArrivalTime="2016-01-10T08:45:00.000+05:30" FlightTime="155" Distance="948" ETicketability="Yes" Equipment="737" ChangeOfPlane="false" OptionalServicesIndicator="false">
            <air:AirAvailInfo ProviderCode="1P">
                <air:BookingCodeInfo BookingCounts="C7|J7|Z7|I7|P7|Y7|M7|T7|U7|N7|L7|Q7|S7|K7|H7|V7|O7|W3|B7" />
            </air:AirAvailInfo>
            <air:FlightDetailsRef Key="n9pCJm+sQd+WNnswEPedew==" />
        </air:AirSegment>
        <air:AirSegment Key="c/o5HezPSH2V5lhAYVyFjQ==" Group="0" Carrier="MJ" FlightNumber="2141" Origin="CMB" Destination="BOM" DepartureTime="2016-01-10T23:45:00.000+05:30" ArrivalTime="2016-01-11T02:10:00.000+05:30" FlightTime="145" Distance="948" ETicketability="Yes" Equipment="320" ChangeOfPlane="false" OptionalServicesIndicator="false">
            <air:CodeshareInfo OperatingCarrier="UL" OperatingFlightNumber="141">SRILANKAN AIRLINES</air:CodeshareInfo>
            <air:AirAvailInfo ProviderCode="1P">
                <air:BookingCodeInfo BookingCounts="J4|C4|D4|I4|Y4|B4|P4|H4|K4|W4|M4|E4|L4|R4|V4|S4|N4|Q4|O4" />
            </air:AirAvailInfo>
            <air:FlightDetailsRef Key="TyWAF1xFRt2mDoMbDUtbOg==" />
        </air:AirSegment>
        <air:AirSegment Key="ZUmSBYBuQfuE+Uh8OZOBOA==" Group="0" Carrier="UL" FlightNumber="141" Origin="CMB" Destination="BOM" DepartureTime="2016-01-10T23:45:00.000+05:30" ArrivalTime="2016-01-11T02:10:00.000+05:30" FlightTime="145" Distance="948" ETicketability="Yes" Equipment="320" ChangeOfPlane="false" OptionalServicesIndicator="false">
            <air:AirAvailInfo ProviderCode="1P">
                <air:BookingCodeInfo BookingCounts="J4|C4|D4|I3|Y7|B7|P7|H7|K7|W7|M7|E7|L7" />
            </air:AirAvailInfo>
            <air:FlightDetailsRef Key="TyWAF1xFRt2mDoMbDUtbOg==" />
        </air:AirSegment>
        <air:AirSegment Key="Wc/L/x4jSkyPLWyRz5SazA==" Group="0" Carrier="AI" FlightNumber="282" Origin="CMB" Destination="DEL" DepartureTime="2016-01-10T08:20:00.000+05:30" ArrivalTime="2016-01-10T12:10:00.000+05:30" FlightTime="230" Distance="1489" ETicketability="Yes" Equipment="321" ChangeOfPlane="false" OptionalServicesIndicator="false">
            <air:AirAvailInfo ProviderCode="1P">
                <air:BookingCodeInfo BookingCounts="C4|D4|J4|Z4|Y9|B4|M4|H4|K4|Q4|V4|W4|G4|L4|U4|T4|S4|E4|N4" />
            </air:AirAvailInfo>
            <air:FlightDetailsRef Key="8LBArPYATnSCcvTnVTEKLA==" />
        </air:AirSegment>
        <air:AirSegment Key="VkXIQBIsTLywtVjewk9X9w==" Group="0" Carrier="AI" FlightNumber="102" Origin="DEL" Destination="BOM" DepartureTime="2016-01-10T16:45:00.000+05:30" ArrivalTime="2016-01-10T18:55:00.000+05:30" FlightTime="130" Distance="708" ETicketability="Yes" Equipment="77W" ChangeOfPlane="false" OptionalServicesIndicator="false">
            <air:AirAvailInfo ProviderCode="1P">
                <air:BookingCodeInfo BookingCounts="F4|A4|C4|D4|J4|Z4|Y9|B4|M4|H4|K4|Q4|V4|W4|G4|L4|U4|T4|S4|E4|N4" />
            </air:AirAvailInfo>
            <air:FlightDetailsRef Key="3g/jPbR5T8i8otytgo5LoA==" />
        </air:AirSegment>
        <air:AirSegment Key="nBlF5r+OQ6iazAGnscYyYg==" Group="0" Carrier="AI" FlightNumber="659" Origin="DEL" Destination="BOM" DepartureTime="2016-01-10T17:00:00.000+05:30" ArrivalTime="2016-01-10T19:20:00.000+05:30" FlightTime="140" Distance="708" ETicketability="Yes" Equipment="321" ChangeOfPlane="false" OptionalServicesIndicator="false">
            <air:AirAvailInfo ProviderCode="1P">
                <air:BookingCodeInfo BookingCounts="C4|D4|J4|Z4|Y9|B4|M4|H4|K4|Q4|V4|W4|G4|L4|U4|T4|S4|E4|N4" />
            </air:AirAvailInfo>
            <air:FlightDetailsRef Key="tqqSxKAcTD+O749h8YkNUg==" />
        </air:AirSegment>
        <air:AirSegment Key="sbtLXlgsTDWsr1ltAbwdlQ==" Group="0" Carrier="AI" FlightNumber="43" Origin="MAA" Destination="DEL" DepartureTime="2016-01-10T21:05:00.000+05:30" ArrivalTime="2016-01-10T23:45:00.000+05:30" FlightTime="160" Distance="1095" ETicketability="Yes" Equipment="321" ChangeOfPlane="false" OptionalServicesIndicator="false">
            <air:AirAvailInfo ProviderCode="1P">
                <air:BookingCodeInfo BookingCounts="C4|D4|J4|Z4|Y9|B4|M4|H4|K4|Q4|V4|W4|G4|L4|U4|T4|S4|E4|N4" />
            </air:AirAvailInfo>
            <air:FlightDetailsRef Key="ykUhtTmgRgqpgJC5PIDMAA==" />
        </air:AirSegment>
        <air:AirSegment Key="8BN/J8H3SQa15oQD1YDUlA==" Group="0" Carrier="AI" FlightNumber="349" Origin="DEL" Destination="BOM" DepartureTime="2016-01-11T04:00:00.000+05:30" ArrivalTime="2016-01-11T06:15:00.000+05:30" FlightTime="135" Distance="708" ETicketability="Yes" Equipment="788" ChangeOfPlane="false" OptionalServicesIndicator="false">
            <air:AirAvailInfo ProviderCode="1P">
                <air:BookingCodeInfo BookingCounts="C4|D4|J4|Z4|Y9|B4|M4|H4|K4|Q4|V4|W4|G4|L4|U4|T4|S4|E4|N4" />
            </air:AirAvailInfo>
            <air:FlightDetailsRef Key="DqhbNFzVRWa1pW29NMmWQg==" />
        </air:AirSegment>
        <air:AirSegment Key="RLO2cT1KR0eO2ZIoWzXC7w==" Group="0" Carrier="AI" FlightNumber="657" Origin="DEL" Destination="BOM" DepartureTime="2016-01-11T07:00:00.000+05:30" ArrivalTime="2016-01-11T09:10:00.000+05:30" FlightTime="130" Distance="708" ETicketability="Yes" Equipment="321" ChangeOfPlane="false" OptionalServicesIndicator="false">
            <air:AirAvailInfo ProviderCode="1P">
                <air:BookingCodeInfo BookingCounts="C4|D4|J4|Z4|Y9|B4|M4|H4|K4|Q4|V4|W4|G4|L4|U4|T4|S4|E4|N4" />
            </air:AirAvailInfo>
            <air:FlightDetailsRef Key="qO4+gr4zRWyuW8appu44Aw==" />
        </air:AirSegment>
        <air:AirSegment Key="KVe23y4NQXaDS4C6Kdy6rA==" Group="0" Carrier="AI" FlightNumber="502" Origin="DEL" Destination="BLR" DepartureTime="2016-01-10T13:30:00.000+05:30" ArrivalTime="2016-01-10T16:15:00.000+05:30" FlightTime="165" Distance="1063" ETicketability="Yes" Equipment="321" ChangeOfPlane="false" OptionalServicesIndicator="false">
            <air:AirAvailInfo ProviderCode="1P">
                <air:BookingCodeInfo BookingCounts="C4|D4|J4|Z4|Y9|B4|M4|H4|K4|Q4|V4|W4|G4|L4|U4|T4|S4|E4|N4" />
            </air:AirAvailInfo>
            <air:FlightDetailsRef Key="2C6WbaE2QVSiMjVph2GKRg==" />
        </air:AirSegment>
        <air:AirSegment Key="Unm9TtHNTaa/LwKzJaWn7Q==" Group="0" Carrier="AI" FlightNumber="608" Origin="BLR" Destination="BOM" DepartureTime="2016-01-10T17:20:00.000+05:30" ArrivalTime="2016-01-10T19:25:00.000+05:30" FlightTime="125" Distance="519" ETicketability="Yes" Equipment="319" ChangeOfPlane="false" OptionalServicesIndicator="false">
            <air:AirAvailInfo ProviderCode="1P">
                <air:BookingCodeInfo BookingCounts="C4|D4|J4|Z4|Y9|B4|M4|H4|K4|Q4|V4|W4|G4|L4|U4|T4|S4|E4|N4" />
            </air:AirAvailInfo>
            <air:FlightDetailsRef Key="ElVsTPUGQUagurbQ7Fjavg==" />
        </air:AirSegment>
        <air:AirSegment Key="kj4kRMUESFeN9ECJnweSQg==" Group="0" Carrier="AI" FlightNumber="563" Origin="MAA" Destination="BLR" DepartureTime="2016-01-11T14:20:00.000+05:30" ArrivalTime="2016-01-11T15:20:00.000+05:30" FlightTime="60" Distance="168" ETicketability="Yes" Equipment="319" ChangeOfPlane="false" OptionalServicesIndicator="false">
            <air:AirAvailInfo ProviderCode="1P">
                <air:BookingCodeInfo BookingCounts="C4|D4|J4|Z4|Y9|B4|M4|H4|K4|Q4|V4|W4|G4|L4|U4|T4|S4|E4|N4" />
            </air:AirAvailInfo>
            <air:FlightDetailsRef Key="NYROJjt1SgyIxqzXhlyBag==" />
        </air:AirSegment>
        <air:AirSegment Key="1djiAXJLR4609QNW2KSOfQ==" Group="0" Carrier="AI" FlightNumber="608" Origin="BLR" Destination="BOM" DepartureTime="2016-01-11T17:20:00.000+05:30" ArrivalTime="2016-01-11T19:25:00.000+05:30" FlightTime="125" Distance="519" ETicketability="Yes" Equipment="319" ChangeOfPlane="false" OptionalServicesIndicator="false">
            <air:AirAvailInfo ProviderCode="1P">
                <air:BookingCodeInfo BookingCounts="C4|D4|J4|Z4|Y9|B4|M4|H4|K4|Q4|V4|W4|G4|L4|U4|T4|S4|E4|N4" />
            </air:AirAvailInfo>
            <air:FlightDetailsRef Key="aanv6fNxQDWh+bvnI7Cxuw==" />
        </air:AirSegment>
        <air:AirSegment Key="r79SJ6lwRVWGzwX/bWBfAg==" Group="0" Carrier="FZ" FlightNumber="554" Origin="CMB" Destination="DXB" DepartureTime="2016-01-10T16:20:00.000+05:30" ArrivalTime="2016-01-10T19:55:00.000+04:00" FlightTime="305" Distance="2043" ETicketability="Yes" Equipment="737" ChangeOfPlane="false" OptionalServicesIndicator="false">
            <air:AirAvailInfo ProviderCode="1P">
                <air:BookingCodeInfo BookingCounts="Z4|J4|Y4|A4|I4|E4|W4|T4" />
            </air:AirAvailInfo>
            <air:FlightDetailsRef Key="ozOM2S5zRE6CfFQBou96TQ==" />
        </air:AirSegment>
        <air:AirSegment Key="ojS+Dtb3Rey7sV32K0cYBQ==" Group="0" Carrier="FZ" FlightNumber="445" Origin="DXB" Destination="BOM" DepartureTime="2016-01-10T23:35:00.000+04:00" ArrivalTime="2016-01-11T04:10:00.000+05:30" FlightTime="185" Distance="1201" ETicketability="Yes" Equipment="737" ChangeOfPlane="false" OptionalServicesIndicator="false">
            <air:AirAvailInfo ProviderCode="1P">
                <air:BookingCodeInfo BookingCounts="Z4|J4|Y4|A4|I4|E4|W4|T4|M4|L4" />
            </air:AirAvailInfo>
            <air:FlightDetailsRef Key="+e9faYpPSCaUecnJGvd4Aw==" />
        </air:AirSegment>
        <air:AirSegment Key="QZKyfzWgR4ScjarSP+Cx6w==" Group="0" Carrier="UL" FlightNumber="173" Origin="CMB" Destination="BLR" DepartureTime="2016-01-10T01:15:00.000+05:30" ArrivalTime="2016-01-10T02:35:00.000+05:30" FlightTime="80" Distance="442" ETicketability="Yes" Equipment="319" ChangeOfPlane="false" OptionalServicesIndicator="false">
            <air:CodeshareInfo>UL USING MIHIN ACFT</air:CodeshareInfo>
            <air:AirAvailInfo ProviderCode="1P">
                <air:BookingCodeInfo BookingCounts="J4|C4|D4|I4|Y7|B7|P4|H7|K7|W4|M7|E4|L7|R4|V7|S4|N7|Q4|O4" />
            </air:AirAvailInfo>
            <air:FlightDetailsRef Key="Y42HffJkTfqTucF1l2KiCg==" />
        </air:AirSegment>
        <air:AirSegment Key="iLH+vTAsReqPFgeXHm8/uw==" Group="0" Carrier="9W" FlightNumber="410" Origin="BLR" Destination="BOM" DepartureTime="2016-01-10T05:50:00.000+05:30" ArrivalTime="2016-01-10T07:25:00.000+05:30" FlightTime="95" Distance="519" ETicketability="Yes" Equipment="738" ChangeOfPlane="false" OptionalServicesIndicator="false">
            <air:AirAvailInfo ProviderCode="1P">
                <air:BookingCodeInfo BookingCounts="C7|J7|Z7|I7|P7|Y7|M7|T7|U7|N7|L7|Q7|S7|K7|H7|V7|O7|W7|B7" />
            </air:AirAvailInfo>
            <air:FlightDetailsRef Key="Rf43gpudT1Ocmdo92D056A==" />
        </air:AirSegment>
        <air:AirSegment Key="QXjtZ2YYRgWYtNxm49SKgg==" Group="0" Carrier="UL" FlightNumber="121" Origin="CMB" Destination="MAA" DepartureTime="2016-01-10T07:20:00.000+05:30" ArrivalTime="2016-01-10T08:40:00.000+05:30" FlightTime="80" Distance="402" ETicketability="Yes" Equipment="332" ChangeOfPlane="false" OptionalServicesIndicator="false">
            <air:AirAvailInfo ProviderCode="1P">
                <air:BookingCodeInfo BookingCounts="J4|C4|D4|I4|Y7|B7|P4|H7|K7|W4|M7|E4|L7|R4|V7|S4|N7|Q4|O4" />
            </air:AirAvailInfo>
            <air:FlightDetailsRef Key="v1JEqA57SYOYJZZG3238aQ==" />
        </air:AirSegment>
        <air:AirSegment Key="CAi3hq9gS4OhxRMfL9nqzg==" Group="0" Carrier="9W" FlightNumber="460" Origin="MAA" Destination="BOM" DepartureTime="2016-01-10T11:25:00.000+05:30" ArrivalTime="2016-01-10T13:15:00.000+05:30" FlightTime="110" Distance="644" ETicketability="Yes" Equipment="738" ChangeOfPlane="false" OptionalServicesIndicator="false">
            <air:AirAvailInfo ProviderCode="1P">
                <air:BookingCodeInfo BookingCounts="C7|J7|Z7|I7|P7|Y7|M7|T7|U7|N7|L7|Q7|S7|K7|B7" />
            </air:AirAvailInfo>
            <air:FlightDetailsRef Key="B2PknRGtRk+4hO+6hFp6Fw==" />
        </air:AirSegment>
        <air:AirSegment Key="eNGo4XRzQNOFhPH/PbLV4A==" Group="0" Carrier="UL" FlightNumber="123" Origin="CMB" Destination="MAA" DepartureTime="2016-01-10T18:35:00.000+05:30" ArrivalTime="2016-01-10T19:55:00.000+05:30" FlightTime="80" Distance="402" ETicketability="Yes" Equipment="320" ChangeOfPlane="false" OptionalServicesIndicator="false">
            <air:AirAvailInfo ProviderCode="1P">
                <air:BookingCodeInfo BookingCounts="J4|C4|D4|I4|Y7|B7|P4|H7|K7|W4|M7|E4|L7|R4|V7|S4|N7|Q4|O4" />
            </air:AirAvailInfo>
            <air:FlightDetailsRef Key="GWOHk12yRHiI7edsq8D7Pw==" />
        </air:AirSegment>
        <air:AirSegment Key="6qPlNBaIT/aTnnmAlz+saQ==" Group="0" Carrier="9W" FlightNumber="7012" Origin="MAA" Destination="BOM" DepartureTime="2016-01-11T02:35:00.000+05:30" ArrivalTime="2016-01-11T04:30:00.000+05:30" FlightTime="115" Distance="644" ETicketability="Yes" Equipment="737" ChangeOfPlane="false" OptionalServicesIndicator="false">
            <air:CodeshareInfo>JETKONNECT</air:CodeshareInfo>
            <air:AirAvailInfo ProviderCode="1P">
                <air:BookingCodeInfo BookingCounts="C7|J7|Z7|I7|P7|Y7|M7|T7|U7|N7|L7|Q7|S7|K7|H7|V7" />
            </air:AirAvailInfo>
            <air:FlightDetailsRef Key="oIYp6KBoQVW7X8UAgp7D9A==" />
        </air:AirSegment>
        <air:AirSegment Key="A4YNT3OLSEakv9MoxpZxag==" Group="0" Carrier="9W" FlightNumber="251" Origin="CMB" Destination="BOM" DepartureTime="2016-01-10T21:45:00.000+05:30" ArrivalTime="2016-01-11T00:25:00.000+05:30" FlightTime="160" Distance="948" ETicketability="Yes" Equipment="737" ChangeOfPlane="false" OptionalServicesIndicator="false">
            <air:AirAvailInfo ProviderCode="1P">
                <air:BookingCodeInfo BookingCounts="C3|J2|Z1|Y7|B7" />
            </air:AirAvailInfo>
            <air:FlightDetailsRef Key="W7AiU30YS66Cu3g5GMvl4Q==" />
        </air:AirSegment>
        <air:AirSegment Key="hiuMsaq9S2yMdmDq1DB95w==" Group="0" Carrier="UL" FlightNumber="127" Origin="CMB" Destination="MAA" DepartureTime="2016-01-10T13:45:00.000+05:30" ArrivalTime="2016-01-10T15:05:00.000+05:30" FlightTime="80" Distance="402" ETicketability="Yes" Equipment="320" ChangeOfPlane="false" OptionalServicesIndicator="false">
            <air:AirAvailInfo ProviderCode="1P">
                <air:BookingCodeInfo BookingCounts="J4|C4|D4|I4|Y7|B7|P4|H7|K7|W4|M7|E4|L7|R4|V7|S4|N7|Q4|O4" />
            </air:AirAvailInfo>
            <air:FlightDetailsRef Key="iAj7T3d3T/mC+l+cGUvSkQ==" />
        </air:AirSegment>
        <air:AirSegment Key="udzuWdPxTtyfnK9bOPEVYA==" Group="0" Carrier="UL" FlightNumber="167" Origin="CMB" Destination="COK" DepartureTime="2016-01-10T14:05:00.000+05:30" ArrivalTime="2016-01-10T15:25:00.000+05:30" FlightTime="80" Distance="312" ETicketability="Yes" Equipment="320" ChangeOfPlane="false" OptionalServicesIndicator="false">
            <air:AirAvailInfo ProviderCode="1P">
                <air:BookingCodeInfo BookingCounts="J4|C4|D4|I4|Y7|B7|P4|H7|K7|W4|M7|E4|L7|R4|V7|S4|N7|Q4|O4" />
            </air:AirAvailInfo>
            <air:FlightDetailsRef Key="Vls6qSi4TByn5gWxWcdEjw==" />
        </air:AirSegment>
        <air:AirSegment Key="DZ0wJ1+bSjKwAJ71fjCaQw==" Group="0" Carrier="AI" FlightNumber="55" Origin="COK" Destination="BOM" DepartureTime="2016-01-10T22:00:00.000+05:30" ArrivalTime="2016-01-10T23:45:00.000+05:30" FlightTime="105" Distance="672" ETicketability="Yes" Equipment="320" ChangeOfPlane="false" OptionalServicesIndicator="false">
            <air:AirAvailInfo ProviderCode="1P">
                <air:BookingCodeInfo BookingCounts="Y9|B4|M4|H4|K4|Q4|V4|W4|G4|L4|U4|T4|S4|E4|N4" />
            </air:AirAvailInfo>
            <air:FlightDetailsRef Key="MyzuH80cR6yXoA7DTMrXfg==" />
        </air:AirSegment>
        <air:AirSegment Key="E1uHScknSWaYlB++/CRkxg==" Group="0" Carrier="AI" FlightNumber="640" Origin="BLR" Destination="BOM" DepartureTime="2016-01-10T06:45:00.000+05:30" ArrivalTime="2016-01-10T08:20:00.000+05:30" FlightTime="95" Distance="519" ETicketability="Yes" Equipment="319" ChangeOfPlane="false" OptionalServicesIndicator="false">
            <air:AirAvailInfo ProviderCode="1P">
                <air:BookingCodeInfo BookingCounts="C4|D4|J4|Z4|Y9|B4|M4|H4|K4|Q4|V4|W4|G4|L4|U4|T4|S4|E4|N4" />
            </air:AirAvailInfo>
            <air:FlightDetailsRef Key="O1WZG9rfQh6Z5GR29cjDEA==" />
        </air:AirSegment>
        <air:AirSegment Key="wOKHGa45QSO1k1SsLFaNxA==" Group="0" Carrier="UL" FlightNumber="2925" Origin="CMB" Destination="MCT" DepartureTime="2016-01-10T19:05:00.000+05:30" ArrivalTime="2016-01-10T21:55:00.000+04:00" FlightTime="260" Distance="1816" ETicketability="Yes" Equipment="320" ChangeOfPlane="false" OptionalServicesIndicator="false">
            <air:CodeshareInfo OperatingCarrier="MJ" OperatingFlightNumber="205">MIHIN LANKA</air:CodeshareInfo>
            <air:AirAvailInfo ProviderCode="1P">
                <air:BookingCodeInfo BookingCounts="J4|C4|D4|I4|Y7|B7|P4|H7|K7|W4|M7|E4|L7|R4|V7|S4|N7|Q4|O4" />
            </air:AirAvailInfo>
            <air:FlightDetailsRef Key="YnaXTsJyQiyh0pUQJgGF9w==" />
        </air:AirSegment>
        <air:AirSegment Key="HbwA0eNpSKSL/NK0uC6Btg==" Group="0" Carrier="AI" FlightNumber="986" Origin="MCT" Destination="BOM" DepartureTime="2016-01-11T00:20:00.000+04:00" ArrivalTime="2016-01-11T04:45:00.000+05:30" FlightTime="175" Distance="974" ETicketability="Yes" Equipment="321" ChangeOfPlane="false" OptionalServicesIndicator="false">
            <air:AirAvailInfo ProviderCode="1P">
                <air:BookingCodeInfo BookingCounts="C4|D4|J4|Z4|Y9|B4|M4|H4|K4|Q4|V4|W4|G4|L4|U4|T4|S4|E4|N4" />
            </air:AirAvailInfo>
            <air:FlightDetailsRef Key="JAQeVRncQKyZAIGuxwJrsg==" />
        </air:AirSegment>
        <air:AirSegment Key="coVQAUPmR3+iFdpAUIpMpw==" Group="0" Carrier="9W" FlightNumber="539" Origin="MCT" Destination="BOM" DepartureTime="2016-01-11T00:25:00.000+04:00" ArrivalTime="2016-01-11T04:25:00.000+05:30" FlightTime="150" Distance="974" ETicketability="Yes" Equipment="738" ChangeOfPlane="false" OptionalServicesIndicator="false">
            <air:AirAvailInfo ProviderCode="1P">
                <air:BookingCodeInfo BookingCounts="C7|J7|Z7|I6|P5|Y7|M7|T7|U7|N7|L7|Q7|S7|K7|H7|V7|O7|W7|B7" />
            </air:AirAvailInfo>
            <air:FlightDetailsRef Key="pq5poDXrTmKm92wa33QLQQ==" />
        </air:AirSegment>
        <air:AirSegment Key="TDMQdRLDTByu/J0ixzyU6g==" Group="0" Carrier="MH" FlightNumber="184" Origin="CMB" Destination="KUL" DepartureTime="2016-01-10T12:55:00.000+05:30" ArrivalTime="2016-01-10T19:05:00.000+08:00" FlightTime="220" Distance="1526" ETicketability="Yes" Equipment="738" ChangeOfPlane="false" OptionalServicesIndicator="false">
            <air:AirAvailInfo ProviderCode="1P">
                <air:BookingCodeInfo BookingCounts="J4|C4|D4|Z4|I4|Y9|B9|H9|K9|M9|L9|V9|S9|N9|Q9|O9|G9" />
            </air:AirAvailInfo>
            <air:FlightDetailsRef Key="YOfdAUXKSAuMnUcIdFRZUQ==" />
        </air:AirSegment>
        <air:AirSegment Key="nenp5FUHRSOcOrtF3eQY8Q==" Group="0" Carrier="MH" FlightNumber="194" Origin="KUL" Destination="BOM" DepartureTime="2016-01-10T20:30:00.000+08:00" ArrivalTime="2016-01-10T22:55:00.000+05:30" FlightTime="295" Distance="2241" ETicketability="Yes" Equipment="738" ChangeOfPlane="false" OptionalServicesIndicator="false">
            <air:AirAvailInfo ProviderCode="1P">
                <air:BookingCodeInfo BookingCounts="J4|C4|D4|Z4|I4|Y9|B9|H9|K9|M9|L9|V9|S9|N9|Q9|O9|G9" />
            </air:AirAvailInfo>
            <air:FlightDetailsRef Key="5OHeNUlBQmiqbT/Ya37VCg==" />
        </air:AirSegment>
        <air:AirSegment Key="yuFnXJ8MSoaBigLqycUt+g==" Group="0" Carrier="TG" FlightNumber="308" Origin="CMB" Destination="BKK" DepartureTime="2016-01-10T01:30:00.000+05:30" ArrivalTime="2016-01-10T06:25:00.000+07:00" FlightTime="205" Distance="1485" ETicketability="Yes" Equipment="777" ChangeOfPlane="false" OptionalServicesIndicator="false">
            <air:AirAvailInfo ProviderCode="1P">
                <air:BookingCodeInfo BookingCounts="C9|D9|J9|Z9|I9|R9|Y9|B9|M9|H9|Q9|T9|K9|S9|X9|V9|W9" />
            </air:AirAvailInfo>
            <air:FlightDetailsRef Key="POPCdSLSRJKEpeRLoCe0bw==" />
        </air:AirSegment>
        <air:AirSegment Key="+ZT4DTDoQQu7I/2fn7yN5w==" Group="0" Carrier="TG" FlightNumber="317" Origin="BKK" Destination="BOM" DepartureTime="2016-01-10T18:55:00.000+07:00" ArrivalTime="2016-01-10T22:00:00.000+05:30" FlightTime="275" Distance="1878" ETicketability="Yes" Equipment="744" ChangeOfPlane="false" OptionalServicesIndicator="false">
            <air:AirAvailInfo ProviderCode="1P">
                <air:BookingCodeInfo BookingCounts="C9|D7|Y9|B9|M9|H9|Q9|T9|K9|S9|X9|V9|W9" />
            </air:AirAvailInfo>
            <air:FlightDetailsRef Key="qchCpgzITOStAd5PGztx3g==" />
        </air:AirSegment>
        <air:AirSegment Key="9R3hMegXSHeNFc0r0kYvVw==" Group="0" Carrier="EY" FlightNumber="265" Origin="CMB" Destination="AUH" DepartureTime="2016-01-10T21:00:00.000+05:30" ArrivalTime="2016-01-11T00:25:00.000+04:00" FlightTime="295" Distance="2061" ETicketability="Yes" Equipment="320" ChangeOfPlane="false" OptionalServicesIndicator="false">
            <air:AirAvailInfo ProviderCode="1P">
                <air:BookingCodeInfo BookingCounts="J7|C6|D6|W5|Y7|B7|H7" />
            </air:AirAvailInfo>
            <air:FlightDetailsRef Key="k2d+YuMbRWaP8Syg9pZQyg==" />
        </air:AirSegment>
        <air:AirSegment Key="oulYHHIuR3G8t/OLW7fU3g==" Group="0" Carrier="EY" FlightNumber="212" Origin="AUH" Destination="BOM" DepartureTime="2016-01-11T03:10:00.000+04:00" ArrivalTime="2016-01-11T08:15:00.000+05:30" FlightTime="215" Distance="1237" ETicketability="Yes" Equipment="320" ChangeOfPlane="false" OptionalServicesIndicator="false">
            <air:AirAvailInfo ProviderCode="1P">
                <air:BookingCodeInfo BookingCounts="J7|C6|D6|W5|Y7|B7|H7" />
            </air:AirAvailInfo>
            <air:FlightDetailsRef Key="FTm/u+2xQIyQ9hQHUwnybw==" />
        </air:AirSegment>
        <air:AirSegment Key="6MVL9G8MT1eHNQsLr++n3g==" Group="0" Carrier="HM" FlightNumber="5523" Origin="CMB" Destination="AUH" DepartureTime="2016-01-10T21:00:00.000+05:30" ArrivalTime="2016-01-11T00:25:00.000+04:00" FlightTime="295" Distance="2061" ETicketability="Yes" Equipment="320" ChangeOfPlane="false" OptionalServicesIndicator="false">
            <air:CodeshareInfo OperatingCarrier="EY" OperatingFlightNumber="265">ETIHAD AIRWAYS</air:CodeshareInfo>
            <air:AirAvailInfo ProviderCode="1P">
                <air:BookingCodeInfo BookingCounts="J4|D4|C4|I4|Y9|B9|H9|K9|L9|W9|S9|M9|N9|V9|G9" />
            </air:AirAvailInfo>
            <air:FlightDetailsRef Key="k2d+YuMbRWaP8Syg9pZQyg==" />
        </air:AirSegment>
        <air:AirSegment Key="SIeg1x73Sw2gmoXzaOghoQ==" Group="0" Carrier="HM" FlightNumber="5320" Origin="AUH" Destination="BOM" DepartureTime="2016-01-11T03:10:00.000+04:00" ArrivalTime="2016-01-11T08:15:00.000+05:30" FlightTime="215" Distance="1237" ETicketability="Yes" Equipment="320" ChangeOfPlane="false" OptionalServicesIndicator="false">
            <air:CodeshareInfo OperatingCarrier="EY" OperatingFlightNumber="212">ETIHAD AIRWAYS</air:CodeshareInfo>
            <air:AirAvailInfo ProviderCode="1P">
                <air:BookingCodeInfo BookingCounts="J4|D4|C4|I4|Y9|B9|H9|K9|L9|W9|S9|M9|N9|V9|G9" />
            </air:AirAvailInfo>
            <air:FlightDetailsRef Key="FTm/u+2xQIyQ9hQHUwnybw==" />
        </air:AirSegment>
        <air:AirSegment Key="TIspzxT7QUKgsnoej2t8Ew==" Group="0" Carrier="SQ" FlightNumber="469" Origin="CMB" Destination="SIN" DepartureTime="2016-01-10T01:10:00.000+05:30" ArrivalTime="2016-01-10T07:40:00.000+08:00" FlightTime="240" Distance="1709" ETicketability="Yes" Equipment="333" ChangeOfPlane="false" OptionalServicesIndicator="false">
            <air:AirAvailInfo ProviderCode="1P">
                <air:BookingCodeInfo BookingCounts="Z9|C9|J9|U9|D9|Y9|B9|E9|M9|H9|Q9|N9" />
            </air:AirAvailInfo>
            <air:FlightDetailsRef Key="RRuv/BCKT+ySfdmpHZ0ebw==" />
        </air:AirSegment>
        <air:AirSegment Key="UFfsRUqWR/uJXKLJ4tKsMQ==" Group="0" Carrier="SQ" FlightNumber="424" Origin="SIN" Destination="BOM" DepartureTime="2016-01-10T18:55:00.000+08:00" ArrivalTime="2016-01-10T21:45:00.000+05:30" FlightTime="320" Distance="2437" ETicketability="Yes" Equipment="388" ChangeOfPlane="false" OptionalServicesIndicator="false">
            <air:AirAvailInfo ProviderCode="1P">
                <air:BookingCodeInfo BookingCounts="R9|F9|A8|Z9|C9|J6|S9|T9|P9|Y9|B9|E9|M9|H9|W9|Q9|N9|L9" />
            </air:AirAvailInfo>
            <air:FlightDetailsRef Key="LU2oY/tvSlqqK8tO4MAywA==" />
        </air:AirSegment>
        <air:AirSegment Key="PHdojzyGSTqpgr0CLgQf+A==" Group="0" Carrier="QF" FlightNumber="3420" Origin="CMB" Destination="SIN" DepartureTime="2016-01-10T01:00:00.000+05:30" ArrivalTime="2016-01-10T07:30:00.000+08:00" FlightTime="240" Distance="1709" ETicketability="Yes" Equipment="320" ChangeOfPlane="false" OptionalServicesIndicator="false">
            <air:CodeshareInfo OperatingCarrier="UL" OperatingFlightNumber="306">SRILANKAN AIRLINES</air:CodeshareInfo>
            <air:AirAvailInfo ProviderCode="1P">
                <air:BookingCodeInfo BookingCounts="J9|C8|D8|I6|Y9|B9|H9|K9|M9|L9|V9|S9" />
            </air:AirAvailInfo>
            <air:FlightDetailsRef Key="ZTqx8q2dQwGfadAzHtF9Aw==" />
        </air:AirSegment>
        <air:AirSegment Key="cDtsLDA8RNOB89qNWL6m5Q==" Group="0" Carrier="QF" FlightNumber="3957" Origin="SIN" Destination="BOM" DepartureTime="2016-01-10T10:30:00.000+08:00" ArrivalTime="2016-01-10T13:30:00.000+05:30" FlightTime="330" Distance="2437" ETicketability="Yes" Equipment="737" ChangeOfPlane="false" OptionalServicesIndicator="false">
            <air:CodeshareInfo OperatingCarrier="9W" OperatingFlightNumber="9">JET*AIRWAYS*INDIA*LTD</air:CodeshareInfo>
            <air:AirAvailInfo ProviderCode="1P">
                <air:BookingCodeInfo BookingCounts="J9|C9|D9|I9|Y9|B9|H9|K9" />
            </air:AirAvailInfo>
            <air:FlightDetailsRef Key="KIiBDbgcQYqVlpSz9w5i1g==" />
        </air:AirSegment>
    </air:AirSegmentList>
    <air:FareInfoList>
        <air:FareInfo Key="1spaEzfCQ6u8Gji2V32UoA==" FareBasis="LOW" PassengerTypeCode="ADT" Origin="CMB" Destination="BOM" EffectiveDate="2016-01-02T04:58:00.000+00:00" DepartureDate="2016-01-10" Amount="SGD170.00" NegotiatedFare="false" NotValidBefore="2016-01-10" NotValidAfter="2016-01-10">
            <air:BaggageAllowance>
                <air:MaxWeight Value="30" Unit="Kilograms" />
            </air:BaggageAllowance>
            <air:FareRuleKey FareInfoRef="1spaEzfCQ6u8Gji2V32UoA==" ProviderCode="1P">dir-CMB^BOM^2016010^ALL^LOW^AI^^</air:FareRuleKey>
            <air:Brand Key="NLh+a2unTsqsybUAdaRI2Q==" BrandFound="false" UpSellBrandFound="false" />
        </air:FareInfo>
        <air:FareInfo Key="pGAa6IujRsiUpF5xDl7qQw==" FareBasis="K2OWLK" PassengerTypeCode="ADT" Origin="CMB" Destination="BOM" EffectiveDate="2016-01-02T04:58:00.000+00:00" DepartureDate="2016-01-10" Amount="SGD222.00" NegotiatedFare="false" NotValidBefore="2016-01-10" NotValidAfter="2016-01-10">
            <air:FareSurcharge Key="qFpi8DNESk+RxL/4yTFkow==" Type="Weekend Surcharge" Amount="NUC20.00" />
            <air:BaggageAllowance>
                <air:MaxWeight Value="30" Unit="Kilograms" />
            </air:BaggageAllowance>
            <air:FareRuleKey FareInfoRef="pGAa6IujRsiUpF5xDl7qQw==" ProviderCode="1P">dir-CMB^BOM^2016010^ALL^K2OWLK^9W^^</air:FareRuleKey>
        </air:FareInfo>
        <air:FareInfo Key="QHe4j31/T4C5yM7vd1nNBA==" FareBasis="REFOWLK" PassengerTypeCode="ADT" Origin="CMB" Destination="BOM" EffectiveDate="2016-01-02T04:58:00.000+00:00" DepartureDate="2016-01-10" Amount="SGD219.00" NegotiatedFare="false" NotValidBefore="2016-01-10" NotValidAfter="2016-01-10">
            <air:BaggageAllowance>
                <air:MaxWeight Value="30" Unit="Kilograms" />
            </air:BaggageAllowance>
            <air:FareRuleKey FareInfoRef="QHe4j31/T4C5yM7vd1nNBA==" ProviderCode="1P">dir-CMB^BOM^2016010^ALL^REFOWLK^MJ^^</air:FareRuleKey>
        </air:FareInfo>
        <air:FareInfo Key="nkKa1jRTTJqFU6s1ZzULRA==" FareBasis="LOWLK" PassengerTypeCode="ADT" Origin="CMB" Destination="BOM" EffectiveDate="2016-01-02T04:58:00.000+00:00" DepartureDate="2016-01-10" Amount="SGD240.00" NegotiatedFare="false" NotValidBefore="2016-01-10" NotValidAfter="2016-01-10">
            <air:BaggageAllowance>
                <air:MaxWeight Value="30" Unit="Kilograms" />
            </air:BaggageAllowance>
            <air:FareRuleKey FareInfoRef="nkKa1jRTTJqFU6s1ZzULRA==" ProviderCode="1P">dir-CMB^BOM^2016010^ALL^LOWLK^UL^^</air:FareRuleKey>
        </air:FareInfo>
        <air:FareInfo Key="xg/hLJxzRrioyMfwnyKeaA==" FareBasis="TOWLKD" PassengerTypeCode="ADT" Origin="CMB" Destination="BOM" EffectiveDate="2016-01-02T04:58:00.000+00:00" DepartureDate="2016-01-10" Amount="SGD280.00" NegotiatedFare="false" NotValidBefore="2016-01-11" NotValidAfter="2016-01-11">
            <air:BaggageAllowance>
                <air:MaxWeight Value="30" Unit="Kilograms" />
            </air:BaggageAllowance>
            <air:FareRuleKey FareInfoRef="xg/hLJxzRrioyMfwnyKeaA==" ProviderCode="1P">dir-CMB^BOM^2016010^ALL^TOWLKD^AI^^</air:FareRuleKey>
            <air:Brand Key="ebl5ItYxSri4JmR6ZkMdgg==" BrandFound="false" UpSellBrandFound="false" />
        </air:FareInfo>
        <air:FareInfo Key="MrwSFEOoS2mV1F3pUYP1bQ==" FareBasis="WOWLKD" PassengerTypeCode="ADT" Origin="CMB" Destination="DEL" EffectiveDate="2016-01-02T04:58:00.000+00:00" DepartureDate="2016-01-10" Amount="SGD290.00" NegotiatedFare="false" NotValidBefore="2016-01-10" NotValidAfter="2016-01-10">
            <air:BaggageAllowance>
                <air:MaxWeight Value="30" Unit="Kilograms" />
            </air:BaggageAllowance>
            <air:FareRuleKey FareInfoRef="MrwSFEOoS2mV1F3pUYP1bQ==" ProviderCode="1P">dir-CMB^DEL^2016010^ALL^WOWLKD^AI^^</air:FareRuleKey>
            <air:Brand Key="X8FPdgCsS4yNQ4zhCCC88Q==" BrandFound="false" UpSellBrandFound="false" />
        </air:FareInfo>
        <air:FareInfo Key="Q1TNJfQlTbm5dnmMq7tOkg==" FareBasis="UIPD" PassengerTypeCode="ADT" Origin="DEL" Destination="BLR" EffectiveDate="2016-01-02T04:58:00.000+00:00" DepartureDate="2016-01-10" Amount="SGD39.00" NegotiatedFare="false" NotValidBefore="2016-01-10" NotValidAfter="2016-01-10">
            <air:BaggageAllowance>
                <air:MaxWeight Value="30" Unit="Kilograms" />
            </air:BaggageAllowance>
            <air:FareRuleKey FareInfoRef="Q1TNJfQlTbm5dnmMq7tOkg==" ProviderCode="1P">dir-DEL^BLR^2016010^ALL^UIPD^AI^^</air:FareRuleKey>
            <air:Brand Key="zMuyoF14RKqPQOu/O6BqPA==" BrandFound="false" UpSellBrandFound="false" />
        </air:FareInfo>
        <air:FareInfo Key="AoAaPfLASeGBrwjJ0OvZ6A==" FareBasis="UIPB" PassengerTypeCode="ADT" Origin="BLR" Destination="BOM" EffectiveDate="2016-01-02T04:58:00.000+00:00" DepartureDate="2016-01-10" Amount="SGD48.00" NegotiatedFare="false" NotValidBefore="2016-01-11" NotValidAfter="2016-01-11">
            <air:BaggageAllowance>
                <air:MaxWeight Value="30" Unit="Kilograms" />
            </air:BaggageAllowance>
            <air:FareRuleKey FareInfoRef="AoAaPfLASeGBrwjJ0OvZ6A==" ProviderCode="1P">dir-BLR^BOM^2016010^ALL^UIPB^AI^^</air:FareRuleKey>
            <air:Brand Key="WbWzDolGTpmW+DuqXxN92Q==" BrandFound="false" UpSellBrandFound="false" />
        </air:FareInfo>
        <air:FareInfo Key="xlzIG2AgRcmkMkLwQcBypQ==" FareBasis="WOWLKD" PassengerTypeCode="ADT" Origin="CMB" Destination="BLR" EffectiveDate="2016-01-02T04:58:00.000+00:00" DepartureDate="2016-01-10" Amount="SGD356.00" NegotiatedFare="false" NotValidBefore="2016-01-11" NotValidAfter="2016-01-11">
            <air:BaggageAllowance>
                <air:MaxWeight Value="30" Unit="Kilograms" />
            </air:BaggageAllowance>
            <air:FareRuleKey FareInfoRef="xlzIG2AgRcmkMkLwQcBypQ==" ProviderCode="1P">dir-CMB^BLR^2016010^ALL^WOWLKD^AI^^</air:FareRuleKey>
            <air:Brand Key="lTclpKz7TOiBqCrHyH6o0A==" BrandFound="false" UpSellBrandFound="false" />
        </air:FareInfo>
        <air:FareInfo Key="lHw96QzwRlyb+D2jD34mYQ==" FareBasis="EOW2LK1" PassengerTypeCode="ADT" Origin="CMB" Destination="BOM" EffectiveDate="2016-01-02T04:58:00.000+00:00" DepartureDate="2016-01-10" Amount="SGD477.00" NegotiatedFare="false">
            <air:BaggageAllowance>
                <air:MaxWeight Value="30" Unit="Kilograms" />
            </air:BaggageAllowance>
            <air:FareRuleKey FareInfoRef="lHw96QzwRlyb+D2jD34mYQ==" ProviderCode="1P">dir-CMB^BOM^2016010^ALL^EOW2LK1^FZ^^</air:FareRuleKey>
            <air:Brand Key="gVBazbISTdqOtkEd2RMJ0Q==" BrandID="5127" UpSellBrandID="5128">
                <air:UpsellBrand FareBasis="IOW2LK1" FareInfoRef="8MDLWMgQRSO7Kr3D8TlJtA==" />
            </air:Brand>
        </air:FareInfo>
        <air:FareInfo Key="K5XGlnwBSO6T1r7EYMejGA==" FareBasis="SAPOWLK" PassengerTypeCode="ADT" Origin="CMB" Destination="BLR" EffectiveDate="2016-01-02T04:58:00.000+00:00" DepartureDate="2016-01-10" Amount="SGD115.00" NegotiatedFare="false" NotValidBefore="2016-01-10" NotValidAfter="2016-01-10">
            <air:BaggageAllowance>
                <air:MaxWeight Value="30" Unit="Kilograms" />
            </air:BaggageAllowance>
            <air:FareRuleKey FareInfoRef="K5XGlnwBSO6T1r7EYMejGA==" ProviderCode="1P">dir-CMB^BLR^2016010^ALL^SAPOWLK^UL^^</air:FareRuleKey>
        </air:FareInfo>
        <air:FareInfo Key="Cl8Udzh9Sh62av+8JgLBhw==" FareBasis="Y2OW" PassengerTypeCode="ADT" Origin="BLR" Destination="BOM" EffectiveDate="2016-01-02T04:58:00.000+00:00" DepartureDate="2016-01-10" Amount="SGD436.00" NegotiatedFare="false" NotValidBefore="2016-01-10" NotValidAfter="2016-01-10">
            <air:FareSurcharge Key="JhGiq/kOR56aldQ5RFqlYQ==" Type="Other" Amount="NUC20.00" />
            <air:BaggageAllowance>
                <air:MaxWeight Value="30" Unit="Kilograms" />
            </air:BaggageAllowance>
            <air:FareRuleKey FareInfoRef="Cl8Udzh9Sh62av+8JgLBhw==" ProviderCode="1P">dir-BLR^BOM^2016010^ALL^Y2OW^9W^^</air:FareRuleKey>
        </air:FareInfo>
        <air:FareInfo Key="RsOaRIpvR0qWOrrfO27Zzg==" FareBasis="QOWLK" PassengerTypeCode="ADT" Origin="CMB" Destination="MAA" EffectiveDate="2016-01-02T04:58:00.000+00:00" DepartureDate="2016-01-10" Amount="SGD97.00" NegotiatedFare="false" NotValidBefore="2016-01-10" NotValidAfter="2016-01-10">
            <air:BaggageAllowance>
                <air:MaxWeight Value="30" Unit="Kilograms" />
            </air:BaggageAllowance>
            <air:FareRuleKey FareInfoRef="RsOaRIpvR0qWOrrfO27Zzg==" ProviderCode="1P">dir-CMB^MAA^2016010^ALL^QOWLK^UL^^</air:FareRuleKey>
        </air:FareInfo>
        <air:FareInfo Key="xlATZsktRp+2TSP/kpA9Ow==" FareBasis="Y2OW" PassengerTypeCode="ADT" Origin="MAA" Destination="BOM" EffectiveDate="2016-01-02T04:58:00.000+00:00" DepartureDate="2016-01-10" Amount="SGD472.00" NegotiatedFare="false" NotValidBefore="2016-01-11" NotValidAfter="2016-01-11">
            <air:FareSurcharge Key="kv3lElICTOeLjdT2nYXQHQ==" Type="Other" Amount="NUC20.00" />
            <air:BaggageAllowance>
                <air:MaxWeight Value="30" Unit="Kilograms" />
            </air:BaggageAllowance>
            <air:FareRuleKey FareInfoRef="xlATZsktRp+2TSP/kpA9Ow==" ProviderCode="1P">dir-MAA^BOM^2016010^ALL^Y2OW^9W^^</air:FareRuleKey>
        </air:FareInfo>
        <air:FareInfo Key="m4F05tCeS06DPWuGO1uodg==" FareBasis="Z2OWLK" PassengerTypeCode="ADT" Origin="CMB" Destination="BOM" EffectiveDate="2016-01-02T04:58:00.000+00:00" DepartureDate="2016-01-10" Amount="SGD649.00" NegotiatedFare="false" NotValidBefore="2016-01-10" NotValidAfter="2016-01-10">
            <air:FareSurcharge Key="djTVdq18QuOYeIXWRclSng==" Type="Weekend Surcharge" Amount="NUC25.00" />
            <air:BaggageAllowance>
                <air:MaxWeight Value="40" Unit="Kilograms" />
            </air:BaggageAllowance>
            <air:FareRuleKey FareInfoRef="m4F05tCeS06DPWuGO1uodg==" ProviderCode="1P">dir-CMB^BOM^2016010^ALL^Z2OWLK^9W^^</air:FareRuleKey>
        </air:FareInfo>
        <air:FareInfo Key="XKPXAyLvRyuXQyBDg1pYxQ==" FareBasis="YIF" PassengerTypeCode="ADT" Origin="CMB" Destination="BOM" EffectiveDate="2016-01-02T04:58:00.000+00:00" DepartureDate="2016-01-10" Amount="SGD759.00" NegotiatedFare="false">
            <air:BaggageAllowance>
                <air:MaxWeight Value="30" Unit="Kilograms" />
            </air:BaggageAllowance>
            <air:FareRuleKey FareInfoRef="XKPXAyLvRyuXQyBDg1pYxQ==" ProviderCode="1P">dir-CMB^BOM^2016010^ALL^YIF^YY^^</air:FareRuleKey>
        </air:FareInfo>
        <air:FareInfo Key="tCFMdIJqS4iCyh+UY7JLuw==" FareBasis="QOWMJLK" PassengerTypeCode="ADT" Origin="CMB" Destination="MCT" EffectiveDate="2016-01-02T04:58:00.000+00:00" DepartureDate="2016-01-10" Amount="SGD141.00" NegotiatedFare="false" NotValidBefore="2016-01-10" NotValidAfter="2016-01-10">
            <air:FareSurcharge Key="rlOzMjHDQqi2+/k93Rf35w==" Type="Other" Amount="NUC3.00" />
            <air:BaggageAllowance>
                <air:MaxWeight Value="30" Unit="Kilograms" />
            </air:BaggageAllowance>
            <air:FareRuleKey FareInfoRef="tCFMdIJqS4iCyh+UY7JLuw==" ProviderCode="1P">dir-CMB^MCT^2016010^ALL^QOWMJLK^UL^^</air:FareRuleKey>
        </air:FareInfo>
        <air:FareInfo Key="2kHvsa7xQBuVcFzqm5Fy1Q==" FareBasis="Y" PassengerTypeCode="ADT" Origin="MCT" Destination="BOM" EffectiveDate="2016-01-02T04:58:00.000+00:00" DepartureDate="2016-01-10" Amount="SGD705.00" NegotiatedFare="false">
            <air:BaggageAllowance>
                <air:MaxWeight Value="30" Unit="Kilograms" />
            </air:BaggageAllowance>
            <air:FareRuleKey FareInfoRef="2kHvsa7xQBuVcFzqm5Fy1Q==" ProviderCode="1P">dir-MCT^BOM^2016010^ALL^Y^YY^^</air:FareRuleKey>
            <air:Brand Key="qcWwQU9UTnu8ECczI0+KKQ==" BrandFound="false" UpSellBrandFound="false" />
        </air:FareInfo>
        <air:FareInfo Key="ZZOoOTUNRQ+Wa579FlHfeQ==" FareBasis="Y" PassengerTypeCode="ADT" Origin="MCT" Destination="BOM" EffectiveDate="2016-01-02T04:58:00.000+00:00" DepartureDate="2016-01-10" Amount="SGD705.00" NegotiatedFare="false">
            <air:BaggageAllowance>
                <air:MaxWeight Value="30" Unit="Kilograms" />
            </air:BaggageAllowance>
            <air:FareRuleKey FareInfoRef="ZZOoOTUNRQ+Wa579FlHfeQ==" ProviderCode="1P">dir-MCT^BOM^2016010^ALL^Y^YY^^</air:FareRuleKey>
        </air:FareInfo>
        <air:FareInfo Key="S50dIJiOT7ugOzLyNSWwsg==" FareBasis="YIFMH" PassengerTypeCode="ADT" Origin="CMB" Destination="KUL" EffectiveDate="2016-01-02T04:58:00.000+00:00" DepartureDate="2016-01-10" Amount="SGD698.00" NegotiatedFare="false" NotValidAfter="2017-01-10">
            <air:BaggageAllowance>
                <air:MaxWeight Value="30" Unit="Kilograms" />
            </air:BaggageAllowance>
            <air:FareRuleKey FareInfoRef="S50dIJiOT7ugOzLyNSWwsg==" ProviderCode="1P">dir-CMB^KUL^2016010^ALL^YIFMH^MH^^</air:FareRuleKey>
        </air:FareInfo>
        <air:FareInfo Key="b953pyqFRr+8y8KHc5OobQ==" FareBasis="LST1YMY" PassengerTypeCode="ADT" Origin="KUL" Destination="BOM" EffectiveDate="2016-01-02T04:58:00.000+00:00" DepartureDate="2016-01-10" Amount="SGD368.00" NegotiatedFare="false" NotValidBefore="2016-01-10" NotValidAfter="2016-01-10">
            <air:FareSurcharge Key="t03lsk/zRYmwSdDDrR6PNw==" Type="Other" Amount="NUC30.00" />
            <air:BaggageAllowance>
                <air:MaxWeight Value="30" Unit="Kilograms" />
            </air:BaggageAllowance>
            <air:FareRuleKey FareInfoRef="b953pyqFRr+8y8KHc5OobQ==" ProviderCode="1P">dir-KUL^BOM^2016010^ALL^LST1YMY^MH^^</air:FareRuleKey>
        </air:FareInfo>
        <air:FareInfo Key="m85d68MCQQ+tA/rEBCYWKw==" FareBasis="YOMTG" PassengerTypeCode="ADT" Origin="CMB" Destination="BKK" EffectiveDate="2016-01-02T04:58:00.000+00:00" DepartureDate="2016-01-10" Amount="SGD527.00" NegotiatedFare="false">
            <air:BaggageAllowance>
                <air:MaxWeight Value="30" Unit="Kilograms" />
            </air:BaggageAllowance>
            <air:FareRuleKey FareInfoRef="m85d68MCQQ+tA/rEBCYWKw==" ProviderCode="1P">dir-CMB^BKK^2016010^ALL^YOMTG^TG^^</air:FareRuleKey>
            <air:Brand Key="MF8D4N/xSHuF+71KMYSeqA==" BrandFound="false" UpSellBrandFound="false" />
        </air:FareInfo>
        <air:FareInfo Key="UisGy7vKSdiRtokN6DLZLw==" FareBasis="YOMTG" PassengerTypeCode="ADT" Origin="BKK" Destination="BOM" EffectiveDate="2016-01-02T04:58:00.000+00:00" DepartureDate="2016-01-10" Amount="SGD911.00" NegotiatedFare="false">
            <air:BaggageAllowance>
                <air:MaxWeight Value="30" Unit="Kilograms" />
            </air:BaggageAllowance>
            <air:FareRuleKey FareInfoRef="UisGy7vKSdiRtokN6DLZLw==" ProviderCode="1P">dir-BKK^BOM^2016010^ALL^YOMTG^TG^^</air:FareRuleKey>
            <air:Brand Key="dhni9Eh2R9OtKai0lU2ODw==" BrandFound="false" UpSellBrandFound="false" />
        </air:FareInfo>
        <air:FareInfo Key="wRPr5T4sR+mNHofsO8CfIQ==" FareBasis="YRTLK" PassengerTypeCode="ADT" Origin="CMB" Destination="AUH" EffectiveDate="2016-01-02T04:58:00.000+00:00" DepartureDate="2016-01-10" Amount="SGD550.00" NegotiatedFare="false" NotValidAfter="2017-01-10">
            <air:FareTicketDesignator Value="YF" />
            <air:FareSurcharge Key="WClRyMVNS0uDKm6KurPlyA==" Type="Other" Amount="NUC52.00" />
            <air:BaggageAllowance>
                <air:NumberOfPieces>2</air:NumberOfPieces>
                <air:MaxWeight />
            </air:BaggageAllowance>
            <air:FareRuleKey FareInfoRef="wRPr5T4sR+mNHofsO8CfIQ==" ProviderCode="1P">dir-CMB^AUH^2016010^ALL^YRTLK^EY^^</air:FareRuleKey>
            <air:Brand Key="SBJhN8L9TEmaWZDzZOvmgQ==" BrandFound="false" UpSellBrandFound="false" />
        </air:FareInfo>
        <air:FareInfo Key="QGzgZMKOTX6TJB+1VnF5lg==" FareBasis="YRT11" PassengerTypeCode="ADT" Origin="AUH" Destination="BOM" EffectiveDate="2016-01-02T04:58:00.000+00:00" DepartureDate="2016-01-10" Amount="SGD1284.00" NegotiatedFare="false" NotValidAfter="2017-01-10">
            <air:FareTicketDesignator Value="YF" />
            <air:BaggageAllowance>
                <air:NumberOfPieces>2</air:NumberOfPieces>
                <air:MaxWeight />
            </air:BaggageAllowance>
            <air:FareRuleKey FareInfoRef="QGzgZMKOTX6TJB+1VnF5lg==" ProviderCode="1P">dir-AUH^BOM^2016010^ALL^YRT11^EY^^</air:FareRuleKey>
            <air:Brand Key="fAYKLw0uSQCMYaswTCiTug==" BrandFound="false" UpSellBrandFound="false" />
        </air:FareInfo>
        <air:FareInfo Key="Qv5IfRX5Q1mgPIolx2q6wA==" FareBasis="YIF" PassengerTypeCode="ADT" Origin="CMB" Destination="AUH" EffectiveDate="2016-01-02T04:58:00.000+00:00" DepartureDate="2016-01-10" Amount="SGD1049.00" NegotiatedFare="false">
            <air:BaggageAllowance>
                <air:NumberOfPieces>2</air:NumberOfPieces>
                <air:MaxWeight />
            </air:BaggageAllowance>
            <air:FareRuleKey FareInfoRef="Qv5IfRX5Q1mgPIolx2q6wA==" ProviderCode="1P">dir-CMB^AUH^2016010^ALL^YIF^YY^^</air:FareRuleKey>
        </air:FareInfo>
        <air:FareInfo Key="QYyaqkhQTnmz3rYQit8vhg==" FareBasis="YIF" PassengerTypeCode="ADT" Origin="AUH" Destination="BOM" EffectiveDate="2016-01-02T04:58:00.000+00:00" DepartureDate="2016-01-10" Amount="SGD1211.00" NegotiatedFare="false">
            <air:BaggageAllowance>
                <air:NumberOfPieces>2</air:NumberOfPieces>
                <air:MaxWeight />
            </air:BaggageAllowance>
            <air:FareRuleKey FareInfoRef="QYyaqkhQTnmz3rYQit8vhg==" ProviderCode="1P">dir-AUH^BOM^2016010^ALL^YIF^YY^^</air:FareRuleKey>
        </air:FareInfo>
        <air:FareInfo Key="Iu/ZuMcRRuq7Rl8fssqXBg==" FareBasis="YRTLK6" PassengerTypeCode="ADT" Origin="CMB" Destination="SIN" EffectiveDate="2016-01-02T04:58:00.000+00:00" DepartureDate="2016-01-10" Amount="SGD618.00" NegotiatedFare="false" NotValidAfter="2017-01-10">
            <air:BaggageAllowance>
                <air:MaxWeight Value="30" Unit="Kilograms" />
            </air:BaggageAllowance>
            <air:FareRuleKey FareInfoRef="Iu/ZuMcRRuq7Rl8fssqXBg==" ProviderCode="1P">dir-CMB^SIN^2016010^ALL^YRTLK6^SQ^^</air:FareRuleKey>
            <air:Brand Key="eKHqp2esS9a26IhDR5LBrw==" BrandID="5705" UpSellBrandFound="false" />
        </air:FareInfo>
        <air:FareInfo Key="pY2MfEUUQcmdaak/SUPJ8w==" FareBasis="YIF" PassengerTypeCode="ADT" Origin="SIN" Destination="BOM" EffectiveDate="2016-01-02T04:58:00.000+00:00" DepartureDate="2016-01-10" Amount="SGD2497.00" NegotiatedFare="false" NotValidAfter="2017-01-10">
            <air:BaggageAllowance>
                <air:MaxWeight Value="30" Unit="Kilograms" />
            </air:BaggageAllowance>
            <air:FareRuleKey FareInfoRef="pY2MfEUUQcmdaak/SUPJ8w==" ProviderCode="1P">dir-SIN^BOM^2016010^ALL^YIF^YY^^</air:FareRuleKey>
            <air:Brand Key="1GGjv0L8Rqqx9ysAYKXL9w==" BrandID="5705" UpSellBrandFound="false" />
        </air:FareInfo>
        <air:FareInfo Key="BEbnxNWcQhe26FlfS+mphQ==" FareBasis="YIF" PassengerTypeCode="ADT" Origin="CMB" Destination="SIN" EffectiveDate="2016-01-02T04:58:00.000+00:00" DepartureDate="2016-01-10" Amount="SGD714.00" NegotiatedFare="false">
            <air:BaggageAllowance>
                <air:MaxWeight Value="30" Unit="Kilograms" />
            </air:BaggageAllowance>
            <air:FareRuleKey FareInfoRef="BEbnxNWcQhe26FlfS+mphQ==" ProviderCode="1P">dir-CMB^SIN^2016010^ALL^YIF^YY^^</air:FareRuleKey>
        </air:FareInfo>
        <air:FareInfo Key="GnpFB8oGRAG8mvfGJ8ofnw==" FareBasis="CIF" PassengerTypeCode="ADT" Origin="SIN" Destination="BOM" EffectiveDate="2016-01-02T04:58:00.000+00:00" DepartureDate="2016-01-10" Amount="SGD3273.00" NegotiatedFare="false">
            <air:BaggageAllowance>
                <air:MaxWeight Value="30" Unit="Kilograms" />
            </air:BaggageAllowance>
            <air:FareRuleKey FareInfoRef="GnpFB8oGRAG8mvfGJ8ofnw==" ProviderCode="1P">dir-SIN^BOM^2016010^ALL^CIF^YY^^</air:FareRuleKey>
        </air:FareInfo>
        <air:FareInfo Key="8MDLWMgQRSO7Kr3D8TlJtA==" FareBasis="IOW2LK1" PassengerTypeCode="ADT" Origin="CMB" Destination="BOM" EffectiveDate="2016-01-02T04:58:00.000+00:00" DepartureDate="2016-01-10" Amount="SGD556.00" NegotiatedFare="false">
            <air:BaggageAllowance>
                <air:MaxWeight Value="30" Unit="Kilograms" />
            </air:BaggageAllowance>
            <air:FareRuleKey FareInfoRef="8MDLWMgQRSO7Kr3D8TlJtA==" ProviderCode="1P">dir-CMB^BOM^2016010^ALL^IOW2LK1^FZ^^</air:FareRuleKey>
            <air:Brand Key="nQyxKWcMR32fhcQA9JzB7A==" BrandID="5128" UpSellBrandFound="false" />
        </air:FareInfo>
    </air:FareInfoList>
    <air:RouteList>
        <air:Route Key="oNkJPTBER0ONvY5ji/aIAw==">
            <air:Leg Key="AGeIxHoCRVKUqYadw8wubQ==" Group="0" Origin="CMB" Destination="BOM" />
        </air:Route>
    </air:RouteList>
    <air:AirPricePointList>
        <air:AirPricePoint Key="pOENU9QkTICkJdqkJ9VlMw==" TotalPrice="SGD289.00" BasePrice="LKR17000" ApproximateTotalPrice="USD206.89" ApproximateBasePrice="USD121.70" EquivalentBasePrice="SGD170.00" Taxes="SGD119.00" ApproximateTaxes="USD85.19" CompleteItinerary="true">
            <air:AirPricingInfo Key="+5EcYv7LTTm9/tftK/I4Tg==" TotalPrice="SGD289.00" BasePrice="LKR17000" ApproximateTotalPrice="USD206.89" ApproximateBasePrice="USD121.70" EquivalentBasePrice="SGD170.00" Taxes="SGD119.00" LatestTicketingTime="2016-01-10T23:59:00.000+00:00" PricingMethod="Unknown" Refundable="true" ETicketability="Yes" PlatingCarrier="AI" ProviderCode="1P">
                <air:FareInfoRef Key="1spaEzfCQ6u8Gji2V32UoA==" />
                <air:TaxInfo Category="EJ" Amount="SGD84.00" />
                <air:TaxInfo Category="LK" Amount="SGD35.00" />
                <air:FareCalc>CMB AI X/MAA AI BOM122.92NUC122.92END ROE138.30</air:FareCalc>
                <air:PassengerType Code="ADT" Age="40" />
                <air:ChangePenalty>
                    <air:Amount>SGD21.00</air:Amount>
                </air:ChangePenalty>
                <air:CancelPenalty>
                    <air:Amount>SGD29.00</air:Amount>
                </air:CancelPenalty>
                <air:FlightOptionsList>
                    <air:FlightOption LegRef="AGeIxHoCRVKUqYadw8wubQ==" Destination="BOM" Origin="CMB">
                        <air:Option Key="GXl6oJ0ITvKCP601CnvfbQ==" TravelTime="P0DT6H15M0S">
                            <air:BookingInfo BookingCode="L" CabinClass="Economy" FareInfoRef="1spaEzfCQ6u8Gji2V32UoA==" SegmentRef="qEqLJK1LRAGnhEwqpT9j5Q==" />
                            <air:BookingInfo BookingCode="V" CabinClass="Economy" FareInfoRef="1spaEzfCQ6u8Gji2V32UoA==" SegmentRef="aZTP9qwYR9SOncCsMhoaSQ==" />
                            <air:Connection SegmentIndex="0" />
                        </air:Option>
                        <air:Option Key="hU2SBJDbStK2BqQeddw4Uw==" TravelTime="P0DT15H5M0S">
                            <air:BookingInfo BookingCode="L" CabinClass="Economy" FareInfoRef="1spaEzfCQ6u8Gji2V32UoA==" SegmentRef="qEqLJK1LRAGnhEwqpT9j5Q==" />
                            <air:BookingInfo BookingCode="V" CabinClass="Economy" FareInfoRef="1spaEzfCQ6u8Gji2V32UoA==" SegmentRef="K3tPr6oWRcCqgIipKphuxg==" />
                            <air:Connection SegmentIndex="0" />
                        </air:Option>
                    </air:FlightOption>
                </air:FlightOptionsList>
            </air:AirPricingInfo>
        </air:AirPricePoint>
       
       <air:AirPricePoint Key="KoKtDGcIQZSU3nIgRV3sEg==" TotalPrice="SGD341.00" BasePrice="LKR22200" ApproximateTotalPrice="USD244.12" ApproximateBasePrice="USD158.93" EquivalentBasePrice="SGD222.00" Taxes="SGD119.00" ApproximateTaxes="USD85.19" CompleteItinerary="true">
            <air:AirPricingInfo Key="sK0/6fcXRw2jDrwy8vc1fA==" TotalPrice="SGD341.00" BasePrice="LKR22200" ApproximateTotalPrice="USD244.12" ApproximateBasePrice="USD158.93" EquivalentBasePrice="SGD222.00" Taxes="SGD119.00" LatestTicketingTime="2016-01-10T23:59:00.000+00:00" PricingMethod="Unknown" Refundable="true" ETicketability="Yes" PlatingCarrier="9W" ProviderCode="1P">
                <air:FareInfoRef Key="pGAa6IujRsiUpF5xDl7qQw==" />
                <air:TaxInfo Category="EJ" Amount="SGD84.00" />
                <air:TaxInfo Category="LK" Amount="SGD35.00" />
                <air:FareCalc>CMB 9W BOM Q20.00 140.27NUC160.27END ROE138.30</air:FareCalc>
                <air:PassengerType Code="ADT" Age="40" />
                <air:ChangePenalty>
                    <air:Amount>SGD19.00</air:Amount>
                </air:ChangePenalty>
                <air:CancelPenalty>
                    <air:Amount>SGD29.00</air:Amount>
                </air:CancelPenalty>
                <air:FlightOptionsList>
                    <air:FlightOption LegRef="AGeIxHoCRVKUqYadw8wubQ==" Destination="BOM" Origin="CMB">
                        <air:Option Key="EA8FAl8BRPSIf09H2ndNlA==" TravelTime="P0DT2H35M0S">
                            <air:BookingInfo BookingCode="K" CabinClass="Economy" FareInfoRef="pGAa6IujRsiUpF5xDl7qQw==" SegmentRef="cK3iLlrFTbu5CkLz3ZvIXg==" />
                        </air:Option>
                    </air:FlightOption>
                </air:FlightOptionsList>
            </air:AirPricingInfo>
        </air:AirPricePoint>
     
        
  
    </air:AirPricePointList>
    
</air:LowFareSearchRsp>
</SOAP:Body>
</SOAP:Envelope>';


        $arr = $this->input->post();
       
        //get the airpricinginfo key form the interface
        $AirPricinginfoKey = $arr["key"];
       
        $optionDetails = array();
        $optionKeys = array();
        $flightOptionArray = array(); //flight option details
        $bookingInfoArray = array();
        $SegmentRefKey = NULL;

        $response1_xml = simplexml_load_string($response1);
        $response1_xml->registerXPathNamespace('soap', "http://schemas.xmlsoap.org/soap/envelope/");
        $response1_xml->registerXPathNamespace('air', "http://www.travelport.com/schema/air_v33_0");


        // $xmlobject_result = $response1_xml->xpath('/soap:Envelope/soap:Body/air:LowFareSearchRsp/air:AirPricePointList/air:AirPricePoint/air:AirPricingInfo[@Key="'.$AirPricinginfoKey.'"]');
        $getair_optionKeys = $response1_xml->xpath('/soap:Envelope/soap:Body/air:LowFareSearchRsp/air:AirPricePointList/air:AirPricePoint/air:AirPricingInfo[@Key="' . $AirPricinginfoKey . '"]/air:FlightOptionsList/air:FlightOption/air:Option/@Key');

        //get the option details according to the option key 
        foreach ($getair_optionKeys as $key => $optionKeys) {

            $optionKey = get_object_vars($optionKeys);
            $opKey = $optionKey ["@attributes"]["Key"];
            $flightOptionArray["$key"]["flightOptionKey"] = $opKey;
            //booking info tag fetch
            $BookingInfo = $response1_xml->xpath('/soap:Envelope/soap:Body/air:LowFareSearchRsp/air:AirPricePointList/air:AirPricePoint/air:AirPricingInfo/air:FlightOptionsList/air:FlightOption/air:Option[@Key="' . $opKey . '"]/air:BookingInfo');


            foreach ($BookingInfo as $key2 => $BookingInfovalue) {

                $BookingInfofiltervalue = get_object_vars($BookingInfovalue);

                $SegmentRefKey = $BookingInfofiltervalue["@attributes"]["SegmentRef"];

                $bookingInfoArray[$key2]["bookingDetails"] = $BookingInfofiltervalue["@attributes"];

                $segmentDetails = $response1_xml->xpath('/soap:Envelope/soap:Body/air:LowFareSearchRsp/air:AirSegmentList/air:AirSegment[@Key="' . $SegmentRefKey . '"]');

                $segmentDetailsFilterValue = get_object_vars($segmentDetails[0]);
                $bookingInfoArray[$key2]["segmentDetails"] = $segmentDetailsFilterValue["@attributes"];
                $segmentDetailsFilterValue = NULL;
            }

            $flightOptionArray["$key"]["flightOptionDetails"] = $bookingInfoArray;
            $bookingInfoArray = NULL;
        }
        
        
        $arr1=array("value"=>$flightOptionArray);
       // echo json_encode($flightOptionArray);
        
        $this->load->view("bookingView2",$arr1);
    }
    
    public function priceRequestCreate()
    {
        $arr=$this->input->post();
        var_dump($arr);
    }

}
