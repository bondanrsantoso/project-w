Verifikasi Email

***

Hai {{ $user->name }},

Terima kasih telah bergabung dengan Dongkrak Cuan. Sebelum melanjutkan, kami perlu memastikan bahwa {{ $user->email }} merupakan alamat emailmu. Untuk melanjutkan proses verifikasi salin dan buka URL tautan di bawah dan buka menggunakan web browser (contohnya: Chrome, Firefox, Safari, atau Microsoft Edge)

{{ url('auth/verify?token=' . $token) }}
