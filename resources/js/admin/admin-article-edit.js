import EasyMDE from 'easymde';

const editor = new EasyMDE({
    element: document.querySelector('#admin-content'),
    spellChecker: false,
    showIcons: ['strikethrough', 'code', 'table', 'heading-bigger', 'heading-smaller', 'clean-block', 'horizontal-rule'],
});