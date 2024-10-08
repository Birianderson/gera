<?php

namespace App\Databases\Contracts;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

interface UserSolicitacaoMensagemContract
{
    public function paginate(array $pagination = [], array $columns = ['*']): LengthAwarePaginator;

    public function getById(int $id): Model;

    public function getArquivoById(int $id_arquivo): Model;

    public function create(array $params, bool $autoCommit = true): bool;

    public function mudarSituacao(array $params, bool $autoCommit = true): bool;

    public function getAllDocumentos(): Collection;
}
