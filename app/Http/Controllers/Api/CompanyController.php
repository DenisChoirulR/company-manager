<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Company\CompanyIndexRequest;
use App\Http\Requests\Company\CompanyStoreRequest;
use App\Http\Requests\Company\CompanyUpdateRequest;
use App\Http\Resources\CompanyResource;
use App\Http\Resources\UserResource;
use App\Models\Company;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Hash;

class CompanyController extends Controller
{
    public function index(CompanyIndexRequest $request): AnonymousResourceCollection
    {
        $query = Company::query();

        if ($request->has('search') && $request->search != '') {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->has('sortBy')) {
            $query->orderBy($request->sortBy, $request->get('sortDirection', 'asc'));
        }

        $companies = $query->paginate($request->get('perPage', 10));

        return CompanyResource::collection($companies);
    }

    public function store(CompanyStoreRequest $request): JsonResponse
    {
        $company = Company::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'phone_number' => $request['phoneNumber'],
        ]);

        $user = User::factory()
            ->manager()
            ->create([
                'company_id' => $company->id,
                'email' => 'manager-'.$company->email,
            ]);

        return response()->json(['data' => [
            'company' => new CompanyResource($company),
            'user' => new UserResource($user),
        ]]);
    }

    public function show(Company $company): CompanyResource
    {
        return new CompanyResource($company);
    }

    public function update(CompanyUpdateRequest $request, Company $company): CompanyResource
    {
        $company->update([
            'name' => $request['name'],
            'email' => $request['email'],
            'phone_number' => $request['phoneNumber'],
        ]);

        return new CompanyResource($company);
    }

    public function destroy(Company $company): JsonResponse
    {
        $company->delete();

        return response()->json(['message' => 'Company deleted successfully.']);
    }
}
