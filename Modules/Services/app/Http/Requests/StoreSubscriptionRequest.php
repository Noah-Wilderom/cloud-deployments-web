<?php

namespace Modules\Services\Http\Requests;

use App\Rules\UuidV7Rule;
use Illuminate\Foundation\Http\FormRequest;

class StoreSubscriptionRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            "customer_id" => ["required", "exists:customers,id"],
            "service_plan_id" => ["required", "exists:service_plans,id"],
            "service_id" => ["nullable", new UuidV7Rule],
            "name" => ["nullable", "string", "max:255"],
            "price" => ["nullable", "integer"],
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
}
