<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="CompanyResource",
 *     title="Recurso de Empresa",
 *     description="Respuesta de una empresa individual",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="nit", type="string", example="900123456-7"),
 *     @OA\Property(property="name", type="string", example="Empresa ABC"),
 *     @OA\Property(property="address", type="string", example="Calle 123 #45-67"),
 *     @OA\Property(property="phone", type="string", example="3001234567"),
 *     @OA\Property(property="status", type="string", enum={"active", "inactive"}, example="active"),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2023-01-01T12:00:00Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2023-01-01T12:00:00Z")
 * )
 */
class CompanyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'nit' => $this->nit,
            'name' => $this->name,
            'address' => $this->address,
            'phone' => $this->phone,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}