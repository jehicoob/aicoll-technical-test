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

/**
 * @OA\Info(
 *     title="Companies API",
 *     version="1.0.0",
 *     description="API para la gestión de empresas",
 *     @OA\Contact(
 *         email="admin@example.com"
 *     )
 * )
 * 
 * @OA\Server(
 *     url="/api/v1",
 *     description="API Server"
 * )
 * 
 * @OA\Tag(
 *     name="Companies",
 *     description="Endpoints para la gestión de empresas"
 * )
 */
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
     * @OA\Get(
     *     path="/companies",
     *     summary="Obtener lista de empresas",
     *     description="Retorna una lista paginada de todas las empresas",
     *     tags={"Companies"},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de empresas obtenida con éxito",
     *         @OA\JsonContent(ref="#/components/schemas/CompanyCollection")
     *     )
     * )
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
     * @OA\Post(
     *     path="/companies",
     *     summary="Crear una nueva empresa",
     *     description="Almacena una nueva empresa y retorna sus datos",
     *     tags={"Companies"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"nit", "name", "address", "phone"},
     *             @OA\Property(property="nit", type="string", maxLength=20, example="900123456-7"),
     *             @OA\Property(property="name", type="string", maxLength=200, example="Empresa ABC"),
     *             @OA\Property(property="address", type="string", maxLength=200, example="Calle 123 #45-67"),
     *             @OA\Property(property="phone", type="string", maxLength=50, example="3001234567"),
     *             @OA\Property(property="status", type="string", enum={"active", "inactive"}, example="active")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Empresa creada con éxito",
     *         @OA\JsonContent(ref="#/components/schemas/CompanyResource")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Datos inválidos"
     *     )
     * )
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
     * @OA\Get(
     *     path="/companies/{nit}",
     *     summary="Obtener detalle de una empresa",
     *     description="Retorna los datos de una empresa específica",
     *     tags={"Companies"},
     *     @OA\Parameter(
     *         name="nit",
     *         in="path",
     *         required=true,
     *         description="NIT de la empresa",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Empresa encontrada",
     *         @OA\JsonContent(ref="#/components/schemas/CompanyResource")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Empresa no encontrada"
     *     )
     * )
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
     * @OA\Put(
     *     path="/companies/{nit}",
     *     summary="Actualizar una empresa",
     *     description="Actualiza los datos de una empresa específica",
     *     tags={"Companies"},
     *     @OA\Parameter(
     *         name="nit",
     *         in="path",
     *         required=true,
     *         description="NIT de la empresa a actualizar",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", maxLength=200, example="Empresa XYZ Actualizada"),
     *             @OA\Property(property="address", type="string", maxLength=200, example="Av Principal #100-200"),
     *             @OA\Property(property="phone", type="string", maxLength=50, example="3109876543"),
     *             @OA\Property(property="status", type="string", enum={"active", "inactive"}, example="inactive")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Empresa actualizada con éxito",
     *         @OA\JsonContent(ref="#/components/schemas/CompanyResource")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Empresa no encontrada"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Datos inválidos"
     *     )
     * )
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
     * @OA\Delete(
     *     path="/companies/{nit}",
     *     summary="Eliminar una empresa",
     *     description="Elimina una empresa específica",
     *     tags={"Companies"},
     *     @OA\Parameter(
     *         name="nit",
     *         in="path",
     *         required=true,
     *         description="NIT de la empresa a eliminar",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Empresa eliminada con éxito",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Company deleted successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Empresa no encontrada"
     *     )
     * )
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
     * @OA\Delete(
     *     path="/companies/inactive",
     *     summary="Eliminar empresas inactivas",
     *     description="Elimina todas las empresas con estado inactivo",
     *     tags={"Companies"},
     *     @OA\Response(
     *         response=200,
     *         description="Empresas inactivas eliminadas con éxito",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="5 inactive companies have been deleted"),
     *             @OA\Property(property="count", type="integer", example=5)
     *         )
     *     )
     * )
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