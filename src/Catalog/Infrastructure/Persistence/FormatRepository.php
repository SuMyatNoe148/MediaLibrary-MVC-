<?php

namespace MediaLibrary\Catalog\Infrastructure\Persistence;

use MediaLibrary\Catalog\Domain\Repositories\FormatRepositoryInterface;
use MediaLibrary\Shared\Infrastructure\Persistence\BaseRepository;
use PDO;

class FormatRepository extends BaseRepository implements FormatRepositoryInterface
{
    public function get_format_drop_down($category = null): array
    {
        $result = $this->db->prepare("CALL sp_get_formats_by_category (:category)");
        $result->bindValue(':category', $category, $category === null ? PDO::PARAM_NULL : PDO::PARAM_STR);
        $result->execute();

        $format = [];
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $format[$row['category']][] = $row['format'];
        }
        $result->closeCursor();
        return $format;
    }

    public function get_category_drop_down(): array
    {
        $result = $this->db->prepare("SELECT DISTINCT category FROM view_catalog ORDER BY category");
        $result->execute();
        return $result->fetchAll(PDO::FETCH_COLUMN);
    }

    public function get_genres_drop_down($category = null): array
    {
        $result = $this->db->prepare("CALL sp_get_genres_by_category (:category)");
        $result->bindValue(':category', $category, $category === null ? PDO::PARAM_NULL : PDO::PARAM_STR);
        $result->execute();

        $genre = [];
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $genre[$row['category']][] = $row['genre'];
        }
        $result->closeCursor();
        return $genre;
    }
}
