<?php
// Plik obsługujący formularz kontaktowy i funkcje związane z e-mailem

// Funkcja generująca formularz kontaktowy
function PokazKontakt() {
    return '
        <h2>Formularz Kontaktowy</h2>
        <form method="POST" action="index.php?idp=contact">
            <label for="name">Imię:</label>
            <input type="text" id="name" name="name" required>
            
            <label for="email">E-mail:</label>
            <input type="email" id="email" name="email" required>
            
            <label for="message">Wiadomość:</label>
            <textarea id="message" name="message" required></textarea>
            
            <button type="submit">Wyślij</button>
        </form>
    ';
}

// Funkcja wysyłająca wiadomość z formularza kontaktowego
function WyslijMailKontakt($name, $email, $message) {
    // Dane e-maila
    $to = "nazawodowe@gmail.com"; // Docelowy adres e-mail
    $subject = "Nowa wiadomość z formularza kontaktowego"; // Temat wiadomości
    
    // Nagłówki
    $headers = "From: nazawodowe@gmail.com\r\n"; // Nadawca (zgodny z sendmail.ini)
    $headers .= "Reply-To: $email\r\n"; // Adres zwrotny
    $headers .= "Content-type: text/plain; charset=UTF-8\r\n"; // Kodowanie i typ wiadomości

    // Treść wiadomości
    $body = "Imię: $name\n";
    $body .= "E-mail: $email\n\n";
    $body .= "Wiadomość:\n$message";

    // Wysyłanie wiadomości
    if (mail($to, $subject, $body, $headers)) {
        return "<p>Wiadomość została wysłana pomyślnie. Dziękujemy za kontakt!</p>";
    } else {
        // Sprawdź, czy istnieje ostatni błąd
        $lastError = error_get_last();
        if ($lastError !== null) {
            error_log("Błąd wysyłania e-maila: " . $lastError['message']);
        } else {
            error_log("Błąd wysyłania e-maila: brak szczegółowych informacji.");
        }
        return "<p>Błąd: nie udało się wysłać wiadomości. Spróbuj ponownie później.</p>";
    }
}    

// Funkcja wysyłająca hasło przypomnienia
function PrzypomnijHaslo($email) {
    // Dane e-maila
    $to = $email; // Docelowy adres odbiorcy
    $subject = "Przypomnienie hasła"; // Temat wiadomości
    
    // Nagłówki
    $headers = "From: nazawodowe@gmail.com\r\n"; // Nadawca (zgodny z sendmail.ini)
    $headers .= "Content-type: text/plain; charset=UTF-8\r\n"; // Kodowanie i typ wiadomości
    
    // Treść wiadomości
    $body = "Twoje hasło do panelu admina to: admin";
    
    // Wysyłanie wiadomości
    if (mail($to, $subject, $body, $headers)) {
        return "<p>Hasło zostało wysłane na podany e-mail.</p>";
    } else {
        // Debugowanie: wyświetlanie komunikatu błędu
        error_log("Błąd wysyłania e-maila: " . error_get_last()['message']);
        return "<p>Błąd: nie udało się wysłać hasła. Spróbuj ponownie później.</p>";
    }
}
?>
