<h2 class="mb-4 text-center">🛒 Giỏ hàng của bạn</h2>

<form action="<?= BASE_URL ?>/cart/update" method="POST">
    <div class="table-responsive shadow-sm">
        <table class="table table-bordered table-hover align-middle mb-0">
            <thead class="table-light text-center">
                <tr>
                    <th scope="col" colspan="2">Sản phẩm</th>
                    <th scope="col">Đơn giá</th>
                    <th scope="col" style="width: 120px;">Số lượng</th>
                    <th scope="col">Thành tiền</th>
                    <th scope="col">Xóa</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cart as $item): ?>
                <tr>
                    <td class="text-center" style="width: 100px;">
                        <img src="<?= BASE_URL ?>/assets/images/<?= htmlspecialchars($item['hinhanh']) ?>" 
                             width="80" height="80" class="rounded object-fit-cover">
                    </td>
                    <td>
                        <?= htmlspecialchars($item['ten_sanpham']) ?>
                    </td>
                    <td class="text-end">
                        <?= number_format($item['gia'], 0, ',', '.') ?>₫
                    </td>
                    <td>
                        <input type="number" 
                               name="qty[<?= $item['id'] ?>]" 
                               value="<?= $item['qty'] ?>" 
                               min="0" 
                               class="form-control text-center">
                    </td>
                    <td class="text-end fw-bold">
                        <?= number_format($item['subtotal'], 0, ',', '.') ?>₫
                    </td>
                    <td class="text-center">
                        <a href="<?= BASE_URL ?>/cart/remove/<?= $item['id'] ?>" 
                           class="btn btn-danger btn-sm"
                           onclick="return confirm('Xóa sản phẩm này khỏi giỏ?')">Xóa</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

      <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mt-4">
            <h4 class="mb-3 mb-md-0">
                   Tổng cộng: <span class="text-danger fw-bolder"><?= number_format($total_price, 0, ',', '.') ?>₫</span>
                </h4>
                <div class="d-grid gap-2 d-md-flex">
                    <a href="<?= BASE_URL ?>/cart/clear" 
                    class="btn btn-outline-danger" 
                    onclick="return confirm('Bạn có chắc muốn xóa toàn bộ giỏ hàng?')">
                    🗑️ Xóa toàn bộ giỏ
                    </a>
                    <a href="<?= BASE_URL ?>/product/list" class="btn btn-outline-primary">
                        Tiếp tục mua hàng
                    </a>
                    <button type="submit" name="update" class="btn btn-success">
                        Cập nhật giỏ hàng
                    </button>
                </div>
            </div>
        </form> <div class="card mt-4 border-0 shadow-sm bg-light">
            <div class="card-body">
                <h5 class="card-title mb-3">Phương thức thanh toán</h5>
                
                <?php if (!isset($_SESSION['user'])): ?>
                    <div class="alert alert-info alert-dismissible fade show" role="alert">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Thông báo:</strong> Vui lòng <a href="<?= BASE_URL ?>/auth/login" class="alert-link"><u>đăng nhập</u></a> hoặc <a href="<?= BASE_URL ?>/auth/register" class="alert-link"><u>đăng ký</u></a> để hoàn thành đơn hàng.
                    </div>
                <?php endif; ?>
                
                <form action="<?= BASE_URL ?>/cart/checkout" method="POST">
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="payment_method" id="cod" value="cod" checked>
                            <label class="form-check-label" for="cod">
                                🏠 Thanh toán khi nhận hàng (COD)
                            </label>
                        </div>
                        <div class="form-check mt-2">
                            <input class="form-check-input" type="radio" name="payment_method" id="qr" value="qr">
                            <label class="form-check-label" for="qr">
                                📱 Chuyển khoản ngân hàng (QR Code)
                            </label>
                        </div>
                    </div>
                    
                    <div class="d-grid gap-2 d-md-flex justify-content-end">
                        <?php if (isset($_SESSION['user'])): ?>
                            <button type="submit" class="btn btn-danger btn-lg" onclick="return confirm('Xác nhận đặt hàng?')">
                                💳 Tiến hành đặt hàng
                            </button>
                        <?php else: ?>
                            <a href="<?= BASE_URL ?>/auth/login" class="btn btn-danger btn-lg">
                                <i class="fas fa-sign-in-alt me-2"></i> Đăng nhập để đặt hàng
                            </a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
        </div>
</form>