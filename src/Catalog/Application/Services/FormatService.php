<?php

namespace MediaLibrary\Catalog\Application\Services;

use MediaLibrary\Catalog\Domain\Repositories\FormatRepositoryInterface;
use MediaLibrary\Catalog\Infrastructure\Persistence\FormatRepository;

class FormatService
{
    private FormatRepositoryInterface $repo;

    public function __construct(?FormatRepositoryInterface $repo = null)
    {
        if ($repo === null) {
            $db = \Database::getConnection();
            $repo = new FormatRepository($db);
        }
        $this->repo = $repo;
    }

    public function format_array($category = null)
    {
        return $this->repo->get_format_drop_down($category);
    }

    public function category_drop_down()
    {
        return $this->repo->get_category_drop_down();
    }

    public function genres_array($category = null)
    {
        return $this->repo->get_genres_drop_down($category);
    }
}
