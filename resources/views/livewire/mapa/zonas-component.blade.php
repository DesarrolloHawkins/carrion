<div>
    <h1>Mapa de Zonas</h1>
    
    @if($svgFile)
        <object type="image/svg+xml" data="{{ asset('svg/'.$svgFile) }}" id="svgObject"></object>
    @else
        <p>No se ha encontrado el SVG correspondiente.</p>
    @endif

    <script type="text/javascript">
        document.addEventListener("DOMContentLoaded", function() {
            var svgObject = document.getElementById("svgObject").contentDocument;

            // Seleccionar todos los paths con los data attributes
            var paths = svgObject.querySelectorAll('path[data-id][data-zona]');

            paths.forEach(function(path) {
                path.addEventListener("click", function() {
                    var dataId = this.getAttribute('data-id');
                    var dataZona = this.getAttribute('data-zona');

                    // Emitir evento Livewire con la zona seleccionada
                    Livewire.emit('selectZone', dataId, dataZona);
                });
            });
        });
    </script>
</div>