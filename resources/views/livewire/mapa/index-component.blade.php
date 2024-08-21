    <div>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/ol@v10.0.0/ol.css">
        <script src="https://cdn.jsdelivr.net/npm/ol@v10.0.0/dist/ol.js"></script>
        <style>
            .map {
                height: 600px;
                width: 600px;
            }
        </style>
    <div id="map" class="map"></div>
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
            style: function(feature) {
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
    
        // Añadir interacción para redirigir a otra página al hacer clic en una zona
        map.on('singleclick', function(evt) {
            map.forEachFeatureAtPixel(evt.pixel, function(feature) {
                var zona = feature.get('zona');
                // Redirigir a la página correspondiente (puedes modificar la URL según tus necesidades)
                window.location.href = '/detalles-zona?nombre=' + encodeURIComponent(zona);
            });
        });
    </script>
    </div>