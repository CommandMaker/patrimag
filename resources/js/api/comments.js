import axios from 'axios';
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
        });

    return data;
}

export {fetchComments};
