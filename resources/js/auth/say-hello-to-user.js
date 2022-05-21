(function sayHelloToUser () {
    let helloSpan = document.querySelector('span.hello');

    if (!helloSpan) {
        return;
    }

    let time = new Date();
    let curTime = parseInt(time.getHours() + "" + ("0" + time.getMinutes()).substring(-2) + "" + ("0" + time.getSeconds()).substring(-2));

    if (curTime > 183000) {
        helloSpan.textContent = 'Bonsoir';
    } else {
        helloSpan.textContent = 'Bonjour';
    }
})();
