<?php

namespace App\Http\Requests\Teams;

use App\Enums\RoleName;
use App\Models\Team;
use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTeamMemberRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->can('addTeamMember') ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        /** @var Team $team */
        $team = $this->route('team');

        return [
            'user_id' => [
                'required',
                'integer',
                'exists:users,id',
                Rule::unique('team_user', 'user_id')->where('team_id', $team->id),
                function (string $attribute, mixed $value, \Closure $fail): void {
                    $member = User::query()->find($value);

                    if ($member === null) {
                        return;
                    }

                    if ($member->hasRole(RoleName::Admin)) {
                        $fail('Admins cannot be added to a team.');

                        return;
                    }

                    if (
                        ! $member->hasRole(RoleName::Member)
                        && ! $member->hasRole(RoleName::ProjectLead)
                    ) {
                        $fail('Only members and project leads can be added to a team.');
                    }
                },
            ],
        ];
    }
}
