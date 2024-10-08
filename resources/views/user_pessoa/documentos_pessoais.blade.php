<x-layout.principal>
    <div class="mt-3">
        <div class="d-none d-md-block d-flex justify-content-between align-items-center mb-3">
            <h2 class="text-primary mb-0">
                Meus Documentos
            </h2>
        </div>

        <div class="d-md-none text-center mb-3 p-2">
            <h4 class="bg-primary rounded-3 text-white p-2">
                MEUS DOCUMENTOS
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
                    <span> <i class="fa fa-info-circle"> </i> Para realizar o cadastro de Imóveis é necessário ter TODOS documentos pessoais tenham status:  <span class="bg-success text-white p-2 rounded-3">Aprovado</span></span>
                    <br>
                    <mobile-documentos-user-pessoa></mobile-documentos-user-pessoa>
                </div>
            </div>
        </div>
    </div>
</x-layout.principal>
