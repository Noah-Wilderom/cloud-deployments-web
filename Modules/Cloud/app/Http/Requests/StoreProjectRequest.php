<?php

namespace Modules\Cloud\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;
use Modules\Cloud\Enums\ProjectTemplate;

class StoreProjectRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            "team_id" => [Rule::requiredIf(fn() => auth()->user()->allTeams()->count() > 1), "exists:teams,id"],
            "customer_id" => ["nullable", "exists:customers,id"],
            "domain_id" => ["required", "exists:domains,id"],
            "server_id" => ["required", "exists:servers,id"],
            "git_repository" => ["required"],
            "name" => ["required", "string", "max:255"],
            "sub_domain" => ["nullable", "string", "max:255"],
            "template" => ["required", new Enum(ProjectTemplate::class)],
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
