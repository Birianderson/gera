<x-layout.principal>
    <div class="mt-3">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="text-primary mb-0">
                Documentos
            </h2>
            <div class="d-flex">
                <div class="ms-2">
                    <popup-button id="nova-Loteamento" title="Novo Tipo de arquivo"
                                  component="tipo-arquivo-form" action="/tipo_arquivo/" size="xl">
                        <i class="fa fa-plus"></i>
                        Novo Documento
                    </popup-button>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <tipo-arquivo-grid >
                </tipo-arquivo-grid>
            </div>
        </div>
    </div>
</x-layout.principal>
