<?php

namespace Modules\Cloud\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;
use Modules\Cloud\Enums\ServerType;

class StoreServerRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            "team_id" => [Rule::requiredIf(fn() => auth()->user()->allTeams()->count() > 1), "exists:teams,id"],
            "customer_id" => ["required", "exists:customers,id"],
            "type" => ["required", new Enum(ServerType::class)],
            "name" => ["required", "string", "max:255"],
            "cloud_provider_type" => ["required", "int"],
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
