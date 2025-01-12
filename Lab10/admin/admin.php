<?php

ob_start();
session_start();
require("../php/cfg.php");
require("../php/contact.php");

// -----------------------------------------------------------------------------
// Funkcja sprawdzająca, czy użytkownik jest zalogowany
// ----------------------------------------------------------------------------- 
if (!isset($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] !== true) {
    header("Location: login.php");
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
// Wywołanie funkcji
// ----------------------------------------------------------------------------- 
ZarzadzajPodstronami();
ZarzadzajKategorie();

?>
