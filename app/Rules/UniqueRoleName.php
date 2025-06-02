<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Str;
use Illuminate\Translation\PotentiallyTranslatedString;
use Spatie\Permission\Models\Role;

class UniqueRoleName implements ValidationRule
{
    /**
     * @var int|null
     */
    protected ?int $ignoreRoleId = null;

    /**
     *
     * @param int|null $ignoreRoleId
     */
    public function __construct(?int $ignoreRoleId = null)
    {
        $this->ignoreRoleId = $ignoreRoleId;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $generatedName = Str::slug($value, '_');

        $query = Role::where('name', $generatedName);

        if ($this->ignoreRoleId !== null) {
            $query->where('id', '!=', $this->ignoreRoleId);
        }

        if ($query->exists()) {
            $fail("Ya existe un rol con el nombre de llave '{$generatedName}'. Por favor, elige un nombre diferente.");
        }
    }
}
