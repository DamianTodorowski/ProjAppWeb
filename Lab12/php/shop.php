<?php
require_once 'cfg.php'; // Połączenie z bazą danych

function PokazKategorieIProdukty() {
    global $link;

    // Pobranie kategorii głównych (matka = 0)
    $query_kategorie_glowne = "SELECT * FROM categories WHERE matka = 0";
    $result_kategorie_glowne = mysqli_query($link, $query_kategorie_glowne);

    if (!$result_kategorie_glowne) {
        die("Błąd w zapytaniu SQL: " . mysqli_error($link));
    }

    echo '<h1>Produkty w sklepie</h1>';

    while ($kategoria_glowna = mysqli_fetch_assoc($result_kategorie_glowne)) {
        $id_kategorii = $kategoria_glowna['id'];
        $nazwa_kategorii = htmlspecialchars($kategoria_glowna['nazwa']);

        echo '<h2>Kategoria: ' . $nazwa_kategorii . '</h2>';

        // Wyświetlenie podkategorii
        echo '<ul>';
        $query_podkategorie = "SELECT * FROM categories WHERE matka = $id_kategorii";
        $result_podkategorie = mysqli_query($link, $query_podkategorie);

        if (mysqli_num_rows($result_podkategorie) > 0) {
            while ($podkategoria = mysqli_fetch_assoc($result_podkategorie)) {
                $id_podkategorii = $podkategoria['id'];
                $nazwa_podkategorii = htmlspecialchars($podkategoria['nazwa']);
                echo '<li>' . $nazwa_podkategorii . '</li>';
            }
        } else {
            echo '<li>Brak podkategorii</li>';
        }
        echo '</ul>';

        echo '<div style="display: flex; flex-wrap: wrap; gap: 20px;">';

        // Pobranie produktów z kategorii głównej oraz jej podkategorii
        $query_produkty = "
            SELECT * FROM produkty 
            WHERE ilosc_magazyn > 0 
            AND (id_kategoria = $id_kategorii OR id_kategoria IN (
                SELECT id FROM categories WHERE matka = $id_kategorii
            ))
        ";
        $result_produkty = mysqli_query($link, $query_produkty);

        if (mysqli_num_rows($result_produkty) > 0) {
            while ($produkt = mysqli_fetch_assoc($result_produkty)) {
                echo '<div style="border: 1px solid #ccc; padding: 10px; width: 250px;">';
                echo '<h3>' . htmlspecialchars($produkt['tytul']) . '</h3>';
                echo '<p>' . htmlspecialchars($produkt['opis']) . '</p>';
                echo '<p><b>Cena:</b> ' . number_format($produkt['cena_netto'] * (1 + $produkt['podatek_vat'] / 100), 2) . ' zł</p>';
                echo '<p><b>W magazynie:</b> ' . $produkt['ilosc_magazyn'] . ' szt.</p>';
                echo '<p><b>VAT:</b> ' . $produkt['podatek_vat'] . '%</p>';
                if (!empty($produkt['zdjecie'])) {
                    echo '<img src="data:image/jpeg;base64,' . base64_encode($produkt['zdjecie']) . '" alt="Zdjęcie produktu" style="width: 100%; height: auto;">';
                } else {
                    echo '<p>Brak zdjęcia</p>';
                }
                echo '<form method="post" action="index.php?idp=cart">
                        <input type="hidden" name="id" value="' . $produkt['id'] . '">
                        <input type="hidden" name="tytul" value="' . htmlspecialchars($produkt['tytul']) . '">
                        <input type="hidden" name="cena" value="' . $produkt['cena_netto'] * (1 + $produkt['podatek_vat'] / 100) . '">
                        <label>Ilość:</label>
                        <input type="number" name="ilosc" value="1" min="1" max="' . $produkt['ilosc_magazyn'] . '" required>
                        <button type="submit" name="dodaj_do_koszyka">Dodaj do koszyka</button>
                      </form>';
                echo '</div>';
            }
        } else {
            echo '<p>Brak produktów w tej kategorii.</p>';
        }

        echo '</div>'; // Zakończenie sekcji produktów
    }
}

// Wywołanie funkcji
PokazKategorieIProdukty();
?>
