<?php

namespace Database\Seeders;

use App\Models\Uf;
use App\Models\Rodovia;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;

class RodoviaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Busca todas as UFs cadastradas no seu banco
        $ufs = Uf::all();
        $dataAtual = now()->format('Y-m-d');

        foreach ($ufs as $uf) {
            $this->command->info("Buscando rodovias para a UF: {$uf->sigla}...");

            try {
                // 2. Faz a requisição para a API do DNIT para cada UF
                $response = Http::get("https://servicos.dnit.gov.br/sgplan/apigeo/snv/listarbrporuf", [
                    'data' => $dataAtual,
                    'uf'   => $uf->sigla
                ]);

                if ($response->successful()) {
                    $dados = $response->json();

                    // 3. Verifica se o atributo 'lista_br' existe (ex: "101,104,110")
                    if (isset($dados['lista_br']) && !empty($dados['lista_br'])) {
                        $brs = explode(',', $dados['lista_br']);

                        foreach ($brs as $br) {
                            // 4. Salva ou atualiza garantindo que não haverá duplicatas por UF
                            Rodovia::updateOrCreate(
                                [
                                    'nome'  => trim($br), // O número da BR (ex: 101)
                                    'uf_id' => $uf->id    // O ID da UF relacionada
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