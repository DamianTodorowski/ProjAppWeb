<?php

ob_start();
session_start();
require("../php/cfg.php");
require("../php/contact.php");


//------------------------------------------------
// Panel logowania
//------------------------------------------------
function FormularzLogowania()
{
    $wynik = '
    <div class="logowanie">
        <h1 class="heading">Panel CMS:</h1>
        <div class="logowanie">
            <form method="post" name="LoginForm" enctype="multipart/form-data" action="' . htmlspecialchars($_SERVER['REQUEST_URI']) . '">
                <table class="logowanie">
                    <tr>
                        <td class="log4_t">[login]</td>
                        <td><input type="text" name="login_name" class="logowanie" /></td>
                    </tr>
                    <tr>
                        <td class="log4_t">[haslo]</td>
                        <td><input type="password" name="login_pass" class="logowanie" /></td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td><input type="submit" name="x1_submit" class="logowanie" value="zaloguj" /></td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td><button type="submit" name="przypomnij_haslo" class="logowanie">Przypomnij hasło</button></td>
                    </tr>
                </table>
            </form>
        </div>
    </div>
    ';

    return $wynik;
}

function FormularzPrzypomnieniaHasla()
{
    $wynik = '
    <div class="logowanie">
        <h1 class="heading">Przypomnij hasło:</h1>
        <form method="post" name="PasswordReminderForm" enctype="multipart/form-data" action="' . htmlspecialchars($_SERVER['REQUEST_URI']) . '">
            <table class="logowanie">
                <tr>
                    <td class="log4_t">[email]</td>
                    <td><input type="email" name="email" class="logowanie" required /></td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td><input type="submit" name="wyslij_przypomnienie" class="logowanie" value="Wyślij przypomnienie" /></td>
                </tr>
            </table>
        </form>
    </div>
    ';

    return $wynik;
}

// Obsługa przypomnienia hasła
if (isset($_POST['przypomnij_haslo'])) {
    echo FormularzPrzypomnieniaHasla();
    exit;
}

if (isset($_POST['wyslij_przypomnienie'])) {
    $email = $_POST['email'] ?? '';
    if (!empty($email)) {
        echo PrzypomnijHaslo($email); // Funkcja z contact.php wysyła przypomnienie hasła
    } else {
        echo "<p>Podaj poprawny adres e-mail.</p>";
    }
    exit;
}

// Obsługa logowania
if (isset($_POST['x1_submit'])) {
    $login = $_POST['login_name'] ?? '';
    $password = $_POST['login_pass'] ?? '';
    if ($login === $login && $password === $pass) {  
        $_SESSION['is_logged_in'] = true;
        $admin = TRUE;
    } else {
        echo '<p>Błędny login lub hasło.</p>';
        exit;
    }
}

