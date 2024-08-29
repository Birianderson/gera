<x-layout.principal>
    <div class="mt-3">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="text-primary mb-0">
                Importação de Dados
            </h2>
        </div>

        <div class="row">
            <!-- Grid para Importar Terrenos -->
            <div class="col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h3 class="text-secondary mb-3">Importar Terrenos</h3>
                        <p class="text-muted mb-4">
                            Selecione o arquivo Excel contendo as informações dos terrenos. Certifique-se de que o arquivo segue o formato esperado.
                        </p>
                        <import-excel title="Importar Terrenos" upload-url="/imovel/upload-excel"></import-excel>
                    </div>
                </div>
            </div>

            <!-- Grid para Importar Coordenadas Geográficas -->
            <div class="col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h3 class="text-secondary mb-3">Importar Coordenadas Geográficas</h3>
                        <p class="text-muted mb-4">
                            Selecione o arquivo KML contendo as coordenadas geográficas dos terrenos. Esse arquivo será usado para mapear as localizações no sistema.
                        </p>
                        <import-excel title="Importar Coordenadas"></import-excel>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout.principal>
