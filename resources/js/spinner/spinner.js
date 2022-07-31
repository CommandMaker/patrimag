/**
 * Display spinner in a container
 * 
 * @param {HTMLElement} container The element where you want to display the spinner
 * @returns {void}
 */
const displaySpinner = (container) => {
    const spinner = document.createElement('span');
    spinner.classList.add('spinner-three-dots');

    container.appendChild(spinner);
}

/**
 * Hide a specific spinner (remove it from the DOM)
 * 
 * @param {HTMLElement} spinner The spinner to hide
 * @param {boolean} removeContainer Does the function remove the spinner's parent ?
 * @returns {void}
 */
const hideSpinner = (spinner, removeContainer) => {
    const parent = spinner.parentNode;

    if (removeContainer) {
        parent.parentNode.removeChild(parent);
        return;
    }

    parent.removeChild(spinner);
}

export {displaySpinner, hideSpinner}
