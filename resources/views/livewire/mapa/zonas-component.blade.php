<div>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <h1>Mapa de Zonas</h1>

    @if($svgFile)
        <div id="svgContainer">
            <object type="image/svg+xml" data="{{ asset('svg/'.$svgFile) }}" id="svgObject"></object>
            <div class="zoom-controls">
                <button id="zoomIn">+</button>
                <button id="zoomOut">-</button>
            </div>
        </div>
    @else
        <p>No se ha encontrado el SVG correspondiente.</p>
    @endif

    <script type="text/javascript">
        document.addEventListener("DOMContentLoaded", function () {
            var svgObject = document.getElementById("svgObject");

            svgObject.addEventListener('load', function () {
                var svgDoc = svgObject.contentDocument;
                var svgElement = svgDoc.documentElement;

                // Variables de Palcos y Gradas pasadas desde el backend
                var palcos = @json($palcos);
                var gradas = @json($gradas);

                var zoomLevel = 1;
                var zoomStep = 0.1;
                var maxZoom = 5;
                var minZoom = 0.5;

                var isPanning = false;
                var startPoint = {x: 0, y: 0};
                var panOffset = {x: 0, y: 0};

                // Función para actualizar la transformación del SVG
                function updateTransform() {
                    svgElement.style.transform = `scale(${zoomLevel}) translate(${panOffset.x}px, ${panOffset.y}px)`;
                }

                // Zoom in/out functionality
                document.getElementById('zoomIn').addEventListener('click', function () {
                    if (zoomLevel < maxZoom) {
                        zoomLevel += zoomStep;
                        updateTransform();
                    }
                });

                document.getElementById('zoomOut').addEventListener('click', function () {
                    if (zoomLevel > minZoom) {
                        zoomLevel -= zoomStep;
                        updateTransform();
                    }
                });

                // Zoom with mouse scroll
                svgElement.addEventListener('wheel', function (e) {
                    e.preventDefault();
                    if (e.deltaY < 0) {
                        if (zoomLevel < maxZoom) {
                            zoomLevel += zoomStep;
                        }
                    } else {
                        if (zoomLevel > minZoom) {
                            zoomLevel -= zoomStep;
                        }
                    }
                    updateTransform();
                });

                // Pan functionality (dragging)
                svgElement.addEventListener('mousedown', function (e) {
                    isPanning = true;
                    startPoint = {x: e.clientX, y: e.clientY};
                });

                svgElement.addEventListener('mousemove', function (e) {
                    if (isPanning) {
                        var dx = (e.clientX - startPoint.x) / zoomLevel;
                        var dy = (e.clientY - startPoint.y) / zoomLevel;

                        panOffset.x += dx;
                        panOffset.y += dy;

                        updateTransform();

                        startPoint = {x: e.clientX, y: e.clientY};
                    }
                });

                svgElement.addEventListener('mouseup', function () {
                    isPanning = false;
                });

                svgElement.addEventListener('mouseleave', function () {
                    isPanning = false;
                });
                console.log(palcos)

                // Colorear palcos y gradas completos
                palcos.forEach(function (palco) {
                    if (palco.completo) {
                        var palcoElement = svgDoc.querySelector('path[data-id="' + palco.id + '"]');
                        if (palcoElement) {
                            palcoElement.style.fill = 'red'; // Cambia el color a rojo
                        }
                    }
                });
                console.log(gradas)
                gradas.forEach(function (grada) {
                    console.log(grada)
                    if (grada.id == 8) {
                        
                    }
                    if (grada.completo) {
                        var gradaElement = svgDoc.querySelector('path[data-id="' + grada.id + '"]');
                        if (gradaElement) {
                            gradaElement.style.fill = 'red'; // Cambia el color a rojo
                        }
                    }
                });

                // Hacer clic en una zona para seleccionar
                var paths = svgDoc.querySelectorAll('path[data-id][data-zona]');
                paths.forEach(function (path) {

                    

                    path.addEventListener("click", function () {
                        var dataId = this.getAttribute('data-id');
                        var dataZona = this.getAttribute('data-zona');
                        var dataType = this.getAttribute('data-type');
                        var dataSector = this.getAttribute('data-sector');
                        console.log(dataId)

                        Livewire.emit('selectZone', dataId, dataZona, dataType, dataSector);
                    });
                });
            });
        });


        document.addEventListener('DOMContentLoaded', function () {
    var svgObject = document.getElementById('svgObject');

    // Verificamos que el objeto SVG está presente
    if (svgObject) {
        svgObject.addEventListener('load', function () {
            var svgDoc = svgObject.contentDocument;

            if (svgDoc) {
                var paths = svgDoc.querySelectorAll('path[data-id][data-zona]');

                // Para cada path del SVG, hacemos una petición al backend
                paths.forEach(function (path) {
                    var dataId = path.getAttribute('data-id');
                    var dataZona = path.getAttribute('data-zona');
                    var dataType = path.getAttribute('data-type');

                    // Crear el objeto que enviaremos al servidor
                    var data = {
                        '_token': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'data-id': dataId,
                        'data-zona': dataZona,
                        'data-type': dataType,
                    };

                    // Hacemos una petición POST al controlador
                    fetch('/check-zona-completa', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') // Necesitas asegurarte de tener el token CSRF en la vista
                        },
                        body: JSON.stringify(data),
                    })
                    .then(response => response.json())
                    .then(result => {
                        console.log('Result:', result); // Para depuración

                        // Colorear el path según si está completo o no
                        if (result.completo) {
                            path.style.fill = 'red'; // Colorea en rojo si está completo
                        } else {
                            path.style.fill = 'green'; // O en verde si no está completo
                        }
                    })
                    .catch(error => console.error('Error:', error));
                });
            } else {
                console.error('No se pudo acceder al documento SVG.');
            }
        });
    } else {
        console.error('No se encontró el elemento con el ID "svgMap".');
    }
});




    </script>

    <style>
        #svgContainer {
            width: 100%;
            height: 600px;
            overflow: hidden;
            position: relative;
            border: 1px solid #ddd;
            cursor: grab; /* Cambia el cursor a una mano abierta */
        }

        #svgContainer:active {
            cursor: grabbing; /* Cambia el cursor a una mano cerrada cuando se está arrastrando */
        }

        #svgObject {
            width: 100%;
            height: 100%;
            transform-origin: center center;
        }

        .zoom-controls {
            position: absolute;
            top: 10px;
            background-color: rgba(255, 255, 255, 0.8);
            border-radius: 5px;
            padding: 5px;
            z-index: 99;
        }

        .zoom-controls button {
            font-size: 18px;
            padding: 5px 10px;
            margin: 2px;
            cursor: pointer;
            z-index: 99;
        }
    </style>
</div>
