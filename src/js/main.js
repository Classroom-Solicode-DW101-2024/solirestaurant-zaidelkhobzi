const incdecContainers = document.querySelectorAll(".quantite-counter");

incdecContainers.forEach(container => {
    const plus = container.querySelector(".plus");
    const minus = container.querySelector(".minus");
    const quantite = container.querySelector(".quantite");

    plus.addEventListener("click", () => {
        let currentValue = parseInt(quantite.value);
        quantite.value = currentValue + 1;
    });

    minus.addEventListener("click", () => {
        let currentValue = parseInt(quantite.value);
        quantite.value = currentValue > 0 ? currentValue - 1 : 0; // Prevent going below 0
    });
});

/** popup */

var popup = document.getElementById("popup");
var btn = document.querySelector("input[name='valider']");
var span = document.getElementsByClassName("close")[0];

btn.onclick = function() {
    popup.style.display = "block";
}

// When the user clicks on <span> (x), close the popup
span.onclick = function() {
    popup.style.display = "none";
}

// When the user clicks anywhere outside of the popup, close it
window.onclick = function(event) {
    if (event.target == popup) {
        popup.style.display = "none";
    }
}