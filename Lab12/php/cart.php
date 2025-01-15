<?php
session_start();

// Inicjalizacja koszyka w sesji, jeśli jeszcze nie istnieje
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Funkcja dodawania produktu do koszyka
function DodajDoKoszyka($id, $tytul, $cena, $ilosc) {
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $id) {
            $item['ilosc'] += $ilosc; // Zwiększ ilość, jeśli produkt już jest w koszyku
            return;
        }
    }

    // Jeśli produktu nie ma w koszyku, dodaj go jako nowy element
    $_SESSION['cart'][] = [
        'id' => $id,
        'tytul' => $tytul,
        'cena' => $cena,
        'ilosc' => $ilosc,
    ];
}

// Funkcja usuwania produktu z koszyka
function UsunZKoszyka($id) {
    foreach ($_SESSION['cart'] as $index => $item) {
        if ($item['id'] == $id) {
            unset($_SESSION['cart'][$index]);
            $_SESSION['cart'] = array_values($_SESSION['cart']); // Przeindeksowanie tablicy
            return;
        }
    }
}

// Funkcja aktualizacji ilości produktu w koszyku
function AktualizujKoszyk($id, $ilosc) {
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $id) {
            $item['ilosc'] = $ilosc;
            return;
        }
    }
}

// Obsługa żądań POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['dodaj_do_koszyka'])) {
        $id = intval($_POST['id']);
        $tytul = htmlspecialchars($_POST['tytul']);
        $cena = floatval($_POST['cena']);
        $ilosc = intval($_POST['ilosc']);

        DodajDoKoszyka($id, $tytul, $cena, $ilosc);

        // Przekierowanie na `index.php` z parametrem `idp=cart`
        header('Location: index.php?idp=cart');
        exit;
    }

    if (isset($_POST['usun_z_koszyka'])) {
        $id = intval($_POST['id']);
        UsunZKoszyka($id);
        header('Location: index.php?idp=cart');
        exit;
    }

    if (isset($_POST['aktualizuj_koszyk'])) {
        foreach ($_POST['ilosc'] as $id => $ilosc) {
            AktualizujKoszyk(intval($id), intval($ilosc));
        }
        header('Location: index.php?idp=cart');
        exit;
    }
}

// Wyświetlanie koszyka
function PokazKoszyk() {
    if (empty($_SESSION['cart'])) {
        echo '<h1>Twój koszyk jest pusty.</h1>';
        return;
    }

    echo '<h1>Twój koszyk</h1>';
    echo '<form method="post">';
    echo '<table border="1" cellpadding="10" cellspacing="0">';
    echo '<tr>
            <th>Produkt</th>
            <th>Cena za sztukę</th>
            <th>Ilość</th>
            <th>Razem</th>
            <th>Akcje</th>
          </tr>';

    $total = 0;
    foreach ($_SESSION['cart'] as $item) {
        $subtotal = $item['cena'] * $item['ilosc'];
        $total += $subtotal;

        echo '<tr>';
        echo '<td>' . htmlspecialchars($item['tytul']) . '</td>';
        echo '<td>' . number_format($item['cena'], 2) . ' zł</td>';
        echo '<td>
                <input type="number" name="ilosc[' . $item['id'] . ']" value="' . $item['ilosc'] . '" min="1" required>
              </td>';
        echo '<td>' . number_format($subtotal, 2) . ' zł</td>';
        echo '<td>
                <form method="post" style="display:inline;">
                    <input type="hidden" name="id" value="' . $item['id'] . '">
                    <button type="submit" name="usun_z_koszyka">Usuń</button>
                </form>
              </td>';
        echo '</tr>';
    }

    echo '</table>';
    echo '<h3>Łączna kwota: ' . number_format($total, 2) . ' zł</h3>';
    echo '<button type="submit" name="aktualizuj_koszyk">Aktualizuj koszyk</button>';
    echo '</form>';

   
}

// Wywołanie funkcji wyświetlającej koszyk
PokazKoszyk();
?>
