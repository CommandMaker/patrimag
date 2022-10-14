import React from 'react'
import VisibilitySensor from 'react-visibility-sensor';

import { deleteCommentAPI, fetchComments } from '../../api/comments';
import { displayFlash } from '../../flashes/flash';
import { useAsyncEffect } from '../../hooks/hooks';
import { Comment } from './Comment';

/**
 * @return JSX.Element
 *
 * @param {{comments: Array, articleId: number, state: Array}} param0
 */
const CommentsView = ({comments, articleId, state}) => {
    useAsyncEffect(async () => {
        if (state[0].page === 0) return;
        const request = await fetchComments(articleId, state[0].page, state[0].orderBy);

        comments[1](s => ({ total: request.total, comments: state[0].page === 1 ? request.data : [...(s.comments || []), ...request.data] }));
    }, [state[0]]);

    /**
     * @param {boolean} isVisible
     */
    const triggerCommentRender = (isVisible) => {
        if (isVisible) {
            state[1](s => ({ page: s.page + 1 }));
        }
    }

    /**
     * @param {React.MouseEvent} e 
     * @param {number} commentId 
     */
    const onCommentDeleted = (e, commentId) => {
        e.preventDefault();
        e.stopPropagation();

        deleteCommentAPI(commentId)
            .then(res => {
                if (!res) return;

                displayFlash('success', 'Votre commentaire a bien été supprimé');
                comments[1](s => ({total: s.total - 1, comments: comments[0].comments.filter(co => co.id !== commentId)}));
            });
    };

    const renderComments = () => {
        let el = [];

        if (comments[0].comments === null) {
            return;
        }

        if (comments[0].comments.length === 0) {
            return <div className="is-flex is-justify-content-center is-align-items-center is-flex-direction-column">
                <h3 className="my-4">Aucun commentaire n'a encore été posté</h3>
                <p>Soyez le premier à en poster un !</p>
            </div>
        }

        comments[0].comments.forEach(c => {
            el.push(
                <Comment
                    articleId={articleId}
                    commentId={c.id}
                    content={c.content}
                    author={c.author}
                    created_at={c.created_at}
                    isReply={c.parent === true}
                    replies={c.replies}
                    onCommentDeleted={onCommentDeleted}
                    key={c.id}
                />
            )
        });

        return el;
    }

    return <>
        <div className="is-flex is-align-items-center is-justify-content-space-between">
            <h2 className="section-title" id="other-comments">Les autres commentaires ({comments[0].total})</h2>
            <div style={{display: 'flex', gap: '10px'}}>
                <label htmlFor="orderby">Trier par :</label>
                <select id="orderby" onChange={e => {
                    state[1](s => ({ orderBy: e.target.value, page: 1 }));
                    comments[1](s => ({ comments: null }));
                }}>
                    <option value="desc">Les + récents</option>
                    <option value="asc">Les + anciens</option>
                </select>
            </div>
        </div>

        {renderComments()}

        <div className={`is-flex is-justify-content-center${comments[0].comments !== null && comments[0].comments.length === 0 ? ' hidden' : ''}`}><div className="spinner-three-dots" /></div>

        <VisibilitySensor onChange={triggerCommentRender}>
            <div style={{height: '1px'}}>&nbsp;</div>
        </VisibilitySensor>
    </>
}

export {CommentsView};
