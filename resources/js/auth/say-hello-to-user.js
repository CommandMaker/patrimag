const moment = require('moment');

(function sayHelloToUser () {
    let helloSpan = document.querySelector('span.hello');

    if (!helloSpan) {
        return;
    }

    const date = new Date();
    const hm = String(date.getHours()) + String(date.getMinutes()).padStart(2, '0');

    if (hm < 1830) {
        helloSpan.textContent = 'Bonjour';
        return;
    }

    helloSpan.textContent = 'Bonsoir';
})();
