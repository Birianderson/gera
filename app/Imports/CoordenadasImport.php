<?php

namespace App\Imports;

use App\Databases\Models\Coordenadas;
use App\Databases\Models\Imovel;
use App\Databases\Models\Pessoa;
use App\Databases\Models\VinculacaoImovelPessoas;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CoordenadasImport implements ToCollection, WithHeadingRow
{
    private $cidade;

    public function __construct(string $cidade)
    {
        $this->cidade = $cidade;
    }

    public function collection(Collection|\Illuminate\Support\Collection $rows): void
    {
        foreach ($rows as $row) {
            print($row);
        }
    }

    public function importKML(string $xmlContent): void
    {
        $xmlContent = str_replace('gx:', '', $xmlContent);
        $xml = simplexml_load_string($xmlContent);
        foreach ($xml->Document->Placemark as $placemark) {
            $longitude = '';
            $latitude = '';
            if (isset($placemark->Polygon->outerBoundaryIs->LinearRing->coordinates)) {
                $coordinates = trim((string)$placemark->Polygon->outerBoundaryIs->LinearRing->coordinates);
                $coordinatePairs = explode(' ', $coordinates);
                foreach ($coordinatePairs as $pair) {
                    $coordsArray = explode(',', $pair);
                    $longitude = $longitude . ',' . $coordsArray[0] ;
                    $latitude = $latitude . ',' . $coordsArray[1];
                }
                // Remover a primeira vírgula
                $longitude = ltrim($longitude, ',');
                $latitude = ltrim($latitude, ',');
                $coordenadas = new Coordenadas([
                    'municipio' => $this->cidade,
                    'lat' => $latitude,
                    'long' => $longitude,
                ]);
                $coordenadas->save();
            }
        }
    }
}
