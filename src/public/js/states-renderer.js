var eventSent = false;
var currentMillis = 0;
var arrObjects = [];

window.onload = function () {
    arrObjects = [];
    sendEvent();
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

function sendEvent(event, formData = {}) {
    if (eventSent) {
        return;
    }
    eventSent = true;
    currentMillis = Date.now();
    const keyParent = findParentWithKey(document.activeElement);
    const source = keyParent ? keyParent.getAttribute('key') : null;

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
            source: source,
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
                    elementsUpdated = 0;
                    updated = "";
                    for (const key in json) {
                        const element = document.getElementById(key);
                        if (element) {
                            $html = decodeBase64(json[key]);
                            $html = '<div key="' + key + '">' + $html + '</div>';
                            element.innerHTML = $html;
                            runScripts(element);
                            elementsUpdated++;
                            updated += key + ", ";
                            if (!arrObjects.includes(key)) {
                                arrObjects.push(key);
                            }
                        } else {
                            console.error('Element not found: ' + key);
                        }
                    }
                    eventSent = false;
                    console.info('Rendered: ' + elementsUpdated + " in " + (Date.now() - currentMillis) + 'ms');
                    // console.info('Current: ' + arrObjects);
                    if (elementsUpdated > 0 && elementsUpdated < 10) {
                        //console.info('Updated: ' + updated);
                    }
                }
            } catch (error) {
                document.write(data);
                eventSent = false;
            }
        });
}

//todo: move to a helper file and optimize it
function decodeBase64(data) {
    const binaryString = atob(data);
    return binaryString;
    const bytes = new Uint8Array(binaryString.length);
    console.log(binaryString.length);
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
