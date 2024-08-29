<?php

namespace App\Databases\Contracts;

interface UploadContract
{
    public function uploadTerrenos(array $data): void;
    public function uploadCoordenadas(array $data): void;
}

