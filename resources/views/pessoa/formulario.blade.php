<x-layout.principal>
    <div class="mt-3">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="bg-danger text-white p-3 rounded-3 mb-0">
                <i class="fa fa-warning"></i>
                Enviando esse formulario voce está de acordo com o processo de Regularização Fundiaria da Cidade de {{$imovel->cidade->nome}}, no Loteamento de {{$imovel->loteamento->nome}}, de Quadra {{$imovel->quadra}} e Lote {{$imovel->lote}}
            </h2>

        </div>

        <div class="card">
            <div class="card-body">
                <form-externo-pessoa data="{{$imovel->id}}">
                </form-externo-pessoa>
            </div>
        </div>
    </div>
</x-layout.principal>
