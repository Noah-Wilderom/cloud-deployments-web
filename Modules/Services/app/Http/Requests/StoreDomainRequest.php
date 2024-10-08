<?php

namespace Modules\Services\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreDomainRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            "team_id" => [Rule::requiredIf(fn() => auth()->user()->allTeams()->count() > 1), "exists:teams,id"],
            "customer_id" => ["required", "exists:customers,id"],
            "name" => ["required", "string", "max:255"],
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
