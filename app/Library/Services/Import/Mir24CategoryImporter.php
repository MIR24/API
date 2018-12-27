<?php

namespace App\Library\Services\Import;

use Illuminate\Support\Facades\DB;

class Mir24CategoryImporter
{
    public function getCategories(): array
    {
        $query = "SELECT id, title as name, translateTitle AS url, deleted_at, priority "
            . "FROM   tags "
            . "WHERE  title IS NOT NULL "
            . "AND    type = 3 "
            . "AND    (deleted_at IS NULL OR deleted_at >= DATE_SUB(CURDATE(), INTERVAL 12 HOUR))";

        return DB::connection('mir24')->select($query);
    }

    public function updateCategories(array $categories): void
    {
        $insertCategories = [];

        foreach ($categories as $category) {
            if ($category->deleted_at != null) {
                $this->removeCategory($category->id);
            } else {
                $insertCategories[] = $category;
            }
        }

        $this->saveCategories($insertCategories);
    }

    private function saveCategories($categories)
    {
        $query = "INSERT INTO categories (`id`, `name`, `url`, `order`) "
            . "VALUES (?, ?, ?, ?)"
            . "ON DUPLICATE KEY UPDATE "
            . "   id = VALUES(id), name = VALUES(name), url = VALUES(url), "
            . "   `order` = VALUES(`order`)";

        foreach ($categories as $category) {
            DB::insert($query, [$category->id, $category->name, $category->url, $category->priority ?? 0]);
        }
    }

    private function removeCategory($id)
    {
        $query = "DELETE FROM categories WHERE id = ?";
        DB::delete($query, [$id]);
    }
}
