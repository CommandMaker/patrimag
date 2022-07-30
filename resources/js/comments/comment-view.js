const {displayError} = require('../error/displayer');
const moment = require('moment');
const DOMPurify = require('dompurify');
const {marked} = require('marked');

let page = 1;

const commentsContainer = document.querySelector('#comments-container');
const postID = document.querySelector('article.article').dataset.id;

let apiCalled = false;
let numberShowed = false;

/**
 * Fetch comments from the API
 *
 * @param {string} orderBy
 * @returns {Promise<Object>}
 */
const getApiData = async (orderBy) => {
    if (orderBy.toLowerCase() !== 'asc' && orderBy.toLowerCase() !== 'desc')
    {
        displayError('Je ne sais pas comment vous faites mais la valeur de orderBy est invalide');
        return;
    }

    const commentsApiUrl = `${location.protocol}//${location.host}/api/comments/${postID}?page=${page}&orderby=${orderBy}`;

    apiCalled = true

    return await fetch(commentsApiUrl)
        .then(res => {
            return res.json()
        })
        .then(json => {
            return json
        }).catch(ex => {
            console.error('Erreur dans le fetch : ' + ex.message);
        });
}

/**
 * Add the fetched comments to the page
 *
 * @param {number} json.status HTTP Status Code
 * @param {?string} json.msg Error message (if any)
 * @param {number} json.total The total number of comments for this article
 * @param {Array} json.data The API returned value
 * @param {string} json.data.content The comment's content
 * @param {Date} json.data.created_at The comment's creation date
 * @param {string} json.data.author.name Name of the comment's author
 * @param {boolean} json.data.author.is_admin If the comment's author is admin
 * @returns void
 */
const appendCommentsToPage = (json) => {
    if (json.status !== 200) {
        return displayError(`Erreur lors de la récupération des commentaires (status ${json.status}) : ${json.msg}`);
    }

    if (!numberShowed) {
        document.querySelector('#other-comments').textContent += ` (${json.total})`;
        numberShowed = true;
    }

    json.data.forEach(e => {
        let container = document.createElement('div');
        container.classList.add('comment');

        let commentHeader = document.createElement('div');
        commentHeader.classList.add('comment-header');
        commentHeader.innerHTML = `Écrit par <b>${e.author.name}</b> ${e.author.is_admin ? '(Administrateur)': ''} le ${moment(e.created_at).format('DD/MM/YYYY à H:m')}`;

        container.appendChild(commentHeader);

        let commentBody = document.createElement('div');
        commentBody.classList.add('comment-body');
        commentBody.innerHTML = DOMPurify.sanitize(marked.parse(e.content));

        container.appendChild(commentBody);

        commentsContainer.appendChild(container);
    });

    page++;
    apiCalled = false;
}

/**
 * 
 * @param {string} orderBy
 */
const fetchAndDisplayComments = async (orderBy) => {
    let apiCall = await getApiData(orderBy)
        .then(json => {return json});
    appendCommentsToPage(apiCall);
}

/**
 * Trigger comments fetch when user change orderBy
 */
document.querySelector('#orderby').addEventListener('change', (e) => {
    commentsContainer.textContent = '';
    page = 1;

    fetchAndDisplayComments(e.target.value)
});

/**
 * Trigger comments fetch when user scroll to the bottom of the page
 */
window.addEventListener('scroll', async (e) => {
    if ((window.innerHeight + window.scrollY) <= document.body.offsetHeight) return;
    if (apiCalled) return;

    let orderBy = document.querySelector('#orderby');

    fetchAndDisplayComments(orderBy.value);
});
