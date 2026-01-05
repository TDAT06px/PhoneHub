<?php

class Category extends Database {

    public function getAll() {
        $sql = "SELECT * FROM danhmuc ORDER BY parent_id ASC, id ASC";
        return self::query($sql, [], true);
    }

    public function getById($id) {
        $sql = "SELECT * FROM danhmuc WHERE id = :id";
        return self::query($sql, [':id' => $id], false);
    }

    public function getCategoryTree() {
        $categories = self::query("SELECT * FROM danhmuc ORDER BY parent_id, ten_danhmuc");
        
        $categoryTree = [];
        $childrenOf = [];

        foreach ($categories as $category) {
            $childrenOf[$category['parent_id']][] = $category;
        }

        foreach ($childrenOf[null] as $category) {
            $category['children'] = $this->buildTree($category['id'], $childrenOf);
            $categoryTree[] = $category;
        }
        
        return $categoryTree;
    }

    private function buildTree($parentId, $childrenOf) {
        $tree = [];
        if (isset($childrenOf[$parentId])) {
            foreach ($childrenOf[$parentId] as $category) {
                $category['children'] = $this->buildTree($category['id'], $childrenOf);
                $tree[] = $category;
            }
        }
        return $tree;
    }
}
?>