<div>
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
        document.addEventListener("DOMContentLoaded", function() {
            var svgObject = document.getElementById("svgObject").contentDocument;
            var svgElement = svgObject.documentElement;

            var zoomLevel = 1;
            var zoomStep = 0.1;
            var maxZoom = 5; // Zoom máximo
            var minZoom = 0.5; // Zoom mínimo

            var isPanning = false;
            var startPoint = { x: 0, y: 0 };
            var panOffset = { x: 0, y: 0 };

            // Función para actualizar la transformación del SVG
            function updateTransform() {
                svgElement.style.transform = `scale(${zoomLevel}) translate(${panOffset.x}px, ${panOffset.y}px)`;
            }

            document.getElementById('zoomIn').addEventListener('click', function() {
                if (zoomLevel < maxZoom) {
                    zoomLevel += zoomStep;
                    updateTransform();
                }
            });

            document.getElementById('zoomOut').addEventListener('click', function() {
                if (zoomLevel > minZoom) {
                    zoomLevel -= zoomStep;
                    updateTransform();
                }
            });

            // Añadir zoom con scroll del ratón
            svgElement.addEventListener('wheel', function(e) {
                e.preventDefault();
                if (e.deltaY < 0) {
                    // Scroll hacia arriba (zoom in)
                    if (zoomLevel < maxZoom) {
                        zoomLevel += zoomStep;
                    }
                } else {
                    // Scroll hacia abajo (zoom out)
                    if (zoomLevel > minZoom) {
                        zoomLevel -= zoomStep;
                    }
                }
                updateTransform();
            });

            svgElement.addEventListener('mousedown', function(e) {
                isPanning = true;
                startPoint = { x: e.clientX, y: e.clientY };
            });

            svgElement.addEventListener('mousemove', function(e) {
                if (isPanning) {
                    var dx = (e.clientX - startPoint.x) / zoomLevel;
                    var dy = (e.clientY - startPoint.y) / zoomLevel;

                    panOffset.x += dx;
                    panOffset.y += dy;

                    updateTransform();

                    startPoint = { x: e.clientX, y: e.clientY };
                }
            });

            svgElement.addEventListener('mouseup', function() {
                isPanning = false;
            });

            svgElement.addEventListener('mouseleave', function() {
                isPanning = false;
            });

            // Soporte para dispositivos móviles
            var initialDistance = 0;
            var initialZoomLevel = zoomLevel;

            svgElement.addEventListener('touchstart', function(e) {
                if (e.touches.length === 2) {
                    isPanning = false;
                    initialDistance = Math.hypot(
                        e.touches[0].clientX - e.touches[1].clientX,
                        e.touches[0].clientY - e.touches[1].clientY
                    );
                    initialZoomLevel = zoomLevel;
                } else if (e.touches.length === 1) {
                    isPanning = true;
                    startPoint = { x: e.touches[0].clientX, y: e.touches[0].clientY };
                }
            });

            svgElement.addEventListener('touchmove', function(e) {
                if (e.touches.length === 2) {
                    var newDistance = Math.hypot(
                        e.touches[0].clientX - e.touches[1].clientX,
                        e.touches[0].clientY - e.touches[1].clientY
                    );
                    zoomLevel = initialZoomLevel * (newDistance / initialDistance);
                    if (zoomLevel > maxZoom) zoomLevel = maxZoom;
                    if (zoomLevel < minZoom) zoomLevel = minZoom;
                    updateTransform();
                } else if (e.touches.length === 1 && isPanning) {
                    var dx = (e.touches[0].clientX - startPoint.x) / zoomLevel;
                    var dy = (e.touches[0].clientY - startPoint.y) / zoomLevel;

                    panOffset.x += dx;
                    panOffset.y += dy;

                    updateTransform();

                    startPoint = { x: e.touches[0].clientX, y: e.touches[0].clientY };
                }
            });

            svgElement.addEventListener('touchend', function() {
                isPanning = false;
            });

            var paths = svgObject.querySelectorAll('path[data-id][data-zona]');

            paths.forEach(function(path) {
                path.addEventListener("click", function() {
                    var dataId = this.getAttribute('data-id');
                    var dataZona = this.getAttribute('data-zona');
                    var dataType = this.getAttribute('data-type');
                    var dataSector = this.getAttribute('data-sector');

                    Livewire.emit('selectZone', dataId, dataZona, dataType, dataSector);
                });
            });
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
            z-index: 99; /* Asegura que los controles estén por encima del SVG */
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
