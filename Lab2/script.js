var computed = false;
var decimal = 0;

function convert(form, measure1, measure2) {
    const inputValue = parseFloat(form.input.value);
    const result = inputValue * measure1.value / measure2.value;
    form.display.value = result;
}

function addChar(input, character) {
    if ((character == "." && decimal == 0) || character != ".") {
        if (input.value == "" || input.value == "0") {
            input.value = character;
        } else {
            input.value += character;
        }
        convert(input.form, input.form.measure1, input.form.measure2);
        computed = true;
        if (character == ".") {
            decimal = 1;
        }
    }
}

function openVotchom() {
    window.open("", "Display window", "toolbar=no,directories=no,menubar=no");
}

function clear(form) {
    form.input.value = 0;
    form.display.value = 0;

}

function changeBackground(color) {
    document.body.style.backgroundColor = color;
}