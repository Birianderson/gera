<?php

namespace App\Databases\Repositories;

use App\Databases\Contracts\SolicitacaoMensagemContract;
use App\Databases\Models\Arquivo;
use App\Databases\Models\Solicitacao;
use App\Databases\Models\SolicitacaoMensagem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Exception;

class SolicitacaoMensagemRepository implements SolicitacaoMensagemContract
{
    /**
     * Constructor
     * @param SolicitacaoMensagem $model
     */
    public function __construct(private SolicitacaoMensagem $model)
    {
    }

    /**
     *
     * @param int $SolicitacaoMensagemId
     * @return Model
     */
    public function getById(int $SolicitacaoMensagemId): Model
    {
        return Solicitacao::query()->with(['mensagens.arquivos.tipo_arquivo', 'usuario', 'imovel.loteamento.cidade'])
            ->where('id', '=', $SolicitacaoMensagemId)
            ->firstOrFail();
    }

    /**
     *
     * @param int $SolicitacaoMensagemId
     * @return Model
     */
    public function getArquivoById(int $id_arquivo): Model
    {
        return Arquivo::query()->find($id_arquivo);
    }

    /**
     * Paginates SolicitacaoMensagem records
     * @param array $pagination
     * @param array $columns
     * @return LengthAwarePaginator
     */
    public function paginate(array $pagination = [], array $columns = ['*']): LengthAwarePaginator
    {
        $query = SolicitacaoMensagem::query();

        if (isset($pagination['numero_protocolo'])) {
            $keyword = mb_strtolower($pagination['numero_protocolo']);
            $query->whereRaw('lower(numero_protocolo) like ?', ["%{$keyword}%"]);
        }
        if (isset($pagination['nome_solicitante'])) {
            $keyword = mb_strtolower($pagination['nome_solicitante']);
            $query->whereRaw('lower(nome_solicitante) like ?', ["%{$keyword}%"]);
        }
        if (isset($pagination['cnpj_cpf_cod_tce_entidade'])) {
            $keyword = mb_strtolower($pagination['cnpj_cpf_cod_tce_entidade']);
            $query->whereRaw('lower(cnpj_cpf_cod_tce_entidade) like ?', ["%{$keyword}%"]);
        }
        if (isset($pagination['email'])) {
            $keyword = mb_strtolower($pagination['email']);
            $query->whereRaw('lower(email) like ?', ["%{$keyword}%"]);
        }
        if (isset($pagination['situacao'])) {
            $query->where('situacao', '=', $pagination['situacao']);
        }

        $query->orderBy($pagination['sort'] ?? 'nome_solicitante', $pagination['sort_direction'] ?? 'desc');
        return $query->paginate($pagination['per_page'] ?? 10, $columns, 'page', $pagination['current_page'] ?? 1);
    }

    /**
     * Salva novo registro
     * @param array $params
     * @param bool $autoCommit
     * @return bool
     * @throws Exception
     */
    public function create(array $params, bool $autoCommit = true): bool
    {
        $autoCommit && DB::beginTransaction();
        try {
            $user_id = Auth::user()->id;
            $atendimento = new SolicitacaoMensagem([
                'texto' => $params['texto'],
                'solicitacao_id' => $params['solicitacao_id'],
                'usuario_id' => $user_id,
            ]);
            $atendimento->save();
            $autoCommit && DB::commit();
            return true;
        } catch (Exception $ex) {
            $autoCommit && DB::rollBack();
            throw new Exception($ex);
        }
    }

    public function mudarSituacao(array $params, bool $autoCommit = true): bool
    {
        $autoCommit && DB::beginTransaction();
        try {
            $solicitacao = Solicitacao::query()->findOrFail($params['solicitacao_id']);
            $solicitacao->update([
                'situacao' => $params['novo_status'],
            ]);
            $solicitacao->save();
            $autoCommit && DB::commit();
            return true;
        } catch (Exception $ex) {
            $autoCommit && DB::rollBack();
            throw new Exception($ex);
        }
    }


}
