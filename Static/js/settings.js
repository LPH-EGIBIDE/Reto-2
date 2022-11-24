async function setAvatar(formData) {
    return fetch(`/api/attachments/uploadAttachment`, {
        method: 'POST',
        body: formData
    });
}

async function setPassword(formData) {
    formData.append('method', 'changePassword');
    return fetch(`/api/users/manageUser`, {
        method: 'POST',
        body: formData
    });
}

async function setDescription(formData) {
    formData.append('method', 'changeDescription');
    return fetch(`/api/users/manageUser`, {
        method: 'POST',
        body: formData
    });
}

function changeAvatar(){
    // Generate a hidden input element
    let input = document.createElement('input');
    input.type = 'file';
    input.accept = 'image/*';
    input.onchange = e => {
        let file = e.target.files[0];
        let formData = new FormData();
        formData.append('file', file);
        formData.append('action', 'setAvatar');
        setAvatar(formData).then(response => {
            if (response.ok) {
                response.json().then(data => {
                    if (data.status === 'success') {
                        let avatar = document.getElementById('avatar');
                        let avatarHeader = document.getElementById('userLogo');
                        let avatarUrl = "/api/attachments/id/"+data.attachment;
                        avatar.src = avatarUrl;
                        avatarHeader.src = avatarUrl;
                        showToast(data.message, 'success');
                    } else
                        showToast(data.message, 'error');
                }).catch(err => {
                    console.error(err);
                    showToast(err.message, 'error');
                });
            }
        });
    };
    input.click();
}

function changePassword(formElement){
    let formData = new FormData(formElement);
    setPassword(formData).then(response => {
        if (response.ok) {
            response.json().then(data => {
                if (data.status === 'success') {
                    showToast(data.message, 'success');
                } else
                    showToast(data.message, 'error');
                formElement.reset();
            }).catch(err => {
                console.error(err);
                showToast(err.message, 'error');
            });
        }
    });
}

function changeDescription(formElement){
    let formData = new FormData(formElement);
    setDescription(formData).then(response => {
        if (response.ok) {
            response.json().then(data => {
                if (data.status === 'success') {
                    showToast(data.message, 'success');
                } else
                    showToast(data.message, 'error');
            }).catch(err => {
                console.error(err);
                showToast(err.message, 'error');
            });
        }
    });
}

// Get the form elements defined in your form HTML above and add the event listener
document.getElementById('changePassword').addEventListener('submit', (e) => {
    e.preventDefault();
    changePassword(e.target);
});

document.getElementById('changeDescription').addEventListener('submit', (e) => {
    e.preventDefault();
    changeDescription(e.target);
});