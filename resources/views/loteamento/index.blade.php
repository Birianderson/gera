<x-layout.principal>
    <div class="mt-3">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="text-primary mb-0">
                Loteamentos da {{$cidade_nome}}
            </h2>
            <div class="d-flex">
                <div class="ms-2">
                    <popup-button id="nova-Loteamento" title="Nova Loteamento" data="{{$cidade_id}}"
                                  component="loteamento-form" action="/Loteamento/" size="xl">
                        <i class="fa fa-plus"></i>
                        Novo loteamento
                    </popup-button>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <loteamento-grid cidade_id="{{$cidade_id}}">
                </loteamento-grid>
            </div>
        </div>
    </div>
</x-layout.principal>
