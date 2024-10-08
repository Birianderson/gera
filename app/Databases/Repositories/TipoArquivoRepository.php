<?php

namespace App\Databases\Repositories;

use App\Databases\Contracts\TipoArquivoContract;
use App\Databases\Models\TipoArquivo;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class TipoArquivoRepository implements TipoArquivoContract {

    public function __construct(private TipoArquivo $model){}

    /**
     * Salvar novo categoria
     * @param array $params
     * @param bool $autoCommit
     * @return bool
     * @throws Exception
     */
    public function create(array $params, bool $autoCommit = true): bool
    {
        $autoCommit && DB::beginTransaction();
        try {
            $TipoArquivo = new TipoArquivo([
                'nome' => $params['nome'],
                'tabela' => $params['tabela'],
            ]);
            $TipoArquivo->save();
            $autoCommit && DB::commit();
            return true;
        } catch (Exception $ex) {
            $autoCommit && DB::rollBack();
            throw new Exception($ex);
        }
    }


    /**
     * Atualizar Empresa
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
            $TipoArquivo = $this->getById($id);
            $TipoArquivo->update([
                'nome' => $params['nome'],
                'tabela' => $params['tabela'],
            ]);
            $autoCommit && DB::commit();
            return true;
        } catch (Exception $ex) {
            $autoCommit && DB::rollBack();
            throw new Exception($ex);
        }
    }


    /**
     * Deletar categoria
     * @param int $id
     * @param bool $autoCommit
     * @return bool
     * @throws Exception
     */
    public function destroy(int $id, bool $autoCommit = true): bool
    {
        $autoCommit && DB::beginTransaction();
        try {
            $TipoArquivo = $this->getById($id);
            $TipoArquivo->delete();
            $autoCommit && DB::commit();
        } catch (Exception $ex) {
            $autoCommit && DB::rollBack();
            throw new Exception($ex->getMessage());
        }

        return true;
    }


    /**
     * @param array $pagination
     * @param array $columns
     * @return LengthAwarePaginator
     */
    public function getAll(array $pagination = [], array $columns = ['*']): LengthAwarePaginator
    {
        $query = TipoArquivo::query();

        if (isset($pagination['nome'])) {
            $keyword = mb_strtolower($pagination['nome']);
            $query->whereRaw('lower(nome) like ?', ["%{$keyword}%"]);
        }

        $query->orderBy($pagination['sort'] ?? 'nome', $pagination['sort_direction'] ?? 'asc');
        return $query->paginate($pagination['per_page'] ?? 10, $columns, 'page', $pagination['current_page'] ?? 1);
    }


    public function getById(int $id): Model
    {
        return TipoArquivo::query()->where('id', $id)->firstOrFail();
    }

    public function getByTabela(string $tabela): array|\Illuminate\Database\Eloquent\Collection
    {
        return TipoArquivo::query()->where('tabela', $tabela)->get();
    }


}

