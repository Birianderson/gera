<x-layout.principal>
    <div class="mt-3">
        <div class="d-none d-md-block d-flex justify-content-between align-items-center mb-3">
            <h2 class="text-primary mb-0">
                Solicitações
            </h2>
        </div>

        <div class="d-md-none text-center mb-3 p-2">
            <h4 class="bg-primary rounded-3 text-white p-2">
                SOLICITAÇÃO
            </h4>
        </div>
        <div class="card">
            <div class="">
                <!-- Grid para desktop -->
                <div class="d-none d-md-block">
                    <user-solicitacao-mensagem data="{{$id}}" user_id="{{$user_id}}"></user-solicitacao-mensagem>
                </div>

                <!-- Grid para mobile -->
                <div class="d-md-none">
                    <mobile-user-solicitacao-mensagem data="{{$id}}" user_id="{{$user_id}}"></mobile-user-solicitacao-mensagem>
                </div>
            </div>
        </div>
    </div>
</x-layout.principal>
