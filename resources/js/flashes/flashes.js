let flashesContainer = document.querySelector('div.flashes-container');

/**
* Disappear the flash
* @param f {HTMLDivElement} The flash to disappear
* @returns void
*/
const disappear = (f) => {
    f.classList.add('disappear');
    
    setTimeout(() => {
        flashesContainer.removeChild(f);
    }, 1000);
}

const flashAnimation = () => {
    let flashes = flashesContainer.querySelectorAll('div.flash');

    flashes.forEach(f => {
        let timeoutId;
        timeoutId = setTimeout(() => {disappear(f)}, 6000);

        f.querySelector('div.close-container').addEventListener('click', () => {
            disappear(f);
            clearTimeout(timeoutId);
        });
        
    });
}

flashesContainer.addEventListener('DOMNodeInserted', flashAnimation);

flashAnimation();