<?php

namespace App\Http\Requests\Users;

use App\Enums\RoleName;
use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if (! ($this->user()?->can('updateUser') ?? false)) {
            return false;
        }

        /** @var User $member */
        $member = $this->route('user');

        return ! $member->hasRole(RoleName::Admin);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        /** @var User $member */
        $member = $this->route('user');

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($member),
            ],
            'roles' => ['required', 'array', 'min:1'],
            'roles.*' => [
                'required',
                Rule::enum(RoleName::class)->only([
                    RoleName::Member,
                    RoleName::ProjectLead,
                ]),
            ],
        ];
    }
}
