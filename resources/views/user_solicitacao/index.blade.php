<x-layout.principal>
    <div class="mt-3">
        <div class="d-none d-md-block d-flex justify-content-between align-items-center mb-3">
            <h2 class="text-primary mb-0">
                Solicitações
            </h2>
        </div>

        <div class="d-md-none text-center mb-3 p-2">
            <h4 class="bg-primary rounded-3 text-white p-2">
                MINHAS SOLICITACOES
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
                    <mobile-user-solicitacao-grid></mobile-user-solicitacao-grid>
                </div>
            </div>
        </div>
    </div>
</x-layout.principal>
