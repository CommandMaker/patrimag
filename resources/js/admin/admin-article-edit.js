import EasyMDE from 'easymde';

const editorEventElement = document.querySelector('.editor-toolbar');
const sectionHeader = document.querySelector('.article-edit-header');

const onClassChange = (element, callback) => {
    const observer = new MutationObserver((mutations) => {
        mutations.forEach((mutation) => {
            if (
                mutation.type === 'attributes' &&
                mutation.attributeName === 'class'
                ) {
                    callback(mutation.target);
                }
            });
        });
    observer.observe(element, { attributes: true });
    return observer.disconnect;
}
    
if (editorEventElement) {
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

    onClassChange(editorEventElement, (target) => {
        if (target.classList.contains('fullscreen')) {
            sectionHeader.style.display = 'none';
        } else if (!target.classList.contains('fullscreen')) {
            sectionHeader.style.display = 'flex';
        }
    });
}