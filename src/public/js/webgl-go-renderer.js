var rootId = '';
var eventSent = false;
var currentMillis = 0;
var arrCachedViews = {};
var arrClientRenderings = [];
const elementsMap = new Map();

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
			rendered: allIdsFromMap(),
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
					renderComponents(json, 'glCanvas');
					eventSent = false;
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

function allIdsFromMap() {
	return Array.from(elementsMap.keys());
}

function createComponent(data, mainContainer) {
	Object.entries(data).forEach(([id, component]) => {
		if (elementsMap.has(id)) return

		if (id === 'actives' || id == 'elapsed' || id == 'root' || id == 'deactives') return;

		let element;

		switch (component.type) {
			case 'container':
				element = document.createElement('div');
				element.className = component.layout || 'vertical';
				if (component.width) element.style.width = component.width;
				if (component.height) element.style.height = component.height;
				if (component.image) {
					element.style.backgroundImage = `url(res/${component.image})`;
					element.style.backgroundSize = 'cover';
					element.style.backgroundPosition = 'center';
				}
				break;

			case 'label':
				element = document.createElement('span');
				element.textContent = component.text;
				if (component.style) element.className = component.style;
				break;

			case 'button':
				element = document.createElement('button');
				element.textContent = component.text;
				if (component.event) {
					element.addEventListener('click', () => handleEvent(component.event));
				}
				break;

			case 'sound':
				element = document.createElement('audio');
				element.id = id;
				element.src = `res/${component.sound}`;
				element.autoplay = false;
				element.loop = component.loop;
				element.volume = component.volume;
				// add click event listener to play/pause audio to the parent element
				const parentElement = elementsMap.get(component.parent.toString());
				parentElement?.addEventListener('click', () => playAudio(element));
				break;
		}

		elementsMap.set(id, element);

		if (component.parent) {
			const parentElement = elementsMap.get(component.parent.toString());
			parentElement?.appendChild(element);
		} else {
			// Agregar al contenedor principal
			mainContainer.appendChild(element);
		}
	});
}

function handleEvent(eventType) {
	sendEvent(eventType);
}

function renderComponents(responseData, mainContainerName) {
	const mainContainer = document.getElementById(mainContainerName || 'main');
	if (!mainContainer) {
		console.error(`No se encontró el contenedor principal con id "${mainContainerName}"`);
		return;
	}
	let deactives = responseData.deactives;
	if (deactives) {
		deactives.forEach(id => {
			const element = elementsMap.get(id);
			element?.remove();
			elementsMap.delete(id);
		});
	}
	setStyles();
	createComponent(responseData, mainContainer);
}

function playAudio(audio) {
	if (audio instanceof HTMLAudioElement){
		if (audio.paused) {
			console.log('playing audio', audio.id);
			audio.play();
		} else {
			console.log('pausing audio', audio.id);
			audio.pause();
		}
	}
}

function setStyles() {
	addStyles({
		"#glCanvas": {
			"width": "100%",
			"max-width": "800px",
		},
		".vertical": {
			"display": "flex",
			"flex-direction": "column",
			"align-items": "center",
			"gap": "10px",
			"width": "100%"
		},
		".title": {
			"font-size": "48px",
			"font-weight": "bold",
			"color": "#007bff",
			"text-shadow": "2px 2px 2px rgba(0, 0, 0, 0.5)",
			"margin": "10px 0"
		},
		".paragraph": {
			"font-size": "20px",
			"font-weight": "normal",
			"margin": "5px 0",
			"color": "#fff",
			"text-shadow": "2px 2px 2px rgba(0, 0, 0, 0.5)"
		},
		"button": {
			"padding": "5px 10px",
			"background-color": "#007bff",
			"color": "white",
			"border": "none",
			"border-radius": "4px",
			"cursor": "pointer",
			"transition": "background-color 0.3s"
		},
		"button:hover": {
			"background-color": "#0056b3"
		}
	});
}

function addStyles(styles) {
	let styleSheet = document.getElementById("dynamic-styles");

	// Si no existe el <style>, lo creamos y lo agregamos al <head>
	if (!styleSheet) {
		styleSheet = document.createElement("style");
		styleSheet.id = "dynamic-styles";
		document.head.appendChild(styleSheet);
	}

	// Convertimos el objeto de estilos en reglas CSS y las agregamos al <style>
	let cssText = "";
	for (const selector in styles) {
		if (styles.hasOwnProperty(selector)) {
			const rules = Object.entries(styles[selector])
				.map(([prop, value]) => `${prop}: ${value};`)
				.join(" ");
			cssText += `${selector} { ${rules} } `;
		}
	}

	styleSheet.textContent += cssText;
}
