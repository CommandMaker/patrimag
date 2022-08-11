import EasyMDE from 'easymde';

const editor = new EasyMDE({
    element: document.querySelector('#admin-content'),
    spellChecker: false,
    toolbar: [
        'bold',
        'italic',
        'strikethrough', 
        'heading',
        'heading-bigger', 
        'heading-smaller', 
        '|',
        'code',
        'quote',
        {
            name: 'sup',
            action: (editor) => {
                const cm = editor.codemirror;
                let output = '';
                const selectedText = cm.getSelection() || '';

                output = `<sup>${selectedText}</sup>`;
                cm.replaceSelection(output);
            },
            className: 'fa fa-superscript',
            title: 'Exposant'
        },
        {
            name: 'ind',
            action: (editor) => {
                const cm = editor.codemirror;
                let output = '';
                const selectedText = cm.getSelection() || '';

                output = `<sub>${selectedText}</sub>`;
                cm.replaceSelection(output);
            },
            className: 'fa fa-subscript',
            title: 'Indice'
        },
        'unordered-list',
        'ordered-list',
        'clean-block',
        '|',
        'link',
        'image',
        'table',
        'horizontal-rule',
        '|',
        'preview',
        'side-by-side',
        'fullscreen',
        '|',
        'guide'
    ],
});