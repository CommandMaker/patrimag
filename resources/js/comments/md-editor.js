const EasyMDE = require('easymde');

const mdEditor = new EasyMDE({
    element: document.querySelector('#comment-md-editor'),
    toolbar: false,
    status: false
});
