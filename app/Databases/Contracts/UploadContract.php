<?php

namespace App\Databases\Contracts;

interface UploadContract
{
    public function upload(array $data): void;
}

