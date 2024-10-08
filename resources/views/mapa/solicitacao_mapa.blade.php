<x-layout.principal>
    <div class="mt-3">
        <div class="d-none d-md-block d-flex justify-content-between align-items-center mb-3">
            <h2 class="text-primary mb-0">
                Verficação de Localização
            </h2>
        </div>

        <div class="d-md-none text-center mb-3 p-2">
            <h4 class="bg-primary rounded-3 text-white p-2">
                VERIFICAÇÃO DE LOCALIZAÇÃO
            </h4>

        </div>

        <div class="card">
            <div class="card-body">
                <div> <i class="fa fa-info-circle"> </i> Utilize o mapa abaixo para verificar se sua localização está dentro dos limites do imóvel.</div>
                <br>
                <div> <i class="fa fa-info-circle"> </i> Clique no icone apenas quando você estiver dentro da área definida para liberar o cadastro.</div>
                <br>
                <solicitacao-mapa data="{{$imovel_id}}"></solicitacao-mapa>
            </div>
        </div>
    </div>
</x-layout.principal>
