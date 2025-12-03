<div class="text-center mb-5">
    <h2 class="fw-bold">Liên hệ với chúng tôi</h2>
    <p class="text-muted">Chúng tôi luôn sẵn sàng hỗ trợ bạn 24/7</p>
</div>

<div class="row">
    <div class="col-lg-5 mb-4">
        <div class="card border-0 shadow-sm h-100 bg-primary text-white">
            <div class="card-body p-4 p-md-5">
                <h4 class="mb-4 fw-bold">Thông tin liên lạc</h4>
                
                <div class="d-flex mb-4">
                    <div class="me-3">
                        <i class="fas fa-map-marker-alt fs-4"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1">Địa chỉ:</h6>
                        <p class="mb-0 text-white-50">123 Đường ABC, Quận Hải Châu, TP. Đà Nẵng, Việt Nam</p>
                    </div>
                </div>

                <div class="d-flex mb-4">
                    <div class="me-3">
                        <i class="fas fa-phone-alt fs-4"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1">Điện thoại:</h6>
                        <p class="mb-0 text-white-50">+84 905 123 456</p>
                        <p class="mb-0 text-white-50">+84 905 987 654</p>
                    </div>
                </div>

                <div class="d-flex mb-4">
                    <div class="me-3">
                        <i class="fas fa-envelope fs-4"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1">Email:</h6>
                        <p class="mb-0 text-white-50">support@shopphonehub.com</p>
                        <p class="mb-0 text-white-50">contact@shopphonehub.com</p>
                    </div>
                </div>

                <div class="mt-5">
                    <h6 class="fw-bold mb-3">Mạng xã hội:</h6>
                    <a href="#" class="text-white me-3 fs-4"><i class="fab fa-facebook"></i></a>
                    <a href="#" class="text-white me-3 fs-4"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="text-white fs-4"><i class="fab fa-youtube"></i></a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-7 mb-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body p-4 p-md-5">
                <h4 class="mb-4 fw-bold text-primary">Gửi tin nhắn cho chúng tôi</h4>

                <?php if (!empty($success)): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i> <?= $success ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>
                
                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                         <i class="fas fa-exclamation-circle me-2"></i> <?= $error ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <form action="<?= BASE_URL ?>/page/contact" method="POST">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Họ và tên</label>
                            <input type="text" class="form-control" id="name" name="name" required placeholder="Nhập tên của bạn">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required placeholder="Nhập email">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="subject" class="form-label">Tiêu đề</label>
                        <input type="text" class="form-control" id="subject" name="subject" placeholder="Bạn cần hỗ trợ vấn đề gì?">
                    </div>
                    <div class="mb-3">
                        <label for="message" class="form-label">Nội dung tin nhắn</label>
                        <textarea class="form-control" id="message" name="message" rows="5" required placeholder="Nhập nội dung..."></textarea>
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-lg">Gửi Tin Nhắn</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-12">
        <div class="card shadow-sm border-0 overflow-hidden">
            <iframe 
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3833.823610486392!2d108.22079087508009!3d16.074618784606263!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31421830638525b3%3A0x64468f2373059637!2zQ8OqdSBS4buTbmcsIFRo4bqhY2ggVGhhbmcsIFPhuqFuIFRyw6AsIMSQw6AgTuG6tW5nLCBWaWV0bmFt!5e0!3m2!1sen!2s!4v1714478123456!5m2!1sen!2s" 
                width="100%" height="400" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade">
            </iframe>
        </div>
    </div>
</div>