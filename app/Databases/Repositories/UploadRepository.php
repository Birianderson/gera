<?php
namespace App\Databases\Repositories;

use App\Databases\Contracts\UploadContract;
use App\Imports\TerrenosImport;
use Illuminate\Support\Facades\DB;
use Exception;
use Maatwebsite\Excel\Facades\Excel;

class UploadRepository implements UploadContract
{
    /**
     * @throws Exception
     */
    public function uploadTerrenos(array $data): void
    {
        DB::beginTransaction();
        try {
            Excel::import(new TerrenosImport, $data['file']);
            DB::commit();
        } catch (Exception $ex) {
            DB::rollBack();
            throw new Exception($ex->getMessage());
        }
    }

    /**
     * @throws Exception
     */
    public function uploadCoordenadas(array $data): void
    {
        DB::beginTransaction();
        try {
            Excel::import(new TerrenosImport, $data['file']);
            DB::commit();
        } catch (Exception $ex) {
            DB::rollBack();
            throw new Exception($ex->getMessage());
        }
    }

}
