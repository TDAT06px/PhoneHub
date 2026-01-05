<?php

class PageController extends Controller {
    public function about() {
        $this->loadView('page/about', [
            'title' => 'Về chúng tôi'
        ]);
    }

    public function contact() {
        $success_message = '';
        $error_message = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';
            $email = $_POST['email'] ?? '';
            $message = $_POST['message'] ?? '';

            if (!empty($name) && !empty($email) && !empty($message)) {
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