<?php
// /views/product/edit.php
// (ĐÃ CẬP NHẬT: Upload ảnh + Xem trước ảnh cũ)
?>
<h2 class="mb-4">✏️ Sửa sản phẩm: <?= htmlspecialchars($product['ten_sanpham']) ?></h2>

<div class="card shadow-sm border-0">
    <div class="card-body p-4">
        <form action="<?= BASE_URL ?>/product/edit/<?= $product['id'] ?>" method="POST" enctype="multipart/form-data">
            
            <div class="row">
                <div class="col-md-8">
                    <div class="mb-3">
                        <label for="ten_sanpham" class="form-label">Tên sản phẩm (*)</label>
                        <input type="text" class="form-control" id="ten_sanpham" name="ten_sanpham" required 
                               value="<?= htmlspecialchars($product['ten_sanpham']) ?>">
                    </div>
                </div>
                <div class="col-md-4">
                     <div class="mb-3">
                        <label for="id_danhmuc" class="form-label">Danh mục (*)</label>
                        <select class="form-select" id="id_danhmuc" name="id_danhmuc" required>
                            <option value="">-- Chọn danh mục --</option>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?= $category['id'] ?>" 
                                    <?= ($category['id'] == $product['id_danhmuc']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($category['ten_danhmuc']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="gia" class="form-label">Giá (VNĐ) (*)</label>
                        <input type="number" class="form-control" id="gia" name="gia" required min="0" 
                               value="<?= $product['gia'] ?>">
                    </div>
                </div>
                 <div class="col-md-4">
                    <div class="mb-3">
                        <label for="so_luong_ton" class="form-label">Số lượng tồn kho (*)</label>
                        <input type="number" class="form-control" id="so_luong_ton" name="so_luong_ton" required min="0" 
                               value="<?= $product['so_luong_ton'] ?>">
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label">Hình ảnh hiện tại</label>
                        <div class="mb-2">
                            <?php if (!empty($product['hinhanh'])): ?>
                                <img src="<?= BASE_URL ?>/assets/images/<?= htmlspecialchars($product['hinhanh']) ?>" 
                                     class="img-thumbnail" style="height: 100px;">
                            <?php else: ?>
                                <span class="text-muted">Chưa có ảnh</span>
                            <?php endif; ?>
                        </div>
                        
                        <label for="hinhanh" class="form-label">Chọn ảnh mới (Nếu muốn đổi)</label>
                        <input type="file" class="form-control" id="hinhanh" name="hinhanh" accept="image/*">
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label for="mo_ta" class="form-label">Mô tả chi tiết</label>
                <textarea class="form-control" id="mo_ta" name="mo_ta" rows="5"
                ><?= htmlspecialchars($product['mo_ta'] ?? '') ?></textarea>
            </div>
            
            <div class="mb-3">
                <label for="thong_so_ky_thuat" class="form-label">Thông số kỹ thuật</label>
                <textarea class="form-control" id="thong_so_ky_thuat" name="thong_so_ky_thuat" rows="8"
                ><?= htmlspecialchars($product['thong_so_ky_thuat'] ?? '') ?></textarea>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="<?= BASE_URL ?>/product/list" class="btn btn-outline-secondary">Hủy bỏ</a>
                <button type="submit" class="btn btn-warning">Cập nhật</button>
            </div>
            
        </form>
    </div>
</div>