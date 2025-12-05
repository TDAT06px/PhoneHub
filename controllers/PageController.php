<?php
// controllers/PageController.php

class PageController extends Controller {

    /**
     * Hiển thị trang Giới thiệu
     * URL: /page/about
     */
    public function about() {
        $this->loadView('page/about', [
            'title' => 'Về chúng tôi'
        ]);
    }

    /**
     * Hiển thị trang Liên hệ
     * URL: /page/contact
     */
    public function contact() {
        $success_message = '';
        $error_message = '';

        // Xử lý giả lập gửi form liên hệ (Basic)
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';
            $email = $_POST['email'] ?? '';
            $message = $_POST['message'] ?? '';

            if (!empty($name) && !empty($email) && !empty($message)) {
                // Ở đây bạn có thể thêm code lưu vào DB hoặc gửi email thật
                $success_message = "Cảm ơn $name! Chúng tôi đã nhận được tin nhắn và sẽ phản hồi sớm nhất.";
            } else {
                $error_message = "Vui lòng điền đầy đủ thông tin.";
            }
        }

        $this->loadView('page/contact', [
            'title' => 'Liên hệ',
            'success' => $success_message,
            'error' => $error_message
        ]);
    }
}