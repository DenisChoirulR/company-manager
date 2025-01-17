<?php

namespace App\Http\Controllers\Api;

use App\Enums\RoleEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserIndexRequest;
use App\Http\Requests\User\UserStoreRequest;
use App\Http\Requests\User\UserUpdateRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(UserIndexRequest $request): AnonymousResourceCollection
    {
        $query = User::query();

        switch (auth()->user()->role?->value) {
            case RoleEnum::ADMIN->value:
                if ($request->has('role')) {
                    $query->where('role', $request->role);
                }
                break;

            case RoleEnum::MANAGER->value:
                $query->whereIn('role', [RoleEnum::MANAGER->value, RoleEnum::EMPLOYEE->value]);
                if ($request->has('role')) {
                    $query->where('role', $request->role);
                }
                break;

            case RoleEnum::EMPLOYEE->value:
                $query->where('role', RoleEnum::EMPLOYEE->value);
                break;
        }

        if ($request->has('companyId')) {
            $query->where('company_id', $request->companyId);
        }

        if ($request->has('search') && $request->search != '') {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->has('sortBy')) {
            $query->orderBy($request->sortBy, $request->get('sortDirection', 'asc'));
        }

        $users = $query->with('company')->paginate($request->get('perPage', 10));

        return UserResource::collection($users);
    }

    public function store(UserStoreRequest $request): UserResource
    {
        $user = User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => Hash::make($request['password']),
            'phone_number' => $request['phoneNumber'],
            'address' => $request['address'],
            'role' => $request['role'],
            'company_id' => $request['companyId'],
        ]);

        return new UserResource($user);
    }

    public function show(User $user): UserResource|JsonResponse
    {
        $authUser = auth()->user();

        switch ($authUser->role->value) {
            case RoleEnum::ADMIN->value:
                break;

            case RoleEnum::MANAGER->value:
                if (!in_array($user->role->value, [RoleEnum::MANAGER->value, RoleEnum::EMPLOYEE->value]) || $user->company_id !== $authUser->company_id) {
                    return response()->json([
                        'message' => 'Unauthorized',
                    ], 401);
                }
                break;

            case RoleEnum::EMPLOYEE->value:
                if ($user->role->value !== RoleEnum::EMPLOYEE->value || $user->company_id !== $authUser->company_id) {
                    return response()->json([
                        'message' => 'Unauthorized',
                    ], 401);
                }
                break;

            default:
                return response()->json([
                    'message' => 'Unauthorized',
                ], 401);
        }

        return new UserResource($user->load('company'));
    }

    public function update(UserUpdateRequest $request, User $user): UserResource
    {
        $data = $request->only([
            'name',
            'email',
            'password',
            'phoneNumber',
            'address',
            'companyId',
        ]);

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update([
            'name' => $data['name'] ?? $user->name,
            'email' => $data['email'] ?? $user->email,
            'password' => $data['password'] ?? $user->password,
            'phone_number' => $data['phoneNumber'] ?? $user->phone_number,
            'address' => $data['address'] ?? $user->address,
            'company_id' => $data['companyId'] ?? $user->company_id,
        ]);

        return new UserResource($user);
    }

    public function destroy(User $user): JsonResponse
    {
        $user->delete();

        return response()->json(['message' => 'User deleted successfully.']);
    }
}
