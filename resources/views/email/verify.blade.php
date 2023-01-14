<body style="font-family: sans-serif">
    <div style="display: flex; flex-direction: row; padding: 50px; padding-top: 20px; padding-bottom: 0;">
        {{-- Mail Heading --}}
        {{-- @isset($message)
            <img
                src="{{ $message->embed(public_path('assets/images/KedairekaLogo.svg')) }}"
                style="max-width: 200px; width: 25%"
                alt="Docu Logo"
            >
        @else
            <img
                src="{{ url('/assets/images/KedairekaLogo.svg') }}"
                style="max-width: 200px; width: 25%"
                alt="Docu Logo"
            >
        @endisset --}}
        <h1>Verifikasi Email</h1>
    </div>
    <div style="padding: 20px 50px;">
        <p>
            Hai <b>{{ $user->name }}</b>,
        </p>
        <p>
            Terima kasih telah bergabung dengan Dongkrak Cuan. Sebelum melanjutkan, kami perlu memastikan bahwa
            {{ $user->email }} merupakan alamat emailmu. Untuk melanjutkan proses verifikasi tekan tombol di bawah ini
        </p>
        <a
            href="{{ url('auth/verify?token=' . $token) }}"
            style="display: block; background-color: #ff0045; padding: 0.5em; text-align: center; color: white; text-decoration: none;"
        >
            Verifikasi Email
        </a>

        <p>
            Apabila Anda tidak dapat mengklik tombol di atas, salin dan buka URL tautan di bawah dan buka menggunakan
            <i>web browser</i> (contohnya: Chrome, Firefox, Safari, atau Microsoft Edge)
        </p>
        <p>
            <a href="{{ url('auth/verify?token=' . $token) }}">
                {{ url('auth/verify?token=' . $token) }}
            </a>
        </p>
    </div>
</body>
