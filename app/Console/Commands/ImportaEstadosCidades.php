<?php

namespace App\Console\Commands;

use App\Databases\Models\Cidade;
use App\Databases\Models\Estado;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class ImportaEstadosCidades extends Command
{
    protected $signature = 'importa:estados-cidades';
    protected $description = 'Importa os estados e as cidades de Mato Grosso a partir da API do IBGE';

    public function handle()
    {
        $this->info('Iniciando a importação dos estados...');

        // Importar estados
        $responseEstados = Http::get('https://servicodados.ibge.gov.br/api/v1/localidades/estados');

        if ($responseEstados->successful()) {
            $estados = $responseEstados->json();

            foreach ($estados as $estado) {
                Estado::updateOrCreate(
                    ['sigla' => $estado['sigla']],
                    ['nome' => $estado['nome']]
                );
            }

            $this->info('Estados importados com sucesso.');
        } else {
            $this->error('Erro ao importar os estados.');
            return;
        }

        $this->info('Iniciando a importação das cidades de Mato Grosso...');

        // Pegar ID do estado de Mato Grosso
        $estadoMT = Estado::query()->where('sigla','=', 'MT')->first();
        if (!$estadoMT) {
            $this->error('Estado de Mato Grosso não encontrado.');
            return;
        }

        // Importar cidades de Mato Grosso
        $responseCidades = Http::get("https://servicodados.ibge.gov.br/api/v1/localidades/estados/51/municipios");

        if ($responseCidades->successful()) {
            $cidades = $responseCidades->json();

            foreach ($cidades as $cidade) {
                Cidade::updateOrCreate(
                    ['nome' => $cidade['nome'], 'estado_id' => $estadoMT->id],
                    ['nome' => $cidade['nome']]
                );
            }

            $this->info('Cidades de Mato Grosso importadas com sucesso.');
        } else {
            $this->error('Erro ao importar as cidades de Mato Grosso.');
        }
    }
}
