document.addEventListener("DOMContentLoaded", function () {
    let i = 15;
    const loadMoreButton = document.querySelector('#loadMoreButton');
    const cards = document.querySelectorAll('.card');

    loadMoreButton.addEventListener('click', () => {
        i += 15; // Increment i by 5 to display the next 5 cards
        displayMoreCards(i);
    });

    const displayMoreCards = (i) => {
        cards.forEach((card, index) => {

            if (index < i) {
                card.style.display = '';
            } else {
                card.style.display = 'none';
            }

            if (i >= cards.length) {
                loadMoreButton.style.display = 'none'; // Hide the button if there are no more cards
            } else {
                loadMoreButton.style.display = 'block'; // Show the button if there are more cards
            }

        });
    };

    displayMoreCards(i);
});
