<?php
declare(strict_types=1);
namespace App\Responses;

final class JsonResponse extends BaseResponse
{
    final public function withData(array $data): string
    {
        return json_encode($data);
    }

}