<div>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/ol@v10.0.0/ol.css">
    <script src="https://cdn.jsdelivr.net/npm/ol@v10.0.0/dist/ol.js"></script>
    <style>
        .map {
            height: 800px;
            width: 95%;
        }
    </style>
    <div id="map" class="map"></div>
    <div class="controls">
        <button onclick="captureAllZones()">Capturar Todas las Zonas</button>
    </div>
    <script type="text/javascript">
        var map = new ol.Map({
            target: 'map',
            layers: [
                new ol.layer.Tile({
                    source: new ol.source.OSM()
                })
            ],
            view: new ol.View({
                center: ol.proj.fromLonLat([-6.1385, 36.6832]), // Coordenadas transformadas
                zoom: 17
            })
        });

        // Crear vector source vacío
        var vectorSource = new ol.source.Vector();

        // Cargar GeoJSON manualmente usando fetch
        fetch('/geojson/zonas2.geojson')
            .then(response => response.json())
            .then(data => {
                var features = new ol.format.GeoJSON().readFeatures(data, {
                    featureProjection: map.getView().getProjection() // Asegúrate de que la proyección sea la correcta
                });
                vectorSource.addFeatures(features);
            })
            .catch(error => console.error('Error cargando GeoJSON:', error));

        // Función para asignar colores según el nombre de la zona
        function getColor(zona) {
            switch (zona) {
                case '01- Asunción (Protocolo)': return 'rgba(255, 0, 0, 0.5)'; // Rojo
                case '02.- Consistorio': return 'rgba(0, 255, 0, 0.5)'; // Verde
                case '04.- Lancería-Gallo Azul': return 'rgba(0, 0, 255, 0.5)'; // Azul
                case '05.- Algarve-Plaza del Banco': return 'rgba(255, 255, 0, 0.5)'; // Amarillo
                case '06.- Rotonda de los Casinos-Santo Domingo': return 'rgba(0, 255, 255, 0.5)'; // Cian
                case '07.- Marqués de Casa Domecq': return 'rgba(255, 0, 255, 0.5)'; // Magenta
                case '08.- Eguiluz': return 'rgba(128, 0, 128, 0.5)'; // Púrpura
                case '03. Arenal': return 'rgba(128, 128, 0, 0.5)'; // Oliva
                default: return 'rgba(0, 0, 0, 0.5)'; // Negro por defecto
            }
        }

        // Crear vector layer con estilo dinámico basado en el nombre de la zona
        var vectorLayer = new ol.layer.Vector({
            source: vectorSource,
            style: function (feature) {
                return new ol.style.Style({
                    fill: new ol.style.Fill({
                        color: getColor(feature.get('zona'))
                    }),
                    stroke: new ol.style.Stroke({
                        color: 'black',
                        width: 2
                    })
                });
            }
        });

        map.addLayer(vectorLayer);

        // Captura todas las zonas acercando el mapa a cada una
        function captureAllZones() {
            var features = vectorSource.getFeatures();

            features.forEach((feature, index) => {
                setTimeout(() => {
                    // Acercar al centro del feature
                    var extent = feature.getGeometry().getExtent();
                    map.getView().fit(extent, { size: map.getSize(), maxZoom: 20 });

                    // Capturar el mapa después de que se ajuste la vista
                    setTimeout(() => {
                        captureMap(feature.get('zona'));
                    }, 1000); // Esperar 1 segundo para capturar la imagen después del zoom
                }, index * 2000); // Espaciar cada captura en 2 segundos
            });
        }
        // Añadir interacción para redirigir a otra página al hacer clic en una zona
        map.on('singleclick', function(evt) {
            map.forEachFeatureAtPixel(evt.pixel, function(feature) {
                var zona = feature.get('zona');

               let id = 0; 
               switch (zona) {
                    case '01- Asunción (Protocolo)': id = 8; break;
                    case '02.- Consistorio': id = 7; break;
                    case '04.- Lancería-Gallo Azul': id = 2; break;
                    case '05.- Algarve-Plaza del Banco': id = 6; break;
                    case '06.- Rotonda de los Casinos-Santo Domingo': id = 4; break;
                    case '07.- Marqués de Casa Domecq': id = 5; break;
                    case '08.- Eguiluz': id = 3; break;
                    case '03. Arenal': id = 1; break;
                default: break ; 
                }
                // Redirigir a la página correspondiente (puedes modificar la URL según tus necesidades)
                window.location.href = '/admin/detalles-zona?id=' + id;
            });
        }); 
        function captureMap(zonaName) {
            var mapCanvas = document.querySelector('canvas');
            var imageData = mapCanvas.toDataURL('image/png');

            // Enviar la imagen capturada con el nombre de la zona a Livewire
            Livewire.emit('captureMap', imageData, zonaName);
        }
    </script>
</div>
