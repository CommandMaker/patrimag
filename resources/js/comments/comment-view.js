import { displayError } from '../error/displayer';
import { sanitize } from 'dompurify';
import { marked } from 'marked';
import { displaySpinner, hideSpinner } from '../spinner/spinner';

let page = 1;

(() => {
    const commentsContainer = document.querySelector('#comments-container');

    if (!commentsContainer) return;

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
        if (json.total === 0) {
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
            commentHeader.innerHTML = `<div class="is-flex is-justify-content-space-between is-align-items-center">
                <p>Écrit par <b>${e.author.name}</b> ${e.author.is_admin ? '(Administrateur)': ''} le ${createdAt.toLocaleDateString('fr-FR')} à ${createdAt.getHours() + ':' + String(createdAt.getMinutes()).padStart(2, '0')}</p>
            </div>`;

            if (user && e.author.id === user.id) {
                commentHeader.querySelector('div').innerHTML += `<a href="${route}?cid=${e.id}" class="link-without-animation"><i class="ri-delete-bin-line" style="color: rgb(var(--danger-color));font-size: 1.5rem"></i></a>`
            }

            container.appendChild(commentHeader);

            let commentBody = document.createElement('div');
            commentBody.classList.add('comment-body');
            commentBody.innerHTML = sanitize(marked.parse(e.content));

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
            hideSpinner(spinnerContainer.firstElementChild, true);
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
        if ((window.innerHeight + window.scrollY + 70) < document.body.scrollHeight) return;
        if (apiCalled) return;

        let orderBy = document.querySelector('#orderby');

        fetchAndDisplayComments(orderBy.value);
    });
})();
