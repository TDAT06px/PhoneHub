<?php
// /models/Product.php

class Product extends Database {

    /**
     * Lấy danh sách sản phẩm (Có phân trang & Lọc & Sắp xếp & Đếm comment)
     */
    public function getAll($limit, $offset, $filters = []) {
        // Subquery lấy số lượng comment
        $sql = "SELECT s.*, 
                       (SELECT COUNT(*) FROM binhluan WHERE id_sanpham = s.id) as review_count
                FROM sanpham s";
        
        $where_clauses = ["1=1"]; 
        $params = [];

        // 1. CÁC BỘ LỌC (WHERE)
        // Lọc theo danh mục
        if (!empty($filters['category_id'])) {
            $where_clauses[] = "id_danhmuc = :category_id";
            $params[':category_id'] = (int)$filters['category_id'];
        }
        // Lọc theo giá
        if (!empty($filters['min_price'])) {
            $where_clauses[] = "gia >= :min_price";
            $params[':min_price'] = (float)$filters['min_price'];
        }
        if (!empty($filters['max_price'])) {
            $where_clauses[] = "gia <= :max_price";
            $params[':max_price'] = (float)$filters['max_price'];
        }
        // Lọc theo đánh giá (sao)
        if (!empty($filters['rating'])) {
            $where_clauses[] = "avg_rating >= :rating";
            $params[':rating'] = (float)$filters['rating'];
        }
        
        // Lọc theo từ khóa tìm kiếm
        if (!empty($filters['keyword'])) {
            $where_clauses[] = "ten_sanpham LIKE :keyword";
            $params[':keyword'] = '%' . $filters['keyword'] . '%';
        }

        $sql .= " WHERE " . implode(" AND ", $where_clauses);

        // 2. SẮP XẾP (ORDER BY) - [PHẦN MỚI THÊM]
        $sort = $filters['sort'] ?? 'new'; // Mặc định là mới nhất
        switch ($sort) {
            case 'price_asc':
                $sql .= " ORDER BY gia ASC";
                break;
            case 'price_desc':
                $sql .= " ORDER BY gia DESC";
                break;
            case 'name_asc':
                $sql .= " ORDER BY ten_sanpham ASC";
                break;
            case 'name_desc':
                $sql .= " ORDER BY ten_sanpham DESC";
                break;
            case 'view':
                $sql .= " ORDER BY luot_xem DESC";
                break;
            default: // 'new'
                $sql .= " ORDER BY id DESC";
                break;
        }

        // 3. PHÂN TRANG (LIMIT)
        $sql .= " LIMIT :limit OFFSET :offset";
        
        $params[':limit'] = (int)$limit;
        $params[':offset'] = (int)$offset;
        
        return self::query($sql, $params, true);
    }

    /**
     * Đếm tổng số sản phẩm (Dùng cho phân trang)
     */
    public function countAll($filters = []) {
        $sql = "SELECT COUNT(*) as total FROM sanpham";
        $where_clauses = ["1=1"]; 
        $params = [];

        if (!empty($filters['category_id'])) {
            $where_clauses[] = "id_danhmuc = :category_id";
            $params[':category_id'] = (int)$filters['category_id'];
        }
        if (!empty($filters['min_price'])) {
            $where_clauses[] = "gia >= :min_price";
            $params[':min_price'] = (float)$filters['min_price'];
        }
        if (!empty($filters['max_price'])) {
            $where_clauses[] = "gia <= :max_price";
            $params[':max_price'] = (float)$filters['max_price'];
        }
        if (!empty($filters['rating'])) {
            $where_clauses[] = "avg_rating >= :rating";
            $params[':rating'] = (float)$filters['rating'];
        }
        if (!empty($filters['keyword'])) {
            $where_clauses[] = "ten_sanpham LIKE :keyword";
            $params[':keyword'] = '%' . $filters['keyword'] . '%';
        }

        $sql .= " WHERE " . implode(" AND ", $where_clauses);
        $result = self::query($sql, $params, false);
        return $result['total'];
    }

    // Lấy chi tiết 1 sản phẩm
    public function getById($id) {
        $sql = "SELECT * FROM sanpham WHERE id = :id";
        return self::query($sql, [':id' => $id], false);
    }

    // Lấy nhiều sản phẩm theo danh sách ID (Dùng cho giỏ hàng)
    public function getByIds($ids) {
        if (empty($ids)) return [];
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $sql = "SELECT * FROM sanpham WHERE id IN ($placeholders)";
        return self::query($sql, array_values($ids), true);
    }

    // Thêm sản phẩm
    public function create($data) {
        $sql = "INSERT INTO sanpham (ten_sanpham, gia, hinhanh, id_danhmuc, mo_ta, thong_so_ky_thuat, so_luong_ton) 
                VALUES (:ten_sanpham, :gia, :hinhanh, :id_danhmuc, :mo_ta, :thong_so_ky_thuat, :so_luong_ton)";
        
        $params = [
            ':ten_sanpham'  => $data['ten_sanpham'],
            ':gia'          => $data['gia'],
            ':hinhanh'      => $data['hinhanh'],
            ':id_danhmuc'   => (int)$data['id_danhmuc'],
            ':mo_ta'        => $data['mo_ta'],
            ':thong_so_ky_thuat' => $data['thong_so_ky_thuat'],
            ':so_luong_ton' => (int)$data['so_luong_ton']
        ];
        return self::execute($sql, $params);
    }

    // Cập nhật sản phẩm
    public function update($id, $data) {
        $sql = "UPDATE sanpham SET 
                    ten_sanpham = :ten_sanpham, 
                    gia = :gia, 
                    hinhanh = :hinhanh, 
                    id_danhmuc = :id_danhmuc, 
                    mo_ta = :mo_ta, 
                    thong_so_ky_thuat = :thong_so_ky_thuat,
                    so_luong_ton = :so_luong_ton
                WHERE id = :id";
        
        $params = [
            ':ten_sanpham'  => $data['ten_sanpham'],
            ':gia'          => $data['gia'],
            ':hinhanh'      => $data['hinhanh'],
            ':id_danhmuc'   => (int)$data['id_danhmuc'],
            ':mo_ta'        => $data['mo_ta'],
            ':thong_so_ky_thuat' => $data['thong_so_ky_thuat'],
            ':so_luong_ton' => (int)$data['so_luong_ton'],
            ':id'           => (int)$id
        ];
        return self::execute($sql, $params);
    }
    
    // Xóa sản phẩm
    public function delete($id) {
        $sql = "DELETE FROM sanpham WHERE id = :id";
        return self::execute($sql, [':id' => (int)$id]);
    }

    public function searchByName($keyword) {
        $search_term = '%' . $keyword . '%';
        $sql = "SELECT * FROM sanpham WHERE ten_sanpham LIKE :keyword";
        return self::query($sql, [':keyword' => $search_term]);
    }
   
    // Sản phẩm liên quan
    public function getRelated($category_id, $exclude_id, $limit = 4) {
        $sql = "SELECT * FROM sanpham 
                WHERE id_danhmuc = :cat_id AND id != :ex_id 
                ORDER BY RAND() LIMIT :limit";
        $params = [
            ':cat_id' => $category_id,
            ':ex_id' => $exclude_id,
            ':limit' => (int)$limit
        ];
        return self::query($sql, $params);
    }
}
?>