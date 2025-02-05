<?php

namespace Database\Seeders;

use App\Models\Category\Category;
use App\Models\Product\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder {
    /**
     * Seed the application's database.
     */
    public function run(): void {

        User::factory()->create([
            'name' => 'System',
            'is_admin' => true,
            'email' => 'system@ecommerce.com.br',
            'document_id' => '',
            'document_type' => 0,
            'birth_date' => '2024-09-01',
            'phone_number' => '47984774946'
        ]);

        User::factory()->create([
            'name' => 'Bruno Coelho Ribas',
            'is_admin' => true,
            'email' => 'bruno200coelho@hotmail.com',
            'password' => bcrypt('Teste@teste'),
            'document_id' => '7665972',
            'document_type' => 1,
            'birth_date' => '2003-03-01',
            'phone_number' => '47996545644'
        ]);

        User::factory()->create([
            'name' => 'Cliente',
            'is_admin' => false,
            'email' => 'cliente@hotmail.com',
            'password' => bcrypt('Teste@teste'),
            'document_id' => '13055068980',
            'document_type' => 2,
            'birth_date' => '2004-09-15',
            'phone_number' => '63998222373'
        ]);

        User::factory()->create([
            'name' => 'Fornecedor',
            'is_admin' => false,
            'email' => 'fornecedor@hotmail.com',
            'password' => bcrypt('Teste@teste'),
            'document_id' => '44703291707',
            'document_type' => 3,
            'birth_date' => '2002-09-06',
            'phone_number' => '99989483404'
        ]);
    }
}
