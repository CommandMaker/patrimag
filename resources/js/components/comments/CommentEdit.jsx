import EasyMDE from 'easymde';
import React, {memo, useEffect} from 'react';

/**
 * @param {{articleId: number, commentId: number, onCancel: (e: React.MouseEvent) => void, onSubmit: (e: React.MouseEvent, content: string) => void }} param0
 *
 * @return JSX.Element
 */
const CommentEdit = memo(({articleId, commentId, onCancel, onSubmit}) => {
    const editorRef = React.createRef();
    /** @type {EasyMDE} */
    let editor;

    useEffect(() => {
        editor = new EasyMDE({
            element: editorRef.current,
            toolbar: false,
            status: false,
            spellChecker: false,
            minHeight: '100px'
        });

        return () => {
            editor.toTextArea();
            editor = null;
        }
    }, []);

    return <div className="comment reply">
        <div className="comment-body">
            <textarea ref={editorRef} name="comment_content" id="comment-reply-area"/>
            <div className="is-flex" style={{ gap: '1rem', marginTop: '1rem' }}>
                <button type="button" className="button" onClick={e => onSubmit(e, editor.value())}>Publier</button>
                <button type="button" className="button danger" onClick={onCancel}>Annuler</button>
            </div>
        </div>
    </div>
});

export { CommentEdit };