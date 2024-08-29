<?php

namespace App\Databases\Contracts;

use App\Databases\Models\Pessoa;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

interface ImovelContract
{

    public function paginate(array $pagination = [], array $columns = ['*']): LengthAwarePaginator;
    public function getAll();
    public function getById(int $id): Model;
    public function create(array $params, bool $autoCommit = true) : bool;
    public function update(int $id, array $params, bool $autoCommit = true): bool;
    public function destroy(int $id, bool $autoCommit = true): bool;
}

