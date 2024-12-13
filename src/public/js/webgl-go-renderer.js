var rootId = '';
var eventSent = false;
var currentMillis = 0;
var arrCachedViews = {};
var arrClientRenderings = [];

window.onload = function () {
    sendEvent('reload', {}, true);
}

function sendEvent(event, formData = {}) {
    if (eventSent) {
        return;
    }
    eventSent = true;
    currentMillis = Date.now();

    event = event || '';
    var routeDiv = document.getElementById('routeDiv')
    var route = routeDiv.getAttribute('route');
    var token = routeDiv.getAttribute('token');

    fetch(route, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': token
        },
        body: JSON.stringify({
            event: event,
            data: formData,
        })
    }).then(response => response.text())
        .then(data => {
            try {
                if (data.startsWith('<')) {
                    document.write(data);
                    eventSent = false;
                } else {
                    json = JSON.parse(data);
                    stringified = JSON.stringify(json, null, 2);
                    console.log(stringified);
                }
            } catch (error) {
                console.error(error);
                eventSent = false;
            }
        }).catch(error => {
            console.error(error);
            eventSent = false;
        });
}

// TODO move to a helper file and optimize it
function decodeBase64(data) {
    const binaryString = atob(data);
    //return binaryString;
    const bytes = new Uint8Array(binaryString.length);
    //console.log(binaryString.length);
    for (let i = 0; i < binaryString.length; i++) {
        bytes[i] = binaryString.charCodeAt(i);
    }
    return new TextDecoder().decode(bytes);
}
