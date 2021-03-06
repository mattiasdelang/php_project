<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Mattias
 * Date: 29/04/14
 * Time: 22:30
 * To change this template use File | Settings | File Templates.
 */
session_start();
include_once("classes/restaurant.class.php");
include_once("classes/tafel.class.php");
include_once("classes/db.class.php");

if(!isset($_SESSION["login"])){
    header("Location:login.php");
    exit;
}

$restaurantId = $_GET['id'];
$t = new Tafel;

$tafels = array();

$dbtafels = $t->getByRestaurantId($restaurantId);
while($tafel = $dbtafels->fetch_array()){
    $tafels[] = $tafel;
}


if (isset($_POST['aanpassen'])){
    foreach ($_POST['tafelnr'] as $index => $tafelnummer) {
        $tafelId = $_POST['tafelid'][$index];
        $aantalPlaatsen = $_POST['plaatsen'][$index];

        // uit array $tafels, de tafel met tafelnummer vinden
        $gevonden = false;
        $i = 0;

        $tafelgegevens = null;
        while(!$gevonden && $tafelgegevens = $tafels[$i]){
            $i++;
            if ($tafelgegevens['id'] == $tafelId) {
                $gevonden = true;
            }
        }

        if (!$gevonden) {

        }

        $tafel = new Tafel();
        $tafel->setId($tafelId);
        $tafel->setTafelnummer($tafelnummer);
        $tafel->setAantalPlaatsen($aantalPlaatsen);
        $tafel->setOwnerId($_SESSION['login']['id']);
        $tafel->setRestaurantId($restaurantId);
        $tafel->save();
    }
    header('Location: modify.php');
    exit;
}

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="css.css">
    <title>Tafels aanpassen</title>
</head>
<body>

<h1>Tafels aanpassen</h1>
<div id="tafels">
    <?php
    if(count($tafels) > 0):
    ?>
    <form action="" method="post">
        <?php
        foreach($tafels as $tafel):
        ?>
            <input type="hidden" name="tafelid[]" value="<?=$tafel['id']?>" />
            <input type="number" name="tafelnr[]" value="<?=$tafel['tafelnummer']?>" placeholder="Tafelnummer" required />
            <input type="number" name="plaatsen[]" value="<?=$tafel['plaatsen']?>" placeholder="Aantal plaatsen" required/>
            <a href="remove_tafel.php?id=<?=$tafel['id']?>">Verwijder</a>
            <br>
        <?php
        endforeach;
        ?>
        <input type="submit" name="aanpassen" value="Aanpassen">
    </form>
    <?php
    else:
    ?>
        Er zijn geen tafels gevonden.
    <?php
    endif;
    ?>
</div>
</body>
</html>