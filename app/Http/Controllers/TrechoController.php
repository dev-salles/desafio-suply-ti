<?php

namespace App\Http\Controllers;

use App\Models\Uf;
use App\Models\Rodovia;
use App\Models\Trecho;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Inertia\Inertia;

class TrechoController extends Controller
{
    /**
     * Exibe a listagem de trechos (Módulo 2)
     */
    public function index()
    {
        // Substituímos o get() por paginate(10) para gerar os links de página
        $trechos = Trecho::with(['uf', 'rodovia'])
        ->latest()
        ->paginate(10);

        return Inertia::render('Trechos/Index', [
            'trechos' => $trechos
        ]);
    }

    /**
     * Exibe o formulário de cadastro (Módulo 1)
     */
    public function create()
    {
        return Inertia::render('Trechos/Create', [
            'ufs' => Uf::all(),
            'rodovias' => Rodovia::all(),
        ]);
    }

    public function edit(Trecho $trecho)
    {
        // Buscamos as UFs para preencher o select, igual no Create
        $ufs = \App\Models\Uf::all();

        $trecho->load('rodovia');

        return Inertia::render('Trechos/Edit', [
            'trecho' => $trecho,
            'ufs' => $ufs
        ]);
    }

    public function update(Request $request, Trecho $trecho)
    {
        $validated = $request->validate([
            'data_referencia' => 'required|date',
            'uf_id'           => 'required|exists:ufs,id',
            'rodovia_id'      => 'required', // Nome que vem da API (ex: "101")
            'tipo'            => 'required',
            'km_inicial'      => 'required|numeric',
            'km_final'        => 'required|numeric|gt:km_inicial',
        ]);

        // 1. Busca a rodovia real no seu banco para pegar o ID correto
        $rodoviaReal = Rodovia::where('uf_id', $validated['uf_id'])
            ->where(function ($query) use ($validated) {
                $query->where('nome', $validated['rodovia_id'])
                    ->orWhere('nome', 'LIKE', '%' . $validated['rodovia_id'] . '%');
            })->first();

        if (!$rodoviaReal) {
            return redirect()->back()->withErrors(['rodovia_id' => 'Rodovia não encontrada no banco local.']);
        }

        // 2. Atualiza o valor para o ID do banco antes de salvar
        $validated['rodovia_id'] = $rodoviaReal->id;

        // 3. (Opcional) Se quiser atualizar a GEO-Localização no update também:
        $ufSigla = Uf::find($validated['uf_id'])->sigla;
        $rodoviaDnit = preg_replace('/[^0-9]/', '', $rodoviaReal->nome);

        $response = Http::get("https://servicos.dnit.gov.br/sgplan/apigeo/rotas/espacializarlinha", [
            'br'      => $rodoviaDnit,
            'tipo'    => $validated['tipo'],
            'uf'      => strtoupper($ufSigla),
            'cd_tipo' => 'null',
            'data'    => date('Y-m-d', strtotime($validated['data_referencia'])),
            'kmi'     => $validated['km_inicial'],
            'kmf'     => $validated['km_final'],
        ]);

        if ($response->successful()) {
            $validated['geo'] = $response->json();
        }

        $trecho->update($validated);

        session()->flash('success', 'Trecho atualizado com sucesso!');

        return redirect()->route('trechos.index');
    }

    public function destroy(Trecho $trecho)
    {
        $trecho->delete();
        return redirect()->back()->with('success', 'Trecho removido com sucesso!|' . now()->timestamp);
    }

    /**
     * Salva o novo trecho e consome a API do DNIT
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'data_referencia' => 'required|date',
            'uf_id'           => 'required|exists:ufs,id',
            'rodovia_id'      => 'required',
            'tipo'            => 'required|string|max:1',
            'km_inicial'      => 'required|numeric|min:0',
            'km_final'        => 'required|numeric|gt:km_inicial',
        ]);

        $ufModel = Uf::find($validated['uf_id']);
        $ufSigla = $ufModel->sigla;

        // Busca a rodovia garantindo que pertence à UF selecionada
        $rodoviaReal = Rodovia::where('uf_id', $validated['uf_id'])
            ->where(function ($query) use ($validated) {
                $query->where('nome', $validated['rodovia_id'])
                    ->orWhere('nome', 'LIKE', '%' . $validated['rodovia_id'] . '%');
            })->first();

        if (!$rodoviaReal) {
            // Importante: O erro deve ser associado ao campo 'rodovia_id'
            return redirect()->back()->withErrors(['rodovia_id' => 'A rodovia 222 não foi encontrada para a UF selecionada.']);
        }


        $rodovia = preg_replace('/[^0-9]/', '', $rodoviaReal->nome);

        $validated['rodovia_id'] = $rodoviaReal->id;
        $dataFormatada = date('Y-m-d', strtotime($validated['data_referencia']));

        $response = Http::get("https://servicos.dnit.gov.br/sgplan/apigeo/rotas/espacializarlinha", [
            'br'      => $rodovia,
            'tipo'    => $validated['tipo'],
            'uf'      => strtoupper($ufSigla),
            'cd_tipo' => 'null',
            'data'    => $dataFormatada,
            'kmi'     => $validated['km_inicial'],
            'kmf'     => $validated['km_final'],
        ]);

        if ($response->successful()) {
            $validated['geo'] = $response->json();
        }

        Trecho::create($validated);

        return redirect()->route('trechos.index')->with('success', 'Trecho cadastrado com sucesso!');
    }

    public function show(Trecho $trecho)
    {
        // Carregamos a relação da UF para mostrar a sigla no mapa
        $trecho->load(['uf', 'rodovia']);

        return Inertia::render('Trechos/ShowMap', [
            'trecho' => $trecho
        ]);
    }

    public function getRodovias($uf)
    {
        // Usamos o Cache para não sobrecarregar o banco, mas agora com dados internos
        $cacheKey = "rodovias_internas_uf_{$uf}";

        $rodovias = Cache::remember($cacheKey, 86400, function () use ($uf) {
            // 1. Buscamos a UF pela sigla para pegar o ID correto no banco
            $ufModel = \App\Models\Uf::where('sigla', $uf)->first();

            if (!$ufModel) {
                return [];
            }

            // 2. Buscamos as rodovias vinculadas a esse ID na nossa tabela 'rodovias'
            // que você acabou de popular via DBeaver
            return \App\Models\Rodovia::where('uf_id', $ufModel->id)
                ->orderBy('nome')
                ->pluck('nome'); // O pluck retorna apenas o array de nomes ['101', '116'...]
        });

        return response()->json($rodovias);
    }

    public function getUfs()
    {
        // API do DNIT que retorna a lista de estados
        $response = Http::get("https://servicos.dnit.gov.br/sgplan/apigeo/snv/listarUfs");

        return response()->json($response->json());
    }
}
