document.addEventListener("DOMContentLoaded", function () {
    const cards = document.querySelectorAll('.CarteRetournement');


    cards.forEach(card => card.classList.add('move-left'));


    setTimeout(() => {
        cards.forEach(card => card.classList.remove('move-left'));

        cards.forEach(card => card.classList.add('initial'));
    }, 1000);
});