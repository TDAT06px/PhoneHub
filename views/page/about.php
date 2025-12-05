<?php
// views/page/about.php
?>

<style>
    /* CSS riêng cho trang About */
    .carousel-item img {
        height: 400px; 
        object-fit: cover;
        object-position: center; 
    }
    .text-shadow {
        text-shadow: 0 2px 10px rgba(0,0,0,0.7);
    }
    .policy-icon {
        width: 60px; height: 60px;
        display: flex; align-items: center; justify-content: center;
        border-radius: 50%;
        background: rgba(var(--bs-primary-rgb), 0.1);
        font-size: 1.8rem;
    }
    .about-content {
        line-height: 1.8;
        color: #555;
    }
</style>

<div class="mb-5">
    <div id="aboutCarousel" class="carousel slide shadow rounded-4 overflow-hidden" data-bs-ride="carousel">
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#aboutCarousel" data-bs-slide-to="0" class="active"></button>
            <button type="button" data-bs-target="#aboutCarousel" data-bs-slide-to="1"></button>
            <button type="button" data-bs-target="#aboutCarousel" data-bs-slide-to="2"></button>
        </div>
        <div class="carousel-inner">
            <div class="carousel-item active" data-bs-interval="3000">
               <img src="https://images.unsplash.com/photo-1522071820081-009f0129c71c?q=80&w=1200&auto=format&fit=crop" class="d-block w-100" alt="Shop Team">
                <div class="carousel-caption d-none d-md-block pb-5 text-shadow">
                    <h2 class="display-4 fw-bold">Chào mừng đến ShopPhoneHub</h2>
                    <p class="fs-4">Điểm đến tin cậy cho tín đồ công nghệ.</p>
                </div>
            </div>
            <div class="carousel-item" data-bs-interval="3000">
                <img src="https://images.unsplash.com/photo-1519389950473-47ba0277781c?q=80&w=1200&fit=crop" class="d-block w-100" alt="Work space">
                <div class="carousel-caption d-none d-md-block pb-5 text-shadow">
                    <h2 class="display-4 fw-bold">Không gian làm việc chuyên nghiệp</h2>
                    <p class="fs-4">Đội ngũ hỗ trợ tận tâm 24/7.</p>
                </div>
            </div>
            <div class="carousel-item" data-bs-interval="3000">
              <img src="https://images.unsplash.com/photo-1498050108023-c5249f4df085?q=80&w=1200&auto=format&fit=crop" class="d-block w-100" alt="Tech">
                <div class="carousel-caption d-none d-md-block pb-5 text-shadow">
                    <h2 class="display-4 fw-bold">Sản phẩm chính hãng</h2>
                    <p class="fs-4">Cam kết chất lượng 100%.</p>
                </div>
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#aboutCarousel" data-bs-slide="prev"><span class="carousel-control-prev-icon"></span></button>
        <button class="carousel-control-next" type="button" data-bs-target="#aboutCarousel" data-bs-slide="next"><span class="carousel-control-next-icon"></span></button>
    </div>
</div>

<div class="container">
    <div class="row justify-content-center mb-5">
        <div class="col-lg-10">
            <div class="text-center mb-5">
                <h1 class="fw-bold text-primary mb-3">Về ShopPhoneHub</h1>
                <p class="lead text-muted">Chúng tôi không chỉ bán điện thoại, chúng tôi mang đến trải nghiệm công nghệ đỉnh cao.</p>
            </div>
            
            <div class="about-content">
                <p>Thành lập từ năm 2025, <strong>ShopPhoneHub</strong> nhanh chóng trở thành một trong những hệ thống bán lẻ các thiết bị di động uy tín hàng đầu. Với phương châm <em>"Khách hàng là trọng tâm"</em>, chúng tôi luôn nỗ lực không ngừng để mang đến những sản phẩm chất lượng nhất với mức giá cạnh tranh nhất thị trường.</p>
                <p>Chúng tôi chuyên cung cấp các dòng sản phẩm chính hãng từ Apple, Samsung, Xiaomi, Oppo... cùng các loại phụ kiện đa dạng. Đội ngũ nhân viên tư vấn nhiệt tình, am hiểu công nghệ sẽ giúp bạn tìm được sản phẩm ưng ý nhất.</p>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-5">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100 text-center py-4">
                <div class="card-body">
                    <div class="policy-icon mx-auto mb-3 text-primary"><i class="fas fa-truck-fast"></i></div>
                    <h5 class="fw-bold">Miễn phí vận chuyển</h5>
                    <p class="text-muted small mb-0">Cho đơn hàng từ 500k trên toàn quốc.</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100 text-center py-4">
                <div class="card-body">
                    <div class="policy-icon mx-auto mb-3 text-success"><i class="fas fa-shield-alt"></i></div>
                    <h5 class="fw-bold">Bảo hành uy tín</h5>
                    <p class="text-muted small mb-0">Cam kết 1 đổi 1 trong 30 ngày đầu.</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100 text-center py-4">
                <div class="card-body">
                    <div class="policy-icon mx-auto mb-3 text-warning"><i class="fas fa-headset"></i></div>
                    <h5 class="fw-bold">Hỗ trợ 24/7</h5>
                    <p class="text-muted small mb-0">Đội ngũ tư vấn viên luôn sẵn sàng.</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100 text-center py-4">
                <div class="card-body">
                    <div class="policy-icon mx-auto mb-3 text-danger"><i class="fas fa-tag"></i></div>
                    <h5 class="fw-bold">Giá cả cạnh tranh</h5>
                    <p class="text-muted small mb-0">Luôn có ưu đãi hấp dẫn mỗi ngày.</p>
                </div>
            </div>
        </div>
    </div>
</div>