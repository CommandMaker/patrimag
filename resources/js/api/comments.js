import axios from 'axios';
import { Axios } from 'axios';
import {displayFlash} from '../flashes/flash';

/**
 * Fetch comments from the api
 *
 * @param {number} articleId The id of the article you want to get the comments
 * @param {number} page The page of comments to fetch
 * @param {?string} orderBy The order to retrieve comments
 *
 * @typedef {{id: number, content: string, article_id: number, author_id: number, created_at: string, updated_at: string, parent: ?number, author: CommentAuthor, replies: APIComment[]}} APIComment
 * @typedef {{status: number, total: ?number, lastPage: ?number, page: ?number, data: ?APIComment[]}} APIReturnData
 *
 * @returns {APIReturnData}
 */
const fetchComments = async (articleId, page, orderBy) => {
    let order = orderBy

    if (typeof order !== 'string' || order.toLowerCase() !== 'asc' && order.toLowerCase() !== 'desc') {
        order = 'desc';
    }

    let data;

    await axios.get(`${location.protocol}//${location.host}/api/comments/${articleId}?page=${page}&orderby=${order}`)
        .then(res => {
            data = res.data;
        })
        .catch(err => {
            displayFlash('danger', `Erreur lors de la récupération des commentaires : ${err}`);
        });

    return data;
}

/**
 * Add a new comment to the given article
 * 
 * @param {number} articleId The id of the article you want to publish the comment
 * @param {string} content The content of the comment you want publish
 * @param {number} parent The id of the parent comment (to add a reply)
 * @returns {Promise<import('axios').AxiosResponse<any, any>>}
 */
const submitComment = async (articleId, content, parent = null) => {
    return await axios.post(`${location.protocol}//${location.host}/api/comments/new/${articleId}`, {
        'comment_content': content,
        'parent': parent
    })
    .catch(err => {
        if (err.response) {
            switch (err.response.data.status) {
                case 401:
                    displayFlash('danger', err.response.data.msg);
                    break;
                case 500:
                    if (err.response.data.errors) {
                        err.response.data.errors.forEach(e => displayFlash('danger', `Erreur lors de l'ajout de votre commentaire : ${e}`));
                    } else {
                        displayFlash('danger', `Erreur lors de l'ajout de votre commentaire : ${err.response.data.msg}`);
                    }
                    break;
                default:
                    displayFlash('danger', `Erreur lors de l'ajout de votre commentaire : ${err}`);
                    break;
            }
        }
    });
}

/**
 * Delete a given comment
 * 
 * @param {number} commentId The id of the comment you want to delete
 */
const deleteCommentAPI = async (commentId) => {
    return axios.delete(`${location.protocol}//${location.host}/api/comments/delete/${commentId}`)
        .catch(err => {
            if (err.response) {
                displayFlash('danger', err.response.data.msg);
            }
        });
}

export {fetchComments, submitComment, deleteCommentAPI};
