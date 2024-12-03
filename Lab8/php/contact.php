<?php
// Funkcja wyświetlająca formularz kontaktowy
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

function WyslijMailKontakt($name, $email, $message) {
   
    $to = "admin@projappweb.com";
    $subject = "Nowa wiadomość z formularza kontaktowego";
    $headers = "From: $email\r\n";
    $headers .= "Reply-To: $email\r\n";
    $headers .= "Content-type: text/plain; charset=UTF-8";
    $body = "Imię: $name\n";
    $body .= "E-mail: $email\n\n";
    $body .= "Wiadomość:\n$message";

    
    if (mail($to, $subject, $body, $headers)) {
        return "<p>Wiadomość została wysłana pomyślnie. Dziękujemy za kontakt!</p>";
    } else {
        return "<p>Błąd: nie udało się wysłać wiadomości. Spróbuj ponownie później.</p>";
    }
}

function PrzypomnijHaslo($email) {
    $to = $email;
    $subject = "Przypomnienie hasła";
    $headers = "From: no-reply@example.com\r\n";
    $headers .= "Content-type: text/plain; charset=UTF-8";
    $body = "Twoje hasło do panelu admina to: [wpisz_haslo_tutaj]";


    if (mail($to, $subject, $body, $headers)) {
        return "<p>Hasło zostało wysłane na podany e-mail.</p>";
    } else {
        return "<p>Błąd: nie udało się wysłać hasła. Spróbuj ponownie później.</p>";
    }
}
?>
