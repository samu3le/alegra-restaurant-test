<?php

namespace App\Http\Middleware\Validations\Requests\IngredientValidation;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\Ingredient;
use App\Services\Response;

/**
 * Validate data to get all Ingredient
 * @param \Illuminate\Http\Request  $request { sort_by,sort }
 * @param Closure $next Controllers\IngredientController\Find.
 *
 * @return mixed Validate data, return array validated with error or next to cotroller.
 */
class GetAll
{
    public function handle(Request $request, Closure $next)
    {
        $ingredient = new Ingredient();

        $validator = Validator::make($request['query'], [
            'sort_by' => ['nullable', 'string', 'in:' . implode(',', $ingredient->getFillable())],
            'sort' => ['nullable', 'string', 'in:asc,desc'],
        ]);

        if($validator->fails()){
            return Response::UNPROCESSABLE_ENTITY(
                message: 'Validation failed.',
                errors: $validator->errors(),
            );
        }

        $query = $request['query'];

        $query['page'] = $query['page'] ?? 1;
        $query['per_page'] = $query['per_page'] ?? 10;

        $request->merge([
            'query' => $query,
        ]);

        return $next($request);
    }
}
