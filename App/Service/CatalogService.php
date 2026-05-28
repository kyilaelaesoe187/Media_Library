<?php

namespace App\Service;

use App\Contract\BaseInterface;
use App\Contract\CatalogRepositoryInterface;

class CatalogService extends BaseService
{
    private CatalogRepositoryInterface $repo;

    public function __construct(CatalogRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

    public function getHomePageData(): array
    {
        return [
            'random' => $this->repo->findRandom(),
            'pageTitle' => 'Personal Media Library',
            'section' => 'catalog'
        ];
    }

    public function getCatalogPage(array $queryParams): array
    {
        // 1. INPUT CLEANING
        $section = $this->normalizeSearch($queryParams['cat'] ?? null);
        $search  = $this->normalizeSearch($queryParams['s'] ?? null);

        // 2. FORCE VALIDATION (SAFE MODE)
        $allowedCategories = ['books', 'movies', 'music'];

        if ($section && !in_array($section, $allowedCategories, true)) {
            $section = null;
        }

        // 3. PAGINATION SAFE
        $currentPage = $this->getCurrentPage($queryParams);

        $totalItems = $this->repo->count($section, $search);

        $pagination = $this->buildPagination($totalItems, $currentPage, 10);

        // 4. DATA LOAD
        $catalog = $this->loadCatalog(
            $section,
            $search,
            $pagination['limit'],
            $pagination['offset']
        );

        return [
            'catalog'      => $catalog,
            'section'      => $section,
            'search'       => $search,
            'currentPage'  => $pagination['currentPage'],
            'totalPages'   => $pagination['totalPages']
        ];
    }

    private function loadCatalog(
        ?string $section,
        ?string $search,
        int $limit,
        int $offset
    ): array {

        if ($search && $section) {
            return $this->repo->search($search, $section, $limit, $offset);
        }

        if ($search) {
            return $this->repo->search($search, null, $limit, $offset);
        }

        if ($section) {
            return $this->repo->findByCategory($section, $limit, $offset);
        }

        return $this->repo->findAll($limit, $offset);
    }

    private function buildPageTitle(?string $section): string
    {
        return $section ? ucfirst($section) : 'Full Catalog';
    }

    public function getById(int $id): ?array
    {
        return $this->repo->findById($id);
    }
}
