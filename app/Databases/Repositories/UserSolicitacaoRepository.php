<?php
namespace App\Databases\Repositories;

use App\Databases\Contracts\UserSolicitacaoContract;
use App\Databases\Models\Coordenadas;
use App\Databases\Models\Solicitacao;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Exception;
use Maatwebsite\Excel\Facades\Excel;

class UserSolicitacaoRepository implements UserSolicitacaoContract
{
    /**
     * Constructor
     * @param Solicitacao $model
     */
    public function __construct(private Solicitacao $model)
    {
    }

    /**
     * Busca 1 registro de Unidade de Atendimento
     * @param int $id
     * @return Model
     */
    public function getByCPF(string $cpf): Model|null
    {

        return Solicitacao::query()
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

        return Solicitacao::query()
            ->where('id', '=', $id)
            ->with(['coordenadas','pessoa.conjuge'])
            ->firstOrFail();
    }

    public function findByQuadraLote($loteamento_id, $quadra, $lote)
    {
        return Solicitacao::query()
            ->where('loteamento_id', $loteamento_id)
            ->where('quadra', $quadra)
            ->where('lote', $lote)
            ->first();
    }

    /**
     * Busca todos registros de Unidade de Atendimento
     * @return Collection
     */
    public function getAll(): Collection
    {
        return Solicitacao::query()->get();
    }

    /**
     * Pagina Unidades Atendimento
     * @param array $pagination
     * @param array $columns
     * @return LengthAwarePaginator
     */
    public function paginate(array $pagination = [], array $columns = ['*']): LengthAwarePaginator
    {
        $user_id = Auth::user()->id;
        $query = Solicitacao::query()
            ->where('usuario_id', '=', $user_id)
            ->with(['usuario','imovel']);

        if (isset($pagination['municipio'])) {
            $keyword = mb_strtolower($pagination['municipio']);
            $query->whereRaw('lower(municipio) like ?', ["%{$keyword}%"]);
        }
        if (isset($pagination['loteamento'])) {
            $keyword = mb_strtolower($pagination['loteamento']);
            $query->whereRaw('lower(loteamento) like ?', ["%{$keyword}%"]);
        }
        if (isset($pagination['prefixo_titulo'])) {
            $keyword = mb_strtolower($pagination['prefixo_titulo']);
            $query->whereRaw('lower(prefixo_titulo) like ?', ["%{$keyword}%"]);
        }
        if (isset($pagination['quadra'])) {
            $keyword = mb_strtolower($pagination['quadra']);
            $query->whereRaw('lower(quadra) like ?', ["%{$keyword}%"]);
        }
        if (isset($pagination['lote'])) {
            $keyword = mb_strtolower($pagination['lote']);
            $query->whereRaw('lower(lote) like ?', ["%{$keyword}%"]);
        }

        // Ajuste para filtrar pelo nome da pessoa
        if (isset($pagination['pessoa_nome'])) {
            $keyword = mb_strtolower($pagination['pessoa_nome']);
            $query->whereHas('pessoa', function ($q) use ($keyword) {
                $q->whereRaw('lower(nome) like ?', ["%{$keyword}%"]);
            });
        }

        $query->orderBy($pagination['sort'] ?? 'municipio', $pagination['sort_direction'] ?? 'asc');
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
            $Solicitacao = new Solicitacao([
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

            $Solicitacao->save();

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

            $Solicitacao = $this->getById($id);
            $Solicitacao->update([
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

            $Solicitacao = Solicitacao::query()
                ->where('loteamento_id', $params['loteamento_id'])
                ->where('quadra', $params['quadra'])
                ->where('lote', $params['lote'])
                ->first();
            $coordenadas = Coordenadas::query()->where('id', '=', $params['coordenada_id'])->first();
            $coordenadas->update([
                'Solicitacao_id' => $Solicitacao->id,
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
            $Solicitacao = $this->getById($id);
            $Solicitacao->delete();
            $autoCommit && DB::commit();
        } catch (Exception $ex) {
            $autoCommit && DB::rollBack();
            throw new Exception($ex->getMessage());
        }

        return true;
    }

}
