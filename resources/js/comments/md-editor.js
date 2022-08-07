import EasyMDE from 'easymde';

const mdEditor = new EasyMDE({
    element: document.querySelector('#comment-md-editor'),
    toolbar: false,
    status: false,
    spellChecker: false
});
