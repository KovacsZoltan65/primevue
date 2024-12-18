<?php

namespace App\Traits;

use App\Http\Controllers\ErrorController;
use Illuminate\Support\Facades\Auth;
use Exception;

trait Functions
{
    public function getValidationRules()
    {
        //
        $rules = json_decode(file_get_contents(resource_path('js/Validation/ValidationRules.json')), true);

        return $rules;
    }

    /**
     * Használat:
     *  $roles = $this->getUserRoles(['companies', 'users']);
     * @param array|string $str
     * @return array
     */
    public function getUserRoles(array|string $prefix): array
    {
        //$permissions = ['list', 'create', 'edit', 'delete', 'restore'];
        $permissions = config('permission.permissions');
        $prefixes = is_array($prefix) ? $prefix : [$prefix];
        $userRoles = [];

        foreach ($prefixes as $str) {
            foreach ($permissions as $permission) {
                $userRoles["{$str}_{$permission}"] = Auth::user()->can("{$str} {$permission}");
            }
        }

        return $userRoles;
    }

    public function handleException(Exception $ex, string $defaultMessage, int $statusCode)
    {
        return response()->json([
            'success' => App_FALSE,
            'error' => $defaultMessage,
        ], $statusCode);
    }

    public function logError(Exception $ex, string $context, array $params):void
    {
        ErrorController::logServerError($ex, [
            'context' => $context,
            'params' => $params,
            'route' => request()->path(),
            'type' => get_class($ex),
            'severity' => 'error',
        ]);
    }

    public function generateCacheKey(string $tag, string $key): string
    {
        return "{$tag}_" . md5($key);
    }
}
