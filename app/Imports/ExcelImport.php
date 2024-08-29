<?php

namespace App\Imports;

use App\Databases\Models\Imovel;
use App\Databases\Models\Pessoa;
use App\Databases\Models\VinculacaoImovelPessoas;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ExcelImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection|\Illuminate\Support\Collection $rows)
    {
        $estadoCivilMap = [
            'CASADO(A)' => 'C',
            'CASADO' => 'C',
            'CASADA' => 'C',
            'SOLTEIRO(A)' => 'O',
            'SOLTEIRO' => 'O',
            'SOLTEIRA' => 'O',
            'DIVORCIADO(A)' => 'D',
            'DIVORCIADO' => 'D',
            'DIVORCIADA' => 'D',
            'SEPARADO(A)' => 'E',
            'SEPARADO' => 'E',
            'SEPARADA' => 'E',
            'UNIÃO ESTÁVEL' => 'U',
            'UNIAO ESTAVEL' => 'U',
            'VIÚVO(A)' => 'V',
            'VIÚVO' => 'V',
            'VIÚVA' => 'V',
            'VIUVO' => 'V',
            'VIUVA' => 'V',
        ];
        foreach ($rows as $row) {
            if (isset($row['1_nome']) && $row['1_nome'] !== null) {
                $cpf = preg_replace('/[^0-9]/', '', $row['2_cpfcnpj']);  // Limpa o CPF removendo caracteres especiais
                $pessoaExistente = Pessoa::query()->where('cpf', $cpf)->first();
                if (!$pessoaExistente) {
                    $estadoCivil = strtoupper(trim($row['14_estado_civil']));
                    $estadoCivil = strtr($estadoCivil, [
                        'Á' => 'A', 'É' => 'E', 'Í' => 'I', 'Ó' => 'O', 'Ú' => 'U',
                        'Ã' => 'A', 'Õ' => 'O', 'Ê' => 'E', 'Ô' => 'O', 'Ç' => 'C',
                    ]);
                    $telefone = preg_replace('/[^0-9]/', '', $row['telefone_contato']);

                    if (strlen($telefone) == 8) {
                        $telefone = '659' . $telefone;
                    } elseif (strlen($telefone) == 9) {
                        $telefone = '65' . $telefone;
                    }
                    $pessoa = new Pessoa([
                        'nome' => $row['1_nome'],
                        'profissao' => $row['13_profissao'],
                        'estado_civil' => $estadoCivilMap[$estadoCivil] ?? null,
                        'regime_casamento' => $row['22_regime_de_bens'] ?? null,
                        'uniao_estavel' => $row['uniao_estavel'] ?? null,
                        'nome_pai' => $row['16_nome_do_pai'],
                        'nome_mae' => $row['15_nome_da_mae'],
                        'telefone' => $telefone,
                        'cpf' => preg_replace('/[^0-9]/', '', $row['2_cpfcnpj']),
                        'registro_geral' => preg_replace('/[^0-9]/', '', $row['4_rgie']),
                        'orgao_expedidor' => $row['5_orgao_expedidor'],
                        'nacionalidade' => $row['12_nacionalidade'],
                        'falecida' => 0,
                    ]);
                    $pessoa->save();
                    if ($row['17_nome_conjuge'] !== null) {
                        $cpf = preg_replace('/[^0-9]/', '', $row['20_cpf_conjuge']);  // Limpa o CPF removendo caracteres especiais
                        $conjugeExistente = Pessoa::query()->where('cpf', $cpf)->first();
                        if (!$conjugeExistente) {
                            $conjugue = new Pessoa([
                                'nome' => $row['17_nome_conjuge'],
                                'conjuge_id' => $pessoa->id,
                                'profissao' => null,
                                'estado_civil' => $estadoCivilMap[$estadoCivil] ?? null,
                                'regime_casamento' => $row['22_regime_de_bens'],
                                'uniao_estavel' => $row['uniao_estavel'] ?? null,
                                'nome_pai' => $row['24_nome_do_pai_conjuge'],
                                'nome_mae' => $row['23_nome_da_mae_conjuge'],
                                'telefone' => null,
                                'cpf' => preg_replace('/[^0-9]/', '', $row['20_cpf_conjuge']),
                                'registro_geral' => preg_replace('/[^0-9]/', '', $row['18_rg_conjuge']),
                                'orgao_expedidor' => $row['19_orgao_expedidor_conjuge'],
                                'nacionalidade' => $row['12_nacionalidade'],
                                'falecida' => $pessoa->falecida === 'V' ? 1 : 0,
                            ]);
                            $conjugue->save();
                            $pessoa->update([
                                'conjuge_id' => $conjugue->id,
                            ]);
                            $imovel = new Imovel([
                                'municipio' => $row['10_municipio'],
                                'loteamento' => $row['9_bairro_loteamento'],
                                'quadra' => $row['8_complemento_quadra'],
                                'lote' => $row['7_numero_lote'],
                                'casa' => $row['6_logradouro_frente'],
                                'inscricao_imobiliaria' => $row['inscricao_imobiliaria'] ?? null,
                                'matricula_reurb' => $row['matricula'],
                                'area' => $row['area_m2'],
                                'perimetro' => $row['perimetro'],
                                'medida_frente' => $row['medida_frente'],
                                'medida_fundo' => $row['medida_fundo'],
                                'medida_lado_direito' => $row['medida_lado_direito'],
                                'medida_lado_esquerdo' => $row['medida_lado_esquerdo'],
                                'confinante_frente' => $row['confinante_frente'],
                                'confinante_fundo' => $row['confinante_fundo'],
                                'confinante_lado_direito' => $row['confinante_lado_direito'],
                                'confinante_lado_esquerdo' => $row['confinante_lado_esquerdo'],
                                'prefixo_titulo' => $this->getAbreviacaoBairro($row['9_bairro_loteamento'], $row['prefixo_titulo']),
                            ]);
                            $imovel->save();
                            $vinculacao = new VinculacaoImovelPessoas([
                                'pessoa_id' => $pessoa->id,
                                'imovel_id' => $imovel->id,
                                'data_vinculacao' => $row['data_escritura']
                            ]);
                            $vinculacao->save();
                        } else {
                            $imovel = new Imovel([
                                'municipio' => $row['10_municipio'],
                                'loteamento' => $row['9_bairro_loteamento'],
                                'quadra' => $row['8_complemento_quadra'],
                                'lote' => $row['7_numero_lote'],
                                'casa' => $row['6_logradouro_frente'],
                                'inscricao_imobiliaria' => $row['inscricao_imobiliaria'] ?? null,
                                'matricula_reurb' => $row['matricula'],
                                'area' => $row['area_m2'],
                                'perimetro' => $row['perimetro'],
                                'medida_frente' => $row['medida_frente'],
                                'medida_fundo' => $row['medida_fundo'],
                                'medida_lado_direito' => $row['medida_lado_direito'],
                                'medida_lado_esquerdo' => $row['medida_lado_esquerdo'],
                                'confinante_frente' => $row['confinante_frente'],
                                'confinante_fundo' => $row['confinante_fundo'],
                                'confinante_lado_direito' => $row['confinante_lado_direito'],
                                'confinante_lado_esquerdo' => $row['confinante_lado_esquerdo'],
                                'prefixo_titulo' => $this->getAbreviacaoBairro($row['9_bairro_loteamento'], $row['prefixo_titulo']),
                            ]);
                            $imovel->save();
                            $vinculacao = new VinculacaoImovelPessoas([
                                'pessoa_id' => $pessoa->id,
                                'imovel_id' => $imovel->id,
                                'data_vinculacao' => $row['data_escritura']
                            ]);
                            $vinculacao->save();
                            $pessoa->update([
                                'conjuge_id' => $conjugeExistente->id,
                            ]);
                            $conjugeExistente->update([
                                'conjuge_id' => $pessoa->id,
                            ]);
                        }
                    } else {
                        $imovel = new Imovel([
                            'municipio' => $row['10_municipio'],
                            'loteamento' => $row['9_bairro_loteamento'],
                            'quadra' => $row['8_complemento_quadra'],
                            'lote' => $row['7_numero_lote'],
                            'casa' => $row['6_logradouro_frente'],
                            'inscricao_imobiliaria' => $row['inscricao_imobiliaria'] ?? null,
                            'matricula_reurb' => $row['matricula'],
                            'area' => $row['area_m2'],
                            'perimetro' => $row['perimetro'],
                            'medida_frente' => $row['medida_frente'],
                            'medida_fundo' => $row['medida_fundo'],
                            'medida_lado_direito' => $row['medida_lado_direito'],
                            'medida_lado_esquerdo' => $row['medida_lado_esquerdo'],
                            'confinante_frente' => $row['confinante_frente'],
                            'confinante_fundo' => $row['confinante_fundo'],
                            'confinante_lado_direito' => $row['confinante_lado_direito'],
                            'confinante_lado_esquerdo' => $row['confinante_lado_esquerdo'],
                            'prefixo_titulo' => $this->getAbreviacaoBairro($row['9_bairro_loteamento'], $row['prefixo_titulo']),
                        ]);
                        $imovel->save();
                        $vinculacao = new VinculacaoImovelPessoas([
                            'pessoa_id' => $pessoa->id,
                            'imovel_id' => $imovel->id,
                            'data_vinculacao' => $row['data_escritura']
                        ]);
                        $vinculacao->save();
                    }
                } else {
                    if ($row['17_nome_conjuge'] !== null) {
                        $cpf = preg_replace('/[^0-9]/', '', $row['20_cpf_conjuge']);  // Limpa o CPF removendo caracteres especiais
                        $conjugeExistente = Pessoa::query()->where('cpf', $cpf)->first();
                        if (!$conjugeExistente) {
                            $estadoCivil = strtoupper(trim($row['14_estado_civil']));
                            $estadoCivil = strtr($estadoCivil, [
                                'Á' => 'A', 'É' => 'E', 'Í' => 'I', 'Ó' => 'O', 'Ú' => 'U',
                                'Ã' => 'A', 'Õ' => 'O', 'Ê' => 'E', 'Ô' => 'O', 'Ç' => 'C',
                            ]);
                            $conjugue = new Pessoa([
                                'nome' => $row['17_nome_conjuge'],
                                'conjuge_id' => $pessoa->id,
                                'profissao' => null,
                                'estado_civil' => $estadoCivilMap[$estadoCivil] ?? null,
                                'regime_casamento' => $row['22_regime_de_bens'],
                                'uniao_estavel' => $row['uniao_estavel'] ?? null,
                                'nome_pai' => $row['24_nome_do_pai_conjuge'],
                                'nome_mae' => $row['23_nome_da_mae_conjuge'],
                                'telefone' => null,
                                'cpf' => preg_replace('/[^0-9]/', '', $row['20_cpf_conjuge']),
                                'registro_geral' => preg_replace('/[^0-9]/', '', $row['18_rg_conjuge']),
                                'orgao_expedidor' => $row['19_orgao_expedidor_conjuge'],
                                'nacionalidade' => $row['12_nacionalidade'],
                                'falecida' => $pessoa->falecida === 'V' ? 1 : 0,
                            ]);
                            $conjugue->save();
                            $pessoa->update([
                                'conjuge_id' => $conjugue->id,
                            ]);
                            $imovel = new Imovel([
                                'municipio' => $row['10_municipio'],
                                'loteamento' => $row['9_bairro_loteamento'],
                                'quadra' => $row['8_complemento_quadra'],
                                'lote' => $row['7_numero_lote'],
                                'casa' => $row['6_logradouro_frente'],
                                'inscricao_imobiliaria' => $row['inscricao_imobiliaria'] ?? null,
                                'matricula_reurb' => $row['matricula'],
                                'area' => $row['area_m2'],
                                'perimetro' => $row['perimetro'],
                                'medida_frente' => $row['medida_frente'],
                                'medida_fundo' => $row['medida_fundo'],
                                'medida_lado_direito' => $row['medida_lado_direito'],
                                'medida_lado_esquerdo' => $row['medida_lado_esquerdo'],
                                'confinante_frente' => $row['confinante_frente'],
                                'confinante_fundo' => $row['confinante_fundo'],
                                'confinante_lado_direito' => $row['confinante_lado_direito'],
                                'confinante_lado_esquerdo' => $row['confinante_lado_esquerdo'],
                                'prefixo_titulo' => $this->getAbreviacaoBairro($row['9_bairro_loteamento'], $row['prefixo_titulo']),
                            ]);
                            $imovel->save();
                            $vinculacao = new VinculacaoImovelPessoas([
                                'pessoa_id' => $pessoa->id,
                                'imovel_id' => $imovel->id,
                                'data_vinculacao' => $row['data_escritura']
                            ]);
                            $vinculacao->save();
                        } else {
                            $pessoaExistente->update([
                                'conjuge_id' => $conjugeExistente->id,
                            ]);
                            $conjugeExistente->update([
                                'conjuge_id' => $pessoaExistente->id,
                            ]);
                            $imovel = new Imovel([
                                'municipio' => $row['10_municipio'],
                                'loteamento' => $row['9_bairro_loteamento'],
                                'quadra' => $row['8_complemento_quadra'],
                                'lote' => $row['7_numero_lote'],
                                'casa' => $row['6_logradouro_frente'],
                                'inscricao_imobiliaria' => $row['inscricao_imobiliaria'] ?? null,
                                'matricula_reurb' => $row['matricula'],
                                'area' => $row['area_m2'],
                                'perimetro' => $row['perimetro'],
                                'medida_frente' => $row['medida_frente'],
                                'medida_fundo' => $row['medida_fundo'],
                                'medida_lado_direito' => $row['medida_lado_direito'],
                                'medida_lado_esquerdo' => $row['medida_lado_esquerdo'],
                                'confinante_frente' => $row['confinante_frente'],
                                'confinante_fundo' => $row['confinante_fundo'],
                                'confinante_lado_direito' => $row['confinante_lado_direito'],
                                'confinante_lado_esquerdo' => $row['confinante_lado_esquerdo'],
                                'prefixo_titulo' => $this->getAbreviacaoBairro($row['9_bairro_loteamento'], $row['prefixo_titulo']),
                            ]);
                            $imovel->save();
                            $vinculacao = new VinculacaoImovelPessoas([
                                'pessoa_id' => $pessoaExistente->id,
                                'imovel_id' => $imovel->id,
                                'data_vinculacao' => $row['data_escritura']
                            ]);
                            $vinculacao->save();
                        }
                    } else {
                        $imovel = new Imovel([
                            'municipio' => $row['10_municipio'],
                            'loteamento' => $row['9_bairro_loteamento'],
                            'quadra' => $row['8_complemento_quadra'],
                            'lote' => $row['7_numero_lote'],
                            'casa' => $row['6_logradouro_frente'],
                            'inscricao_imobiliaria' => $row['inscricao_imobiliaria'] ?? null,
                            'matricula_reurb' => $row['matricula'],
                            'area' => $row['area_m2'],
                            'perimetro' => $row['perimetro'],
                            'medida_frente' => $row['medida_frente'],
                            'medida_fundo' => $row['medida_fundo'],
                            'medida_lado_direito' => $row['medida_lado_direito'],
                            'medida_lado_esquerdo' => $row['medida_lado_esquerdo'],
                            'confinante_frente' => $row['confinante_frente'],
                            'confinante_fundo' => $row['confinante_fundo'],
                            'confinante_lado_direito' => $row['confinante_lado_direito'],
                            'confinante_lado_esquerdo' => $row['confinante_lado_esquerdo'],
                            'prefixo_titulo' => $this->getAbreviacaoBairro($row['9_bairro_loteamento'], $row['prefixo_titulo']),
                        ]);
                        $imovel->save();
                        $vinculacao = new VinculacaoImovelPessoas([
                            'pessoa_id' => $pessoaExistente->id,
                            'imovel_id' => $imovel->id,
                            'data_vinculacao' => $row['data_escritura']
                        ]);
                        $vinculacao->save();
                    }
                }
            } else {
                $imovel = new Imovel([
                    'municipio' => $row['10_municipio'],
                    'loteamento' => $row['9_bairro_loteamento'],
                    'quadra' => $row['8_complemento_quadra'],
                    'lote' => $row['7_numero_lote'],
                    'casa' => $row['6_logradouro_frente'],
                    'inscricao_imobiliaria' => $row['inscricao_imobiliaria'] ?? null,
                    'matricula_reurb' => $row['matricula'],
                    'area' => $row['area_m2'],
                    'perimetro' => $row['perimetro'],
                    'medida_frente' => $row['medida_frente'],
                    'medida_fundo' => $row['medida_fundo'],
                    'medida_lado_direito' => $row['medida_lado_direito'],
                    'medida_lado_esquerdo' => $row['medida_lado_esquerdo'],
                    'confinante_frente' => $row['confinante_frente'],
                    'confinante_fundo' => $row['confinante_fundo'],
                    'confinante_lado_direito' => $row['confinante_lado_direito'],
                    'confinante_lado_esquerdo' => $row['confinante_lado_esquerdo'],
                    'prefixo_titulo' => $this->getAbreviacaoBairro($row['9_bairro_loteamento'], $row['prefixo_titulo']),
                ]);
                $imovel->save();
            }
        }
    }

    function getAbreviacaoBairro($bairroLoteamento, $inputString)
    {
        // Extrai todas as strings entre aspas duplas
        $quotedStrings = $this->extractQuotedStrings($inputString);
        for ($i = 0; $i < count($quotedStrings); $i += 2) {
            $bairro = $quotedStrings[$i];
            $prefixo = $quotedStrings[$i + 1];
            if ($bairroLoteamento === $bairro) {
                return $prefixo;
            }
        }
        return $inputString;
    }

// Função para extrair as strings entre aspas duplas
    function extractQuotedStrings($inputString)
    {
        preg_match_all('/"([^"]+)"/', $inputString, $matches);
        return $matches[1];
    }

}
