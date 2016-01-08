

<html>
    <head><title></title></head>
    <body>
        <?php
        foreach ($value as $key => $arrayValue) {


            //  var_dump($arrayValue["flightOptionDetails"]);
            //echo '<br>';

            $flightOptionDetails = $arrayValue["flightOptionDetails"];
            ?>
                 <form action ="price" method="post">
                      Option<br>
                     <br>
                <?php
            foreach ($flightOptionDetails as $key2 => $flightOptionDetailsvalue) {
//                var_dump($flightOptionDetailsvalue["bookingDetails"]);
//                echo '------------------<br>';
//                var_dump($flightOptionDetailsvalue["segmentDetails"]);
//                echo '----------------------<br>';
//                echo $flightOptionDetailsvalue["bookingDetails"]["CabinClass"];
//                echo '----------------------<br>';
                ?>

                    Origin:<input type="text" name="Origin<?= $key2?>" value="<?= $flightOptionDetailsvalue["segmentDetails"]["Origin"]; ?>">-------------to ----Destination:<input type="text" name="Destination" value="<?= $flightOptionDetailsvalue["segmentDetails"]["Destination"]; ?>">
                    <label name='Origin'>Origin:<?= $flightOptionDetailsvalue["segmentDetails"]["Origin"]; ?></label>
                    <br>
                   BookingCode: <input type="text" name="bookingCode<?= $key2?>" value="<?= $flightOptionDetailsvalue["bookingDetails"]["BookingCode"]; ?>">
                    CabinClass<input type="text" name="cabinclass<?= $key2?>" value="<?= $flightOptionDetailsvalue["bookingDetails"]["CabinClass"]; ?>">
                  SegmentRef  <input type="text" name="SegmentRef<?= $key2?>" value="<?= $flightOptionDetailsvalue["bookingDetails"]["SegmentRef"]; ?>">
                  <br>
                   Carrier :<input type="text" name="Carrier<?= $key2?>" value="<?= $flightOptionDetailsvalue["segmentDetails"]["Carrier"]; ?>">
                   FlightNumber :<input type="text" name="FlightNumber<?= $key2?>" value="<?= $flightOptionDetailsvalue["segmentDetails"]["FlightNumber"]; ?>">
                    
                    
                    Equipment: <input type="text" name="Equipment<?= $key2?>" value="<?= $flightOptionDetailsvalue["segmentDetails"]["Equipment"]; ?>">
                   FlightTime: <input type="text" name="FlightTime<?= $key2?>" value="<?= $flightOptionDetailsvalue["segmentDetails"]["FlightTime"]; ?>">

                   
                   
               
        <hr>
                <?php
            }

            //echo '<br>';
            //echo '<br>';
            ?>
         <input type="submit" value="Select">    
                </form>  <hr> <?php
        }
        ?> 
    </body>
</html>

