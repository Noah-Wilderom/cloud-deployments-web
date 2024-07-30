<?php

namespace Modules\Services\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Services\Models\ServicePlan;

/**
 * @mixin ServicePlan
 */
class ServicePlanResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "service_type" => $this->service_type,
            "name" => $this->name,
            "base_price" => $this->base_price,
            "resources" => $this->resources,
            "settings" => $this->settings,
        ];
    }
}
