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
        $section = $this->validateCategory(
            $queryParams['cat'] ?? null,
            ['books', 'movies', 'music']
        );

        $search = $this->normalizeSearch(
            $queryParams['s'] ?? null
        );

        $currentPage = $this->getCurrentPage($queryParams);

        $totalItems = $this->repo->count($section, $search);

        $pagination = $this->buildPagination($totalItems, $currentPage);

        $catalog = $this->loadCatalog(
            $section,
            $search,
            $pagination
        );

        return [
            'catalog' => $catalog,
            'section' => $section,
            'search' => $search,
            'currentPage' => $pagination['currentPage'],
            'totalPages' => $pagination['totalPages'],
            'pageTitle' => $this->buildPageTitle($section),
            'queryString' => $this->buildQueryString($section, $search)
        ];
    }

    private function loadCatalog(
        ?string $section,
        ?string $search,
        array $pagination
    ): array {

        if ($search && $section) {
            return $this->repo->search(
                $search,
                $section,
                $pagination['limit'],
                $pagination['offset']
            );
        }

        if ($search) {
            return $this->repo->search(
                $search,
                null,
                $pagination['limit'],
                $pagination['offset']
            );
        }

        if ($section) {
            return $this->repo->findByCategory(
                $section,
                $pagination['limit'],
                $pagination['offset']
            );
        }

        return $this->repo->findAll(
            $pagination['limit'],
            $pagination['offset']
        );
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
