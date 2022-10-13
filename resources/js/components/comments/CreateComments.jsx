import React, { createRef, memo, useEffect } from 'react';
import EasyMDE from 'easymde';

/**
 * @return JSX.Element
 * 
 * @param {{onSubmit: (e: React.SyntheticEvent, content: string) => void}} param0
 */
const CreateComments = memo(({onSubmit}) => {
    const mdEditorArea = createRef();

    /** @type {EasyMDE} */
    let editor;
    
    useEffect(() => {
        editor = new EasyMDE({
            element: mdEditorArea.current,
            toolbar: false,
            status: false,
            spellChecker: false,
        });

        return () => {
            editor.toTextArea();
            editor = null;
        }
    }, []);

    return <div hidden={!user}>
        <div className="field">
            <label htmlFor="comment-md-editor" className="label">Votre commentaire :</label>
            <textarea ref={mdEditorArea} name="comment_content" id="comment-md-editor"></textarea>
        </div>
        <div className="text-right">
            <button 
                type="button" 
                className="button" 
                onClick={e => onSubmit(e, editor.value())}>Publier</button>
        </div>
    </div>
});

export {CreateComments};