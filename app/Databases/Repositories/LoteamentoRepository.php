<?php
namespace App\Databases\Repositories;

use App\Databases\Contracts\LoteamentoContract;
use App\Databases\Models\Cidade;
use App\Databases\Models\Loteamento;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Exception;
use Maatwebsite\Excel\Facades\Excel;

class LoteamentoRepository implements LoteamentoContract
{
    /**
     * Constructor
     * @param Loteamento $model
     */
    public function __construct(private Loteamento $model)
    {
    }

    /**
     * Busca 1 registro de Unidade de Atendimento
     * @param int $id
     * @return Model
     */
    public function getCidadeByid(string $id): Model|null
    {

        return Cidade::query()
            ->where('id', '=', $id)
            ->first();
    }

    /**
     * Busca 1 registro de Unidade de Atendimento
     * @param int $id
     * @return Model
     */
    public function getById(int $id): Model
    {

        return Loteamento::query()
            ->where('id', '=', $id)
            ->with('coordenadas')
            ->firstOrFail();
    }

    /**
     * Busca todos registros de Unidade de Atendimento
     * @return Collection
     */
    public function getByCidadeId(string $cidade_id): Collection
    {
        return Loteamento::query()->where('cidade_id','=', $cidade_id)->get();
    }

    /**
     * Pagina Unidades Atendimento
     * @param array $pagination
     * @param array $columns
     * @return LengthAwarePaginator
     */
    public function paginate(String $cidade_id, array $pagination = [], array $columns = ['*']): LengthAwarePaginator
    {
        $query = Loteamento::query()->where('cidade_id','=', $cidade_id);

        if (isset($pagination['nome'])) {
            $keyword = mb_strtolower($pagination['nome']);
            $query->whereRaw('lower(nome) like ?', ["%{$keyword}%"]);
        }
        if (isset($pagination['sigla'])) {
            $keyword = mb_strtolower($pagination['sigla']);
            $query->whereRaw('lower(sigla) like ?', ["%{$keyword}%"]);
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
            $Loteamento = new Loteamento([
                'nome' => $params['nome'],
                'sigla' => $params['sigla'],
                'cidade_id' => $params['cidade_id'],
            ]);

            $Loteamento->save();

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

            $Loteamento = $this->getById($id);
            $Loteamento->update([
                'nome' => $params['nome'],
                'sigla' => $params['sigla'],
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
            $Loteamento = $this->getById($id);
            $Loteamento->delete();
            $autoCommit && DB::commit();
        } catch (Exception $ex) {
            $autoCommit && DB::rollBack();
            throw new Exception($ex->getMessage());
        }

        return true;
    }

}
