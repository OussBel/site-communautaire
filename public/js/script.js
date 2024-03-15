document
    .querySelectorAll('.add_item_link')
    .forEach(btn => {
        btn.addEventListener("click", addFormToCollection)
    });

function addFormToCollection(e) {
    const collectionHolder = document.querySelector('.' + e.currentTarget.dataset.collectionHolderClass);

    const item = document.createElement('li');

    item.innerHTML = collectionHolder
        .dataset
        .prototype
        .replace(
            /__name__/g,
            collectionHolder.dataset.index
        );

    item.style.listStyleType = 'none';

    collectionHolder.appendChild(item);

    collectionHolder.dataset.index++;

    addTagFormDeleteLink(item);

};

function addTagFormDeleteLink(item) {

    const removeFormButton = document.createElement('button');

    // Create an <i> element with FontAwesome classes
    const iconElement = document.createElement('i');
    iconElement.classList.add('fas', 'fa-times', 'fa-2x'); // Adjust classes based on your FontAwesome version

    // Append the <i> element to the button
    removeFormButton.appendChild(iconElement);

    // Add Bootstrap classes for a red link without underline
    removeFormButton.classList.add('btn', 'btn-link', 'text-danger', 'd-flex', 'align-items-start', 'mb-1', 'p-0', 'border-0', 'background-transparent', 'text-decoration-none', 'ml-auto');
    // Change 'align-items-center' to 'align-items-start'

    item.append(removeFormButton);

    removeFormButton.addEventListener('click', (e) => {
        e.preventDefault();
        // remove the li for the tag form
        item.remove();
    });
}

document
    .querySelectorAll('ul.images li')
    .forEach((tag) => {
        addTagFormDeleteLink(tag)
    })



// Get all elements with the class 'js-remove-item'
const removeButtons = document.querySelectorAll('.js-remove-item');

// Loop through each remove button and attach a click event listener
removeButtons.forEach(function (button) {
    button.addEventListener('click', function () {
        // Get the parent element (the div with class 'js-item') of the clicked button
        const parentDiv = button.closest('.js-item');

        // Check if the parent div exists
        if (parentDiv) {
            // Remove the parent div from the DOM
            parentDiv.remove();
        }
    });
});