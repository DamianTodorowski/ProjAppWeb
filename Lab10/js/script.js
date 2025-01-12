// Plik JavaScript do obsługi różnych funkcji na stronie

// Flagi do kontroli stanu
var computed = false; // Czy wartość została obliczona
var decimal = 0;      // Czy użyto kropki dziesiętnej

// Funkcja przeliczająca jednostki
function convert(form, measure1, measure2) {
    const inputValue = parseFloat(form.input.value); // Pobranie wartości wejściowej
    const result = inputValue * measure1.value / measure2.value; // Obliczenie wyniku na podstawie przelicznika
    form.display.value = result; // Wyświetlenie wyniku w polu wyjściowym
}

// Funkcja dodająca znak do pola wejściowego
function addChar(input, character) {
    if ((character == "." && decimal == 0) || character != ".") { // Sprawdzenie, czy znak jest poprawny
        if (input.value == "" || input.value == "0") { // Jeśli pole jest puste lub wartość to 0
            input.value = character; // Dodaj znak
        } else {
            input.value += character; // Dodaj znak do istniejącej wartości
        }
        convert(input.form, input.form.measure1, input.form.measure2); // Aktualizacja wyniku
        computed = true; // Ustawienie flagi obliczenia
        if (character == ".") {
            decimal = 1; // Ustawienie flagi dziesiętnej
        }
    }
}

// Funkcja otwierająca nowe okno
function openVotchom() {
    window.open("", "Display window", "toolbar=no,directories=no,menubar=no"); // Otwiera nowe okno bez pasków narzędzi
}

// Funkcja czyszcząca pola formularza
function clear(form) {
    form.input.value = 0;    // Ustawienie pola wejściowego na 0
    form.display.value = 0;  // Ustawienie pola wyjściowego na 0
}

// Funkcja zmieniająca kolor tła strony
function changeBackground(color) {
    document.body.style.backgroundColor = color; // Ustawienie nowego koloru tła
}
