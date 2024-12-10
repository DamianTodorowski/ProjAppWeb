<?php
session_start();
require("../php/cfg.php");
function FormularzLogowania()
{
    $wynik = '
    <div class="logowanie">
        <h1 class="heading">Panel CMS:</h1>
        <div class="logowanie">
            <form method="post" name="LoginForm" enctype="multipart/form-data" action="' . htmlspecialchars($_SERVER['REQUEST_URI']) . '">
                <table class="logowanie">
                    <tr>
                        <td class="log4_t">[email]</td>
                        <td><input type="text" name="login_email" class="logowanie" /></td>
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
                        <td><input type="submit" name="skip_login" class="logowanie" value="Pomiń logowanie" /></td>
                    </tr>
                </table>
            </form>
        </div>
    </div>
    ';

    return $wynik;
}

// Sprawdzanie czy użytkownik chce pominąć logowanie
if (isset($_POST['skip_login'])) {
    $_SESSION['is_logged_in'] = true;
    header("Location: index.php?idp=calc"); // Przekierowanie na stronę główną po pominięciu logowania
    exit; // Przerywamy dalsze wykonanie
}

// Jeśli formularz logowania został wysłany
if (isset($_POST['x1_submit'])) {
    $email = $_POST['login_email'] ?? '';
    $password = $_POST['login_pass'] ?? '';
    if ($email === $login && $password === $pass) {
        $_SESSION['is_logged_in'] = true;
        $admin = TRUE;
    } else {
        echo 'Błędny login lub hasło.';
        exit; 
    }
}

// Jeśli nie ma sesji z logowaniem i nie jest pominięte logowanie
if (!isset($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] !== true) {
    echo FormularzLogowania();
    exit; // Przerywamy dalsze wykonanie, jeśli użytkownik nie jest zalogowany
}
function ListaPodstron() {
    global $link;

    // Zapytanie do bazy danych, aby pobrać wszystkie podstrony
    $query = "SELECT id, page_title FROM page_list"; 
    $result = mysqli_query($link, $query);

    if ($result) {
        echo '<table border="1" cellpadding="5">';
        echo '<tr><th>ID</th><th>Tytuł</th><th>Opcje</th></tr>';
        while ($row = mysqli_fetch_assoc($result)) {
            echo '<tr>';
            echo '<td>' . $row['id'] . '</td>';
            echo '<td>' . $row['page_title'] . '</td>';
            echo '<td>
                    <form method="post" action="">
                        <input type="hidden" name="action" value="edit">
                        <input type="hidden" name="id" value="' . $row['id'] . '">
                        <input type="submit" value="Edytuj" />
                    </form>
                    <form method="post" action="">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="id" value="' . $row['id'] . '">
                        <input type="submit" value="Usuń" onclick="return confirm(\'Czy na pewno chcesz usunąć tę podstronę?\')" />
                    </form>
                  </td>';
            echo '</tr>';
        }
        echo '</table>';
        echo '<hr>';
    } else {
        echo 'Wystąpił błąd podczas pobierania danych z bazy.';
    }

    #edycja
    if (isset($_POST['action']) && $_POST['action'] == 'edit' && isset($_POST['id'])) {
        $id = $_POST['id'];

        // Pobranie danych podstrony z bazy
        $query = "SELECT * FROM page_list WHERE id = $id LIMIT 1";
        $result = mysqli_query($link, $query);
        
        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);

            echo '<h3>Edytuj Podstronę</h3>';
            echo '<form method="post" action="">
                    Tytuł: <input type="text" name="page_title" value="' . $row['page_title'] . '" required /><br>
                    Treść: <textarea name="content" required>' . $row['page_content'] . '</textarea><br>
                    Aktywna: <input type="checkbox" name="active" ' . ($row['status'] == 1 ? 'checked' : '') . ' /><br>
                    <input type="hidden" name="action" value="update" />
                    <input type="hidden" name="id" value="' . $row['id'] . '" />
                    <input type="submit" value="Zapisz" />
                  </form>';
        } else {
            echo "Nie znaleziono podstrony o tym ID.";
        }
    }
#aktualizacja
    if (isset($_POST['action']) && $_POST['action'] == 'update' && isset($_POST['id'])) {
        $id = $_POST['id'];
        $page_title = $_POST['page_title'];
        $content = $_POST['content'];
        $active = isset($_POST['active']) ? 1 : 0;

        $query = "UPDATE page_list SET page_title = '$page_title', page_content = '$content', status = $active WHERE id = $id LIMIT 1";
        
        if (mysqli_query($link, $query)) {
            echo "Podstrona została zaktualizowana!";
        } else {
            echo "Błąd aktualizacji: " . mysqli_error($link);
        }
    }

#usuniecie
    if (isset($_POST['action']) && $_POST['action'] == 'delete' && isset($_POST['id'])) {
        $id = $_POST['id'];

        // Usunięcie podstrony z bazy danych
        $query = "DELETE FROM page_list WHERE id = $id LIMIT 1";
        
        if (mysqli_query($link, $query)) {
            echo "Podstrona została usunięta!";
        } else {
            echo "Błąd usuwania podstrony: " . mysqli_error($link);
        }
    }
}
echo '<br><br><h2>Witaj w panelu administracyjnym!</h2>';
ListaPodstron();

function DodajNowaPodstrone() {
    global $link;

    if (isset($_POST['action']) && $_POST['action'] == 'add') {
        $page_title = $_POST['page_title'];
        $content = $_POST['content'];
        $active = isset($_POST['active']) ? 1 : 0;

        $query = "INSERT INTO page_list (page_title, page_content, status) 
                  VALUES ('$page_title', '$content', $active) LIMIT 1";
        if (mysqli_query($link, $query)) {
            echo "Nowa podstrona została dodana!";
        } else {
            echo "Błąd dodawania podstrony: " . mysqli_error($link);
        }
    }

    echo '<h3>Dodaj Nową Podstronę</h3>';
    echo '<form method="post" action="">
            Tytuł: <input type="text" name="page_title" value="" required /><br>
            Treść: <textarea name="content" required></textarea><br>
            Aktywna: <input type="checkbox" name="active"><br>
            <input type="hidden" name="action" value="add" />
            <input type="submit" value="Dodaj Podstronę" />
          </form>';
}
DodajNowaPodstrone()
?>