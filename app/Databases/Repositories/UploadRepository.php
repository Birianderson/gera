<?php
namespace App\Databases\Repositories;

use App\Databases\Contracts\UploadContract;
use App\Imports\CoordenadasImport;
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
            Excel::import(new TerrenosImport($data['cidade']), $data['file']);
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
            $extension = $data['file']->getClientOriginalExtension();

            if ($extension === 'kml') {
                $this->processKML($data['file'], $data['cidade']);
            } else {
                Excel::import(new CoordenadasImport($data['cidade']), $data['file']);
            }

            DB::commit();
        } catch (Exception $ex) {
            DB::rollBack();
            throw new Exception($ex->getMessage());
        }
    }

    /**
     * Processar arquivo KML
     * @throws Exception
     */
    protected function processKML($file, string $cidade): void
    {
        $xmlContent = file_get_contents($file);
        $importer = new CoordenadasImport($cidade);
        $importer->importKML($xmlContent);
    }

}
