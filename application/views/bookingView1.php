

<html>
    <head><title></title></head>
    <body>
       <?php

for ($x = 0; $x < sizeof($prices); $x++) {

    echo $origin . '  to  ' . $destination;
    echo '<br>';
    //var_dump($prices);
    echo $prices[$x]["TotalPrice"];
    ?>
<form action ="response1option" method="post">
    <label><?= $prices[$x]["PlatingCarrier"];?></label>
    
        <input type="hidden" name="key" value="<?= $prices[$x]["Key"] ;?>">
        <input type="submit" value="Select">    
    </form>
    <?php
    echo '<hr>';
}

?> 
    </body>
</html>

