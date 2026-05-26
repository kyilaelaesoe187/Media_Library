<?php

namespace App\Service;

use App\Contract\BaseInterface;

/**
 * Shared service utilities (ONLY reusable logic)
 * No business logic here
 */
abstract class BaseService //implements BaseInterface
{
    /* =========================
     * PAGINATION
     * ========================= */

    protected function getCurrentPage(array $params): int
    {
        $page = filter_var($params['pg'] ?? 1, FILTER_VALIDATE_INT);

        return ($page === false || $page < 1) ? 1 : $page;
    }



    protected function buildPagination(
        int $totalItems,
        int $currentPage,
        int $itemsPerPage = 8
    ): array {

        $totalPages = max(1, (int) ceil($totalItems / $itemsPerPage));

        $currentPage = min($currentPage, $totalPages);

        return [
            'limit' => $itemsPerPage,
            'offset' => ($currentPage - 1) * $itemsPerPage,
            'currentPage' => $currentPage,
            'totalPages' => $totalPages
        ];
    }

    /* =========================
     * INPUT HELPERS
     * ========================= */

    protected function normalizeSearch(?string $search): ?string
    {
        $search = trim((string) $search);
        return $search !== '' ? $search : null;
    }

    protected function validateCategory(?string $category, array $allowed): ?string
    {
        return in_array($category, $allowed, true) ? $category : null;
    }

    /* =========================
     * QUERY STRING
     * ========================= */

    protected function buildQueryString(?string $category, ?string $search): string
    {
        $params = [];

        if ($category) {
            $params[] = 'cat=' . urlencode($category);
        }

        if ($search) {
            $params[] = 's=' . urlencode($search);
        }

        return implode('&', $params);
    }

    /* =========================
     * RESPONSE FORMATTERS
     * ========================= */

    protected function success(string $message, array $data = []): array
    {
        return array_merge([
            'success' => true,
            'message' => $message
        ], $data);
    }

    protected function error(string $message, array $data = []): array
    {
        return array_merge([
            'success' => false,
            'message' => $message
        ], $data);
    }
}
