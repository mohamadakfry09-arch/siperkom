<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Baris Bahasa Validasi
    |--------------------------------------------------------------------------
    |
    | Baris bahasa berikut berisi pesan kesalahan default yang digunakan oleh
    | kelas validator. Beberapa aturan memiliki beberapa versi seperti aturan ukuran.
    | Silakan sesuaikan setiap pesan di sini sesuai kebutuhan Anda.
    |
    */

    'accepted'             => ':attribute harus diterima.',
    'accepted_if'          => ':attribute harus diterima ketika :other bernilai :value.',
    'active_url'           => ':attribute bukan URL yang valid.',
    'after'                => ':attribute harus berisi tanggal setelah :date.',
    'after_or_equal'       => ':attribute harus berisi tanggal setelah atau sama dengan :date.',
    'alpha'                => ':attribute hanya boleh berisi huruf.',
    'alpha_dash'           => ':attribute hanya boleh berisi huruf, angka, setrip, dan garis bawah.',
    'alpha_num'            => ':attribute hanya boleh berisi huruf dan angka.',
    'any_of'               => ':attribute harus merupakan salah satu dari opsi yang tersedia.',
    'array'                => ':attribute harus berupa sebuah array.',
    'ascii'                => ':attribute hanya boleh berisi karakter alfanumerik byte tunggal dan simbol.',
    'before'               => ':attribute harus berisi tanggal sebelum :date.',
    'before_or_equal'      => ':attribute harus berisi tanggal sebelum atau sama dengan :date.',
    'between'              => [
        'array'   => ':attribute harus memiliki antara :min dan :max anggota.',
        'file'    => ':attribute harus berukuran antara :min dan :max kilobita.',
        'numeric' => ':attribute harus bernilai antara :min dan :max.',
        'string'  => ':attribute harus berisi antara :min dan :max karakter.',
    ],
    'boolean'              => ':attribute harus bernilai true atau false.',
    'can'                  => ':attribute berisi nilai yang tidak diizinkan.',
    'confirmed'            => 'Konfirmasi :attribute tidak cocok.',
    'contains'             => ':attribute tidak memiliki nilai yang dibutuhkan.',
    'current_password'     => 'Kata sandi salah.',
    'date'                 => ':attribute bukan tanggal yang valid.',
    'date_equals'          => ':attribute harus berisi tanggal yang sama dengan :date.',
    'date_format'          => ':attribute tidak cocok dengan format :format.',
    'decimal'              => ':attribute harus memiliki :decimal tempat desimal.',
    'declined'             => ':attribute harus ditolak.',
    'declined_if'          => ':attribute harus ditolak ketika :other bernilai :value.',
    'different'            => ':attribute dan :other harus berbeda.',
    'digits'               => ':attribute harus terdiri dari :digits angka.',
    'digits_between'       => ':attribute harus terdiri dari antara :min dan :max angka.',
    'dimensions'           => ':attribute tidak memiliki dimensi gambar yang valid.',
    'distinct'             => ':attribute memiliki nilai yang duplikat.',
    'doesnt_contain'       => ':attribute tidak boleh berisi salah satu dari nilai berikut: :values.',
    'doesnt_end_with'      => ':attribute tidak boleh diakhiri dengan salah satu dari berikut: :values.',
    'doesnt_start_with'    => ':attribute tidak boleh diawali dengan salah satu dari berikut: :values.',
    'email'                => ':attribute harus berupa alamat email yang valid.',
    'ends_with'            => ':attribute harus diakhiri dengan salah satu dari berikut: :values.',
    'enum'                 => ':attribute yang dipilih tidak valid.',
    'exists'               => ':attribute yang dipilih tidak valid.',
    'extensions'           => ':attribute harus memiliki salah satu ekstensi berikut: :values.',
    'file'                 => ':attribute harus berupa sebuah berkas.',
    'filled'               => ':attribute harus memiliki nilai.',
    'gt'                   => [
        'array'   => ':attribute harus memiliki lebih dari :value anggota.',
        'file'    => ':attribute harus berukuran lebih besar dari :value kilobita.',
        'numeric' => ':attribute harus bernilai lebih besar dari :value.',
        'string'  => ':attribute harus berisi lebih dari :value karakter.',
    ],
    'gte'                  => [
        'array'   => ':attribute harus terdiri dari :value anggota atau lebih.',
        'file'    => ':attribute harus berukuran lebih besar dari atau sama dengan :value kilobita.',
        'numeric' => ':attribute harus bernilai lebih besar dari atau sama dengan :value.',
        'string'  => ':attribute harus berisi lebih dari atau sama dengan :value karakter.',
    ],
    'hex_color'            => ':attribute harus berupa kode warna heksadesimal yang valid.',
    'image'                => ':attribute harus berupa gambar.',
    'in'                   => ':attribute yang dipilih tidak valid.',
    'in_array'             => ':attribute tidak ada di dalam :other.',
    'in_database'          => ':attribute tidak ditemukan dalam database.',
    'integer'              => ':attribute harus berupa bilangan bulat.',
    'ip'                   => ':attribute harus berupa alamat IP yang valid.',
    'ipv4'                 => ':attribute harus berupa alamat IPv4 yang valid.',
    'ipv6'                 => ':attribute harus berupa alamat IPv6 yang valid.',
    'json'                 => ':attribute harus berupa string JSON yang valid.',
    'list'                 => ':attribute harus berupa sebuah daftar.',
    'lowercase'            => ':attribute harus menggunakan huruf kecil.',
    'lt'                   => [
        'array'   => ':attribute harus memiliki kurang dari :value anggota.',
        'file'    => ':attribute harus berukuran kurang dari :value kilobita.',
        'numeric' => ':attribute harus bernilai kurang dari :value.',
        'string'  => ':attribute harus berisi kurang dari :value karakter.',
    ],
    'lte'                  => [
        'array'   => ':attribute tidak boleh memiliki lebih dari :value anggota.',
        'file'    => ':attribute harus berukuran kurang dari atau sama dengan :value kilobita.',
        'numeric' => ':attribute harus bernilai kurang dari atau sama dengan :value.',
        'string'  => ':attribute harus berisi kurang dari atau sama dengan :value karakter.',
    ],
    'mac_address'          => ':attribute harus berupa alamat MAC yang valid.',
    'max'                  => [
        'array'   => ':attribute tidak boleh memiliki lebih dari :max anggota.',
        'file'    => ':attribute tidak boleh lebih dari :max kilobita.',
        'numeric' => ':attribute tidak boleh bernilai lebih dari :max.',
        'string'  => ':attribute tidak boleh lebih dari :max karakter.',
    ],
    'max_digits'           => ':attribute tidak boleh memiliki lebih dari :max angka.',
    'mimes'                => ':attribute harus berupa berkas bertipe: :values.',
    'mimetypes'            => ':attribute harus berupa berkas bertipe: :values.',
    'min'                  => [
        'array'   => ':attribute minimal harus memiliki :min anggota.',
        'file'    => ':attribute minimal harus berukuran :min kilobita.',
        'numeric' => ':attribute minimal harus bernilai :min.',
        'string'  => ':attribute minimal harus berisi :min karakter.',
    ],
    'min_digits'           => ':attribute minimal harus memiliki :min angka.',
    'missing'              => ':attribute harus tidak ada.',
    'missing_if'           => ':attribute harus tidak ada ketika :other bernilai :value.',
    'missing_unless'       => ':attribute harus tidak ada kecuali :other bernilai :value.',
    'missing_with'         => ':attribute harus tidak ada ketika :values ada.',
    'missing_with_all'     => ':attribute harus tidak ada ketika :values ada semua.',
    'multiple_of'          => ':attribute harus merupakan kelipatan dari :value.',
    'not_in'               => ':attribute yang dipilih tidak valid.',
    'not_regex'            => 'Format :attribute tidak valid.',
    'numeric'              => ':attribute harus berupa angka.',
    'password'             => [
        'letters'       => ':attribute harus berisi setidaknya satu huruf.',
        'mixed'         => ':attribute harus berisi setidaknya satu huruf besar dan satu huruf kecil.',
        'numbers'       => ':attribute harus berisi setidaknya satu angka.',
        'symbols'       => ':attribute harus berisi setidaknya satu simbol.',
        'uncompromised' => ':attribute yang diberikan telah muncul dalam kebocoran data. Silakan pilih :attribute yang lain.',
    ],
    'present'              => ':attribute harus ada.',
    'present_if'           => ':attribute harus ada ketika :other bernilai :value.',
    'present_unless'       => ':attribute harus ada kecuali :other bernilai :value.',
    'present_with'         => ':attribute harus ada ketika :values ada.',
    'present_with_all'     => ':attribute harus ada ketika :values ada semua.',
    'prohibited'           => ':attribute tidak diizinkan.',
    'prohibited_if'        => ':attribute tidak diizinkan ketika :other bernilai :value.',
    'prohibited_if_accepted' => ':attribute tidak diizinkan ketika :other diterima.',
    'prohibited_if_declined' => ':attribute tidak diizinkan ketika :other ditolak.',
    'prohibited_unless'    => ':attribute tidak diizinkan kecuali :other bernilai :values.',
    'prohibits'            => ':attribute melarang :other untuk ada.',
    'regex'                => 'Format :attribute tidak valid.',
    'required'             => ':attribute wajib diisi.',
    'required_array_keys'  => ':attribute harus berisi entri untuk: :values.',
    'required_if'          => ':attribute wajib diisi ketika :other bernilai :value.',
    'required_if_accepted' => ':attribute wajib diisi ketika :other diterima.',
    'required_if_declined' => ':attribute wajib diisi ketika :other ditolak.',
    'required_unless'      => ':attribute wajib diisi kecuali :other memiliki nilai :values.',
    'required_with'        => ':attribute wajib diisi ketika :values ada.',
    'required_with_all'    => ':attribute wajib diisi ketika :values ada semua.',
    'required_without'     => ':attribute wajib diisi ketika :values tidak ada.',
    'required_without_all' => ':attribute wajib diisi ketika tidak ada satu pun dari :values yang ada.',
    'same'                 => ':attribute dan :other harus sama.',
    'size'                 => [
        'array'   => ':attribute harus mengandung :size anggota.',
        'file'    => ':attribute harus berukuran :size kilobita.',
        'numeric' => ':attribute harus bernilai :size.',
        'string'  => ':attribute harus berisi :size karakter.',
    ],
    'starts_with'          => ':attribute harus diawali dengan salah satu dari berikut: :values.',
    'string'               => ':attribute harus berupa teks.',
    'timezone'             => ':attribute harus berupa zona waktu yang valid.',
    'unique'               => ':attribute ini sudah terdaftar dan tidak dapat digunakan kembali.',
    'uploaded'             => ':attribute gagal diunggah.',
    'uppercase'            => ':attribute harus menggunakan huruf kapital.',
    'url'                  => ':attribute harus berupa format tautan URL yang valid.',
    'ulid'                 => ':attribute harus berupa ULID yang valid.',
    'uuid'                 => ':attribute harus berupa UUID yang valid.',

    /*
    |--------------------------------------------------------------------------
    | Kustomisasi Pesan Validasi Khusus
    |--------------------------------------------------------------------------
    */

    'custom' => [
        'email' => [
            'unique'   => 'Alamat email ini sudah terdaftar. Silakan gunakan alamat email lain atau masuk ke akun Anda.',
            'required' => 'Alamat email wajib diisi.',
            'email'    => 'Format alamat email yang dimasukkan tidak valid.',
        ],
        'password' => [
            'required' => 'Kata sandi wajib diisi.',
            'min'      => 'Kata sandi minimal harus terdiri dari :min karakter.',
        ],
        'name' => [
            'required' => 'Nama lengkap wajib diisi.',
        ],
        'nama_desa' => [
            'required' => 'Nama desa wajib diisi.',
        ],
        'kecamatan' => [
            'required' => 'Kecamatan wajib dipilih.',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Kustomisasi Nama Atribut
    |--------------------------------------------------------------------------
    |
    | Baris berikut digunakan untuk mengganti placeholder atribut kami
    | dengan sesuatu yang lebih mudah dipahami seperti "Alamat E-Mail" alih-alih "email".
    |
    */

    'attributes' => [
        'name'                  => 'nama lengkap',
        'username'              => 'nama pengguna',
        'email'                 => 'alamat email',
        'first_name'            => 'nama depan',
        'last_name'             => 'nama belakang',
        'password'              => 'kata sandi',
        'password_confirmation' => 'konfirmasi kata sandi',
        'city'                  => 'kota',
        'country'               => 'negara',
        'address'               => 'alamat',
        'phone'                 => 'nomor telepon',
        'mobile'                => 'nomor ponsel',
        'age'                   => 'usia',
        'sex'                   => 'jenis kelamin',
        'gender'                => 'jenis kelamin',
        'day'                   => 'hari',
        'month'                 => 'bulan',
        'year'                  => 'tahun',
        'hour'                  => 'jam',
        'minute'                => 'menit',
        'second'                => 'detik',
        'title'                 => 'judul',
        'content'               => 'konten',
        'description'           => 'deskripsi',
        'excerpt'               => 'kutipan',
        'date'                  => 'tanggal',
        'time'                  => 'waktu',
        'available'             => 'tersedia',
        'size'                  => 'ukuran',
        'nama_desa'             => 'nama desa',
        'desa'                  => 'desa',
        'kecamatan'             => 'kecamatan',
        'perangkat'             => 'nama perangkat',
        'jenis_perangkat'       => 'jenis perangkat',
        'kendala'               => 'kendala kerusakan',
        'foto'                  => 'foto dokumentasi',
        'dokumen'               => 'dokumen pendukung',
        'avatar'                => 'foto profil',
        'catatan_teknisi'       => 'catatan teknisi',
        'status'                => 'status laporan',
        'prioritas'             => 'prioritas penanganan',
    ],

];
