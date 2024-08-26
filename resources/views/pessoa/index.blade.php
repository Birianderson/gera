<x-layout.principal>
    <div class="mt-3">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="text-primary mb-0">
                Pessoas
            </h2>
            <div class="d-flex">
                <div class="ms-2">
                    <popup-button id="nova-pessoa" title="Nova Pessoa"
                                  component="pessoa-form" action="/pessoa/" size="lg">
                        <i class="fa fa-plus"></i>
                        Nova Pessoa
                    </popup-button>
                </div>
            </div>

        </div>

        <div class="card">
            <div class="card-body">
                <pessoa-grid>
                </pessoa-grid>
            </div>
        </div>
    </div>
</x-layout.principal>
