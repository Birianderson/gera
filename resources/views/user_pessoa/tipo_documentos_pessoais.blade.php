<x-layout.principal>
    <div class="mt-3">
        <div class="d-none d-md-block d-flex justify-content-between align-items-center mb-3">
            <h2 class="text-primary mb-0">
                Meus Documentos
            </h2>
        </div>

        <div class="d-md-none mb-3 p-2">
            <a class="text-black" href="/user/pessoa/documentos"><i class="fa fa-arrow-alt-circle-left text-start text-primary"></i>
            Voltar
            </a>
            <h4 class="bg-primary rounded-3 text-center  mt-2 text-white p-2">
                MEUS DOCUMENTOS : {{$tipo_documento_nome}}
            </h4>
        </div>

        <div class="card">
            <div class="card-body">
                <!-- Grid para desktop -->
                <div class="d-none d-md-block">
                    <user-solicitacao-grid></user-solicitacao-grid>
                </div>

                <!-- Grid para mobile -->
                <div class="d-md-none">
                    <div> <i class="fa fa-info-circle"> </i> Envie os Documentos para análise</div>
                    <br>
                    <div> <i class="fa fa-info-circle"> </i> Apenas 1 documento pode ser enviado por vez</div>
                    <br>
                    <mobile-tipo-documento-user-pessoa data="{{$tipo_documento_id}}"></mobile-tipo-documento-user-pessoa>
                </div>
            </div>
        </div>
    </div>
</x-layout.principal>
