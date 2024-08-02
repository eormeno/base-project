var eventSent = false;
var currentMillis = 0;
var arrObjects = [];

window.onload = function () {
    // if the url contains a query parameter 'reset' then reload the page
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('reset')) {
        localStorage.removeItem('rendered');
        // remove the 'reset' query parameter from the url
        window.history.replaceState({}, document.title, window.location.pathname);
        location.reload();
    } else {
        arrObjects = localStorage.getItem('rendered') || '{}';
        //arrObjects = JSON.parse(arrObjects);
        console.info(arrObjects);
        // if the arrObjects is {} then reload the page
        if (arrObjects === '{}') {
            sendEvent('reload', {}, true);
        }
    }
}

function findParentWithKey(element) {
    let parent = element.parentElement;
    while (parent) {
        if (parent.getAttribute('key')) {
            return parent;
        }
        parent = parent.parentElement;
    }
    return null;
}

function sendEvent(event, formData = {}, signal = false) {
    if (eventSent) {
        return;
    }
    eventSent = true;
    currentMillis = Date.now();
    const keyParent = findParentWithKey(document.activeElement);
    const source = keyParent ? keyParent.getAttribute('key') : null;
    let destination = source;
    if (signal) {
        destination = 'all';
    }

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
            is_signal: signal,
            source: source,
            destination: destination,
            data: formData,
            rendered: arrObjects
        })
    })
        .then(response => response.text())
        .then(data => {
            try {
                if (data.startsWith('<!DOCTYPE html>')) {
                    document.write(data);
                    eventSent = false;
                } else {
                    json = JSON.parse(data);
                    if (json['tree']) {
                        console.warn(json['tree']);
                    }
                    rootId = json['root'];
                    mainDiv = document.getElementById('main');
                    mainDiv.innerHTML = '<div id="' + rootId + '"></div>';
                    elementsUpdated = 0;
                    updated = "";
                    elementsNotFound = [];
                    for (const key in json) {
                        if (key === 'tree' || key === 'root') {
                            continue;
                        }
                        const element = document.getElementById(key);
                        if (element) {
                            $html = decodeBase64(json[key]);
                            $html = '<div key="' + key + '">' + $html + '</div>';
                            element.innerHTML = $html;
                            runScripts(element);
                            elementsUpdated++;
                            updated += key + ", ";
                            //if (!arrObjects.includes(key)) {
                                // pushes the key associated with the element to the array
                                arrObjects = {};

                                // pushes the key associated with the element to the array
                                arrObjects[key] = json[key];
                            //}
                        } else {
                            elementsNotFound.push(key);
                            // TODO: review this
                            //arrObjects = [];
                        }
                    }
                    eventSent = false;
                    console.info('Rendered: ' + elementsUpdated + " in " + (Date.now() - currentMillis) + 'ms');

                    // store the array of objects in local storage
                    localStorage.setItem('rendered', arrObjects);
                    //console.info('Current: ' + arrObjects);
                    if (elementsUpdated > 0 && elementsUpdated < 15) {
                        //console.info('Updated: ' + updated);
                    }
                    if (elementsNotFound.length > 0) {
                        console.error('Not found: ' + elementsNotFound);
                    }
                }
            } catch (error) {
                console.error(error);
                eventSent = false;
            }
        });
}

//todo: move to a helper file and optimize it
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

function runScripts(domElement) {
    domElement.querySelectorAll('script').forEach(script => {
        const newScript = document.createElement('script');
        Array.from(script.attributes).forEach(attr => {
            newScript.setAttribute(attr.name, attr.value);
        });
        newScript.appendChild(document.createTextNode(script.innerHTML));
        script.parentNode.replaceChild(newScript, script);
    });
}
