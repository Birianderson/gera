<?php
namespace App\Databases\Repositories;

use App\Databases\Contracts\ImovelContract;
use App\Databases\Models\Imovel;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Exception;

class ImovelRepository implements ImovelContract
{
    /**
     * Constructor
     * @param Imovel $model
     */
    public function __construct(private Imovel $model)
    {
    }

    /**
     * Busca 1 registro de Unidade de Atendimento
     * @param int $id
     * @return Model
     */
    public function getByCPF(string $cpf): Model|null
    {

        return Imovel::query()
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

        return Imovel::query()
            ->where('id', '=', $id)
            ->firstOrFail();
    }

    /**
     * Busca todos registros de Unidade de Atendimento
     * @return Collection
     */
    public function getAll(): Collection
    {
        return Imovel::query()->get();
    }

    /**
     * Pagina Unidades Atendimento
     * @param array $pagination
     * @param array $columns
     * @return LengthAwarePaginator
     */
    public function paginate(array $pagination = [], array $columns = ['*']): LengthAwarePaginator
    {
        $query = Imovel::query();

        if (isset($pagination['nome'])) {
            $keyword = mb_strtolower($pagination['nome']);
            $query->whereRaw('lower(nome) like ?', ["%{$keyword}%"]);
        }
        if (isset($pagination['cpf'])) {
            $keyword = mb_strtolower($pagination['cpf']);
            $query->whereRaw('lower(cpf) like ?', ["%{$keyword}%"]);
        }
        if (isset($pagination['estado_civil'])) {
            $keyword = mb_strtolower($pagination['estado_civil']);
            $query->whereRaw('lower(estado_civil) like ?', ["%{$keyword}%"]);
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
            $Imovel = new Imovel([
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

            $Imovel->save();

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

            $imovel = $this->getById($id);
            $imovel->update([
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
            $Imovel = $this->getById($id);
            $Imovel->delete();
            $autoCommit && DB::commit();
        } catch (Exception $ex) {
            $autoCommit && DB::rollBack();
            throw new Exception($ex->getMessage());
        }

        return true;
    }

    /**
     * Ordenar ao deletar Unidade de Atendimento
     * @return void
     */
    public function updateOrderAfterDeletion(): void
    {
        $unidades = Imovel::query()
            ->orderBy('ordem')
            ->get();

        $ordem = 1;
        foreach ($unidades as $unidade) {
            $unidade->ordem = $ordem;
            $unidade->save();
            $ordem++;
        }
    }

    /**
     * Busca todas Unidade de Atendimento para componente de ordenação
     * @return Collection
     */
    public function getAllOrdem(): Collection
    {
        return Imovel::query()->orderBy('ordem')->get(['id', 'nome_unidade as name', 'ordem']);
    }

    /**
     * Salva a ordem de todas Unidade de Atendimento
     * @param array $data
     * @throws Exception
     */
    public function saveOrder(array $data): void
    {
        DB::beginTransaction();
        try {
            foreach ($data as $index => $item) {
                $this->model::query()->orderBy('ordem')->where('id', '=', $item['id'])->update([
                    'ordem' => $index + 1
                ]);
            }
            DB::commit();
        } catch (Exception $ex) {
            DB::rollBack();
            throw new Exception($ex->getMessage());
        }
    }

}
