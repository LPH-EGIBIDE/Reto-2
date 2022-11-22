async function getNotifications() {
    fetch("/api/user/manageNotifications").then((response) => {
        return response.json();
    }).then((data) => {
        if (data.status === "success") {
            let notifications = data.notifications;
            let notificationsContainer = document.querySelector("#notificationCenter");
            notificationsContainer.innerHTML = "";
            let notificationTypeIcons = {
                0: "fa-regular fa-bell",
                1: "fa-regular fa-up",
                2: "fa-regular fa-throphy-star",
            }
            for (let i = 0; i < notifications.length; i++) {
                let notification = notifications[i];
                let notificationHtml = notificationTemplate(notification);
            }
        } else {
            showToast(data.message, "error");
        }
    });
}

function notificationTemplate(notificationData) {
    //Calculamos la fecha de la notificación
    let notificationDate = new Date(notificationData.date);
    let notificationDateFormatted = notificationDate.toLocaleDateString("es-ES", {
        day: "numeric",
    }) + " de " + notificationDate.toLocaleDateString("es-ES", {
        month: "long",

    }) + " de " + notificationDate.toLocaleDateString("es-ES", {
        year: "numeric",
    });
    let notificationHtml = `
    <div class="notificacion">
        <i class="fa-regular ${notificationData.type}"></i>
        <p class="mensaje"><a href="${notificationData.href}">${notificationData.text}</a></p>
        <div class="fecha">
            <!--<p>Hace: </p><p class="tiempo"></p>1 dia<p></p>-->
        </div>
        <i class="fa-solid fa-trash"></i>
    </div>
    `;
    return notificationHtml;
}



