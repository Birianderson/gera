<x-layout.principal>
    <div class="mt-3">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="text-primary mb-0">
                Imoveis
            </h2>
            <div class="d-flex">
                <div class="ms-2">
                    <popup-button id="nova-Cidade" title="Nova Cidade"
                                  component="Cidade-form" action="/Cidade/" size="xl">
                        <i class="fa fa-plus"></i>
                        Novo imóvel
                    </popup-button>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <Cidade-grid>
                </Cidade-grid>
            </div>
        </div>
    </div>
</x-layout.principal>
