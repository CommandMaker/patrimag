const {displayError} = require('../error/displayer');
const DOMPurify = require('dompurify');
const {marked} = require('marked');
const { displaySpinner, hideSpinner } = require('../spinner/spinner');

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

    /* Display message when no comments */
    if (json.total == 0) {
        const container = document.createElement('div');
        container.classList.add('is-flex', 'is-flex-direction-column', 'is-justify-content-center', 'is-align-items-center');

        const h3 = document.createElement('h3');
        h3.classList.add('title', 'is-3', 'my-4');
        h3.textContent = 'Aucun commentaire n\'a encore été posté';

        const subtitle = document.createElement('p');
        subtitle.textContent = 'Soyez le premier à en poster un !';

        container.appendChild(h3);
        container.appendChild(subtitle);

        commentsContainer.appendChild(container);
        return;
    }

    /* Show comments number */
    if (!numberShowed) {
        document.querySelector('#other-comments').textContent += ` (${json.total})`;
        numberShowed = true;
    }

    /* Display comments */
    json.data.forEach(e => {
        const createdAt = new Date(e.created_at);

        let container = document.createElement('div');
        container.classList.add('comment');

        let commentHeader = document.createElement('div');
        commentHeader.classList.add('comment-header');
        commentHeader.innerHTML = `Écrit par <b>${e.author.name}</b> ${e.author.is_admin ? '(Administrateur)': ''} le ${createdAt.toLocaleDateString('fr-FR')} à ${createdAt.getHours() + ':' + createdAt.getMinutes()}`;

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
    const spinnerContainer = document.createElement('div');
    spinnerContainer.classList.add('is-flex', 'is-justify-content-center');

    commentsContainer.appendChild(spinnerContainer);

    displaySpinner(spinnerContainer);

    const apiCall = await getApiData(orderBy)
        .then(json => {return json});

    if (apiCall.status) {
        hideSpinner(spinnerContainer.firstChild, true);
    }

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
