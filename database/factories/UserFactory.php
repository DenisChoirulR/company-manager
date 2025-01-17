<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\User;
use App\Enums\RoleEnum;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class UserFactory extends Factory
{
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'id' => $this->faker->uuid(),
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'role' => RoleEnum::EMPLOYEE->value,
            'company_id' => Company::factory(),
            'password' => Hash::make('password'),
            'phone_number' => $this->faker->phoneNumber(),
            'address' => $this->faker->address(),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Define the model state for admin users.
     */
    public function admin()
    {
        return $this->state(fn (array $attributes) => [
            'role' => RoleEnum::ADMIN->value,
        ]);
    }

    /**
     * Define the model state for manager users.
     */
    public function manager()
    {
        return $this->state(fn (array $attributes) => [
            'role' => RoleEnum::MANAGER->value,
        ]);
    }
}
