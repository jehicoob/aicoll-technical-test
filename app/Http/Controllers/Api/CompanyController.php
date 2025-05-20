<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CompanyResource;
use App\Http\Resources\CompanyCollection;
use App\Http\Requests\CompanyStoreRequest;
use App\Http\Requests\CompanyUpdateRequest;
use App\Services\CompanyService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class CompanyController extends Controller
{
    /**
     * @var CompanyService
     */
    protected $companyService;

    /**
     * Constructor
     *
     * @param CompanyService $companyService
     */
    public function __construct(CompanyService $companyService)
    {
        $this->companyService = $companyService;
    }

    /**
     * Display a listing of the companies.
     *
     * @return \App\Http\Resources\CompanyCollection
     */
    public function index(): CompanyCollection
    {
        $companies = $this->companyService->getAllCompanies();
        return new CompanyCollection($companies);
    }

    /**
     * Store a newly created company in storage.
     *
     * @param \App\Http\Requests\CompanyStoreRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CompanyStoreRequest $request): JsonResponse
    {
        $company = $this->companyService->createCompany($request->validated());
        return (new CompanyResource($company))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Display the specified company.
     *
     * @param string $nit
     * @return \App\Http\Resources\CompanyResource|\Illuminate\Http\JsonResponse
     */
    public function show(string $nit): CompanyResource
    {
        $company = $this->companyService->getCompanyByNit($nit);
        return new CompanyResource($company);
    }

    /**
     * Update the specified company in storage.
     *
     * @param \App\Http\Requests\CompanyUpdateRequest $request
     * @param string $nit
     * @return \App\Http\Resources\CompanyResource|\Illuminate\Http\JsonResponse
     */
    public function update(CompanyUpdateRequest $request, string $nit)
    {
        $company = $this->companyService->updateCompany($nit, $request->validated());
        return new CompanyResource($company);
    }

    /**
     * Remove the specified company from storage.
     *
     * @param string $nit
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(string $nit): JsonResponse
    {
        $this->companyService->deleteCompany($nit);
        return response()->json([
            'message' => 'Company deleted successfully'
        ], Response::HTTP_OK);
    }

    /**
     * Remove inactive companies from storage.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteInactive(): JsonResponse
    {
        $count = $this->companyService->deleteInactiveCompanies();
        return response()->json([
            'message' => "{$count} inactive companies have been deleted",
            'count' => $count
        ], Response::HTTP_OK);
    }
}