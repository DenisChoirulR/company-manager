<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()
            ->admin()
            ->create([
                'company_id' => null,
                'email' => 'admin@mail.com',
            ]);

        $companies = Company::factory(5)->create();

        foreach ($companies as $company) {
            User::factory()
                ->manager()
                ->create([
                    'company_id' => $company->id,
                    'email' => 'manager_' . $company->id . '@mail.com',
                ]);
        }

        foreach ($companies as $company) {
            User::factory(10)
            ->create([
                'company_id' => $company->id,
            ]);
        }
    }
}
