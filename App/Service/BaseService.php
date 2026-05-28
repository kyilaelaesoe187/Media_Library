<?php

namespace App\Service;

/**
 * Shared service utilities (ONLY reusable logic)
 * No business logic here
 */
abstract class BaseService 
{
    /* =========================
     * PAGINATION
     * ========================= */

    protected function getCurrentPage(array $params): int
    {
        $page = filter_var($params['pg'] ?? 1, FILTER_VALIDATE_INT);

        return ($page && $page > 0) ? $page : 1;
    }

    protected function buildPagination(
        int $totalItems,
        int $currentPage,
        int $limit = 10
    ): array {

        $totalPages = max(1, (int) ceil($totalItems / $limit));

        if ($currentPage > $totalPages) {
            $currentPage = $totalPages;
        }

        return [
            'limit' => $limit,
            'offset' => ($currentPage - 1) * $limit,
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
            'message' => $message,
            'data' => $data
        ], $data);
    }

    protected function error(string $message, array $data = []): array
    {
        return array_merge([
            'success' => false,
            'message' => $message,
             'data' => $data
        ], $data);
    }
}
