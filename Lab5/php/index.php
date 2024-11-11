<?php
//errory
error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);

// ładowanie z $_GET
$strona = '../html/glowna.html'; 

if ($_GET['idp'] == 'ufc') $strona = '../html/UFC.html';
if ($_GET['idp'] == 'divisions') $strona = '../html/divisions.html';
if ($_GET['idp'] == 'pereira') $strona = '../html/pereira.html';
if ($_GET['idp'] == 'poland') $strona = '../html/poland.html';
if ($_GET['idp'] == 'filmy') $strona = '../html/filmy.html';

if (!file_exists($strona)) {
    $strona = '../html/404.html'; 
}

?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Language" content="pl" />
    <meta name="Author" content="Damian Todorowski" />
    <title>Moje Hobby: MMA</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">
    <link rel="shortcut icon" href="../favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="../css/style.css"> 
    <script src="../js/script.js" type="text/javascript"></script> 
    <script src="../js/timedate.js" type="text/javascript"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body onload="startClock()">
    <div id="wrapper"> 
        <header>
            <img src="../jpg/Octagon.jpg" alt="octagon header"/>
        </header>
        <nav>
            <a class="menu" href="index.php?idp=glowna">O MMA</a>
            <a class="menu" href="index.php?idp=ufc">Ulubiona federacja</a>
            <a class="menu" href="index.php?idp=divisions">Dywizje</a>
            <a class="menu" href="index.php?idp=pereira">Ulubiony zawodnik</a>
            <a class="menu" href="index.php?idp=poland">Polacy w UFC</a>
            <a class="menu" href="index.php?idp=filmy">Filmy<a>
        </nav>
        <section>
            <!-- PHP include-->
            <?php include($strona); ?>
            <h2>Zmiana koloru tła</h2>
                <button onclick="changeBackground('#808080')">Zmień tło na szare</button>
                <button onclick="changeBackground('#FF0000')">Zmień tło na czerwone</button>
        </section>
        <footer>
            <?php
            $nr_indeksu = '169372';
            $nrGrupy = '1';
            echo 'Autor: Damian Todorowski   ' . $nr_indeksu . '   grupa: ' . $nrGrupy . '<br /><br />';
            ?>

            
        </footer>
    </div>
    <script>
    $(document).ready(function() {
        $("a.menu").on({
            mouseover: function() {
                $(this).animate({ width: 300 }, 800);
            },
            mouseout: function() {
                $(this).animate({ width: 200 }, 800);
            }
        });

        $("#animacjaTestowa1").on("click", function() {
            $(this).animate({ width: "500px", opacity: 0.4, fontSize: "3em", borderWidth: "10px" }, 1500);
        });

        $("#mataleoImage").on("click", function() {
            if (!$(this).is(":animated")) {
                $(this).animate({ width: "+=50", height: "+=10", opacity: "+=0.1" }, 1000);
            }
        });
    });
    </script>
</body>
</html>
