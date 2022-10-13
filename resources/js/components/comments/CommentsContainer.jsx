import { useState, useCallback } from 'react';
import { submitComment } from '../../api/comments';
import { displayFlash } from '../../flashes/flash';

import { CommentsView } from './CommentsView';
import { CreateComments } from './CreateComments';

/**
 * @returns JSX.Element
 *
 * @typedef {{articleId: number}} Props
 * @param {Props} param0
 */
const CommentsContainer = ({ articleId }) => {
    const [state, setState] = useState({
        orderBy: 'desc',
        page: 0,
    });
    const [comments, setComments] = useState({
        total: 0,
        comments: null
    });

    const addComment = useCallback(async (e, content) => {
        e.stopPropagation();
        e.preventDefault();

        const request = await submitComment(articleId, content)
            .then(res => {
                if (!res) return;

                setState(s => ({ page: 1 }));
                editor.value('');
            })
    }, []);

    return <>
        <h2 className="section-title">L'article vous a plu ? Laissez-un commentaire !</h2>

        <CreateComments onSubmit={addComment} />

        <CommentsView articleId={articleId} comments={[comments, setComments]} state={[state, setState]} />
    </>
};

export default CommentsContainer;