// Sprawdzanie sesji logowania
if (!isset($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] !== true) {
    echo FormularzLogowania();
    exit;
}
// -----------------------------------------------------------------------------
// Funkcja do zarządzania podstronami
// ----------------------------------------------------------------------------- 
function ZarzadzajPodstronami() {
    global $link;

    echo '<h2>Panel zarządzania podstronami</h2>';

    // Edycja podstrony
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'edit' && isset($_POST['id'])) {
        $id = $_POST['id'];
        $query = "SELECT * FROM page_list WHERE id = $id LIMIT 1";
        $result = mysqli_query($link, $query);
        $row = mysqli_fetch_assoc($result);

        echo '<h3>Edytuj Podstronę</h3>';
        echo '<form method="post">
                Tytuł: <input type="text" name="page_title" value="' . htmlspecialchars($row['page_title']) . '" required /><br>
                Treść: <textarea name="content" required>' . htmlspecialchars($row['page_content']) . '</textarea><br>
                Aktywna: <input type="checkbox" name="active" ' . ($row['status'] == 1 ? 'checked' : '') . ' /><br>
                <input type="hidden" name="action" value="update" />
                <input type="hidden" name="id" value="' . $row['id'] . '" />
                <input type="submit" value="Zapisz zmiany" />
              </form>';
        return; // Zatrzymaj dalsze renderowanie, aby uniknąć ponownego wyświetlania tabeli
    }

    // Aktualizacja podstrony
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update' && isset($_POST['id'])) {
        $id = $_POST['id'];
        $title = $_POST['page_title'];
        $content = $_POST['content'];
        $active = isset($_POST['active']) ? 1 : 0;

        $query = "UPDATE page_list SET page_title = '$title', page_content = '$content', status = $active WHERE id = $id LIMIT 1";
        if (mysqli_query($link, $query)) {
            echo "Podstrona została zaktualizowana!";
            header("Location: " . htmlspecialchars($_SERVER['PHP_SELF']));
            exit;
        } else {
            echo "Błąd podczas edycji podstrony: " . mysqli_error($link);
        }
    }

    // Usuwanie podstrony
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete' && isset($_POST['id'])) {
        $id = $_POST['id'];

        $query = "DELETE FROM page_list WHERE id = $id LIMIT 1";
        if (mysqli_query($link, $query)) {
            echo "Podstrona została usunięta!";
            header("Location: " . htmlspecialchars($_SERVER['PHP_SELF']));
            exit;
        } else {
            echo "Błąd podczas usuwania podstrony: " . mysqli_error($link);
        }
    }

    // Wyświetlanie listy podstron
    $result = mysqli_query($link, "SELECT * FROM page_list");
    echo '<table border="1">
            <tr>
                <th>Tytuł</th>
                <th>Aktywna</th>
                <th>Akcje</th>
            </tr>';
    while ($row = mysqli_fetch_assoc($result)) {
        echo '<tr>';
        echo '<td>' . htmlspecialchars($row['page_title']) . '</td>';
        echo '<td>' . ($row['status'] == 1 ? 'Tak' : 'Nie') . '</td>';
        echo '<td>
                <form method="post" style="display:inline;">
                    <input type="hidden" name="action" value="edit">
                    <input type="hidden" name="id" value="' . $row['id'] . '">
                    <button type="submit">Edytuj</button>
                </form>
                <form method="post" style="display:inline;">
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="id" value="' . $row['id'] . '">
                    <button type="submit">Usuń</button>
                </form>
              </td>';
        echo '</tr>';
    }
    echo '</table>';

    // Formularz dodawania nowej podstrony
    echo '<h3>Dodaj nową podstronę</h3>';
    echo '<form method="post">
            Tytuł: <input type="text" name="page_title" required><br>
            Treść: <textarea name="content" required></textarea><br>
            Aktywna: <input type="checkbox" name="active"><br>
            <button type="submit" name="dodaj_podstrone">Dodaj podstronę</button>
          </form>';

    // Dodawanie podstrony
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['dodaj_podstrone'])) {
        $title = mysqli_real_escape_string($link, $_POST['page_title']);
        $content = mysqli_real_escape_string($link, $_POST['content']);
        $active = isset($_POST['active']) ? 1 : 0;

        $query = "INSERT INTO page_list (page_title, page_content, status) VALUES ('$title', '$content', $active)";
        if (mysqli_query($link, $query)) {
            echo "Dodano nową podstronę!";
            header("Location: " . htmlspecialchars($_SERVER['PHP_SELF']));
            exit;
        } else {
            echo "Błąd podczas dodawania podstrony: " . mysqli_error($link);
        }
    }
}


