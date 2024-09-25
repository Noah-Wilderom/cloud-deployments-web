<?php

namespace Modules\Services\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreServicePlanRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            "team_id" => [Rule::requiredIf(fn() => auth()->user()->allTeams()->count() > 1), "exists:teams,id"],
            "service_type" => ["nullable"],
            "name" => ["required", "string", "max:255"],
            "base_price" => ["required", "integer"],
//            "resources" => ["required"],
//            "settings" => ["required"],
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
