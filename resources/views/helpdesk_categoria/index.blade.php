<x-layout.principal>
    <div class="mt-3">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="text-primary mb-0">
                Helpdesk Categoria
            </h2>
            <div class="d-flex">
                <div class="ms-2">
                    <popup-button id="nova-helpdesk_categoria" title="Nova Categoria do Helpdesk"
                                  component="helpdesk-categoria-form" action="/helpdesk_categoria/" size="md">
                        <i class="fa fa-plus"></i>
                        Nova Categoria
                    </popup-button>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <helpdesk-categoria-grid >
                </helpdesk-categoria-grid>
            </div>
        </div>
    </div>
</x-layout.principal>