// -----------------------------------------------------------------------------
// Funkcja do zarządzania kategoriami
// ----------------------------------------------------------------------------- 
function ZarzadzajKategorie() {
    global $link;

    echo '<h2>Zarządzaj kategoriami</h2>';

    // Dodawanie kategorii
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['dodaj_kategorie'])) {
        $nazwa = mysqli_real_escape_string($link, $_POST['nazwa']);
        $matka = intval($_POST['matka']);

        $query = "INSERT INTO categories (nazwa, matka) VALUES ('$nazwa', $matka)";
        if (mysqli_query($link, $query)) {
            echo "Dodano kategorię!";
            header("Location: " . htmlspecialchars($_SERVER['PHP_SELF']));
            exit;
        } else {
            echo "Błąd podczas dodawania kategorii: " . mysqli_error($link);
        }
    }

    // Edycja kategorii
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edytuj_kategorie'])) {
        $id = intval($_POST['id']);
        $nazwa = mysqli_real_escape_string($link, $_POST['nazwa']);
        $matka = intval($_POST['matka']);
        $new_id = intval($_POST['new_id']); // ID, które użytkownik chce ustawić

        if ($new_id != $id) {
            // Zmiana ID
            $query = "UPDATE categories SET id = $new_id, nazwa = '$nazwa', matka = $matka WHERE id = $id";
        } else {
            // Brak zmiany ID
            $query = "UPDATE categories SET nazwa = '$nazwa', matka = $matka WHERE id = $id";
        }

        if (mysqli_query($link, $query)) {
            echo "Kategoria zaktualizowana!";
            header("Location: " . htmlspecialchars($_SERVER['PHP_SELF']));
            exit;
        } else {
            echo "Błąd podczas edycji kategorii: " . mysqli_error($link);
        }
    }

    // Usuwanie kategorii
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['usun_kategorie'])) {
        $id = intval($_POST['id']);

        $query = "DELETE FROM categories WHERE id = $id";
        if (mysqli_query($link, $query)) {
            echo "Kategoria usunięta!";
            header("Location: " . htmlspecialchars($_SERVER['PHP_SELF']));
            exit;
        } else {
            echo "Błąd podczas usuwania kategorii: " . mysqli_error($link);
        }
    }

    // Wyświetlanie kategorii
    PokazKategorie();

    // Formularz dodawania kategorii
    echo '<h3>Dodaj nową kategorię</h3>';
    echo '<form method="post">
            Nazwa: <input type="text" name="nazwa" required><br>
            Matka: <input type="number" name="matka" value="0" required><br>
            <button type="submit" name="dodaj_kategorie">Dodaj kategorię</button>
          </form>';
}

// -----------------------------------------------------------------------------
// Funkcja do wyświetlania kategorii
// ----------------------------------------------------------------------------- 
function PokazKategorie($matka = 0, $poziom = 1) {
    global $link;

    $query = "SELECT * FROM categories WHERE matka = $matka";
    $result = mysqli_query($link, $query);

    while ($row = mysqli_fetch_assoc($result)) {
        echo str_repeat('--', $poziom) . htmlspecialchars($row['nazwa']) . ' | ID: ' . $row['id'] . ' | Matka: ' . $row['matka'] . ' ';
        echo '<form method="post" style="display:inline;">
                <input type="hidden" name="id" value="' . $row['id'] . '">
                <input type="hidden" name="nazwa" value="' . htmlspecialchars($row['nazwa']) . '">
                <input type="hidden" name="matka" value="' . $row['matka'] . '">
                <button type="submit" name="edytuj_kategorie_form">Edytuj</button>
                <button type="submit" name="usun_kategorie">Usuń</button>
              </form>';
        echo '<br>';
        PokazKategorie($row['id'], $poziom + 1);
    }
}

// -----------------------------------------------------------------------------
// Edytowanie kategorii (formularz edycji)
// ----------------------------------------------------------------------------- 
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edytuj_kategorie_form'])) {
    $id = intval($_POST['id']);
    $query = "SELECT * FROM categories WHERE id = $id LIMIT 1";
    $result = mysqli_query($link, $query);
    $row = mysqli_fetch_assoc($result);

    if ($row) {
        echo '<h3>Edytuj kategorię</h3>';
        echo '<form method="post">
                Nazwa: <input type="text" name="nazwa" value="' . htmlspecialchars($row['nazwa']) . '" required><br>
                Matka: <input type="number" name="matka" value="' . $row['matka'] . '" required><br>
                Nowe ID: <input type="number" name="new_id" value="' . $row['id'] . '" required><br>
                <input type="hidden" name="id" value="' . $row['id'] . '">
                <button type="submit" name="edytuj_kategorie">Zaktualizuj kategorię</button>
              </form>';
    } else {
        echo "Nie znaleziono kategorii do edycji.";
    }
}


