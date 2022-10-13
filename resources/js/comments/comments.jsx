import React from 'react';
import ReactDOM from 'react-dom/client';
import CommentsContainer from '../components/comments/CommentsContainer';
import EasyMDE from 'easymde';
import { submitComment } from '../api/comments';
import { displayFlash } from '../flashes/flash';

const commentsContainer = document.querySelector('section.comments-section');

if (commentsContainer) {
    /** @type {number}  */
    const articleId = Number.parseInt(document.querySelector('article.article').dataset.id);

    ReactDOM.createRoot(commentsContainer).render(
        <CommentsContainer articleId={articleId} />
    );
}
