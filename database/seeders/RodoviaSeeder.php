<?php

namespace Database\Seeders;

use App\Models\Uf;
use App\Models\Rodovia;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;

class RodoviaSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Busca todas as UFs
        $ufs = Uf::all();
        $dataAtual = now()->format('Y-m-d');

        foreach ($ufs as $uf) {
            $this->command->info("Buscando rodovias para a UF: {$uf->sigla}...");

            try {
                // 2. Faz a requisição UMA VEZ por UF com timeout de 15 segundos
                $response = Http::timeout(15)->get("https://servicos.dnit.gov.br/sgplan/apigeo/snv/listarbrporuf", [
                    'data' => $dataAtual,
                    'uf'   => $uf->sigla
                ]);

                if ($response->successful()) {
                    $dados = $response->json();

                    if (isset($dados['lista_br']) && !empty($dados['lista_br'])) {
                        // Transforma a string "101,116,230" em um array
                        $brs = explode(',', $dados['lista_br']);

                        foreach ($brs as $br) {
                            // 3. Apenas salva ou atualiza os dados que já recebemos no JSON
                            Rodovia::updateOrCreate(
                                [
                                    'nome'  => trim($br), 
                                    'uf_id' => $uf->id    
                                ],
                                [
                                    'updated_at' => now()
                                ]
                            );
                        }
                    }
                }
            } catch (\Exception $e) {
                $this->command->error("Erro ao buscar rodovias de {$uf->sigla}: " . $e->getMessage());
            }
        }

        $this->command->info("Sincronização de rodovias concluída!");
    }
}