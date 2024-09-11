<x-layout.principal>
    <div class="mt-3">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="text-primary mb-0">
                Imoveis
            </h2>
            <div class="d-flex">
                <div class="ms-2">
                    <popup-button id="nova-imovel" title="Nova imovel"
                                  component="imovel-form" action="/imovel/" size="xl">
                        <i class="fa fa-plus"></i>
                        Novo imóvel
                    </popup-button>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <imovel-grid>
                </imovel-grid>
            </div>
        </div>
    </div>
</x-layout.principal>
