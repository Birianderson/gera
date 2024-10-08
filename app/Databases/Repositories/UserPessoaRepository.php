<?php

namespace App\Databases\Repositories;

use App\Databases\Contracts\UserPessoaContract;
use App\Databases\Models\Arquivo;
use App\Databases\Models\Imovel;
use App\Databases\Models\Pessoa;
use App\Databases\Models\TipoArquivo;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Str;

class UserPessoaRepository implements UserPessoaContract
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
    public function getByCPF(): Model|null
    {
        $user_cpf = Auth::user()->cpf;
        return Pessoa::query()
            ->where('cpf', '=', $user_cpf)
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

    public function getAllDocumentos(): Collection
    {

        return TipoArquivo::query()
            ->where('tabela', '=', 'pessoa')
            ->get();
    }

    /**
     * Busca 1 registro de Unidade de Atendimento
     * @param int $id
     * @return Collection
     */
    public function getMeusDocumentos(): Collection
    {

        $user_cpf = Auth::user()->cpf;
        $pessoa = Pessoa::query()->where('cpf', '=', $user_cpf)->first();
        return Arquivo::query()
            ->where('chave', '=', $pessoa->id)
            ->where('tabela', '=', 'pessoa')
            ->with('tipo_arquivo')
            ->get();
    }

    /**
     * Salva nova Unidade de Atendimento
     * @param array $params
     * @param bool $autoCommit
     * @return bool
     * @throws Exception
     */
    public function upload_documentos(array $params, bool $autoCommit = true): bool
    {
        $autoCommit && DB::beginTransaction();
        try {
            $user_cpf = Auth::user()->cpf;
            $pessoa = Pessoa::query()->where('cpf', '=', $user_cpf)->first();
            if (isset($params['arquivo'])) {

                $hash = Str::uuid();
                $name = $params['arquivo']->getClientOriginalName();
                $mime = $params['arquivo']->getClientMimeType();
                $size = $params['arquivo']->getSize();
                $extension = $params['arquivo']->getClientOriginalExtension();
                $destino = sprintf("public/uploads/%s", date("Y/m/d"));
                $filename = sprintf("%s.%s", $hash, strtolower($extension));
                $params['arquivo']->storeAs($destino, $filename);
                $arquivo = new Arquivo([
                    'tipo_arquivo_id' => $params['tipo_arquivo_id'],
                    'tabela' => 'pessoa',
                    'chave' => $pessoa->id,
                    'titulo' => $data['titulo'] ?? $name,
                    'descricao' => $data['descricao'] ?? null,
                    'nome' => $name,
                    'tamanho' => $size,
                    'content_type' => $mime,
                    'hash' => "{$destino}/{$filename}",
                    'status' => 'P',

                ]);
                $arquivo->save();

            }
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
    public function update(array $params, bool $autoCommit = true): bool
    {
        $autoCommit && DB::beginTransaction();
        try {
            $usuario = $this->getByCPF();
            $usuario->update($params);

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
        return Imovel::query()->where('pessoa_id', '=', $id)->with(['loteamento', 'cidade'])->get();
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
