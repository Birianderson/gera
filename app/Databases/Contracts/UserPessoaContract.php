<?php

namespace App\Databases\Contracts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

interface UserPessoaContract
{

    public function getById(int $id): Model;
    public function create(array $params, bool $autoCommit = true) : bool;
    public function update( array $params, bool $autoCommit = true): bool;
    public function destroy(int $id, bool $autoCommit = true): bool;
    public function saveOrder(array $data): void;
}

