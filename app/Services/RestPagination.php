<?php
declare(strict_types=1);
namespace App\Services;

use CodeIgniter\Pager\Pager;

final class RestPagination
{
    public function __construct(readonly Pager $pager)
    {
    }
    private int $perPage = 10;
    final public function paginate(array $data): array
    {
        $perPage = $this->perPage;
        $currentPage = $this->pager->getCurrentPage();
        $offset = ($currentPage - 1) * $perPage;
        $paginatedResults = array_slice($data, $offset, $perPage);
        return [
            'data' => $paginatedResults,
            'pagination' => [
                'total' => count($data),
                'per_page' => $perPage,
                'current_page' => $currentPage,
                'last_page' => ceil(count($data) / $perPage),
            ],
        ];
    }
}