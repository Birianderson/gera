<x-layout.principal>
    <div class="mt-3">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="text-primary mb-0">
                Minha Conta
            </h2>
        </div>

        <div class="card">
            <div class="card-body">
                <user-pessoa-form data="{{$cpf}}">
                </user-pessoa-form>
            </div>
        </div>
    </div>
</x-layout.principal>
