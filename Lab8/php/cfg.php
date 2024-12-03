<?php
$dbhost = 'localhost'; 
$dbuser = 'root';      
$dbpass = '';          
$dbname = 'moja_strona'; 
global $link;

$link = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);


if (!$link) {
    die('<b>Przerwane połączenie:</b> ' . mysqli_connect_error());
}


echo '<b>Połączono z bazą danych.</b>';
?>
