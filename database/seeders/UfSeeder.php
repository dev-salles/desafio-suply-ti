<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class UfSeeder extends Seeder
{
    public function run(): void
    {
        $siglas = [
            'AC', 'AL', 'AP', 'AM', 'BA', 'CE', 'DF', 'ES', 'GO', 'MA',
            'MT', 'MS', 'MG', 'PA', 'PB', 'PR', 'PE', 'PI', 'RJ', 'RN',
            'RS', 'RO', 'RR', 'SC', 'SP', 'SE', 'TO'
        ];

        foreach ($siglas as $sigla) {
            DB::table('ufs')->updateOrInsert(
                ['sigla' => $sigla],
                ['updated_at' => now()]
            );
        }
    }
}
