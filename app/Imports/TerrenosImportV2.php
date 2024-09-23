<?php

namespace App\Imports;

use App\Databases\Models\Imovel;
use App\Databases\Models\Pessoa;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class TerrenosImportV2 implements ToCollection, WithHeadingRow
{
    private $loteamento_id;

    public function __construct(string $loteamento_id)
    {
        $this->loteamento_id = $loteamento_id;
    }

    public function collection(Collection|\Illuminate\Support\Collection $rows): void
    {
        $estadoCivilMap = $this->getEstadoCivilMap();

        foreach ($rows as $row) {
            if ($row['cpf'] && $row['cpf'] !== null) {
                $cpf = $this->cleanCpf($row['cpf']);
                if (!$this->pessoaExists($cpf)) {
                    $pessoa = $this->createPessoa($row->toArray(), $estadoCivilMap);
                    $this->handleConjuge($pessoa, $row->toArray(), $estadoCivilMap);
                    $this->createImovel($row->toArray(), $pessoa->id);
                } else {
                    $pessoa_existente = Pessoa::query()->where('cpf', $cpf)->first();
                    $this->createImovel($row->toArray(), $pessoa_existente->id);
                }
            } else {
                $this->createImovel($row->toArray());
            }
        }
    }

    private function getEstadoCivilMap(): array
    {
        return [
            'CASADO(A)' => 'C', 'CASADO' => 'C', 'CASADA' => 'C',
            'SOLTEIRO(A)' => 'O', 'SOLTEIRO' => 'O', 'SOLTEIRA' => 'O',
            'DIVORCIADO(A)' => 'D', 'DIVORCIADO' => 'D', 'DIVORCIADA' => 'D',
            'SEPARADO(A)' => 'E', 'SEPARADO' => 'E', 'SEPARADA' => 'E',
            'UNIÃO ESTÁVEL' => 'U', 'UNIAO ESTAVEL' => 'U',
            'VIÚVO(A)' => 'V', 'VIÚVO' => 'V', 'VIÚVA' => 'V',
            'VIUVO' => 'V', 'VIUVA' => 'V',
        ];
    }

    private function cleanCpf(?string $cpf): ?string
    {
        return $cpf ? preg_replace('/[^0-9]/', '', $cpf) : null;
    }

    private function pessoaExists(string $cpf): bool
    {
        return Pessoa::query()->where('cpf', $cpf)->exists();
    }

    private function cleanString(string $string): string
    {
        return strtr(strtoupper(trim($string)), [
            'Á' => 'A', 'É' => 'E', 'Í' => 'I', 'Ó' => 'O', 'Ú' => 'U',
            'Ã' => 'A', 'Õ' => 'O', 'Ê' => 'E', 'Ô' => 'O', 'Ç' => 'C'
        ]);
    }

    private function createPessoa(array $row, array $estadoCivilMap): Pessoa
    {
        $estadoCivil = isset($row['estado_civil']) ? $this->cleanString($row['estado_civil']) : 'O';

        $pessoa = new Pessoa([
            'nome' => $row['nome_do_morador'],
            'profissao' => $row['profissao'] ?? '',
            'estado_civil' => $estadoCivilMap[$estadoCivil] ?? null,
            'regime_casamento' => $row['regime_de_casamento'] ?? null,
            'uniao_estavel' => $row['uniao_estavel'] ?? null,
            'nome_pai' => $row['pai'] ?? '',
            'nome_mae' => $row['mae'] ?? '',
            'cpf' => $this->cleanCpf($row['cpf']),
            'registro_geral' => isset($row['rg']) ? $this->cleanCpf($row['rg']) : '',
            'orgao_expedidor' => $row['orgao_expedidor'] ?? '',
            'nacionalidade' => $row['nacionalidade'] ?? '',
            'falecida' => 0,
        ]);

        $pessoa->save();
        return $pessoa;
    }

    private function handleConjuge(Pessoa $pessoa, array $row, array $estadoCivilMap): void
    {
        if (in_array($pessoa->estado_civil, ['C', 'U', 'V'])) {
            $cpfConjuge = $this->cleanCpf($row['cpf_conjuge_ou_companheiro'] ?? $row['cpf_conjuge_ou_companheiro_falecido'] ?? null);
            if ($cpfConjuge) {
                $conjuge = Pessoa::query()->where('cpf', $cpfConjuge)->first();

                if (!$conjuge) {
                    $conjuge = $this->createConjuge($row, $pessoa, $estadoCivilMap);
                }

                $pessoa->update(['conjuge_id' => $conjuge->id]);
                $conjuge->update(['conjuge_id' => $pessoa->id]);
            }
        }
    }

    private function createConjuge(array $row, Pessoa $pessoa, array $estadoCivilMap): Pessoa
    {
        $estadoCivil = isset($row['estado_civil']) ? $this->cleanString($row['estado_civil']) : 'O';

        $conjuge = new Pessoa([
            'nome' => $row['conjuge_ou_companheiro'] ?? $row['conjuge_ou_companheiro_falecido'] ?? '',
            'conjuge_id' => $pessoa->id,
            'estado_civil' => $estadoCivilMap[$estadoCivil] ?? null,
            'regime_casamento' => $row['regime_de_casamento'] ?? null,
            'uniao_estavel' => $row['uniao_estavel'] ?? null,
            'cpf' => $this->cleanCpf($row['cpf_conjuge_ou_companheiro'] ?? $row['cpf_conjuge_ou_companheiro_falecido'] ?? null),
            'nacionalidade' => $row['nacionalidade'] ?? '',
            'falecida' => isset($row['conjuge_ou_companheiro_falecido']) ? 1 : 0,
        ]);

        $conjuge->save();
        return $conjuge;
    }

    private function createImovel(array $row, ?int $pessoa_id = null): void
    {
        $status = $pessoa_id === null ? 'S' : 'A';
        $existingImovel = Imovel::query()
            ->where('loteamento_id', $this->loteamento_id)
            ->where('quadra', $row['quadra'])
            ->where('lote', $row['lote'])
            ->first();

        // Se não existir, cria um novo
        if (!$existingImovel) {
            $imovel = new Imovel([
                'loteamento_id' => $this->loteamento_id,
                'pessoa_id' => $pessoa_id,
                'data_escritura' => $row['data_da_escritura'] ?? '',
                'quadra' => $row['quadra'],
                'lote' => $row['lote'],
                'inscricao_imobiliaria' => $row['inscricao_imobiliaria'] ?? null,
                'matricula_reurb' => $row['matricula_reurb'] ?? '',
                'area' => $row['area_m2'] ?? '',
                'perimetro' => ' ',
                'status' => $status,
                'medida_frente' => $row['medida_frente'] ?? '',
                'medida_fundo' => $row['medida_fundo'] ?? '',
                'medida_direita' => $row['medida_lado_direito'] ?? '',
                'medida_esquerda' => $row['medida_lado_esquerdo'] ?? '',
                'confinante_frente' => $row['confinante_frente'] ?? ' ',
                'confinante_fundo' => $row['confinante_fundo'] ?? '',
                'confinante_direita' => $row['confinante_lado_direito'] ?? '',
                'confinante_esquerda' => $row['confinante_lado_esquerdo'] ?? '',
            ]);

            $imovel->save();
        }
    }
}
