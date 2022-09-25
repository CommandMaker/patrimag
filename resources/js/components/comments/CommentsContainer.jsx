import { useState, useEffect } from 'react';
import VisibilitySensor from 'react-visibility-sensor';

import { fetchComments } from '../../api/comments';
import { useAsyncEffect } from '../../hooks/hooks';
import { Comment } from './Comment';

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

    useAsyncEffect(async () => {
        if (state.page === 0) return;
        const request = await fetchComments(articleId, state.page, state.orderBy);

        setComments(s => ({ total: request.total, comments: state.page === 1 ? request.data : [...(s.comments || []), ...request.data] }));
    }, [state]);

    /**
     * @param {boolean} isVisible
     */
    const triggerCommentRender = (isVisible) => {
        if (isVisible) {
            setState(s => ({ page: s.page + 1 }));
        }
    }

    const renderComments = () => {
        let el = [];

        if (comments.comments === null) {
            return;
        }

        if (comments.comments.length === 0) {
            return <div className="is-flex is-justify-content-center is-align-items-center is-flex-direction-column">
                <h3 className="my-4">Aucun commentaire n'a encore été posté</h3>
                <p>Soyez le premier à en poster un !</p>
            </div>
        }

        comments.comments.forEach(c => {
            el.push(
                <Comment
                    articleId={articleId}
                    commentId={c.id}
                    content={c.content}
                    author={c.author}
                    created_at={c.created_at}
                    isReply={c.parent === true}
                    replies={c.replies} key={c.id}
                />
            )
        });

        return el;
    }

    return <>
        <div className="is-flex is-align-items-center is-justify-content-space-between">
            <h2 className="section-title" id="other-comments">Les autres commentaires ({comments.total})</h2>
            <div style={{display: 'flex', gap: '10px'}}>
                <label htmlFor="orderby">Trier par :</label>
                <select id="orderby" onChange={e => {
                    setState(s => ({ orderBy: e.target.value, page: 1 }));
                    setComments(s => ({ comments: null }));
                }}>
                    <option value="desc">Les + récents</option>
                    <option value="asc">Les + anciens</option>
                </select>
            </div>
        </div>

        {renderComments()}

        <div className="is-flex is-justify-content-center"><div className="spinner-three-dots" /></div>

        <VisibilitySensor onChange={triggerCommentRender}>
            <div style={{height: '1px'}}>&nbsp;</div>
        </VisibilitySensor>
    </>
};

export default CommentsContainer;
