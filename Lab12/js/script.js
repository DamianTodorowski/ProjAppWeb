// Plik JavaScript do obsługi różnych funkcji na stronie

// Flagi do kontroli stanu
var computed = false; // Czy wartość została obliczona
var decimal = 0;      // Czy użyto kropki dziesiętnej

// Funkcja przeliczająca jednostki
function convert(form) {
    const inputValue = parseFloat(form.input.value); // Pobranie wartości wejściowej
    const measure1Value = parseFloat(form.measure1.value); // Wartość przelicznika jednostki 1
    const measure2Value = parseFloat(form.measure2.value); // Wartość przelicznika jednostki 2

    if (isNaN(inputValue)) { // Sprawdzenie, czy wartość jest liczbą
        form.display.value = "Błąd: podaj liczbę";
        return;
    }

    // Przeliczenie jednostek
    const result = inputValue * measure1Value / measure2Value;
    form.display.value = result.toFixed(2); // Wyświetlenie wyniku z 2 miejscami po przecinku
}


// Funkcja dodająca znak do pola wejściowego
function addChar(input, character) {
    if ((character == "." && decimal == 0) || character != ".") { // Sprawdzenie, czy znak jest poprawny
        if (input.value == "" || input.value == "0") { // Jeśli pole jest puste lub wartość to 0
            input.value = character; // Dodaj znak
        } else {
            input.value += character; // Dodaj znak do istniejącej wartości
        }
        decimal = character == "." ? 1 : decimal; // Ustawienie flagi dziesiętnej
    }
}

// Funkcja otwierająca nowe okno
function openVotchom() {
    window.open("", "Display window", "toolbar=no,directories=no,menubar=no"); // Otwiera nowe okno bez pasków narzędzi
}

// Funkcja czyszcząca formularz
function clear(form) {
    form.input.value = "0";    // Czyszczenie pola wejściowego
    form.display.value = "0";  // Czyszczenie pola wyjściowego
}

// Funkcja zmieniająca kolor tła strony
function changeBackground(color) {
    document.body.style.backgroundColor = color; // Ustawienie nowego koloru tła
}
