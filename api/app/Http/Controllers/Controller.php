<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="API WEB 2 - Documentação",
 *      description="Com finalidade de validar materia",
 *      @OA\Contact(
 *          name="FURB",
 *          url="https://www.furb.br/pt"
 *      ),
 * )
 * 
 * @OA\Server(
 *      url=L5_SWAGGER_CONST,
 *      description="Base URL"
 * )
 * 
 */
class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}
