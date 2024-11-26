<?php

$nr_indeksu = '169372';

$nrGrupy = '1';

echo 'Damian Todorowski '.$nr_indeksu.' grupa '.$nrGrupy.' <br /><br />';
echo '<h3>Zastosowanie metody include() <br /><br /><br /></h3>';

include('plik.php');

require_once('plik2.php');

echo '<h3>instrukcje warunkowe <br /><br /></h3>';

$liczba = 20;

if ($liczba < 5) {
    echo "Liczba jest mniejsza niż 5.<br />";
} elseif ($liczba == 10) {
    echo "Liczba jest równa 10.<br />";
} else {
    echo "Liczba jest większa niż 5 i nie jest równa 10.<br />";
}


echo '<h3>użycie switch <br /><br /></h3>';

$kolor = "czerwony";
switch ($kolor) {
    case "czerwony":
        echo "Wybrałeś kolor czerwony.<br />";
        break;
    case "zielony":
        echo "Wybrałeś kolor zielony.<br />";
        break;
    default:
        echo "Wybrałeś inny kolor.<br />";
}

echo '<h3>pętla while i for  <br /><br /></h3>';


//while
echo "Przykład pętli while:<br />";
$i = 1;
while ($i <= 5) {
    echo "To jest iteracja nr $i w pętli while.<br />";
    $i++;
}


echo '<br /><br />';

//for
for ($j = 1; $j <= 5; $j++) {
    echo "To jest iteracja nr $j w pętli for.<br />";
}

echo '<br /><br />';

echo '<h3>Typy zmiennych $_GET, $_POST, $_SESSION <br /><br /></h3>';

session_start(); //rozpoczęcie sesji do użycia zmiennej $_SESSION


//get
if (isset($_GET['imie'])) {
    echo "Wartość przekazana za pomocą \$_GET['imie']: " . htmlspecialchars($_GET['imie']) . "<br />";
} else {
    echo "Nie podano wartości.<br />";
}


//post
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['nazwisko'])) {
    echo "Wartość przekazana za pomocą \$_POST['nazwisko']: " . htmlspecialchars($_POST['nazwisko']) . "<br />";
} else {
    echo "Nie podano wartości \$_POST['nazwisko'].<br />";
}

//session
$_SESSION['login'] = 'DamianTodorowski';
echo "Wartość przechowywana w \$_SESSION['login']: " . $_SESSION['login'] . "<br />";


//Aby przetestować zmienne $_GET i $_POST, można użyć adresu URL z parametrami ?imie=TwojeImie, lub formularza z metodą POST w HTML, który prześle wartość nazwisko.

?>


?>