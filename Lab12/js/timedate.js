

// Funkcja pobierająca i wyświetlająca bieżącą datę w formacie MM/DD/RRRR
function getDate() {
    var today = new Date(); // Utworzenie obiektu reprezentującego bieżącą datę i godzinę
    theDate = ((today.getMonth() + 1) < 10 ? "0" : "") + (today.getMonth() + 1) + "/" + // Formatowanie miesiąca z zerem przed cyframi jednocyfrowymi
              (today.getDate() < 10 ? "0" : "") + today.getDate() + "/" +              // Formatowanie dnia w podobny sposób
              today.getFullYear();                                                    // Pobranie roku
    document.getElementById("data").innerHTML = theDate; // Wyświetlenie daty w elemencie HTML o id "data"
}

// Zmienna do przechowywania identyfikatora timera
var timerID = null;
// Flaga określająca, czy zegar jest uruchomiony
var timerRunning = false;

// Funkcja zatrzymująca działający zegar
function stopClock() {
    if (timerRunning) {        // Sprawdzenie, czy zegar jest uruchomiony
        clearTimeout(timerID); // Zatrzymanie timera
        timerRunning = false;  // Ustawienie flagi zegara jako nieaktywnej
    }
}

// Funkcja uruchamiająca zegar i aktualizująca datę
function startClock() {
    stopClock();  // Zatrzymanie ewentualnie działającego zegara
    getDate();    // Aktualizacja daty
    showTime();   // Wyświetlenie aktualnego czasu
}

// Funkcja wyświetlająca aktualny czas
function showTime() {
    var now = new Date();     // Utworzenie obiektu reprezentującego bieżący czas
    var hours = now.getHours();   // Pobranie godzin
    var minutes = now.getMinutes(); // Pobranie minut
    var seconds = now.getSeconds(); // Pobranie sekund

    // Formatowanie godziny w formacie HH:MM:SS
    var timeValue = ((hours > 12) ? hours - 12 : hours) + " : " + 
                    (minutes < 10 ? "0" : "") + minutes + " : " + 
                    (seconds < 10 ? "0" : "") + seconds;
    // Dodanie informacji o porze dnia (AM/PM)
    timeValue += (hours >= 12) ? " P.M." : " A.M.";

    // Wyświetlenie sformatowanego czasu w elemencie HTML o id "zegarek"
    document.getElementById("zegarek").innerHTML = timeValue;

    // Ustawienie timera na wywołanie funkcji `showTime` co sekundę
    timerID = setTimeout("showTime()", 1000);
    timerRunning = true; // Ustawienie flagi zegara jako aktywnej
}
