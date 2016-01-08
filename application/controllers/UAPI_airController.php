<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class UAPI_airController extends CI_Controller {

    public $response1 = NULL;

    public function index() {
        
    }

    public function createFirstRequest() {
        //get the customer entered value(From,To,DepDate,ReturnDate,NoAdults,NoChildren,NoInfant)


        $parameterArray = array();
        //oneway
        $parameterArray[0]['origin'] = "CMB";
        $parameterArray[0]['destination'] = "BOM";
        $parameterArray[0]['date'] = "2015-12-30";
        //round trip
        $parameterArray[1]['origin'] = "BOM";
        $parameterArray[1]['destination'] = "CMB";
        $parameterArray[1]['date'] = "2015-12-30";
        //customer details
        $searchPassengerArray = array();
        $searchPassengerArray[0]['Code'] = "ADT";
        $searchPassengerArray[0]['Age'] = "40";
        $searchPassengerArray[0]['DOB'] = "1975-12-23";
        $searchPassengerArray[1]['Code'] = "CNN";
        $searchPassengerArray[1]['Age'] = "10";
        $searchPassengerArray[1]['DOB'] = "2005-12-23";
        //number of passengers
        $adults = 1;
        $child = 1;
        $infant = 1;
        $tripCount = 1;

        $SearchLeg = NULL;
        $searchPassengers = NULL;
        $TARGETBRANCH = 'P7026977'; //'Enter the Target Branch that you received in your Welcome letter';
        $CREDENTIALS = 'Universal API/uAPI4025719287-ca2a7cc1:Si8=?Wk3Lf'; //Universal API/API1234567:Password provieded in the welcome leter';
        $Provider = '1P'; // Any provider you want to use like 1G/1P/1V/ACH
        //---loop for add relevant tags---------------------------------

        for ($i = 0; $i < $tripCount; $i++) {//create SearchAirLeg tags according to origin and destination
            $origin = $parameterArray[$i]['origin'];
            $destination = $parameterArray[$i]['destination'];
            $date = $parameterArray[0]['date'];

            $SearchLeg .=" <SearchAirLeg>
        <SearchOrigin>
            <CityOrAirport xmlns='http://www.travelport.com/schema/common_v33_0' Code='$origin' PreferCity='true' />
        </SearchOrigin>
        <SearchDestination>
            <CityOrAirport xmlns='http://www.travelport.com/schema/common_v33_0' Code='$destination' PreferCity='true' />
        </SearchDestination>
        <SearchDepTime PreferredTime='$date' />
    </SearchAirLeg>";
        }

        for ($i = 0; $i < $tripCount; $i++) {//create SearchPassenger tags according to passengers
            $Code = $searchPassengerArray[$i]['Code'];
            $Age = $searchPassengerArray[$i]['Age'];
            $DOB = $searchPassengerArray[$i]['DOB'];

            $searchPassengers .="<SearchPassenger xmlns='http://www.travelport.com/schema/common_v33_0' Code='$Code' Age='$Age' DOB='$DOB'/>";
        }

        //------create request------------------------------------------
        $lowfareRequest = <<<EOM
<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/">
<soapenv:Header/>
<soapenv:Body>
  <LowFareSearchReq xmlns="http://www.travelport.com/schema/air_v33_0" TraceId="49087225-93d8-48d0-bfc0-90ad69f9329c" TargetBranch="$TARGETBRANCH" ReturnUpsellFare="true">
    <BillingPointOfSaleInfo xmlns="http://www.travelport.com/schema/common_v33_0" OriginApplication="uAPI" />
    
    $SearchLeg
    <AirSearchModifiers>
        <PreferredProviders>
            <Provider xmlns="http://www.travelport.com/schema/common_v33_0" Code="1P" />
        </PreferredProviders>
    </AirSearchModifiers>
   $searchPassengers
</LowFareSearchReq>
</soapenv:Body>
</soapenv:Envelope>      
EOM;

        //echo htmlentities($lowfareRequest);
        //die();
        //------create curl---------------------------------------------
//credintial
//curl request
        $auth = base64_encode("$CREDENTIALS");
//$soap_do = curl_init("https://americas.universal-api.pp.travelport.com/B2BGateway/connect/uAPI/AirService");
        $soap_do = curl_init("https://apac.universal-api.pp.travelport.com/B2BGateway/connect/uAPI/AirService");
        $header = array(
            "Content-Type: text/xml;charset=UTF-8",
            "Accept: gzip,deflate",
            "Cache-Control: no-cache",
            "Pragma: no-cache",
            "SOAPAction: \"\"",
            "Authorization: Basic $auth",
            "Content-length: " . strlen($lowfareRequest),
        );
        //curl_setopt($soap_do, CURLOPT_CONNECTTIMEOUT, 30); 
        //curl_setopt($soap_do, CURLOPT_TIMEOUT, 30); 
        curl_setopt($soap_do, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($soap_do, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($soap_do, CURLOPT_POST, true);
        curl_setopt($soap_do, CURLOPT_POSTFIELDS, $lowfareRequest);
        curl_setopt($soap_do, CURLOPT_HTTPHEADER, $header);
        curl_setopt($soap_do, CURLOPT_RETURNTRANSFER, true); // this will prevent the curl_exec to return result and will let us to capture output
        $return = curl_exec($soap_do);

        // echo htmlentities($return);
        //$this->fetchFirstResponse($return);
        //$response1=$return;
    }

    public function fetchFirstResponse() {
        // echo htmlentities($response);
        $response = '<SOAP:Envelope xmlns:SOAP="http://schemas.xmlsoap.org/soap/envelope/">
    <SOAP:Body>
        <air:LowFareSearchRsp TraceId="910b76e8-a41b-4cb8-b74b-218051cec913" TransactionId="CD2E1B2B0A076477DB8090B815AC0F13" ResponseTime="6432" DistanceUnits="MI" CurrencyType="LKR" xmlns:air="http://www.travelport.com/schema/air_v33_0">
            <air:FlightDetailsList>
                <air:FlightDetails Key="d/xPJlHqRq+oR2sTQiFgyw==" Origin="CMB" Destination="MAA" DepartureTime="2015-12-30T18:30:00.000+05:30" ArrivalTime="2015-12-30T19:50:00.000+05:30" FlightTime="80" TravelTime="820" Equipment="321" DestinationTerminal="4"/>
                <air:FlightDetails Key="xU8001M/Qe61/805PDnA0g==" Origin="MAA" Destination="BOM" DepartureTime="2015-12-31T06:20:00.000+05:30" ArrivalTime="2015-12-31T08:10:00.000+05:30" FlightTime="110" TravelTime="820" Equipment="319" OriginTerminal="1" DestinationTerminal="2"/>
                <air:FlightDetails Key="ZbwpPYQJRkqyeSamQbuUiA==" Origin="CMB" Destination="BOM" DepartureTime="2015-12-30T23:45:00.000+05:30" ArrivalTime="2015-12-31T02:10:00.000+05:30" FlightTime="145" TravelTime="145" Equipment="320" DestinationTerminal="2"/>
                <air:FlightDetails Key="Gd2Jc0QrTiiQAGIMXHoPlg==" Origin="CMB" Destination="DEL" DepartureTime="2015-12-30T08:20:00.000+05:30" ArrivalTime="2015-12-30T12:10:00.000+05:30" FlightTime="230" TravelTime="635" Equipment="321" DestinationTerminal="3"/>
                <air:FlightDetails Key="U7G2bGx2Q+uUKH1CBLXX/A==" Origin="DEL" Destination="BOM" DepartureTime="2015-12-30T16:45:00.000+05:30" ArrivalTime="2015-12-30T18:55:00.000+05:30" FlightTime="130" TravelTime="635" Equipment="77W" OriginTerminal="3" DestinationTerminal="2"/>
                <air:FlightDetails Key="cZs+vjH+RMiAxMREYz/Deg==" Origin="DEL" Destination="BOM" DepartureTime="2015-12-30T17:00:00.000+05:30" ArrivalTime="2015-12-30T19:20:00.000+05:30" FlightTime="140" TravelTime="660" Equipment="321" OriginTerminal="3" DestinationTerminal="2"/>
                <air:FlightDetails Key="HdS0zPwDRE+q3hfUD6z78Q==" Origin="CMB" Destination="BKK" DepartureTime="2015-12-30T01:30:00.000+05:30" ArrivalTime="2015-12-30T06:25:00.000+07:00" FlightTime="205" TravelTime="1230" Equipment="777"/>
                <air:FlightDetails Key="cAfVJ4/bTAqImb+c19OqRQ==" Origin="BKK" Destination="BOM" DepartureTime="2015-12-30T18:55:00.000+07:00" ArrivalTime="2015-12-30T22:00:00.000+05:30" FlightTime="275" TravelTime="1230" Equipment="744" DestinationTerminal="2"/>
                <air:FlightDetails Key="/JWxfWPsR9OBvHrX6PMhvw==" Origin="CMB" Destination="KWI" DepartureTime="2015-12-30T05:45:00.000+05:30" ArrivalTime="2015-12-30T08:50:00.000+03:00" FlightTime="335" TravelTime="1365" Equipment="340" DestinationTerminal="M"/>
                <air:FlightDetails Key="ZlTb3cBfQFautbZZ3Exluw==" Origin="KWI" Destination="BOM" DepartureTime="2015-12-30T22:10:00.000+03:00" ArrivalTime="2015-12-31T04:30:00.000+05:30" FlightTime="230" TravelTime="1365" Equipment="747" OriginTerminal="M" DestinationTerminal="2"/>
                <air:FlightDetails Key="TEPWnCHeSIKj0P7vJ/RWFw==" Origin="CMB" Destination="RUH" Equipment="744"/>
                <air:FlightDetails Key="qi+qIBnCSrSc6Hw7FxUahA==" Origin="RUH" Destination="JED" Equipment="744"/>
                <air:FlightDetails Key="J5nzS5vQSImaemg/iN0VZA==" Origin="JED" Destination="BOM" DepartureTime="2015-12-31T00:35:00.000+03:00" ArrivalTime="2015-12-31T07:30:00.000+05:30" FlightTime="265" TravelTime="1100" Equipment="772" OriginTerminal="S" DestinationTerminal="2"/>
                <air:FlightDetails Key="LxdpuAhWR+adm3lmPhAo1Q==" Origin="CMB" Destination="BLR" DepartureTime="2015-12-30T01:15:00.000+05:30" ArrivalTime="2015-12-30T02:35:00.000+05:30" FlightTime="80" TravelTime="370" Equipment="321"/>
                <air:FlightDetails Key="0tDsNA5EQLSxvRIKRhc4OA==" Origin="BLR" Destination="BOM" DepartureTime="2015-12-30T05:50:00.000+05:30" ArrivalTime="2015-12-30T07:25:00.000+05:30" FlightTime="95" TravelTime="370" Equipment="738" DestinationTerminal="1B"/>
                <air:FlightDetails Key="LShq2hp2S1enmJcTPGp34Q==" Origin="CMB" Destination="MAA" DepartureTime="2015-12-30T13:45:00.000+05:30" ArrivalTime="2015-12-30T15:05:00.000+05:30" FlightTime="80" TravelTime="375" Equipment="332" DestinationTerminal="3"/>
                <air:FlightDetails Key="bkrj3iUdTTeoORVLHHreOQ==" Origin="MAA" Destination="BOM" DepartureTime="2015-12-30T18:10:00.000+05:30" ArrivalTime="2015-12-30T20:00:00.000+05:30" FlightTime="110" TravelTime="375" Equipment="738" OriginTerminal="1" DestinationTerminal="1B"/>
                <air:FlightDetails Key="VRrV12IATP2GQ3DMVdGM+Q==" Origin="CMB" Destination="MAA" DepartureTime="2015-12-30T07:20:00.000+05:30" ArrivalTime="2015-12-30T08:40:00.000+05:30" FlightTime="80" TravelTime="355" Equipment="332" DestinationTerminal="3"/>
                <air:FlightDetails Key="RJb0aNsJRHmp9w4uLMAZ4Q==" Origin="MAA" Destination="BOM" DepartureTime="2015-12-30T11:25:00.000+05:30" ArrivalTime="2015-12-30T13:15:00.000+05:30" FlightTime="110" TravelTime="355" Equipment="737" OriginTerminal="1" DestinationTerminal="1B"/>
                <air:FlightDetails Key="UoxYxq60QYmHLqRbdGhgjQ==" Origin="CMB" Destination="BLR" DepartureTime="2015-12-30T18:45:00.000+05:30" ArrivalTime="2015-12-30T20:05:00.000+05:30" FlightTime="80" TravelTime="275" Equipment="320"/>
                <air:FlightDetails Key="qJTmznqESIiptpB0lIksdA==" Origin="BLR" Destination="BOM" DepartureTime="2015-12-30T21:45:00.000+05:30" ArrivalTime="2015-12-30T23:20:00.000+05:30" FlightTime="95" TravelTime="275" Equipment="737" DestinationTerminal="1B"/>
                <air:FlightDetails Key="uVayohQfRDOOsajbTPItTA==" Origin="CMB" Destination="DOH" DepartureTime="2015-12-30T09:25:00.000+05:30" ArrivalTime="2015-12-30T11:55:00.000+03:00" FlightTime="300" TravelTime="995" Equipment="346"/>
                <air:FlightDetails Key="t4xQWdQDTaaeS589EgKcwg==" Origin="DOH" Destination="BOM" DepartureTime="2015-12-30T20:20:00.000+03:00" ArrivalTime="2015-12-31T02:00:00.000+05:30" FlightTime="190" TravelTime="995" Equipment="77W" DestinationTerminal="2"/>
                <air:FlightDetails Key="61vx6xLsTQGyXPbxuKAxKA==" Origin="CMB" Destination="DOH" DepartureTime="2015-12-30T04:10:00.000+05:30" ArrivalTime="2015-12-30T06:40:00.000+03:00" FlightTime="300" TravelTime="1310" Equipment="346"/>
                <air:FlightDetails Key="54b5mD+sQzyseJVQN+7pkw==" Origin="CMB" Destination="COK" DepartureTime="2015-12-30T07:25:00.000+05:30" ArrivalTime="2015-12-30T08:45:00.000+05:30" FlightTime="80" TravelTime="395" Equipment="320"/>
                <air:FlightDetails Key="hF/u963UR/248BmxV+qSLg==" Origin="COK" Destination="BOM" DepartureTime="2015-12-30T12:05:00.000+05:30" ArrivalTime="2015-12-30T14:00:00.000+05:30" FlightTime="115" TravelTime="395" Equipment="738" DestinationTerminal="1B"/>
                <air:FlightDetails Key="D80dvTESTlmCE4wL8QqYdQ==" Origin="DEL" Destination="BOM" DepartureTime="2015-12-30T15:20:00.000+05:30" ArrivalTime="2015-12-30T17:35:00.000+05:30" FlightTime="135" TravelTime="555" Equipment="737" OriginTerminal="3" DestinationTerminal="1B"/>
                <air:FlightDetails Key="TtjbKKPgQh+z3u8N95LBzg==" Origin="DEL" Destination="BOM" DepartureTime="2015-12-30T16:20:00.000+05:30" ArrivalTime="2015-12-30T18:30:00.000+05:30" FlightTime="130" TravelTime="610" Equipment="737" OriginTerminal="3" DestinationTerminal="1B"/>
                <air:FlightDetails Key="OoYnH3C8Q3OMEmNSi2BA9Q==" Origin="CMB" Destination="KUL" DepartureTime="2015-12-30T07:30:00.000+05:30" ArrivalTime="2015-12-30T13:45:00.000+08:00" FlightTime="225" TravelTime="925" Equipment="321" DestinationTerminal="M"/>
                <air:FlightDetails Key="9DCvbqhxQ7KloSzDHY0tPw==" Origin="KUL" Destination="BOM" DepartureTime="2015-12-30T20:30:00.000+08:00" ArrivalTime="2015-12-30T22:55:00.000+05:30" FlightTime="295" TravelTime="925" Equipment="738" OriginTerminal="M" DestinationTerminal="2"/>
                <air:FlightDetails Key="vzcf3o9PS8uCRVKTw20TiQ==" Origin="KUL" Destination="BOM" DepartureTime="2015-12-30T23:00:00.000+08:00" ArrivalTime="2015-12-31T01:25:00.000+05:30" FlightTime="295" TravelTime="1075" Equipment="738" OriginTerminal="M" DestinationTerminal="2"/>
                <air:FlightDetails Key="YWSjKBJfQi2GFtnSn/yJLw==" Origin="CMB" Destination="KUL" DepartureTime="2015-12-30T01:00:00.000+05:30" ArrivalTime="2015-12-30T07:10:00.000+08:00" FlightTime="220" TravelTime="1315" Equipment="738" DestinationTerminal="M"/>
                <air:FlightDetails Key="0y25fKM8TqiaJcMVohn+MQ==" Origin="CMB" Destination="AUH" DepartureTime="2015-12-30T21:00:00.000+05:30" ArrivalTime="2015-12-31T00:25:00.000+04:00" FlightTime="295" TravelTime="675" Equipment="320" DestinationTerminal="1"/>
                <air:FlightDetails Key="DsCy/NycSNKzFJInSpDHZw==" Origin="AUH" Destination="BOM" DepartureTime="2015-12-31T03:10:00.000+04:00" ArrivalTime="2015-12-31T08:15:00.000+05:30" FlightTime="215" TravelTime="675" Equipment="320" OriginTerminal="1" DestinationTerminal="2"/>
                <air:FlightDetails Key="fEpXJxf1R92OmQw1Pgh09A==" Origin="CMB" Destination="AUH" DepartureTime="2015-12-30T18:45:00.000+05:30" ArrivalTime="2015-12-30T22:00:00.000+04:00" FlightTime="285" TravelTime="810" Equipment="320" DestinationTerminal="1"/>
                <air:FlightDetails Key="zONv786DRvWx+u7LARCOXg==" Origin="AUH" Destination="BOM" DepartureTime="2015-12-31T08:25:00.000+04:00" ArrivalTime="2015-12-31T12:55:00.000+05:30" FlightTime="180" TravelTime="955" Equipment="737" OriginTerminal="1" DestinationTerminal="2"/>
                <air:FlightDetails Key="ReYGpUqjQa+LyxOic0Blig==" Origin="AUH" Destination="BOM" DepartureTime="2015-12-31T21:45:00.000+04:00" ArrivalTime="2016-01-01T02:50:00.000+05:30" FlightTime="215" TravelTime="1790" Equipment="77W" OriginTerminal="1" DestinationTerminal="2"/>
                <air:FlightDetails Key="mbin54C5SUW81dWAceJMfA==" Origin="CMB" Destination="HKG" DepartureTime="2015-12-30T00:40:00.000+05:30" ArrivalTime="2015-12-30T08:25:00.000+08:00" FlightTime="315" TravelTime="1415" Equipment="333" DestinationTerminal="1"/>
                <air:FlightDetails Key="WxAhl+FiSVSOYab23m3EaA==" Origin="HKG" Destination="BOM" DepartureTime="2015-12-30T20:00:00.000+08:00" ArrivalTime="2015-12-31T00:15:00.000+05:30" FlightTime="405" TravelTime="1415" Equipment="333" OriginTerminal="1" DestinationTerminal="2"/>
            </air:FlightDetailsList>
            <air:AirSegmentList>
                <air:AirSegment Key="rYIAyCVbSWyf2aEzw5sX8w==" Group="0" Carrier="AI" FlightNumber="274" Origin="CMB" Destination="MAA" DepartureTime="2015-12-30T18:30:00.000+05:30" ArrivalTime="2015-12-30T19:50:00.000+05:30" FlightTime="80" Distance="402" ETicketability="Yes" Equipment="321" ChangeOfPlane="false" OptionalServicesIndicator="false">
                    <air:AirAvailInfo ProviderCode="1P">
                        <air:BookingCodeInfo BookingCounts="B4|C4|D4|E4|G4|H4|J4|K4|L4|M4|N4|Q4|S4|T4|U4|V4|W4|Y9|Z4"/>
                    </air:AirAvailInfo>
                    <air:FlightDetailsRef Key="d/xPJlHqRq+oR2sTQiFgyw==" />
                </air:AirSegment>
                <air:AirSegment Key="pmYvnWp2QG+fbaoczQUyyw==" Group="0" Carrier="AI" FlightNumber="569" Origin="MAA" Destination="BOM" DepartureTime="2015-12-31T06:20:00.000+05:30" ArrivalTime="2015-12-31T08:10:00.000+05:30" FlightTime="110" Distance="644" ETicketability="Yes" Equipment="319" ChangeOfPlane="false" OptionalServicesIndicator="false">
                    <air:AirAvailInfo ProviderCode="1P">
                        <air:BookingCodeInfo BookingCounts="B4|C4|D4|E4|G4|H4|J4|K4|L4|M4|N4|Q4|S4|T4|U4|V4|W4|Y9|Z4"/>
                    </air:AirAvailInfo>
                    <air:FlightDetailsRef Key="xU8001M/Qe61/805PDnA0g=="/>
                </air:AirSegment>
                <air:AirSegment Key="3JPZrzd/RpOA49xHkh6AqQ==" Group="0" Carrier="MJ" FlightNumber="2141" Origin="CMB" Destination="BOM" DepartureTime="2015-12-30T23:45:00.000+05:30" ArrivalTime="2015-12-31T02:10:00.000+05:30" FlightTime="145" Distance="948" ETicketability="Yes" Equipment="320" ChangeOfPlane="false" OptionalServicesIndicator="false">
                    <air:CodeshareInfo OperatingCarrier="UL" OperatingFlightNumber="141">SRILANKAN AIRLINES</air:CodeshareInfo>
                    <air:AirAvailInfo ProviderCode="1P">
                        <air:BookingCodeInfo BookingCounts="B4|C4|D4|E4|H4|I4|J4|K4|L4|M4|N4|P4|R4|S4|V4|W4|Y4"/>
                    </air:AirAvailInfo>
                    <air:FlightDetailsRef Key="ZbwpPYQJRkqyeSamQbuUiA=="/>
                </air:AirSegment>
                <air:AirSegment Key="bGngmU8aRG2A+x16m+lJ7g==" Group="0" Carrier="AI" FlightNumber="282" Origin="CMB" Destination="DEL" DepartureTime="2015-12-30T08:20:00.000+05:30" ArrivalTime="2015-12-30T12:10:00.000+05:30" FlightTime="230" Distance="1489" ETicketability="Yes" Equipment="321" ChangeOfPlane="false" OptionalServicesIndicator="false">
                    <air:AirAvailInfo ProviderCode="1P">
                        <air:BookingCodeInfo BookingCounts="B4|C4|D4|E4|G4|H4|J4|K4|L4|M4|N4|Q4|S4|T4|U4|V4|W4|Y9|Z4"/>
                    </air:AirAvailInfo>
                    <air:FlightDetailsRef Key="Gd2Jc0QrTiiQAGIMXHoPlg=="/>
                </air:AirSegment>
                <air:AirSegment Key="3fJQkrKcTKaH3JlToYMbkA==" Group="0" Carrier="AI" FlightNumber="102" Origin="DEL" Destination="BOM" DepartureTime="2015-12-30T16:45:00.000+05:30" ArrivalTime="2015-12-30T18:55:00.000+05:30" FlightTime="130" Distance="708" ETicketability="Yes" Equipment="77W" ChangeOfPlane="false" OptionalServicesIndicator="false">
                    <air:AirAvailInfo ProviderCode="1P">
                        <air:BookingCodeInfo BookingCounts="A4|B4|C4|D4|E4|F4|G4|H4|J4|K4|L4|M4|N4|Q4|S4|T4|U4|V4|W4|Y9|Z4"/>
                    </air:AirAvailInfo>
                    <air:FlightDetailsRef Key="U7G2bGx2Q+uUKH1CBLXX/A=="/>
                </air:AirSegment>
                <air:AirSegment Key="Sdi8wCXdTiuhbSPyjKtnYw==" Group="0" Carrier="AI" FlightNumber="659" Origin="DEL" Destination="BOM" DepartureTime="2015-12-30T17:00:00.000+05:30" ArrivalTime="2015-12-30T19:20:00.000+05:30" FlightTime="140" Distance="708" ETicketability="Yes" Equipment="321" ChangeOfPlane="false" OptionalServicesIndicator="false">
                    <air:AirAvailInfo ProviderCode="1P">
                        <air:BookingCodeInfo BookingCounts="B4|C4|D4|E4|G4|H4|J4|K4|L4|M4|N4|Q4|S4|T4|U4|V4|W4|Y9|Z4"/>
                    </air:AirAvailInfo>
                    <air:FlightDetailsRef Key="cZs+vjH+RMiAxMREYz/Deg=="/>
                </air:AirSegment>
                <air:AirSegment Key="zTiMUOfiTJui9rbVWexUBw==" Group="0" Carrier="TG" FlightNumber="308" Origin="CMB" Destination="BKK" DepartureTime="2015-12-30T01:30:00.000+05:30" ArrivalTime="2015-12-30T06:25:00.000+07:00" FlightTime="205" Distance="1485" ETicketability="Yes" Equipment="777" ChangeOfPlane="false" OptionalServicesIndicator="false">
                    <air:AirAvailInfo ProviderCode="1P">
                        <air:BookingCodeInfo BookingCounts="C9|D9|J9|Z9|I6|R5|Y9|B9|M9|H9|Q9|T9|K9|S9|X9|V9"/>
                    </air:AirAvailInfo>
                    <air:FlightDetailsRef Key="HdS0zPwDRE+q3hfUD6z78Q=="/>
                </air:AirSegment>
                <air:AirSegment Key="R8aq8iGWSvKKRkSXphzv+Q==" Group="0" Carrier="TG" FlightNumber="317" Origin="BKK" Destination="BOM" DepartureTime="2015-12-30T18:55:00.000+07:00" ArrivalTime="2015-12-30T22:00:00.000+05:30" FlightTime="275" Distance="1878" ETicketability="Yes" Equipment="744" ChangeOfPlane="false" OptionalServicesIndicator="false">
                    <air:AirAvailInfo ProviderCode="1P">
                        <air:BookingCodeInfo BookingCounts="C9|D5|Y9|B9|M9|H9|Q9|T9|K9|S9|X9|V9|W9|N9"/>
                    </air:AirAvailInfo>
                    <air:FlightDetailsRef Key="cAfVJ4/bTAqImb+c19OqRQ=="/>
                </air:AirSegment>
                <air:AirSegment Key="4YD0h9VDQAm0Zcvf05dh3g==" Group="0" Carrier="KU" FlightNumber="362" Origin="CMB" Destination="KWI" DepartureTime="2015-12-30T05:45:00.000+05:30" ArrivalTime="2015-12-30T08:50:00.000+03:00" FlightTime="335" Distance="2573" ETicketability="Yes" Equipment="340" ChangeOfPlane="false" OptionalServicesIndicator="false">
                    <air:AirAvailInfo ProviderCode="1P">
                        <air:BookingCodeInfo BookingCounts="J9|C7|Y9|N9|K9|M9|B9|H9|L9|T9|V9"/>
                    </air:AirAvailInfo>
                    <air:FlightDetailsRef Key="/JWxfWPsR9OBvHrX6PMhvw=="/>
                </air:AirSegment>
                <air:AirSegment Key="y0GmFDjrREajR6dLHnJwzw==" Group="0" Carrier="KU" FlightNumber="301" Origin="KWI" Destination="BOM" DepartureTime="2015-12-30T22:10:00.000+03:00" ArrivalTime="2015-12-31T04:30:00.000+05:30" FlightTime="230" Distance="1714" ETicketability="Yes" Equipment="747" ChangeOfPlane="false" OptionalServicesIndicator="false">
                    <air:AirAvailInfo ProviderCode="1P">
                        <air:BookingCodeInfo BookingCounts="J9|C9|Y9|N9|K9|M9|B9|H9|L9"/>
                    </air:AirAvailInfo>
                    <air:FlightDetailsRef Key="ZlTb3cBfQFautbZZ3Exluw=="/>
                </air:AirSegment>
                <air:AirSegment Key="i0kd15qYQ0ycOH9T8F1TSw==" Group="0" Carrier="SV" FlightNumber="789" Origin="CMB" Destination="JED" DepartureTime="2015-12-30T13:10:00.000+05:30" ArrivalTime="2015-12-30T18:50:00.000+03:00" FlightTime="490" Distance="2886" ETicketability="Yes" Equipment="744" ChangeOfPlane="false" OptionalServicesIndicator="false" NumberOfStops="1">
                    <air:AirAvailInfo ProviderCode="1P">
                        <air:BookingCodeInfo BookingCounts="J9|C9|D9|I9|Y9|E9|B9|M9|K9|H9|L9|Q9|T9|N9|V9"/>
                    </air:AirAvailInfo>
                    <air:FlightDetailsRef Key="TEPWnCHeSIKj0P7vJ/RWFw=="/>
                    <air:FlightDetailsRef Key="qi+qIBnCSrSc6Hw7FxUahA=="/>
                </air:AirSegment>
                <air:AirSegment Key="yQBXiAd5RyOPEd0fgQM+kw==" Group="0" Carrier="SV" FlightNumber="744" Origin="JED" Destination="BOM" DepartureTime="2015-12-31T00:35:00.000+03:00" ArrivalTime="2015-12-31T07:30:00.000+05:30" FlightTime="265" Distance="2185" ETicketability="Yes" Equipment="772" ChangeOfPlane="false" OptionalServicesIndicator="false">
                    <air:AirAvailInfo ProviderCode="1P">
                        <air:BookingCodeInfo BookingCounts="J9|C7|D4|I4|Y9|E9|B9|M9|K9|H9|L9|Q9|T9|N9|V9"/>
                    </air:AirAvailInfo>
                    <air:FlightDetailsRef Key="J5nzS5vQSImaemg/iN0VZA=="/>
                </air:AirSegment>
                <air:AirSegment Key="uoPfx/zGQDqkCyPw/NqJAQ==" Group="0" Carrier="UL" FlightNumber="173" Origin="CMB" Destination="BLR" DepartureTime="2015-12-30T01:15:00.000+05:30" ArrivalTime="2015-12-30T02:35:00.000+05:30" FlightTime="80" Distance="442" ETicketability="Yes" Equipment="321" ChangeOfPlane="false" OptionalServicesIndicator="false">
                    <air:CodeshareInfo>UL USING MIHIN ACFT</air:CodeshareInfo>
                    <air:AirAvailInfo ProviderCode="1P">
                        <air:BookingCodeInfo BookingCounts="B7|C4|D4|E4|H7|I4|J4|K7|L7|M7|N7|P4|R4|S4|V7|W4|Y7"/>
                    </air:AirAvailInfo>
                    <air:FlightDetailsRef Key="LxdpuAhWR+adm3lmPhAo1Q=="/>
                </air:AirSegment>
                <air:AirSegment Key="xSFWvGYvReW0atzYedX1Zg==" Group="0" Carrier="9W" FlightNumber="410" Origin="BLR" Destination="BOM" DepartureTime="2015-12-30T05:50:00.000+05:30" ArrivalTime="2015-12-30T07:25:00.000+05:30" FlightTime="95" Distance="519" ETicketability="Yes" Equipment="738" ChangeOfPlane="false" OptionalServicesIndicator="false">
                    <air:AirAvailInfo ProviderCode="1P">
                        <air:BookingCodeInfo BookingCounts="C7|J7|Z7|I7|P7|Y7|M7|T7|U7|N7|L7|Q7|S7|K7|H7|V7|O7|W7|B7"/>
                    </air:AirAvailInfo>
                    <air:FlightDetailsRef Key="0tDsNA5EQLSxvRIKRhc4OA=="/>
                </air:AirSegment>
                <air:AirSegment Key="iwp/RLWMQWCI8CGA/rA/vw==" Group="0" Carrier="UL" FlightNumber="127" Origin="CMB" Destination="MAA" DepartureTime="2015-12-30T13:45:00.000+05:30" ArrivalTime="2015-12-30T15:05:00.000+05:30" FlightTime="80" Distance="402" ETicketability="Yes" Equipment="332" ChangeOfPlane="false" OptionalServicesIndicator="false">
                    <air:AirAvailInfo ProviderCode="1P">
                        <air:BookingCodeInfo BookingCounts="B7|C4|D4|E4|H7|I4|J4|K7|L7|M7|N7|P4|Q4|R4|S4|V7|W4|Y7"/>
                    </air:AirAvailInfo>
                    <air:FlightDetailsRef Key="LShq2hp2S1enmJcTPGp34Q=="/>
                </air:AirSegment>
                <air:AirSegment Key="0dXq6wzQS8qioSvEm2D7Yw==" Group="0" Carrier="9W" FlightNumber="468" Origin="MAA" Destination="BOM" DepartureTime="2015-12-30T18:10:00.000+05:30" ArrivalTime="2015-12-30T20:00:00.000+05:30" FlightTime="110" Distance="644" ETicketability="Yes" Equipment="738" ChangeOfPlane="false" OptionalServicesIndicator="false">
                    <air:AirAvailInfo ProviderCode="1P">
                        <air:BookingCodeInfo BookingCounts="C6|J4|Z1|Y7|M7|T7|U7|N7|L7"/>
                    </air:AirAvailInfo>
                    <air:FlightDetailsRef Key="bkrj3iUdTTeoORVLHHreOQ=="/>
                </air:AirSegment>
                <air:AirSegment Key="AT+tbEWhSbyFbRY6bSRzeA==" Group="0" Carrier="UL" FlightNumber="121" Origin="CMB" Destination="MAA" DepartureTime="2015-12-30T07:20:00.000+05:30" ArrivalTime="2015-12-30T08:40:00.000+05:30" FlightTime="80" Distance="402" ETicketability="Yes" Equipment="332" ChangeOfPlane="false" OptionalServicesIndicator="false">
                    <air:AirAvailInfo ProviderCode="1P">
                        <air:BookingCodeInfo BookingCounts="B7|C4|D4|E4|H7|I4|J4|K7|L7|M7|N7|P4|R4|S4|V7|W4|Y7"/>
                    </air:AirAvailInfo>
                    <air:FlightDetailsRef Key="VRrV12IATP2GQ3DMVdGM+Q=="/>
                </air:AirSegment>
                <air:AirSegment Key="X557SugmQIye+lQk5tLFYg==" Group="0" Carrier="9W" FlightNumber="460" Origin="MAA" Destination="BOM" DepartureTime="2015-12-30T11:25:00.000+05:30" ArrivalTime="2015-12-30T13:15:00.000+05:30" FlightTime="110" Distance="644" ETicketability="Yes" Equipment="737" ChangeOfPlane="false" OptionalServicesIndicator="false">
                    <air:AirAvailInfo ProviderCode="1P">
                        <air:BookingCodeInfo BookingCounts="C7|J7|Z7|I7|P7|Y7|M7|T7|U7|N7|L7|Q7|S7|K7"/>
                    </air:AirAvailInfo>
                    <air:FlightDetailsRef Key="RJb0aNsJRHmp9w4uLMAZ4Q=="/>
                </air:AirSegment>
                <air:AirSegment Key="v9852ceNTGOhkPNvi3XeVw==" Group="0" Carrier="UL" FlightNumber="171" Origin="CMB" Destination="BLR" DepartureTime="2015-12-30T18:45:00.000+05:30" ArrivalTime="2015-12-30T20:05:00.000+05:30" FlightTime="80" Distance="442" ETicketability="Yes" Equipment="320" ChangeOfPlane="false" OptionalServicesIndicator="false">
                    <air:AirAvailInfo ProviderCode="1P">
                        <air:BookingCodeInfo BookingCounts="B7|C4|D4|E4|H7|I4|J4|K7|L7|M7|P4|R4|V7|W4|Y7"/>
                    </air:AirAvailInfo>
                    <air:FlightDetailsRef Key="UoxYxq60QYmHLqRbdGhgjQ=="/>
                </air:AirSegment>
                <air:AirSegment Key="DDa84luNRoKfbkAuqZ2ejQ==" Group="0" Carrier="9W" FlightNumber="382" Origin="BLR" Destination="BOM" DepartureTime="2015-12-30T21:45:00.000+05:30" ArrivalTime="2015-12-30T23:20:00.000+05:30" FlightTime="95" Distance="519" ETicketability="Yes" Equipment="737" ChangeOfPlane="false" OptionalServicesIndicator="false">
                    <air:AirAvailInfo ProviderCode="1P">
                        <air:BookingCodeInfo BookingCounts="C7|J6|Z3|Y7|M7|T7|U7|N7|L7|Q7|S7|K7|B7"/>
                    </air:AirAvailInfo>
                    <air:FlightDetailsRef Key="qJTmznqESIiptpB0lIksdA=="/>
                </air:AirSegment>
                <air:AirSegment Key="Z5+/4AJQR0ii4q6qeQF89A==" Group="0" Carrier="QR" FlightNumber="665" Origin="CMB" Destination="DOH" DepartureTime="2015-12-30T09:25:00.000+05:30" ArrivalTime="2015-12-30T11:55:00.000+03:00" FlightTime="300" Distance="2245" ETicketability="Yes" Equipment="346" ChangeOfPlane="false" OptionalServicesIndicator="false">
                    <air:AirAvailInfo ProviderCode="1P">
                        <air:BookingCodeInfo BookingCounts="J9|C9|D9|I9|Y9|B9|H9|K9|M9|L9|V9|S9|N9|Q9|T9|O9"/>
                    </air:AirAvailInfo>
                    <air:FlightDetailsRef Key="uVayohQfRDOOsajbTPItTA=="/>
                </air:AirSegment>
                <air:AirSegment Key="2nP9/I8DR9W7IKKexlMeCw==" Group="0" Carrier="QR" FlightNumber="556" Origin="DOH" Destination="BOM" DepartureTime="2015-12-30T20:20:00.000+03:00" ArrivalTime="2015-12-31T02:00:00.000+05:30" FlightTime="190" Distance="1424" ETicketability="Yes" Equipment="77W" ChangeOfPlane="false" OptionalServicesIndicator="false">
                    <air:AirAvailInfo ProviderCode="1P">
                        <air:BookingCodeInfo BookingCounts="J9|C9|D9|I9|Y9|B9|H9|K9|M9|L9|V9|S9|N9|Q9|T9|O9"/>
                    </air:AirAvailInfo>
                    <air:FlightDetailsRef Key="t4xQWdQDTaaeS589EgKcwg=="/>
                </air:AirSegment>
                <air:AirSegment Key="Tq39Si3AQyekAcRGS8PJGg==" Group="0" Carrier="QR" FlightNumber="669" Origin="CMB" Destination="DOH" DepartureTime="2015-12-30T04:10:00.000+05:30" ArrivalTime="2015-12-30T06:40:00.000+03:00" FlightTime="300" Distance="2245" ETicketability="Yes" Equipment="346" ChangeOfPlane="false" OptionalServicesIndicator="false">
                    <air:AirAvailInfo ProviderCode="1P">
                        <air:BookingCodeInfo BookingCounts="J9|C9|D9|I9|Y9|B9|H9|K9|M9|L9|V9|S9|N9|Q9|T9|O9"/>
                    </air:AirAvailInfo>
                    <air:FlightDetailsRef Key="61vx6xLsTQGyXPbxuKAxKA=="/>
                </air:AirSegment>
                <air:AirSegment Key="6jtrWUvfRyWBFbaHacrvkQ==" Group="0" Carrier="UL" FlightNumber="165" Origin="CMB" Destination="COK" DepartureTime="2015-12-30T07:25:00.000+05:30" ArrivalTime="2015-12-30T08:45:00.000+05:30" FlightTime="80" Distance="312" ETicketability="Yes" Equipment="320" ChangeOfPlane="false" OptionalServicesIndicator="false">
                    <air:AirAvailInfo ProviderCode="1P">
                        <air:BookingCodeInfo BookingCounts="B7|C4|D4|E4|H7|I4|J4|K7|M7|P4|W4|Y7"/>
                    </air:AirAvailInfo>
                    <air:FlightDetailsRef Key="54b5mD+sQzyseJVQN+7pkw=="/>
                </air:AirSegment>
                <air:AirSegment Key="CJjDsTGwT4C3Go8a7dqkMw==" Group="0" Carrier="9W" FlightNumber="408" Origin="COK" Destination="BOM" DepartureTime="2015-12-30T12:05:00.000+05:30" ArrivalTime="2015-12-30T14:00:00.000+05:30" FlightTime="115" Distance="672" ETicketability="Yes" Equipment="738" ChangeOfPlane="false" OptionalServicesIndicator="false">
                    <air:AirAvailInfo ProviderCode="1P">
                        <air:BookingCodeInfo BookingCounts="C7|J7|Z7|I7|P7|Y7|M7|T7|U7|N7|L7|B7"/>
                    </air:AirAvailInfo>
                    <air:FlightDetailsRef Key="hF/u963UR/248BmxV+qSLg=="/>
                </air:AirSegment>
                <air:AirSegment Key="Pdxf+tBEQ5mSfJwlX48bAA==" Group="0" Carrier="9W" FlightNumber="308" Origin="DEL" Destination="BOM" DepartureTime="2015-12-30T15:20:00.000+05:30" ArrivalTime="2015-12-30T17:35:00.000+05:30" FlightTime="135" Distance="708" ETicketability="Yes" Equipment="737" ChangeOfPlane="false" OptionalServicesIndicator="false">
                    <air:AirAvailInfo ProviderCode="1P">
                        <air:BookingCodeInfo BookingCounts="C7|J7|Z7|I7|P6|Y7|M7|T7|U7|N7|L7|Q7|S7|K7|H7|V7|B7"/>
                    </air:AirAvailInfo>
                    <air:FlightDetailsRef Key="D80dvTESTlmCE4wL8QqYdQ=="/>
                </air:AirSegment>
                <air:AirSegment Key="vU/ihTCAQZiQQTngPDUJzg==" Group="0" Carrier="9W" FlightNumber="373" Origin="DEL" Destination="BOM" DepartureTime="2015-12-30T16:20:00.000+05:30" ArrivalTime="2015-12-30T18:30:00.000+05:30" FlightTime="130" Distance="708" ETicketability="Yes" Equipment="737" ChangeOfPlane="false" OptionalServicesIndicator="false">
                    <air:AirAvailInfo ProviderCode="1P">
                        <air:BookingCodeInfo BookingCounts="C4|J3|Z2|Y7|M7|T7|U7|N7|L7|Q7|S7|K7|H7|V7|B7"/>
                    </air:AirAvailInfo>
                    <air:FlightDetailsRef Key="TtjbKKPgQh+z3u8N95LBzg=="/>
                </air:AirSegment>
                <air:AirSegment Key="ywi0//OKShCCi2dewirAew==" Group="0" Carrier="MH" FlightNumber="9008" Origin="CMB" Destination="KUL" DepartureTime="2015-12-30T07:30:00.000+05:30" ArrivalTime="2015-12-30T13:45:00.000+08:00" FlightTime="225" Distance="1526" ETicketability="Yes" Equipment="321" ChangeOfPlane="false" OptionalServicesIndicator="false">
                    <air:CodeshareInfo OperatingCarrier="UL" OperatingFlightNumber="314">SRILANKAN AIRLINES</air:CodeshareInfo>
                    <air:AirAvailInfo ProviderCode="1P">
                        <air:BookingCodeInfo BookingCounts="B9|C4|D4|H9|J4|K9|L9|M9|N9|S9|V9|Y9"/>
                    </air:AirAvailInfo>
                    <air:FlightDetailsRef Key="OoYnH3C8Q3OMEmNSi2BA9Q=="/>
                </air:AirSegment>
                <air:AirSegment Key="Vt1iQ+1vTeeVu0DRlaabuA==" Group="0" Carrier="MH" FlightNumber="194" Origin="KUL" Destination="BOM" DepartureTime="2015-12-30T20:30:00.000+08:00" ArrivalTime="2015-12-30T22:55:00.000+05:30" FlightTime="295" Distance="2241" ETicketability="Yes" Equipment="738" ChangeOfPlane="false" OptionalServicesIndicator="false">
                    <air:AirAvailInfo ProviderCode="1P">
                        <air:BookingCodeInfo BookingCounts="B9|C4|D4|G9|H9|I4|J4|K9|L9|M9|N9|O9|Q9|S9|V9|Y9|Z4"/>
                    </air:AirAvailInfo>
                    <air:FlightDetailsRef Key="9DCvbqhxQ7KloSzDHY0tPw=="/>
                </air:AirSegment>
                <air:AirSegment Key="DbdFbSZDTvSUvqfkkFKkWQ==" Group="0" Carrier="MH" FlightNumber="186" Origin="KUL" Destination="BOM" DepartureTime="2015-12-30T23:00:00.000+08:00" ArrivalTime="2015-12-31T01:25:00.000+05:30" FlightTime="295" Distance="2241" ETicketability="Yes" Equipment="738" ChangeOfPlane="false" OptionalServicesIndicator="false">
                    <air:AirAvailInfo ProviderCode="1P">
                        <air:BookingCodeInfo BookingCounts="B9|C4|D4|G9|H9|I4|J4|K9|L9|M9|N9|O9|Q9|S9|V9|Y9|Z4"/>
                    </air:AirAvailInfo>
                    <air:FlightDetailsRef Key="vzcf3o9PS8uCRVKTw20TiQ=="/>
                </air:AirSegment>
                <air:AirSegment Key="RJWgTt4vSueHj9wb584AKg==" Group="0" Carrier="MH" FlightNumber="178" Origin="CMB" Destination="KUL" DepartureTime="2015-12-30T01:00:00.000+05:30" ArrivalTime="2015-12-30T07:10:00.000+08:00" FlightTime="220" Distance="1526" ETicketability="Yes" Equipment="738" ChangeOfPlane="false" OptionalServicesIndicator="false">
                    <air:AirAvailInfo ProviderCode="1P">
                        <air:BookingCodeInfo BookingCounts="B9|C4|D4|G9|H9|I4|J4|K9|L9|M9|N9|O9|Q9|S9|V9|Y9|Z4"/>
                    </air:AirAvailInfo>
                    <air:FlightDetailsRef Key="YWSjKBJfQi2GFtnSn/yJLw=="/>
                </air:AirSegment>
                <air:AirSegment Key="+MMO5rAESiOCeShQny3Syw==" Group="0" Carrier="EY" FlightNumber="265" Origin="CMB" Destination="AUH" DepartureTime="2015-12-30T21:00:00.000+05:30" ArrivalTime="2015-12-31T00:25:00.000+04:00" FlightTime="295" Distance="2061" ETicketability="Yes" Equipment="320" ChangeOfPlane="false" OptionalServicesIndicator="false">
                    <air:AirAvailInfo ProviderCode="1P">
                        <air:BookingCodeInfo BookingCounts="B7|C7|D7|H7|J7|K7|W7|Y7|Z7"/>
                    </air:AirAvailInfo>
                    <air:FlightDetailsRef Key="0y25fKM8TqiaJcMVohn+MQ=="/>
                </air:AirSegment>
                <air:AirSegment Key="a4AcYah5RECT8wBNkwKrDA==" Group="0" Carrier="EY" FlightNumber="212" Origin="AUH" Destination="BOM" DepartureTime="2015-12-31T03:10:00.000+04:00" ArrivalTime="2015-12-31T08:15:00.000+05:30" FlightTime="215" Distance="1237" ETicketability="Yes" Equipment="320" ChangeOfPlane="false" OptionalServicesIndicator="false">
                    <air:AirAvailInfo ProviderCode="1P">
                        <air:BookingCodeInfo BookingCounts="B7|C7|D7|H7|J7|K7|W7|Y7|Z7"/>
                    </air:AirAvailInfo>
                    <air:FlightDetailsRef Key="DsCy/NycSNKzFJInSpDHZw=="/>
                </air:AirSegment>
                <air:AirSegment Key="pZFnN2jITn6KXZDFiZBERA==" Group="0" Carrier="EY" FlightNumber="7153" Origin="CMB" Destination="AUH" DepartureTime="2015-12-30T18:45:00.000+05:30" ArrivalTime="2015-12-30T22:00:00.000+04:00" FlightTime="285" Distance="2061" ETicketability="Yes" Equipment="320" ChangeOfPlane="false" OptionalServicesIndicator="false">
                    <air:CodeshareInfo OperatingCarrier="UL" OperatingFlightNumber="207">SRILANKAN AIRLINES</air:CodeshareInfo>
                    <air:AirAvailInfo ProviderCode="1P">
                        <air:BookingCodeInfo BookingCounts="B4|Y4"/>
                    </air:AirAvailInfo>
                    <air:FlightDetailsRef Key="fEpXJxf1R92OmQw1Pgh09A=="/>
                </air:AirSegment>
                <air:AirSegment Key="Y8S7pDkVQiacyI4ZHq2ZCw==" Group="0" Carrier="HM" FlightNumber="5523" Origin="CMB" Destination="AUH" DepartureTime="2015-12-30T21:00:00.000+05:30" ArrivalTime="2015-12-31T00:25:00.000+04:00" FlightTime="295" Distance="2061" ETicketability="Yes" Equipment="320" ChangeOfPlane="false" OptionalServicesIndicator="false">
                    <air:CodeshareInfo OperatingCarrier="EY" OperatingFlightNumber="265">ETIHAD AIRWAYS</air:CodeshareInfo>
                    <air:AirAvailInfo ProviderCode="1P">
                        <air:BookingCodeInfo BookingCounts="B9|C4|D4|G9|H9|I4|J4|K9|L9|M9|N9|S9|V9|W9|Y9"/>
                    </air:AirAvailInfo>
                    <air:FlightDetailsRef Key="0y25fKM8TqiaJcMVohn+MQ=="/>
                </air:AirSegment>
                <air:AirSegment Key="MOByCm8BQU+AFxqXfWndBA==" Group="0" Carrier="HM" FlightNumber="5320" Origin="AUH" Destination="BOM" DepartureTime="2015-12-31T03:10:00.000+04:00" ArrivalTime="2015-12-31T08:15:00.000+05:30" FlightTime="215" Distance="1237" ETicketability="Yes" Equipment="320" ChangeOfPlane="false" OptionalServicesIndicator="false">
                    <air:CodeshareInfo OperatingCarrier="EY" OperatingFlightNumber="212">ETIHAD AIRWAYS</air:CodeshareInfo>
                    <air:AirAvailInfo ProviderCode="1P">
                        <air:BookingCodeInfo BookingCounts="B9|C4|D4|G9|H9|I4|J4|K9|L9|M9|N9|S9|V9|W9|Y9"/>
                    </air:AirAvailInfo>
                    <air:FlightDetailsRef Key="DsCy/NycSNKzFJInSpDHZw=="/>
                </air:AirSegment>
                <air:AirSegment Key="4+oO8WgRT9CHPFB2KrxN0Q==" Group="0" Carrier="HM" FlightNumber="5560" Origin="AUH" Destination="BOM" DepartureTime="2015-12-31T08:25:00.000+04:00" ArrivalTime="2015-12-31T12:55:00.000+05:30" FlightTime="180" Distance="1237" ETicketability="Yes" Equipment="737" ChangeOfPlane="false" OptionalServicesIndicator="false">
                    <air:CodeshareInfo OperatingCarrier="9W" OperatingFlightNumber="585">JET*AIRWAYS*INDIA*LTD</air:CodeshareInfo>
                    <air:AirAvailInfo ProviderCode="1P">
                        <air:BookingCodeInfo BookingCounts="B9|C4|D4|G9|H9|I4|J4|K9|L9|M9|S9|V9|W9|Y9"/>
                    </air:AirAvailInfo>
                    <air:FlightDetailsRef Key="zONv786DRvWx+u7LARCOXg=="/>
                </air:AirSegment>
                <air:AirSegment Key="PemwJukMRtuKXefkZBLwuw==" Group="0" Carrier="HM" FlightNumber="5314" Origin="AUH" Destination="BOM" DepartureTime="2015-12-31T21:45:00.000+04:00" ArrivalTime="2016-01-01T02:50:00.000+05:30" FlightTime="215" Distance="1237" ETicketability="Yes" Equipment="77W" ChangeOfPlane="false" OptionalServicesIndicator="false">
                    <air:CodeshareInfo>ETIHAD ON JET AIRWAYS CONFIGURED AIRCRA</air:CodeshareInfo>
                    <air:AirAvailInfo ProviderCode="1P">
                        <air:BookingCodeInfo BookingCounts="B9|C4|D4|G9|H9|I4|J4|K9|L9|M9|N9|S9|V9|W9|Y9"/>
                    </air:AirAvailInfo>
                    <air:FlightDetailsRef Key="ReYGpUqjQa+LyxOic0Blig=="/>
                </air:AirSegment>
                <air:AirSegment Key="KW1Je2lyQk2n+nEBrqUX1g==" Group="0" Carrier="CX" FlightNumber="610" Origin="CMB" Destination="HKG" DepartureTime="2015-12-30T00:40:00.000+05:30" ArrivalTime="2015-12-30T08:25:00.000+08:00" FlightTime="315" Distance="2494" ETicketability="Yes" Equipment="333" ChangeOfPlane="false" OptionalServicesIndicator="false">
                    <air:AirAvailInfo ProviderCode="1P">
                        <air:BookingCodeInfo BookingCounts="J9|C9|D9|I9|Y9|B9|H9|K9|M9|L9|V9|S9|N9|Q9|O9"/>
                    </air:AirAvailInfo>
                    <air:FlightDetailsRef Key="mbin54C5SUW81dWAceJMfA=="/>
                </air:AirSegment>
                <air:AirSegment Key="GtdgjEmUSu+4+ZLUAhfkjA==" Group="0" Carrier="CX" FlightNumber="663" Origin="HKG" Destination="BOM" DepartureTime="2015-12-30T20:00:00.000+08:00" ArrivalTime="2015-12-31T00:15:00.000+05:30" FlightTime="405" Distance="2655" ETicketability="Yes" Equipment="333" ChangeOfPlane="false" OptionalServicesIndicator="false">
                    <air:AirAvailInfo ProviderCode="1P">
                        <air:BookingCodeInfo BookingCounts="J9|C9|D9|I9|Y9|B9|H9|K9|M9|L9|V9|S9|N9|Q9|O8"/>
                    </air:AirAvailInfo>
                    <air:FlightDetailsRef Key="WxAhl+FiSVSOYab23m3EaA=="/>
                </air:AirSegment>
            </air:AirSegmentList>
            <air:FareInfoList>
                <air:FareInfo Key="ckit2lp2TISiJLsf7wa0mA==" FareBasis="SOW" PassengerTypeCode="ADT" Origin="CMB" Destination="BOM" EffectiveDate="2015-12-23T04:52:00.000+00:00" DepartureDate="2015-12-30" Amount="LKR14000" NegotiatedFare="false" NotValidBefore="2015-12-31" NotValidAfter="2015-12-31">
                    <air:BaggageAllowance>
                        <air:MaxWeight Value="30" Unit="Kilograms"/>
                    </air:BaggageAllowance>
                    <air:FareRuleKey FareInfoRef="ckit2lp2TISiJLsf7wa0mA==" ProviderCode="1P">dir-CMB^BOM^2015364^ALL^SOW^AI^^</air:FareRuleKey>
                    <air:Brand Key="A6O0mnjdSgiz4hR+PxmMbQ==" BrandFound="false" UpSellBrandFound="false"/>
                </air:FareInfo>
                <air:FareInfo Key="SlxQ4fkSTuOIpPcue+S8yQ==" FareBasis="REFOWLK" PassengerTypeCode="ADT" Origin="CMB" Destination="BOM" EffectiveDate="2015-12-23T04:52:00.000+00:00" DepartureDate="2015-12-30" Amount="LKR21900" NegotiatedFare="false" NotValidBefore="2015-12-30" NotValidAfter="2015-12-30">
                    <air:BaggageAllowance>
                        <air:MaxWeight Value="30" Unit="Kilograms"/>
                    </air:BaggageAllowance>
                    <air:FareRuleKey FareInfoRef="SlxQ4fkSTuOIpPcue+S8yQ==" ProviderCode="1P">dir-CMB^BOM^2015364^ALL^REFOWLK^MJ^^</air:FareRuleKey>
                </air:FareInfo>
                <air:FareInfo Key="bj1WKyZlR4WZFWPiqwm1hQ==" FareBasis="TOWLKD" PassengerTypeCode="ADT" Origin="CMB" Destination="BOM" EffectiveDate="2015-12-23T04:52:00.000+00:00" DepartureDate="2015-12-30" Amount="LKR28000" NegotiatedFare="false" NotValidBefore="2015-12-30" NotValidAfter="2015-12-30">
                    <air:BaggageAllowance>
                        <air:MaxWeight Value="30" Unit="Kilograms"/>
                    </air:BaggageAllowance>
                    <air:FareRuleKey FareInfoRef="bj1WKyZlR4WZFWPiqwm1hQ==" ProviderCode="1P">dir-CMB^BOM^2015364^ALL^TOWLKD^AI^^</air:FareRuleKey>
                    <air:Brand Key="Gnm+w3WjQrO7/fwamp8fkw==" BrandFound="false" UpSellBrandFound="false"/>
                </air:FareInfo>
                <air:FareInfo Key="kxuUkvTTQyWnQaY9OHv1Tw==" FareBasis="VOWLKP" PassengerTypeCode="ADT" Origin="CMB" Destination="BOM" EffectiveDate="2015-12-23T04:52:00.000+00:00" DepartureDate="2015-12-30" Amount="LKR38900" NegotiatedFare="false">
                    <air:FareSurcharge Key="BHtCfhjrR8unie/sIYlOZQ==" Type="Other" Amount="NUC135.85"/>
                    <air:BaggageAllowance>
                        <air:MaxWeight Value="30" Unit="Kilograms"/>
                    </air:BaggageAllowance>
                    <air:FareRuleKey FareInfoRef="kxuUkvTTQyWnQaY9OHv1Tw==" ProviderCode="1P">dir-CMB^BOM^2015364^ALL^VOWLKP^TG^^</air:FareRuleKey>
                    <air:Brand Key="sFd5w3rvScaUepwYmQA1pg==" BrandFound="false" UpSellBrandFound="false"/>
                </air:FareInfo>
                <air:FareInfo Key="F+qvdDNTSx2L2yK3lPa42w==" FareBasis="TEE6MLK1" PassengerTypeCode="ADT" Origin="CMB" Destination="KWI" EffectiveDate="2015-12-23T04:52:00.000+00:00" DepartureDate="2015-12-30" Amount="LKR20600" NegotiatedFare="false" NotValidBefore="2015-12-30" NotValidAfter="2015-12-30">
                    <air:BaggageAllowance>
                        <air:MaxWeight Value="20" Unit="Kilograms"/>
                    </air:BaggageAllowance>
                    <air:FareRuleKey FareInfoRef="F+qvdDNTSx2L2yK3lPa42w==" ProviderCode="1P">dir-CMB^KWI^2015364^ALL^TEE6MLK1^KU^^</air:FareRuleKey>
                </air:FareInfo>
                <air:FareInfo Key="16lpSSfyQ8SoNzcX7ieDbw==" FareBasis="LRMF3M" PassengerTypeCode="ADT" Origin="KWI" Destination="BOM" EffectiveDate="2015-12-23T04:52:00.000+00:00" DepartureDate="2015-12-30" Amount="LKR19700" NegotiatedFare="false" NotValidAfter="2016-03-30">
                    <air:BaggageAllowance>
                        <air:MaxWeight Value="20" Unit="Kilograms"/>
                    </air:BaggageAllowance>
                    <air:FareRuleKey FareInfoRef="16lpSSfyQ8SoNzcX7ieDbw==" ProviderCode="1P">dir-KWI^BOM^2015364^ALL^LRMF3M^KU^^</air:FareRuleKey>
                </air:FareInfo>
                <air:FareInfo Key="3dQhk0agQxW7gEXmw+7U9w==" FareBasis="VRTSVR" PassengerTypeCode="ADT" Origin="CMB" Destination="JED" EffectiveDate="2015-12-23T04:52:00.000+00:00" DepartureDate="2015-12-30" Amount="LKR22700" NegotiatedFare="false" NotValidBefore="2015-12-30" NotValidAfter="2015-12-30">
                    <air:BaggageAllowance>
                        <air:NumberOfPieces>2</air:NumberOfPieces>
                        <air:MaxWeight/>
                    </air:BaggageAllowance>
                    <air:FareRuleKey FareInfoRef="3dQhk0agQxW7gEXmw+7U9w==" ProviderCode="1P">dir-CMB^JED^2015364^ALL^VRTSVR^SV^^</air:FareRuleKey>
                    <air:Brand Key="/c2jMI+qQ0OpoJNKk6wvKQ==" BrandFound="false" UpSellBrandFound="false"/>
                </air:FareInfo>
                <air:FareInfo Key="ejp+KjRDT1m9qxZWj/7Osg==" FareBasis="VRTSVR" PassengerTypeCode="ADT" Origin="JED" Destination="BOM" EffectiveDate="2015-12-23T04:52:00.000+00:00" DepartureDate="2015-12-30" Amount="LKR25800" NegotiatedFare="false" NotValidBefore="2015-12-31" NotValidAfter="2015-12-31">
                    <air:BaggageAllowance>
                        <air:NumberOfPieces>2</air:NumberOfPieces>
                        <air:MaxWeight/>
                    </air:BaggageAllowance>
                    <air:FareRuleKey FareInfoRef="ejp+KjRDT1m9qxZWj/7Osg==" ProviderCode="1P">dir-JED^BOM^2015364^ALL^VRTSVR^SV^^</air:FareRuleKey>
                    <air:Brand Key="RJh8sttvTACdoCeFs9/FGQ==" BrandFound="false" UpSellBrandFound="false"/>
                </air:FareInfo>
                <air:FareInfo Key="GNx2drVSQDCBzzhTrl7hug==" FareBasis="SAPOWLK" PassengerTypeCode="ADT" Origin="CMB" Destination="BLR" EffectiveDate="2015-12-23T04:52:00.000+00:00" DepartureDate="2015-12-30" Amount="LKR11500" NegotiatedFare="false" NotValidBefore="2015-12-30" NotValidAfter="2015-12-30">
                    <air:BaggageAllowance>
                        <air:MaxWeight Value="30" Unit="Kilograms"/>
                    </air:BaggageAllowance>
                    <air:FareRuleKey FareInfoRef="GNx2drVSQDCBzzhTrl7hug==" ProviderCode="1P">dir-CMB^BLR^2015364^ALL^SAPOWLK^UL^^</air:FareRuleKey>
                </air:FareInfo>
                <air:FareInfo Key="jn1Q8IJmRyeNJlP0ZKTJrQ==" FareBasis="Y2OW" PassengerTypeCode="ADT" Origin="BLR" Destination="BOM" EffectiveDate="2015-12-23T04:52:00.000+00:00" DepartureDate="2015-12-30" Amount="LKR43600" NegotiatedFare="false" NotValidBefore="2015-12-30" NotValidAfter="2015-12-30">
                    <air:FareSurcharge Key="V4m5AJ+FQNy6JkHcARMFnQ==" Type="Other" Amount="NUC20.00"/>
                    <air:BaggageAllowance>
                        <air:MaxWeight Value="30" Unit="Kilograms"/>
                    </air:BaggageAllowance>
                    <air:FareRuleKey FareInfoRef="jn1Q8IJmRyeNJlP0ZKTJrQ==" ProviderCode="1P">dir-BLR^BOM^2015364^ALL^Y2OW^9W^^</air:FareRuleKey>
                </air:FareInfo>
                <air:FareInfo Key="ElaJU5w5QpGYmxBdj0JjVA==" FareBasis="QOWLK" PassengerTypeCode="ADT" Origin="CMB" Destination="MAA" EffectiveDate="2015-12-23T04:52:00.000+00:00" DepartureDate="2015-12-30" Amount="LKR9800" NegotiatedFare="false" NotValidBefore="2015-12-30" NotValidAfter="2015-12-30">
                    <air:BaggageAllowance>
                        <air:MaxWeight Value="30" Unit="Kilograms"/>
                    </air:BaggageAllowance>
                    <air:FareRuleKey FareInfoRef="ElaJU5w5QpGYmxBdj0JjVA==" ProviderCode="1P">dir-CMB^MAA^2015364^ALL^QOWLK^UL^^</air:FareRuleKey>
                </air:FareInfo>
                <air:FareInfo Key="xjo7hbaeQIa0Nt4Y/9zldw==" FareBasis="Y2OW" PassengerTypeCode="ADT" Origin="MAA" Destination="BOM" EffectiveDate="2015-12-23T04:52:00.000+00:00" DepartureDate="2015-12-30" Amount="LKR47200" NegotiatedFare="false" NotValidBefore="2015-12-30" NotValidAfter="2015-12-30">
                    <air:FareSurcharge Key="SlyrII4PQ++pQYTuzBz8Rw==" Type="Other" Amount="NUC20.00"/>
                    <air:BaggageAllowance>
                        <air:MaxWeight Value="30" Unit="Kilograms"/>
                    </air:BaggageAllowance>
                    <air:FareRuleKey FareInfoRef="xjo7hbaeQIa0Nt4Y/9zldw==" ProviderCode="1P">dir-MAA^BOM^2015364^ALL^Y2OW^9W^^</air:FareRuleKey>
                </air:FareInfo>
                <air:FareInfo Key="hcw7BZBiSR6JMPIYAeUQXQ==" FareBasis="NOWLK" PassengerTypeCode="ADT" Origin="CMB" Destination="MAA" EffectiveDate="2015-12-23T04:52:00.000+00:00" DepartureDate="2015-12-30" Amount="LKR10600" NegotiatedFare="false" NotValidBefore="2015-12-30" NotValidAfter="2015-12-30">
                    <air:BaggageAllowance>
                        <air:MaxWeight Value="30" Unit="Kilograms"/>
                    </air:BaggageAllowance>
                    <air:FareRuleKey FareInfoRef="hcw7BZBiSR6JMPIYAeUQXQ==" ProviderCode="1P">dir-CMB^MAA^2015364^ALL^NOWLK^UL^^</air:FareRuleKey>
                </air:FareInfo>
                <air:FareInfo Key="CEXuae/8TougBtCeSdJQlQ==" FareBasis="VAPOWLK" PassengerTypeCode="ADT" Origin="CMB" Destination="BLR" EffectiveDate="2015-12-23T04:52:00.000+00:00" DepartureDate="2015-12-30" Amount="LKR13200" NegotiatedFare="false" NotValidBefore="2015-12-30" NotValidAfter="2015-12-30">
                    <air:BaggageAllowance>
                        <air:MaxWeight Value="30" Unit="Kilograms"/>
                    </air:BaggageAllowance>
                    <air:FareRuleKey FareInfoRef="CEXuae/8TougBtCeSdJQlQ==" ProviderCode="1P">dir-CMB^BLR^2015364^ALL^VAPOWLK^UL^^</air:FareRuleKey>
                </air:FareInfo>
                <air:FareInfo Key="vKHueM6mSNeKeLJ2fEFFkQ==" FareBasis="NJR4R1RI" PassengerTypeCode="ADT" Origin="CMB" Destination="BOM" EffectiveDate="2015-12-23T04:52:00.000+00:00" DepartureDate="2015-12-30" Amount="LKR65700" NegotiatedFare="false" NotValidBefore="2015-12-30" NotValidAfter="2015-12-30">
                    <air:BaggageAllowance>
                        <air:MaxWeight Value="30" Unit="Kilograms"/>
                    </air:BaggageAllowance>
                    <air:FareRuleKey FareInfoRef="vKHueM6mSNeKeLJ2fEFFkQ==" ProviderCode="1P">dir-CMB^BOM^2015364^ALL^NJR4R1RI^QR^^</air:FareRuleKey>
                </air:FareInfo>
                <air:FareInfo Key="0XRzSHoQTsm57HzxbopdDg==" FareBasis="EOWLK" PassengerTypeCode="ADT" Origin="CMB" Destination="COK" EffectiveDate="2015-12-23T04:52:00.000+00:00" DepartureDate="2015-12-30" Amount="LKR16300" NegotiatedFare="false" NotValidBefore="2015-12-30" NotValidAfter="2015-12-30">
                    <air:BaggageAllowance>
                        <air:MaxWeight Value="30" Unit="Kilograms"/>
                    </air:BaggageAllowance>
                    <air:FareRuleKey FareInfoRef="0XRzSHoQTsm57HzxbopdDg==" ProviderCode="1P">dir-CMB^COK^2015364^ALL^EOWLK^UL^^</air:FareRuleKey>
                </air:FareInfo>
                <air:FareInfo Key="xfBxh71RT3+DsWagjwKUPA==" FareBasis="Y2OW" PassengerTypeCode="ADT" Origin="COK" Destination="BOM" EffectiveDate="2015-12-23T04:52:00.000+00:00" DepartureDate="2015-12-30" Amount="LKR54000" NegotiatedFare="false" NotValidBefore="2015-12-30" NotValidAfter="2015-12-30">
                    <air:FareSurcharge Key="DSkhoZ4QRGSewNL7ily+8A==" Type="Other" Amount="NUC20.00"/>
                    <air:BaggageAllowance>
                        <air:MaxWeight Value="30" Unit="Kilograms"/>
                    </air:BaggageAllowance>
                    <air:FareRuleKey FareInfoRef="xfBxh71RT3+DsWagjwKUPA==" ProviderCode="1P">dir-COK^BOM^2015364^ALL^Y2OW^9W^^</air:FareRuleKey>
                </air:FareInfo>
                <air:FareInfo Key="zaM0+3oHRNqVVATFNKYMuQ==" FareBasis="WOWLKD" PassengerTypeCode="ADT" Origin="CMB" Destination="DEL" EffectiveDate="2015-12-23T04:52:00.000+00:00" DepartureDate="2015-12-30" Amount="LKR29000" NegotiatedFare="false" NotValidBefore="2015-12-30" NotValidAfter="2015-12-30">
                    <air:BaggageAllowance>
                        <air:MaxWeight Value="30" Unit="Kilograms"/>
                    </air:BaggageAllowance>
                    <air:FareRuleKey FareInfoRef="zaM0+3oHRNqVVATFNKYMuQ==" ProviderCode="1P">dir-CMB^DEL^2015364^ALL^WOWLKD^AI^^</air:FareRuleKey>
                    <air:Brand Key="nlPrtkMMR/iBB/7H7WPD/A==" BrandFound="false" UpSellBrandFound="false"/>
                </air:FareInfo>
                <air:FareInfo Key="f2YxOlckRLKEJAyDLLKovg==" FareBasis="Y2OW" PassengerTypeCode="ADT" Origin="DEL" Destination="BOM" EffectiveDate="2015-12-23T04:52:00.000+00:00" DepartureDate="2015-12-30" Amount="LKR54100" NegotiatedFare="false" NotValidBefore="2015-12-30" NotValidAfter="2015-12-30">
                    <air:FareSurcharge Key="qW4fsZvWTPClYdiIIPK1Fw==" Type="Other" Amount="NUC20.00"/>
                    <air:BaggageAllowance>
                        <air:MaxWeight Value="30" Unit="Kilograms"/>
                    </air:BaggageAllowance>
                    <air:FareRuleKey FareInfoRef="f2YxOlckRLKEJAyDLLKovg==" ProviderCode="1P">dir-DEL^BOM^2015364^ALL^Y2OW^9W^^</air:FareRuleKey>
                </air:FareInfo>
                <air:FareInfo Key="ftIvaU+bSR2iDy9aMEUoiQ==" FareBasis="YIFMH" PassengerTypeCode="ADT" Origin="CMB" Destination="KUL" EffectiveDate="2015-12-23T04:52:00.000+00:00" DepartureDate="2015-12-30" Amount="LKR69900" NegotiatedFare="false" NotValidAfter="2016-12-30">
                    <air:BaggageAllowance>
                        <air:MaxWeight Value="30" Unit="Kilograms"/>
                    </air:BaggageAllowance>
                    <air:FareRuleKey FareInfoRef="ftIvaU+bSR2iDy9aMEUoiQ==" ProviderCode="1P">dir-CMB^KUL^2015364^ALL^YIFMH^MH^^</air:FareRuleKey>
                </air:FareInfo>
                <air:FareInfo Key="WY8zaL+7RzmTFkVYJddkLg==" FareBasis="LST1YMY" PassengerTypeCode="ADT" Origin="KUL" Destination="BOM" EffectiveDate="2015-12-23T04:52:00.000+00:00" DepartureDate="2015-12-30" Amount="LKR36800" NegotiatedFare="false" NotValidBefore="2015-12-30" NotValidAfter="2015-12-30">
                    <air:FareSurcharge Key="lcax9shYTNyBAs2KRlNBjQ==" Type="Other" Amount="NUC30.00"/>
                    <air:BaggageAllowance>
                        <air:MaxWeight Value="30" Unit="Kilograms"/>
                    </air:BaggageAllowance>
                    <air:FareRuleKey FareInfoRef="WY8zaL+7RzmTFkVYJddkLg==" ProviderCode="1P">dir-KUL^BOM^2015364^ALL^LST1YMY^MH^^</air:FareRuleKey>
                </air:FareInfo>
                <air:FareInfo Key="avD1EYCKTV+E7SKdj5GTLg==" FareBasis="YRTLK" PassengerTypeCode="ADT" Origin="CMB" Destination="AUH" EffectiveDate="2015-12-23T04:52:00.000+00:00" DepartureDate="2015-12-30" Amount="LKR54900" NegotiatedFare="false" NotValidAfter="2016-12-30">
                    <air:FareTicketDesignator Value="YF"/>
                    <air:FareSurcharge Key="fQz7BuuyQQyzhGovvq+XQg==" Type="Other" Amount="NUC52.00"/>
                    <air:BaggageAllowance>
                        <air:NumberOfPieces>2</air:NumberOfPieces>
                        <air:MaxWeight/>
                    </air:BaggageAllowance>
                    <air:FareRuleKey FareInfoRef="avD1EYCKTV+E7SKdj5GTLg==" ProviderCode="1P">dir-CMB^AUH^2015364^ALL^YRTLK^EY^^</air:FareRuleKey>
                    <air:Brand Key="Z0okfdX/SyKJyD1swsBp5w==" BrandFound="false" UpSellBrandFound="false"/>
                </air:FareInfo>
                <air:FareInfo Key="YckcKZEvTiWsfHifsZgA7A==" FareBasis="YRT11" PassengerTypeCode="ADT" Origin="AUH" Destination="BOM" EffectiveDate="2015-12-23T04:52:00.000+00:00" DepartureDate="2015-12-30" Amount="LKR128800" NegotiatedFare="false" NotValidAfter="2016-12-30">
                    <air:FareTicketDesignator Value="YF"/>
                    <air:BaggageAllowance>
                        <air:NumberOfPieces>2</air:NumberOfPieces>
                        <air:MaxWeight/>
                    </air:BaggageAllowance>
                    <air:FareRuleKey FareInfoRef="YckcKZEvTiWsfHifsZgA7A==" ProviderCode="1P">dir-AUH^BOM^2015364^ALL^YRT11^EY^^</air:FareRuleKey>
                    <air:Brand Key="P7qIKbUlQJGEOOQGKO+rjw==" BrandFound="false" UpSellBrandFound="false"/>
                </air:FareInfo>
                <air:FareInfo Key="9a7I2UgbSUi8S9WL5UCfGg==" FareBasis="YIF" PassengerTypeCode="ADT" Origin="CMB" Destination="AUH" EffectiveDate="2015-12-23T04:52:00.000+00:00" DepartureDate="2015-12-30" Amount="LKR105000" NegotiatedFare="false">
                    <air:BaggageAllowance>
                        <air:NumberOfPieces>2</air:NumberOfPieces>
                        <air:MaxWeight/>
                    </air:BaggageAllowance>
                    <air:FareRuleKey FareInfoRef="9a7I2UgbSUi8S9WL5UCfGg==" ProviderCode="1P">dir-CMB^AUH^2015364^ALL^YIF^YY^^</air:FareRuleKey>
                </air:FareInfo>
                <air:FareInfo Key="u7rMZhMZTe+lZx3NvM3xjQ==" FareBasis="YIF" PassengerTypeCode="ADT" Origin="AUH" Destination="BOM" EffectiveDate="2015-12-23T04:52:00.000+00:00" DepartureDate="2015-12-30" Amount="LKR121300" NegotiatedFare="false">
                    <air:BaggageAllowance>
                        <air:NumberOfPieces>2</air:NumberOfPieces>
                        <air:MaxWeight/>
                    </air:BaggageAllowance>
                    <air:FareRuleKey FareInfoRef="u7rMZhMZTe+lZx3NvM3xjQ==" ProviderCode="1P">dir-AUH^BOM^2015364^ALL^YIF^YY^^</air:FareRuleKey>
                </air:FareInfo>
                <air:FareInfo Key="MoOt1K4PRP2QSGQyHJMl0Q==" FareBasis="YOW" PassengerTypeCode="ADT" Origin="CMB" Destination="HKG" EffectiveDate="2015-12-23T04:52:00.000+00:00" DepartureDate="2015-12-30" Amount="LKR89400" NegotiatedFare="false">
                    <air:BaggageAllowance>
                        <air:MaxWeight Value="20" Unit="Kilograms"/>
                    </air:BaggageAllowance>
                    <air:FareRuleKey FareInfoRef="MoOt1K4PRP2QSGQyHJMl0Q==" ProviderCode="1P">dir-CMB^HKG^2015364^ALL^YOW^CX^^</air:FareRuleKey>
                    <air:Brand Key="FN+Hq4s5RQC6IpDdB4J8Mg==" BrandFound="false" UpSellBrandFound="false"/>
                </air:FareInfo>
                <air:FareInfo Key="vMLe3Bq8T1qBNGRrs06x9Q==" FareBasis="YOW1" PassengerTypeCode="ADT" Origin="HKG" Destination="BOM" EffectiveDate="2015-12-23T04:52:00.000+00:00" DepartureDate="2015-12-30" Amount="LKR151300" NegotiatedFare="false">
                    <air:FareSurcharge Key="d5prxAoOQlGH31XvsVEHmw==" Type="Other" Amount="NUC5.80"/>
                    <air:BaggageAllowance>
                        <air:MaxWeight Value="20" Unit="Kilograms"/>
                    </air:BaggageAllowance>
                    <air:FareRuleKey FareInfoRef="vMLe3Bq8T1qBNGRrs06x9Q==" ProviderCode="1P">dir-HKG^BOM^2015364^ALL^YOW1^CX^^</air:FareRuleKey>
                    <air:Brand Key="ks77u1ZAQC+ENyQXXA4Bfw==" BrandFound="false" UpSellBrandFound="false"/>
                </air:FareInfo>
            </air:FareInfoList>
            <air:RouteList>
                <air:Route Key="s6sMxaPkQbGl8ohfJlHKtg==">
                    <air:Leg Key="9fTBGMLGTamc/5jZ3JejTQ==" Group="0" Origin="CMB" Destination="BOM"/>
                </air:Route>
            </air:RouteList>
            <air:AirPricePointList>
                <air:AirPricePoint Key="xC03Zdn1RueJyIsgrl6Xog==" TotalPrice="LKR17500" BasePrice="LKR14000" ApproximateTotalPrice="LKR17500" ApproximateBasePrice="LKR14000" Taxes="LKR3500" ApproximateTaxes="LKR3500" CompleteItinerary="true">
                    <air:AirPricingInfo Key="k2jWF8B2QMKf9vAOKcv2nA==" TotalPrice="LKR17500" BasePrice="LKR14000" ApproximateTotalPrice="LKR17500" ApproximateBasePrice="LKR14000" Taxes="LKR3500" LatestTicketingTime="2015-12-30T23:59:00.000+00:00" PricingMethod="Unknown" Refundable="true" ETicketability="Yes" PlatingCarrier="AI" ProviderCode="1P">
                        <air:FareInfoRef Key="ckit2lp2TISiJLsf7wa0mA=="/>
                        <air:TaxInfo Category="LK" Amount="LKR3500"/>
                        <air:FareCalc>CMB AI X/MAA AI BOM101.22NUC101.22END ROE138.30</air:FareCalc>
                        <air:PassengerType Code="ADT" Age="40"/>
                        <air:ChangePenalty>
                            <air:Amount>LKR2200.0</air:Amount>
                        </air:ChangePenalty>
                        <air:CancelPenalty>
                            <air:Amount>LKR4000.0</air:Amount>
                        </air:CancelPenalty>
                        <air:FlightOptionsList>
                            <air:FlightOption LegRef="9fTBGMLGTamc/5jZ3JejTQ==" Destination="BOM" Origin="CMB">
                                <air:Option Key="+wkjxLKxSSmDOEOqwpklBg==" TravelTime="P0DT13H40M0S">
                                    <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="ckit2lp2TISiJLsf7wa0mA==" SegmentRef="rYIAyCVbSWyf2aEzw5sX8w=="/>
                                    <air:BookingInfo BookingCode="V" CabinClass="Economy" FareInfoRef="ckit2lp2TISiJLsf7wa0mA==" SegmentRef="pmYvnWp2QG+fbaoczQUyyw=="/>
                                    <air:Connection SegmentIndex="0"/>
                                </air:Option>
                            </air:FlightOption>
                        </air:FlightOptionsList>
                    </air:AirPricingInfo>
                </air:AirPricePoint>
                <air:AirPricePoint Key="WJ4bHvzkTAiXNHIgqCtfyg==" TotalPrice="LKR26070" BasePrice="LKR21900" ApproximateTotalPrice="LKR26070" ApproximateBasePrice="LKR21900" Taxes="LKR4170" ApproximateTaxes="LKR4170" CompleteItinerary="true">
                    <air:AirPricingInfo Key="H5aRXPApTb+T/ntP74u9Eg==" TotalPrice="LKR26070" BasePrice="LKR21900" ApproximateTotalPrice="LKR26070" ApproximateBasePrice="LKR21900" Taxes="LKR4170" LatestTicketingTime="2015-12-30T23:59:00.000+00:00" PricingMethod="Unknown" Refundable="true" ETicketability="Yes" PlatingCarrier="MJ" ProviderCode="1P">
                        <air:FareInfoRef Key="SlxQ4fkSTuOIpPcue+S8yQ=="/>
                        <air:TaxInfo Category="LK" Amount="LKR3500"/>
                        <air:TaxInfo Category="YQ" Amount="LKR670"/>
                        <air:FareCalc>CMB MJ BOM158.35NUC158.35END ROE138.30</air:FareCalc>
                        <air:PassengerType Code="ADT" Age="40"/>
                        <air:ChangePenalty>
                            <air:Amount>LKR4500.0</air:Amount>
                        </air:ChangePenalty>
                        <air:FlightOptionsList>
                            <air:FlightOption LegRef="9fTBGMLGTamc/5jZ3JejTQ==" Destination="BOM" Origin="CMB">
                                <air:Option Key="hK8hHE77QYmcW6xRyd29lQ==" TravelTime="P0DT2H25M0S">
                                    <air:BookingInfo BookingCode="R" CabinClass="Economy" FareInfoRef="SlxQ4fkSTuOIpPcue+S8yQ==" SegmentRef="3JPZrzd/RpOA49xHkh6AqQ=="/>
                                </air:Option>
                            </air:FlightOption>
                        </air:FlightOptionsList>
                    </air:AirPricingInfo>
                </air:AirPricePoint>
                <air:AirPricePoint Key="CtHxtII0TA66QmkcjAjWPA==" TotalPrice="LKR31500" BasePrice="LKR28000" ApproximateTotalPrice="LKR31500" ApproximateBasePrice="LKR28000" Taxes="LKR3500" ApproximateTaxes="LKR3500" CompleteItinerary="true">
                    <air:AirPricingInfo Key="WBdckBUqQiGONBXxZJjFpg==" TotalPrice="LKR31500" BasePrice="LKR28000" ApproximateTotalPrice="LKR31500" ApproximateBasePrice="LKR28000" Taxes="LKR3500" LatestTicketingTime="2015-12-30T23:59:00.000+00:00" PricingMethod="Unknown" Refundable="true" ETicketability="Yes" PlatingCarrier="AI" ProviderCode="1P">
                        <air:FareInfoRef Key="bj1WKyZlR4WZFWPiqwm1hQ=="/>
                        <air:TaxInfo Category="LK" Amount="LKR3500"/>
                        <air:FareCalc>CMB AI X/DEL AI BOM202.45NUC202.45END ROE138.30</air:FareCalc>
                        <air:PassengerType Code="ADT" Age="40"/>
                        <air:ChangePenalty>
                            <air:Amount>LKR2200.0</air:Amount>
                        </air:ChangePenalty>
                        <air:CancelPenalty>
                            <air:Amount>LKR4000.0</air:Amount>
                        </air:CancelPenalty>
                        <air:FlightOptionsList>
                            <air:FlightOption LegRef="9fTBGMLGTamc/5jZ3JejTQ==" Destination="BOM" Origin="CMB">
                                <air:Option Key="rasYUh/6QrybsxTSWLvOrg==" TravelTime="P0DT10H35M0S">
                                    <air:BookingInfo BookingCode="T" CabinClass="Economy" FareInfoRef="bj1WKyZlR4WZFWPiqwm1hQ==" SegmentRef="bGngmU8aRG2A+x16m+lJ7g=="/>
                                    <air:BookingInfo BookingCode="V" CabinClass="Economy" FareInfoRef="bj1WKyZlR4WZFWPiqwm1hQ==" SegmentRef="3fJQkrKcTKaH3JlToYMbkA=="/>
                                    <air:Connection SegmentIndex="0"/>
                                </air:Option>
                                <air:Option Key="NIOZIWXzRFae/p9ZZqsWDA==" TravelTime="P0DT11H0M0S">
                                    <air:BookingInfo BookingCode="T" CabinClass="Economy" FareInfoRef="bj1WKyZlR4WZFWPiqwm1hQ==" SegmentRef="bGngmU8aRG2A+x16m+lJ7g=="/>
                                    <air:BookingInfo BookingCode="V" CabinClass="Economy" FareInfoRef="bj1WKyZlR4WZFWPiqwm1hQ==" SegmentRef="Sdi8wCXdTiuhbSPyjKtnYw=="/>
                                    <air:Connection SegmentIndex="0"/>
                                </air:Option>
                            </air:FlightOption>
                        </air:FlightOptionsList>
                    </air:AirPricingInfo>
                </air:AirPricePoint>
                <air:AirPricePoint Key="lENpTkiYRzqXS72Zm9IFLA==" TotalPrice="LKR42688" BasePrice="LKR38900" ApproximateTotalPrice="LKR42688" ApproximateBasePrice="LKR38900" Taxes="LKR3788" ApproximateTaxes="LKR3788" CompleteItinerary="true">
                    <air:AirPricingInfo Key="JINC8ZS5ToyZHP2VMg4bMA==" TotalPrice="LKR42688" BasePrice="LKR38900" ApproximateTotalPrice="LKR42688" ApproximateBasePrice="LKR38900" Taxes="LKR3788" LatestTicketingTime="2015-12-30T23:59:00.000+00:00" PricingMethod="Unknown" Refundable="true" ETicketability="Yes" PlatingCarrier="TG" ProviderCode="1P">
                        <air:FareInfoRef Key="kxuUkvTTQyWnQaY9OHv1Tw=="/>
                        <air:TaxInfo Category="LK" Amount="LKR3500"/>
                        <air:TaxInfo Category="E7" Amount="LKR288"/>
                        <air:FareCalc>CMB TG X/BKK TG BOM Q135.85 145.33NUC281.18END ROE138.30</air:FareCalc>
                        <air:PassengerType Code="ADT" Age="40"/>
                        <air:FlightOptionsList>
                            <air:FlightOption LegRef="9fTBGMLGTamc/5jZ3JejTQ==" Destination="BOM" Origin="CMB">
                                <air:Option Key="FFSsSN/8RIex3B6d88DYHg==" TravelTime="P0DT20H30M0S">
                                    <air:BookingInfo BookingCode="V" CabinClass="Economy" FareInfoRef="kxuUkvTTQyWnQaY9OHv1Tw==" SegmentRef="zTiMUOfiTJui9rbVWexUBw=="/>
                                    <air:BookingInfo BookingCode="V" CabinClass="Economy" FareInfoRef="kxuUkvTTQyWnQaY9OHv1Tw==" SegmentRef="R8aq8iGWSvKKRkSXphzv+Q=="/>
                                    <air:Connection SegmentIndex="0"/>
                                </air:Option>
                            </air:FlightOption>
                        </air:FlightOptionsList>
                    </air:AirPricingInfo>
                </air:AirPricePoint>
                <air:AirPricePoint Key="OXT4taW0SZSTzyoEqepafg==" TotalPrice="LKR43918" BasePrice="LKR40300" ApproximateTotalPrice="LKR43918" ApproximateBasePrice="LKR40300" Taxes="LKR3618" ApproximateTaxes="LKR3618" CompleteItinerary="true">
                    <air:AirPricingInfo Key="jTDe80A5Tx+Rx2ivs/HEuQ==" TotalPrice="LKR43918" BasePrice="LKR40300" ApproximateTotalPrice="LKR43918" ApproximateBasePrice="LKR40300" Taxes="LKR3618" LatestTicketingTime="2015-12-30T23:59:00.000+00:00" PricingMethod="Unknown" Refundable="true" ETicketability="Yes" PlatingCarrier="KU" ProviderCode="1P">
                        <air:FareInfoRef Key="F+qvdDNTSx2L2yK3lPa42w=="/>
                        <air:FareInfoRef Key="16lpSSfyQ8SoNzcX7ieDbw=="/>
                        <air:TaxInfo Category="LK" Amount="LKR3500"/>
                        <air:TaxInfo Category="YX" Amount="LKR118"/>
                        <air:FareCalc>CMB KU KWI148.59KU BOM142.24NUC290.83END ROE138.30</air:FareCalc>
                        <air:PassengerType Code="ADT" Age="40"/>
                        <air:FlightOptionsList>
                            <air:FlightOption LegRef="9fTBGMLGTamc/5jZ3JejTQ==" Destination="BOM" Origin="CMB">
                                <air:Option Key="3uje0NE7SbqKDabST7fYSQ==" TravelTime="P0DT22H45M0S">
                                    <air:BookingInfo BookingCode="T" CabinClass="Economy" FareInfoRef="F+qvdDNTSx2L2yK3lPa42w==" SegmentRef="4YD0h9VDQAm0Zcvf05dh3g=="/>
                                    <air:BookingInfo BookingCode="L" CabinClass="Economy" FareInfoRef="16lpSSfyQ8SoNzcX7ieDbw==" SegmentRef="y0GmFDjrREajR6dLHnJwzw=="/>
                                    <air:Connection SegmentIndex="0"/>
                                </air:Option>
                            </air:FlightOption>
                        </air:FlightOptionsList>
                    </air:AirPricingInfo>
                </air:AirPricePoint>
                <air:AirPricePoint Key="MV556yK3RHK/bFiyOxOKpw==" TotalPrice="LKR54540" BasePrice="LKR48500" ApproximateTotalPrice="LKR54540" ApproximateBasePrice="LKR48500" Taxes="LKR6040" ApproximateTaxes="LKR6040" CompleteItinerary="true">
                    <air:AirPricingInfo Key="FL6W/IHUSPudDWYjAb1N2Q==" TotalPrice="LKR54540" BasePrice="LKR48500" ApproximateTotalPrice="LKR54540" ApproximateBasePrice="LKR48500" Taxes="LKR6040" LatestTicketingTime="2015-12-30T23:59:00.000+00:00" PricingMethod="Unknown" Refundable="true" ETicketability="Yes" PlatingCarrier="SV" ProviderCode="1P">
                        <air:FareInfoRef Key="3dQhk0agQxW7gEXmw+7U9w=="/>
                        <air:FareInfoRef Key="ejp+KjRDT1m9qxZWj/7Osg=="/>
                        <air:TaxInfo Category="LK" Amount="LKR3500"/>
                        <air:TaxInfo Category="E3" Amount="LKR586"/>
                        <air:TaxInfo Category="UR" Amount="LKR1954"/>
                        <air:FareCalc>CMB SV JED163.77SV BOM186.89NUC350.66END ROE138.30</air:FareCalc>
                        <air:PassengerType Code="ADT" Age="40"/>
                        <air:ChangePenalty>
                            <air:Percentage>99.00</air:Percentage>
                        </air:ChangePenalty>
                        <air:FlightOptionsList>
                            <air:FlightOption LegRef="9fTBGMLGTamc/5jZ3JejTQ==" Destination="BOM" Origin="CMB">
                                <air:Option Key="w6eozEBpTd+b1rqeM8V3Ug==" TravelTime="P0DT18H20M0S">
                                    <air:BookingInfo BookingCode="V" CabinClass="Economy" FareInfoRef="3dQhk0agQxW7gEXmw+7U9w==" SegmentRef="i0kd15qYQ0ycOH9T8F1TSw=="/>
                                    <air:BookingInfo BookingCode="V" CabinClass="Economy" FareInfoRef="ejp+KjRDT1m9qxZWj/7Osg==" SegmentRef="yQBXiAd5RyOPEd0fgQM+kw=="/>
                                    <air:Connection SegmentIndex="0"/>
                                </air:Option>
                            </air:FlightOption>
                        </air:FlightOptionsList>
                    </air:AirPricingInfo>
                </air:AirPricePoint>
                <air:AirPricePoint Key="dkKoImkrRS27xrKT9450Pw==" TotalPrice="LKR60373" BasePrice="LKR55100" ApproximateTotalPrice="LKR60373" ApproximateBasePrice="LKR55100" Taxes="LKR5273" ApproximateTaxes="LKR5273" CompleteItinerary="true">
                    <air:AirPricingInfo Key="W1iuCyU4Q+WVBVGo6BRsaQ==" TotalPrice="LKR60373" BasePrice="LKR55100" ApproximateTotalPrice="LKR60373" ApproximateBasePrice="LKR55100" Taxes="LKR5273" LatestTicketingTime="2015-12-23T23:59:00.000+00:00" PricingMethod="Unknown" Refundable="true" ETicketability="Yes" PlatingCarrier="UL" ProviderCode="1P">
                        <air:FareInfoRef Key="GNx2drVSQDCBzzhTrl7hug=="/>
                        <air:FareInfoRef Key="jn1Q8IJmRyeNJlP0ZKTJrQ=="/>
                        <air:TaxInfo Category="LK" Amount="LKR3500"/>
                        <air:TaxInfo Category="IN" Amount="LKR774"/>
                        <air:TaxInfo Category="WO" Amount="LKR329"/>
                        <air:TaxInfo Category="YQ" Amount="LKR670"/>
                        <air:FareCalc>CMB UL BLR83.15 9W BOM Q20.00 294.55NUC397.70END ROE138.30</air:FareCalc>
                        <air:PassengerType Code="ADT" Age="40"/>
                        <air:ChangePenalty>
                            <air:Amount>LKR4500.0</air:Amount>
                        </air:ChangePenalty>
                        <air:FlightOptionsList>
                            <air:FlightOption LegRef="9fTBGMLGTamc/5jZ3JejTQ==" Destination="BOM" Origin="CMB">
                                <air:Option Key="ntV0agwPTHOHLUBUN9lDTA==" TravelTime="P0DT6H10M0S">
                                    <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="GNx2drVSQDCBzzhTrl7hug==" SegmentRef="uoPfx/zGQDqkCyPw/NqJAQ=="/>
                                    <air:BookingInfo BookingCode="Y" CabinClass="Economy" FareInfoRef="jn1Q8IJmRyeNJlP0ZKTJrQ==" SegmentRef="xSFWvGYvReW0atzYedX1Zg=="/>
                                    <air:Connection SegmentIndex="0"/>
                                </air:Option>
                            </air:FlightOption>
                        </air:FlightOptionsList>
                    </air:AirPricingInfo>
                </air:AirPricePoint>
                <air:AirPricePoint Key="7N9Fp0LsSRam+uUqtqGx2w==" TotalPrice="LKR61170" BasePrice="LKR57000" ApproximateTotalPrice="LKR61170" ApproximateBasePrice="LKR57000" Taxes="LKR4170" ApproximateTaxes="LKR4170" CompleteItinerary="true">
                    <air:AirPricingInfo Key="H/ienv1+T060FvRVjNxu/Q==" TotalPrice="LKR61170" BasePrice="LKR57000" ApproximateTotalPrice="LKR61170" ApproximateBasePrice="LKR57000" Taxes="LKR4170" LatestTicketingTime="2015-12-30T23:59:00.000+00:00" PricingMethod="Unknown" Refundable="true" ETicketability="Yes" PlatingCarrier="UL" ProviderCode="1P">
                        <air:FareInfoRef Key="ElaJU5w5QpGYmxBdj0JjVA=="/>
                        <air:FareInfoRef Key="xjo7hbaeQIa0Nt4Y/9zldw=="/>
                        <air:TaxInfo Category="LK" Amount="LKR3500"/>
                        <air:TaxInfo Category="YQ" Amount="LKR670"/>
                        <air:FareCalc>CMB UL MAA70.86 9W BOM Q20.00 321.07NUC411.93END ROE138.30</air:FareCalc>
                        <air:PassengerType Code="ADT" Age="40"/>
                        <air:ChangePenalty>
                            <air:Amount>LKR2300.0</air:Amount>
                        </air:ChangePenalty>
                        <air:CancelPenalty>
                            <air:Amount>LKR3400.0</air:Amount>
                        </air:CancelPenalty>
                        <air:FlightOptionsList>
                            <air:FlightOption LegRef="9fTBGMLGTamc/5jZ3JejTQ==" Destination="BOM" Origin="CMB">
                                <air:Option Key="OZcFFAiwS5qpHQigNqddAA==" TravelTime="P0DT6H15M0S">
                                    <air:BookingInfo BookingCode="Q" CabinClass="Economy" FareInfoRef="ElaJU5w5QpGYmxBdj0JjVA==" SegmentRef="iwp/RLWMQWCI8CGA/rA/vw=="/>
                                    <air:BookingInfo BookingCode="Y" CabinClass="Economy" FareInfoRef="xjo7hbaeQIa0Nt4Y/9zldw==" SegmentRef="0dXq6wzQS8qioSvEm2D7Yw=="/>
                                    <air:Connection SegmentIndex="0"/>
                                </air:Option>
                            </air:FlightOption>
                        </air:FlightOptionsList>
                    </air:AirPricingInfo>
                </air:AirPricePoint>
                <air:AirPricePoint Key="cUYeL8ApQqyKfYv3+gM1rQ==" TotalPrice="LKR61970" BasePrice="LKR57800" ApproximateTotalPrice="LKR61970" ApproximateBasePrice="LKR57800" Taxes="LKR4170" ApproximateTaxes="LKR4170" CompleteItinerary="true">
                    <air:AirPricingInfo Key="zwxucA/NRoOqnlCx0nIsDw==" TotalPrice="LKR61970" BasePrice="LKR57800" ApproximateTotalPrice="LKR61970" ApproximateBasePrice="LKR57800" Taxes="LKR4170" LatestTicketingTime="2015-12-30T23:59:00.000+00:00" PricingMethod="Unknown" Refundable="true" ETicketability="Yes" PlatingCarrier="UL" ProviderCode="1P">
                        <air:FareInfoRef Key="hcw7BZBiSR6JMPIYAeUQXQ=="/>
                        <air:FareInfoRef Key="xjo7hbaeQIa0Nt4Y/9zldw=="/>
                        <air:TaxInfo Category="LK" Amount="LKR3500"/>
                        <air:TaxInfo Category="YQ" Amount="LKR670"/>
                        <air:FareCalc>CMB UL MAA76.64 9W BOM Q20.00 321.07NUC417.71END ROE138.30</air:FareCalc>
                        <air:PassengerType Code="ADT" Age="40"/>
                        <air:ChangePenalty>
                            <air:Amount>LKR2300.0</air:Amount>
                        </air:ChangePenalty>
                        <air:CancelPenalty>
                            <air:Amount>LKR3400.0</air:Amount>
                        </air:CancelPenalty>
                        <air:FlightOptionsList>
                            <air:FlightOption LegRef="9fTBGMLGTamc/5jZ3JejTQ==" Destination="BOM" Origin="CMB">
                                <air:Option Key="+I8l9V01SE6ef2UTE+lqgA==" TravelTime="P0DT5H55M0S">
                                    <air:BookingInfo BookingCode="N" CabinClass="Economy" FareInfoRef="hcw7BZBiSR6JMPIYAeUQXQ==" SegmentRef="AT+tbEWhSbyFbRY6bSRzeA=="/>
                                    <air:BookingInfo BookingCode="Y" CabinClass="Economy" FareInfoRef="xjo7hbaeQIa0Nt4Y/9zldw==" SegmentRef="X557SugmQIye+lQk5tLFYg=="/>
                                    <air:Connection SegmentIndex="0"/>
                                </air:Option>
                            </air:FlightOption>
                        </air:FlightOptionsList>
                    </air:AirPricingInfo>
                </air:AirPricePoint>
                <air:AirPricePoint Key="n3F6F5dMRLGaK32/6iGsmQ==" TotalPrice="LKR62073" BasePrice="LKR56800" ApproximateTotalPrice="LKR62073" ApproximateBasePrice="LKR56800" Taxes="LKR5273" ApproximateTaxes="LKR5273" CompleteItinerary="true">
                    <air:AirPricingInfo Key="Fg0ECASZSuaSNJAS3Z2WGA==" TotalPrice="LKR62073" BasePrice="LKR56800" ApproximateTotalPrice="LKR62073" ApproximateBasePrice="LKR56800" Taxes="LKR5273" LatestTicketingTime="2015-12-27T23:59:00.000+00:00" PricingMethod="Unknown" Refundable="true" ETicketability="Yes" PlatingCarrier="UL" ProviderCode="1P">
                        <air:FareInfoRef Key="CEXuae/8TougBtCeSdJQlQ=="/>
                        <air:FareInfoRef Key="jn1Q8IJmRyeNJlP0ZKTJrQ=="/>
                        <air:TaxInfo Category="LK" Amount="LKR3500"/>
                        <air:TaxInfo Category="IN" Amount="LKR774"/>
                        <air:TaxInfo Category="WO" Amount="LKR329"/>
                        <air:TaxInfo Category="YQ" Amount="LKR670"/>
                        <air:FareCalc>CMB UL BLR95.44 9W BOM Q20.00 294.55NUC409.99END ROE138.30</air:FareCalc>
                        <air:PassengerType Code="ADT" Age="40"/>
                        <air:ChangePenalty>
                            <air:Amount>LKR4500.0</air:Amount>
                        </air:ChangePenalty>
                        <air:FlightOptionsList>
                            <air:FlightOption LegRef="9fTBGMLGTamc/5jZ3JejTQ==" Destination="BOM" Origin="CMB">
                                <air:Option Key="PZavmOWBTMq4J8VkQklPrA==" TravelTime="P0DT4H35M0S">
                                    <air:BookingInfo BookingCode="V" CabinClass="Economy" FareInfoRef="CEXuae/8TougBtCeSdJQlQ==" SegmentRef="v9852ceNTGOhkPNvi3XeVw=="/>
                                    <air:BookingInfo BookingCode="Y" CabinClass="Economy" FareInfoRef="jn1Q8IJmRyeNJlP0ZKTJrQ==" SegmentRef="DDa84luNRoKfbkAuqZ2ejQ=="/>
                                    <air:Connection SegmentIndex="0"/>
                                </air:Option>
                            </air:FlightOption>
                        </air:FlightOptionsList>
                    </air:AirPricingInfo>
                </air:AirPricePoint>
                <air:AirPricePoint Key="DNdA9pXpTFWjml7CrjyDuw==" TotalPrice="LKR69274" BasePrice="LKR65700" ApproximateTotalPrice="LKR69274" ApproximateBasePrice="LKR65700" Taxes="LKR3574" ApproximateTaxes="LKR3574" CompleteItinerary="true">
                    <air:AirPricingInfo Key="O7YO/iO/SIGDJP8q7wttww==" TotalPrice="LKR69274" BasePrice="LKR65700" ApproximateTotalPrice="LKR69274" ApproximateBasePrice="LKR65700" Taxes="LKR3574" LatestTicketingTime="2015-12-30T23:59:00.000+00:00" PricingMethod="Unknown" Refundable="true" ETicketability="Yes" PlatingCarrier="QR" ProviderCode="1P">
                        <air:FareInfoRef Key="vKHueM6mSNeKeLJ2fEFFkQ=="/>
                        <air:TaxInfo Category="LK" Amount="LKR3500"/>
                        <air:TaxInfo Category="PZ" Amount="LKR74"/>
                        <air:FareCalc>CMB QR X/DOH QR BOM475.05NUC475.05END ROE138.30</air:FareCalc>
                        <air:PassengerType Code="ADT" Age="40"/>
                        <air:ChangePenalty>
                            <air:Amount>LKR4400.0</air:Amount>
                        </air:ChangePenalty>
                        <air:CancelPenalty>
                            <air:Amount>LKR5200.0</air:Amount>
                        </air:CancelPenalty>
                        <air:FlightOptionsList>
                            <air:FlightOption LegRef="9fTBGMLGTamc/5jZ3JejTQ==" Destination="BOM" Origin="CMB">
                                <air:Option Key="ceDivXKQS5uXLv4gQCp3yw==" TravelTime="P0DT16H35M0S">
                                    <air:BookingInfo BookingCode="N" CabinClass="Economy" FareInfoRef="vKHueM6mSNeKeLJ2fEFFkQ==" SegmentRef="Z5+/4AJQR0ii4q6qeQF89A=="/>
                                    <air:BookingInfo BookingCode="N" CabinClass="Economy" FareInfoRef="vKHueM6mSNeKeLJ2fEFFkQ==" SegmentRef="2nP9/I8DR9W7IKKexlMeCw=="/>
                                    <air:Connection SegmentIndex="0"/>
                                </air:Option>
                            </air:FlightOption>
                        </air:FlightOptionsList>
                    </air:AirPricingInfo>
                </air:AirPricePoint>
                <air:AirPricePoint Key="3NihTvMeRcarDQIOwI42lg==" TotalPrice="LKR69802" BasePrice="LKR65700" ApproximateTotalPrice="LKR69802" ApproximateBasePrice="LKR65700" Taxes="LKR4102" ApproximateTaxes="LKR4102" CompleteItinerary="true">
                    <air:AirPricingInfo Key="XwKsIXN9SgWG2hU7FJwGsA==" TotalPrice="LKR69802" BasePrice="LKR65700" ApproximateTotalPrice="LKR69802" ApproximateBasePrice="LKR65700" Taxes="LKR4102" LatestTicketingTime="2015-12-30T23:59:00.000+00:00" PricingMethod="Unknown" Refundable="true" ETicketability="Yes" PlatingCarrier="QR" ProviderCode="1P">
                        <air:FareInfoRef Key="vKHueM6mSNeKeLJ2fEFFkQ=="/>
                        <air:TaxInfo Category="LK" Amount="LKR3500"/>
                        <air:TaxInfo Category="PZ" Amount="LKR602"/>
                        <air:FareCalc>CMB QR X/DOH QR BOM475.05NUC475.05END ROE138.30</air:FareCalc>
                        <air:PassengerType Code="ADT" Age="40"/>
                        <air:ChangePenalty>
                            <air:Amount>LKR4400.0</air:Amount>
                        </air:ChangePenalty>
                        <air:CancelPenalty>
                            <air:Amount>LKR5200.0</air:Amount>
                        </air:CancelPenalty>
                        <air:FlightOptionsList>
                            <air:FlightOption LegRef="9fTBGMLGTamc/5jZ3JejTQ==" Destination="BOM" Origin="CMB">
                                <air:Option Key="9Od1MAh0RRuy+5CSvtOZww==" TravelTime="P0DT21H50M0S">
                                    <air:BookingInfo BookingCode="N" CabinClass="Economy" FareInfoRef="vKHueM6mSNeKeLJ2fEFFkQ==" SegmentRef="Tq39Si3AQyekAcRGS8PJGg=="/>
                                    <air:BookingInfo BookingCode="N" CabinClass="Economy" FareInfoRef="vKHueM6mSNeKeLJ2fEFFkQ==" SegmentRef="2nP9/I8DR9W7IKKexlMeCw=="/>
                                    <air:Connection SegmentIndex="0"/>
                                </air:Option>
                            </air:FlightOption>
                        </air:FlightOptionsList>
                    </air:AirPricingInfo>
                </air:AirPricePoint>
                <air:AirPricePoint Key="jKo6eiunS8yUt1Qwe9eDbQ==" TotalPrice="LKR75310" BasePrice="LKR70300" ApproximateTotalPrice="LKR75310" ApproximateBasePrice="LKR70300" Taxes="LKR5010" ApproximateTaxes="LKR5010" CompleteItinerary="true">
                    <air:AirPricingInfo Key="xxLPnb5lRMGS7sWcC6tccg==" TotalPrice="LKR75310" BasePrice="LKR70300" ApproximateTotalPrice="LKR75310" ApproximateBasePrice="LKR70300" Taxes="LKR5010" LatestTicketingTime="2015-12-30T23:59:00.000+00:00" PricingMethod="Unknown" Refundable="true" ETicketability="Yes" PlatingCarrier="UL" ProviderCode="1P">
                        <air:FareInfoRef Key="0XRzSHoQTsm57HzxbopdDg=="/>
                        <air:FareInfoRef Key="xfBxh71RT3+DsWagjwKUPA=="/>
                        <air:TaxInfo Category="LK" Amount="LKR3500"/>
                        <air:TaxInfo Category="WO" Amount="LKR840"/>
                        <air:TaxInfo Category="YQ" Amount="LKR670"/>
                        <air:FareCalc>CMB UL COK117.85 9W BOM Q20.00 370.44NUC508.29END ROE138.30</air:FareCalc>
                        <air:PassengerType Code="ADT" Age="40"/>
                        <air:ChangePenalty>
                            <air:Amount>LKR2300.0</air:Amount>
                        </air:ChangePenalty>
                        <air:CancelPenalty>
                            <air:Amount>LKR3400.0</air:Amount>
                        </air:CancelPenalty>
                        <air:FlightOptionsList>
                            <air:FlightOption LegRef="9fTBGMLGTamc/5jZ3JejTQ==" Destination="BOM" Origin="CMB">
                                <air:Option Key="AeCPwPKGRNuKTjTVGUBhzw==" TravelTime="P0DT6H35M0S">
                                    <air:BookingInfo BookingCode="E" CabinClass="Economy" FareInfoRef="0XRzSHoQTsm57HzxbopdDg==" SegmentRef="6jtrWUvfRyWBFbaHacrvkQ=="/>
                                    <air:BookingInfo BookingCode="Y" CabinClass="Economy" FareInfoRef="xfBxh71RT3+DsWagjwKUPA==" SegmentRef="CJjDsTGwT4C3Go8a7dqkMw=="/>
                                    <air:Connection SegmentIndex="0"/>
                                </air:Option>
                            </air:FlightOption>
                        </air:FlightOptionsList>
                    </air:AirPricingInfo>
                </air:AirPricePoint>
                <air:AirPricePoint Key="StREavf5T+iE5NzOeCztzw==" TotalPrice="LKR86893" BasePrice="LKR83100" ApproximateTotalPrice="LKR86893" ApproximateBasePrice="LKR83100" Taxes="LKR3793" ApproximateTaxes="LKR3793" CompleteItinerary="true">
                    <air:AirPricingInfo Key="OZAbvnwtQsq0Ys+zEbLZfQ==" TotalPrice="LKR86893" BasePrice="LKR83100" ApproximateTotalPrice="LKR86893" ApproximateBasePrice="LKR83100" Taxes="LKR3793" LatestTicketingTime="2015-12-30T23:59:00.000+00:00" PricingMethod="Unknown" Refundable="true" ETicketability="Yes" PlatingCarrier="AI" ProviderCode="1P">
                        <air:FareInfoRef Key="zaM0+3oHRNqVVATFNKYMuQ=="/>
                        <air:FareInfoRef Key="f2YxOlckRLKEJAyDLLKovg=="/>
                        <air:TaxInfo Category="LK" Amount="LKR3500"/>
                        <air:TaxInfo Category="YR" Amount="LKR293"/>
                        <air:FareCalc>CMB AI DEL209.68 9W BOM Q20.00 370.74NUC600.42END ROE138.30</air:FareCalc>
                        <air:PassengerType Code="ADT" Age="40"/>
                        <air:ChangePenalty>
                            <air:Amount>LKR2300.0</air:Amount>
                        </air:ChangePenalty>
                        <air:CancelPenalty>
                            <air:Amount>LKR3400.0</air:Amount>
                        </air:CancelPenalty>
                        <air:FlightOptionsList>
                            <air:FlightOption LegRef="9fTBGMLGTamc/5jZ3JejTQ==" Destination="BOM" Origin="CMB">
                                <air:Option Key="YvXDbKRlQsGkR7IgbuWrbg==" TravelTime="P0DT9H15M0S">
                                    <air:BookingInfo BookingCode="W" CabinClass="Economy" FareInfoRef="zaM0+3oHRNqVVATFNKYMuQ==" SegmentRef="bGngmU8aRG2A+x16m+lJ7g=="/>
                                    <air:BookingInfo BookingCode="Y" CabinClass="Economy" FareInfoRef="f2YxOlckRLKEJAyDLLKovg==" SegmentRef="Pdxf+tBEQ5mSfJwlX48bAA=="/>
                                    <air:Connection SegmentIndex="0"/>
                                </air:Option>
                                <air:Option Key="7CYXvIihTcubMgFrWklNqg==" TravelTime="P0DT10H10M0S">
                                    <air:BookingInfo BookingCode="W" CabinClass="Economy" FareInfoRef="zaM0+3oHRNqVVATFNKYMuQ==" SegmentRef="bGngmU8aRG2A+x16m+lJ7g=="/>
                                    <air:BookingInfo BookingCode="Y" CabinClass="Economy" FareInfoRef="f2YxOlckRLKEJAyDLLKovg==" SegmentRef="vU/ihTCAQZiQQTngPDUJzg=="/>
                                    <air:Connection SegmentIndex="0"/>
                                </air:Option>
                            </air:FlightOption>
                        </air:FlightOptionsList>
                    </air:AirPricingInfo>
                </air:AirPricePoint>
                <air:AirPricePoint Key="vlznSFV4Roys5Q+f+dqCJg==" TotalPrice="LKR145958" BasePrice="LKR106700" ApproximateTotalPrice="LKR145958" ApproximateBasePrice="LKR106700" Taxes="LKR39258" ApproximateTaxes="LKR39258" CompleteItinerary="true">
                    <air:AirPricingInfo Key="aDnKZaegRGGIXKb5EM6Jvw==" TotalPrice="LKR145958" BasePrice="LKR106700" ApproximateTotalPrice="LKR145958" ApproximateBasePrice="LKR106700" Taxes="LKR39258" LatestTicketingTime="2015-12-30T23:59:00.000+00:00" PricingMethod="Unknown" ETicketability="Yes" PlatingCarrier="MH" ProviderCode="1P">
                        <air:FareInfoRef Key="ftIvaU+bSR2iDy9aMEUoiQ=="/>
                        <air:FareInfoRef Key="WY8zaL+7RzmTFkVYJddkLg=="/>
                        <air:TaxInfo Category="LK" Amount="LKR3500"/>
                        <air:TaxInfo Category="YQ" Amount="LKR35172"/>
                        <air:TaxInfo Category="YR" Amount="LKR586"/>
                        <air:FareCalc>CMB MH KUL505.42MH BOM Q30.00 235.42NUC770.84END ROE138.30</air:FareCalc>
                        <air:PassengerType Code="ADT" Age="40"/>
                        <air:ChangePenalty>
                            <air:Amount>LKR5300.0</air:Amount>
                        </air:ChangePenalty>
                        <air:FlightOptionsList>
                            <air:FlightOption LegRef="9fTBGMLGTamc/5jZ3JejTQ==" Destination="BOM" Origin="CMB">
                                <air:Option Key="CTb8uidLRNa/DUwD/+rYSw==" TravelTime="P0DT15H25M0S">
                                    <air:BookingInfo BookingCode="Y" CabinClass="Economy" FareInfoRef="ftIvaU+bSR2iDy9aMEUoiQ==" SegmentRef="ywi0//OKShCCi2dewirAew=="/>
                                    <air:BookingInfo BookingCode="L" CabinClass="Economy" FareInfoRef="WY8zaL+7RzmTFkVYJddkLg==" SegmentRef="Vt1iQ+1vTeeVu0DRlaabuA=="/>
                                    <air:Connection SegmentIndex="0"/>
                                </air:Option>
                                <air:Option Key="FPm75n0/S1mtKNO5krGLXQ==" TravelTime="P0DT17H55M0S">
                                    <air:BookingInfo BookingCode="Y" CabinClass="Economy" FareInfoRef="ftIvaU+bSR2iDy9aMEUoiQ==" SegmentRef="ywi0//OKShCCi2dewirAew=="/>
                                    <air:BookingInfo BookingCode="L" CabinClass="Economy" FareInfoRef="WY8zaL+7RzmTFkVYJddkLg==" SegmentRef="DbdFbSZDTvSUvqfkkFKkWQ=="/>
                                    <air:Connection SegmentIndex="0"/>
                                </air:Option>
                            </air:FlightOption>
                        </air:FlightOptionsList>
                    </air:AirPricingInfo>
                </air:AirPricePoint>
                <air:AirPricePoint Key="LmKhGw5NSkG6Xles2C8VQg==" TotalPrice="LKR148376" BasePrice="LKR106700" ApproximateTotalPrice="LKR148376" ApproximateBasePrice="LKR106700" Taxes="LKR41676" ApproximateTaxes="LKR41676" CompleteItinerary="true">
                    <air:AirPricingInfo Key="CyqEl/b/R0iRKI98JRc2dg==" TotalPrice="LKR148376" BasePrice="LKR106700" ApproximateTotalPrice="LKR148376" ApproximateBasePrice="LKR106700" Taxes="LKR41676" LatestTicketingTime="2015-12-30T23:59:00.000+00:00" PricingMethod="Unknown" ETicketability="Yes" PlatingCarrier="MH" ProviderCode="1P">
                        <air:FareInfoRef Key="ftIvaU+bSR2iDy9aMEUoiQ=="/>
                        <air:FareInfoRef Key="WY8zaL+7RzmTFkVYJddkLg=="/>
                        <air:TaxInfo Category="LK" Amount="LKR3500"/>
                        <air:TaxInfo Category="MY" Amount="LKR2281"/>
                        <air:TaxInfo Category="D8" Amount="LKR137"/>
                        <air:TaxInfo Category="YQ" Amount="LKR35172"/>
                        <air:TaxInfo Category="YR" Amount="LKR586"/>
                        <air:FareCalc>CMB MH KUL505.42MH BOM Q30.00 235.42NUC770.84END ROE138.30</air:FareCalc>
                        <air:PassengerType Code="ADT" Age="40"/>
                        <air:ChangePenalty>
                            <air:Amount>LKR5300.0</air:Amount>
                        </air:ChangePenalty>
                        <air:FlightOptionsList>
                            <air:FlightOption LegRef="9fTBGMLGTamc/5jZ3JejTQ==" Destination="BOM" Origin="CMB">
                                <air:Option Key="cT6tDSSBRfuS7/+TNZ3flQ==" TravelTime="P0DT21H55M0S">
                                    <air:BookingInfo BookingCode="Y" CabinClass="Economy" FareInfoRef="ftIvaU+bSR2iDy9aMEUoiQ==" SegmentRef="RJWgTt4vSueHj9wb584AKg=="/>
                                    <air:BookingInfo BookingCode="L" CabinClass="Economy" FareInfoRef="WY8zaL+7RzmTFkVYJddkLg==" SegmentRef="Vt1iQ+1vTeeVu0DRlaabuA=="/>
                                    <air:Connection SegmentIndex="0"/>
                                </air:Option>
                            </air:FlightOption>
                        </air:FlightOptionsList>
                    </air:AirPricingInfo>
                </air:AirPricePoint>
                <air:AirPricePoint Key="DgaJ891/T/yfyfxo9hyhgA==" TotalPrice="LKR187360" BasePrice="LKR183700" ApproximateTotalPrice="LKR187360" ApproximateBasePrice="LKR183700" Taxes="LKR3660" ApproximateTaxes="LKR3660" CompleteItinerary="true">
                    <air:AirPricingInfo Key="Ym2maWGWQ1eqwmEzP3lYmQ==" TotalPrice="LKR187360" BasePrice="LKR183700" ApproximateTotalPrice="LKR187360" ApproximateBasePrice="LKR183700" Taxes="LKR3660" LatestTicketingTime="2015-12-30T23:59:00.000+00:00" PricingMethod="Unknown" Refundable="true" ETicketability="Yes" PlatingCarrier="EY" ProviderCode="1P">
                        <air:FareInfoRef Key="avD1EYCKTV+E7SKdj5GTLg=="/>
                        <air:FareInfoRef Key="YckcKZEvTiWsfHifsZgA7A=="/>
                        <air:TaxInfo Category="LK" Amount="LKR3500"/>
                        <air:TaxInfo Category="YR" Amount="LKR160"/>
                        <air:FareCalc>CMB EY AUH Q52.00 344.54EY BOM931.12NUC1327.66END ROE138.30</air:FareCalc>
                        <air:PassengerType Code="ADT" Age="40"/>
                        <air:FlightOptionsList>
                            <air:FlightOption LegRef="9fTBGMLGTamc/5jZ3JejTQ==" Destination="BOM" Origin="CMB">
                                <air:Option Key="+UmbLjYDR/Gh4878HDaN0A==" TravelTime="P0DT11H15M0S">
                                    <air:BookingInfo BookingCode="Y" CabinClass="Economy" FareInfoRef="avD1EYCKTV+E7SKdj5GTLg==" SegmentRef="+MMO5rAESiOCeShQny3Syw=="/>
                                    <air:BookingInfo BookingCode="Y" CabinClass="Economy" FareInfoRef="YckcKZEvTiWsfHifsZgA7A==" SegmentRef="a4AcYah5RECT8wBNkwKrDA=="/>
                                    <air:Connection SegmentIndex="0"/>
                                </air:Option>
                                <air:Option Key="nmsn74hdQ/q93nY7cKKhdg==" TravelTime="P0DT13H30M0S">
                                    <air:BookingInfo BookingCode="Y" CabinClass="Economy" FareInfoRef="avD1EYCKTV+E7SKdj5GTLg==" SegmentRef="pZFnN2jITn6KXZDFiZBERA=="/>
                                    <air:BookingInfo BookingCode="Y" CabinClass="Economy" FareInfoRef="YckcKZEvTiWsfHifsZgA7A==" SegmentRef="a4AcYah5RECT8wBNkwKrDA=="/>
                                    <air:Connection SegmentIndex="0"/>
                                </air:Option>
                            </air:FlightOption>
                        </air:FlightOptionsList>
                    </air:AirPricingInfo>
                </air:AirPricePoint>
                <air:AirPricePoint Key="04xs7X/9RTqW3WxG8y0YBw==" TotalPrice="LKR229800" BasePrice="LKR226300" ApproximateTotalPrice="LKR229800" ApproximateBasePrice="LKR226300" Taxes="LKR3500" ApproximateTaxes="LKR3500" CompleteItinerary="true">
                    <air:AirPricingInfo Key="h/iwnMlARpSFnTuClKXDGw==" TotalPrice="LKR229800" BasePrice="LKR226300" ApproximateTotalPrice="LKR229800" ApproximateBasePrice="LKR226300" Taxes="LKR3500" LatestTicketingTime="2015-12-30T23:59:00.000+00:00" PricingMethod="Unknown" Refundable="true" ETicketability="Yes" PlatingCarrier="HM" ProviderCode="1P">
                        <air:FareInfoRef Key="9a7I2UgbSUi8S9WL5UCfGg=="/>
                        <air:FareInfoRef Key="u7rMZhMZTe+lZx3NvM3xjQ=="/>
                        <air:TaxInfo Category="LK" Amount="LKR3500"/>
                        <air:FareCalc>CMB HM AUH759.21HM BOM876.67NUC1635.88END ROE138.30</air:FareCalc>
                        <air:PassengerType Code="ADT" Age="40"/>
                        <air:FlightOptionsList>
                            <air:FlightOption LegRef="9fTBGMLGTamc/5jZ3JejTQ==" Destination="BOM" Origin="CMB">
                                <air:Option Key="+y1+rcheTwCyTYXPDUdsnw==" TravelTime="P0DT11H15M0S">
                                    <air:BookingInfo BookingCode="M" CabinClass="Economy" FareInfoRef="9a7I2UgbSUi8S9WL5UCfGg==" SegmentRef="Y8S7pDkVQiacyI4ZHq2ZCw=="/>
                                    <air:BookingInfo BookingCode="M" CabinClass="Economy" FareInfoRef="u7rMZhMZTe+lZx3NvM3xjQ==" SegmentRef="MOByCm8BQU+AFxqXfWndBA=="/>
                                    <air:Connection SegmentIndex="0"/>
                                </air:Option>
                                <air:Option Key="By46XiHLRoODX713NTDJEw==" TravelTime="P0DT15H55M0S">
                                    <air:BookingInfo BookingCode="M" CabinClass="Economy" FareInfoRef="9a7I2UgbSUi8S9WL5UCfGg==" SegmentRef="Y8S7pDkVQiacyI4ZHq2ZCw=="/>
                                    <air:BookingInfo BookingCode="M" CabinClass="Economy" FareInfoRef="u7rMZhMZTe+lZx3NvM3xjQ==" SegmentRef="4+oO8WgRT9CHPFB2KrxN0Q=="/>
                                    <air:Connection SegmentIndex="0"/>
                                </air:Option>
                            </air:FlightOption>
                        </air:FlightOptionsList>
                    </air:AirPricingInfo>
                </air:AirPricePoint>
                <air:AirPricePoint Key="fJFekzynSZiipv24Xcls/Q==" TotalPrice="LKR230000" BasePrice="LKR226300" ApproximateTotalPrice="LKR230000" ApproximateBasePrice="LKR226300" Taxes="LKR3700" ApproximateTaxes="LKR3700" CompleteItinerary="true">
                    <air:AirPricingInfo Key="QUGnXOuiQZagpuvwtbKqtw==" TotalPrice="LKR230000" BasePrice="LKR226300" ApproximateTotalPrice="LKR230000" ApproximateBasePrice="LKR226300" Taxes="LKR3700" LatestTicketingTime="2015-12-30T23:59:00.000+00:00" PricingMethod="Unknown" Refundable="true" ETicketability="Yes" PlatingCarrier="HM" ProviderCode="1P">
                        <air:FareInfoRef Key="9a7I2UgbSUi8S9WL5UCfGg=="/>
                        <air:FareInfoRef Key="u7rMZhMZTe+lZx3NvM3xjQ=="/>
                        <air:TaxInfo Category="LK" Amount="LKR3500"/>
                        <air:TaxInfo Category="ZR" Amount="LKR200"/>
                        <air:FareCalc>CMB HM AUH759.21HM BOM876.67NUC1635.88END ROE138.30</air:FareCalc>
                        <air:PassengerType Code="ADT" Age="40"/>
                        <air:FlightOptionsList>
                            <air:FlightOption LegRef="9fTBGMLGTamc/5jZ3JejTQ==" Destination="BOM" Origin="CMB">
                                <air:Option Key="xLX9+eVWTAe7BdYOXT9xVg==" TravelTime="P1DT5H50M0S">
                                    <air:BookingInfo BookingCode="M" CabinClass="Economy" FareInfoRef="9a7I2UgbSUi8S9WL5UCfGg==" SegmentRef="Y8S7pDkVQiacyI4ZHq2ZCw=="/>
                                    <air:BookingInfo BookingCode="M" CabinClass="Economy" FareInfoRef="u7rMZhMZTe+lZx3NvM3xjQ==" SegmentRef="PemwJukMRtuKXefkZBLwuw=="/>
                                    <air:Connection SegmentIndex="0"/>
                                </air:Option>
                            </air:FlightOption>
                        </air:FlightOptionsList>
                    </air:AirPricingInfo>
                </air:AirPricePoint>
                <air:AirPricePoint Key="qJzILpydToatz/ddsG8hzw==" TotalPrice="LKR248422" BasePrice="LKR240700" ApproximateTotalPrice="LKR248422" ApproximateBasePrice="LKR240700" Taxes="LKR7722" ApproximateTaxes="LKR7722" CompleteItinerary="true">
                    <air:AirPricingInfo Key="nIdUy7SYReWUpTUKA6BJWA==" TotalPrice="LKR248422" BasePrice="LKR240700" ApproximateTotalPrice="LKR248422" ApproximateBasePrice="LKR240700" Taxes="LKR7722" LatestTicketingTime="2015-12-30T23:59:00.000+00:00" PricingMethod="Unknown" Refundable="true" ETicketability="Yes" PlatingCarrier="CX" ProviderCode="1P">
                        <air:FareInfoRef Key="MoOt1K4PRP2QSGQyHJMl0Q=="/>
                        <air:FareInfoRef Key="vMLe3Bq8T1qBNGRrs06x9Q=="/>
                        <air:TaxInfo Category="LK" Amount="LKR3500"/>
                        <air:TaxInfo Category="YR" Amount="LKR4222"/>
                        <air:FareCalc>CMB CX HKG646.42CX BOM Q5.80 1087.70NUC1739.92END ROE138.30</air:FareCalc>
                        <air:PassengerType Code="ADT" Age="40"/>
                        <air:FlightOptionsList>
                            <air:FlightOption LegRef="9fTBGMLGTamc/5jZ3JejTQ==" Destination="BOM" Origin="CMB">
                                <air:Option Key="ljBg1ehQR9K502OIqwqmnA==" TravelTime="P0DT23H35M0S">
                                    <air:BookingInfo BookingCode="Y" CabinClass="Economy" FareInfoRef="MoOt1K4PRP2QSGQyHJMl0Q==" SegmentRef="KW1Je2lyQk2n+nEBrqUX1g=="/>
                                    <air:BookingInfo BookingCode="Y" CabinClass="Economy" FareInfoRef="vMLe3Bq8T1qBNGRrs06x9Q==" SegmentRef="GtdgjEmUSu+4+ZLUAhfkjA=="/>
                                    <air:Connection SegmentIndex="0"/>
                                </air:Option>
                            </air:FlightOption>
                        </air:FlightOptionsList>
                    </air:AirPricingInfo>
                </air:AirPricePoint>
            </air:AirPricePointList>
        </air:LowFareSearchRsp>
    </SOAP:Body>
</SOAP:Envelope>';
        //load the response xml
        $response1_xml = simplexml_load_string($response);
        //registering the namespaces of response
        $response1_xml->registerXPathNamespace('soap', "http://schemas.xmlsoap.org/soap/envelope/");
        $response1_xml->registerXPathNamespace('air', "http://www.travelport.com/schema/air_v33_0");
        //get the all air:FlightDetails tag details
        $FlightDetailsValues = $response1_xml->xpath('/soap:Envelope/soap:Body/air:LowFareSearchRsp/air:FlightDetailsList/air:FlightDetails');
        $AirPricePointValues = $response1_xml->xpath('/soap:Envelope/soap:Body/air:LowFareSearchRsp/air:AirPricePointList/air:AirPricePoint');
        echo '<br><br>';

        // var_dump($FlightDetailsValues);
        echo '<br><br>';
        //create FlightDetailsArray
        $FlightDetailsArray = array();
        //change object to string values and assign to $FlightDetailsArray
        for ($i = 0; $i < sizeof($FlightDetailsValues); $i++) {

            $result = get_object_vars($FlightDetailsValues[$i]);
            $FlightDetailsArray[$i]['FlightDetails'] = $result["@attributes"];
            //fetch segment details
            $Key = $result["@attributes"]["Key"];
            $segmentDetailsValues = $response1_xml->xpath('/soap:Envelope/soap:Body/air:LowFareSearchRsp/air:AirSegmentList/air:AirSegment[air:FlightDetailsRef[@Key="' . $Key . '"]]');
            $result2 = get_object_vars($segmentDetailsValues[0]);
            $FlightDetailsArray[$i]['segmentDetails'] = $result2["@attributes"];
            //fetch price details
        }
        var_dump($FlightDetailsArray);


        //var_dump($segmentDetailsArray);
    }

    public function filterFlights() {
        //  echo 'hello';
        $resXmlfilter = '<SOAP:Envelope xmlns:SOAP="http://schemas.xmlsoap.org/soap/envelope/">
    <SOAP:Body>
        <air:LowFareSearchRsp TraceId="49087225-93d8-48d0-bfc0-90ad69f9329c" TransactionId="E721EFC00A076477DB8090B8B3C21D12" ResponseTime="30370" DistanceUnits="MI" CurrencyType="LKR" xmlns:air="http://www.travelport.com/schema/air_v33_0">
            <air:FlightDetailsList>
                <air:FlightDetails Key="MZ7QqDr+QC6HuB+4hwSAhg==" Origin="CMB" Destination="MAA" DepartureTime="2015-12-30T18:30:00.000+05:30" ArrivalTime="2015-12-30T19:50:00.000+05:30" FlightTime="80" TravelTime="820" Equipment="321" DestinationTerminal="4"/>
                <air:FlightDetails Key="S/I4sXVZShyRxuajYeDnkw==" Origin="MAA" Destination="BOM" DepartureTime="2015-12-31T06:20:00.000+05:30" ArrivalTime="2015-12-31T08:10:00.000+05:30" FlightTime="110" TravelTime="820" Equipment="319" OriginTerminal="1" DestinationTerminal="2"/>
                <air:FlightDetails Key="v1sX/lIHS5OCc677Pke86g==" Origin="MAA" Destination="BOM" DepartureTime="2015-12-31T16:15:00.000+05:30" ArrivalTime="2015-12-31T18:25:00.000+05:30" FlightTime="130" TravelTime="1435" Equipment="319" OriginTerminal="1" DestinationTerminal="2"/>
                <air:FlightDetails Key="5yhaR0A9R+a51SHQPV1xgQ==" Origin="CMB" Destination="BOM" DepartureTime="2015-12-30T06:10:00.000+05:30" ArrivalTime="2015-12-30T08:45:00.000+05:30" FlightTime="155" TravelTime="155" Equipment="737" DestinationTerminal="2"/>
                <air:FlightDetails Key="MLCdBF/aSy+XB22LguYJFQ==" Origin="CMB" Destination="BOM" DepartureTime="2015-12-30T23:45:00.000+05:30" ArrivalTime="2015-12-31T02:10:00.000+05:30" FlightTime="145" TravelTime="145" Equipment="320" DestinationTerminal="2"/>
                <air:FlightDetails Key="cf5ctDmrShKk0oEcwK5mKw==" Origin="CMB" Destination="DEL" DepartureTime="2015-12-30T08:20:00.000+05:30" ArrivalTime="2015-12-30T12:10:00.000+05:30" FlightTime="230" TravelTime="635" Equipment="321" DestinationTerminal="3"/>
                <air:FlightDetails Key="xNF8HRHMRq294kByDmT/vw==" Origin="DEL" Destination="BOM" DepartureTime="2015-12-30T16:45:00.000+05:30" ArrivalTime="2015-12-30T18:55:00.000+05:30" FlightTime="130" TravelTime="635" Equipment="77W" OriginTerminal="3" DestinationTerminal="2"/>
                <air:FlightDetails Key="t6yaokewROSZPFHnQUHerA==" Origin="DEL" Destination="BOM" DepartureTime="2015-12-30T17:00:00.000+05:30" ArrivalTime="2015-12-30T19:20:00.000+05:30" FlightTime="140" TravelTime="660" Equipment="321" OriginTerminal="3" DestinationTerminal="2"/>
                <air:FlightDetails Key="olM4f/ryQx2A9cRIR9sQRQ==" Origin="MAA" Destination="DEL" DepartureTime="2015-12-31T06:15:00.000+05:30" ArrivalTime="2015-12-31T09:05:00.000+05:30" FlightTime="170" TravelTime="1065" Equipment="321" OriginTerminal="1" DestinationTerminal="3"/>
                <air:FlightDetails Key="gR3inqwpTHK6hFPXLcTAFw==" Origin="DEL" Destination="BOM" DepartureTime="2015-12-31T10:00:00.000+05:30" ArrivalTime="2015-12-31T12:15:00.000+05:30" FlightTime="135" TravelTime="1065" Equipment="321" OriginTerminal="3" DestinationTerminal="2"/>
                <air:FlightDetails Key="BkXr2+XtT7Spev8mwn6wJg==" Origin="MAA" Destination="DEL" DepartureTime="2015-12-31T08:45:00.000+05:30" ArrivalTime="2015-12-31T11:30:00.000+05:30" FlightTime="165" TravelTime="1245" Equipment="320" OriginTerminal="4" DestinationTerminal="3"/>
                <air:FlightDetails Key="9bkSwKyrT2+NSQMTDmcY2w==" Origin="DEL" Destination="BOM" DepartureTime="2015-12-31T13:00:00.000+05:30" ArrivalTime="2015-12-31T15:15:00.000+05:30" FlightTime="135" TravelTime="1245" Equipment="321" OriginTerminal="3" DestinationTerminal="2"/>
                <air:FlightDetails Key="Qx6VGCgKTk6bWemuPs4/Cg==" Origin="DEL" Destination="BLR" DepartureTime="2015-12-30T13:30:00.000+05:30" ArrivalTime="2015-12-30T16:15:00.000+05:30" FlightTime="165" TravelTime="665" Equipment="321" OriginTerminal="3"/>
                <air:FlightDetails Key="DowDQ/atR1etDJVpg71ypw==" Origin="BLR" Destination="BOM" DepartureTime="2015-12-30T17:20:00.000+05:30" ArrivalTime="2015-12-30T19:25:00.000+05:30" FlightTime="125" TravelTime="665" Equipment="319" DestinationTerminal="2"/>
                <air:FlightDetails Key="4JmWXMTETUW7zskwih/i8A==" Origin="CMB" Destination="BKK" DepartureTime="2015-12-30T01:30:00.000+05:30" ArrivalTime="2015-12-30T06:25:00.000+07:00" FlightTime="205" TravelTime="1230" Equipment="777"/>
                <air:FlightDetails Key="kd0QgtB1TquY/inhyO5aeA==" Origin="BKK" Destination="BOM" DepartureTime="2015-12-30T18:55:00.000+07:00" ArrivalTime="2015-12-30T22:00:00.000+05:30" FlightTime="275" TravelTime="1230" Equipment="744" DestinationTerminal="2"/>
                <air:FlightDetails Key="TTdr/Lr3QX+BtXm980mSTw==" Origin="CMB" Destination="KWI" DepartureTime="2015-12-30T05:45:00.000+05:30" ArrivalTime="2015-12-30T08:50:00.000+03:00" FlightTime="335" TravelTime="1365" Equipment="340" DestinationTerminal="M"/>
                <air:FlightDetails Key="i7uhdtG5T4uRFzzlBEixFQ==" Origin="KWI" Destination="BOM" DepartureTime="2015-12-30T22:10:00.000+03:00" ArrivalTime="2015-12-31T04:30:00.000+05:30" FlightTime="230" TravelTime="1365" Equipment="747" OriginTerminal="M" DestinationTerminal="2"/>
                <air:FlightDetails Key="KfXPdHapR8O+9/9R+7mLvg==" Origin="MAA" Destination="BLR" DepartureTime="2015-12-31T14:20:00.000+05:30" ArrivalTime="2015-12-31T15:20:00.000+05:30" FlightTime="60" TravelTime="1495" Equipment="319" OriginTerminal="1"/>
                <air:FlightDetails Key="qgvEWxk+R26BbJvgOryxjQ==" Origin="BLR" Destination="BOM" DepartureTime="2015-12-31T17:20:00.000+05:30" ArrivalTime="2015-12-31T19:25:00.000+05:30" FlightTime="125" TravelTime="1495" Equipment="319" DestinationTerminal="2"/>
                <air:FlightDetails Key="tSY8c+hZQ9qTY8nEg3kzzQ==" Origin="CMB" Destination="MAA" DepartureTime="2015-12-30T07:20:00.000+05:30" ArrivalTime="2015-12-30T08:40:00.000+05:30" FlightTime="80" TravelTime="355" Equipment="332" DestinationTerminal="3"/>
                <air:FlightDetails Key="wJqRUfH1RDSCks37FcPC5w==" Origin="MAA" Destination="BOM" DepartureTime="2015-12-30T11:25:00.000+05:30" ArrivalTime="2015-12-30T13:15:00.000+05:30" FlightTime="110" TravelTime="355" Equipment="737" OriginTerminal="1" DestinationTerminal="1B"/>
                <air:FlightDetails Key="8aue71cFRf62dLqy/4O+Vw==" Origin="CMB" Destination="MAA" DepartureTime="2015-12-30T18:35:00.000+05:30" ArrivalTime="2015-12-30T19:55:00.000+05:30" FlightTime="80" TravelTime="595" Equipment="321" DestinationTerminal="3"/>
                <air:FlightDetails Key="s8+CESe0Qc+qppbGWZYOAw==" Origin="MAA" Destination="BOM" DepartureTime="2015-12-31T02:35:00.000+05:30" ArrivalTime="2015-12-31T04:30:00.000+05:30" FlightTime="115" TravelTime="595" Equipment="73G" OriginTerminal="1" DestinationTerminal="1B"/>
                <air:FlightDetails Key="gUUn5WUjSCKOdTJapJOpeA==" Origin="CMB" Destination="BLR" DepartureTime="2015-12-30T01:15:00.000+05:30" ArrivalTime="2015-12-30T02:35:00.000+05:30" FlightTime="80" TravelTime="370" Equipment="321"/>
                <air:FlightDetails Key="pyq2EE06TGy7ZfMMiJ0ZYQ==" Origin="BLR" Destination="BOM" DepartureTime="2015-12-30T05:50:00.000+05:30" ArrivalTime="2015-12-30T07:25:00.000+05:30" FlightTime="95" TravelTime="370" Equipment="738" DestinationTerminal="1B"/>
                <air:FlightDetails Key="AAyuN5WhSmSPQ+tlLs/CkQ==" Origin="CMB" Destination="DOH" DepartureTime="2015-12-30T09:25:00.000+05:30" ArrivalTime="2015-12-30T11:55:00.000+03:00" FlightTime="300" TravelTime="995" Equipment="346"/>
                <air:FlightDetails Key="pCPYqaTKRxGn8w2ACbvLbA==" Origin="DOH" Destination="BOM" DepartureTime="2015-12-30T20:20:00.000+03:00" ArrivalTime="2015-12-31T02:00:00.000+05:30" FlightTime="190" TravelTime="995" Equipment="77W" DestinationTerminal="2"/>
                <air:FlightDetails Key="aY41THQxSLKLNVmptAxd1g==" Origin="CMB" Destination="MAA" DepartureTime="2015-12-30T00:40:00.000+05:30" ArrivalTime="2015-12-30T02:00:00.000+05:30" FlightTime="80" TravelTime="450" Equipment="320" DestinationTerminal="3"/>
                <air:FlightDetails Key="4DGasBVYRFqyPi0mnFakvQ==" Origin="MAA" Destination="BOM" DepartureTime="2015-12-30T06:20:00.000+05:30" ArrivalTime="2015-12-30T08:10:00.000+05:30" FlightTime="110" TravelTime="450" Equipment="319" OriginTerminal="1" DestinationTerminal="2"/>
                <air:FlightDetails Key="GY+qsQ42SOO5++TalG/92Q==" Origin="CMB" Destination="COK" DepartureTime="2015-12-30T14:05:00.000+05:30" ArrivalTime="2015-12-30T15:25:00.000+05:30" FlightTime="80" TravelTime="580" Equipment="320"/>
                <air:FlightDetails Key="b62DTAMvQ72FOdQQNR260A==" Origin="COK" Destination="BOM" DepartureTime="2015-12-30T22:00:00.000+05:30" ArrivalTime="2015-12-30T23:45:00.000+05:30" FlightTime="105" TravelTime="580" Equipment="320" DestinationTerminal="2"/>
                <air:FlightDetails Key="NN+I3xxzS3uodBXHbaLNYw==" Origin="BLR" Destination="BOM" DepartureTime="2015-12-30T06:45:00.000+05:30" ArrivalTime="2015-12-30T08:20:00.000+05:30" FlightTime="95" TravelTime="425" Equipment="319" DestinationTerminal="2"/>
                <air:FlightDetails Key="iBTeLbTtTCym7UAJuE+xUw==" Origin="CMB" Destination="MCT" DepartureTime="2015-12-30T19:05:00.000+05:30" ArrivalTime="2015-12-30T21:55:00.000+04:00" FlightTime="260" TravelTime="580" Equipment="320"/>
                <air:FlightDetails Key="zWNb+9aqTBKgj0wOyuquXA==" Origin="MCT" Destination="BOM" DepartureTime="2015-12-31T00:20:00.000+04:00" ArrivalTime="2015-12-31T04:45:00.000+05:30" FlightTime="175" TravelTime="580" Equipment="321" DestinationTerminal="2"/>
                <air:FlightDetails Key="Q2M5BR1gTzm6cYeqKF704w==" Origin="CMB" Destination="AUH" DepartureTime="2015-12-30T21:00:00.000+05:30" ArrivalTime="2015-12-31T00:25:00.000+04:00" FlightTime="295" TravelTime="675" Equipment="320" DestinationTerminal="1"/>
                <air:FlightDetails Key="qxHq1wLtQvWMbjmwjeCrGw==" Origin="AUH" Destination="BOM" DepartureTime="2015-12-31T03:10:00.000+04:00" ArrivalTime="2015-12-31T08:15:00.000+05:30" FlightTime="215" TravelTime="675" Equipment="320" OriginTerminal="1" DestinationTerminal="2"/>
                <air:FlightDetails Key="LPgO6qF4TZyKmUK8V+Sn6A==" Origin="CMB" Destination="SIN" DepartureTime="2015-12-30T01:10:00.000+05:30" ArrivalTime="2015-12-30T07:40:00.000+08:00" FlightTime="240" TravelTime="1235" Equipment="333" DestinationTerminal="0"/>
                <air:FlightDetails Key="+sG5rvtiTS6+FhEMywX1cA==" Origin="SIN" Destination="BOM" DepartureTime="2015-12-30T18:55:00.000+08:00" ArrivalTime="2015-12-30T21:45:00.000+05:30" FlightTime="320" TravelTime="1235" Equipment="388" OriginTerminal="2" DestinationTerminal="2"/>
                <air:FlightDetails Key="BQfb4pfdSyOR0tgY11qXHA==" Origin="CMB" Destination="SIN" DepartureTime="2015-12-30T01:00:00.000+05:30" ArrivalTime="2015-12-30T07:30:00.000+08:00" FlightTime="240" TravelTime="750" Equipment="320" DestinationTerminal="3"/>
                <air:FlightDetails Key="X4L5BLbKTDeCvpEynlXb5w==" Origin="SIN" Destination="BOM" DepartureTime="2015-12-30T10:30:00.000+08:00" ArrivalTime="2015-12-30T13:30:00.000+05:30" FlightTime="330" TravelTime="750" Equipment="737" OriginTerminal="3" DestinationTerminal="2"/>
            </air:FlightDetailsList>
            <air:AirSegmentList>
                <air:AirSegment Key="5prwP64xRJSHRE+YUOVOpA==" Group="0" Carrier="AI" FlightNumber="274" Origin="CMB" Destination="MAA" DepartureTime="2015-12-30T18:30:00.000+05:30" ArrivalTime="2015-12-30T19:50:00.000+05:30" FlightTime="80" Distance="402" ETicketability="Yes" Equipment="321" ChangeOfPlane="false" OptionalServicesIndicator="false">
                    <air:AirAvailInfo ProviderCode="1P">
                        <air:BookingCodeInfo BookingCounts="C4|D4|J4|Z4|Y9|B4|M4|H4|K4|Q4|V4|W4|G4|L4|U4|T4|S4|E4|N4"/>
                    </air:AirAvailInfo>
                    <air:FlightDetailsRef Key="MZ7QqDr+QC6HuB+4hwSAhg=="/>
                </air:AirSegment>
                <air:AirSegment Key="ek+tHMznQmqgkV2yB4coXg==" Group="0" Carrier="AI" FlightNumber="569" Origin="MAA" Destination="BOM" DepartureTime="2015-12-31T06:20:00.000+05:30" ArrivalTime="2015-12-31T08:10:00.000+05:30" FlightTime="110" Distance="644" ETicketability="Yes" Equipment="319" ChangeOfPlane="false" OptionalServicesIndicator="false">
                    <air:AirAvailInfo ProviderCode="1P">
                        <air:BookingCodeInfo BookingCounts="C4|D4|J4|Z4|Y9|B4|M4|H4|K4|Q4|V4|W4|G4|L4|U4|T4|S4|E4|N4"/>
                    </air:AirAvailInfo>
                    <air:FlightDetailsRef Key="S/I4sXVZShyRxuajYeDnkw=="/>
                </air:AirSegment>
                <air:AirSegment Key="80Ukm7I3SVS1iSPkRzHoDQ==" Group="0" Carrier="AI" FlightNumber="672" Origin="MAA" Destination="BOM" DepartureTime="2015-12-31T16:15:00.000+05:30" ArrivalTime="2015-12-31T18:25:00.000+05:30" FlightTime="130" Distance="644" ETicketability="Yes" Equipment="319" ChangeOfPlane="false" OptionalServicesIndicator="false">
                    <air:AirAvailInfo ProviderCode="1P">
                        <air:BookingCodeInfo BookingCounts="C4|D4|J4|Z4|Y9|B4|M4|H4|K4|Q4|V4|W4|G4|L4|U4|T4|S4|E4|N4"/>
                    </air:AirAvailInfo>
                    <air:FlightDetailsRef Key="v1sX/lIHS5OCc677Pke86g=="/>
                </air:AirSegment>
                <air:AirSegment Key="wUsM4tuhSx+u75QaV9enIQ==" Group="0" Carrier="9W" FlightNumber="255" Origin="CMB" Destination="BOM" DepartureTime="2015-12-30T06:10:00.000+05:30" ArrivalTime="2015-12-30T08:45:00.000+05:30" FlightTime="155" Distance="948" ETicketability="Yes" Equipment="737" ChangeOfPlane="false" OptionalServicesIndicator="false">
                    <air:AirAvailInfo ProviderCode="1P">
                        <air:BookingCodeInfo BookingCounts="C6|J6|Z5|I5|P3|Y7|M7|T7|U7|N7|L7|Q7|S7|K7|H7|V7|O7|W7"/>
                    </air:AirAvailInfo>
                    <air:FlightDetailsRef Key="5yhaR0A9R+a51SHQPV1xgQ=="/>
                </air:AirSegment>
                <air:AirSegment Key="0sPvLtdHT2if1Sz+um+ZPA==" Group="0" Carrier="UL" FlightNumber="141" Origin="CMB" Destination="BOM" DepartureTime="2015-12-30T23:45:00.000+05:30" ArrivalTime="2015-12-31T02:10:00.000+05:30" FlightTime="145" Distance="948" ETicketability="Yes" Equipment="320" ChangeOfPlane="false" OptionalServicesIndicator="false">
                    <air:AirAvailInfo ProviderCode="1P">
                        <air:BookingCodeInfo BookingCounts="J4|C4|D4|I4|Y7|B7|P4|H7|K7|W4|M7|E4|L7|R4|V7|S4|N7|Q4|O4"/>
                    </air:AirAvailInfo>
                    <air:FlightDetailsRef Key="MLCdBF/aSy+XB22LguYJFQ=="/>
                </air:AirSegment>
                <air:AirSegment Key="zfzYjZssS9O+FjcOPHlS9g==" Group="0" Carrier="MJ" FlightNumber="2141" Origin="CMB" Destination="BOM" DepartureTime="2015-12-30T23:45:00.000+05:30" ArrivalTime="2015-12-31T02:10:00.000+05:30" FlightTime="145" Distance="948" ETicketability="Yes" Equipment="320" ChangeOfPlane="false" OptionalServicesIndicator="false">
                    <air:CodeshareInfo OperatingCarrier="UL" OperatingFlightNumber="141">SRILANKAN AIRLINES</air:CodeshareInfo>
                    <air:AirAvailInfo ProviderCode="1P">
                        <air:BookingCodeInfo BookingCounts="J4|C4|D4|I4|Y4|B4|P4|H4|K4|W4|M4|E4|L4|R4|V4|S4|N4|Q4|O4"/>
                    </air:AirAvailInfo>
                    <air:FlightDetailsRef Key="MLCdBF/aSy+XB22LguYJFQ=="/>
                </air:AirSegment>
                <air:AirSegment Key="BxHogvm4S9+73DL7Iju7ZA==" Group="0" Carrier="AI" FlightNumber="282" Origin="CMB" Destination="DEL" DepartureTime="2015-12-30T08:20:00.000+05:30" ArrivalTime="2015-12-30T12:10:00.000+05:30" FlightTime="230" Distance="1489" ETicketability="Yes" Equipment="321" ChangeOfPlane="false" OptionalServicesIndicator="false">
                    <air:AirAvailInfo ProviderCode="1P">
                        <air:BookingCodeInfo BookingCounts="C4|D4|J4|Z4|Y9|B4|M4|H4|K4|Q4|V4|W4|G4|L4|U4|T4|S4|E4|N4"/>
                    </air:AirAvailInfo>
                    <air:FlightDetailsRef Key="cf5ctDmrShKk0oEcwK5mKw=="/>
                </air:AirSegment>
                <air:AirSegment Key="ql61iU/9TDSWChJyiJrLvg==" Group="0" Carrier="AI" FlightNumber="102" Origin="DEL" Destination="BOM" DepartureTime="2015-12-30T16:45:00.000+05:30" ArrivalTime="2015-12-30T18:55:00.000+05:30" FlightTime="130" Distance="708" ETicketability="Yes" Equipment="77W" ChangeOfPlane="false" OptionalServicesIndicator="false">
                    <air:AirAvailInfo ProviderCode="1P">
                        <air:BookingCodeInfo BookingCounts="F4|A4|C4|D4|J4|Z4|Y9|B4|M4|H4|K4|Q4|V4|W4|G4|L4|U4|T4|S4|E4|N4"/>
                    </air:AirAvailInfo>
                    <air:FlightDetailsRef Key="xNF8HRHMRq294kByDmT/vw=="/>
                </air:AirSegment>
                <air:AirSegment Key="RHZ5VUh6S/mCCegpqpj5dw==" Group="0" Carrier="AI" FlightNumber="659" Origin="DEL" Destination="BOM" DepartureTime="2015-12-30T17:00:00.000+05:30" ArrivalTime="2015-12-30T19:20:00.000+05:30" FlightTime="140" Distance="708" ETicketability="Yes" Equipment="321" ChangeOfPlane="false" OptionalServicesIndicator="false">
                    <air:AirAvailInfo ProviderCode="1P">
                        <air:BookingCodeInfo BookingCounts="C4|D4|J4|Z4|Y9|B4|M4|H4|K4|Q4|V4|W4|G4|L4|U4|T4|S4|E4|N4"/>
                    </air:AirAvailInfo>
                    <air:FlightDetailsRef Key="t6yaokewROSZPFHnQUHerA=="/>
                </air:AirSegment>
                <air:AirSegment Key="H6tKrdeESRet0ZkGQFaWSw==" Group="0" Carrier="AI" FlightNumber="440" Origin="MAA" Destination="DEL" DepartureTime="2015-12-31T06:15:00.000+05:30" ArrivalTime="2015-12-31T09:05:00.000+05:30" FlightTime="170" Distance="1095" ETicketability="Yes" Equipment="321" ChangeOfPlane="false" OptionalServicesIndicator="false">
                    <air:AirAvailInfo ProviderCode="1P">
                        <air:BookingCodeInfo BookingCounts="C4|D4|J4|Z4|Y9|B4|M4|H4|K4|Q4|V4|W4|G4|L4|U4|T4|S4|E4|N4"/>
                    </air:AirAvailInfo>
                    <air:FlightDetailsRef Key="olM4f/ryQx2A9cRIR9sQRQ=="/>
                </air:AirSegment>
                <air:AirSegment Key="w2nddKA4R/S9XZzKQNgDpA==" Group="0" Carrier="AI" FlightNumber="865" Origin="DEL" Destination="BOM" DepartureTime="2015-12-31T10:00:00.000+05:30" ArrivalTime="2015-12-31T12:15:00.000+05:30" FlightTime="135" Distance="708" ETicketability="Yes" Equipment="321" ChangeOfPlane="false" OptionalServicesIndicator="false">
                    <air:AirAvailInfo ProviderCode="1P">
                        <air:BookingCodeInfo BookingCounts="C4|D4|J4|Z4|Y9|B4|M4|H4|K4|Q4|V4|W4|G4|L4|U4|T4|S4|E4|N4"/>
                    </air:AirAvailInfo>
                    <air:FlightDetailsRef Key="gR3inqwpTHK6hFPXLcTAFw=="/>
                </air:AirSegment>
                <air:AirSegment Key="1NFlHND0QRS1Hc37ANWKjA==" Group="0" Carrier="AI" FlightNumber="143" Origin="MAA" Destination="DEL" DepartureTime="2015-12-31T08:45:00.000+05:30" ArrivalTime="2015-12-31T11:30:00.000+05:30" FlightTime="165" Distance="1095" ETicketability="Yes" Equipment="320" ChangeOfPlane="false" OptionalServicesIndicator="false">
                    <air:AirAvailInfo ProviderCode="1P">
                        <air:BookingCodeInfo BookingCounts="C4|D4|J4|Z4|Y9|B4|M4|H4|K4|Q4|V4|W4|G4|L4|U4|T4|S4|E4|N4"/>
                    </air:AirAvailInfo>
                    <air:FlightDetailsRef Key="BkXr2+XtT7Spev8mwn6wJg=="/>
                </air:AirSegment>
                <air:AirSegment Key="LduEaBcySaeOMWkCUyZ7ew==" Group="0" Carrier="AI" FlightNumber="863" Origin="DEL" Destination="BOM" DepartureTime="2015-12-31T13:00:00.000+05:30" ArrivalTime="2015-12-31T15:15:00.000+05:30" FlightTime="135" Distance="708" ETicketability="Yes" Equipment="321" ChangeOfPlane="false" OptionalServicesIndicator="false">
                    <air:AirAvailInfo ProviderCode="1P">
                        <air:BookingCodeInfo BookingCounts="C4|D4|J4|Z4|Y9|B4|M4|H4|K4|Q4|V4|W4|G4|L4|U4|T4|S4|E4|N4"/>
                    </air:AirAvailInfo>
                    <air:FlightDetailsRef Key="9bkSwKyrT2+NSQMTDmcY2w=="/>
                </air:AirSegment>
                <air:AirSegment Key="KrfAd76dRS2C1Qffc0gXxw==" Group="0" Carrier="AI" FlightNumber="502" Origin="DEL" Destination="BLR" DepartureTime="2015-12-30T13:30:00.000+05:30" ArrivalTime="2015-12-30T16:15:00.000+05:30" FlightTime="165" Distance="1063" ETicketability="Yes" Equipment="321" ChangeOfPlane="false" OptionalServicesIndicator="false">
                    <air:AirAvailInfo ProviderCode="1P">
                        <air:BookingCodeInfo BookingCounts="C4|D4|J4|Z4|Y9|B4|M4|H4|K4|Q4|V4|W4|G4|L4|U4|T4|S4|E4|N4"/>
                    </air:AirAvailInfo>
                    <air:FlightDetailsRef Key="Qx6VGCgKTk6bWemuPs4/Cg=="/>
                </air:AirSegment>
                <air:AirSegment Key="tuloGUIxQeOZRii5cjFCuQ==" Group="0" Carrier="AI" FlightNumber="608" Origin="BLR" Destination="BOM" DepartureTime="2015-12-30T17:20:00.000+05:30" ArrivalTime="2015-12-30T19:25:00.000+05:30" FlightTime="125" Distance="519" ETicketability="Yes" Equipment="319" ChangeOfPlane="false" OptionalServicesIndicator="false">
                    <air:AirAvailInfo ProviderCode="1P">
                        <air:BookingCodeInfo BookingCounts="C4|D4|J4|Z4|Y9|B4|M4|H4|K4|Q4|V4|W4|G4|L4|U4|T4|S4|E4|N4"/>
                    </air:AirAvailInfo>
                    <air:FlightDetailsRef Key="DowDQ/atR1etDJVpg71ypw=="/>
                </air:AirSegment>
                <air:AirSegment Key="9nUkPsjMQBSlVG7DYPyFDA==" Group="0" Carrier="TG" FlightNumber="308" Origin="CMB" Destination="BKK" DepartureTime="2015-12-30T01:30:00.000+05:30" ArrivalTime="2015-12-30T06:25:00.000+07:00" FlightTime="205" Distance="1485" ETicketability="Yes" Equipment="777" ChangeOfPlane="false" OptionalServicesIndicator="false">
                    <air:AirAvailInfo ProviderCode="1P">
                        <air:BookingCodeInfo BookingCounts="C9|D9|J9|Z9|I6|R5|Y9|B9|M9|H9|Q9|T9|K9|S9|X9|V9"/>
                    </air:AirAvailInfo>
                    <air:FlightDetailsRef Key="4JmWXMTETUW7zskwih/i8A=="/>
                </air:AirSegment>
                <air:AirSegment Key="yjemUQ11SF+KYeFzbnf/gg==" Group="0" Carrier="TG" FlightNumber="317" Origin="BKK" Destination="BOM" DepartureTime="2015-12-30T18:55:00.000+07:00" ArrivalTime="2015-12-30T22:00:00.000+05:30" FlightTime="275" Distance="1878" ETicketability="Yes" Equipment="744" ChangeOfPlane="false" OptionalServicesIndicator="false">
                    <air:AirAvailInfo ProviderCode="1P">
                        <air:BookingCodeInfo BookingCounts="C9|D5|Y9|B9|M9|H9|Q9|T9|K9|S9|X9|V9|W9|N9"/>
                    </air:AirAvailInfo>
                    <air:FlightDetailsRef Key="kd0QgtB1TquY/inhyO5aeA=="/>
                </air:AirSegment>
                <air:AirSegment Key="CO+0DRsyTviGJgTR2lecVw==" Group="0" Carrier="KU" FlightNumber="362" Origin="CMB" Destination="KWI" DepartureTime="2015-12-30T05:45:00.000+05:30" ArrivalTime="2015-12-30T08:50:00.000+03:00" FlightTime="335" Distance="2573" ETicketability="Yes" Equipment="340" ChangeOfPlane="false" OptionalServicesIndicator="false">
                    <air:AirAvailInfo ProviderCode="1P">
                        <air:BookingCodeInfo BookingCounts="J4|C4|Y9|N9|K9|M9|B9|H9|L9|T9|V9"/>
                    </air:AirAvailInfo>
                    <air:FlightDetailsRef Key="TTdr/Lr3QX+BtXm980mSTw=="/>
                </air:AirSegment>
                <air:AirSegment Key="cqXkk4GiRuqLAZOFpgAFvQ==" Group="0" Carrier="KU" FlightNumber="301" Origin="KWI" Destination="BOM" DepartureTime="2015-12-30T22:10:00.000+03:00" ArrivalTime="2015-12-31T04:30:00.000+05:30" FlightTime="230" Distance="1714" ETicketability="Yes" Equipment="747" ChangeOfPlane="false" OptionalServicesIndicator="false">
                    <air:AirAvailInfo ProviderCode="1P">
                        <air:BookingCodeInfo BookingCounts="J4|C4|Y9|N9|K9|M9|B9|H9|L8"/>
                    </air:AirAvailInfo>
                    <air:FlightDetailsRef Key="i7uhdtG5T4uRFzzlBEixFQ=="/>
                </air:AirSegment>
                <air:AirSegment Key="h8sva1J+TE6pBqF8lJzZqQ==" Group="0" Carrier="AI" FlightNumber="563" Origin="MAA" Destination="BLR" DepartureTime="2015-12-31T14:20:00.000+05:30" ArrivalTime="2015-12-31T15:20:00.000+05:30" FlightTime="60" Distance="168" ETicketability="Yes" Equipment="319" ChangeOfPlane="false" OptionalServicesIndicator="false">
                    <air:AirAvailInfo ProviderCode="1P">
                        <air:BookingCodeInfo BookingCounts="C4|D4|J4|Z4|Y9|B4|M4|H4|K4|Q4|V4|W4|G4|L4|U4|T4|S4|E4|N4"/>
                    </air:AirAvailInfo>
                    <air:FlightDetailsRef Key="KfXPdHapR8O+9/9R+7mLvg=="/>
                </air:AirSegment>
                <air:AirSegment Key="K6ewDLHXS7ialSD9/l+mSg==" Group="0" Carrier="AI" FlightNumber="608" Origin="BLR" Destination="BOM" DepartureTime="2015-12-31T17:20:00.000+05:30" ArrivalTime="2015-12-31T19:25:00.000+05:30" FlightTime="125" Distance="519" ETicketability="Yes" Equipment="319" ChangeOfPlane="false" OptionalServicesIndicator="false">
                    <air:AirAvailInfo ProviderCode="1P">
                        <air:BookingCodeInfo BookingCounts="C4|D4|J4|Z4|Y9|B4|M4|H4|K4|Q4|V4|W4|G4|L4|U4|T4|S4|E4|N4"/>
                    </air:AirAvailInfo>
                    <air:FlightDetailsRef Key="qgvEWxk+R26BbJvgOryxjQ=="/>
                </air:AirSegment>
                <air:AirSegment Key="f90MbwBQSqWb59Ilu9sVlA==" Group="0" Carrier="UL" FlightNumber="121" Origin="CMB" Destination="MAA" DepartureTime="2015-12-30T07:20:00.000+05:30" ArrivalTime="2015-12-30T08:40:00.000+05:30" FlightTime="80" Distance="402" ETicketability="Yes" Equipment="332" ChangeOfPlane="false" OptionalServicesIndicator="false">
                    <air:AirAvailInfo ProviderCode="1P">
                        <air:BookingCodeInfo BookingCounts="J4|C4|D4|I4|Y7|B7|P4|H7|K7|W4|M7|E4|L7|R4|V7|S4|N7|Q4|O4"/>
                    </air:AirAvailInfo>
                    <air:FlightDetailsRef Key="tSY8c+hZQ9qTY8nEg3kzzQ=="/>
                </air:AirSegment>
                <air:AirSegment Key="+nnddfIcQbS2G7jSW1lxLA==" Group="0" Carrier="9W" FlightNumber="460" Origin="MAA" Destination="BOM" DepartureTime="2015-12-30T11:25:00.000+05:30" ArrivalTime="2015-12-30T13:15:00.000+05:30" FlightTime="110" Distance="644" ETicketability="Yes" Equipment="737" ChangeOfPlane="false" OptionalServicesIndicator="false">
                    <air:AirAvailInfo ProviderCode="1P">
                        <air:BookingCodeInfo BookingCounts="C7|J7|Z7|I7|P7|Y7|M7|T7|U7|N7|L7|Q7|S7|K7"/>
                    </air:AirAvailInfo>
                    <air:FlightDetailsRef Key="wJqRUfH1RDSCks37FcPC5w=="/>
                </air:AirSegment>
                <air:AirSegment Key="66jMUncSQHysn0acgiu4zw==" Group="0" Carrier="UL" FlightNumber="123" Origin="CMB" Destination="MAA" DepartureTime="2015-12-30T18:35:00.000+05:30" ArrivalTime="2015-12-30T19:55:00.000+05:30" FlightTime="80" Distance="402" ETicketability="Yes" Equipment="321" ChangeOfPlane="false" OptionalServicesIndicator="false">
                    <air:CodeshareInfo>UL USING MIHIN ACFT</air:CodeshareInfo>
                    <air:AirAvailInfo ProviderCode="1P">
                        <air:BookingCodeInfo BookingCounts="J4|C4|D4|I4|Y7|B7|P4|H7|K7|W4|M7|E4|L7|R4|V7|S4|N7|Q4|O4"/>
                    </air:AirAvailInfo>
                    <air:FlightDetailsRef Key="8aue71cFRf62dLqy/4O+Vw=="/>
                </air:AirSegment>
                <air:AirSegment Key="hwqO5C39TkuzI8J81zb9uw==" Group="0" Carrier="9W" FlightNumber="7012" Origin="MAA" Destination="BOM" DepartureTime="2015-12-31T02:35:00.000+05:30" ArrivalTime="2015-12-31T04:30:00.000+05:30" FlightTime="115" Distance="644" ETicketability="Yes" Equipment="73G" ChangeOfPlane="false" OptionalServicesIndicator="false">
                    <air:CodeshareInfo>JETKONNECT</air:CodeshareInfo>
                    <air:AirAvailInfo ProviderCode="1P">
                        <air:BookingCodeInfo BookingCounts="C7|J7|Z7|I7|P7|Y7|M7|T7|U7|N7|L7|Q7|S7|K7|H7|V7|O7"/>
                    </air:AirAvailInfo>
                    <air:FlightDetailsRef Key="s8+CESe0Qc+qppbGWZYOAw=="/>
                </air:AirSegment>
                <air:AirSegment Key="1OxefzSvRSua5Aa0FhLWJQ==" Group="0" Carrier="UL" FlightNumber="173" Origin="CMB" Destination="BLR" DepartureTime="2015-12-30T01:15:00.000+05:30" ArrivalTime="2015-12-30T02:35:00.000+05:30" FlightTime="80" Distance="442" ETicketability="Yes" Equipment="321" ChangeOfPlane="false" OptionalServicesIndicator="false">
                    <air:CodeshareInfo>UL USING MIHIN ACFT</air:CodeshareInfo>
                    <air:AirAvailInfo ProviderCode="1P">
                        <air:BookingCodeInfo BookingCounts="J4|C4|D4|I4|Y7|B7|P4|H7|K7|W4|M7|E4|L7|R4|V7|S4|N7|Q4|O4"/>
                    </air:AirAvailInfo>
                    <air:FlightDetailsRef Key="gUUn5WUjSCKOdTJapJOpeA=="/>
                </air:AirSegment>
                <air:AirSegment Key="t4OE0jfGQ3OXpZ/2fwT5zA==" Group="0" Carrier="9W" FlightNumber="410" Origin="BLR" Destination="BOM" DepartureTime="2015-12-30T05:50:00.000+05:30" ArrivalTime="2015-12-30T07:25:00.000+05:30" FlightTime="95" Distance="519" ETicketability="Yes" Equipment="738" ChangeOfPlane="false" OptionalServicesIndicator="false">
                    <air:AirAvailInfo ProviderCode="1P">
                        <air:BookingCodeInfo BookingCounts="C7|J7|Z7|I7|P7|Y7|M7|T7|U7|N7|L7|Q7|S7|K7|H7|V7|O7|W7|B7"/>
                    </air:AirAvailInfo>
                    <air:FlightDetailsRef Key="pyq2EE06TGy7ZfMMiJ0ZYQ=="/>
                </air:AirSegment>
                <air:AirSegment Key="PLHLO28JSTiIkZ70ezTAjA==" Group="0" Carrier="QR" FlightNumber="665" Origin="CMB" Destination="DOH" DepartureTime="2015-12-30T09:25:00.000+05:30" ArrivalTime="2015-12-30T11:55:00.000+03:00" FlightTime="300" Distance="2245" ETicketability="Yes" Equipment="346" ChangeOfPlane="false" OptionalServicesIndicator="false">
                    <air:AirAvailInfo ProviderCode="1P">
                        <air:BookingCodeInfo BookingCounts="J9|C9|D9|I9|Y9|B9|H9|K9|M9|L9|V9|S9|N9|Q9|T9|O9"/>
                    </air:AirAvailInfo>
                    <air:FlightDetailsRef Key="AAyuN5WhSmSPQ+tlLs/CkQ=="/>
                </air:AirSegment>
                <air:AirSegment Key="nYcBG+HSRtWNpTFCt/2heg==" Group="0" Carrier="QR" FlightNumber="556" Origin="DOH" Destination="BOM" DepartureTime="2015-12-30T20:20:00.000+03:00" ArrivalTime="2015-12-31T02:00:00.000+05:30" FlightTime="190" Distance="1424" ETicketability="Yes" Equipment="77W" ChangeOfPlane="false" OptionalServicesIndicator="false">
                    <air:AirAvailInfo ProviderCode="1P">
                        <air:BookingCodeInfo BookingCounts="J9|C9|D9|I9|Y9|B9|H9|K9|M9|L9|V9|S9|N9|Q9|T9|O9"/>
                    </air:AirAvailInfo>
                    <air:FlightDetailsRef Key="pCPYqaTKRxGn8w2ACbvLbA=="/>
                </air:AirSegment>
                <air:AirSegment Key="xijIeFvuSr6K8DHHX8qk6w==" Group="0" Carrier="UL" FlightNumber="125" Origin="CMB" Destination="MAA" DepartureTime="2015-12-30T00:40:00.000+05:30" ArrivalTime="2015-12-30T02:00:00.000+05:30" FlightTime="80" Distance="402" ETicketability="Yes" Equipment="320" ChangeOfPlane="false" OptionalServicesIndicator="false">
                    <air:AirAvailInfo ProviderCode="1P">
                        <air:BookingCodeInfo BookingCounts="J4|C4|D4|I4|Y7|B7|P4|H7|K7|W4|M7|E4|L7|R4|V7|S4|N7|Q4|O4"/>
                    </air:AirAvailInfo>
                    <air:FlightDetailsRef Key="aY41THQxSLKLNVmptAxd1g=="/>
                </air:AirSegment>
                <air:AirSegment Key="weDvdh5hS2CMyjkof6NMuQ==" Group="0" Carrier="AI" FlightNumber="569" Origin="MAA" Destination="BOM" DepartureTime="2015-12-30T06:20:00.000+05:30" ArrivalTime="2015-12-30T08:10:00.000+05:30" FlightTime="110" Distance="644" ETicketability="Yes" Equipment="319" ChangeOfPlane="false" OptionalServicesIndicator="false">
                    <air:AirAvailInfo ProviderCode="1P">
                        <air:BookingCodeInfo BookingCounts="C4|D4|J4|Z4|Y9|B4|M4|H4|K4|Q4|V4|W4|G4|L4|U4|T4|S4|E4|N4"/>
                    </air:AirAvailInfo>
                    <air:FlightDetailsRef Key="4DGasBVYRFqyPi0mnFakvQ=="/>
                </air:AirSegment>
                <air:AirSegment Key="wXh2eq+3TYSnj08zmsbg9A==" Group="0" Carrier="UL" FlightNumber="167" Origin="CMB" Destination="COK" DepartureTime="2015-12-30T14:05:00.000+05:30" ArrivalTime="2015-12-30T15:25:00.000+05:30" FlightTime="80" Distance="312" ETicketability="Yes" Equipment="320" ChangeOfPlane="false" OptionalServicesIndicator="false">
                    <air:AirAvailInfo ProviderCode="1P">
                        <air:BookingCodeInfo BookingCounts="J4|C4|D4|I4|Y7|B7|P4|H7|K7|W4|M7|E4|L7|R4|V7|S4|N7|Q4|O4"/>
                    </air:AirAvailInfo>
                    <air:FlightDetailsRef Key="GY+qsQ42SOO5++TalG/92Q=="/>
                </air:AirSegment>
                <air:AirSegment Key="FZ3EP+wCSAO/dTlGXxuirg==" Group="0" Carrier="AI" FlightNumber="55" Origin="COK" Destination="BOM" DepartureTime="2015-12-30T22:00:00.000+05:30" ArrivalTime="2015-12-30T23:45:00.000+05:30" FlightTime="105" Distance="672" ETicketability="Yes" Equipment="320" ChangeOfPlane="false" OptionalServicesIndicator="false">
                    <air:AirAvailInfo ProviderCode="1P">
                        <air:BookingCodeInfo BookingCounts="Y9|B4|M4|H4|K4|Q4|V4|W4|G4|L4|U4|T4|S4|E4|N4"/>
                    </air:AirAvailInfo>
                    <air:FlightDetailsRef Key="b62DTAMvQ72FOdQQNR260A=="/>
                </air:AirSegment>
                <air:AirSegment Key="76aWqUpsTRyj2+iR6l/uHw==" Group="0" Carrier="AI" FlightNumber="640" Origin="BLR" Destination="BOM" DepartureTime="2015-12-30T06:45:00.000+05:30" ArrivalTime="2015-12-30T08:20:00.000+05:30" FlightTime="95" Distance="519" ETicketability="Yes" Equipment="319" ChangeOfPlane="false" OptionalServicesIndicator="false">
                    <air:AirAvailInfo ProviderCode="1P">
                        <air:BookingCodeInfo BookingCounts="C4|D4|J4|Z4|Y9|B4|M4|H4|K4|Q4|V4|W4|G4|L4|U4|T4|S4|E4|N4"/>
                    </air:AirAvailInfo>
                    <air:FlightDetailsRef Key="NN+I3xxzS3uodBXHbaLNYw=="/>
                </air:AirSegment>
                <air:AirSegment Key="dNdj5yZ/QJeItY9bVAzorg==" Group="0" Carrier="UL" FlightNumber="2925" Origin="CMB" Destination="MCT" DepartureTime="2015-12-30T19:05:00.000+05:30" ArrivalTime="2015-12-30T21:55:00.000+04:00" FlightTime="260" Distance="1816" ETicketability="Yes" Equipment="320" ChangeOfPlane="false" OptionalServicesIndicator="false">
                    <air:CodeshareInfo OperatingCarrier="MJ" OperatingFlightNumber="205">MIHIN LANKA</air:CodeshareInfo>
                    <air:AirAvailInfo ProviderCode="1P">
                        <air:BookingCodeInfo BookingCounts="J4|C4|D4|I4|Y7|B7|P4|H7|K7|W4|M7|E4|L7|R4|V7|S4|N7|Q4|O4"/>
                    </air:AirAvailInfo>
                    <air:FlightDetailsRef Key="iBTeLbTtTCym7UAJuE+xUw=="/>
                </air:AirSegment>
                <air:AirSegment Key="QrsjEjXJQUOZMuFsVAAo6Q==" Group="0" Carrier="AI" FlightNumber="986" Origin="MCT" Destination="BOM" DepartureTime="2015-12-31T00:20:00.000+04:00" ArrivalTime="2015-12-31T04:45:00.000+05:30" FlightTime="175" Distance="974" ETicketability="Yes" Equipment="321" ChangeOfPlane="false" OptionalServicesIndicator="false">
                    <air:AirAvailInfo ProviderCode="1P">
                        <air:BookingCodeInfo BookingCounts="C4|D4|J4|Z4|Y9|B4|M4|H4|K4|Q4|V4|W4|G4|L4|U4|T4|S4|E4|N4"/>
                    </air:AirAvailInfo>
                    <air:FlightDetailsRef Key="zWNb+9aqTBKgj0wOyuquXA=="/>
                </air:AirSegment>
                <air:AirSegment Key="UNIWeyDWRTq9AZXzz/cZGg==" Group="0" Carrier="EY" FlightNumber="265" Origin="CMB" Destination="AUH" DepartureTime="2015-12-30T21:00:00.000+05:30" ArrivalTime="2015-12-31T00:25:00.000+04:00" FlightTime="295" Distance="2061" ETicketability="Yes" Equipment="320" ChangeOfPlane="false" OptionalServicesIndicator="false">
                    <air:AirAvailInfo ProviderCode="1P">
                        <air:BookingCodeInfo BookingCounts="J7|C7|D7|W7|Z7|Y7|B7|H7|K7"/>
                    </air:AirAvailInfo>
                    <air:FlightDetailsRef Key="Q2M5BR1gTzm6cYeqKF704w=="/>
                </air:AirSegment>
                <air:AirSegment Key="oVEcNwOeQ+qwN71iIo5Cgg==" Group="0" Carrier="EY" FlightNumber="212" Origin="AUH" Destination="BOM" DepartureTime="2015-12-31T03:10:00.000+04:00" ArrivalTime="2015-12-31T08:15:00.000+05:30" FlightTime="215" Distance="1237" ETicketability="Yes" Equipment="320" ChangeOfPlane="false" OptionalServicesIndicator="false">
                    <air:AirAvailInfo ProviderCode="1P">
                        <air:BookingCodeInfo BookingCounts="J7|C7|D7|W7|Z7|Y7|B7|H7|K7"/>
                    </air:AirAvailInfo>
                    <air:FlightDetailsRef Key="qxHq1wLtQvWMbjmwjeCrGw=="/>
                </air:AirSegment>
                <air:AirSegment Key="D4lv+RGmRpGDZiTXOO2vnA==" Group="0" Carrier="HM" FlightNumber="5523" Origin="CMB" Destination="AUH" DepartureTime="2015-12-30T21:00:00.000+05:30" ArrivalTime="2015-12-31T00:25:00.000+04:00" FlightTime="295" Distance="2061" ETicketability="Yes" Equipment="320" ChangeOfPlane="false" OptionalServicesIndicator="false">
                    <air:CodeshareInfo OperatingCarrier="EY" OperatingFlightNumber="265">ETIHAD AIRWAYS</air:CodeshareInfo>
                    <air:AirAvailInfo ProviderCode="1P">
                        <air:BookingCodeInfo BookingCounts="J4|D4|C4|I4|Y9|B9|H9|K9|L9|W9|S9|M9|N9|V9|G9"/>
                    </air:AirAvailInfo>
                    <air:FlightDetailsRef Key="Q2M5BR1gTzm6cYeqKF704w=="/>
                </air:AirSegment>
                <air:AirSegment Key="4G665u4dT7ezTYmxfQ4UbQ==" Group="0" Carrier="HM" FlightNumber="5320" Origin="AUH" Destination="BOM" DepartureTime="2015-12-31T03:10:00.000+04:00" ArrivalTime="2015-12-31T08:15:00.000+05:30" FlightTime="215" Distance="1237" ETicketability="Yes" Equipment="320" ChangeOfPlane="false" OptionalServicesIndicator="false">
                    <air:CodeshareInfo OperatingCarrier="EY" OperatingFlightNumber="212">ETIHAD AIRWAYS</air:CodeshareInfo>
                    <air:AirAvailInfo ProviderCode="1P">
                        <air:BookingCodeInfo BookingCounts="J4|D4|C4|I4|Y9|B9|H9|K9|L9|W9|S9|M9|N9|V9|G9"/>
                    </air:AirAvailInfo>
                    <air:FlightDetailsRef Key="qxHq1wLtQvWMbjmwjeCrGw=="/>
                </air:AirSegment>
                <air:AirSegment Key="35NDQM4vSBimE3dhLngNpA==" Group="0" Carrier="SQ" FlightNumber="469" Origin="CMB" Destination="SIN" DepartureTime="2015-12-30T01:10:00.000+05:30" ArrivalTime="2015-12-30T07:40:00.000+08:00" FlightTime="240" Distance="1709" ETicketability="Yes" Equipment="333" ChangeOfPlane="false" OptionalServicesIndicator="false">
                    <air:AirAvailInfo ProviderCode="1P">
                        <air:BookingCodeInfo BookingCounts="Z9|C9|J9|U9|D9|I2|Y9|B9|E9|M8|H2"/>
                    </air:AirAvailInfo>
                    <air:FlightDetailsRef Key="LPgO6qF4TZyKmUK8V+Sn6A=="/>
                </air:AirSegment>
                <air:AirSegment Key="DO38sICKQwCJpqLfnEXDRw==" Group="0" Carrier="SQ" FlightNumber="424" Origin="SIN" Destination="BOM" DepartureTime="2015-12-30T18:55:00.000+08:00" ArrivalTime="2015-12-30T21:45:00.000+05:30" FlightTime="320" Distance="2437" ETicketability="Yes" Equipment="388" ChangeOfPlane="false" OptionalServicesIndicator="false">
                    <air:AirAvailInfo ProviderCode="1P">
                        <air:BookingCodeInfo BookingCounts="R9|F3|A2|Z9|C9|J9|U5|D2|I2|Y9|B9|E9|M9|H9|W9|Q9|N9|V9|L9|K9|X4"/>
                    </air:AirAvailInfo>
                    <air:FlightDetailsRef Key="+sG5rvtiTS6+FhEMywX1cA=="/>
                </air:AirSegment>
                <air:AirSegment Key="zGx0vkDWQG6UpfSZuNvWZQ==" Group="0" Carrier="QF" FlightNumber="3420" Origin="CMB" Destination="SIN" DepartureTime="2015-12-30T01:00:00.000+05:30" ArrivalTime="2015-12-30T07:30:00.000+08:00" FlightTime="240" Distance="1709" ETicketability="Yes" Equipment="320" ChangeOfPlane="false" OptionalServicesIndicator="false">
                    <air:CodeshareInfo OperatingCarrier="UL" OperatingFlightNumber="306">SRILANKAN AIRLINES</air:CodeshareInfo>
                    <air:AirAvailInfo ProviderCode="1P">
                        <air:BookingCodeInfo BookingCounts="J9|C9|D9|I3|Y9|B9|H9|K9|M9|L9|V9|S9|N4"/>
                    </air:AirAvailInfo>
                    <air:FlightDetailsRef Key="BQfb4pfdSyOR0tgY11qXHA=="/>
                </air:AirSegment>
                <air:AirSegment Key="0y5sdL/gQOGTycsXY0LD6Q==" Group="0" Carrier="QF" FlightNumber="3957" Origin="SIN" Destination="BOM" DepartureTime="2015-12-30T10:30:00.000+08:00" ArrivalTime="2015-12-30T13:30:00.000+05:30" FlightTime="330" Distance="2437" ETicketability="Yes" Equipment="737" ChangeOfPlane="false" OptionalServicesIndicator="false">
                    <air:CodeshareInfo OperatingCarrier="9W" OperatingFlightNumber="9">JET*AIRWAYS*INDIA*LTD</air:CodeshareInfo>
                    <air:AirAvailInfo ProviderCode="1P">
                        <air:BookingCodeInfo BookingCounts="J9|C9|D9|I9|Y9|B9|H9|K9|M9|L9|V9|S9|N9|Q9"/>
                    </air:AirAvailInfo>
                    <air:FlightDetailsRef Key="X4L5BLbKTDeCvpEynlXb5w=="/>
                </air:AirSegment>
            </air:AirSegmentList>
            <air:FareInfoList>
                <air:FareInfo Key="TuZDJVvgQEmq9ZUxTZ3WSg==" FareBasis="SOW" PassengerTypeCode="ADT" Origin="CMB" Destination="BOM" EffectiveDate="2015-12-28T05:49:00.000+00:00" DepartureDate="2015-12-30" Amount="LKR14000" NegotiatedFare="false" NotValidBefore="2015-12-31" NotValidAfter="2015-12-31">
                    <air:BaggageAllowance>
                        <air:MaxWeight Value="30" Unit="Kilograms"/>
                    </air:BaggageAllowance>
                    <air:FareRuleKey FareInfoRef="TuZDJVvgQEmq9ZUxTZ3WSg==" ProviderCode="1P">dir-CMB^BOM^2015364^ALL^SOW^AI^^</air:FareRuleKey>
                    <air:Brand Key="6MAiNXwgSsyqYacRTNhyHQ==" BrandFound="false" UpSellBrandFound="false"/>
                </air:FareInfo>
                <air:FareInfo Key="AhblwkXVRQC6WMFEKnK7wg==" FareBasis="K2OWLK" PassengerTypeCode="ADT" Origin="CMB" Destination="BOM" EffectiveDate="2015-12-28T05:49:00.000+00:00" DepartureDate="2015-12-30" Amount="LKR19400" NegotiatedFare="false" NotValidBefore="2015-12-30" NotValidAfter="2015-12-30">
                    <air:BaggageAllowance>
                        <air:MaxWeight Value="30" Unit="Kilograms"/>
                    </air:BaggageAllowance>
                    <air:FareRuleKey FareInfoRef="AhblwkXVRQC6WMFEKnK7wg==" ProviderCode="1P">dir-CMB^BOM^2015364^ALL^K2OWLK^9W^^</air:FareRuleKey>
                </air:FareInfo>
                <air:FareInfo Key="2PK7JIGmSomsO5bY5tDj3g==" FareBasis="ROWLK" PassengerTypeCode="ADT" Origin="CMB" Destination="BOM" EffectiveDate="2015-12-28T05:49:00.000+00:00" DepartureDate="2015-12-30" Amount="LKR21900" NegotiatedFare="false" NotValidBefore="2015-12-30" NotValidAfter="2015-12-30">
                    <air:BaggageAllowance>
                        <air:MaxWeight Value="30" Unit="Kilograms"/>
                    </air:BaggageAllowance>
                    <air:FareRuleKey FareInfoRef="2PK7JIGmSomsO5bY5tDj3g==" ProviderCode="1P">dir-CMB^BOM^2015364^ALL^ROWLK^UL^^</air:FareRuleKey>
                </air:FareInfo>
                <air:FareInfo Key="4hE7McgvS66A0aLJpCm4mQ==" FareBasis="REFOWLK" PassengerTypeCode="ADT" Origin="CMB" Destination="BOM" EffectiveDate="2015-12-28T05:49:00.000+00:00" DepartureDate="2015-12-30" Amount="LKR21900" NegotiatedFare="false" NotValidBefore="2015-12-30" NotValidAfter="2015-12-30">
                    <air:BaggageAllowance>
                        <air:MaxWeight Value="30" Unit="Kilograms"/>
                    </air:BaggageAllowance>
                    <air:FareRuleKey FareInfoRef="4hE7McgvS66A0aLJpCm4mQ==" ProviderCode="1P">dir-CMB^BOM^2015364^ALL^REFOWLK^MJ^^</air:FareRuleKey>
                </air:FareInfo>
                <air:FareInfo Key="mSkUl7KcR/a/crv1vuceYA==" FareBasis="TOWLKD" PassengerTypeCode="ADT" Origin="CMB" Destination="BOM" EffectiveDate="2015-12-28T05:49:00.000+00:00" DepartureDate="2015-12-30" Amount="LKR28000" NegotiatedFare="false" NotValidBefore="2015-12-31" NotValidAfter="2015-12-31">
                    <air:BaggageAllowance>
                        <air:MaxWeight Value="30" Unit="Kilograms"/>
                    </air:BaggageAllowance>
                    <air:FareRuleKey FareInfoRef="mSkUl7KcR/a/crv1vuceYA==" ProviderCode="1P">dir-CMB^BOM^2015364^ALL^TOWLKD^AI^^</air:FareRuleKey>
                    <air:Brand Key="SbsntGZhTeKFPhJWKoQOGw==" BrandFound="false" UpSellBrandFound="false"/>
                </air:FareInfo>
                <air:FareInfo Key="gO8UdrdOTOiiD5XKNFRWEQ==" FareBasis="WOWLKD" PassengerTypeCode="ADT" Origin="CMB" Destination="DEL" EffectiveDate="2015-12-28T05:49:00.000+00:00" DepartureDate="2015-12-30" Amount="LKR29000" NegotiatedFare="false" NotValidBefore="2015-12-30" NotValidAfter="2015-12-30">
                    <air:BaggageAllowance>
                        <air:MaxWeight Value="30" Unit="Kilograms"/>
                    </air:BaggageAllowance>
                    <air:FareRuleKey FareInfoRef="gO8UdrdOTOiiD5XKNFRWEQ==" ProviderCode="1P">dir-CMB^DEL^2015364^ALL^WOWLKD^AI^^</air:FareRuleKey>
                    <air:Brand Key="em57A9d6RceWHOHvg8Z7Ew==" BrandFound="false" UpSellBrandFound="false"/>
                </air:FareInfo>
                <air:FareInfo Key="69WLDAOFQc2SDmiQwQvZVQ==" FareBasis="UIPD" PassengerTypeCode="ADT" Origin="DEL" Destination="BLR" EffectiveDate="2015-12-28T05:49:00.000+00:00" DepartureDate="2015-12-30" Amount="LKR3900" NegotiatedFare="false" NotValidBefore="2015-12-30" NotValidAfter="2015-12-30">
                    <air:BaggageAllowance>
                        <air:MaxWeight Value="30" Unit="Kilograms"/>
                    </air:BaggageAllowance>
                    <air:FareRuleKey FareInfoRef="69WLDAOFQc2SDmiQwQvZVQ==" ProviderCode="1P">dir-DEL^BLR^2015364^ALL^UIPD^AI^^</air:FareRuleKey>
                    <air:Brand Key="O0oIROasTImY3KrJTdQU8g==" BrandFound="false" UpSellBrandFound="false"/>
                </air:FareInfo>
                <air:FareInfo Key="t1dugF9jRj+nAxGAgUNE+g==" FareBasis="UIPB" PassengerTypeCode="ADT" Origin="BLR" Destination="BOM" EffectiveDate="2015-12-28T05:49:00.000+00:00" DepartureDate="2015-12-30" Amount="LKR4800" NegotiatedFare="false" NotValidBefore="2015-12-31" NotValidAfter="2015-12-31">
                    <air:BaggageAllowance>
                        <air:MaxWeight Value="30" Unit="Kilograms"/>
                    </air:BaggageAllowance>
                    <air:FareRuleKey FareInfoRef="t1dugF9jRj+nAxGAgUNE+g==" ProviderCode="1P">dir-BLR^BOM^2015364^ALL^UIPB^AI^^</air:FareRuleKey>
                    <air:Brand Key="izXDNnwDSICQ8Lv6TFLhKA==" BrandFound="false" UpSellBrandFound="false"/>
                </air:FareInfo>
                <air:FareInfo Key="JVIVTw1MQ5qvb1Izp53YBg==" FareBasis="VOWLKP" PassengerTypeCode="ADT" Origin="CMB" Destination="BOM" EffectiveDate="2015-12-28T05:49:00.000+00:00" DepartureDate="2015-12-30" Amount="LKR38900" NegotiatedFare="false">
                    <air:FareSurcharge Key="0ZczNQI7QOqmVBzcQRHKZg==" Type="Other" Amount="NUC135.85"/>
                    <air:BaggageAllowance>
                        <air:MaxWeight Value="30" Unit="Kilograms"/>
                    </air:BaggageAllowance>
                    <air:FareRuleKey FareInfoRef="JVIVTw1MQ5qvb1Izp53YBg==" ProviderCode="1P">dir-CMB^BOM^2015364^ALL^VOWLKP^TG^^</air:FareRuleKey>
                    <air:Brand Key="0cC4NVGUR/q1dsg82Vlrug==" BrandFound="false" UpSellBrandFound="false"/>
                </air:FareInfo>
                <air:FareInfo Key="0iTWbgcxT5KqJxFFZjC3XA==" FareBasis="TEE6MLK1" PassengerTypeCode="ADT" Origin="CMB" Destination="KWI" EffectiveDate="2015-12-28T05:49:00.000+00:00" DepartureDate="2015-12-30" Amount="LKR20600" NegotiatedFare="false" NotValidBefore="2015-12-30" NotValidAfter="2015-12-30">
                    <air:BaggageAllowance>
                        <air:MaxWeight Value="20" Unit="Kilograms"/>
                    </air:BaggageAllowance>
                    <air:FareRuleKey FareInfoRef="0iTWbgcxT5KqJxFFZjC3XA==" ProviderCode="1P">dir-CMB^KWI^2015364^ALL^TEE6MLK1^KU^^</air:FareRuleKey>
                </air:FareInfo>
                <air:FareInfo Key="1L/PknWUSbq3OLixZaXMSQ==" FareBasis="LRMF3M" PassengerTypeCode="ADT" Origin="KWI" Destination="BOM" EffectiveDate="2015-12-28T05:49:00.000+00:00" DepartureDate="2015-12-30" Amount="LKR19700" NegotiatedFare="false" NotValidAfter="2016-03-30">
                    <air:BaggageAllowance>
                        <air:MaxWeight Value="20" Unit="Kilograms"/>
                    </air:BaggageAllowance>
                    <air:FareRuleKey FareInfoRef="1L/PknWUSbq3OLixZaXMSQ==" ProviderCode="1P">dir-KWI^BOM^2015364^ALL^LRMF3M^KU^^</air:FareRuleKey>
                </air:FareInfo>
                <air:FareInfo Key="N8f9bATgSJegYdLTuxxbEQ==" FareBasis="WOWLKD" PassengerTypeCode="ADT" Origin="CMB" Destination="BLR" EffectiveDate="2015-12-28T05:49:00.000+00:00" DepartureDate="2015-12-30" Amount="LKR35600" NegotiatedFare="false" NotValidBefore="2015-12-31" NotValidAfter="2015-12-31">
                    <air:BaggageAllowance>
                        <air:MaxWeight Value="30" Unit="Kilograms"/>
                    </air:BaggageAllowance>
                    <air:FareRuleKey FareInfoRef="N8f9bATgSJegYdLTuxxbEQ==" ProviderCode="1P">dir-CMB^BLR^2015364^ALL^WOWLKD^AI^^</air:FareRuleKey>
                    <air:Brand Key="naSOtAdVTlaaNuHWsz5bmQ==" BrandFound="false" UpSellBrandFound="false"/>
                </air:FareInfo>
                <air:FareInfo Key="GDWwSZcqRIy4w4KZpuWqPA==" FareBasis="QOWLK" PassengerTypeCode="ADT" Origin="CMB" Destination="MAA" EffectiveDate="2015-12-28T05:49:00.000+00:00" DepartureDate="2015-12-30" Amount="LKR9800" NegotiatedFare="false" NotValidBefore="2015-12-30" NotValidAfter="2015-12-30">
                    <air:BaggageAllowance>
                        <air:MaxWeight Value="30" Unit="Kilograms"/>
                    </air:BaggageAllowance>
                    <air:FareRuleKey FareInfoRef="GDWwSZcqRIy4w4KZpuWqPA==" ProviderCode="1P">dir-CMB^MAA^2015364^ALL^QOWLK^UL^^</air:FareRuleKey>
                </air:FareInfo>
                <air:FareInfo Key="aTdXioKVRAOB8ZfPQPqBQw==" FareBasis="Y2OW" PassengerTypeCode="ADT" Origin="MAA" Destination="BOM" EffectiveDate="2015-12-28T05:49:00.000+00:00" DepartureDate="2015-12-30" Amount="LKR47200" NegotiatedFare="false" NotValidBefore="2015-12-31" NotValidAfter="2015-12-31">
                    <air:FareSurcharge Key="fUl1Vb3uQfmtmUfD1jOvEA==" Type="Other" Amount="NUC20.00"/>
                    <air:BaggageAllowance>
                        <air:MaxWeight Value="30" Unit="Kilograms"/>
                    </air:BaggageAllowance>
                    <air:FareRuleKey FareInfoRef="aTdXioKVRAOB8ZfPQPqBQw==" ProviderCode="1P">dir-MAA^BOM^2015364^ALL^Y2OW^9W^^</air:FareRuleKey>
                </air:FareInfo>
                <air:FareInfo Key="ylmLppvLQxS1gVu6YrSmXQ==" FareBasis="ROWLK" PassengerTypeCode="ADT" Origin="CMB" Destination="BLR" EffectiveDate="2015-12-28T05:49:00.000+00:00" DepartureDate="2015-12-30" Amount="LKR15000" NegotiatedFare="false" NotValidBefore="2015-12-30" NotValidAfter="2015-12-30">
                    <air:BaggageAllowance>
                        <air:MaxWeight Value="30" Unit="Kilograms"/>
                    </air:BaggageAllowance>
                    <air:FareRuleKey FareInfoRef="ylmLppvLQxS1gVu6YrSmXQ==" ProviderCode="1P">dir-CMB^BLR^2015364^ALL^ROWLK^UL^^</air:FareRuleKey>
                </air:FareInfo>
                <air:FareInfo Key="n3Sh9LBgSkqTQ8/T3DjhCg==" FareBasis="Y2OW" PassengerTypeCode="ADT" Origin="BLR" Destination="BOM" EffectiveDate="2015-12-28T05:49:00.000+00:00" DepartureDate="2015-12-30" Amount="LKR43600" NegotiatedFare="false" NotValidBefore="2015-12-30" NotValidAfter="2015-12-30">
                    <air:FareSurcharge Key="iH3Baq8PSKK0vH2KkE6ahA==" Type="Other" Amount="NUC20.00"/>
                    <air:BaggageAllowance>
                        <air:MaxWeight Value="30" Unit="Kilograms"/>
                    </air:BaggageAllowance>
                    <air:FareRuleKey FareInfoRef="n3Sh9LBgSkqTQ8/T3DjhCg==" ProviderCode="1P">dir-BLR^BOM^2015364^ALL^Y2OW^9W^^</air:FareRuleKey>
                </air:FareInfo>
                <air:FareInfo Key="1zPnV1WpTOOSCyJd0E4T2A==" FareBasis="NJR4R1RI" PassengerTypeCode="ADT" Origin="CMB" Destination="BOM" EffectiveDate="2015-12-28T05:49:00.000+00:00" DepartureDate="2015-12-30" Amount="LKR65700" NegotiatedFare="false" NotValidBefore="2015-12-30" NotValidAfter="2015-12-30">
                    <air:BaggageAllowance>
                        <air:MaxWeight Value="30" Unit="Kilograms"/>
                    </air:BaggageAllowance>
                    <air:FareRuleKey FareInfoRef="1zPnV1WpTOOSCyJd0E4T2A==" ProviderCode="1P">dir-CMB^BOM^2015364^ALL^NJR4R1RI^QR^^</air:FareRuleKey>
                </air:FareInfo>
                <air:FareInfo Key="w3Yn4NV2Rd+v4IT9UmpT3Q==" FareBasis="YIF" PassengerTypeCode="ADT" Origin="CMB" Destination="BOM" EffectiveDate="2015-12-28T05:49:00.000+00:00" DepartureDate="2015-12-30" Amount="LKR76000" NegotiatedFare="false">
                    <air:BaggageAllowance>
                        <air:MaxWeight Value="30" Unit="Kilograms"/>
                    </air:BaggageAllowance>
                    <air:FareRuleKey FareInfoRef="w3Yn4NV2Rd+v4IT9UmpT3Q==" ProviderCode="1P">dir-CMB^BOM^2015364^ALL^YIF^YY^^</air:FareRuleKey>
                </air:FareInfo>
                <air:FareInfo Key="RwAUVJ8nRqqXhP3m4rAxGw==" FareBasis="QOWMJLK" PassengerTypeCode="ADT" Origin="CMB" Destination="MCT" EffectiveDate="2015-12-28T05:49:00.000+00:00" DepartureDate="2015-12-30" Amount="LKR14200" NegotiatedFare="false" NotValidBefore="2015-12-30" NotValidAfter="2015-12-30">
                    <air:FareSurcharge Key="hnNEmH/YTFGoW+I2gkcrHA==" Type="Other" Amount="NUC3.00"/>
                    <air:BaggageAllowance>
                        <air:MaxWeight Value="30" Unit="Kilograms"/>
                    </air:BaggageAllowance>
                    <air:FareRuleKey FareInfoRef="RwAUVJ8nRqqXhP3m4rAxGw==" ProviderCode="1P">dir-CMB^MCT^2015364^ALL^QOWMJLK^UL^^</air:FareRuleKey>
                </air:FareInfo>
                <air:FareInfo Key="0fI0BJ/1Rwe9lMP/VUIgQA==" FareBasis="Y" PassengerTypeCode="ADT" Origin="MCT" Destination="BOM" EffectiveDate="2015-12-28T05:49:00.000+00:00" DepartureDate="2015-12-30" Amount="LKR70500" NegotiatedFare="false">
                    <air:BaggageAllowance>
                        <air:MaxWeight Value="30" Unit="Kilograms"/>
                    </air:BaggageAllowance>
                    <air:FareRuleKey FareInfoRef="0fI0BJ/1Rwe9lMP/VUIgQA==" ProviderCode="1P">dir-MCT^BOM^2015364^ALL^Y^YY^^</air:FareRuleKey>
                    <air:Brand Key="UpWUPELVRz6CL+PZdyTdZw==" BrandFound="false" UpSellBrandFound="false"/>
                </air:FareInfo>
                <air:FareInfo Key="TGq7WcqxQ5OBvvHEm2JK8w==" FareBasis="YRTLK" PassengerTypeCode="ADT" Origin="CMB" Destination="AUH" EffectiveDate="2015-12-28T05:49:00.000+00:00" DepartureDate="2015-12-30" Amount="LKR54900" NegotiatedFare="false" NotValidAfter="2016-12-30">
                    <air:FareTicketDesignator Value="YF"/>
                    <air:FareSurcharge Key="0IS0VcxqSoy6r9Nt6vXl7A==" Type="Other" Amount="NUC52.00"/>
                    <air:BaggageAllowance>
                        <air:NumberOfPieces>2</air:NumberOfPieces>
                        <air:MaxWeight/>
                    </air:BaggageAllowance>
                    <air:FareRuleKey FareInfoRef="TGq7WcqxQ5OBvvHEm2JK8w==" ProviderCode="1P">dir-CMB^AUH^2015364^ALL^YRTLK^EY^^</air:FareRuleKey>
                    <air:Brand Key="x5W3kRi0T42yw65iIiO1gA==" BrandFound="false" UpSellBrandFound="false"/>
                </air:FareInfo>
                <air:FareInfo Key="GPvzaK9tTzqjKluo7iZb9w==" FareBasis="YRT11" PassengerTypeCode="ADT" Origin="AUH" Destination="BOM" EffectiveDate="2015-12-28T05:49:00.000+00:00" DepartureDate="2015-12-30" Amount="LKR128800" NegotiatedFare="false" NotValidAfter="2016-12-30">
                    <air:FareTicketDesignator Value="YF"/>
                    <air:BaggageAllowance>
                        <air:NumberOfPieces>2</air:NumberOfPieces>
                        <air:MaxWeight/>
                    </air:BaggageAllowance>
                    <air:FareRuleKey FareInfoRef="GPvzaK9tTzqjKluo7iZb9w==" ProviderCode="1P">dir-AUH^BOM^2015364^ALL^YRT11^EY^^</air:FareRuleKey>
                    <air:Brand Key="6OuK578uR7qEonfpojZ6TA==" BrandFound="false" UpSellBrandFound="false"/>
                </air:FareInfo>
                <air:FareInfo Key="ewtt09q1QUmecbwuxmRy4w==" FareBasis="YIF" PassengerTypeCode="ADT" Origin="CMB" Destination="AUH" EffectiveDate="2015-12-28T05:49:00.000+00:00" DepartureDate="2015-12-30" Amount="LKR105000" NegotiatedFare="false">
                    <air:BaggageAllowance>
                        <air:NumberOfPieces>2</air:NumberOfPieces>
                        <air:MaxWeight/>
                    </air:BaggageAllowance>
                    <air:FareRuleKey FareInfoRef="ewtt09q1QUmecbwuxmRy4w==" ProviderCode="1P">dir-CMB^AUH^2015364^ALL^YIF^YY^^</air:FareRuleKey>
                </air:FareInfo>
                <air:FareInfo Key="9QFGnYmORCKxeN2iDiIXXA==" FareBasis="YIF" PassengerTypeCode="ADT" Origin="AUH" Destination="BOM" EffectiveDate="2015-12-28T05:49:00.000+00:00" DepartureDate="2015-12-30" Amount="LKR121300" NegotiatedFare="false">
                    <air:BaggageAllowance>
                        <air:NumberOfPieces>2</air:NumberOfPieces>
                        <air:MaxWeight/>
                    </air:BaggageAllowance>
                    <air:FareRuleKey FareInfoRef="9QFGnYmORCKxeN2iDiIXXA==" ProviderCode="1P">dir-AUH^BOM^2015364^ALL^YIF^YY^^</air:FareRuleKey>
                </air:FareInfo>
                <air:FareInfo Key="hGXrS/5oSeOKDKiyWDSOmg==" FareBasis="YRTLK6" PassengerTypeCode="ADT" Origin="CMB" Destination="SIN" EffectiveDate="2015-12-28T05:49:00.000+00:00" DepartureDate="2015-12-30" Amount="LKR61900" NegotiatedFare="false" NotValidAfter="2016-12-30">
                    <air:BaggageAllowance>
                        <air:MaxWeight Value="30" Unit="Kilograms"/>
                    </air:BaggageAllowance>
                    <air:FareRuleKey FareInfoRef="hGXrS/5oSeOKDKiyWDSOmg==" ProviderCode="1P">dir-CMB^SIN^2015364^ALL^YRTLK6^SQ^^</air:FareRuleKey>
                    <air:Brand Key="T7PMCYFdT4mB86+PuGjpUA==" BrandID="5705" UpSellBrandFound="false"/>
                </air:FareInfo>
                <air:FareInfo Key="NwA4s1AlTyqIdeQd9cGzJw==" FareBasis="YIF" PassengerTypeCode="ADT" Origin="SIN" Destination="BOM" EffectiveDate="2015-12-28T05:49:00.000+00:00" DepartureDate="2015-12-30" Amount="LKR250100" NegotiatedFare="false" NotValidAfter="2016-12-30">
                    <air:BaggageAllowance>
                        <air:MaxWeight Value="30" Unit="Kilograms"/>
                    </air:BaggageAllowance>
                    <air:FareRuleKey FareInfoRef="NwA4s1AlTyqIdeQd9cGzJw==" ProviderCode="1P">dir-SIN^BOM^2015364^ALL^YIF^YY^^</air:FareRuleKey>
                    <air:Brand Key="5GH0xkHwR2ygIoJHc6YboQ==" BrandID="5705" UpSellBrandFound="false"/>
                </air:FareInfo>
                <air:FareInfo Key="I+jbjgnpRoWcOtHxAPcaPg==" FareBasis="YIF" PassengerTypeCode="ADT" Origin="CMB" Destination="SIN" EffectiveDate="2015-12-28T05:49:00.000+00:00" DepartureDate="2015-12-30" Amount="LKR71500" NegotiatedFare="false">
                    <air:BaggageAllowance>
                        <air:MaxWeight Value="30" Unit="Kilograms"/>
                    </air:BaggageAllowance>
                    <air:FareRuleKey FareInfoRef="I+jbjgnpRoWcOtHxAPcaPg==" ProviderCode="1P">dir-CMB^SIN^2015364^ALL^YIF^YY^^</air:FareRuleKey>
                </air:FareInfo>
                <air:FareInfo Key="K39nDbT+TgiCfwtVEPU2xA==" FareBasis="YIF" PassengerTypeCode="ADT" Origin="SIN" Destination="BOM" EffectiveDate="2015-12-28T05:49:00.000+00:00" DepartureDate="2015-12-30" Amount="LKR250100" NegotiatedFare="false">
                    <air:BaggageAllowance>
                        <air:MaxWeight Value="30" Unit="Kilograms"/>
                    </air:BaggageAllowance>
                    <air:FareRuleKey FareInfoRef="K39nDbT+TgiCfwtVEPU2xA==" ProviderCode="1P">dir-SIN^BOM^2015364^ALL^YIF^YY^^</air:FareRuleKey>
                </air:FareInfo>
            </air:FareInfoList>
            <air:RouteList>
                <air:Route Key="sJCcLnKlS5mQ/NMUZow/Xw==">
                    <air:Leg Key="Yy/n9UuxR+uMskWhMvyx2A==" Group="0" Origin="CMB" Destination="BOM"/>
                </air:Route>
            </air:RouteList>
            <air:AirPricePointList>
                <air:AirPricePoint Key="n/PESxXuR6yWWt7Cmq5TSg==" TotalPrice="LKR17500" BasePrice="LKR14000" ApproximateTotalPrice="LKR17500" ApproximateBasePrice="LKR14000" Taxes="LKR3500" ApproximateTaxes="LKR3500" CompleteItinerary="true">
                    <air:AirPricingInfo Key="zh5MJfIzSruSZHcoaMNM8g==" TotalPrice="LKR17500" BasePrice="LKR14000" ApproximateTotalPrice="LKR17500" ApproximateBasePrice="LKR14000" Taxes="LKR3500" LatestTicketingTime="2015-12-30T23:59:00.000+00:00" PricingMethod="Unknown" Refundable="true" ETicketability="Yes" PlatingCarrier="AI" ProviderCode="1P">
                        <air:FareInfoRef Key="TuZDJVvgQEmq9ZUxTZ3WSg=="/>
                        <air:TaxInfo Category="LK" Amount="LKR3500"/>
                        <air:FareCalc>CMB AI X/MAA AI BOM101.22NUC101.22END ROE138.30</air:FareCalc>
                        <air:PassengerType Code="ADT" Age="40"/>
                        <air:ChangePenalty>
                            <air:Amount>LKR2200.0</air:Amount>
                        </air:ChangePenalty>
                        <air:CancelPenalty>
                            <air:Amount>LKR4000.0</air:Amount>
                        </air:CancelPenalty>
                        <air:FlightOptionsList>
                            <air:FlightOption LegRef="Yy/n9UuxR+uMskWhMvyx2A==" Destination="BOM" Origin="CMB">
                                <air:Option Key="MWRGMVQeSZyfZYdAqIPnUg==" TravelTime="P0DT13H40M0S">
                                    <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="TuZDJVvgQEmq9ZUxTZ3WSg==" SegmentRef="5prwP64xRJSHRE+YUOVOpA=="/>
                                    <air:BookingInfo BookingCode="V" CabinClass="Economy" FareInfoRef="TuZDJVvgQEmq9ZUxTZ3WSg==" SegmentRef="ek+tHMznQmqgkV2yB4coXg=="/>
                                    <air:Connection SegmentIndex="0"/>
                                </air:Option>
                                <air:Option Key="XaQdWqXtQBeyFUVr4KhdrQ==" TravelTime="P0DT23H55M0S">
                                    <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="TuZDJVvgQEmq9ZUxTZ3WSg==" SegmentRef="5prwP64xRJSHRE+YUOVOpA=="/>
                                    <air:BookingInfo BookingCode="V" CabinClass="Economy" FareInfoRef="TuZDJVvgQEmq9ZUxTZ3WSg==" SegmentRef="80Ukm7I3SVS1iSPkRzHoDQ=="/>
                                    <air:Connection SegmentIndex="0"/>
                                </air:Option>
                            </air:FlightOption>
                        </air:FlightOptionsList>
                    </air:AirPricingInfo>
                </air:AirPricePoint>
                <air:AirPricePoint Key="NgDGBIvtQDqPMOv1XhPXUA==" TotalPrice="LKR22900" BasePrice="LKR19400" ApproximateTotalPrice="LKR22900" ApproximateBasePrice="LKR19400" Taxes="LKR3500" ApproximateTaxes="LKR3500" CompleteItinerary="true">
                    <air:AirPricingInfo Key="Ju6rTwxBR22lPZFQtosphw==" TotalPrice="LKR22900" BasePrice="LKR19400" ApproximateTotalPrice="LKR22900" ApproximateBasePrice="LKR19400" Taxes="LKR3500" LatestTicketingTime="2015-12-30T23:59:00.000+00:00" PricingMethod="Unknown" Refundable="true" ETicketability="Yes" PlatingCarrier="9W" ProviderCode="1P">
                        <air:FareInfoRef Key="AhblwkXVRQC6WMFEKnK7wg=="/>
                        <air:TaxInfo Category="LK" Amount="LKR3500"/>
                        <air:FareCalc>CMB 9W BOM140.27NUC140.27END ROE138.30</air:FareCalc>
                        <air:PassengerType Code="ADT" Age="40"/>
                        <air:ChangePenalty>
                            <air:Amount>LKR2000.0</air:Amount>
                        </air:ChangePenalty>
                        <air:CancelPenalty>
                            <air:Amount>LKR3000.0</air:Amount>
                        </air:CancelPenalty>
                        <air:FlightOptionsList>
                            <air:FlightOption LegRef="Yy/n9UuxR+uMskWhMvyx2A==" Destination="BOM" Origin="CMB">
                                <air:Option Key="AemdhwMjQc6DjWj/lvamWw==" TravelTime="P0DT2H35M0S">
                                    <air:BookingInfo BookingCode="K" CabinClass="Economy" FareInfoRef="AhblwkXVRQC6WMFEKnK7wg==" SegmentRef="wUsM4tuhSx+u75QaV9enIQ=="/>
                                </air:Option>
                            </air:FlightOption>
                        </air:FlightOptionsList>
                    </air:AirPricingInfo>
                </air:AirPricePoint>
                <air:AirPricePoint Key="rQihiE6gSWCwvAc6OUpXYw==" TotalPrice="LKR26070" BasePrice="LKR21900" ApproximateTotalPrice="LKR26070" ApproximateBasePrice="LKR21900" Taxes="LKR4170" ApproximateTaxes="LKR4170" CompleteItinerary="true">
                    <air:AirPricingInfo Key="eqx/lBdXSH2WYe2mIrutTw==" TotalPrice="LKR26070" BasePrice="LKR21900" ApproximateTotalPrice="LKR26070" ApproximateBasePrice="LKR21900" Taxes="LKR4170" LatestTicketingTime="2015-12-30T23:59:00.000+00:00" PricingMethod="Unknown" Refundable="true" ETicketability="Yes" PlatingCarrier="UL" ProviderCode="1P">
                        <air:FareInfoRef Key="2PK7JIGmSomsO5bY5tDj3g=="/>
                        <air:TaxInfo Category="LK" Amount="LKR3500"/>
                        <air:TaxInfo Category="YQ" Amount="LKR670"/>
                        <air:FareCalc>CMB UL BOM158.35NUC158.35END ROE138.30</air:FareCalc>
                        <air:PassengerType Code="ADT" Age="40"/>
                        <air:ChangePenalty>
                            <air:Amount>LKR4500.0</air:Amount>
                        </air:ChangePenalty>
                        <air:FlightOptionsList>
                            <air:FlightOption LegRef="Yy/n9UuxR+uMskWhMvyx2A==" Destination="BOM" Origin="CMB">
                                <air:Option Key="/Z2+2ixxQQ2g3C7jsrj+RA==" TravelTime="P0DT2H25M0S">
                                    <air:BookingInfo BookingCode="R" CabinClass="Economy" FareInfoRef="2PK7JIGmSomsO5bY5tDj3g==" SegmentRef="0sPvLtdHT2if1Sz+um+ZPA=="/>
                                </air:Option>
                            </air:FlightOption>
                        </air:FlightOptionsList>
                    </air:AirPricingInfo>
                </air:AirPricePoint>
                <air:AirPricePoint Key="6tK6/sN5RN+mrKRDMtfSmA==" TotalPrice="LKR26070" BasePrice="LKR21900" ApproximateTotalPrice="LKR26070" ApproximateBasePrice="LKR21900" Taxes="LKR4170" ApproximateTaxes="LKR4170" CompleteItinerary="true">
                    <air:AirPricingInfo Key="bGtMBzsJQrWO+QNW2QHEBg==" TotalPrice="LKR26070" BasePrice="LKR21900" ApproximateTotalPrice="LKR26070" ApproximateBasePrice="LKR21900" Taxes="LKR4170" LatestTicketingTime="2015-12-30T23:59:00.000+00:00" PricingMethod="Unknown" Refundable="true" ETicketability="Yes" PlatingCarrier="MJ" ProviderCode="1P">
                        <air:FareInfoRef Key="4hE7McgvS66A0aLJpCm4mQ=="/>
                        <air:TaxInfo Category="LK" Amount="LKR3500"/>
                        <air:TaxInfo Category="YQ" Amount="LKR670"/>
                        <air:FareCalc>CMB MJ BOM158.35NUC158.35END ROE138.30</air:FareCalc>
                        <air:PassengerType Code="ADT" Age="40"/>
                        <air:ChangePenalty>
                            <air:Amount>LKR4500.0</air:Amount>
                        </air:ChangePenalty>
                        <air:FlightOptionsList>
                            <air:FlightOption LegRef="Yy/n9UuxR+uMskWhMvyx2A==" Destination="BOM" Origin="CMB">
                                <air:Option Key="lAA3FByfT+uEvGvl1ayOtA==" TravelTime="P0DT2H25M0S">
                                    <air:BookingInfo BookingCode="R" CabinClass="Economy" FareInfoRef="4hE7McgvS66A0aLJpCm4mQ==" SegmentRef="zfzYjZssS9O+FjcOPHlS9g=="/>
                                </air:Option>
                            </air:FlightOption>
                        </air:FlightOptionsList>
                    </air:AirPricingInfo>
                </air:AirPricePoint>
                <air:AirPricePoint Key="UJu67e8lS02t47m0M+8m4A==" TotalPrice="LKR31500" BasePrice="LKR28000" ApproximateTotalPrice="LKR31500" ApproximateBasePrice="LKR28000" Taxes="LKR3500" ApproximateTaxes="LKR3500" CompleteItinerary="true">
                    <air:AirPricingInfo Key="2UOKz/5oS+GFWBTHKsj5qw==" TotalPrice="LKR31500" BasePrice="LKR28000" ApproximateTotalPrice="LKR31500" ApproximateBasePrice="LKR28000" Taxes="LKR3500" LatestTicketingTime="2015-12-30T23:59:00.000+00:00" PricingMethod="Unknown" Refundable="true" ETicketability="Yes" PlatingCarrier="AI" ProviderCode="1P">
                        <air:FareInfoRef Key="mSkUl7KcR/a/crv1vuceYA=="/>
                        <air:TaxInfo Category="LK" Amount="LKR3500"/>
                        <air:FareCalc>CMB AI X/DEL AI BOM202.45NUC202.45END ROE138.30</air:FareCalc>
                        <air:PassengerType Code="ADT" Age="40"/>
                        <air:ChangePenalty>
                            <air:Amount>LKR2200.0</air:Amount>
                        </air:ChangePenalty>
                        <air:CancelPenalty>
                            <air:Amount>LKR4000.0</air:Amount>
                        </air:CancelPenalty>
                        <air:FlightOptionsList>
                            <air:FlightOption LegRef="Yy/n9UuxR+uMskWhMvyx2A==" Destination="BOM" Origin="CMB">
                                <air:Option Key="zATMVH64SQeiw22A/zXhlQ==" TravelTime="P0DT10H35M0S">
                                    <air:BookingInfo BookingCode="T" CabinClass="Economy" FareInfoRef="mSkUl7KcR/a/crv1vuceYA==" SegmentRef="BxHogvm4S9+73DL7Iju7ZA=="/>
                                    <air:BookingInfo BookingCode="V" CabinClass="Economy" FareInfoRef="mSkUl7KcR/a/crv1vuceYA==" SegmentRef="ql61iU/9TDSWChJyiJrLvg=="/>
                                    <air:Connection SegmentIndex="0"/>
                                </air:Option>
                                <air:Option Key="TlsxYeghT1qyhI9q4hJHew==" TravelTime="P0DT11H0M0S">
                                    <air:BookingInfo BookingCode="T" CabinClass="Economy" FareInfoRef="mSkUl7KcR/a/crv1vuceYA==" SegmentRef="BxHogvm4S9+73DL7Iju7ZA=="/>
                                    <air:BookingInfo BookingCode="V" CabinClass="Economy" FareInfoRef="mSkUl7KcR/a/crv1vuceYA==" SegmentRef="RHZ5VUh6S/mCCegpqpj5dw=="/>
                                    <air:Connection SegmentIndex="0"/>
                                </air:Option>
                            </air:FlightOption>
                        </air:FlightOptionsList>
                    </air:AirPricingInfo>
                </air:AirPricePoint>
                <air:AirPricePoint Key="+IuWNR//RLuZiTRfHbBSgA==" TotalPrice="LKR31500" BasePrice="LKR28000" ApproximateTotalPrice="LKR31500" ApproximateBasePrice="LKR28000" Taxes="LKR3500" ApproximateTaxes="LKR3500" CompleteItinerary="true">
                    <air:AirPricingInfo Key="ec1yuXMfRM2FCTweq2466g==" TotalPrice="LKR31500" BasePrice="LKR28000" ApproximateTotalPrice="LKR31500" ApproximateBasePrice="LKR28000" Taxes="LKR3500" LatestTicketingTime="2015-12-30T23:59:00.000+00:00" PricingMethod="Unknown" Refundable="true" ETicketability="Yes" PlatingCarrier="AI" ProviderCode="1P">
                        <air:FareInfoRef Key="mSkUl7KcR/a/crv1vuceYA=="/>
                        <air:TaxInfo Category="LK" Amount="LKR3500"/>
                        <air:FareCalc>CMB AI X/MAA AI X/DEL AI BOM202.45NUC202.45END ROE138.30</air:FareCalc>
                        <air:PassengerType Code="ADT" Age="40"/>
                        <air:ChangePenalty>
                            <air:Amount>LKR2200.0</air:Amount>
                        </air:ChangePenalty>
                        <air:CancelPenalty>
                            <air:Amount>LKR4000.0</air:Amount>
                        </air:CancelPenalty>
                        <air:FlightOptionsList>
                            <air:FlightOption LegRef="Yy/n9UuxR+uMskWhMvyx2A==" Destination="BOM" Origin="CMB">
                                <air:Option Key="X/HNB3qtTEOH0DUXsKgOSA==" TravelTime="P0DT17H45M0S">
                                    <air:BookingInfo BookingCode="T" CabinClass="Economy" FareInfoRef="mSkUl7KcR/a/crv1vuceYA==" SegmentRef="5prwP64xRJSHRE+YUOVOpA=="/>
                                    <air:BookingInfo BookingCode="V" CabinClass="Economy" FareInfoRef="mSkUl7KcR/a/crv1vuceYA==" SegmentRef="H6tKrdeESRet0ZkGQFaWSw=="/>
                                    <air:BookingInfo BookingCode="V" CabinClass="Economy" FareInfoRef="mSkUl7KcR/a/crv1vuceYA==" SegmentRef="w2nddKA4R/S9XZzKQNgDpA=="/>
                                    <air:Connection SegmentIndex="0"/>
                                    <air:Connection SegmentIndex="1"/>
                                </air:Option>
                                <air:Option Key="9DByLbNVR9uYQIUeMcmPDg==" TravelTime="P0DT20H45M0S">
                                    <air:BookingInfo BookingCode="T" CabinClass="Economy" FareInfoRef="mSkUl7KcR/a/crv1vuceYA==" SegmentRef="5prwP64xRJSHRE+YUOVOpA=="/>
                                    <air:BookingInfo BookingCode="V" CabinClass="Economy" FareInfoRef="mSkUl7KcR/a/crv1vuceYA==" SegmentRef="1NFlHND0QRS1Hc37ANWKjA=="/>
                                    <air:BookingInfo BookingCode="V" CabinClass="Economy" FareInfoRef="mSkUl7KcR/a/crv1vuceYA==" SegmentRef="LduEaBcySaeOMWkCUyZ7ew=="/>
                                    <air:Connection SegmentIndex="0"/>
                                    <air:Connection SegmentIndex="1"/>
                                </air:Option>
                            </air:FlightOption>
                        </air:FlightOptionsList>
                    </air:AirPricingInfo>
                </air:AirPricePoint>
                <air:AirPricePoint Key="Dx4yVBqWT0SyALLXY2L90w==" TotalPrice="LKR42303" BasePrice="LKR37700" ApproximateTotalPrice="LKR42303" ApproximateBasePrice="LKR37700" Taxes="LKR4603" ApproximateTaxes="LKR4603" CompleteItinerary="true">
                    <air:AirPricingInfo Key="QAK1vd+cSee5lzfG7plMDA==" TotalPrice="LKR42303" BasePrice="LKR37700" ApproximateTotalPrice="LKR42303" ApproximateBasePrice="LKR37700" Taxes="LKR4603" LatestTicketingTime="2015-12-30T23:59:00.000+00:00" PricingMethod="Unknown" Refundable="true" ETicketability="Yes" PlatingCarrier="AI" ProviderCode="1P">
                        <air:FareInfoRef Key="gO8UdrdOTOiiD5XKNFRWEQ=="/>
                        <air:FareInfoRef Key="69WLDAOFQc2SDmiQwQvZVQ=="/>
                        <air:FareInfoRef Key="t1dugF9jRj+nAxGAgUNE+g=="/>
                        <air:TaxInfo Category="LK" Amount="LKR3500"/>
                        <air:TaxInfo Category="IN" Amount="LKR774"/>
                        <air:TaxInfo Category="WO" Amount="LKR329"/>
                        <air:FareCalc>CMB AI DEL209.68AI BLR27.95AI BOM34.56NUC272.19END ROE138.30</air:FareCalc>
                        <air:PassengerType Code="ADT" Age="40"/>
                        <air:ChangePenalty>
                            <air:Amount>LKR2400.0</air:Amount>
                        </air:ChangePenalty>
                        <air:CancelPenalty>
                            <air:Amount>LKR2400.0</air:Amount>
                        </air:CancelPenalty>
                        <air:FlightOptionsList>
                            <air:FlightOption LegRef="Yy/n9UuxR+uMskWhMvyx2A==" Destination="BOM" Origin="CMB">
                                <air:Option Key="1pgV2siUSfaynTUn1zwFNQ==" TravelTime="P0DT11H5M0S">
                                    <air:BookingInfo BookingCode="W" CabinClass="Economy" FareInfoRef="gO8UdrdOTOiiD5XKNFRWEQ==" SegmentRef="BxHogvm4S9+73DL7Iju7ZA=="/>
                                    <air:BookingInfo BookingCode="U" CabinClass="Economy" FareInfoRef="69WLDAOFQc2SDmiQwQvZVQ==" SegmentRef="KrfAd76dRS2C1Qffc0gXxw=="/>
                                    <air:BookingInfo BookingCode="U" CabinClass="Economy" FareInfoRef="t1dugF9jRj+nAxGAgUNE+g==" SegmentRef="tuloGUIxQeOZRii5cjFCuQ=="/>
                                    <air:Connection SegmentIndex="0"/>
                                    <air:Connection SegmentIndex="1"/>
                                </air:Option>
                            </air:FlightOption>
                        </air:FlightOptionsList>
                    </air:AirPricingInfo>
                </air:AirPricePoint>
                <air:AirPricePoint Key="ttfWE8ahTGefY1pycXljKw==" TotalPrice="LKR42688" BasePrice="LKR38900" ApproximateTotalPrice="LKR42688" ApproximateBasePrice="LKR38900" Taxes="LKR3788" ApproximateTaxes="LKR3788" CompleteItinerary="true">
                    <air:AirPricingInfo Key="fSp+lwPcRuGpbQWcCQO1Og==" TotalPrice="LKR42688" BasePrice="LKR38900" ApproximateTotalPrice="LKR42688" ApproximateBasePrice="LKR38900" Taxes="LKR3788" LatestTicketingTime="2015-12-30T23:59:00.000+00:00" PricingMethod="Unknown" Refundable="true" ETicketability="Yes" PlatingCarrier="TG" ProviderCode="1P">
                        <air:FareInfoRef Key="JVIVTw1MQ5qvb1Izp53YBg=="/>
                        <air:TaxInfo Category="LK" Amount="LKR3500"/>
                        <air:TaxInfo Category="E7" Amount="LKR288"/>
                        <air:FareCalc>CMB TG X/BKK TG BOM Q135.85 145.33NUC281.18END ROE138.30</air:FareCalc>
                        <air:PassengerType Code="ADT" Age="40"/>
                        <air:FlightOptionsList>
                            <air:FlightOption LegRef="Yy/n9UuxR+uMskWhMvyx2A==" Destination="BOM" Origin="CMB">
                                <air:Option Key="SgbMFwi3SjCDX6XrROSZmA==" TravelTime="P0DT20H30M0S">
                                    <air:BookingInfo BookingCode="V" CabinClass="Economy" FareInfoRef="JVIVTw1MQ5qvb1Izp53YBg==" SegmentRef="9nUkPsjMQBSlVG7DYPyFDA=="/>
                                    <air:BookingInfo BookingCode="V" CabinClass="Economy" FareInfoRef="JVIVTw1MQ5qvb1Izp53YBg==" SegmentRef="yjemUQ11SF+KYeFzbnf/gg=="/>
                                    <air:Connection SegmentIndex="0"/>
                                </air:Option>
                            </air:FlightOption>
                        </air:FlightOptionsList>
                    </air:AirPricingInfo>
                </air:AirPricePoint>
                <air:AirPricePoint Key="h1EzfvUdSvCEqRbhMaA8pA==" TotalPrice="LKR43918" BasePrice="LKR40300" ApproximateTotalPrice="LKR43918" ApproximateBasePrice="LKR40300" Taxes="LKR3618" ApproximateTaxes="LKR3618" CompleteItinerary="true">
                    <air:AirPricingInfo Key="jwv0R9xnQgqi0dbyyUbmvA==" TotalPrice="LKR43918" BasePrice="LKR40300" ApproximateTotalPrice="LKR43918" ApproximateBasePrice="LKR40300" Taxes="LKR3618" LatestTicketingTime="2015-12-30T23:59:00.000+00:00" PricingMethod="Unknown" Refundable="true" ETicketability="Yes" PlatingCarrier="KU" ProviderCode="1P">
                        <air:FareInfoRef Key="0iTWbgcxT5KqJxFFZjC3XA=="/>
                        <air:FareInfoRef Key="1L/PknWUSbq3OLixZaXMSQ=="/>
                        <air:TaxInfo Category="LK" Amount="LKR3500"/>
                        <air:TaxInfo Category="YX" Amount="LKR118"/>
                        <air:FareCalc>CMB KU KWI148.59KU BOM142.24NUC290.83END ROE138.30</air:FareCalc>
                        <air:PassengerType Code="ADT" Age="40"/>
                        <air:FlightOptionsList>
                            <air:FlightOption LegRef="Yy/n9UuxR+uMskWhMvyx2A==" Destination="BOM" Origin="CMB">
                                <air:Option Key="191PwJe0SMGrUPgvuS8cxw==" TravelTime="P0DT22H45M0S">
                                    <air:BookingInfo BookingCode="T" CabinClass="Economy" FareInfoRef="0iTWbgcxT5KqJxFFZjC3XA==" SegmentRef="CO+0DRsyTviGJgTR2lecVw=="/>
                                    <air:BookingInfo BookingCode="L" CabinClass="Economy" FareInfoRef="1L/PknWUSbq3OLixZaXMSQ==" SegmentRef="cqXkk4GiRuqLAZOFpgAFvQ=="/>
                                    <air:Connection SegmentIndex="0"/>
                                </air:Option>
                            </air:FlightOption>
                        </air:FlightOptionsList>
                    </air:AirPricingInfo>
                </air:AirPricePoint>
                <air:AirPricePoint Key="gz73uLQJT8Kc1rk9Q79iUA==" TotalPrice="LKR45003" BasePrice="LKR40400" ApproximateTotalPrice="LKR45003" ApproximateBasePrice="LKR40400" Taxes="LKR4603" ApproximateTaxes="LKR4603" CompleteItinerary="true">
                    <air:AirPricingInfo Key="DYXd11iARs2ldz5jhEmLUg==" TotalPrice="LKR45003" BasePrice="LKR40400" ApproximateTotalPrice="LKR45003" ApproximateBasePrice="LKR40400" Taxes="LKR4603" LatestTicketingTime="2015-12-30T23:59:00.000+00:00" PricingMethod="Unknown" Refundable="true" ETicketability="Yes" PlatingCarrier="AI" ProviderCode="1P">
                        <air:FareInfoRef Key="N8f9bATgSJegYdLTuxxbEQ=="/>
                        <air:FareInfoRef Key="t1dugF9jRj+nAxGAgUNE+g=="/>
                        <air:TaxInfo Category="LK" Amount="LKR3500"/>
                        <air:TaxInfo Category="IN" Amount="LKR774"/>
                        <air:TaxInfo Category="WO" Amount="LKR329"/>
                        <air:FareCalc>CMB AI X/MAA AI BLR257.41AI BOM34.56NUC291.97END ROE138.30</air:FareCalc>
                        <air:PassengerType Code="ADT" Age="40"/>
                        <air:ChangePenalty>
                            <air:Amount>LKR2200.0</air:Amount>
                        </air:ChangePenalty>
                        <air:CancelPenalty>
                            <air:Amount>LKR3000.0</air:Amount>
                        </air:CancelPenalty>
                        <air:FlightOptionsList>
                            <air:FlightOption LegRef="Yy/n9UuxR+uMskWhMvyx2A==" Destination="BOM" Origin="CMB">
                                <air:Option Key="0PsA/un6TTO8gGOhatiplQ==" TravelTime="P1DT0H55M0S">
                                    <air:BookingInfo BookingCode="W" CabinClass="Economy" FareInfoRef="N8f9bATgSJegYdLTuxxbEQ==" SegmentRef="5prwP64xRJSHRE+YUOVOpA=="/>
                                    <air:BookingInfo BookingCode="V" CabinClass="Economy" FareInfoRef="N8f9bATgSJegYdLTuxxbEQ==" SegmentRef="h8sva1J+TE6pBqF8lJzZqQ=="/>
                                    <air:BookingInfo BookingCode="U" CabinClass="Economy" FareInfoRef="t1dugF9jRj+nAxGAgUNE+g==" SegmentRef="K6ewDLHXS7ialSD9/l+mSg=="/>
                                    <air:Connection SegmentIndex="0"/>
                                    <air:Connection SegmentIndex="1"/>
                                </air:Option>
                            </air:FlightOption>
                        </air:FlightOptionsList>
                    </air:AirPricingInfo>
                </air:AirPricePoint>
                <air:AirPricePoint Key="6UAtYQmqRNqsIweVLUOZzw==" TotalPrice="LKR61170" BasePrice="LKR57000" ApproximateTotalPrice="LKR61170" ApproximateBasePrice="LKR57000" Taxes="LKR4170" ApproximateTaxes="LKR4170" CompleteItinerary="true">
                    <air:AirPricingInfo Key="HAIXyEDvRsyYbXQA5RXhEw==" TotalPrice="LKR61170" BasePrice="LKR57000" ApproximateTotalPrice="LKR61170" ApproximateBasePrice="LKR57000" Taxes="LKR4170" LatestTicketingTime="2015-12-30T23:59:00.000+00:00" PricingMethod="Unknown" Refundable="true" ETicketability="Yes" PlatingCarrier="UL" ProviderCode="1P">
                        <air:FareInfoRef Key="GDWwSZcqRIy4w4KZpuWqPA=="/>
                        <air:FareInfoRef Key="aTdXioKVRAOB8ZfPQPqBQw=="/>
                        <air:TaxInfo Category="LK" Amount="LKR3500"/>
                        <air:TaxInfo Category="YQ" Amount="LKR670"/>
                        <air:FareCalc>CMB UL MAA70.86 9W BOM Q20.00 321.07NUC411.93END ROE138.30</air:FareCalc>
                        <air:PassengerType Code="ADT" Age="40"/>
                        <air:ChangePenalty>
                            <air:Amount>LKR4500.0</air:Amount>
                        </air:ChangePenalty>
                        <air:FlightOptionsList>
                            <air:FlightOption LegRef="Yy/n9UuxR+uMskWhMvyx2A==" Destination="BOM" Origin="CMB">
                                <air:Option Key="c7GiD9yzQjudqzHXt4D+dA==" TravelTime="P0DT5H55M0S">
                                    <air:BookingInfo BookingCode="Q" CabinClass="Economy" FareInfoRef="GDWwSZcqRIy4w4KZpuWqPA==" SegmentRef="f90MbwBQSqWb59Ilu9sVlA=="/>
                                    <air:BookingInfo BookingCode="Y" CabinClass="Economy" FareInfoRef="aTdXioKVRAOB8ZfPQPqBQw==" SegmentRef="+nnddfIcQbS2G7jSW1lxLA=="/>
                                    <air:Connection SegmentIndex="0"/>
                                </air:Option>
                            </air:FlightOption>
                        </air:FlightOptionsList>
                    </air:AirPricingInfo>
                </air:AirPricePoint>
                <air:AirPricePoint Key="/8mL/DEPSNSSgueSWIub0w==" TotalPrice="LKR61170" BasePrice="LKR57000" ApproximateTotalPrice="LKR61170" ApproximateBasePrice="LKR57000" Taxes="LKR4170" ApproximateTaxes="LKR4170" CompleteItinerary="true">
                    <air:AirPricingInfo Key="Z9VZs8JBSfGwoGQ1gj8kUg==" TotalPrice="LKR61170" BasePrice="LKR57000" ApproximateTotalPrice="LKR61170" ApproximateBasePrice="LKR57000" Taxes="LKR4170" LatestTicketingTime="2015-12-30T23:59:00.000+00:00" PricingMethod="Unknown" Refundable="true" ETicketability="Yes" PlatingCarrier="UL" ProviderCode="1P">
                        <air:FareInfoRef Key="GDWwSZcqRIy4w4KZpuWqPA=="/>
                        <air:FareInfoRef Key="aTdXioKVRAOB8ZfPQPqBQw=="/>
                        <air:TaxInfo Category="LK" Amount="LKR3500"/>
                        <air:TaxInfo Category="YQ" Amount="LKR670"/>
                        <air:FareCalc>CMB UL MAA70.86 9W BOM Q20.00 321.07NUC411.93END ROE138.30</air:FareCalc>
                        <air:PassengerType Code="ADT" Age="40"/>
                        <air:ChangePenalty>
                            <air:Amount>LKR4500.0</air:Amount>
                        </air:ChangePenalty>
                        <air:FlightOptionsList>
                            <air:FlightOption LegRef="Yy/n9UuxR+uMskWhMvyx2A==" Destination="BOM" Origin="CMB">
                                <air:Option Key="muJg+t5HSlO4/COsct/vBg==" TravelTime="P0DT9H55M0S">
                                    <air:BookingInfo BookingCode="Q" CabinClass="Economy" FareInfoRef="GDWwSZcqRIy4w4KZpuWqPA==" SegmentRef="66jMUncSQHysn0acgiu4zw=="/>
                                    <air:BookingInfo BookingCode="Y" CabinClass="Economy" FareInfoRef="aTdXioKVRAOB8ZfPQPqBQw==" SegmentRef="hwqO5C39TkuzI8J81zb9uw=="/>
                                    <air:Connection SegmentIndex="0"/>
                                </air:Option>
                            </air:FlightOption>
                        </air:FlightOptionsList>
                    </air:AirPricingInfo>
                </air:AirPricePoint>
                <air:AirPricePoint Key="V2Ge/rFvQT2vYW0MtKh5Eg==" TotalPrice="LKR63873" BasePrice="LKR58600" ApproximateTotalPrice="LKR63873" ApproximateBasePrice="LKR58600" Taxes="LKR5273" ApproximateTaxes="LKR5273" CompleteItinerary="true">
                    <air:AirPricingInfo Key="oosSn1QhRfGEsb1Cndvjqw==" TotalPrice="LKR63873" BasePrice="LKR58600" ApproximateTotalPrice="LKR63873" ApproximateBasePrice="LKR58600" Taxes="LKR5273" LatestTicketingTime="2015-12-30T23:59:00.000+00:00" PricingMethod="Unknown" Refundable="true" ETicketability="Yes" PlatingCarrier="UL" ProviderCode="1P">
                        <air:FareInfoRef Key="ylmLppvLQxS1gVu6YrSmXQ=="/>
                        <air:FareInfoRef Key="n3Sh9LBgSkqTQ8/T3DjhCg=="/>
                        <air:TaxInfo Category="LK" Amount="LKR3500"/>
                        <air:TaxInfo Category="IN" Amount="LKR774"/>
                        <air:TaxInfo Category="WO" Amount="LKR329"/>
                        <air:TaxInfo Category="YQ" Amount="LKR670"/>
                        <air:FareCalc>CMB UL BLR108.45 9W BOM Q20.00 294.55NUC423.00END ROE138.30</air:FareCalc>
                        <air:PassengerType Code="ADT" Age="40"/>
                        <air:ChangePenalty>
                            <air:Amount>LKR2300.0</air:Amount>
                        </air:ChangePenalty>
                        <air:CancelPenalty>
                            <air:Amount>LKR3400.0</air:Amount>
                        </air:CancelPenalty>
                        <air:FlightOptionsList>
                            <air:FlightOption LegRef="Yy/n9UuxR+uMskWhMvyx2A==" Destination="BOM" Origin="CMB">
                                <air:Option Key="EII/9g9qTrCxkOmr2EA58A==" TravelTime="P0DT6H10M0S">
                                    <air:BookingInfo BookingCode="R" CabinClass="Economy" FareInfoRef="ylmLppvLQxS1gVu6YrSmXQ==" SegmentRef="1OxefzSvRSua5Aa0FhLWJQ=="/>
                                    <air:BookingInfo BookingCode="Y" CabinClass="Economy" FareInfoRef="n3Sh9LBgSkqTQ8/T3DjhCg==" SegmentRef="t4OE0jfGQ3OXpZ/2fwT5zA=="/>
                                    <air:Connection SegmentIndex="0"/>
                                </air:Option>
                            </air:FlightOption>
                        </air:FlightOptionsList>
                    </air:AirPricingInfo>
                </air:AirPricePoint>
                <air:AirPricePoint Key="0C0S6vF7TS6NF07Iw2p6bw==" TotalPrice="LKR69274" BasePrice="LKR65700" ApproximateTotalPrice="LKR69274" ApproximateBasePrice="LKR65700" Taxes="LKR3574" ApproximateTaxes="LKR3574" CompleteItinerary="true">
                    <air:AirPricingInfo Key="cUNptiDnTe6YqIvAheyUuw==" TotalPrice="LKR69274" BasePrice="LKR65700" ApproximateTotalPrice="LKR69274" ApproximateBasePrice="LKR65700" Taxes="LKR3574" LatestTicketingTime="2015-12-30T23:59:00.000+00:00" PricingMethod="Unknown" Refundable="true" ETicketability="Yes" PlatingCarrier="QR" ProviderCode="1P">
                        <air:FareInfoRef Key="1zPnV1WpTOOSCyJd0E4T2A=="/>
                        <air:TaxInfo Category="LK" Amount="LKR3500"/>
                        <air:TaxInfo Category="PZ" Amount="LKR74"/>
                        <air:FareCalc>CMB QR X/DOH QR BOM475.05NUC475.05END ROE138.30</air:FareCalc>
                        <air:PassengerType Code="ADT" Age="40"/>
                        <air:ChangePenalty>
                            <air:Amount>LKR4400.0</air:Amount>
                        </air:ChangePenalty>
                        <air:CancelPenalty>
                            <air:Amount>LKR5200.0</air:Amount>
                        </air:CancelPenalty>
                        <air:FlightOptionsList>
                            <air:FlightOption LegRef="Yy/n9UuxR+uMskWhMvyx2A==" Destination="BOM" Origin="CMB">
                                <air:Option Key="cHnL+xTIRcSukNm7TpKq7A==" TravelTime="P0DT16H35M0S">
                                    <air:BookingInfo BookingCode="N" CabinClass="Economy" FareInfoRef="1zPnV1WpTOOSCyJd0E4T2A==" SegmentRef="PLHLO28JSTiIkZ70ezTAjA=="/>
                                    <air:BookingInfo BookingCode="N" CabinClass="Economy" FareInfoRef="1zPnV1WpTOOSCyJd0E4T2A==" SegmentRef="nYcBG+HSRtWNpTFCt/2heg=="/>
                                    <air:Connection SegmentIndex="0"/>
                                </air:Option>
                            </air:FlightOption>
                        </air:FlightOptionsList>
                    </air:AirPricingInfo>
                </air:AirPricePoint>
                <air:AirPricePoint Key="rctIrY7lQAiak2F9FeJ9hA==" TotalPrice="LKR80170" BasePrice="LKR76000" ApproximateTotalPrice="LKR80170" ApproximateBasePrice="LKR76000" Taxes="LKR4170" ApproximateTaxes="LKR4170" CompleteItinerary="true">
                    <air:AirPricingInfo Key="WvluLqfGRTyImCssiINq7A==" TotalPrice="LKR80170" BasePrice="LKR76000" ApproximateTotalPrice="LKR80170" ApproximateBasePrice="LKR76000" Taxes="LKR4170" LatestTicketingTime="2015-12-30T23:59:00.000+00:00" PricingMethod="Unknown" Refundable="true" ETicketability="Yes" PlatingCarrier="UL" ProviderCode="1P">
                        <air:FareInfoRef Key="w3Yn4NV2Rd+v4IT9UmpT3Q=="/>
                        <air:TaxInfo Category="LK" Amount="LKR3500"/>
                        <air:TaxInfo Category="YQ" Amount="LKR670"/>
                        <air:FareCalc>CMB UL X/MAA AI BOM M549.53NUC549.53END ROE138.30</air:FareCalc>
                        <air:PassengerType Code="ADT" Age="40"/>
                        <air:FlightOptionsList>
                            <air:FlightOption LegRef="Yy/n9UuxR+uMskWhMvyx2A==" Destination="BOM" Origin="CMB">
                                <air:Option Key="ghShy/oFST2m/FJ0diBzXg==" TravelTime="P0DT7H30M0S">
                                    <air:BookingInfo BookingCode="Q" CabinClass="Economy" FareInfoRef="w3Yn4NV2Rd+v4IT9UmpT3Q==" SegmentRef="xijIeFvuSr6K8DHHX8qk6w=="/>
                                    <air:BookingInfo BookingCode="Y" CabinClass="Economy" FareInfoRef="w3Yn4NV2Rd+v4IT9UmpT3Q==" SegmentRef="weDvdh5hS2CMyjkof6NMuQ=="/>
                                    <air:Connection SegmentIndex="0"/>
                                </air:Option>
                            </air:FlightOption>
                        </air:FlightOptionsList>
                    </air:AirPricingInfo>
                </air:AirPricePoint>
                <air:AirPricePoint Key="5OBj0w27SAuiaxKLyNI9Gg==" TotalPrice="LKR81010" BasePrice="LKR76000" ApproximateTotalPrice="LKR81010" ApproximateBasePrice="LKR76000" Taxes="LKR5010" ApproximateTaxes="LKR5010" CompleteItinerary="true">
                    <air:AirPricingInfo Key="tMOK/2qQQISMTi57ke2mdg==" TotalPrice="LKR81010" BasePrice="LKR76000" ApproximateTotalPrice="LKR81010" ApproximateBasePrice="LKR76000" Taxes="LKR5010" LatestTicketingTime="2015-12-30T23:59:00.000+00:00" PricingMethod="Unknown" Refundable="true" ETicketability="Yes" PlatingCarrier="UL" ProviderCode="1P">
                        <air:FareInfoRef Key="w3Yn4NV2Rd+v4IT9UmpT3Q=="/>
                        <air:TaxInfo Category="LK" Amount="LKR3500"/>
                        <air:TaxInfo Category="WO" Amount="LKR840"/>
                        <air:TaxInfo Category="YQ" Amount="LKR670"/>
                        <air:FareCalc>CMB UL X/COK AI BOM M549.53NUC549.53END ROE138.30</air:FareCalc>
                        <air:PassengerType Code="ADT" Age="40"/>
                        <air:FlightOptionsList>
                            <air:FlightOption LegRef="Yy/n9UuxR+uMskWhMvyx2A==" Destination="BOM" Origin="CMB">
                                <air:Option Key="p4hIAIIXSSKxv3REcANlrw==" TravelTime="P0DT9H40M0S">
                                    <air:BookingInfo BookingCode="Q" CabinClass="Economy" FareInfoRef="w3Yn4NV2Rd+v4IT9UmpT3Q==" SegmentRef="wXh2eq+3TYSnj08zmsbg9A=="/>
                                    <air:BookingInfo BookingCode="Y" CabinClass="Economy" FareInfoRef="w3Yn4NV2Rd+v4IT9UmpT3Q==" SegmentRef="FZ3EP+wCSAO/dTlGXxuirg=="/>
                                    <air:Connection SegmentIndex="0"/>
                                </air:Option>
                            </air:FlightOption>
                        </air:FlightOptionsList>
                    </air:AirPricingInfo>
                </air:AirPricePoint>
                <air:AirPricePoint Key="8nce/OqQSxy5BJPC2727Uw==" TotalPrice="LKR81273" BasePrice="LKR76000" ApproximateTotalPrice="LKR81273" ApproximateBasePrice="LKR76000" Taxes="LKR5273" ApproximateTaxes="LKR5273" CompleteItinerary="true">
                    <air:AirPricingInfo Key="6k1Tnr3zTiKFz4uqgZzcuw==" TotalPrice="LKR81273" BasePrice="LKR76000" ApproximateTotalPrice="LKR81273" ApproximateBasePrice="LKR76000" Taxes="LKR5273" LatestTicketingTime="2015-12-30T23:59:00.000+00:00" PricingMethod="Unknown" Refundable="true" ETicketability="Yes" PlatingCarrier="UL" ProviderCode="1P">
                        <air:FareInfoRef Key="w3Yn4NV2Rd+v4IT9UmpT3Q=="/>
                        <air:TaxInfo Category="LK" Amount="LKR3500"/>
                        <air:TaxInfo Category="IN" Amount="LKR774"/>
                        <air:TaxInfo Category="WO" Amount="LKR329"/>
                        <air:TaxInfo Category="YQ" Amount="LKR670"/>
                        <air:FareCalc>CMB UL X/BLR AI BOM M549.53NUC549.53END ROE138.30</air:FareCalc>
                        <air:PassengerType Code="ADT" Age="40"/>
                        <air:FlightOptionsList>
                            <air:FlightOption LegRef="Yy/n9UuxR+uMskWhMvyx2A==" Destination="BOM" Origin="CMB">
                                <air:Option Key="bjbTsEmyRLu1MAOsocREEQ==" TravelTime="P0DT7H5M0S">
                                    <air:BookingInfo BookingCode="Q" CabinClass="Economy" FareInfoRef="w3Yn4NV2Rd+v4IT9UmpT3Q==" SegmentRef="1OxefzSvRSua5Aa0FhLWJQ=="/>
                                    <air:BookingInfo BookingCode="Y" CabinClass="Economy" FareInfoRef="w3Yn4NV2Rd+v4IT9UmpT3Q==" SegmentRef="76aWqUpsTRyj2+iR6l/uHw=="/>
                                    <air:Connection SegmentIndex="0"/>
                                </air:Option>
                            </air:FlightOption>
                        </air:FlightOptionsList>
                    </air:AirPricingInfo>
                </air:AirPricePoint>
                <air:AirPricePoint Key="U981X9cRQaqNNJjRMxKylQ==" TotalPrice="LKR89252" BasePrice="LKR84700" ApproximateTotalPrice="LKR89252" ApproximateBasePrice="LKR84700" Taxes="LKR4552" ApproximateTaxes="LKR4552" CompleteItinerary="true">
                    <air:AirPricingInfo Key="lGilg1ZvQN2DyCeKDuoeOA==" TotalPrice="LKR89252" BasePrice="LKR84700" ApproximateTotalPrice="LKR89252" ApproximateBasePrice="LKR84700" Taxes="LKR4552" LatestTicketingTime="2015-12-30T23:59:00.000+00:00" PricingMethod="Unknown" Refundable="true" ETicketability="Yes" PlatingCarrier="UL" ProviderCode="1P">
                        <air:FareInfoRef Key="RwAUVJ8nRqqXhP3m4rAxGw=="/>
                        <air:FareInfoRef Key="0fI0BJ/1Rwe9lMP/VUIgQA=="/>
                        <air:TaxInfo Category="LK" Amount="LKR3500"/>
                        <air:TaxInfo Category="OM" Amount="LKR382"/>
                        <air:TaxInfo Category="YQ" Amount="LKR670"/>
                        <air:FareCalc>CMB UL MCT Q3.00 99.06AI BOM509.75NUC611.81END ROE138.30</air:FareCalc>
                        <air:PassengerType Code="ADT" Age="40"/>
                        <air:ChangePenalty>
                            <air:Amount>LKR3500.0</air:Amount>
                        </air:ChangePenalty>
                        <air:FlightOptionsList>
                            <air:FlightOption LegRef="Yy/n9UuxR+uMskWhMvyx2A==" Destination="BOM" Origin="CMB">
                                <air:Option Key="7ucMzDcOTL2NhOj/nfa7Gw==" TravelTime="P0DT9H40M0S">
                                    <air:BookingInfo BookingCode="Q" CabinClass="Economy" FareInfoRef="RwAUVJ8nRqqXhP3m4rAxGw==" SegmentRef="dNdj5yZ/QJeItY9bVAzorg=="/>
                                    <air:BookingInfo BookingCode="Y" CabinClass="Economy" FareInfoRef="0fI0BJ/1Rwe9lMP/VUIgQA==" SegmentRef="QrsjEjXJQUOZMuFsVAAo6Q=="/>
                                    <air:Connection SegmentIndex="0"/>
                                </air:Option>
                            </air:FlightOption>
                        </air:FlightOptionsList>
                    </air:AirPricingInfo>
                </air:AirPricePoint>
                <air:AirPricePoint Key="MplWjdJGQW61APzndMZe/g==" TotalPrice="LKR187360" BasePrice="LKR183700" ApproximateTotalPrice="LKR187360" ApproximateBasePrice="LKR183700" Taxes="LKR3660" ApproximateTaxes="LKR3660" CompleteItinerary="true">
                    <air:AirPricingInfo Key="DaQ68DpeQk6f4wbsLXQ5+Q==" TotalPrice="LKR187360" BasePrice="LKR183700" ApproximateTotalPrice="LKR187360" ApproximateBasePrice="LKR183700" Taxes="LKR3660" LatestTicketingTime="2015-12-30T23:59:00.000+00:00" PricingMethod="Unknown" Refundable="true" ETicketability="Yes" PlatingCarrier="EY" ProviderCode="1P">
                        <air:FareInfoRef Key="TGq7WcqxQ5OBvvHEm2JK8w=="/>
                        <air:FareInfoRef Key="GPvzaK9tTzqjKluo7iZb9w=="/>
                        <air:TaxInfo Category="LK" Amount="LKR3500"/>
                        <air:TaxInfo Category="YR" Amount="LKR160"/>
                        <air:FareCalc>CMB EY AUH Q52.00 344.54EY BOM931.12NUC1327.66END ROE138.30</air:FareCalc>
                        <air:PassengerType Code="ADT" Age="40"/>
                        <air:FlightOptionsList>
                            <air:FlightOption LegRef="Yy/n9UuxR+uMskWhMvyx2A==" Destination="BOM" Origin="CMB">
                                <air:Option Key="EoD7dY58QhK0wn25zbtdNQ==" TravelTime="P0DT11H15M0S">
                                    <air:BookingInfo BookingCode="Y" CabinClass="Economy" FareInfoRef="TGq7WcqxQ5OBvvHEm2JK8w==" SegmentRef="UNIWeyDWRTq9AZXzz/cZGg=="/>
                                    <air:BookingInfo BookingCode="Y" CabinClass="Economy" FareInfoRef="GPvzaK9tTzqjKluo7iZb9w==" SegmentRef="oVEcNwOeQ+qwN71iIo5Cgg=="/>
                                    <air:Connection SegmentIndex="0"/>
                                </air:Option>
                            </air:FlightOption>
                        </air:FlightOptionsList>
                    </air:AirPricingInfo>
                </air:AirPricePoint>
                <air:AirPricePoint Key="vuNdUdNEQOSop6KfQtwVgw==" TotalPrice="LKR229800" BasePrice="LKR226300" ApproximateTotalPrice="LKR229800" ApproximateBasePrice="LKR226300" Taxes="LKR3500" ApproximateTaxes="LKR3500" CompleteItinerary="true">
                    <air:AirPricingInfo Key="xJ2fKJ1/SAq/rW8/mfGHfw==" TotalPrice="LKR229800" BasePrice="LKR226300" ApproximateTotalPrice="LKR229800" ApproximateBasePrice="LKR226300" Taxes="LKR3500" LatestTicketingTime="2015-12-30T23:59:00.000+00:00" PricingMethod="Unknown" Refundable="true" ETicketability="Yes" PlatingCarrier="HM" ProviderCode="1P">
                        <air:FareInfoRef Key="ewtt09q1QUmecbwuxmRy4w=="/>
                        <air:FareInfoRef Key="9QFGnYmORCKxeN2iDiIXXA=="/>
                        <air:TaxInfo Category="LK" Amount="LKR3500"/>
                        <air:FareCalc>CMB HM AUH759.21HM BOM876.67NUC1635.88END ROE138.30</air:FareCalc>
                        <air:PassengerType Code="ADT" Age="40"/>
                        <air:FlightOptionsList>
                            <air:FlightOption LegRef="Yy/n9UuxR+uMskWhMvyx2A==" Destination="BOM" Origin="CMB">
                                <air:Option Key="zLadru14SJ2td+4iaU/W7w==" TravelTime="P0DT11H15M0S">
                                    <air:BookingInfo BookingCode="M" CabinClass="Economy" FareInfoRef="ewtt09q1QUmecbwuxmRy4w==" SegmentRef="D4lv+RGmRpGDZiTXOO2vnA=="/>
                                    <air:BookingInfo BookingCode="M" CabinClass="Economy" FareInfoRef="9QFGnYmORCKxeN2iDiIXXA==" SegmentRef="4G665u4dT7ezTYmxfQ4UbQ=="/>
                                    <air:Connection SegmentIndex="0"/>
                                </air:Option>
                            </air:FlightOption>
                        </air:FlightOptionsList>
                    </air:AirPricingInfo>
                </air:AirPricePoint>
                <air:AirPricePoint Key="1stIdtsbRFm2ng535dh+Sw==" TotalPrice="LKR347784" BasePrice="LKR312000" ApproximateTotalPrice="LKR347784" ApproximateBasePrice="LKR312000" Taxes="LKR35784" ApproximateTaxes="LKR35784" CompleteItinerary="true">
                    <air:AirPricingInfo Key="AZFoecwqSDCcqYxBGfH1/Q==" TotalPrice="LKR347784" BasePrice="LKR312000" ApproximateTotalPrice="LKR347784" ApproximateBasePrice="LKR312000" Taxes="LKR35784" LatestTicketingTime="2015-12-30T23:59:00.000+00:00" PricingMethod="Unknown" Refundable="true" ETicketability="Yes" PlatingCarrier="SQ" ProviderCode="1P">
                        <air:FareInfoRef Key="hGXrS/5oSeOKDKiyWDSOmg=="/>
                        <air:FareInfoRef Key="NwA4s1AlTyqIdeQd9cGzJw=="/>
                        <air:TaxInfo Category="LK" Amount="LKR3500"/>
                        <air:TaxInfo Category="OO" Amount="LKR315"/>
                        <air:TaxInfo Category="SG" Amount="LKR315"/>
                        <air:TaxInfo Category="YQ" Amount="LKR31654"/>
                        <air:FareCalc>CMB SQ SIN447.21SQ BOM1808.36NUC2255.57END ROE138.30</air:FareCalc>
                        <air:PassengerType Code="ADT" Age="40"/>
                        <air:FlightOptionsList>
                            <air:FlightOption LegRef="Yy/n9UuxR+uMskWhMvyx2A==" Destination="BOM" Origin="CMB">
                                <air:Option Key="T3NwRW8iSD2gRoCyOZM7fQ==" TravelTime="P0DT20H35M0S">
                                    <air:BookingInfo BookingCode="Y" CabinClass="Economy" FareInfoRef="hGXrS/5oSeOKDKiyWDSOmg==" SegmentRef="35NDQM4vSBimE3dhLngNpA=="/>
                                    <air:BookingInfo BookingCode="B" CabinClass="Economy" FareInfoRef="NwA4s1AlTyqIdeQd9cGzJw==" SegmentRef="DO38sICKQwCJpqLfnEXDRw=="/>
                                    <air:Connection SegmentIndex="0"/>
                                </air:Option>
                            </air:FlightOption>
                        </air:FlightOptionsList>
                    </air:AirPricingInfo>
                </air:AirPricePoint>
                <air:AirPricePoint Key="GmesljgsRgmUuI8eEDHS7w==" TotalPrice="LKR363392" BasePrice="LKR321600" ApproximateTotalPrice="LKR363392" ApproximateBasePrice="LKR321600" Taxes="LKR41792" ApproximateTaxes="LKR41792" CompleteItinerary="true">
                    <air:AirPricingInfo Key="MllQWewxTaqqDzVcCXgV2A==" TotalPrice="LKR363392" BasePrice="LKR321600" ApproximateTotalPrice="LKR363392" ApproximateBasePrice="LKR321600" Taxes="LKR41792" LatestTicketingTime="2015-12-30T23:59:00.000+00:00" PricingMethod="Unknown" Refundable="true" ETicketability="Yes" PlatingCarrier="QF" ProviderCode="1P">
                        <air:FareInfoRef Key="I+jbjgnpRoWcOtHxAPcaPg=="/>
                        <air:FareInfoRef Key="K39nDbT+TgiCfwtVEPU2xA=="/>
                        <air:TaxInfo Category="LK" Amount="LKR3500"/>
                        <air:TaxInfo Category="OO" Amount="LKR315"/>
                        <air:TaxInfo Category="SG" Amount="LKR315"/>
                        <air:TaxInfo Category="YQ" Amount="LKR37662"/>
                        <air:FareCalc>CMB QF SIN516.99QF BOM1808.35NUC2325.34END ROE138.30</air:FareCalc>
                        <air:PassengerType Code="ADT" Age="40"/>
                        <air:FlightOptionsList>
                            <air:FlightOption LegRef="Yy/n9UuxR+uMskWhMvyx2A==" Destination="BOM" Origin="CMB">
                                <air:Option Key="jvrtXBuaRySlcbB+TqMWzw==" TravelTime="P0DT12H30M0S">
                                    <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="I+jbjgnpRoWcOtHxAPcaPg==" SegmentRef="zGx0vkDWQG6UpfSZuNvWZQ=="/>
                                    <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="K39nDbT+TgiCfwtVEPU2xA==" SegmentRef="0y5sdL/gQOGTycsXY0LD6Q=="/>
                                    <air:Connection SegmentIndex="0"/>
                                </air:Option>
                            </air:FlightOption>
                        </air:FlightOptionsList>
                    </air:AirPricingInfo>
                </air:AirPricePoint>
            </air:AirPricePointList>
            <air:BrandList>
                <air:Brand Key="bqRsYnwfQiqsMdVpijA1+w==" BrandID="5705" Name="Economy Flexi" BrandedDetailsAvailable="true" Carrier="SQ">
                    <air:Title Type="External">Economy Flexi</air:Title>
                    <air:Title Type="Short">Flexi</air:Title>
                    <air:Text Type="Upsell">Enjoying the ability to change your flight dates for free, the option to cancel and refund your ticket and upgrade your flight with miles to Business Class when you book on Economy Flexi today.</air:Text>
                    <air:Text Type="MarketingAgent">Our Economy Class innovative design minimizes intrusion into your personal space even as the passenger in front reclines; while ergonomic styling of the seat further maximizes knee and legroom. It also features a headrest that can be adjusted as desired for greater support. An easily accessible seatback-mounted handset, personal and non-intrusive reading light and in-seat power supply, are just some of the many intelligent touches designed with you in mind. (Applicable to most aircraft types)</air:Text>
                    <air:Text Type="Strapline">Sit back and relax</air:Text>
                    <air:ImageLocation Type="Agent" ImageWidth="150" ImageHeight="150">https://merchandisingmanagement.travelport.com/documents/10431/13649/YCL.jpg</air:ImageLocation>
                </air:Brand>
            </air:BrandList>
        </air:LowFareSearchRsp>
    </SOAP:Body>
</SOAP:Envelope>';

        $airLineName = "UL";

        //load the response xml
        $response1_xml = simplexml_load_string($resXmlfilter);

        //registering the namespaces of response
        $response1_xml->registerXPathNamespace('soap', "http://schemas.xmlsoap.org/soap/envelope/");
        $response1_xml->registerXPathNamespace('air', "http://www.travelport.com/schema/air_v33_0");
        //get the all air:FlightDetails tag details
        //  $FlightDetailsValues = $response1_xml->xpath('/soap:Envelope/soap:Body/air:LowFareSearchRsp/air:FlightDetailsList/air:FlightDetails');
        // $AirPricePointValues = $response1_xml->xpath('/soap:Envelope/soap:Body/air:LowFareSearchRsp/air:AirPricePointList/air:AirPricePoint');

        $searchByairline = $response1_xml->xpath('/soap:Envelope/soap:Body/air:LowFareSearchRsp/air:AirSegmentList/air:AirSegment[@Carrier="' . $airLineName . '"]');
        echo '<br><br>';


        print_r($searchByairline);
    }

    public function fetchResponse1UsingnewMethod() {
        $response12 = '<SOAP:Envelope xmlns:SOAP="http://schemas.xmlsoap.org/soap/envelope/">
    <SOAP:Body>
        <air:LowFareSearchRsp TraceId="49087225-93d8-48d0-bfc0-90ad69f9329c" TransactionId="E721EFC00A076477DB8090B8B3C21D12" ResponseTime="30370" DistanceUnits="MI" CurrencyType="LKR" xmlns:air="http://www.travelport.com/schema/air_v33_0">
            <air:FlightDetailsList>
                <air:FlightDetails Key="MZ7QqDr+QC6HuB+4hwSAhg==" Origin="CMB" Destination="MAA" DepartureTime="2015-12-30T18:30:00.000+05:30" ArrivalTime="2015-12-30T19:50:00.000+05:30" FlightTime="80" TravelTime="820" Equipment="321" DestinationTerminal="4"/>
                <air:FlightDetails Key="S/I4sXVZShyRxuajYeDnkw==" Origin="MAA" Destination="BOM" DepartureTime="2015-12-31T06:20:00.000+05:30" ArrivalTime="2015-12-31T08:10:00.000+05:30" FlightTime="110" TravelTime="820" Equipment="319" OriginTerminal="1" DestinationTerminal="2"/>
                <air:FlightDetails Key="v1sX/lIHS5OCc677Pke86g==" Origin="MAA" Destination="BOM" DepartureTime="2015-12-31T16:15:00.000+05:30" ArrivalTime="2015-12-31T18:25:00.000+05:30" FlightTime="130" TravelTime="1435" Equipment="319" OriginTerminal="1" DestinationTerminal="2"/>
                <air:FlightDetails Key="5yhaR0A9R+a51SHQPV1xgQ==" Origin="CMB" Destination="BOM" DepartureTime="2015-12-30T06:10:00.000+05:30" ArrivalTime="2015-12-30T08:45:00.000+05:30" FlightTime="155" TravelTime="155" Equipment="737" DestinationTerminal="2"/>
                <air:FlightDetails Key="MLCdBF/aSy+XB22LguYJFQ==" Origin="CMB" Destination="BOM" DepartureTime="2015-12-30T23:45:00.000+05:30" ArrivalTime="2015-12-31T02:10:00.000+05:30" FlightTime="145" TravelTime="145" Equipment="320" DestinationTerminal="2"/>
                <air:FlightDetails Key="cf5ctDmrShKk0oEcwK5mKw==" Origin="CMB" Destination="DEL" DepartureTime="2015-12-30T08:20:00.000+05:30" ArrivalTime="2015-12-30T12:10:00.000+05:30" FlightTime="230" TravelTime="635" Equipment="321" DestinationTerminal="3"/>
                <air:FlightDetails Key="xNF8HRHMRq294kByDmT/vw==" Origin="DEL" Destination="BOM" DepartureTime="2015-12-30T16:45:00.000+05:30" ArrivalTime="2015-12-30T18:55:00.000+05:30" FlightTime="130" TravelTime="635" Equipment="77W" OriginTerminal="3" DestinationTerminal="2"/>
                <air:FlightDetails Key="t6yaokewROSZPFHnQUHerA==" Origin="DEL" Destination="BOM" DepartureTime="2015-12-30T17:00:00.000+05:30" ArrivalTime="2015-12-30T19:20:00.000+05:30" FlightTime="140" TravelTime="660" Equipment="321" OriginTerminal="3" DestinationTerminal="2"/>
                <air:FlightDetails Key="olM4f/ryQx2A9cRIR9sQRQ==" Origin="MAA" Destination="DEL" DepartureTime="2015-12-31T06:15:00.000+05:30" ArrivalTime="2015-12-31T09:05:00.000+05:30" FlightTime="170" TravelTime="1065" Equipment="321" OriginTerminal="1" DestinationTerminal="3"/>
                <air:FlightDetails Key="gR3inqwpTHK6hFPXLcTAFw==" Origin="DEL" Destination="BOM" DepartureTime="2015-12-31T10:00:00.000+05:30" ArrivalTime="2015-12-31T12:15:00.000+05:30" FlightTime="135" TravelTime="1065" Equipment="321" OriginTerminal="3" DestinationTerminal="2"/>
                <air:FlightDetails Key="BkXr2+XtT7Spev8mwn6wJg==" Origin="MAA" Destination="DEL" DepartureTime="2015-12-31T08:45:00.000+05:30" ArrivalTime="2015-12-31T11:30:00.000+05:30" FlightTime="165" TravelTime="1245" Equipment="320" OriginTerminal="4" DestinationTerminal="3"/>
                <air:FlightDetails Key="9bkSwKyrT2+NSQMTDmcY2w==" Origin="DEL" Destination="BOM" DepartureTime="2015-12-31T13:00:00.000+05:30" ArrivalTime="2015-12-31T15:15:00.000+05:30" FlightTime="135" TravelTime="1245" Equipment="321" OriginTerminal="3" DestinationTerminal="2"/>
                <air:FlightDetails Key="Qx6VGCgKTk6bWemuPs4/Cg==" Origin="DEL" Destination="BLR" DepartureTime="2015-12-30T13:30:00.000+05:30" ArrivalTime="2015-12-30T16:15:00.000+05:30" FlightTime="165" TravelTime="665" Equipment="321" OriginTerminal="3"/>
                <air:FlightDetails Key="DowDQ/atR1etDJVpg71ypw==" Origin="BLR" Destination="BOM" DepartureTime="2015-12-30T17:20:00.000+05:30" ArrivalTime="2015-12-30T19:25:00.000+05:30" FlightTime="125" TravelTime="665" Equipment="319" DestinationTerminal="2"/>
                <air:FlightDetails Key="4JmWXMTETUW7zskwih/i8A==" Origin="CMB" Destination="BKK" DepartureTime="2015-12-30T01:30:00.000+05:30" ArrivalTime="2015-12-30T06:25:00.000+07:00" FlightTime="205" TravelTime="1230" Equipment="777"/>
                <air:FlightDetails Key="kd0QgtB1TquY/inhyO5aeA==" Origin="BKK" Destination="BOM" DepartureTime="2015-12-30T18:55:00.000+07:00" ArrivalTime="2015-12-30T22:00:00.000+05:30" FlightTime="275" TravelTime="1230" Equipment="744" DestinationTerminal="2"/>
                <air:FlightDetails Key="TTdr/Lr3QX+BtXm980mSTw==" Origin="CMB" Destination="KWI" DepartureTime="2015-12-30T05:45:00.000+05:30" ArrivalTime="2015-12-30T08:50:00.000+03:00" FlightTime="335" TravelTime="1365" Equipment="340" DestinationTerminal="M"/>
                <air:FlightDetails Key="i7uhdtG5T4uRFzzlBEixFQ==" Origin="KWI" Destination="BOM" DepartureTime="2015-12-30T22:10:00.000+03:00" ArrivalTime="2015-12-31T04:30:00.000+05:30" FlightTime="230" TravelTime="1365" Equipment="747" OriginTerminal="M" DestinationTerminal="2"/>
                <air:FlightDetails Key="KfXPdHapR8O+9/9R+7mLvg==" Origin="MAA" Destination="BLR" DepartureTime="2015-12-31T14:20:00.000+05:30" ArrivalTime="2015-12-31T15:20:00.000+05:30" FlightTime="60" TravelTime="1495" Equipment="319" OriginTerminal="1"/>
                <air:FlightDetails Key="qgvEWxk+R26BbJvgOryxjQ==" Origin="BLR" Destination="BOM" DepartureTime="2015-12-31T17:20:00.000+05:30" ArrivalTime="2015-12-31T19:25:00.000+05:30" FlightTime="125" TravelTime="1495" Equipment="319" DestinationTerminal="2"/>
                <air:FlightDetails Key="tSY8c+hZQ9qTY8nEg3kzzQ==" Origin="CMB" Destination="MAA" DepartureTime="2015-12-30T07:20:00.000+05:30" ArrivalTime="2015-12-30T08:40:00.000+05:30" FlightTime="80" TravelTime="355" Equipment="332" DestinationTerminal="3"/>
                <air:FlightDetails Key="wJqRUfH1RDSCks37FcPC5w==" Origin="MAA" Destination="BOM" DepartureTime="2015-12-30T11:25:00.000+05:30" ArrivalTime="2015-12-30T13:15:00.000+05:30" FlightTime="110" TravelTime="355" Equipment="737" OriginTerminal="1" DestinationTerminal="1B"/>
                <air:FlightDetails Key="8aue71cFRf62dLqy/4O+Vw==" Origin="CMB" Destination="MAA" DepartureTime="2015-12-30T18:35:00.000+05:30" ArrivalTime="2015-12-30T19:55:00.000+05:30" FlightTime="80" TravelTime="595" Equipment="321" DestinationTerminal="3"/>
                <air:FlightDetails Key="s8+CESe0Qc+qppbGWZYOAw==" Origin="MAA" Destination="BOM" DepartureTime="2015-12-31T02:35:00.000+05:30" ArrivalTime="2015-12-31T04:30:00.000+05:30" FlightTime="115" TravelTime="595" Equipment="73G" OriginTerminal="1" DestinationTerminal="1B"/>
                <air:FlightDetails Key="gUUn5WUjSCKOdTJapJOpeA==" Origin="CMB" Destination="BLR" DepartureTime="2015-12-30T01:15:00.000+05:30" ArrivalTime="2015-12-30T02:35:00.000+05:30" FlightTime="80" TravelTime="370" Equipment="321"/>
                <air:FlightDetails Key="pyq2EE06TGy7ZfMMiJ0ZYQ==" Origin="BLR" Destination="BOM" DepartureTime="2015-12-30T05:50:00.000+05:30" ArrivalTime="2015-12-30T07:25:00.000+05:30" FlightTime="95" TravelTime="370" Equipment="738" DestinationTerminal="1B"/>
                <air:FlightDetails Key="AAyuN5WhSmSPQ+tlLs/CkQ==" Origin="CMB" Destination="DOH" DepartureTime="2015-12-30T09:25:00.000+05:30" ArrivalTime="2015-12-30T11:55:00.000+03:00" FlightTime="300" TravelTime="995" Equipment="346"/>
                <air:FlightDetails Key="pCPYqaTKRxGn8w2ACbvLbA==" Origin="DOH" Destination="BOM" DepartureTime="2015-12-30T20:20:00.000+03:00" ArrivalTime="2015-12-31T02:00:00.000+05:30" FlightTime="190" TravelTime="995" Equipment="77W" DestinationTerminal="2"/>
                <air:FlightDetails Key="aY41THQxSLKLNVmptAxd1g==" Origin="CMB" Destination="MAA" DepartureTime="2015-12-30T00:40:00.000+05:30" ArrivalTime="2015-12-30T02:00:00.000+05:30" FlightTime="80" TravelTime="450" Equipment="320" DestinationTerminal="3"/>
                <air:FlightDetails Key="4DGasBVYRFqyPi0mnFakvQ==" Origin="MAA" Destination="BOM" DepartureTime="2015-12-30T06:20:00.000+05:30" ArrivalTime="2015-12-30T08:10:00.000+05:30" FlightTime="110" TravelTime="450" Equipment="319" OriginTerminal="1" DestinationTerminal="2"/>
                <air:FlightDetails Key="GY+qsQ42SOO5++TalG/92Q==" Origin="CMB" Destination="COK" DepartureTime="2015-12-30T14:05:00.000+05:30" ArrivalTime="2015-12-30T15:25:00.000+05:30" FlightTime="80" TravelTime="580" Equipment="320"/>
                <air:FlightDetails Key="b62DTAMvQ72FOdQQNR260A==" Origin="COK" Destination="BOM" DepartureTime="2015-12-30T22:00:00.000+05:30" ArrivalTime="2015-12-30T23:45:00.000+05:30" FlightTime="105" TravelTime="580" Equipment="320" DestinationTerminal="2"/>
                <air:FlightDetails Key="NN+I3xxzS3uodBXHbaLNYw==" Origin="BLR" Destination="BOM" DepartureTime="2015-12-30T06:45:00.000+05:30" ArrivalTime="2015-12-30T08:20:00.000+05:30" FlightTime="95" TravelTime="425" Equipment="319" DestinationTerminal="2"/>
                <air:FlightDetails Key="iBTeLbTtTCym7UAJuE+xUw==" Origin="CMB" Destination="MCT" DepartureTime="2015-12-30T19:05:00.000+05:30" ArrivalTime="2015-12-30T21:55:00.000+04:00" FlightTime="260" TravelTime="580" Equipment="320"/>
                <air:FlightDetails Key="zWNb+9aqTBKgj0wOyuquXA==" Origin="MCT" Destination="BOM" DepartureTime="2015-12-31T00:20:00.000+04:00" ArrivalTime="2015-12-31T04:45:00.000+05:30" FlightTime="175" TravelTime="580" Equipment="321" DestinationTerminal="2"/>
                <air:FlightDetails Key="Q2M5BR1gTzm6cYeqKF704w==" Origin="CMB" Destination="AUH" DepartureTime="2015-12-30T21:00:00.000+05:30" ArrivalTime="2015-12-31T00:25:00.000+04:00" FlightTime="295" TravelTime="675" Equipment="320" DestinationTerminal="1"/>
                <air:FlightDetails Key="qxHq1wLtQvWMbjmwjeCrGw==" Origin="AUH" Destination="BOM" DepartureTime="2015-12-31T03:10:00.000+04:00" ArrivalTime="2015-12-31T08:15:00.000+05:30" FlightTime="215" TravelTime="675" Equipment="320" OriginTerminal="1" DestinationTerminal="2"/>
                <air:FlightDetails Key="LPgO6qF4TZyKmUK8V+Sn6A==" Origin="CMB" Destination="SIN" DepartureTime="2015-12-30T01:10:00.000+05:30" ArrivalTime="2015-12-30T07:40:00.000+08:00" FlightTime="240" TravelTime="1235" Equipment="333" DestinationTerminal="0"/>
                <air:FlightDetails Key="+sG5rvtiTS6+FhEMywX1cA==" Origin="SIN" Destination="BOM" DepartureTime="2015-12-30T18:55:00.000+08:00" ArrivalTime="2015-12-30T21:45:00.000+05:30" FlightTime="320" TravelTime="1235" Equipment="388" OriginTerminal="2" DestinationTerminal="2"/>
                <air:FlightDetails Key="BQfb4pfdSyOR0tgY11qXHA==" Origin="CMB" Destination="SIN" DepartureTime="2015-12-30T01:00:00.000+05:30" ArrivalTime="2015-12-30T07:30:00.000+08:00" FlightTime="240" TravelTime="750" Equipment="320" DestinationTerminal="3"/>
                <air:FlightDetails Key="X4L5BLbKTDeCvpEynlXb5w==" Origin="SIN" Destination="BOM" DepartureTime="2015-12-30T10:30:00.000+08:00" ArrivalTime="2015-12-30T13:30:00.000+05:30" FlightTime="330" TravelTime="750" Equipment="737" OriginTerminal="3" DestinationTerminal="2"/>
            </air:FlightDetailsList>
            <air:AirSegmentList>
                <air:AirSegment Key="5prwP64xRJSHRE+YUOVOpA==" Group="0" Carrier="AI" FlightNumber="274" Origin="CMB" Destination="MAA" DepartureTime="2015-12-30T18:30:00.000+05:30" ArrivalTime="2015-12-30T19:50:00.000+05:30" FlightTime="80" Distance="402" ETicketability="Yes" Equipment="321" ChangeOfPlane="false" OptionalServicesIndicator="false">
                    <air:AirAvailInfo ProviderCode="1P">
                        <air:BookingCodeInfo BookingCounts="C4|D4|J4|Z4|Y9|B4|M4|H4|K4|Q4|V4|W4|G4|L4|U4|T4|S4|E4|N4"/>
                    </air:AirAvailInfo>
                    <air:FlightDetailsRef Key="MZ7QqDr+QC6HuB+4hwSAhg=="/>
                </air:AirSegment>
                <air:AirSegment Key="ek+tHMznQmqgkV2yB4coXg==" Group="0" Carrier="AI" FlightNumber="569" Origin="MAA" Destination="BOM" DepartureTime="2015-12-31T06:20:00.000+05:30" ArrivalTime="2015-12-31T08:10:00.000+05:30" FlightTime="110" Distance="644" ETicketability="Yes" Equipment="319" ChangeOfPlane="false" OptionalServicesIndicator="false">
                    <air:AirAvailInfo ProviderCode="1P">
                        <air:BookingCodeInfo BookingCounts="C4|D4|J4|Z4|Y9|B4|M4|H4|K4|Q4|V4|W4|G4|L4|U4|T4|S4|E4|N4"/>
                    </air:AirAvailInfo>
                    <air:FlightDetailsRef Key="S/I4sXVZShyRxuajYeDnkw=="/>
                </air:AirSegment>
                <air:AirSegment Key="80Ukm7I3SVS1iSPkRzHoDQ==" Group="0" Carrier="AI" FlightNumber="672" Origin="MAA" Destination="BOM" DepartureTime="2015-12-31T16:15:00.000+05:30" ArrivalTime="2015-12-31T18:25:00.000+05:30" FlightTime="130" Distance="644" ETicketability="Yes" Equipment="319" ChangeOfPlane="false" OptionalServicesIndicator="false">
                    <air:AirAvailInfo ProviderCode="1P">
                        <air:BookingCodeInfo BookingCounts="C4|D4|J4|Z4|Y9|B4|M4|H4|K4|Q4|V4|W4|G4|L4|U4|T4|S4|E4|N4"/>
                    </air:AirAvailInfo>
                    <air:FlightDetailsRef Key="v1sX/lIHS5OCc677Pke86g=="/>
                </air:AirSegment>
                <air:AirSegment Key="wUsM4tuhSx+u75QaV9enIQ==" Group="0" Carrier="9W" FlightNumber="255" Origin="CMB" Destination="BOM" DepartureTime="2015-12-30T06:10:00.000+05:30" ArrivalTime="2015-12-30T08:45:00.000+05:30" FlightTime="155" Distance="948" ETicketability="Yes" Equipment="737" ChangeOfPlane="false" OptionalServicesIndicator="false">
                    <air:AirAvailInfo ProviderCode="1P">
                        <air:BookingCodeInfo BookingCounts="C6|J6|Z5|I5|P3|Y7|M7|T7|U7|N7|L7|Q7|S7|K7|H7|V7|O7|W7"/>
                    </air:AirAvailInfo>
                    <air:FlightDetailsRef Key="5yhaR0A9R+a51SHQPV1xgQ=="/>
                </air:AirSegment>
                <air:AirSegment Key="0sPvLtdHT2if1Sz+um+ZPA==" Group="0" Carrier="UL" FlightNumber="141" Origin="CMB" Destination="BOM" DepartureTime="2015-12-30T23:45:00.000+05:30" ArrivalTime="2015-12-31T02:10:00.000+05:30" FlightTime="145" Distance="948" ETicketability="Yes" Equipment="320" ChangeOfPlane="false" OptionalServicesIndicator="false">
                    <air:AirAvailInfo ProviderCode="1P">
                        <air:BookingCodeInfo BookingCounts="J4|C4|D4|I4|Y7|B7|P4|H7|K7|W4|M7|E4|L7|R4|V7|S4|N7|Q4|O4"/>
                    </air:AirAvailInfo>
                    <air:FlightDetailsRef Key="MLCdBF/aSy+XB22LguYJFQ=="/>
                </air:AirSegment>
                <air:AirSegment Key="zfzYjZssS9O+FjcOPHlS9g==" Group="0" Carrier="MJ" FlightNumber="2141" Origin="CMB" Destination="BOM" DepartureTime="2015-12-30T23:45:00.000+05:30" ArrivalTime="2015-12-31T02:10:00.000+05:30" FlightTime="145" Distance="948" ETicketability="Yes" Equipment="320" ChangeOfPlane="false" OptionalServicesIndicator="false">
                    <air:CodeshareInfo OperatingCarrier="UL" OperatingFlightNumber="141">SRILANKAN AIRLINES</air:CodeshareInfo>
                    <air:AirAvailInfo ProviderCode="1P">
                        <air:BookingCodeInfo BookingCounts="J4|C4|D4|I4|Y4|B4|P4|H4|K4|W4|M4|E4|L4|R4|V4|S4|N4|Q4|O4"/>
                    </air:AirAvailInfo>
                    <air:FlightDetailsRef Key="MLCdBF/aSy+XB22LguYJFQ=="/>
                </air:AirSegment>
                <air:AirSegment Key="BxHogvm4S9+73DL7Iju7ZA==" Group="0" Carrier="AI" FlightNumber="282" Origin="CMB" Destination="DEL" DepartureTime="2015-12-30T08:20:00.000+05:30" ArrivalTime="2015-12-30T12:10:00.000+05:30" FlightTime="230" Distance="1489" ETicketability="Yes" Equipment="321" ChangeOfPlane="false" OptionalServicesIndicator="false">
                    <air:AirAvailInfo ProviderCode="1P">
                        <air:BookingCodeInfo BookingCounts="C4|D4|J4|Z4|Y9|B4|M4|H4|K4|Q4|V4|W4|G4|L4|U4|T4|S4|E4|N4"/>
                    </air:AirAvailInfo>
                    <air:FlightDetailsRef Key="cf5ctDmrShKk0oEcwK5mKw=="/>
                </air:AirSegment>
                <air:AirSegment Key="ql61iU/9TDSWChJyiJrLvg==" Group="0" Carrier="AI" FlightNumber="102" Origin="DEL" Destination="BOM" DepartureTime="2015-12-30T16:45:00.000+05:30" ArrivalTime="2015-12-30T18:55:00.000+05:30" FlightTime="130" Distance="708" ETicketability="Yes" Equipment="77W" ChangeOfPlane="false" OptionalServicesIndicator="false">
                    <air:AirAvailInfo ProviderCode="1P">
                        <air:BookingCodeInfo BookingCounts="F4|A4|C4|D4|J4|Z4|Y9|B4|M4|H4|K4|Q4|V4|W4|G4|L4|U4|T4|S4|E4|N4"/>
                    </air:AirAvailInfo>
                    <air:FlightDetailsRef Key="xNF8HRHMRq294kByDmT/vw=="/>
                </air:AirSegment>
                <air:AirSegment Key="RHZ5VUh6S/mCCegpqpj5dw==" Group="0" Carrier="AI" FlightNumber="659" Origin="DEL" Destination="BOM" DepartureTime="2015-12-30T17:00:00.000+05:30" ArrivalTime="2015-12-30T19:20:00.000+05:30" FlightTime="140" Distance="708" ETicketability="Yes" Equipment="321" ChangeOfPlane="false" OptionalServicesIndicator="false">
                    <air:AirAvailInfo ProviderCode="1P">
                        <air:BookingCodeInfo BookingCounts="C4|D4|J4|Z4|Y9|B4|M4|H4|K4|Q4|V4|W4|G4|L4|U4|T4|S4|E4|N4"/>
                    </air:AirAvailInfo>
                    <air:FlightDetailsRef Key="t6yaokewROSZPFHnQUHerA=="/>
                </air:AirSegment>
                <air:AirSegment Key="H6tKrdeESRet0ZkGQFaWSw==" Group="0" Carrier="AI" FlightNumber="440" Origin="MAA" Destination="DEL" DepartureTime="2015-12-31T06:15:00.000+05:30" ArrivalTime="2015-12-31T09:05:00.000+05:30" FlightTime="170" Distance="1095" ETicketability="Yes" Equipment="321" ChangeOfPlane="false" OptionalServicesIndicator="false">
                    <air:AirAvailInfo ProviderCode="1P">
                        <air:BookingCodeInfo BookingCounts="C4|D4|J4|Z4|Y9|B4|M4|H4|K4|Q4|V4|W4|G4|L4|U4|T4|S4|E4|N4"/>
                    </air:AirAvailInfo>
                    <air:FlightDetailsRef Key="olM4f/ryQx2A9cRIR9sQRQ=="/>
                </air:AirSegment>
                <air:AirSegment Key="w2nddKA4R/S9XZzKQNgDpA==" Group="0" Carrier="AI" FlightNumber="865" Origin="DEL" Destination="BOM" DepartureTime="2015-12-31T10:00:00.000+05:30" ArrivalTime="2015-12-31T12:15:00.000+05:30" FlightTime="135" Distance="708" ETicketability="Yes" Equipment="321" ChangeOfPlane="false" OptionalServicesIndicator="false">
                    <air:AirAvailInfo ProviderCode="1P">
                        <air:BookingCodeInfo BookingCounts="C4|D4|J4|Z4|Y9|B4|M4|H4|K4|Q4|V4|W4|G4|L4|U4|T4|S4|E4|N4"/>
                    </air:AirAvailInfo>
                    <air:FlightDetailsRef Key="gR3inqwpTHK6hFPXLcTAFw=="/>
                </air:AirSegment>
                <air:AirSegment Key="1NFlHND0QRS1Hc37ANWKjA==" Group="0" Carrier="AI" FlightNumber="143" Origin="MAA" Destination="DEL" DepartureTime="2015-12-31T08:45:00.000+05:30" ArrivalTime="2015-12-31T11:30:00.000+05:30" FlightTime="165" Distance="1095" ETicketability="Yes" Equipment="320" ChangeOfPlane="false" OptionalServicesIndicator="false">
                    <air:AirAvailInfo ProviderCode="1P">
                        <air:BookingCodeInfo BookingCounts="C4|D4|J4|Z4|Y9|B4|M4|H4|K4|Q4|V4|W4|G4|L4|U4|T4|S4|E4|N4"/>
                    </air:AirAvailInfo>
                    <air:FlightDetailsRef Key="BkXr2+XtT7Spev8mwn6wJg=="/>
                </air:AirSegment>
                <air:AirSegment Key="LduEaBcySaeOMWkCUyZ7ew==" Group="0" Carrier="AI" FlightNumber="863" Origin="DEL" Destination="BOM" DepartureTime="2015-12-31T13:00:00.000+05:30" ArrivalTime="2015-12-31T15:15:00.000+05:30" FlightTime="135" Distance="708" ETicketability="Yes" Equipment="321" ChangeOfPlane="false" OptionalServicesIndicator="false">
                    <air:AirAvailInfo ProviderCode="1P">
                        <air:BookingCodeInfo BookingCounts="C4|D4|J4|Z4|Y9|B4|M4|H4|K4|Q4|V4|W4|G4|L4|U4|T4|S4|E4|N4"/>
                    </air:AirAvailInfo>
                    <air:FlightDetailsRef Key="9bkSwKyrT2+NSQMTDmcY2w=="/>
                </air:AirSegment>
                <air:AirSegment Key="KrfAd76dRS2C1Qffc0gXxw==" Group="0" Carrier="AI" FlightNumber="502" Origin="DEL" Destination="BLR" DepartureTime="2015-12-30T13:30:00.000+05:30" ArrivalTime="2015-12-30T16:15:00.000+05:30" FlightTime="165" Distance="1063" ETicketability="Yes" Equipment="321" ChangeOfPlane="false" OptionalServicesIndicator="false">
                    <air:AirAvailInfo ProviderCode="1P">
                        <air:BookingCodeInfo BookingCounts="C4|D4|J4|Z4|Y9|B4|M4|H4|K4|Q4|V4|W4|G4|L4|U4|T4|S4|E4|N4"/>
                    </air:AirAvailInfo>
                    <air:FlightDetailsRef Key="Qx6VGCgKTk6bWemuPs4/Cg=="/>
                </air:AirSegment>
                <air:AirSegment Key="tuloGUIxQeOZRii5cjFCuQ==" Group="0" Carrier="AI" FlightNumber="608" Origin="BLR" Destination="BOM" DepartureTime="2015-12-30T17:20:00.000+05:30" ArrivalTime="2015-12-30T19:25:00.000+05:30" FlightTime="125" Distance="519" ETicketability="Yes" Equipment="319" ChangeOfPlane="false" OptionalServicesIndicator="false">
                    <air:AirAvailInfo ProviderCode="1P">
                        <air:BookingCodeInfo BookingCounts="C4|D4|J4|Z4|Y9|B4|M4|H4|K4|Q4|V4|W4|G4|L4|U4|T4|S4|E4|N4"/>
                    </air:AirAvailInfo>
                    <air:FlightDetailsRef Key="DowDQ/atR1etDJVpg71ypw=="/>
                </air:AirSegment>
                <air:AirSegment Key="9nUkPsjMQBSlVG7DYPyFDA==" Group="0" Carrier="TG" FlightNumber="308" Origin="CMB" Destination="BKK" DepartureTime="2015-12-30T01:30:00.000+05:30" ArrivalTime="2015-12-30T06:25:00.000+07:00" FlightTime="205" Distance="1485" ETicketability="Yes" Equipment="777" ChangeOfPlane="false" OptionalServicesIndicator="false">
                    <air:AirAvailInfo ProviderCode="1P">
                        <air:BookingCodeInfo BookingCounts="C9|D9|J9|Z9|I6|R5|Y9|B9|M9|H9|Q9|T9|K9|S9|X9|V9"/>
                    </air:AirAvailInfo>
                    <air:FlightDetailsRef Key="4JmWXMTETUW7zskwih/i8A=="/>
                </air:AirSegment>
                <air:AirSegment Key="yjemUQ11SF+KYeFzbnf/gg==" Group="0" Carrier="TG" FlightNumber="317" Origin="BKK" Destination="BOM" DepartureTime="2015-12-30T18:55:00.000+07:00" ArrivalTime="2015-12-30T22:00:00.000+05:30" FlightTime="275" Distance="1878" ETicketability="Yes" Equipment="744" ChangeOfPlane="false" OptionalServicesIndicator="false">
                    <air:AirAvailInfo ProviderCode="1P">
                        <air:BookingCodeInfo BookingCounts="C9|D5|Y9|B9|M9|H9|Q9|T9|K9|S9|X9|V9|W9|N9"/>
                    </air:AirAvailInfo>
                    <air:FlightDetailsRef Key="kd0QgtB1TquY/inhyO5aeA=="/>
                </air:AirSegment>
                <air:AirSegment Key="CO+0DRsyTviGJgTR2lecVw==" Group="0" Carrier="KU" FlightNumber="362" Origin="CMB" Destination="KWI" DepartureTime="2015-12-30T05:45:00.000+05:30" ArrivalTime="2015-12-30T08:50:00.000+03:00" FlightTime="335" Distance="2573" ETicketability="Yes" Equipment="340" ChangeOfPlane="false" OptionalServicesIndicator="false">
                    <air:AirAvailInfo ProviderCode="1P">
                        <air:BookingCodeInfo BookingCounts="J4|C4|Y9|N9|K9|M9|B9|H9|L9|T9|V9"/>
                    </air:AirAvailInfo>
                    <air:FlightDetailsRef Key="TTdr/Lr3QX+BtXm980mSTw=="/>
                </air:AirSegment>
                <air:AirSegment Key="cqXkk4GiRuqLAZOFpgAFvQ==" Group="0" Carrier="KU" FlightNumber="301" Origin="KWI" Destination="BOM" DepartureTime="2015-12-30T22:10:00.000+03:00" ArrivalTime="2015-12-31T04:30:00.000+05:30" FlightTime="230" Distance="1714" ETicketability="Yes" Equipment="747" ChangeOfPlane="false" OptionalServicesIndicator="false">
                    <air:AirAvailInfo ProviderCode="1P">
                        <air:BookingCodeInfo BookingCounts="J4|C4|Y9|N9|K9|M9|B9|H9|L8"/>
                    </air:AirAvailInfo>
                    <air:FlightDetailsRef Key="i7uhdtG5T4uRFzzlBEixFQ=="/>
                </air:AirSegment>
                <air:AirSegment Key="h8sva1J+TE6pBqF8lJzZqQ==" Group="0" Carrier="AI" FlightNumber="563" Origin="MAA" Destination="BLR" DepartureTime="2015-12-31T14:20:00.000+05:30" ArrivalTime="2015-12-31T15:20:00.000+05:30" FlightTime="60" Distance="168" ETicketability="Yes" Equipment="319" ChangeOfPlane="false" OptionalServicesIndicator="false">
                    <air:AirAvailInfo ProviderCode="1P">
                        <air:BookingCodeInfo BookingCounts="C4|D4|J4|Z4|Y9|B4|M4|H4|K4|Q4|V4|W4|G4|L4|U4|T4|S4|E4|N4"/>
                    </air:AirAvailInfo>
                    <air:FlightDetailsRef Key="KfXPdHapR8O+9/9R+7mLvg=="/>
                </air:AirSegment>
                <air:AirSegment Key="K6ewDLHXS7ialSD9/l+mSg==" Group="0" Carrier="AI" FlightNumber="608" Origin="BLR" Destination="BOM" DepartureTime="2015-12-31T17:20:00.000+05:30" ArrivalTime="2015-12-31T19:25:00.000+05:30" FlightTime="125" Distance="519" ETicketability="Yes" Equipment="319" ChangeOfPlane="false" OptionalServicesIndicator="false">
                    <air:AirAvailInfo ProviderCode="1P">
                        <air:BookingCodeInfo BookingCounts="C4|D4|J4|Z4|Y9|B4|M4|H4|K4|Q4|V4|W4|G4|L4|U4|T4|S4|E4|N4"/>
                    </air:AirAvailInfo>
                    <air:FlightDetailsRef Key="qgvEWxk+R26BbJvgOryxjQ=="/>
                </air:AirSegment>
                <air:AirSegment Key="f90MbwBQSqWb59Ilu9sVlA==" Group="0" Carrier="UL" FlightNumber="121" Origin="CMB" Destination="MAA" DepartureTime="2015-12-30T07:20:00.000+05:30" ArrivalTime="2015-12-30T08:40:00.000+05:30" FlightTime="80" Distance="402" ETicketability="Yes" Equipment="332" ChangeOfPlane="false" OptionalServicesIndicator="false">
                    <air:AirAvailInfo ProviderCode="1P">
                        <air:BookingCodeInfo BookingCounts="J4|C4|D4|I4|Y7|B7|P4|H7|K7|W4|M7|E4|L7|R4|V7|S4|N7|Q4|O4"/>
                    </air:AirAvailInfo>
                    <air:FlightDetailsRef Key="tSY8c+hZQ9qTY8nEg3kzzQ=="/>
                </air:AirSegment>
                <air:AirSegment Key="+nnddfIcQbS2G7jSW1lxLA==" Group="0" Carrier="9W" FlightNumber="460" Origin="MAA" Destination="BOM" DepartureTime="2015-12-30T11:25:00.000+05:30" ArrivalTime="2015-12-30T13:15:00.000+05:30" FlightTime="110" Distance="644" ETicketability="Yes" Equipment="737" ChangeOfPlane="false" OptionalServicesIndicator="false">
                    <air:AirAvailInfo ProviderCode="1P">
                        <air:BookingCodeInfo BookingCounts="C7|J7|Z7|I7|P7|Y7|M7|T7|U7|N7|L7|Q7|S7|K7"/>
                    </air:AirAvailInfo>
                    <air:FlightDetailsRef Key="wJqRUfH1RDSCks37FcPC5w=="/>
                </air:AirSegment>
                <air:AirSegment Key="66jMUncSQHysn0acgiu4zw==" Group="0" Carrier="UL" FlightNumber="123" Origin="CMB" Destination="MAA" DepartureTime="2015-12-30T18:35:00.000+05:30" ArrivalTime="2015-12-30T19:55:00.000+05:30" FlightTime="80" Distance="402" ETicketability="Yes" Equipment="321" ChangeOfPlane="false" OptionalServicesIndicator="false">
                    <air:CodeshareInfo>UL USING MIHIN ACFT</air:CodeshareInfo>
                    <air:AirAvailInfo ProviderCode="1P">
                        <air:BookingCodeInfo BookingCounts="J4|C4|D4|I4|Y7|B7|P4|H7|K7|W4|M7|E4|L7|R4|V7|S4|N7|Q4|O4"/>
                    </air:AirAvailInfo>
                    <air:FlightDetailsRef Key="8aue71cFRf62dLqy/4O+Vw=="/>
                </air:AirSegment>
                <air:AirSegment Key="hwqO5C39TkuzI8J81zb9uw==" Group="0" Carrier="9W" FlightNumber="7012" Origin="MAA" Destination="BOM" DepartureTime="2015-12-31T02:35:00.000+05:30" ArrivalTime="2015-12-31T04:30:00.000+05:30" FlightTime="115" Distance="644" ETicketability="Yes" Equipment="73G" ChangeOfPlane="false" OptionalServicesIndicator="false">
                    <air:CodeshareInfo>JETKONNECT</air:CodeshareInfo>
                    <air:AirAvailInfo ProviderCode="1P">
                        <air:BookingCodeInfo BookingCounts="C7|J7|Z7|I7|P7|Y7|M7|T7|U7|N7|L7|Q7|S7|K7|H7|V7|O7"/>
                    </air:AirAvailInfo>
                    <air:FlightDetailsRef Key="s8+CESe0Qc+qppbGWZYOAw=="/>
                </air:AirSegment>
                <air:AirSegment Key="1OxefzSvRSua5Aa0FhLWJQ==" Group="0" Carrier="UL" FlightNumber="173" Origin="CMB" Destination="BLR" DepartureTime="2015-12-30T01:15:00.000+05:30" ArrivalTime="2015-12-30T02:35:00.000+05:30" FlightTime="80" Distance="442" ETicketability="Yes" Equipment="321" ChangeOfPlane="false" OptionalServicesIndicator="false">
                    <air:CodeshareInfo>UL USING MIHIN ACFT</air:CodeshareInfo>
                    <air:AirAvailInfo ProviderCode="1P">
                        <air:BookingCodeInfo BookingCounts="J4|C4|D4|I4|Y7|B7|P4|H7|K7|W4|M7|E4|L7|R4|V7|S4|N7|Q4|O4"/>
                    </air:AirAvailInfo>
                    <air:FlightDetailsRef Key="gUUn5WUjSCKOdTJapJOpeA=="/>
                </air:AirSegment>
                <air:AirSegment Key="t4OE0jfGQ3OXpZ/2fwT5zA==" Group="0" Carrier="9W" FlightNumber="410" Origin="BLR" Destination="BOM" DepartureTime="2015-12-30T05:50:00.000+05:30" ArrivalTime="2015-12-30T07:25:00.000+05:30" FlightTime="95" Distance="519" ETicketability="Yes" Equipment="738" ChangeOfPlane="false" OptionalServicesIndicator="false">
                    <air:AirAvailInfo ProviderCode="1P">
                        <air:BookingCodeInfo BookingCounts="C7|J7|Z7|I7|P7|Y7|M7|T7|U7|N7|L7|Q7|S7|K7|H7|V7|O7|W7|B7"/>
                    </air:AirAvailInfo>
                    <air:FlightDetailsRef Key="pyq2EE06TGy7ZfMMiJ0ZYQ=="/>
                </air:AirSegment>
                <air:AirSegment Key="PLHLO28JSTiIkZ70ezTAjA==" Group="0" Carrier="QR" FlightNumber="665" Origin="CMB" Destination="DOH" DepartureTime="2015-12-30T09:25:00.000+05:30" ArrivalTime="2015-12-30T11:55:00.000+03:00" FlightTime="300" Distance="2245" ETicketability="Yes" Equipment="346" ChangeOfPlane="false" OptionalServicesIndicator="false">
                    <air:AirAvailInfo ProviderCode="1P">
                        <air:BookingCodeInfo BookingCounts="J9|C9|D9|I9|Y9|B9|H9|K9|M9|L9|V9|S9|N9|Q9|T9|O9"/>
                    </air:AirAvailInfo>
                    <air:FlightDetailsRef Key="AAyuN5WhSmSPQ+tlLs/CkQ=="/>
                </air:AirSegment>
                <air:AirSegment Key="nYcBG+HSRtWNpTFCt/2heg==" Group="0" Carrier="QR" FlightNumber="556" Origin="DOH" Destination="BOM" DepartureTime="2015-12-30T20:20:00.000+03:00" ArrivalTime="2015-12-31T02:00:00.000+05:30" FlightTime="190" Distance="1424" ETicketability="Yes" Equipment="77W" ChangeOfPlane="false" OptionalServicesIndicator="false">
                    <air:AirAvailInfo ProviderCode="1P">
                        <air:BookingCodeInfo BookingCounts="J9|C9|D9|I9|Y9|B9|H9|K9|M9|L9|V9|S9|N9|Q9|T9|O9"/>
                    </air:AirAvailInfo>
                    <air:FlightDetailsRef Key="pCPYqaTKRxGn8w2ACbvLbA=="/>
                </air:AirSegment>
                <air:AirSegment Key="xijIeFvuSr6K8DHHX8qk6w==" Group="0" Carrier="UL" FlightNumber="125" Origin="CMB" Destination="MAA" DepartureTime="2015-12-30T00:40:00.000+05:30" ArrivalTime="2015-12-30T02:00:00.000+05:30" FlightTime="80" Distance="402" ETicketability="Yes" Equipment="320" ChangeOfPlane="false" OptionalServicesIndicator="false">
                    <air:AirAvailInfo ProviderCode="1P">
                        <air:BookingCodeInfo BookingCounts="J4|C4|D4|I4|Y7|B7|P4|H7|K7|W4|M7|E4|L7|R4|V7|S4|N7|Q4|O4"/>
                    </air:AirAvailInfo>
                    <air:FlightDetailsRef Key="aY41THQxSLKLNVmptAxd1g=="/>
                </air:AirSegment>
                <air:AirSegment Key="weDvdh5hS2CMyjkof6NMuQ==" Group="0" Carrier="AI" FlightNumber="569" Origin="MAA" Destination="BOM" DepartureTime="2015-12-30T06:20:00.000+05:30" ArrivalTime="2015-12-30T08:10:00.000+05:30" FlightTime="110" Distance="644" ETicketability="Yes" Equipment="319" ChangeOfPlane="false" OptionalServicesIndicator="false">
                    <air:AirAvailInfo ProviderCode="1P">
                        <air:BookingCodeInfo BookingCounts="C4|D4|J4|Z4|Y9|B4|M4|H4|K4|Q4|V4|W4|G4|L4|U4|T4|S4|E4|N4"/>
                    </air:AirAvailInfo>
                    <air:FlightDetailsRef Key="4DGasBVYRFqyPi0mnFakvQ=="/>
                </air:AirSegment>
                <air:AirSegment Key="wXh2eq+3TYSnj08zmsbg9A==" Group="0" Carrier="UL" FlightNumber="167" Origin="CMB" Destination="COK" DepartureTime="2015-12-30T14:05:00.000+05:30" ArrivalTime="2015-12-30T15:25:00.000+05:30" FlightTime="80" Distance="312" ETicketability="Yes" Equipment="320" ChangeOfPlane="false" OptionalServicesIndicator="false">
                    <air:AirAvailInfo ProviderCode="1P">
                        <air:BookingCodeInfo BookingCounts="J4|C4|D4|I4|Y7|B7|P4|H7|K7|W4|M7|E4|L7|R4|V7|S4|N7|Q4|O4"/>
                    </air:AirAvailInfo>
                    <air:FlightDetailsRef Key="GY+qsQ42SOO5++TalG/92Q=="/>
                </air:AirSegment>
                <air:AirSegment Key="FZ3EP+wCSAO/dTlGXxuirg==" Group="0" Carrier="AI" FlightNumber="55" Origin="COK" Destination="BOM" DepartureTime="2015-12-30T22:00:00.000+05:30" ArrivalTime="2015-12-30T23:45:00.000+05:30" FlightTime="105" Distance="672" ETicketability="Yes" Equipment="320" ChangeOfPlane="false" OptionalServicesIndicator="false">
                    <air:AirAvailInfo ProviderCode="1P">
                        <air:BookingCodeInfo BookingCounts="Y9|B4|M4|H4|K4|Q4|V4|W4|G4|L4|U4|T4|S4|E4|N4"/>
                    </air:AirAvailInfo>
                    <air:FlightDetailsRef Key="b62DTAMvQ72FOdQQNR260A=="/>
                </air:AirSegment>
                <air:AirSegment Key="76aWqUpsTRyj2+iR6l/uHw==" Group="0" Carrier="AI" FlightNumber="640" Origin="BLR" Destination="BOM" DepartureTime="2015-12-30T06:45:00.000+05:30" ArrivalTime="2015-12-30T08:20:00.000+05:30" FlightTime="95" Distance="519" ETicketability="Yes" Equipment="319" ChangeOfPlane="false" OptionalServicesIndicator="false">
                    <air:AirAvailInfo ProviderCode="1P">
                        <air:BookingCodeInfo BookingCounts="C4|D4|J4|Z4|Y9|B4|M4|H4|K4|Q4|V4|W4|G4|L4|U4|T4|S4|E4|N4"/>
                    </air:AirAvailInfo>
                    <air:FlightDetailsRef Key="NN+I3xxzS3uodBXHbaLNYw=="/>
                </air:AirSegment>
                <air:AirSegment Key="dNdj5yZ/QJeItY9bVAzorg==" Group="0" Carrier="UL" FlightNumber="2925" Origin="CMB" Destination="MCT" DepartureTime="2015-12-30T19:05:00.000+05:30" ArrivalTime="2015-12-30T21:55:00.000+04:00" FlightTime="260" Distance="1816" ETicketability="Yes" Equipment="320" ChangeOfPlane="false" OptionalServicesIndicator="false">
                    <air:CodeshareInfo OperatingCarrier="MJ" OperatingFlightNumber="205">MIHIN LANKA</air:CodeshareInfo>
                    <air:AirAvailInfo ProviderCode="1P">
                        <air:BookingCodeInfo BookingCounts="J4|C4|D4|I4|Y7|B7|P4|H7|K7|W4|M7|E4|L7|R4|V7|S4|N7|Q4|O4"/>
                    </air:AirAvailInfo>
                    <air:FlightDetailsRef Key="iBTeLbTtTCym7UAJuE+xUw=="/>
                </air:AirSegment>
                <air:AirSegment Key="QrsjEjXJQUOZMuFsVAAo6Q==" Group="0" Carrier="AI" FlightNumber="986" Origin="MCT" Destination="BOM" DepartureTime="2015-12-31T00:20:00.000+04:00" ArrivalTime="2015-12-31T04:45:00.000+05:30" FlightTime="175" Distance="974" ETicketability="Yes" Equipment="321" ChangeOfPlane="false" OptionalServicesIndicator="false">
                    <air:AirAvailInfo ProviderCode="1P">
                        <air:BookingCodeInfo BookingCounts="C4|D4|J4|Z4|Y9|B4|M4|H4|K4|Q4|V4|W4|G4|L4|U4|T4|S4|E4|N4"/>
                    </air:AirAvailInfo>
                    <air:FlightDetailsRef Key="zWNb+9aqTBKgj0wOyuquXA=="/>
                </air:AirSegment>
                <air:AirSegment Key="UNIWeyDWRTq9AZXzz/cZGg==" Group="0" Carrier="EY" FlightNumber="265" Origin="CMB" Destination="AUH" DepartureTime="2015-12-30T21:00:00.000+05:30" ArrivalTime="2015-12-31T00:25:00.000+04:00" FlightTime="295" Distance="2061" ETicketability="Yes" Equipment="320" ChangeOfPlane="false" OptionalServicesIndicator="false">
                    <air:AirAvailInfo ProviderCode="1P">
                        <air:BookingCodeInfo BookingCounts="J7|C7|D7|W7|Z7|Y7|B7|H7|K7"/>
                    </air:AirAvailInfo>
                    <air:FlightDetailsRef Key="Q2M5BR1gTzm6cYeqKF704w=="/>
                </air:AirSegment>
                <air:AirSegment Key="oVEcNwOeQ+qwN71iIo5Cgg==" Group="0" Carrier="EY" FlightNumber="212" Origin="AUH" Destination="BOM" DepartureTime="2015-12-31T03:10:00.000+04:00" ArrivalTime="2015-12-31T08:15:00.000+05:30" FlightTime="215" Distance="1237" ETicketability="Yes" Equipment="320" ChangeOfPlane="false" OptionalServicesIndicator="false">
                    <air:AirAvailInfo ProviderCode="1P">
                        <air:BookingCodeInfo BookingCounts="J7|C7|D7|W7|Z7|Y7|B7|H7|K7"/>
                    </air:AirAvailInfo>
                    <air:FlightDetailsRef Key="qxHq1wLtQvWMbjmwjeCrGw=="/>
                </air:AirSegment>
                <air:AirSegment Key="D4lv+RGmRpGDZiTXOO2vnA==" Group="0" Carrier="HM" FlightNumber="5523" Origin="CMB" Destination="AUH" DepartureTime="2015-12-30T21:00:00.000+05:30" ArrivalTime="2015-12-31T00:25:00.000+04:00" FlightTime="295" Distance="2061" ETicketability="Yes" Equipment="320" ChangeOfPlane="false" OptionalServicesIndicator="false">
                    <air:CodeshareInfo OperatingCarrier="EY" OperatingFlightNumber="265">ETIHAD AIRWAYS</air:CodeshareInfo>
                    <air:AirAvailInfo ProviderCode="1P">
                        <air:BookingCodeInfo BookingCounts="J4|D4|C4|I4|Y9|B9|H9|K9|L9|W9|S9|M9|N9|V9|G9"/>
                    </air:AirAvailInfo>
                    <air:FlightDetailsRef Key="Q2M5BR1gTzm6cYeqKF704w=="/>
                </air:AirSegment>
                <air:AirSegment Key="4G665u4dT7ezTYmxfQ4UbQ==" Group="0" Carrier="HM" FlightNumber="5320" Origin="AUH" Destination="BOM" DepartureTime="2015-12-31T03:10:00.000+04:00" ArrivalTime="2015-12-31T08:15:00.000+05:30" FlightTime="215" Distance="1237" ETicketability="Yes" Equipment="320" ChangeOfPlane="false" OptionalServicesIndicator="false">
                    <air:CodeshareInfo OperatingCarrier="EY" OperatingFlightNumber="212">ETIHAD AIRWAYS</air:CodeshareInfo>
                    <air:AirAvailInfo ProviderCode="1P">
                        <air:BookingCodeInfo BookingCounts="J4|D4|C4|I4|Y9|B9|H9|K9|L9|W9|S9|M9|N9|V9|G9"/>
                    </air:AirAvailInfo>
                    <air:FlightDetailsRef Key="qxHq1wLtQvWMbjmwjeCrGw=="/>
                </air:AirSegment>
                <air:AirSegment Key="35NDQM4vSBimE3dhLngNpA==" Group="0" Carrier="SQ" FlightNumber="469" Origin="CMB" Destination="SIN" DepartureTime="2015-12-30T01:10:00.000+05:30" ArrivalTime="2015-12-30T07:40:00.000+08:00" FlightTime="240" Distance="1709" ETicketability="Yes" Equipment="333" ChangeOfPlane="false" OptionalServicesIndicator="false">
                    <air:AirAvailInfo ProviderCode="1P">
                        <air:BookingCodeInfo BookingCounts="Z9|C9|J9|U9|D9|I2|Y9|B9|E9|M8|H2"/>
                    </air:AirAvailInfo>
                    <air:FlightDetailsRef Key="LPgO6qF4TZyKmUK8V+Sn6A=="/>
                </air:AirSegment>
                <air:AirSegment Key="DO38sICKQwCJpqLfnEXDRw==" Group="0" Carrier="SQ" FlightNumber="424" Origin="SIN" Destination="BOM" DepartureTime="2015-12-30T18:55:00.000+08:00" ArrivalTime="2015-12-30T21:45:00.000+05:30" FlightTime="320" Distance="2437" ETicketability="Yes" Equipment="388" ChangeOfPlane="false" OptionalServicesIndicator="false">
                    <air:AirAvailInfo ProviderCode="1P">
                        <air:BookingCodeInfo BookingCounts="R9|F3|A2|Z9|C9|J9|U5|D2|I2|Y9|B9|E9|M9|H9|W9|Q9|N9|V9|L9|K9|X4"/>
                    </air:AirAvailInfo>
                    <air:FlightDetailsRef Key="+sG5rvtiTS6+FhEMywX1cA=="/>
                </air:AirSegment>
                <air:AirSegment Key="zGx0vkDWQG6UpfSZuNvWZQ==" Group="0" Carrier="QF" FlightNumber="3420" Origin="CMB" Destination="SIN" DepartureTime="2015-12-30T01:00:00.000+05:30" ArrivalTime="2015-12-30T07:30:00.000+08:00" FlightTime="240" Distance="1709" ETicketability="Yes" Equipment="320" ChangeOfPlane="false" OptionalServicesIndicator="false">
                    <air:CodeshareInfo OperatingCarrier="UL" OperatingFlightNumber="306">SRILANKAN AIRLINES</air:CodeshareInfo>
                    <air:AirAvailInfo ProviderCode="1P">
                        <air:BookingCodeInfo BookingCounts="J9|C9|D9|I3|Y9|B9|H9|K9|M9|L9|V9|S9|N4"/>
                    </air:AirAvailInfo>
                    <air:FlightDetailsRef Key="BQfb4pfdSyOR0tgY11qXHA=="/>
                </air:AirSegment>
                <air:AirSegment Key="0y5sdL/gQOGTycsXY0LD6Q==" Group="0" Carrier="QF" FlightNumber="3957" Origin="SIN" Destination="BOM" DepartureTime="2015-12-30T10:30:00.000+08:00" ArrivalTime="2015-12-30T13:30:00.000+05:30" FlightTime="330" Distance="2437" ETicketability="Yes" Equipment="737" ChangeOfPlane="false" OptionalServicesIndicator="false">
                    <air:CodeshareInfo OperatingCarrier="9W" OperatingFlightNumber="9">JET*AIRWAYS*INDIA*LTD</air:CodeshareInfo>
                    <air:AirAvailInfo ProviderCode="1P">
                        <air:BookingCodeInfo BookingCounts="J9|C9|D9|I9|Y9|B9|H9|K9|M9|L9|V9|S9|N9|Q9"/>
                    </air:AirAvailInfo>
                    <air:FlightDetailsRef Key="X4L5BLbKTDeCvpEynlXb5w=="/>
                </air:AirSegment>
            </air:AirSegmentList>
            <air:FareInfoList>
                <air:FareInfo Key="TuZDJVvgQEmq9ZUxTZ3WSg==" FareBasis="SOW" PassengerTypeCode="ADT" Origin="CMB" Destination="BOM" EffectiveDate="2015-12-28T05:49:00.000+00:00" DepartureDate="2015-12-30" Amount="LKR14000" NegotiatedFare="false" NotValidBefore="2015-12-31" NotValidAfter="2015-12-31">
                    <air:BaggageAllowance>
                        <air:MaxWeight Value="30" Unit="Kilograms"/>
                    </air:BaggageAllowance>
                    <air:FareRuleKey FareInfoRef="TuZDJVvgQEmq9ZUxTZ3WSg==" ProviderCode="1P">dir-CMB^BOM^2015364^ALL^SOW^AI^^</air:FareRuleKey>
                    <air:Brand Key="6MAiNXwgSsyqYacRTNhyHQ==" BrandFound="false" UpSellBrandFound="false"/>
                </air:FareInfo>
                <air:FareInfo Key="AhblwkXVRQC6WMFEKnK7wg==" FareBasis="K2OWLK" PassengerTypeCode="ADT" Origin="CMB" Destination="BOM" EffectiveDate="2015-12-28T05:49:00.000+00:00" DepartureDate="2015-12-30" Amount="LKR19400" NegotiatedFare="false" NotValidBefore="2015-12-30" NotValidAfter="2015-12-30">
                    <air:BaggageAllowance>
                        <air:MaxWeight Value="30" Unit="Kilograms"/>
                    </air:BaggageAllowance>
                    <air:FareRuleKey FareInfoRef="AhblwkXVRQC6WMFEKnK7wg==" ProviderCode="1P">dir-CMB^BOM^2015364^ALL^K2OWLK^9W^^</air:FareRuleKey>
                </air:FareInfo>
                <air:FareInfo Key="2PK7JIGmSomsO5bY5tDj3g==" FareBasis="ROWLK" PassengerTypeCode="ADT" Origin="CMB" Destination="BOM" EffectiveDate="2015-12-28T05:49:00.000+00:00" DepartureDate="2015-12-30" Amount="LKR21900" NegotiatedFare="false" NotValidBefore="2015-12-30" NotValidAfter="2015-12-30">
                    <air:BaggageAllowance>
                        <air:MaxWeight Value="30" Unit="Kilograms"/>
                    </air:BaggageAllowance>
                    <air:FareRuleKey FareInfoRef="2PK7JIGmSomsO5bY5tDj3g==" ProviderCode="1P">dir-CMB^BOM^2015364^ALL^ROWLK^UL^^</air:FareRuleKey>
                </air:FareInfo>
                <air:FareInfo Key="4hE7McgvS66A0aLJpCm4mQ==" FareBasis="REFOWLK" PassengerTypeCode="ADT" Origin="CMB" Destination="BOM" EffectiveDate="2015-12-28T05:49:00.000+00:00" DepartureDate="2015-12-30" Amount="LKR21900" NegotiatedFare="false" NotValidBefore="2015-12-30" NotValidAfter="2015-12-30">
                    <air:BaggageAllowance>
                        <air:MaxWeight Value="30" Unit="Kilograms"/>
                    </air:BaggageAllowance>
                    <air:FareRuleKey FareInfoRef="4hE7McgvS66A0aLJpCm4mQ==" ProviderCode="1P">dir-CMB^BOM^2015364^ALL^REFOWLK^MJ^^</air:FareRuleKey>
                </air:FareInfo>
                <air:FareInfo Key="mSkUl7KcR/a/crv1vuceYA==" FareBasis="TOWLKD" PassengerTypeCode="ADT" Origin="CMB" Destination="BOM" EffectiveDate="2015-12-28T05:49:00.000+00:00" DepartureDate="2015-12-30" Amount="LKR28000" NegotiatedFare="false" NotValidBefore="2015-12-31" NotValidAfter="2015-12-31">
                    <air:BaggageAllowance>
                        <air:MaxWeight Value="30" Unit="Kilograms"/>
                    </air:BaggageAllowance>
                    <air:FareRuleKey FareInfoRef="mSkUl7KcR/a/crv1vuceYA==" ProviderCode="1P">dir-CMB^BOM^2015364^ALL^TOWLKD^AI^^</air:FareRuleKey>
                    <air:Brand Key="SbsntGZhTeKFPhJWKoQOGw==" BrandFound="false" UpSellBrandFound="false"/>
                </air:FareInfo>
                <air:FareInfo Key="gO8UdrdOTOiiD5XKNFRWEQ==" FareBasis="WOWLKD" PassengerTypeCode="ADT" Origin="CMB" Destination="DEL" EffectiveDate="2015-12-28T05:49:00.000+00:00" DepartureDate="2015-12-30" Amount="LKR29000" NegotiatedFare="false" NotValidBefore="2015-12-30" NotValidAfter="2015-12-30">
                    <air:BaggageAllowance>
                        <air:MaxWeight Value="30" Unit="Kilograms"/>
                    </air:BaggageAllowance>
                    <air:FareRuleKey FareInfoRef="gO8UdrdOTOiiD5XKNFRWEQ==" ProviderCode="1P">dir-CMB^DEL^2015364^ALL^WOWLKD^AI^^</air:FareRuleKey>
                    <air:Brand Key="em57A9d6RceWHOHvg8Z7Ew==" BrandFound="false" UpSellBrandFound="false"/>
                </air:FareInfo>
                <air:FareInfo Key="69WLDAOFQc2SDmiQwQvZVQ==" FareBasis="UIPD" PassengerTypeCode="ADT" Origin="DEL" Destination="BLR" EffectiveDate="2015-12-28T05:49:00.000+00:00" DepartureDate="2015-12-30" Amount="LKR3900" NegotiatedFare="false" NotValidBefore="2015-12-30" NotValidAfter="2015-12-30">
                    <air:BaggageAllowance>
                        <air:MaxWeight Value="30" Unit="Kilograms"/>
                    </air:BaggageAllowance>
                    <air:FareRuleKey FareInfoRef="69WLDAOFQc2SDmiQwQvZVQ==" ProviderCode="1P">dir-DEL^BLR^2015364^ALL^UIPD^AI^^</air:FareRuleKey>
                    <air:Brand Key="O0oIROasTImY3KrJTdQU8g==" BrandFound="false" UpSellBrandFound="false"/>
                </air:FareInfo>
                <air:FareInfo Key="t1dugF9jRj+nAxGAgUNE+g==" FareBasis="UIPB" PassengerTypeCode="ADT" Origin="BLR" Destination="BOM" EffectiveDate="2015-12-28T05:49:00.000+00:00" DepartureDate="2015-12-30" Amount="LKR4800" NegotiatedFare="false" NotValidBefore="2015-12-31" NotValidAfter="2015-12-31">
                    <air:BaggageAllowance>
                        <air:MaxWeight Value="30" Unit="Kilograms"/>
                    </air:BaggageAllowance>
                    <air:FareRuleKey FareInfoRef="t1dugF9jRj+nAxGAgUNE+g==" ProviderCode="1P">dir-BLR^BOM^2015364^ALL^UIPB^AI^^</air:FareRuleKey>
                    <air:Brand Key="izXDNnwDSICQ8Lv6TFLhKA==" BrandFound="false" UpSellBrandFound="false"/>
                </air:FareInfo>
                <air:FareInfo Key="JVIVTw1MQ5qvb1Izp53YBg==" FareBasis="VOWLKP" PassengerTypeCode="ADT" Origin="CMB" Destination="BOM" EffectiveDate="2015-12-28T05:49:00.000+00:00" DepartureDate="2015-12-30" Amount="LKR38900" NegotiatedFare="false">
                    <air:FareSurcharge Key="0ZczNQI7QOqmVBzcQRHKZg==" Type="Other" Amount="NUC135.85"/>
                    <air:BaggageAllowance>
                        <air:MaxWeight Value="30" Unit="Kilograms"/>
                    </air:BaggageAllowance>
                    <air:FareRuleKey FareInfoRef="JVIVTw1MQ5qvb1Izp53YBg==" ProviderCode="1P">dir-CMB^BOM^2015364^ALL^VOWLKP^TG^^</air:FareRuleKey>
                    <air:Brand Key="0cC4NVGUR/q1dsg82Vlrug==" BrandFound="false" UpSellBrandFound="false"/>
                </air:FareInfo>
                <air:FareInfo Key="0iTWbgcxT5KqJxFFZjC3XA==" FareBasis="TEE6MLK1" PassengerTypeCode="ADT" Origin="CMB" Destination="KWI" EffectiveDate="2015-12-28T05:49:00.000+00:00" DepartureDate="2015-12-30" Amount="LKR20600" NegotiatedFare="false" NotValidBefore="2015-12-30" NotValidAfter="2015-12-30">
                    <air:BaggageAllowance>
                        <air:MaxWeight Value="20" Unit="Kilograms"/>
                    </air:BaggageAllowance>
                    <air:FareRuleKey FareInfoRef="0iTWbgcxT5KqJxFFZjC3XA==" ProviderCode="1P">dir-CMB^KWI^2015364^ALL^TEE6MLK1^KU^^</air:FareRuleKey>
                </air:FareInfo>
                <air:FareInfo Key="1L/PknWUSbq3OLixZaXMSQ==" FareBasis="LRMF3M" PassengerTypeCode="ADT" Origin="KWI" Destination="BOM" EffectiveDate="2015-12-28T05:49:00.000+00:00" DepartureDate="2015-12-30" Amount="LKR19700" NegotiatedFare="false" NotValidAfter="2016-03-30">
                    <air:BaggageAllowance>
                        <air:MaxWeight Value="20" Unit="Kilograms"/>
                    </air:BaggageAllowance>
                    <air:FareRuleKey FareInfoRef="1L/PknWUSbq3OLixZaXMSQ==" ProviderCode="1P">dir-KWI^BOM^2015364^ALL^LRMF3M^KU^^</air:FareRuleKey>
                </air:FareInfo>
                <air:FareInfo Key="N8f9bATgSJegYdLTuxxbEQ==" FareBasis="WOWLKD" PassengerTypeCode="ADT" Origin="CMB" Destination="BLR" EffectiveDate="2015-12-28T05:49:00.000+00:00" DepartureDate="2015-12-30" Amount="LKR35600" NegotiatedFare="false" NotValidBefore="2015-12-31" NotValidAfter="2015-12-31">
                    <air:BaggageAllowance>
                        <air:MaxWeight Value="30" Unit="Kilograms"/>
                    </air:BaggageAllowance>
                    <air:FareRuleKey FareInfoRef="N8f9bATgSJegYdLTuxxbEQ==" ProviderCode="1P">dir-CMB^BLR^2015364^ALL^WOWLKD^AI^^</air:FareRuleKey>
                    <air:Brand Key="naSOtAdVTlaaNuHWsz5bmQ==" BrandFound="false" UpSellBrandFound="false"/>
                </air:FareInfo>
                <air:FareInfo Key="GDWwSZcqRIy4w4KZpuWqPA==" FareBasis="QOWLK" PassengerTypeCode="ADT" Origin="CMB" Destination="MAA" EffectiveDate="2015-12-28T05:49:00.000+00:00" DepartureDate="2015-12-30" Amount="LKR9800" NegotiatedFare="false" NotValidBefore="2015-12-30" NotValidAfter="2015-12-30">
                    <air:BaggageAllowance>
                        <air:MaxWeight Value="30" Unit="Kilograms"/>
                    </air:BaggageAllowance>
                    <air:FareRuleKey FareInfoRef="GDWwSZcqRIy4w4KZpuWqPA==" ProviderCode="1P">dir-CMB^MAA^2015364^ALL^QOWLK^UL^^</air:FareRuleKey>
                </air:FareInfo>
                <air:FareInfo Key="aTdXioKVRAOB8ZfPQPqBQw==" FareBasis="Y2OW" PassengerTypeCode="ADT" Origin="MAA" Destination="BOM" EffectiveDate="2015-12-28T05:49:00.000+00:00" DepartureDate="2015-12-30" Amount="LKR47200" NegotiatedFare="false" NotValidBefore="2015-12-31" NotValidAfter="2015-12-31">
                    <air:FareSurcharge Key="fUl1Vb3uQfmtmUfD1jOvEA==" Type="Other" Amount="NUC20.00"/>
                    <air:BaggageAllowance>
                        <air:MaxWeight Value="30" Unit="Kilograms"/>
                    </air:BaggageAllowance>
                    <air:FareRuleKey FareInfoRef="aTdXioKVRAOB8ZfPQPqBQw==" ProviderCode="1P">dir-MAA^BOM^2015364^ALL^Y2OW^9W^^</air:FareRuleKey>
                </air:FareInfo>
                <air:FareInfo Key="ylmLppvLQxS1gVu6YrSmXQ==" FareBasis="ROWLK" PassengerTypeCode="ADT" Origin="CMB" Destination="BLR" EffectiveDate="2015-12-28T05:49:00.000+00:00" DepartureDate="2015-12-30" Amount="LKR15000" NegotiatedFare="false" NotValidBefore="2015-12-30" NotValidAfter="2015-12-30">
                    <air:BaggageAllowance>
                        <air:MaxWeight Value="30" Unit="Kilograms"/>
                    </air:BaggageAllowance>
                    <air:FareRuleKey FareInfoRef="ylmLppvLQxS1gVu6YrSmXQ==" ProviderCode="1P">dir-CMB^BLR^2015364^ALL^ROWLK^UL^^</air:FareRuleKey>
                </air:FareInfo>
                <air:FareInfo Key="n3Sh9LBgSkqTQ8/T3DjhCg==" FareBasis="Y2OW" PassengerTypeCode="ADT" Origin="BLR" Destination="BOM" EffectiveDate="2015-12-28T05:49:00.000+00:00" DepartureDate="2015-12-30" Amount="LKR43600" NegotiatedFare="false" NotValidBefore="2015-12-30" NotValidAfter="2015-12-30">
                    <air:FareSurcharge Key="iH3Baq8PSKK0vH2KkE6ahA==" Type="Other" Amount="NUC20.00"/>
                    <air:BaggageAllowance>
                        <air:MaxWeight Value="30" Unit="Kilograms"/>
                    </air:BaggageAllowance>
                    <air:FareRuleKey FareInfoRef="n3Sh9LBgSkqTQ8/T3DjhCg==" ProviderCode="1P">dir-BLR^BOM^2015364^ALL^Y2OW^9W^^</air:FareRuleKey>
                </air:FareInfo>
                <air:FareInfo Key="1zPnV1WpTOOSCyJd0E4T2A==" FareBasis="NJR4R1RI" PassengerTypeCode="ADT" Origin="CMB" Destination="BOM" EffectiveDate="2015-12-28T05:49:00.000+00:00" DepartureDate="2015-12-30" Amount="LKR65700" NegotiatedFare="false" NotValidBefore="2015-12-30" NotValidAfter="2015-12-30">
                    <air:BaggageAllowance>
                        <air:MaxWeight Value="30" Unit="Kilograms"/>
                    </air:BaggageAllowance>
                    <air:FareRuleKey FareInfoRef="1zPnV1WpTOOSCyJd0E4T2A==" ProviderCode="1P">dir-CMB^BOM^2015364^ALL^NJR4R1RI^QR^^</air:FareRuleKey>
                </air:FareInfo>
                <air:FareInfo Key="w3Yn4NV2Rd+v4IT9UmpT3Q==" FareBasis="YIF" PassengerTypeCode="ADT" Origin="CMB" Destination="BOM" EffectiveDate="2015-12-28T05:49:00.000+00:00" DepartureDate="2015-12-30" Amount="LKR76000" NegotiatedFare="false">
                    <air:BaggageAllowance>
                        <air:MaxWeight Value="30" Unit="Kilograms"/>
                    </air:BaggageAllowance>
                    <air:FareRuleKey FareInfoRef="w3Yn4NV2Rd+v4IT9UmpT3Q==" ProviderCode="1P">dir-CMB^BOM^2015364^ALL^YIF^YY^^</air:FareRuleKey>
                </air:FareInfo>
                <air:FareInfo Key="RwAUVJ8nRqqXhP3m4rAxGw==" FareBasis="QOWMJLK" PassengerTypeCode="ADT" Origin="CMB" Destination="MCT" EffectiveDate="2015-12-28T05:49:00.000+00:00" DepartureDate="2015-12-30" Amount="LKR14200" NegotiatedFare="false" NotValidBefore="2015-12-30" NotValidAfter="2015-12-30">
                    <air:FareSurcharge Key="hnNEmH/YTFGoW+I2gkcrHA==" Type="Other" Amount="NUC3.00"/>
                    <air:BaggageAllowance>
                        <air:MaxWeight Value="30" Unit="Kilograms"/>
                    </air:BaggageAllowance>
                    <air:FareRuleKey FareInfoRef="RwAUVJ8nRqqXhP3m4rAxGw==" ProviderCode="1P">dir-CMB^MCT^2015364^ALL^QOWMJLK^UL^^</air:FareRuleKey>
                </air:FareInfo>
                <air:FareInfo Key="0fI0BJ/1Rwe9lMP/VUIgQA==" FareBasis="Y" PassengerTypeCode="ADT" Origin="MCT" Destination="BOM" EffectiveDate="2015-12-28T05:49:00.000+00:00" DepartureDate="2015-12-30" Amount="LKR70500" NegotiatedFare="false">
                    <air:BaggageAllowance>
                        <air:MaxWeight Value="30" Unit="Kilograms"/>
                    </air:BaggageAllowance>
                    <air:FareRuleKey FareInfoRef="0fI0BJ/1Rwe9lMP/VUIgQA==" ProviderCode="1P">dir-MCT^BOM^2015364^ALL^Y^YY^^</air:FareRuleKey>
                    <air:Brand Key="UpWUPELVRz6CL+PZdyTdZw==" BrandFound="false" UpSellBrandFound="false"/>
                </air:FareInfo>
                <air:FareInfo Key="TGq7WcqxQ5OBvvHEm2JK8w==" FareBasis="YRTLK" PassengerTypeCode="ADT" Origin="CMB" Destination="AUH" EffectiveDate="2015-12-28T05:49:00.000+00:00" DepartureDate="2015-12-30" Amount="LKR54900" NegotiatedFare="false" NotValidAfter="2016-12-30">
                    <air:FareTicketDesignator Value="YF"/>
                    <air:FareSurcharge Key="0IS0VcxqSoy6r9Nt6vXl7A==" Type="Other" Amount="NUC52.00"/>
                    <air:BaggageAllowance>
                        <air:NumberOfPieces>2</air:NumberOfPieces>
                        <air:MaxWeight/>
                    </air:BaggageAllowance>
                    <air:FareRuleKey FareInfoRef="TGq7WcqxQ5OBvvHEm2JK8w==" ProviderCode="1P">dir-CMB^AUH^2015364^ALL^YRTLK^EY^^</air:FareRuleKey>
                    <air:Brand Key="x5W3kRi0T42yw65iIiO1gA==" BrandFound="false" UpSellBrandFound="false"/>
                </air:FareInfo>
                <air:FareInfo Key="GPvzaK9tTzqjKluo7iZb9w==" FareBasis="YRT11" PassengerTypeCode="ADT" Origin="AUH" Destination="BOM" EffectiveDate="2015-12-28T05:49:00.000+00:00" DepartureDate="2015-12-30" Amount="LKR128800" NegotiatedFare="false" NotValidAfter="2016-12-30">
                    <air:FareTicketDesignator Value="YF"/>
                    <air:BaggageAllowance>
                        <air:NumberOfPieces>2</air:NumberOfPieces>
                        <air:MaxWeight/>
                    </air:BaggageAllowance>
                    <air:FareRuleKey FareInfoRef="GPvzaK9tTzqjKluo7iZb9w==" ProviderCode="1P">dir-AUH^BOM^2015364^ALL^YRT11^EY^^</air:FareRuleKey>
                    <air:Brand Key="6OuK578uR7qEonfpojZ6TA==" BrandFound="false" UpSellBrandFound="false"/>
                </air:FareInfo>
                <air:FareInfo Key="ewtt09q1QUmecbwuxmRy4w==" FareBasis="YIF" PassengerTypeCode="ADT" Origin="CMB" Destination="AUH" EffectiveDate="2015-12-28T05:49:00.000+00:00" DepartureDate="2015-12-30" Amount="LKR105000" NegotiatedFare="false">
                    <air:BaggageAllowance>
                        <air:NumberOfPieces>2</air:NumberOfPieces>
                        <air:MaxWeight/>
                    </air:BaggageAllowance>
                    <air:FareRuleKey FareInfoRef="ewtt09q1QUmecbwuxmRy4w==" ProviderCode="1P">dir-CMB^AUH^2015364^ALL^YIF^YY^^</air:FareRuleKey>
                </air:FareInfo>
                <air:FareInfo Key="9QFGnYmORCKxeN2iDiIXXA==" FareBasis="YIF" PassengerTypeCode="ADT" Origin="AUH" Destination="BOM" EffectiveDate="2015-12-28T05:49:00.000+00:00" DepartureDate="2015-12-30" Amount="LKR121300" NegotiatedFare="false">
                    <air:BaggageAllowance>
                        <air:NumberOfPieces>2</air:NumberOfPieces>
                        <air:MaxWeight/>
                    </air:BaggageAllowance>
                    <air:FareRuleKey FareInfoRef="9QFGnYmORCKxeN2iDiIXXA==" ProviderCode="1P">dir-AUH^BOM^2015364^ALL^YIF^YY^^</air:FareRuleKey>
                </air:FareInfo>
                <air:FareInfo Key="hGXrS/5oSeOKDKiyWDSOmg==" FareBasis="YRTLK6" PassengerTypeCode="ADT" Origin="CMB" Destination="SIN" EffectiveDate="2015-12-28T05:49:00.000+00:00" DepartureDate="2015-12-30" Amount="LKR61900" NegotiatedFare="false" NotValidAfter="2016-12-30">
                    <air:BaggageAllowance>
                        <air:MaxWeight Value="30" Unit="Kilograms"/>
                    </air:BaggageAllowance>
                    <air:FareRuleKey FareInfoRef="hGXrS/5oSeOKDKiyWDSOmg==" ProviderCode="1P">dir-CMB^SIN^2015364^ALL^YRTLK6^SQ^^</air:FareRuleKey>
                    <air:Brand Key="T7PMCYFdT4mB86+PuGjpUA==" BrandID="5705" UpSellBrandFound="false"/>
                </air:FareInfo>
                <air:FareInfo Key="NwA4s1AlTyqIdeQd9cGzJw==" FareBasis="YIF" PassengerTypeCode="ADT" Origin="SIN" Destination="BOM" EffectiveDate="2015-12-28T05:49:00.000+00:00" DepartureDate="2015-12-30" Amount="LKR250100" NegotiatedFare="false" NotValidAfter="2016-12-30">
                    <air:BaggageAllowance>
                        <air:MaxWeight Value="30" Unit="Kilograms"/>
                    </air:BaggageAllowance>
                    <air:FareRuleKey FareInfoRef="NwA4s1AlTyqIdeQd9cGzJw==" ProviderCode="1P">dir-SIN^BOM^2015364^ALL^YIF^YY^^</air:FareRuleKey>
                    <air:Brand Key="5GH0xkHwR2ygIoJHc6YboQ==" BrandID="5705" UpSellBrandFound="false"/>
                </air:FareInfo>
                <air:FareInfo Key="I+jbjgnpRoWcOtHxAPcaPg==" FareBasis="YIF" PassengerTypeCode="ADT" Origin="CMB" Destination="SIN" EffectiveDate="2015-12-28T05:49:00.000+00:00" DepartureDate="2015-12-30" Amount="LKR71500" NegotiatedFare="false">
                    <air:BaggageAllowance>
                        <air:MaxWeight Value="30" Unit="Kilograms"/>
                    </air:BaggageAllowance>
                    <air:FareRuleKey FareInfoRef="I+jbjgnpRoWcOtHxAPcaPg==" ProviderCode="1P">dir-CMB^SIN^2015364^ALL^YIF^YY^^</air:FareRuleKey>
                </air:FareInfo>
                <air:FareInfo Key="K39nDbT+TgiCfwtVEPU2xA==" FareBasis="YIF" PassengerTypeCode="ADT" Origin="SIN" Destination="BOM" EffectiveDate="2015-12-28T05:49:00.000+00:00" DepartureDate="2015-12-30" Amount="LKR250100" NegotiatedFare="false">
                    <air:BaggageAllowance>
                        <air:MaxWeight Value="30" Unit="Kilograms"/>
                    </air:BaggageAllowance>
                    <air:FareRuleKey FareInfoRef="K39nDbT+TgiCfwtVEPU2xA==" ProviderCode="1P">dir-SIN^BOM^2015364^ALL^YIF^YY^^</air:FareRuleKey>
                </air:FareInfo>
            </air:FareInfoList>
            <air:RouteList>
                <air:Route Key="sJCcLnKlS5mQ/NMUZow/Xw==">
                    <air:Leg Key="Yy/n9UuxR+uMskWhMvyx2A==" Group="0" Origin="CMB" Destination="BOM"/>
                </air:Route>
            </air:RouteList>
            <air:AirPricePointList>
                <air:AirPricePoint Key="n/PESxXuR6yWWt7Cmq5TSg==" TotalPrice="LKR17500" BasePrice="LKR14000" ApproximateTotalPrice="LKR17500" ApproximateBasePrice="LKR14000" Taxes="LKR3500" ApproximateTaxes="LKR3500" CompleteItinerary="true">
                    <air:AirPricingInfo Key="zh5MJfIzSruSZHcoaMNM8g==" TotalPrice="LKR17500" BasePrice="LKR14000" ApproximateTotalPrice="LKR17500" ApproximateBasePrice="LKR14000" Taxes="LKR3500" LatestTicketingTime="2015-12-30T23:59:00.000+00:00" PricingMethod="Unknown" Refundable="true" ETicketability="Yes" PlatingCarrier="AI" ProviderCode="1P">
                        <air:FareInfoRef Key="TuZDJVvgQEmq9ZUxTZ3WSg=="/>
                        <air:TaxInfo Category="LK" Amount="LKR3500"/>
                        <air:FareCalc>CMB AI X/MAA AI BOM101.22NUC101.22END ROE138.30</air:FareCalc>
                        <air:PassengerType Code="ADT" Age="40"/>
                        <air:ChangePenalty>
                            <air:Amount>LKR2200.0</air:Amount>
                        </air:ChangePenalty>
                        <air:CancelPenalty>
                            <air:Amount>LKR4000.0</air:Amount>
                        </air:CancelPenalty>
                        <air:FlightOptionsList>
                            <air:FlightOption LegRef="Yy/n9UuxR+uMskWhMvyx2A==" Destination="BOM" Origin="CMB">
                                <air:Option Key="MWRGMVQeSZyfZYdAqIPnUg==" TravelTime="P0DT13H40M0S">
                                    <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="TuZDJVvgQEmq9ZUxTZ3WSg==" SegmentRef="5prwP64xRJSHRE+YUOVOpA=="/>
                                    <air:BookingInfo BookingCode="V" CabinClass="Economy" FareInfoRef="TuZDJVvgQEmq9ZUxTZ3WSg==" SegmentRef="ek+tHMznQmqgkV2yB4coXg=="/>
                                    <air:Connection SegmentIndex="0"/>
                                </air:Option>
                                <air:Option Key="XaQdWqXtQBeyFUVr4KhdrQ==" TravelTime="P0DT23H55M0S">
                                    <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="TuZDJVvgQEmq9ZUxTZ3WSg==" SegmentRef="5prwP64xRJSHRE+YUOVOpA=="/>
                                    <air:BookingInfo BookingCode="V" CabinClass="Economy" FareInfoRef="TuZDJVvgQEmq9ZUxTZ3WSg==" SegmentRef="80Ukm7I3SVS1iSPkRzHoDQ=="/>
                                    <air:Connection SegmentIndex="0"/>
                                </air:Option>
                            </air:FlightOption>
                        </air:FlightOptionsList>
                    </air:AirPricingInfo>
                </air:AirPricePoint>
                <air:AirPricePoint Key="NgDGBIvtQDqPMOv1XhPXUA==" TotalPrice="LKR22900" BasePrice="LKR19400" ApproximateTotalPrice="LKR22900" ApproximateBasePrice="LKR19400" Taxes="LKR3500" ApproximateTaxes="LKR3500" CompleteItinerary="true">
                    <air:AirPricingInfo Key="Ju6rTwxBR22lPZFQtosphw==" TotalPrice="LKR22900" BasePrice="LKR19400" ApproximateTotalPrice="LKR22900" ApproximateBasePrice="LKR19400" Taxes="LKR3500" LatestTicketingTime="2015-12-30T23:59:00.000+00:00" PricingMethod="Unknown" Refundable="true" ETicketability="Yes" PlatingCarrier="9W" ProviderCode="1P">
                        <air:FareInfoRef Key="AhblwkXVRQC6WMFEKnK7wg=="/>
                        <air:TaxInfo Category="LK" Amount="LKR3500"/>
                        <air:FareCalc>CMB 9W BOM140.27NUC140.27END ROE138.30</air:FareCalc>
                        <air:PassengerType Code="ADT" Age="40"/>
                        <air:ChangePenalty>
                            <air:Amount>LKR2000.0</air:Amount>
                        </air:ChangePenalty>
                        <air:CancelPenalty>
                            <air:Amount>LKR3000.0</air:Amount>
                        </air:CancelPenalty>
                        <air:FlightOptionsList>
                            <air:FlightOption LegRef="Yy/n9UuxR+uMskWhMvyx2A==" Destination="BOM" Origin="CMB">
                                <air:Option Key="AemdhwMjQc6DjWj/lvamWw==" TravelTime="P0DT2H35M0S">
                                    <air:BookingInfo BookingCode="K" CabinClass="Economy" FareInfoRef="AhblwkXVRQC6WMFEKnK7wg==" SegmentRef="wUsM4tuhSx+u75QaV9enIQ=="/>
                                </air:Option>
                            </air:FlightOption>
                        </air:FlightOptionsList>
                    </air:AirPricingInfo>
                </air:AirPricePoint>
                <air:AirPricePoint Key="rQihiE6gSWCwvAc6OUpXYw==" TotalPrice="LKR26070" BasePrice="LKR21900" ApproximateTotalPrice="LKR26070" ApproximateBasePrice="LKR21900" Taxes="LKR4170" ApproximateTaxes="LKR4170" CompleteItinerary="true">
                    <air:AirPricingInfo Key="eqx/lBdXSH2WYe2mIrutTw==" TotalPrice="LKR26070" BasePrice="LKR21900" ApproximateTotalPrice="LKR26070" ApproximateBasePrice="LKR21900" Taxes="LKR4170" LatestTicketingTime="2015-12-30T23:59:00.000+00:00" PricingMethod="Unknown" Refundable="true" ETicketability="Yes" PlatingCarrier="UL" ProviderCode="1P">
                        <air:FareInfoRef Key="2PK7JIGmSomsO5bY5tDj3g=="/>
                        <air:TaxInfo Category="LK" Amount="LKR3500"/>
                        <air:TaxInfo Category="YQ" Amount="LKR670"/>
                        <air:FareCalc>CMB UL BOM158.35NUC158.35END ROE138.30</air:FareCalc>
                        <air:PassengerType Code="ADT" Age="40"/>
                        <air:ChangePenalty>
                            <air:Amount>LKR4500.0</air:Amount>
                        </air:ChangePenalty>
                        <air:FlightOptionsList>
                            <air:FlightOption LegRef="Yy/n9UuxR+uMskWhMvyx2A==" Destination="BOM" Origin="CMB">
                                <air:Option Key="/Z2+2ixxQQ2g3C7jsrj+RA==" TravelTime="P0DT2H25M0S">
                                    <air:BookingInfo BookingCode="R" CabinClass="Economy" FareInfoRef="2PK7JIGmSomsO5bY5tDj3g==" SegmentRef="0sPvLtdHT2if1Sz+um+ZPA=="/>
                                </air:Option>
                            </air:FlightOption>
                        </air:FlightOptionsList>
                    </air:AirPricingInfo>
                </air:AirPricePoint>
                <air:AirPricePoint Key="6tK6/sN5RN+mrKRDMtfSmA==" TotalPrice="LKR26070" BasePrice="LKR21900" ApproximateTotalPrice="LKR26070" ApproximateBasePrice="LKR21900" Taxes="LKR4170" ApproximateTaxes="LKR4170" CompleteItinerary="true">
                    <air:AirPricingInfo Key="bGtMBzsJQrWO+QNW2QHEBg==" TotalPrice="LKR26070" BasePrice="LKR21900" ApproximateTotalPrice="LKR26070" ApproximateBasePrice="LKR21900" Taxes="LKR4170" LatestTicketingTime="2015-12-30T23:59:00.000+00:00" PricingMethod="Unknown" Refundable="true" ETicketability="Yes" PlatingCarrier="MJ" ProviderCode="1P">
                        <air:FareInfoRef Key="4hE7McgvS66A0aLJpCm4mQ=="/>
                        <air:TaxInfo Category="LK" Amount="LKR3500"/>
                        <air:TaxInfo Category="YQ" Amount="LKR670"/>
                        <air:FareCalc>CMB MJ BOM158.35NUC158.35END ROE138.30</air:FareCalc>
                        <air:PassengerType Code="ADT" Age="40"/>
                        <air:ChangePenalty>
                            <air:Amount>LKR4500.0</air:Amount>
                        </air:ChangePenalty>
                        <air:FlightOptionsList>
                            <air:FlightOption LegRef="Yy/n9UuxR+uMskWhMvyx2A==" Destination="BOM" Origin="CMB">
                                <air:Option Key="lAA3FByfT+uEvGvl1ayOtA==" TravelTime="P0DT2H25M0S">
                                    <air:BookingInfo BookingCode="R" CabinClass="Economy" FareInfoRef="4hE7McgvS66A0aLJpCm4mQ==" SegmentRef="zfzYjZssS9O+FjcOPHlS9g=="/>
                                </air:Option>
                            </air:FlightOption>
                        </air:FlightOptionsList>
                    </air:AirPricingInfo>
                </air:AirPricePoint>
                <air:AirPricePoint Key="UJu67e8lS02t47m0M+8m4A==" TotalPrice="LKR31500" BasePrice="LKR28000" ApproximateTotalPrice="LKR31500" ApproximateBasePrice="LKR28000" Taxes="LKR3500" ApproximateTaxes="LKR3500" CompleteItinerary="true">
                    <air:AirPricingInfo Key="2UOKz/5oS+GFWBTHKsj5qw==" TotalPrice="LKR31500" BasePrice="LKR28000" ApproximateTotalPrice="LKR31500" ApproximateBasePrice="LKR28000" Taxes="LKR3500" LatestTicketingTime="2015-12-30T23:59:00.000+00:00" PricingMethod="Unknown" Refundable="true" ETicketability="Yes" PlatingCarrier="AI" ProviderCode="1P">
                        <air:FareInfoRef Key="mSkUl7KcR/a/crv1vuceYA=="/>
                        <air:TaxInfo Category="LK" Amount="LKR3500"/>
                        <air:FareCalc>CMB AI X/DEL AI BOM202.45NUC202.45END ROE138.30</air:FareCalc>
                        <air:PassengerType Code="ADT" Age="40"/>
                        <air:ChangePenalty>
                            <air:Amount>LKR2200.0</air:Amount>
                        </air:ChangePenalty>
                        <air:CancelPenalty>
                            <air:Amount>LKR4000.0</air:Amount>
                        </air:CancelPenalty>
                        <air:FlightOptionsList>
                            <air:FlightOption LegRef="Yy/n9UuxR+uMskWhMvyx2A==" Destination="BOM" Origin="CMB">
                                <air:Option Key="zATMVH64SQeiw22A/zXhlQ==" TravelTime="P0DT10H35M0S">
                                    <air:BookingInfo BookingCode="T" CabinClass="Economy" FareInfoRef="mSkUl7KcR/a/crv1vuceYA==" SegmentRef="BxHogvm4S9+73DL7Iju7ZA=="/>
                                    <air:BookingInfo BookingCode="V" CabinClass="Economy" FareInfoRef="mSkUl7KcR/a/crv1vuceYA==" SegmentRef="ql61iU/9TDSWChJyiJrLvg=="/>
                                    <air:Connection SegmentIndex="0"/>
                                </air:Option>
                                <air:Option Key="TlsxYeghT1qyhI9q4hJHew==" TravelTime="P0DT11H0M0S">
                                    <air:BookingInfo BookingCode="T" CabinClass="Economy" FareInfoRef="mSkUl7KcR/a/crv1vuceYA==" SegmentRef="BxHogvm4S9+73DL7Iju7ZA=="/>
                                    <air:BookingInfo BookingCode="V" CabinClass="Economy" FareInfoRef="mSkUl7KcR/a/crv1vuceYA==" SegmentRef="RHZ5VUh6S/mCCegpqpj5dw=="/>
                                    <air:Connection SegmentIndex="0"/>
                                </air:Option>
                            </air:FlightOption>
                        </air:FlightOptionsList>
                    </air:AirPricingInfo>
                </air:AirPricePoint>
                <air:AirPricePoint Key="+IuWNR//RLuZiTRfHbBSgA==" TotalPrice="LKR31500" BasePrice="LKR28000" ApproximateTotalPrice="LKR31500" ApproximateBasePrice="LKR28000" Taxes="LKR3500" ApproximateTaxes="LKR3500" CompleteItinerary="true">
                    <air:AirPricingInfo Key="ec1yuXMfRM2FCTweq2466g==" TotalPrice="LKR31500" BasePrice="LKR28000" ApproximateTotalPrice="LKR31500" ApproximateBasePrice="LKR28000" Taxes="LKR3500" LatestTicketingTime="2015-12-30T23:59:00.000+00:00" PricingMethod="Unknown" Refundable="true" ETicketability="Yes" PlatingCarrier="AI" ProviderCode="1P">
                        <air:FareInfoRef Key="mSkUl7KcR/a/crv1vuceYA=="/>
                        <air:TaxInfo Category="LK" Amount="LKR3500"/>
                        <air:FareCalc>CMB AI X/MAA AI X/DEL AI BOM202.45NUC202.45END ROE138.30</air:FareCalc>
                        <air:PassengerType Code="ADT" Age="40"/>
                        <air:ChangePenalty>
                            <air:Amount>LKR2200.0</air:Amount>
                        </air:ChangePenalty>
                        <air:CancelPenalty>
                            <air:Amount>LKR4000.0</air:Amount>
                        </air:CancelPenalty>
                        <air:FlightOptionsList>
                            <air:FlightOption LegRef="Yy/n9UuxR+uMskWhMvyx2A==" Destination="BOM" Origin="CMB">
                                <air:Option Key="X/HNB3qtTEOH0DUXsKgOSA==" TravelTime="P0DT17H45M0S">
                                    <air:BookingInfo BookingCode="T" CabinClass="Economy" FareInfoRef="mSkUl7KcR/a/crv1vuceYA==" SegmentRef="5prwP64xRJSHRE+YUOVOpA=="/>
                                    <air:BookingInfo BookingCode="V" CabinClass="Economy" FareInfoRef="mSkUl7KcR/a/crv1vuceYA==" SegmentRef="H6tKrdeESRet0ZkGQFaWSw=="/>
                                    <air:BookingInfo BookingCode="V" CabinClass="Economy" FareInfoRef="mSkUl7KcR/a/crv1vuceYA==" SegmentRef="w2nddKA4R/S9XZzKQNgDpA=="/>
                                    <air:Connection SegmentIndex="0"/>
                                    <air:Connection SegmentIndex="1"/>
                                </air:Option>
                                <air:Option Key="9DByLbNVR9uYQIUeMcmPDg==" TravelTime="P0DT20H45M0S">
                                    <air:BookingInfo BookingCode="T" CabinClass="Economy" FareInfoRef="mSkUl7KcR/a/crv1vuceYA==" SegmentRef="5prwP64xRJSHRE+YUOVOpA=="/>
                                    <air:BookingInfo BookingCode="V" CabinClass="Economy" FareInfoRef="mSkUl7KcR/a/crv1vuceYA==" SegmentRef="1NFlHND0QRS1Hc37ANWKjA=="/>
                                    <air:BookingInfo BookingCode="V" CabinClass="Economy" FareInfoRef="mSkUl7KcR/a/crv1vuceYA==" SegmentRef="LduEaBcySaeOMWkCUyZ7ew=="/>
                                    <air:Connection SegmentIndex="0"/>
                                    <air:Connection SegmentIndex="1"/>
                                </air:Option>
                            </air:FlightOption>
                        </air:FlightOptionsList>
                    </air:AirPricingInfo>
                </air:AirPricePoint>
                <air:AirPricePoint Key="Dx4yVBqWT0SyALLXY2L90w==" TotalPrice="LKR42303" BasePrice="LKR37700" ApproximateTotalPrice="LKR42303" ApproximateBasePrice="LKR37700" Taxes="LKR4603" ApproximateTaxes="LKR4603" CompleteItinerary="true">
                    <air:AirPricingInfo Key="QAK1vd+cSee5lzfG7plMDA==" TotalPrice="LKR42303" BasePrice="LKR37700" ApproximateTotalPrice="LKR42303" ApproximateBasePrice="LKR37700" Taxes="LKR4603" LatestTicketingTime="2015-12-30T23:59:00.000+00:00" PricingMethod="Unknown" Refundable="true" ETicketability="Yes" PlatingCarrier="AI" ProviderCode="1P">
                        <air:FareInfoRef Key="gO8UdrdOTOiiD5XKNFRWEQ=="/>
                        <air:FareInfoRef Key="69WLDAOFQc2SDmiQwQvZVQ=="/>
                        <air:FareInfoRef Key="t1dugF9jRj+nAxGAgUNE+g=="/>
                        <air:TaxInfo Category="LK" Amount="LKR3500"/>
                        <air:TaxInfo Category="IN" Amount="LKR774"/>
                        <air:TaxInfo Category="WO" Amount="LKR329"/>
                        <air:FareCalc>CMB AI DEL209.68AI BLR27.95AI BOM34.56NUC272.19END ROE138.30</air:FareCalc>
                        <air:PassengerType Code="ADT" Age="40"/>
                        <air:ChangePenalty>
                            <air:Amount>LKR2400.0</air:Amount>
                        </air:ChangePenalty>
                        <air:CancelPenalty>
                            <air:Amount>LKR2400.0</air:Amount>
                        </air:CancelPenalty>
                        <air:FlightOptionsList>
                            <air:FlightOption LegRef="Yy/n9UuxR+uMskWhMvyx2A==" Destination="BOM" Origin="CMB">
                                <air:Option Key="1pgV2siUSfaynTUn1zwFNQ==" TravelTime="P0DT11H5M0S">
                                    <air:BookingInfo BookingCode="W" CabinClass="Economy" FareInfoRef="gO8UdrdOTOiiD5XKNFRWEQ==" SegmentRef="BxHogvm4S9+73DL7Iju7ZA=="/>
                                    <air:BookingInfo BookingCode="U" CabinClass="Economy" FareInfoRef="69WLDAOFQc2SDmiQwQvZVQ==" SegmentRef="KrfAd76dRS2C1Qffc0gXxw=="/>
                                    <air:BookingInfo BookingCode="U" CabinClass="Economy" FareInfoRef="t1dugF9jRj+nAxGAgUNE+g==" SegmentRef="tuloGUIxQeOZRii5cjFCuQ=="/>
                                    <air:Connection SegmentIndex="0"/>
                                    <air:Connection SegmentIndex="1"/>
                                </air:Option>
                            </air:FlightOption>
                        </air:FlightOptionsList>
                    </air:AirPricingInfo>
                </air:AirPricePoint>
                <air:AirPricePoint Key="ttfWE8ahTGefY1pycXljKw==" TotalPrice="LKR42688" BasePrice="LKR38900" ApproximateTotalPrice="LKR42688" ApproximateBasePrice="LKR38900" Taxes="LKR3788" ApproximateTaxes="LKR3788" CompleteItinerary="true">
                    <air:AirPricingInfo Key="fSp+lwPcRuGpbQWcCQO1Og==" TotalPrice="LKR42688" BasePrice="LKR38900" ApproximateTotalPrice="LKR42688" ApproximateBasePrice="LKR38900" Taxes="LKR3788" LatestTicketingTime="2015-12-30T23:59:00.000+00:00" PricingMethod="Unknown" Refundable="true" ETicketability="Yes" PlatingCarrier="TG" ProviderCode="1P">
                        <air:FareInfoRef Key="JVIVTw1MQ5qvb1Izp53YBg=="/>
                        <air:TaxInfo Category="LK" Amount="LKR3500"/>
                        <air:TaxInfo Category="E7" Amount="LKR288"/>
                        <air:FareCalc>CMB TG X/BKK TG BOM Q135.85 145.33NUC281.18END ROE138.30</air:FareCalc>
                        <air:PassengerType Code="ADT" Age="40"/>
                        <air:FlightOptionsList>
                            <air:FlightOption LegRef="Yy/n9UuxR+uMskWhMvyx2A==" Destination="BOM" Origin="CMB">
                                <air:Option Key="SgbMFwi3SjCDX6XrROSZmA==" TravelTime="P0DT20H30M0S">
                                    <air:BookingInfo BookingCode="V" CabinClass="Economy" FareInfoRef="JVIVTw1MQ5qvb1Izp53YBg==" SegmentRef="9nUkPsjMQBSlVG7DYPyFDA=="/>
                                    <air:BookingInfo BookingCode="V" CabinClass="Economy" FareInfoRef="JVIVTw1MQ5qvb1Izp53YBg==" SegmentRef="yjemUQ11SF+KYeFzbnf/gg=="/>
                                    <air:Connection SegmentIndex="0"/>
                                </air:Option>
                            </air:FlightOption>
                        </air:FlightOptionsList>
                    </air:AirPricingInfo>
                </air:AirPricePoint>
                <air:AirPricePoint Key="h1EzfvUdSvCEqRbhMaA8pA==" TotalPrice="LKR43918" BasePrice="LKR40300" ApproximateTotalPrice="LKR43918" ApproximateBasePrice="LKR40300" Taxes="LKR3618" ApproximateTaxes="LKR3618" CompleteItinerary="true">
                    <air:AirPricingInfo Key="jwv0R9xnQgqi0dbyyUbmvA==" TotalPrice="LKR43918" BasePrice="LKR40300" ApproximateTotalPrice="LKR43918" ApproximateBasePrice="LKR40300" Taxes="LKR3618" LatestTicketingTime="2015-12-30T23:59:00.000+00:00" PricingMethod="Unknown" Refundable="true" ETicketability="Yes" PlatingCarrier="KU" ProviderCode="1P">
                        <air:FareInfoRef Key="0iTWbgcxT5KqJxFFZjC3XA=="/>
                        <air:FareInfoRef Key="1L/PknWUSbq3OLixZaXMSQ=="/>
                        <air:TaxInfo Category="LK" Amount="LKR3500"/>
                        <air:TaxInfo Category="YX" Amount="LKR118"/>
                        <air:FareCalc>CMB KU KWI148.59KU BOM142.24NUC290.83END ROE138.30</air:FareCalc>
                        <air:PassengerType Code="ADT" Age="40"/>
                        <air:FlightOptionsList>
                            <air:FlightOption LegRef="Yy/n9UuxR+uMskWhMvyx2A==" Destination="BOM" Origin="CMB">
                                <air:Option Key="191PwJe0SMGrUPgvuS8cxw==" TravelTime="P0DT22H45M0S">
                                    <air:BookingInfo BookingCode="T" CabinClass="Economy" FareInfoRef="0iTWbgcxT5KqJxFFZjC3XA==" SegmentRef="CO+0DRsyTviGJgTR2lecVw=="/>
                                    <air:BookingInfo BookingCode="L" CabinClass="Economy" FareInfoRef="1L/PknWUSbq3OLixZaXMSQ==" SegmentRef="cqXkk4GiRuqLAZOFpgAFvQ=="/>
                                    <air:Connection SegmentIndex="0"/>
                                </air:Option>
                            </air:FlightOption>
                        </air:FlightOptionsList>
                    </air:AirPricingInfo>
                </air:AirPricePoint>
                <air:AirPricePoint Key="gz73uLQJT8Kc1rk9Q79iUA==" TotalPrice="LKR45003" BasePrice="LKR40400" ApproximateTotalPrice="LKR45003" ApproximateBasePrice="LKR40400" Taxes="LKR4603" ApproximateTaxes="LKR4603" CompleteItinerary="true">
                    <air:AirPricingInfo Key="DYXd11iARs2ldz5jhEmLUg==" TotalPrice="LKR45003" BasePrice="LKR40400" ApproximateTotalPrice="LKR45003" ApproximateBasePrice="LKR40400" Taxes="LKR4603" LatestTicketingTime="2015-12-30T23:59:00.000+00:00" PricingMethod="Unknown" Refundable="true" ETicketability="Yes" PlatingCarrier="AI" ProviderCode="1P">
                        <air:FareInfoRef Key="N8f9bATgSJegYdLTuxxbEQ=="/>
                        <air:FareInfoRef Key="t1dugF9jRj+nAxGAgUNE+g=="/>
                        <air:TaxInfo Category="LK" Amount="LKR3500"/>
                        <air:TaxInfo Category="IN" Amount="LKR774"/>
                        <air:TaxInfo Category="WO" Amount="LKR329"/>
                        <air:FareCalc>CMB AI X/MAA AI BLR257.41AI BOM34.56NUC291.97END ROE138.30</air:FareCalc>
                        <air:PassengerType Code="ADT" Age="40"/>
                        <air:ChangePenalty>
                            <air:Amount>LKR2200.0</air:Amount>
                        </air:ChangePenalty>
                        <air:CancelPenalty>
                            <air:Amount>LKR3000.0</air:Amount>
                        </air:CancelPenalty>
                        <air:FlightOptionsList>
                            <air:FlightOption LegRef="Yy/n9UuxR+uMskWhMvyx2A==" Destination="BOM" Origin="CMB">
                                <air:Option Key="0PsA/un6TTO8gGOhatiplQ==" TravelTime="P1DT0H55M0S">
                                    <air:BookingInfo BookingCode="W" CabinClass="Economy" FareInfoRef="N8f9bATgSJegYdLTuxxbEQ==" SegmentRef="5prwP64xRJSHRE+YUOVOpA=="/>
                                    <air:BookingInfo BookingCode="V" CabinClass="Economy" FareInfoRef="N8f9bATgSJegYdLTuxxbEQ==" SegmentRef="h8sva1J+TE6pBqF8lJzZqQ=="/>
                                    <air:BookingInfo BookingCode="U" CabinClass="Economy" FareInfoRef="t1dugF9jRj+nAxGAgUNE+g==" SegmentRef="K6ewDLHXS7ialSD9/l+mSg=="/>
                                    <air:Connection SegmentIndex="0"/>
                                    <air:Connection SegmentIndex="1"/>
                                </air:Option>
                            </air:FlightOption>
                        </air:FlightOptionsList>
                    </air:AirPricingInfo>
                </air:AirPricePoint>
                <air:AirPricePoint Key="6UAtYQmqRNqsIweVLUOZzw==" TotalPrice="LKR61170" BasePrice="LKR57000" ApproximateTotalPrice="LKR61170" ApproximateBasePrice="LKR57000" Taxes="LKR4170" ApproximateTaxes="LKR4170" CompleteItinerary="true">
                    <air:AirPricingInfo Key="HAIXyEDvRsyYbXQA5RXhEw==" TotalPrice="LKR61170" BasePrice="LKR57000" ApproximateTotalPrice="LKR61170" ApproximateBasePrice="LKR57000" Taxes="LKR4170" LatestTicketingTime="2015-12-30T23:59:00.000+00:00" PricingMethod="Unknown" Refundable="true" ETicketability="Yes" PlatingCarrier="UL" ProviderCode="1P">
                        <air:FareInfoRef Key="GDWwSZcqRIy4w4KZpuWqPA=="/>
                        <air:FareInfoRef Key="aTdXioKVRAOB8ZfPQPqBQw=="/>
                        <air:TaxInfo Category="LK" Amount="LKR3500"/>
                        <air:TaxInfo Category="YQ" Amount="LKR670"/>
                        <air:FareCalc>CMB UL MAA70.86 9W BOM Q20.00 321.07NUC411.93END ROE138.30</air:FareCalc>
                        <air:PassengerType Code="ADT" Age="40"/>
                        <air:ChangePenalty>
                            <air:Amount>LKR4500.0</air:Amount>
                        </air:ChangePenalty>
                        <air:FlightOptionsList>
                            <air:FlightOption LegRef="Yy/n9UuxR+uMskWhMvyx2A==" Destination="BOM" Origin="CMB">
                                <air:Option Key="c7GiD9yzQjudqzHXt4D+dA==" TravelTime="P0DT5H55M0S">
                                    <air:BookingInfo BookingCode="Q" CabinClass="Economy" FareInfoRef="GDWwSZcqRIy4w4KZpuWqPA==" SegmentRef="f90MbwBQSqWb59Ilu9sVlA=="/>
                                    <air:BookingInfo BookingCode="Y" CabinClass="Economy" FareInfoRef="aTdXioKVRAOB8ZfPQPqBQw==" SegmentRef="+nnddfIcQbS2G7jSW1lxLA=="/>
                                    <air:Connection SegmentIndex="0"/>
                                </air:Option>
                            </air:FlightOption>
                        </air:FlightOptionsList>
                    </air:AirPricingInfo>
                </air:AirPricePoint>
                <air:AirPricePoint Key="/8mL/DEPSNSSgueSWIub0w==" TotalPrice="LKR61170" BasePrice="LKR57000" ApproximateTotalPrice="LKR61170" ApproximateBasePrice="LKR57000" Taxes="LKR4170" ApproximateTaxes="LKR4170" CompleteItinerary="true">
                    <air:AirPricingInfo Key="Z9VZs8JBSfGwoGQ1gj8kUg==" TotalPrice="LKR61170" BasePrice="LKR57000" ApproximateTotalPrice="LKR61170" ApproximateBasePrice="LKR57000" Taxes="LKR4170" LatestTicketingTime="2015-12-30T23:59:00.000+00:00" PricingMethod="Unknown" Refundable="true" ETicketability="Yes" PlatingCarrier="UL" ProviderCode="1P">
                        <air:FareInfoRef Key="GDWwSZcqRIy4w4KZpuWqPA=="/>
                        <air:FareInfoRef Key="aTdXioKVRAOB8ZfPQPqBQw=="/>
                        <air:TaxInfo Category="LK" Amount="LKR3500"/>
                        <air:TaxInfo Category="YQ" Amount="LKR670"/>
                        <air:FareCalc>CMB UL MAA70.86 9W BOM Q20.00 321.07NUC411.93END ROE138.30</air:FareCalc>
                        <air:PassengerType Code="ADT" Age="40"/>
                        <air:ChangePenalty>
                            <air:Amount>LKR4500.0</air:Amount>
                        </air:ChangePenalty>
                        <air:FlightOptionsList>
                            <air:FlightOption LegRef="Yy/n9UuxR+uMskWhMvyx2A==" Destination="BOM" Origin="CMB">
                                <air:Option Key="muJg+t5HSlO4/COsct/vBg==" TravelTime="P0DT9H55M0S">
                                    <air:BookingInfo BookingCode="Q" CabinClass="Economy" FareInfoRef="GDWwSZcqRIy4w4KZpuWqPA==" SegmentRef="66jMUncSQHysn0acgiu4zw=="/>
                                    <air:BookingInfo BookingCode="Y" CabinClass="Economy" FareInfoRef="aTdXioKVRAOB8ZfPQPqBQw==" SegmentRef="hwqO5C39TkuzI8J81zb9uw=="/>
                                    <air:Connection SegmentIndex="0"/>
                                </air:Option>
                            </air:FlightOption>
                        </air:FlightOptionsList>
                    </air:AirPricingInfo>
                </air:AirPricePoint>
                <air:AirPricePoint Key="V2Ge/rFvQT2vYW0MtKh5Eg==" TotalPrice="LKR63873" BasePrice="LKR58600" ApproximateTotalPrice="LKR63873" ApproximateBasePrice="LKR58600" Taxes="LKR5273" ApproximateTaxes="LKR5273" CompleteItinerary="true">
                    <air:AirPricingInfo Key="oosSn1QhRfGEsb1Cndvjqw==" TotalPrice="LKR63873" BasePrice="LKR58600" ApproximateTotalPrice="LKR63873" ApproximateBasePrice="LKR58600" Taxes="LKR5273" LatestTicketingTime="2015-12-30T23:59:00.000+00:00" PricingMethod="Unknown" Refundable="true" ETicketability="Yes" PlatingCarrier="UL" ProviderCode="1P">
                        <air:FareInfoRef Key="ylmLppvLQxS1gVu6YrSmXQ=="/>
                        <air:FareInfoRef Key="n3Sh9LBgSkqTQ8/T3DjhCg=="/>
                        <air:TaxInfo Category="LK" Amount="LKR3500"/>
                        <air:TaxInfo Category="IN" Amount="LKR774"/>
                        <air:TaxInfo Category="WO" Amount="LKR329"/>
                        <air:TaxInfo Category="YQ" Amount="LKR670"/>
                        <air:FareCalc>CMB UL BLR108.45 9W BOM Q20.00 294.55NUC423.00END ROE138.30</air:FareCalc>
                        <air:PassengerType Code="ADT" Age="40"/>
                        <air:ChangePenalty>
                            <air:Amount>LKR2300.0</air:Amount>
                        </air:ChangePenalty>
                        <air:CancelPenalty>
                            <air:Amount>LKR3400.0</air:Amount>
                        </air:CancelPenalty>
                        <air:FlightOptionsList>
                            <air:FlightOption LegRef="Yy/n9UuxR+uMskWhMvyx2A==" Destination="BOM" Origin="CMB">
                                <air:Option Key="EII/9g9qTrCxkOmr2EA58A==" TravelTime="P0DT6H10M0S">
                                    <air:BookingInfo BookingCode="R" CabinClass="Economy" FareInfoRef="ylmLppvLQxS1gVu6YrSmXQ==" SegmentRef="1OxefzSvRSua5Aa0FhLWJQ=="/>
                                    <air:BookingInfo BookingCode="Y" CabinClass="Economy" FareInfoRef="n3Sh9LBgSkqTQ8/T3DjhCg==" SegmentRef="t4OE0jfGQ3OXpZ/2fwT5zA=="/>
                                    <air:Connection SegmentIndex="0"/>
                                </air:Option>
                            </air:FlightOption>
                        </air:FlightOptionsList>
                    </air:AirPricingInfo>
                </air:AirPricePoint>
                <air:AirPricePoint Key="0C0S6vF7TS6NF07Iw2p6bw==" TotalPrice="LKR69274" BasePrice="LKR65700" ApproximateTotalPrice="LKR69274" ApproximateBasePrice="LKR65700" Taxes="LKR3574" ApproximateTaxes="LKR3574" CompleteItinerary="true">
                    <air:AirPricingInfo Key="cUNptiDnTe6YqIvAheyUuw==" TotalPrice="LKR69274" BasePrice="LKR65700" ApproximateTotalPrice="LKR69274" ApproximateBasePrice="LKR65700" Taxes="LKR3574" LatestTicketingTime="2015-12-30T23:59:00.000+00:00" PricingMethod="Unknown" Refundable="true" ETicketability="Yes" PlatingCarrier="QR" ProviderCode="1P">
                        <air:FareInfoRef Key="1zPnV1WpTOOSCyJd0E4T2A=="/>
                        <air:TaxInfo Category="LK" Amount="LKR3500"/>
                        <air:TaxInfo Category="PZ" Amount="LKR74"/>
                        <air:FareCalc>CMB QR X/DOH QR BOM475.05NUC475.05END ROE138.30</air:FareCalc>
                        <air:PassengerType Code="ADT" Age="40"/>
                        <air:ChangePenalty>
                            <air:Amount>LKR4400.0</air:Amount>
                        </air:ChangePenalty>
                        <air:CancelPenalty>
                            <air:Amount>LKR5200.0</air:Amount>
                        </air:CancelPenalty>
                        <air:FlightOptionsList>
                            <air:FlightOption LegRef="Yy/n9UuxR+uMskWhMvyx2A==" Destination="BOM" Origin="CMB">
                                <air:Option Key="cHnL+xTIRcSukNm7TpKq7A==" TravelTime="P0DT16H35M0S">
                                    <air:BookingInfo BookingCode="N" CabinClass="Economy" FareInfoRef="1zPnV1WpTOOSCyJd0E4T2A==" SegmentRef="PLHLO28JSTiIkZ70ezTAjA=="/>
                                    <air:BookingInfo BookingCode="N" CabinClass="Economy" FareInfoRef="1zPnV1WpTOOSCyJd0E4T2A==" SegmentRef="nYcBG+HSRtWNpTFCt/2heg=="/>
                                    <air:Connection SegmentIndex="0"/>
                                </air:Option>
                            </air:FlightOption>
                        </air:FlightOptionsList>
                    </air:AirPricingInfo>
                </air:AirPricePoint>
                <air:AirPricePoint Key="rctIrY7lQAiak2F9FeJ9hA==" TotalPrice="LKR80170" BasePrice="LKR76000" ApproximateTotalPrice="LKR80170" ApproximateBasePrice="LKR76000" Taxes="LKR4170" ApproximateTaxes="LKR4170" CompleteItinerary="true">
                    <air:AirPricingInfo Key="WvluLqfGRTyImCssiINq7A==" TotalPrice="LKR80170" BasePrice="LKR76000" ApproximateTotalPrice="LKR80170" ApproximateBasePrice="LKR76000" Taxes="LKR4170" LatestTicketingTime="2015-12-30T23:59:00.000+00:00" PricingMethod="Unknown" Refundable="true" ETicketability="Yes" PlatingCarrier="UL" ProviderCode="1P">
                        <air:FareInfoRef Key="w3Yn4NV2Rd+v4IT9UmpT3Q=="/>
                        <air:TaxInfo Category="LK" Amount="LKR3500"/>
                        <air:TaxInfo Category="YQ" Amount="LKR670"/>
                        <air:FareCalc>CMB UL X/MAA AI BOM M549.53NUC549.53END ROE138.30</air:FareCalc>
                        <air:PassengerType Code="ADT" Age="40"/>
                        <air:FlightOptionsList>
                            <air:FlightOption LegRef="Yy/n9UuxR+uMskWhMvyx2A==" Destination="BOM" Origin="CMB">
                                <air:Option Key="ghShy/oFST2m/FJ0diBzXg==" TravelTime="P0DT7H30M0S">
                                    <air:BookingInfo BookingCode="Q" CabinClass="Economy" FareInfoRef="w3Yn4NV2Rd+v4IT9UmpT3Q==" SegmentRef="xijIeFvuSr6K8DHHX8qk6w=="/>
                                    <air:BookingInfo BookingCode="Y" CabinClass="Economy" FareInfoRef="w3Yn4NV2Rd+v4IT9UmpT3Q==" SegmentRef="weDvdh5hS2CMyjkof6NMuQ=="/>
                                    <air:Connection SegmentIndex="0"/>
                                </air:Option>
                            </air:FlightOption>
                        </air:FlightOptionsList>
                    </air:AirPricingInfo>
                </air:AirPricePoint>
                <air:AirPricePoint Key="5OBj0w27SAuiaxKLyNI9Gg==" TotalPrice="LKR81010" BasePrice="LKR76000" ApproximateTotalPrice="LKR81010" ApproximateBasePrice="LKR76000" Taxes="LKR5010" ApproximateTaxes="LKR5010" CompleteItinerary="true">
                    <air:AirPricingInfo Key="tMOK/2qQQISMTi57ke2mdg==" TotalPrice="LKR81010" BasePrice="LKR76000" ApproximateTotalPrice="LKR81010" ApproximateBasePrice="LKR76000" Taxes="LKR5010" LatestTicketingTime="2015-12-30T23:59:00.000+00:00" PricingMethod="Unknown" Refundable="true" ETicketability="Yes" PlatingCarrier="UL" ProviderCode="1P">
                        <air:FareInfoRef Key="w3Yn4NV2Rd+v4IT9UmpT3Q=="/>
                        <air:TaxInfo Category="LK" Amount="LKR3500"/>
                        <air:TaxInfo Category="WO" Amount="LKR840"/>
                        <air:TaxInfo Category="YQ" Amount="LKR670"/>
                        <air:FareCalc>CMB UL X/COK AI BOM M549.53NUC549.53END ROE138.30</air:FareCalc>
                        <air:PassengerType Code="ADT" Age="40"/>
                        <air:FlightOptionsList>
                            <air:FlightOption LegRef="Yy/n9UuxR+uMskWhMvyx2A==" Destination="BOM" Origin="CMB">
                                <air:Option Key="p4hIAIIXSSKxv3REcANlrw==" TravelTime="P0DT9H40M0S">
                                    <air:BookingInfo BookingCode="Q" CabinClass="Economy" FareInfoRef="w3Yn4NV2Rd+v4IT9UmpT3Q==" SegmentRef="wXh2eq+3TYSnj08zmsbg9A=="/>
                                    <air:BookingInfo BookingCode="Y" CabinClass="Economy" FareInfoRef="w3Yn4NV2Rd+v4IT9UmpT3Q==" SegmentRef="FZ3EP+wCSAO/dTlGXxuirg=="/>
                                    <air:Connection SegmentIndex="0"/>
                                </air:Option>
                            </air:FlightOption>
                        </air:FlightOptionsList>
                    </air:AirPricingInfo>
                </air:AirPricePoint>
                <air:AirPricePoint Key="8nce/OqQSxy5BJPC2727Uw==" TotalPrice="LKR81273" BasePrice="LKR76000" ApproximateTotalPrice="LKR81273" ApproximateBasePrice="LKR76000" Taxes="LKR5273" ApproximateTaxes="LKR5273" CompleteItinerary="true">
                    <air:AirPricingInfo Key="6k1Tnr3zTiKFz4uqgZzcuw==" TotalPrice="LKR81273" BasePrice="LKR76000" ApproximateTotalPrice="LKR81273" ApproximateBasePrice="LKR76000" Taxes="LKR5273" LatestTicketingTime="2015-12-30T23:59:00.000+00:00" PricingMethod="Unknown" Refundable="true" ETicketability="Yes" PlatingCarrier="UL" ProviderCode="1P">
                        <air:FareInfoRef Key="w3Yn4NV2Rd+v4IT9UmpT3Q=="/>
                        <air:TaxInfo Category="LK" Amount="LKR3500"/>
                        <air:TaxInfo Category="IN" Amount="LKR774"/>
                        <air:TaxInfo Category="WO" Amount="LKR329"/>
                        <air:TaxInfo Category="YQ" Amount="LKR670"/>
                        <air:FareCalc>CMB UL X/BLR AI BOM M549.53NUC549.53END ROE138.30</air:FareCalc>
                        <air:PassengerType Code="ADT" Age="40"/>
                        <air:FlightOptionsList>
                            <air:FlightOption LegRef="Yy/n9UuxR+uMskWhMvyx2A==" Destination="BOM" Origin="CMB">
                                <air:Option Key="bjbTsEmyRLu1MAOsocREEQ==" TravelTime="P0DT7H5M0S">
                                    <air:BookingInfo BookingCode="Q" CabinClass="Economy" FareInfoRef="w3Yn4NV2Rd+v4IT9UmpT3Q==" SegmentRef="1OxefzSvRSua5Aa0FhLWJQ=="/>
                                    <air:BookingInfo BookingCode="Y" CabinClass="Economy" FareInfoRef="w3Yn4NV2Rd+v4IT9UmpT3Q==" SegmentRef="76aWqUpsTRyj2+iR6l/uHw=="/>
                                    <air:Connection SegmentIndex="0"/>
                                </air:Option>
                            </air:FlightOption>
                        </air:FlightOptionsList>
                    </air:AirPricingInfo>
                </air:AirPricePoint>
                <air:AirPricePoint Key="U981X9cRQaqNNJjRMxKylQ==" TotalPrice="LKR89252" BasePrice="LKR84700" ApproximateTotalPrice="LKR89252" ApproximateBasePrice="LKR84700" Taxes="LKR4552" ApproximateTaxes="LKR4552" CompleteItinerary="true">
                    <air:AirPricingInfo Key="lGilg1ZvQN2DyCeKDuoeOA==" TotalPrice="LKR89252" BasePrice="LKR84700" ApproximateTotalPrice="LKR89252" ApproximateBasePrice="LKR84700" Taxes="LKR4552" LatestTicketingTime="2015-12-30T23:59:00.000+00:00" PricingMethod="Unknown" Refundable="true" ETicketability="Yes" PlatingCarrier="UL" ProviderCode="1P">
                        <air:FareInfoRef Key="RwAUVJ8nRqqXhP3m4rAxGw=="/>
                        <air:FareInfoRef Key="0fI0BJ/1Rwe9lMP/VUIgQA=="/>
                        <air:TaxInfo Category="LK" Amount="LKR3500"/>
                        <air:TaxInfo Category="OM" Amount="LKR382"/>
                        <air:TaxInfo Category="YQ" Amount="LKR670"/>
                        <air:FareCalc>CMB UL MCT Q3.00 99.06AI BOM509.75NUC611.81END ROE138.30</air:FareCalc>
                        <air:PassengerType Code="ADT" Age="40"/>
                        <air:ChangePenalty>
                            <air:Amount>LKR3500.0</air:Amount>
                        </air:ChangePenalty>
                        <air:FlightOptionsList>
                            <air:FlightOption LegRef="Yy/n9UuxR+uMskWhMvyx2A==" Destination="BOM" Origin="CMB">
                                <air:Option Key="7ucMzDcOTL2NhOj/nfa7Gw==" TravelTime="P0DT9H40M0S">
                                    <air:BookingInfo BookingCode="Q" CabinClass="Economy" FareInfoRef="RwAUVJ8nRqqXhP3m4rAxGw==" SegmentRef="dNdj5yZ/QJeItY9bVAzorg=="/>
                                    <air:BookingInfo BookingCode="Y" CabinClass="Economy" FareInfoRef="0fI0BJ/1Rwe9lMP/VUIgQA==" SegmentRef="QrsjEjXJQUOZMuFsVAAo6Q=="/>
                                    <air:Connection SegmentIndex="0"/>
                                </air:Option>
                            </air:FlightOption>
                        </air:FlightOptionsList>
                    </air:AirPricingInfo>
                </air:AirPricePoint>
                <air:AirPricePoint Key="MplWjdJGQW61APzndMZe/g==" TotalPrice="LKR187360" BasePrice="LKR183700" ApproximateTotalPrice="LKR187360" ApproximateBasePrice="LKR183700" Taxes="LKR3660" ApproximateTaxes="LKR3660" CompleteItinerary="true">
                    <air:AirPricingInfo Key="DaQ68DpeQk6f4wbsLXQ5+Q==" TotalPrice="LKR187360" BasePrice="LKR183700" ApproximateTotalPrice="LKR187360" ApproximateBasePrice="LKR183700" Taxes="LKR3660" LatestTicketingTime="2015-12-30T23:59:00.000+00:00" PricingMethod="Unknown" Refundable="true" ETicketability="Yes" PlatingCarrier="EY" ProviderCode="1P">
                        <air:FareInfoRef Key="TGq7WcqxQ5OBvvHEm2JK8w=="/>
                        <air:FareInfoRef Key="GPvzaK9tTzqjKluo7iZb9w=="/>
                        <air:TaxInfo Category="LK" Amount="LKR3500"/>
                        <air:TaxInfo Category="YR" Amount="LKR160"/>
                        <air:FareCalc>CMB EY AUH Q52.00 344.54EY BOM931.12NUC1327.66END ROE138.30</air:FareCalc>
                        <air:PassengerType Code="ADT" Age="40"/>
                        <air:FlightOptionsList>
                            <air:FlightOption LegRef="Yy/n9UuxR+uMskWhMvyx2A==" Destination="BOM" Origin="CMB">
                                <air:Option Key="EoD7dY58QhK0wn25zbtdNQ==" TravelTime="P0DT11H15M0S">
                                    <air:BookingInfo BookingCode="Y" CabinClass="Economy" FareInfoRef="TGq7WcqxQ5OBvvHEm2JK8w==" SegmentRef="UNIWeyDWRTq9AZXzz/cZGg=="/>
                                    <air:BookingInfo BookingCode="Y" CabinClass="Economy" FareInfoRef="GPvzaK9tTzqjKluo7iZb9w==" SegmentRef="oVEcNwOeQ+qwN71iIo5Cgg=="/>
                                    <air:Connection SegmentIndex="0"/>
                                </air:Option>
                            </air:FlightOption>
                        </air:FlightOptionsList>
                    </air:AirPricingInfo>
                </air:AirPricePoint>
                <air:AirPricePoint Key="vuNdUdNEQOSop6KfQtwVgw==" TotalPrice="LKR229800" BasePrice="LKR226300" ApproximateTotalPrice="LKR229800" ApproximateBasePrice="LKR226300" Taxes="LKR3500" ApproximateTaxes="LKR3500" CompleteItinerary="true">
                    <air:AirPricingInfo Key="xJ2fKJ1/SAq/rW8/mfGHfw==" TotalPrice="LKR229800" BasePrice="LKR226300" ApproximateTotalPrice="LKR229800" ApproximateBasePrice="LKR226300" Taxes="LKR3500" LatestTicketingTime="2015-12-30T23:59:00.000+00:00" PricingMethod="Unknown" Refundable="true" ETicketability="Yes" PlatingCarrier="HM" ProviderCode="1P">
                        <air:FareInfoRef Key="ewtt09q1QUmecbwuxmRy4w=="/>
                        <air:FareInfoRef Key="9QFGnYmORCKxeN2iDiIXXA=="/>
                        <air:TaxInfo Category="LK" Amount="LKR3500"/>
                        <air:FareCalc>CMB HM AUH759.21HM BOM876.67NUC1635.88END ROE138.30</air:FareCalc>
                        <air:PassengerType Code="ADT" Age="40"/>
                        <air:FlightOptionsList>
                            <air:FlightOption LegRef="Yy/n9UuxR+uMskWhMvyx2A==" Destination="BOM" Origin="CMB">
                                <air:Option Key="zLadru14SJ2td+4iaU/W7w==" TravelTime="P0DT11H15M0S">
                                    <air:BookingInfo BookingCode="M" CabinClass="Economy" FareInfoRef="ewtt09q1QUmecbwuxmRy4w==" SegmentRef="D4lv+RGmRpGDZiTXOO2vnA=="/>
                                    <air:BookingInfo BookingCode="M" CabinClass="Economy" FareInfoRef="9QFGnYmORCKxeN2iDiIXXA==" SegmentRef="4G665u4dT7ezTYmxfQ4UbQ=="/>
                                    <air:Connection SegmentIndex="0"/>
                                </air:Option>
                            </air:FlightOption>
                        </air:FlightOptionsList>
                    </air:AirPricingInfo>
                </air:AirPricePoint>
                <air:AirPricePoint Key="1stIdtsbRFm2ng535dh+Sw==" TotalPrice="LKR347784" BasePrice="LKR312000" ApproximateTotalPrice="LKR347784" ApproximateBasePrice="LKR312000" Taxes="LKR35784" ApproximateTaxes="LKR35784" CompleteItinerary="true">
                    <air:AirPricingInfo Key="AZFoecwqSDCcqYxBGfH1/Q==" TotalPrice="LKR347784" BasePrice="LKR312000" ApproximateTotalPrice="LKR347784" ApproximateBasePrice="LKR312000" Taxes="LKR35784" LatestTicketingTime="2015-12-30T23:59:00.000+00:00" PricingMethod="Unknown" Refundable="true" ETicketability="Yes" PlatingCarrier="SQ" ProviderCode="1P">
                        <air:FareInfoRef Key="hGXrS/5oSeOKDKiyWDSOmg=="/>
                        <air:FareInfoRef Key="NwA4s1AlTyqIdeQd9cGzJw=="/>
                        <air:TaxInfo Category="LK" Amount="LKR3500"/>
                        <air:TaxInfo Category="OO" Amount="LKR315"/>
                        <air:TaxInfo Category="SG" Amount="LKR315"/>
                        <air:TaxInfo Category="YQ" Amount="LKR31654"/>
                        <air:FareCalc>CMB SQ SIN447.21SQ BOM1808.36NUC2255.57END ROE138.30</air:FareCalc>
                        <air:PassengerType Code="ADT" Age="40"/>
                        <air:FlightOptionsList>
                            <air:FlightOption LegRef="Yy/n9UuxR+uMskWhMvyx2A==" Destination="BOM" Origin="CMB">
                                <air:Option Key="T3NwRW8iSD2gRoCyOZM7fQ==" TravelTime="P0DT20H35M0S">
                                    <air:BookingInfo BookingCode="Y" CabinClass="Economy" FareInfoRef="hGXrS/5oSeOKDKiyWDSOmg==" SegmentRef="35NDQM4vSBimE3dhLngNpA=="/>
                                    <air:BookingInfo BookingCode="B" CabinClass="Economy" FareInfoRef="NwA4s1AlTyqIdeQd9cGzJw==" SegmentRef="DO38sICKQwCJpqLfnEXDRw=="/>
                                    <air:Connection SegmentIndex="0"/>
                                </air:Option>
                            </air:FlightOption>
                        </air:FlightOptionsList>
                    </air:AirPricingInfo>
                </air:AirPricePoint>
                <air:AirPricePoint Key="GmesljgsRgmUuI8eEDHS7w==" TotalPrice="LKR363392" BasePrice="LKR321600" ApproximateTotalPrice="LKR363392" ApproximateBasePrice="LKR321600" Taxes="LKR41792" ApproximateTaxes="LKR41792" CompleteItinerary="true">
                    <air:AirPricingInfo Key="MllQWewxTaqqDzVcCXgV2A==" TotalPrice="LKR363392" BasePrice="LKR321600" ApproximateTotalPrice="LKR363392" ApproximateBasePrice="LKR321600" Taxes="LKR41792" LatestTicketingTime="2015-12-30T23:59:00.000+00:00" PricingMethod="Unknown" Refundable="true" ETicketability="Yes" PlatingCarrier="QF" ProviderCode="1P">
                        <air:FareInfoRef Key="I+jbjgnpRoWcOtHxAPcaPg=="/>
                        <air:FareInfoRef Key="K39nDbT+TgiCfwtVEPU2xA=="/>
                        <air:TaxInfo Category="LK" Amount="LKR3500"/>
                        <air:TaxInfo Category="OO" Amount="LKR315"/>
                        <air:TaxInfo Category="SG" Amount="LKR315"/>
                        <air:TaxInfo Category="YQ" Amount="LKR37662"/>
                        <air:FareCalc>CMB QF SIN516.99QF BOM1808.35NUC2325.34END ROE138.30</air:FareCalc>
                        <air:PassengerType Code="ADT" Age="40"/>
                        <air:FlightOptionsList>
                            <air:FlightOption LegRef="Yy/n9UuxR+uMskWhMvyx2A==" Destination="BOM" Origin="CMB">
                                <air:Option Key="jvrtXBuaRySlcbB+TqMWzw==" TravelTime="P0DT12H30M0S">
                                    <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="I+jbjgnpRoWcOtHxAPcaPg==" SegmentRef="zGx0vkDWQG6UpfSZuNvWZQ=="/>
                                    <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="K39nDbT+TgiCfwtVEPU2xA==" SegmentRef="0y5sdL/gQOGTycsXY0LD6Q=="/>
                                    <air:Connection SegmentIndex="0"/>
                                </air:Option>
                            </air:FlightOption>
                        </air:FlightOptionsList>
                    </air:AirPricingInfo>
                </air:AirPricePoint>
            </air:AirPricePointList>
            <air:BrandList>
                <air:Brand Key="bqRsYnwfQiqsMdVpijA1+w==" BrandID="5705" Name="Economy Flexi" BrandedDetailsAvailable="true" Carrier="SQ">
                    <air:Title Type="External">Economy Flexi</air:Title>
                    <air:Title Type="Short">Flexi</air:Title>
                    <air:Text Type="Upsell">Enjoying the ability to change your flight dates for free, the option to cancel and refund your ticket and upgrade your flight with miles to Business Class when you book on Economy Flexi today.</air:Text>
                    <air:Text Type="MarketingAgent">Our Economy Class innovative design minimizes intrusion into your personal space even as the passenger in front reclines; while ergonomic styling of the seat further maximizes knee and legroom. It also features a headrest that can be adjusted as desired for greater support. An easily accessible seatback-mounted handset, personal and non-intrusive reading light and in-seat power supply, are just some of the many intelligent touches designed with you in mind. (Applicable to most aircraft types)</air:Text>
                    <air:Text Type="Strapline">Sit back and relax</air:Text>
                    <air:ImageLocation Type="Agent" ImageWidth="150" ImageHeight="150">https://merchandisingmanagement.travelport.com/documents/10431/13649/YCL.jpg</air:ImageLocation>
                </air:Brand>
            </air:BrandList>
        </air:LowFareSearchRsp>
    </SOAP:Body>
</SOAP:Envelope>';

        $xmlres = simplexml_load_string($response12);
        // print_r($xmlres);
        //die();
        $xmlres->registerXPathNamespace('soap', "http://schemas.xmlsoap.org/soap/envelope/");
        $xmlres->registerXPathNamespace('air', "http://www.travelport.com/schema/air_v33_0");

        $pricepointPath = '/soap:Envelope/soap:Body/air:LowFareSearchRsp/air:AirPricePointList/air:AirPricePoint/air:AirPricingInfo/air:FlightOptionsList/air:FlightOption/air:Option/@Key';
        $pricepointPath3 = '/soap:Envelope/soap:Body/air:LowFareSearchRsp/air:AirPricePointList/air:AirPricePoint/air:AirPricingInfo/air:FlightOptionsList/air:FlightOption/air:Option/@Key|/soap:Envelope/soap:Body/air:LowFareSearchRsp/air:AirPricePointList/air:AirPricePoint/air:AirPricingInfo/air:FlightOptionsList/air:FlightOption/air:Option/air:BookingInfo/@SegmentRef';
        $pricepointPath2 = '/soap:Envelope/soap:Body/air:LowFareSearchRsp/air:AirPricePointList/air:AirPricePoint/air:AirPricingInfo/air:FlightOptionsList/air:FlightOption/air:Option/air:BookingInfo/@SegmentRef';
        $airPricePoint = $xmlres->xpath($pricepointPath);
        
        
        
        
//        foreach ($airPricePoint as $k=>$v){
//            
//            var_dump($v);
//            
//            
//        }
                
        
        $xml = $airPricePoint[0];
        
   
                
        print_r($xmlFetch);
       // var_dump($xml);
        
      //  echo sizeof($airPricePoint);
        die();
        
        for ($i = 0; $i < sizeof($airPricePoint); $i++) {

            $airOptionarray = get_object_vars($airPricePoint[$i]);
            $airOptionKey = $airOptionarray["@attributes"]["Key"];
            echo $airOptionKey.'<br>';
            $pricepointPath4 = '/soap:Envelope/soap:Body/air:LowFareSearchRsp/air:AirPricePointList/air:AirPricePoint/air:AirPricingInfo/air:FlightOptionsList/air:FlightOption/air:Option[@Key="'.$airOptionKey.'"]/air:BookingInfo/@SegmentRef';
            $airPricePoint1 = $xmlres->xpath($pricepointPath4);

            for ($e = 0; $e < sizeof($airPricePoint1); $e++) {
                $airOptionSeg = get_object_vars($airPricePoint1[$e]);
                $sementRefKey = $airOptionSeg["@attributes"]["SegmentRef"];
                echo $sementRefKey;
                echo '<br>';
            }
             echo '<br>'; echo '<br>';
        }
    }

}
