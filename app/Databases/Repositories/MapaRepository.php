<?php
namespace App\Databases\Repositories;

use App\Databases\Contracts\UploadContract;
use App\Imports\TerrenosImport;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Exception;
use Maatwebsite\Excel\Facades\Excel;

class MapaRepository implements UploadContract
{
    /**
     * @throws Exception
     */
    public function upload(array $data): void
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
