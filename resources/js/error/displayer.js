/**
 * Display an error inside the notification section
 *
 * @param {string} error Message you want to display
 */
const displayError = (error) => {
    let container = document.createElement('div');
    container.textContent = error;
    container.classList.add('notification', 'is-danger');

    document.querySelector('#notification-section').appendChild(container);
}

export {displayError}
