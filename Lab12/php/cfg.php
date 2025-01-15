<?php
// Plik konfiguracyjny do nawiązywania połączenia z bazą danych

// Parametry połączenia z bazą danych
$dbhost = 'localhost'; // Adres hosta bazy danych
$dbuser = 'root';      // Nazwa użytkownika bazy danych
$dbpass = '';          // Hasło do bazy danych
$dbname = 'moja_strona'; // Nazwa bazy danych

// Dane logowania do admina
$login = 'admin'; 
$pass = 'admin'; 

// Zmienna globalna dla połączenia
global $link;

// Nawiązanie połączenia z bazą danych
$link = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

// Sprawdzenie, czy połączenie zostało nawiązane
if (!$link) {
    die('<b>Przerwane połączenie:</b> ' . mysqli_connect_error()); // Wyświetlenie błędu w przypadku problemu
}

// Komunikat potwierdzający połączenie
echo '<b>Połączono z bazą danych.</b>';
?>