// -----------------------------------------------------------------------------
// Funkcje do zarządzania produktami
// -----------------------------------------------------------------------------
function PokazProdukty() {
    global $link;

    $query = "SELECT * FROM produkty";
    $result = mysqli_query($link, $query);

    echo '<h2>Lista produktów</h2>';
    echo '<table border="1">
            <tr>
                <th>ID</th>
                <th>Tytuł</th>
                <th>Cena netto</th>
                <th>Ilość w magazynie</th>
                <th>VAT</th>
                <th>Data wygaśnięcia</th>
                <th>Zdjęcie</th>
                <th>Akcje</th>
            </tr>';
    while ($row = mysqli_fetch_assoc($result)) {
        echo '<tr>';
        echo '<td>' . $row['id'] . '</td>';
        echo '<td>' . htmlspecialchars($row['tytul']) . '</td>';
        echo '<td>' . $row['cena_netto'] . ' zł</td>';
        echo '<td>' . $row['ilosc_magazyn'] . '</td>';
        echo '<td>' . $row['podatek_vat'] . ' %</td>';
        echo '<td>' . ($row['data_wygasniecia'] ?: 'Brak') . '</td>';
        echo '<td>';
        if (!empty($row['zdjecie'])) {
            echo '<img src="data:image/jpeg;base64,' . base64_encode($row['zdjecie']) . '" width="100" height="100"/>';
        } else {
            echo 'Brak zdjęcia';
        }
        echo '</td>';
        echo '<td>
                <form method="post" style="display:inline;">
                    <input type="hidden" name="id" value="' . $row['id'] . '">
                    <button type="submit" name="edytuj_produkt_form">Edytuj</button>
                </form>
                <form method="post" style="display:inline;">
                    <input type="hidden" name="id" value="' . $row['id'] . '">
                    <button type="submit" name="usun_produkt">Usuń</button>
                </form>
              </td>';
        echo '</tr>';
    }
    echo '</table>';
    echo '<h3>Dodaj nowy produkt</h3>';
    echo '<form method="post" enctype="multipart/form-data">
            Tytuł: <input type="text" name="tytul" required><br>
            Opis: <textarea name="opis" required></textarea><br>
            Cena netto: <input type="number" step="0.01" name="cena_netto" required><br>
            Ilość w magazynie: <input type="number" name="ilosc_magazyn" required><br>
            VAT: <input type="number" step="0.01" name="vat" required><br>
            Data wygaśnięcia: <input type="date" name="data_wygasniecia"><br>
            Zdjęcie: <input type="file" name="zdjecie" accept="image/*"><br>
            <button type="submit" name="dodaj_produkt">Dodaj produkt</button>
          </form>';
}

function DodajProdukt() {
    global $link;

    $tytul = mysqli_real_escape_string($link, $_POST['tytul']);
    $opis = mysqli_real_escape_string($link, $_POST['opis']);
    $cena_netto = floatval($_POST['cena_netto']);
    $ilosc_magazyn = intval($_POST['ilosc_magazyn']);
    $vat = floatval($_POST['vat']);
    $data_wygasniecia = !empty($_POST['data_wygasniecia']) 
        ? mysqli_real_escape_string($link, $_POST['data_wygasniecia']) 
        : null;

    $zdjecie = null;
    if (isset($_FILES['zdjecie']) && $_FILES['zdjecie']['error'] == UPLOAD_ERR_OK) {
        $zdjecie = file_get_contents($_FILES['zdjecie']['tmp_name']);
    }

    $query = "INSERT INTO produkty (tytul, opis, cena_netto, podatek_vat, ilosc_magazyn, data_wygasniecia, zdjecie)
              VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($link, $query);
    mysqli_stmt_bind_param($stmt, 'ssddiss', $tytul, $opis, $cena_netto, $vat, $ilosc_magazyn, $data_wygasniecia, $zdjecie);

    if (mysqli_stmt_execute($stmt)) {
        echo "Dodano nowy produkt!";
        header("Location: " . htmlspecialchars($_SERVER['PHP_SELF']));
        exit;
    } else {
        echo "Błąd: " . mysqli_error($link);
    }
}

// Podobne poprawki w `EdytujProduktForm` i `EdytujProdukt`

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['dodaj_produkt'])) {
        DodajProdukt();
    } elseif (isset($_POST['edytuj_produkt'])) {
        EdytujProdukt();
    } elseif (isset($_POST['edytuj_produkt_form'])) {
        EdytujProduktForm();
    } elseif (isset($_POST['usun_produkt'])) {
        UsunProdukt();
    }
}



// -----------------------------------------------------------------------------
// Wywołanie funkcji
// -----------------------------------------------------------------------------
PokazProdukty();
ZarzadzajPodstronami();
ZarzadzajKategorie();

?>
