<?php
namespace App\Databases\Repositories;

use App\Databases\Contracts\PessoaContract;
use App\Databases\Models\Imovel;
use App\Databases\Models\Pessoa;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Exception;

class PessoaRepository implements PessoaContract
{
    /**
     * Constructor
     * @param Pessoa $model
     */
    public function __construct(private Pessoa $model)
    {
    }

    /**
     * Busca 1 registro de Unidade de Atendimento
     * @param int $id
     * @return Model
     */
    public function getByCPF(string $cpf): Model|null
    {

        return Pessoa::query()
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

        return Pessoa::query()
            ->where('id', '=', $id)
            ->firstOrFail();
    }

    /**
     * Busca todos registros de Unidade de Atendimento
     * @return Collection
     */
    public function getAll(): Collection
    {
        return Pessoa::query()->get();
    }

    /**
     * Pagina Unidades Atendimento
     * @param array $pagination
     * @param array $columns
     * @return LengthAwarePaginator
     */
    public function paginate(array $pagination = [], array $columns = ['*']): LengthAwarePaginator
    {
        $query = Pessoa::query()->with(['imoveis','conjuge']);

        if (isset($pagination['nome'])) {
            $keyword = mb_strtolower($pagination['nome']);
            $query->whereRaw('lower(nome) like ?', ["%{$keyword}%"]);
        }
        if (isset($pagination['cpf'])) {
            $keyword = mb_strtolower($pagination['cpf']);
            $query->whereRaw('lower(cpf) like ?', ["%{$keyword}%"]);
        }

        if (isset($pagination['telefone'])) {
            $keyword = mb_strtolower($pagination['telefone']);
            $query->whereRaw('lower(telefone) like ?', ["%{$keyword}%"]);
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
            $params['cpf'] = preg_replace('/\D/', '', $params['cpf']);
            $params['registro_geral'] = preg_replace('/\D/', '', $params['registro_geral']);
            $params['telefone'] = preg_replace('/\D/', '', $params['telefone']);
            $Pessoa = new Pessoa([
                'nome' => $params['nome'],
                'profissao' => $params['profissao'],
                'estado_civil' => $params['estado_civil'],
                'regime_casamento' => $params['regime_casamento'] ?? 0,
                'uniao_estavel' => $params['uniao_estavel'] ?? 0,
                'nome_pai' => $params['nome_pai'],
                'nome_mae' => $params['nome_mae'],
                'telefone' => $params['telefone'],
                'cpf' => $params['cpf'],
                'registro_geral' => $params['registro_geral'],
                'orgao_expedidor' => $params['orgao_expedidor'],
                'nacionalidade' => $params['nacionalidade'],
                'falecida' => $params['falecida'] ?? 0,
            ]);
            $Pessoa->save();

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
            if (!isset($params['ativo'])) {
                $params['ativo'] = 0;
            }

            $competencia = $this->getById($id);
            $competencia->update($params);

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
            $Pessoa = $this->getById($id);
            $Pessoa->delete();
            $autoCommit && DB::commit();
        } catch (Exception $ex) {
            $autoCommit && DB::rollBack();
            throw new Exception($ex->getMessage());
        }

        return true;
    }

    /**
     * Busca todas Unidade de Atendimento para componente de ordenação
     * @return Collection
     */
    public function getImoveisByID($id): Collection
    {
        $pessoa = Pessoa::query()->where('id', '=', $id)->with('imoveis')->first();

        if ($pessoa) {
            $imoveis = $pessoa->imoveis;
        }

        return $imoveis;
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
