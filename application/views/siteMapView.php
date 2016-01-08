

<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Site map</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
        <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    </head>
    <body>

        <div class="container">

            <?php
            if (isset($siteMapDetails)) {


                foreach ($siteMapDetails as $key => $mainValue) {
                    ?>
                    <div class="col-md-4"> 
                        <i class="fa <?=$mainValue["main"]["class"]?>"></i> <?php echo $mainValue["main"]["title"]; ?>
                        <hr>
                        <?php
                        foreach ($mainValue["sub"] as $subValue) {

                            //foreach ($subValue as $key => $subInfo) { 
                            ?>

                            <a href="<?= $subValue['url']; ?>"><?= $subValue['title']; ?></a><br>
                            <?php
                            //}
                        }
                        ?>
                    </div>    
                    <?php
                }
            } else {
                echo 'Error 404';
            }
            ?>


        </div>
    </div>

</body>
</html>

