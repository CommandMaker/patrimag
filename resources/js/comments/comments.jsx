import React from 'react';
import ReactDOM from 'react-dom/client';
import CommentsContainer from '../components/comments/CommentsContainer';
import EasyMDE from 'easymde';

const commentsContainer = document.querySelector('#comments-container');

if (commentsContainer) {
    const newCommentEditor = new EasyMDE({
        element: document.querySelector('#comment-md-editor'),
        toolbar: false,
        status: false,
        spellChecker: false
    });

    /** @type {number}  */
    const articleId = Number.parseInt(document.querySelector('article.article').dataset.id);

    ReactDOM.createRoot(commentsContainer).render(
        <CommentsContainer articleId={articleId} />
    );
}
