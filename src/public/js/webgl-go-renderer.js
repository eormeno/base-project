var rootId = '';
var eventSent = false;
var currentMillis = 0;
var arrCachedViews = {};
var arrClientRenderings = [];
var previousMillis = 500;
let pingAvgElement;
let pingMinElement;
let pingMaxElement;
let pingBackendElement;
let backendMaxElement;
let backendMinElement;
let lowPing = 1000;
let highPing = 0;
let backendMin = 1000;
let backendMax = 0;
const elementsMap = new Map();
const eventQueue = [];
const pings = [];
const backendMs = [];
let eventsAlreadyPending = [];
let resourceUrl = '';

window.onload = function () {
	pingAvgElement = document.getElementById('pingAvg');
	pingMinElement = document.getElementById('pingMin');
	pingMaxElement = document.getElementById('pingMax');
	pingBackendElement = document.getElementById('backendAvg');
	backendMinElement = document.getElementById('backendMin');
	backendMaxElement = document.getElementById('backendMax');
	resourceUrl = document.getElementById('routeDiv').getAttribute('resourceUrl');
	pushEvent('reload', {});
	pullWithTimeout(1000);
}

async function sendEvent(event, formData = {}) {
	if (eventSent) {
		return;
	}
	eventSent = true;
	currentMillis = Date.now();

	event = event || '';
	var routeDiv = document.getElementById('routeDiv');
	var route = routeDiv.getAttribute('route');
	var token = routeDiv.getAttribute('token');

	try {
		const response = await fetch(route, {
			method: 'POST',
			headers: {
				'Content-Type': 'application/json',
				'X-CSRF-TOKEN': token
			},
			body: JSON.stringify({
				event: event,
				data: formData,
				rendered: allIdsFromMapAndVersions(),
			})
		});

		const data = await response.text();

		if (data.startsWith('<')) {
			document.write(data);
		} else {
			const json = JSON.parse(data);
			let stringified = JSON.stringify(json);
			console.log(stringified);
			if (json.elapsed) {
				if (json.elapsed < backendMin) {
					backendMin = json.elapsed;
				}
				if (json.elapsed > backendMax) {
					backendMax = json.elapsed;
				}
				backendMs.push(json.elapsed);
				if (backendMs.length > 10) {
					backendMs.shift();
					const average = Math.round(backendMs.reduce((acc, curr) => acc + curr, 0) / backendMs.length);
					pingBackendElement.textContent = `${average} ms`;
					backendMinElement.textContent = `${backendMin} ms`;
					backendMaxElement.textContent = `${backendMax} ms`;
				}
			}
			renderComponents(json, 'glCanvas');
		}
	} catch (error) {
		console.error(error);
	} finally {
		eventSent = false;
	}
}

function allIdsFromMapAndVersions() {
	// return pairs of id and version { 1: 0, 2: 1, 3: 0 }
	return Array.from(elementsMap.entries()).reduce((acc, [id, element]) => {
		acc[id] = element.version;
		return acc;
	}, {});
}

function createComponent(data, mainContainer) {
	Object.entries(data).forEach(([id, component]) => {
		if (elementsMap.has(id)) {
			// find the element and update it
			const element = elementsMap.get(id);
			if (component.type === 'sprite') {
				let x = component.x - element.width * component.scale;
				let y = component.y - element.height * component.scale;
				element.style.left = x + 'px';
				element.style.top = y + 'px';
				element.style.transform = `scale(${component.scale}) rotate(${component.rotation}deg)`;
			}
			return;
		}

		if (id === 'actives' || id == 'elapsed' || id == 'root' || id == 'deactives') return;

		let element;

		switch (component.type) {
			case 'container':
				element = document.createElement('div');
				element.className = component.layout || 'vertical';
				if (component.width) element.style.width = component.width;
				if (component.height) element.style.height = component.height;
				if (component.image) {
					element.style.backgroundImage = `url(${resourceUrl}/${component.image})`;
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

			case 'sprite':
				element = document.createElement('img');
				element.src = `res/${component.texture}`;
				element.style.position = 'absolute';
				let x = component.x - element.width * component.scale;
				let y = component.y - element.height * component.scale;
				element.style.left = component.x + 'px';
				element.style.top = component.y + 'px';
				element.style.transform = `scale(${component.scale}) rotate(${component.rotation}deg)`;
				element.style.filter = `drop-shadow(5px 5px 5px rgba(0,0,0,0.5))`;
				break;

			case 'sound':
				element = document.createElement('audio');
				element.src = `res/${component.sound}`;
				element.autoplay = false;
				element.loop = component.loop;
				element.volume = component.volume;
				playAudio(element);
				// // add click event listener to play/pause audio to the parent element
				// const parentElement = elementsMap.get(component.parent.toString());
				// parentElement?.addEventListener('click', () => playAudio(element));
				break;
		}

		element.id = id;
		element.version = component.version;
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
	//sendEvent(eventType);
	pushEvent(eventType);
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
	if (audio instanceof HTMLAudioElement) {
		audio.play().catch(error => {
			// Simulate user interaction to play audio
			audio.play();
		});
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
			"position": "relative",
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

const pullWithTimeout = async (interval) => {

	const fetchData = async () => {
		try {
			if (!navigator.onLine) {
				console.error('Disconnected');
				return;
			}
			const startTime = Date.now();
			const { event, data } = dequeueEvent();
			if (event) {
				await sendEvent(event, data);
			}
			const endTime = Date.now();
			const elapsed = endTime - startTime;
			if (elapsed < lowPing) {
				lowPing = elapsed;
			}
			if (elapsed > highPing) {
				highPing = elapsed;
			}
			pings.push(elapsed);
			if (pings.length > 10) {
				pings.shift();
				const average = Math.round(pings.reduce((acc, curr) => acc + curr, 0) / pings.length);
				pingAvgElement.textContent = `${average} ms`;
				pingMinElement.textContent = `${lowPing} ms`;
				pingMaxElement.textContent = `${highPing} ms`;
			}
		} catch (error) {
			console.error('Error:', error);
		} finally {
			pushEvent('update', { 'delta': previousMillis });
			setTimeout(fetchData, interval);
		}
	};

	fetchData();
};

function pushEvent(event, data) {
	if (eventsAlreadyPending.includes(event)) {
		return;
	}
	eventsAlreadyPending.push(event);
	eventQueue.push({ event, data });
}

function dequeueEvent() {
	if (eventQueue.length === 0) {
		return null;
	}
	const { event, data } = eventQueue.shift();
	// Remove the event from the pending list
	const index = eventsAlreadyPending.indexOf(event);
	if (index > -1) {
		eventsAlreadyPending.splice(index, 1);
	}
	return { event, data };
}
