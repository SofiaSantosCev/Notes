const URL = "https://127.0.0.1/Notes/back-restart/public/index.php/api/";

var token = localStorage.getItem('token');
var saveBtn = document.getElementById('save');

window.onload = function () {
    console.log(token);
    if (token != null) {
        getNotes();
    } else {
        window.location.href = "login.html";
    }
}

const sendHttpRequest = (method, url, data) => {
    const promise = new Promise((resolve, reject) => {
        const xhr = new XMLHttpRequest();
        xhr.open(method, url);
        xhr.responseType = 'json';
        var request = new XMLHttpRequest();

        xhr.setRequestHeader('Content-Type', 'application/json');
        xhr.setRequestHeader('Authorization', token);

        xhr.onload = () => {
            if (xhr.status >= 400) {
                reject(xhr.response);
            } else {
                resolve(xhr.response);
            }
        };

        xhr.onerror = () => {
            reject('Something went wrong!');
        };

        xhr.send(JSON.stringify(data));
    });
    return promise;
};

const getNotes = () => {
    sendHttpRequest('GET', URL + 'note')
        .then(responseData => {
            console.log(responseData['notes']);
            showNotes(responseData['notes']);
        })
        .catch(err => {
            console.log(err);
        });
};

function showNotes(notes) {

    notesList = document.getElementById('notesList');

    for (i = 0; i < notes.length; i++) {
        listItem = document.createElement('li');
        listItem.innerHTML = "<a href='#' id=" + i + "><p class='noteName'>" + notes[i]['title'] + '</p><p class="notePreview">' + notes[i]['content'] + '</p></a>';
        notesList.appendChild(listItem);
    }
}

const logOut = () => {

    localStorage.removeItem('token');
    window.location.href = 'login.html';
} 

document.getElementById('save').onclick = () => {
    sendHttpRequest('POST', URL + 'note', {
        title: document.getElementById('inputTitle').value,
        content: document.getElementById('content').value
    }).then(responseData => {
        console.log(responseData);
        location.reload();

    }).catch(err => {
        console.log(err);
    });
}
