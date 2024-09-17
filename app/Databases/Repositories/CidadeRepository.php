<?php
namespace App\Databases\Repositories;

use App\Databases\Contracts\CidadeContract;
use App\Databases\Models\Coordenadas;
use App\Databases\Models\Cidade;
use App\Imports\TerrenosImport;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Exception;
use Maatwebsite\Excel\Facades\Excel;

class CidadeRepository implements CidadeContract
{
    /**
     * Constructor
     * @param Cidade $model
     */
    public function __construct(private Cidade $model)
    {
    }

    /**
     * Busca 1 registro de Unidade de Atendimento
     * @param int $id
     * @return Model
     */
    public function getByCPF(string $cpf): Model|null
    {

        return Cidade::query()
            ->where('cpf', '=', $cpf)
            ->first();
    }

    /**
     * Busca 1 registro de Unidade de Atendimento
     * @param int $id
     * @return Model
     */
    public function getById(int $id): Model
    {

        return Cidade::query()
            ->where('id', '=', $id)
            ->firstOrFail();
    }

    /**
     * Busca todos registros de Unidade de Atendimento
     * @return Collection
     */
    public function getAll(): Collection
    {
        return Cidade::query()->get();
    }

    /**
     * Pagina Unidades Atendimento
     * @param array $pagination
     * @param array $columns
     * @return LengthAwarePaginator
     */
    public function paginate(array $pagination = [], array $columns = ['*']): LengthAwarePaginator
    {
        $query = Cidade::query();

        if (isset($pagination['nome'])) {
            $keyword = mb_strtolower($pagination['nome']);
            $query->whereRaw('lower(nome) like ?', ["%{$keyword}%"]);
        }

        $query->orderBy($pagination['sort'] ?? 'nome', $pagination['sort_direction'] ?? 'asc');
        return $query->paginate($pagination['per_page'] ?? 10, $columns, 'page', $pagination['current_page'] ?? 1);


    }



    /**
     * Salva nova Unidade de Atendimento
     * @param array $params
     * @param bool $autoCommit
     * @return bool
     * @throws Exception
     */
    public function create(array $params, bool $autoCommit = true): bool
    {
        $autoCommit && DB::beginTransaction();
        try {
            $Cidade = new Cidade([
                'municipio' => $params['municipio'],
                'loteamento' => $params['loteamento'],
                'quadra' => $params['quadra'],
                'lote' => $params['lote'],
                'casa' => $params['casa'],
                'inscricao_imobiliaria' => $params['inscricao_imobiliaria'],
                'matricula_reurb' => $params['matricula_reurb'],
                'area' => $params['area'],
                'area_construida' => $params['area_construida'],
                'perimetro' => $params['perimetro'],
                'medida_frente' => $params['medida_frente'],
                'medida_fundo' => $params['medida_fundo'],
                'medida_lado_direito' => $params['medida_lado_direito'],
                'medida_lado_esquerdo' => $params['medida_lado_esquerdo'],
                'confinante_frente' => $params['confinante_frente'],
                'confinante_fundo' => $params['confinante_fundo'],
                'confinante_lado_direito' => $params['confinante_lado_direito'],
                'confinante_lado_esquerdo' => $params['confinante_lado_esquerdo'],
                'valor_venal' => $params['valor_venal'],
                'valor_terreno' => $params['valor_terreno'],
                'valor_construcao' => $params['valor_construcao'],
                'numero_processo_administrativo' => $params['numero_processo_administrativo'],
                'prefixo_titulo' => $params['prefixo_titulo'],
                'ano_titulo' => $params['ano_titulo'],
                'numero_titulo' => $params['numero_titulo'],
            ]);

            $Cidade->save();

            $autoCommit && DB::commit();
            return true;
        } catch (Exception $ex) {
            $autoCommit && DB::rollBack();
            throw new Exception($ex);
        }
    }

    /**
     * Atualiza Unidade de Atendimento
     * @param int $id
     * @param array $params
     * @param bool $autoCommit
     * @return bool
     * @throws Exception
     */
    public function update(int $id, array $params, bool $autoCommit = true): bool
    {
        $autoCommit && DB::beginTransaction();
        try {

            $Cidade = $this->getById($id);
            $Cidade->update([
                'municipio' => $params['municipio'],
                'loteamento' => $params['loteamento'],
                'quadra' => $params['quadra'],
                'lote' => $params['lote'],
                'casa' => $params['casa'],
                'inscricao_imobiliaria' => $params['inscricao_imobiliaria'],
                'matricula_reurb' => $params['matricula_reurb'],
                'area' => $params['area'],
                'area_construida' => $params['area_construida'],
                'perimetro' => $params['perimetro'],
                'medida_frente' => $params['medida_frente'],
                'medida_fundo' => $params['medida_fundo'],
                'medida_lado_direito' => $params['medida_lado_direito'],
                'medida_lado_esquerdo' => $params['medida_lado_esquerdo'],
                'confinante_frente' => $params['confinante_frente'],
                'confinante_fundo' => $params['confinante_fundo'],
                'confinante_lado_direito' => $params['confinante_lado_direito'],
                'confinante_lado_esquerdo' => $params['confinante_lado_esquerdo'],
                'valor_venal' => $params['valor_venal'],
                'valor_terreno' => $params['valor_terreno'],
                'valor_construcao' => $params['valor_construcao'],
                'numero_processo_administrativo' => $params['numero_processo_administrativo'],
                'prefixo_titulo' => $params['prefixo_titulo'],
                'ano_titulo' => $params['ano_titulo'],
                'numero_titulo' => $params['numero_titulo'],
            ]);

            $autoCommit && DB::commit();
            return true;
        } catch (Exception $ex) {
            $autoCommit && DB::rollBack();
            throw new Exception($ex);
        }
    }

    /**
     * Salva nova Unidade de Atendimento
     * @param array $params
     * @param bool $autoCommit
     * @return bool
     * @throws Exception
     */
    public function vincula(array $params, bool $autoCommit = true): bool
    {
        $autoCommit && DB::beginTransaction();
        try {

            $coordenadas = Coordenadas::query()->where('id', '=', $params['coordenada'])->first();
            $coordenadas->update([
                'Cidade_id' => $params['search'],
            ]);

            $autoCommit && DB::commit();
            return true;
        } catch (Exception $ex) {
            $autoCommit && DB::rollBack();
            throw new Exception($ex);
        }
    }

    /**
     * Deleta Unidade de Atendimento
     * @param int $id
     * @param bool $autoCommit
     * @return bool
     * @throws Exception
     */
    public function destroy(int $id, bool $autoCommit = true): bool
    {
        $autoCommit && DB::beginTransaction();
        try {
            $Cidade = $this->getById($id);
            $Cidade->delete();
            $autoCommit && DB::commit();
        } catch (Exception $ex) {
            $autoCommit && DB::rollBack();
            throw new Exception($ex->getMessage());
        }

        return true;
    }

}
