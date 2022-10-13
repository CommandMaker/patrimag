import { sanitize } from 'dompurify';
import { marked } from 'marked';
import { memo, useCallback, useState } from 'react';
import { v4 as uuidv4 } from 'uuid';
import { submitComment } from '../../api/comments';
import { displayFlash } from '../../flashes/flash';

import { CommentEdit } from './CommentEdit';

/**
 * @typedef {{id: number, name: string, email: string, is_admin: boolean}} CommentAuthor
 * @typedef {{articleId: number, commentId: number, content: string, author: CommentAuthor, created_at: string, onReplyAdded: ?() => void, isReply: boolean, replies: Array}} CommentProps
 *
 * @param {CommentProps} param0
 */
const Comment = memo(({articleId, commentId, content, author, created_at, onReplyAdded = null, isReply = false, replies = []}) => {
    let createdAt = new Date(created_at);

    const [inEdit, setInEdit] = useState(false);

    /**
     * @param {React.MouseEvent} e 
     * @param {string} content The content of the comment
     */
    const submitReply = useCallback(async (e, content) => {
        e.preventDefault();
        e.stopPropagation();

        await submitComment(articleId, content, commentId).then(res => {
            if (!res) return;

            setInEdit(false);

            replies.push(res.data.data);

            displayFlash('success', 'Votre commentaire a bien été publié');
        });
    }, []);

    const cancelEdition = useCallback(() => {
        setInEdit(false);
    }, []);

    const renderReplies = () => {
        let el = [];

        if (replies.length === 0 && inEdit !== true) {
            return null;
        }

        replies.forEach(r => {
            el.push(
                <Comment
                    articleId={articleId}
                    commentId={r.id}
                    content={r.content}
                    author={r.author}
                    created_at={r.created_at}
                    isReply={true}
                    key={r.id}
                />
            );
        });

        if (inEdit) {
            el.push(
                <CommentEdit
                    key={uuidv4()}
                    articleId={articleId} 
                    commentId={commentId}
                    onCancel={cancelEdition}
                    onSubmit={submitReply}
                />
            );
        }

        return el;
    }

    /**
     * Action when reply button is clicked
     *
     * @param {React.MouseEvent} e
     */
    const editComment = (e) => {
        e.preventDefault();
        e.stopPropagation();

        setInEdit(true);
    }

    const displayRightSide = () => {
        return user ? <div className="is-flex is-justify-content-space-between is-align-items-center">
            <div className="header-right">
                { !isReply ? <button type="button" className="reply-button" onClick={editComment}><i className="ri-share-forward-fill"/></button> : null }
                {user.id === author.id ? <a href={`/article/delete-comment?cid=${commentId}`} className="link-without-animation" style={{
                    fontSize: '1.5rem',
                    color: 'rgb(var(--danger-color))'
                }}><i className="ri-delete-bin-line"/></a> : null}
            </div>
        </div>
        :
        null
    }

    return <>
        <div className={`comment${isReply ? ' reply' : ''}`}>
            <div className="comment-header">
                <div className="is-flex is-justify-content-space-between is-align-items-center">
                    <p>Par <b>{author.name}</b> {author.is_admin ? '(Administrateur)' : null} le {createdAt.toLocaleDateString('fr-FR')} à {createdAt.getHours()}:{createdAt.getMinutes().toString().padStart(2, '0')}</p>
                    {displayRightSide()}
                </div>
            </div>
            <div className="comment-body" dangerouslySetInnerHTML={{__html: sanitize(marked.parse(content))}} />
        </div>
        {!isReply ? <div className="replies-container">{renderReplies()}</div> : null}
    </>
});

export { Comment };
