// Función para dibujar una imagen en coordenadas específicas usando WebGL
class WebGLImageDrawer {
    constructor(canvasId = 'glCanvas', width = 800, height = 600) {
        // Configurar el canvas y el contexto WebGL
        this.canvas = document.getElementById(canvasId);
        this.canvas.width = width;
        this.canvas.height = height;
        this.gl = this.canvas.getContext('webgl');

        if (!this.gl) {
            throw new Error('WebGL no está soportado en este navegador');
        }

        // Configurar los shaders para renderizar texturas
        this.initShaders();
    }

    initShaders() {
        // Vertex shader
        const vsSource = `
            attribute vec2 a_position;
            attribute vec2 a_texCoord;
            varying vec2 v_texCoord;

            void main() {
                gl_Position = vec4(a_position, 0.0, 1.0);
                v_texCoord = a_texCoord;
            }
        `;

        // Fragment shader
        const fsSource = `
            precision mediump float;
            uniform sampler2D u_image;
            varying vec2 v_texCoord;

            void main() {
                gl_FragColor = texture2D(u_image, v_texCoord);
            }
        `;

        // Crear shaders
        const vertexShader = this.compileShader(this.gl.VERTEX_SHADER, vsSource);
        const fragmentShader = this.compileShader(this.gl.FRAGMENT_SHADER, fsSource);

        // Crear programa
        this.program = this.gl.createProgram();
        this.gl.attachShader(this.program, vertexShader);
        this.gl.attachShader(this.program, fragmentShader);
        this.gl.linkProgram(this.program);

        if (!this.gl.getProgramParameter(this.program, this.gl.LINK_STATUS)) {
            throw new Error('No se pudo inicializar el programa shader');
        }
    }

    compileShader(type, source) {
        const shader = this.gl.createShader(type);
        this.gl.shaderSource(shader, source);
        this.gl.compileShader(shader);

        if (!this.gl.getShaderParameter(shader, this.gl.COMPILE_STATUS)) {
            console.error('Error al compilar shader:', this.gl.getShaderInfoLog(shader));
            this.gl.deleteShader(shader);
            throw new Error('Error al compilar shader');
        }

        return shader;
    }

    drawImage(imageUrl, x, y, width, height) {
        return new Promise((resolve, reject) => {
            const image = new Image();
            image.onload = () => {
                // Limpiar el canvas
                this.gl.clearColor(0, 0, 0, 0);
                this.gl.clear(this.gl.COLOR_BUFFER_BIT);

                // Usar el programa
                this.gl.useProgram(this.program);

                // Convertir coordenadas de píxeles a coordenadas normalizadas de WebGL
                const normalizedX = (x / this.canvas.width) * 2 - 1;
                const normalizedY = 1 - (y / this.canvas.height) * 2;
                const normalizedWidth = (width / this.canvas.width) * 2;
                const normalizedHeight = (height / this.canvas.height) * 2;

                // Crear buffer de posición
                const positionBuffer = this.gl.createBuffer();
                this.gl.bindBuffer(this.gl.ARRAY_BUFFER, positionBuffer);
                const positions = [
                    normalizedX, normalizedY,
                    normalizedX + normalizedWidth, normalizedY,
                    normalizedX, normalizedY - normalizedHeight,
                    normalizedX + normalizedWidth, normalizedY - normalizedHeight
                ];
                this.gl.bufferData(this.gl.ARRAY_BUFFER, new Float32Array(positions), this.gl.STATIC_DRAW);

                // Configurar atributo de posición
                const positionAttributeLocation = this.gl.getAttribLocation(this.program, 'a_position');
                this.gl.enableVertexAttribArray(positionAttributeLocation);
                this.gl.vertexAttribPointer(positionAttributeLocation, 2, this.gl.FLOAT, false, 0, 0);

                // Crear buffer de coordenadas de textura
                const texCoordBuffer = this.gl.createBuffer();
                this.gl.bindBuffer(this.gl.ARRAY_BUFFER, texCoordBuffer);
                const texCoords = [
                    0, 0,
                    1, 0,
                    0, 1,
                    1, 1
                ];
                this.gl.bufferData(this.gl.ARRAY_BUFFER, new Float32Array(texCoords), this.gl.STATIC_DRAW);

                // Configurar atributo de coordenadas de textura
                const texCoordAttributeLocation = this.gl.getAttribLocation(this.program, 'a_texCoord');
                this.gl.enableVertexAttribArray(texCoordAttributeLocation);
                this.gl.vertexAttribPointer(texCoordAttributeLocation, 2, this.gl.FLOAT, false, 0, 0);

                // Crear textura
                const texture = this.gl.createTexture();
                this.gl.bindTexture(this.gl.TEXTURE_2D, texture);

                // Configurar parámetros de textura
                this.gl.texParameteri(this.gl.TEXTURE_2D, this.gl.TEXTURE_WRAP_S, this.gl.CLAMP_TO_EDGE);
                this.gl.texParameteri(this.gl.TEXTURE_2D, this.gl.TEXTURE_WRAP_T, this.gl.CLAMP_TO_EDGE);
                this.gl.texParameteri(this.gl.TEXTURE_2D, this.gl.TEXTURE_MIN_FILTER, this.gl.LINEAR);
                this.gl.texParameteri(this.gl.TEXTURE_2D, this.gl.TEXTURE_MAG_FILTER, this.gl.LINEAR);

                // Cargar imagen en textura
                this.gl.texImage2D(this.gl.TEXTURE_2D, 0, this.gl.RGBA, this.gl.RGBA, this.gl.UNSIGNED_BYTE, image);

                // Dibujar
                this.gl.drawArrays(this.gl.TRIANGLE_STRIP, 0, 4);

                resolve();
            };

            image.onerror = () => {
                reject(new Error('No se pudo cargar la imagen'));
            };

            image.src = imageUrl;
        });
    }
}

// Ejemplo de uso
async function ejemploUso() {
    try {
        // Crear una instancia del dibujador WebGL
        const drawer = new WebGLImageDrawer();

        // Dibujar una imagen en las coordenadas x: 100, y: 200
        await drawer.drawImage('ruta/a/tu/imagen.jpg', 100, 200, 300, 200);
    } catch (error) {
        console.error('Error al dibujar la imagen:', error);
    }
}

// Exportar la clase para poder ser utilizada en otros módulos
export default WebGLImageDrawer;
