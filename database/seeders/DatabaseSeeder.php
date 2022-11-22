<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Job;
use App\Models\PaymentMethod;
use App\Models\Project;
use App\Models\Question;
use App\Models\ServicePack;
use App\Models\User;
use App\Models\Worker;
use Illuminate\Database\Seeder;
use App\Models\WorkerExperience;
use App\Models\WorkerPortofolio;
use App\Models\Workgroup;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        (new CategorySeed())->run();

        // Service pack seeder

        $servicePacks = [
            [
                "name" => "Layanan Branding Agency",
                "description" => "Layanan Branding Agency",
                "workgroups" => [
                    [
                        "name" => "Branding Agency",
                        "jobs" => [
                            [
                                "name" => "Konsultasi Branding",
                                "description" => "Konsultasi Branding",
                                "job_category_id" => 1
                            ],
                        ],
                    ],
                ]
            ],
            [
                "name" => "Social Media Advertiesments",
                "description" => "Layanan Branding Agency",
                "workgroups" => [
                    [
                        "name" => "Instagram",
                        "jobs" => [
                            [
                                "name" => "Instagram Ads dan SEO",
                                "description" => "Program marketing media sosial instagram",
                                "job_category_id" => 6,
                            ],
                        ],
                    ],
                    [
                        "name" => "TikTok",
                        "jobs" => [
                            [
                                "name" => "TikTok Ads dan SEO",
                                "description" => "Program marketing media sosial TikTok",
                                "job_category_id" => 7,
                            ],
                        ],
                    ],
                    [
                        "name" => "Facebook",
                        "jobs" => [
                            [
                                "name" => "Facebook Ads dan SEO",
                                "description" => "Program marketing media sosial Facebook",
                                "job_category_id" => 8,
                            ],
                        ],
                    ],
                ]
            ],
            [
                "name" => "Layanan Marketing Toko Online",
                "description" => "Layanan Marketing Toko Online",
                "workgroups" => [
                    [
                        "name" => "Shopee",
                        "jobs" => [
                            [
                                "name" => "Shopee SEO",
                                "description" => "Strategi marketing via optimisasi pencarian produk Shopee",
                                "job_category_id" => 10
                            ],
                            [
                                "name" => "Shopee Ads",
                                "description" => "Strategi marketing via Iklan berbayar Shopee",
                                "job_category_id" => 11
                            ],
                        ],
                    ],
                    [
                        "name" => "Tokopedia",
                        "jobs" => [
                            [
                                "name" => "Tokopedia SEO",
                                "description" => "Strategi marketing via optimisasi pencarian produk Tokopedia",
                                "job_category_id" => 12
                            ],
                            [
                                "name" => "Tokopedia Ads",
                                "description" => "Strategi marketing via Iklan berbayar Tokopedia",
                                "job_category_id" => 13
                            ],
                        ],
                    ],
                    [
                        "name" => "Bukalapak",
                        "jobs" => [
                            [
                                "name" => "Bukalapak SEO",
                                "description" => "Strategi marketing via optimisasi pencarian produk Bukalapak",
                                "job_category_id" => 14
                            ],
                            [
                                "name" => "Bukalapak Ads",
                                "description" => "Strategi marketing via Iklan berbayar Bukalapak",
                                "job_category_id" => 15
                            ],
                        ],
                    ],
                    [
                        "name" => "Gojek",
                        "jobs" => [
                            [
                                "name" => "Gojek SEO",
                                "description" => "Strategi marketing via optimisasi pencarian produk Gojek",
                                "job_category_id" => 16
                            ],
                            [
                                "name" => "Gojek Ads",
                                "description" => "Strategi marketing via Iklan berbayar Gojek",
                                "job_category_id" => 17
                            ],
                        ],
                    ],
                    [
                        "name" => "Grab",
                        "jobs" => [
                            [
                                "name" => "Grab SEO",
                                "description" => "Strategi marketing via optimisasi pencarian produk Grab",
                                "job_category_id" => 18
                            ],
                            [
                                "name" => "Grab Ads",
                                "description" => "Strategi marketing via Iklan berbayar Grab",
                                "job_category_id" => 19
                            ],
                        ],
                    ],
                    [
                        "name" => "Traveloka",
                        "jobs" => [
                            [
                                "name" => "Traveloka SEO",
                                "description" => "Strategi marketing via optimisasi pencarian produk Traveloka",
                                "job_category_id" => 20
                            ],
                            [
                                "name" => "Traveloka Ads",
                                "description" => "Strategi marketing via Iklan berbayar Traveloka",
                                "job_category_id" => 21
                            ],
                        ],
                    ],
                ]
            ],
            [
                "name" => "Layanan Google SEO & SEM",
                "description" => "Layanan Google SEO & SEM",
                "workgroups" => [
                    [
                        "name" => "Google SEO & SEM",
                        "jobs" => [
                            [
                                "name" => "Google SEO",
                                "description" => "Strategi marketing via optimisasi pencarian Gooogle",
                                "job_category_id" => 22
                            ],
                            [
                                "name" => "Google SEM",
                                "description" => "Strategi marketing via iklan pencarian Google",
                                "job_category_id" => 22
                            ],
                        ],
                    ],
                ],
            ],
            [
                "name" => "Layanan YouTube Ad",
                "description" => "Layanan Periklanan YouTube",
                "workgroups" => [
                    [
                        "name" => "YouTube Ad Campaign",
                        "jobs" => [
                            [
                                "name" => "TrueView Ad",
                                "description" => "Strategi Marketing via iklan YouTube TrueView",
                                "job_category_id" => 24
                            ],
                            [
                                "name" => "Non-Skippable Ad",
                                "description" => "Strategi Marketing via iklan YouTube yang tidak dapat dilewati oleh pengguna",
                                "job_category_id" => 24
                            ],
                            [
                                "name" => "Bumper Ad",
                                "description" => "Strategi Marketing via iklan YouTube berbentuk Bumper video",
                                "job_category_id" => 24
                            ],
                        ],
                    ],
                ]
            ],
            [
                "name" => "Layanan IT Support",
                "description" => "Layanan Dukungan IT",
                "workgroups" => [
                    [
                        "name" => "IT Support",
                        "jobs" => [
                            [
                                "name" => "IT Support",
                                "description" => "Layanan dukungan infrastruktur IT",
                                "job_category_id" => 33
                            ],
                        ],
                    ],
                ]
            ],
            [
                "name" => "Konsultasi Iklan Media Sosial",
                "description" => "Layanan Konsultasi Iklan Media Sosial",
                "workgroups" => [
                    [
                        "name" => "Konsultasi Iklan Media Sosial",
                        "jobs" => [
                            [
                                "name" => "Konsultasi Iklan Media Sosial",
                                "description" => "Layanan Konsultasi Iklan Media Sosial",
                                "job_category_id" => 34
                            ],
                        ],
                    ],
                ]
            ],
            [
                "name" => "Pengembangan Aplikasi Web",
                "description" => "Layanan Pengembangan Aplikasi Web",
                "workgroups" => [
                    [
                        "name" => "Pengembangan Aplikasi Web",
                        "jobs" => [
                            [
                                "name" => "Analisis dan Perencanaan Aplikasi Web",
                                "description" => "Perencanaan analisis dan perancangan aplikasi web",
                                "job_category_id" => 25
                            ],
                            [
                                "name" => "Pengembangan Aplikasi Web: Front End",
                                "description" => "Pengembangan bagian aplikasi web yang berkaitan dengan antarmuka pengguna/user interface",
                                "job_category_id" => 27
                            ],
                            [
                                "name" => "Pengembangan Aplikasi Web: back End",
                                "description" => "Pengembangan bagian aplikasi web yang berkaitan dengan pengolahan data",
                                "job_category_id" => 28
                            ],
                            [
                                "name" => "Perancangan struktur dan pengembangan basis data",
                                "description" => "Perencanaan, pengembangan dan implementasi basis data untuk aplikasi web. Berkaitan erat dengan back-end",
                                "job_category_id" => 29
                            ],
                            [
                                "name" => "Pengujian aplikasi web",
                                "description" => "Pengujian dan quality control aplikasi web",
                                "job_category_id" => 31
                            ],
                        ],
                    ],
                ]
            ],
            [
                "name" => "Konsultasi Aplikasi Web",
                "description" => "Layanan Konsultasi pengembangan aplikasi web",
                "workgroups" => [
                    [
                        "name" => "Konsultasi aplikasi web",
                        "jobs" => [
                            [
                                "name" => "Konsultasi pengembangan aplikasi web",
                                "description" => "Layanan pengembangan aplikasi web",
                                "job_category_id" => 35
                            ],
                        ],
                    ],
                ]
            ],
            [
                "name" => "Konsultasi Jaringan Komputer",
                "description" => "Layanan Konsultasi pengembangan dan administrasi jaringan komputer",
                "workgroups" => [
                    [
                        "name" => "Konsultasi jaringan komputer",
                        "jobs" => [
                            [
                                "name" => "Konsultasi pengembangan jaringan komputer",
                                "description" => "Layanan pengembangan jaringan komputer",
                                "job_category_id" => 36
                            ],
                        ],
                    ],
                ]
            ],
            [
                "name" => "Layanan Konsultasi IT Support",
                "description" => "Layanan Dukungan Konsultasi IT",
                "workgroups" => [
                    [
                        "name" => "Konsultasi IT Support",
                        "jobs" => [
                            [
                                "name" => "Konsultasi IT Support",
                                "description" => "Layanan Konsultasi dukungan infrastruktur IT",
                                "job_category_id" => 37
                            ],
                        ],
                    ],
                ]
            ],
            [
                "name" => "Layanan Kreasi Konten",
                "description" => "Layanan Kreasi konten multimedia untuk marketing",
                "workgroups" => [
                    [
                        "name" => "Kreasi Konten",
                        "jobs" => [
                            [
                                "name" => "Fotografi",
                                "description" => "Layanan kreasi konten fotografi",
                                "job_category_id" => 3
                            ],
                            [
                                "name" => "Videografi",
                                "description" => "Layanan kreasi konten videografi",
                                "job_category_id" => 4
                            ],
                            [
                                "name" => "Penjadwalan Konten",
                                "description" => "Layanan Penjadwalan Konten",
                                "job_category_id" => 2
                            ],
                            [
                                "name" => "Evaluasi Konten",
                                "description" => "Layanan Evaluasi Konten",
                                "job_category_id" => 2
                            ],
                        ],
                    ],
                ]
            ],
            [
                "name" => "Konsultasi Google Ad",
                "description" => "Layanan Konsultasi pengembangan dan administrasi Google Ad",
                "workgroups" => [
                    [
                        "name" => "Konsultasi Google Ad",
                        "jobs" => [
                            [
                                "name" => "Konsultasi pengembangan Google Ad",
                                "description" => "Layanan pengembangan Google Ad",
                                "job_category_id" => 38
                            ],
                        ],
                    ],
                ]
            ],
            [
                "name" => "Konsultasi YouTube Ad",
                "description" => "Layanan Konsultasi pengembangan dan administrasi YouTube Ad",
                "workgroups" => [
                    [
                        "name" => "Konsultasi YouTube Ad",
                        "jobs" => [
                            [
                                "name" => "Konsultasi pengembangan YouTube Ad",
                                "description" => "Layanan pengembangan YouTube Ad",
                                "job_category_id" => 39
                            ],
                        ],
                    ],
                ]
            ],
            [
                "name" => "Konsultasi Marketplace Ad",
                "description" => "Layanan Konsultasi pengembangan dan administrasi Marketplace Ad",
                "workgroups" => [
                    [
                        "name" => "Konsultasi Marketplace Ad",
                        "jobs" => [
                            [
                                "name" => "Konsultasi pengembangan Marketplace Ad",
                                "description" => "Layanan pengembangan Marketplace Ad",
                                "job_category_id" => 40
                            ],
                        ],
                    ],
                ]
            ],
            [
                "name" => "Konsultasi Kreasi Konten",
                "description" => "Layanan Konsultasi pengembangan dan administrasi Kreasi Konten",
                "workgroups" => [
                    [
                        "name" => "Konsultasi Kreasi Konten",
                        "jobs" => [
                            [
                                "name" => "Konsultasi pengembangan Kreasi Konten",
                                "description" => "Layanan pengembangan Kreasi Konten",
                                "job_category_id" => 41
                            ],
                        ],
                    ],
                ]
            ],
        ];

        foreach ($servicePacks as $servicePack) {
            $servicePack = collect($servicePack);
            $sp = ServicePack::create($servicePack->only(["name", "description"])->all());

            if (isset($servicePack["workgroups"])) {
                foreach ($servicePack["workgroups"] as $workgroup) {
                    $workgroup = collect($workgroup);
                    $wg = $sp->workgroups()->create($workgroup->only(["name"])->all());

                    if (isset($workgroup["jobs"])) {
                        foreach ($workgroup["jobs"] as $job) {
                            $j = $wg->jobs()->create($job);
                        }
                    }
                }
            }
        }

        // Seed Questions
        $questions = [
            ['1', 'Apakah anda tahu apa yang anda butuhkan?', null, '2', null, null],
            ['2', 'Apakah anda seorang pengusaha?', '4', '3', null, null],
            ['3', 'Apakah anda seorang karyawan?', '4', '4', null, null],
            ['4', 'Apakah anda menjual produk/jasa ?', '5', '5', null, null],
            ['5', 'Dalam melakukan penjualan produk/jasa anda, apakah memiliki kesulitan dalam promosi?', '6', '7', null, null],
            ['6', 'Apakah anda memiliki target pasar?', '9', '8', null, null],
            ['7', 'Apakah promosi anda sesuai dengan target pasar anda?', '9', '8', null, null],
            ['8', 'apakah anda ingin merubah target pasar?', '10', '9', null, null],
            ['9', 'Apakah anda sudah konsisten dalam melakukan penjualan sesuai dengan target pasar?', '12', '12', null, null],
            ['10', 'apakah target pasar anda kalangan berpendapatan tinggi?', '9', '11', null, null],
            ['11', 'apakah target pasar anda berpendapatan menengah ke bawah?', '9', '9', null, null],
            ['12', 'apakah nama produk/jasa sudah sesuai dengan karakteristik?', '14', '13', null, null],
            ['13', 'apakah anda ingin mengubah karakteristik pada produk/jasa anda?', '23', '16', '1', null],
            ['14', 'apakah slogan anda sudah mewakili visi dan misi perusahaan?', '17', '15', null, null],
            ['15', 'apakah anda ingin merubah slogan anda?', '18', '17', null, null],
            ['16', 'apakah anda tau karakteristik seperti apa yang akan dirubah?', '17', '23', null, '1'],
            ['17', 'apakah warna yang anda gunakan sudah sesuai dengan karakteristik produk/jasa?', '20', '19', null, null],
            ['18', 'apakah anda sudah tahu slogan yang akan anda rubah?', '17', '17', null, null],
            ['19', 'apakah anda ingin merubah warna pada produk / jasa anda?', '22', '20', null, null],
            ['20', 'apakah logo yang anda punyai sudah representasi identitas produk/jasa?', '23', '21', null, null],
            ['21', 'apakah anda merubah logo anda?', '23', '23', null, '1'],
            ['22', 'apakah anda tahu warna apa yang akan anda gunakan pada produk / jasa anda?', '20', '23', null, '1'],
            ['23', 'agar promosi anda tersebar lebih luas, maukah anda melakukan promosi melalui media digital?', '25', '26', null, null],
            ['24', 'apakah anda sudah mempunyai design logo untuk bisnis produk / jasa anda?', '23', '23', null, '1'],
            ['25', 'apakah anda sudah mengetahui jenis promosi apa yang akan anda lakukan kedepannya?', '26', '26', null, null],
            ['26', 'Apakah target pasar anda di kalangan Anak-anak?', '29', '27', null, null],
            ['27', 'Apakah target pasar anda di kalangan Remaja?', '28', '29', null, null],
            ['28', 'Apakah target pasar anda di kalangan Dewasa?', '29', '29', null, null],
            ['29', 'Apakah anda dapat membuat konten yang dapat menarik minat pelanggan?', '31', '30', null, null],
            ['30', 'apakah anda ingin membuat konten yang menarik?', '33', '31', null, null],
            ['31', 'agar anda tetap konsisten, dapatkah anda membuat perencanaan untuk kedepannya?', '32', '32', null, null],
            ['32', 'Apakah anda sudah mempromosikan bisnis anda ke masyarakat?', '35', '33', null, null],
            ['33', 'Apakah anda memiliki foto/video yang dapat diangkat untuk promosi?', '37', '34', null, null],
            ['34', 'Bersediakah anda membuat foto/video yang menarik dan sesuai untuk bisnis anda?', '46', '46', null, null],
            ['35', 'Apakah anda melakukan promosi secara langsung?', '36', '37', null, null],
            ['36', 'Apakah anda melakukan promosi melalui media digital?', '38', '37', null, null],
            ['37', 'Apakah anda tertarik melakukan promosi melalui media digital?', '39', '105', null, null],
            ['38', 'Apakah anda memiliki akun sosial media untuk bisnis anda?', '39', '41', null, null],
            ['39', 'Apakah membuat iklan di sosial media berkemungkinan meningkatkan penjualan bisnis anda?', '40', '42', null, null],
            ['40', 'Bersediakah anda membayar layanan iklan sosial media ads untuk menjadi pencarian teratas?', '61', '61', null, null],
            ['41', 'Apakah anda berjualan di toko online?', '42', '44', null, null],
            ['42', 'Apakah membuat iklan di toko online berkemungkinan meningkatkan penjualan bisnis anda?', '43', '44', null, null],
            ['43', 'Bersediakah anda membayar layanan iklan marketplace ads untuk menjadi pencarian teratas?', '73', '73', null, null],
            ['44', 'Apakah anda melakuan promosi melalui google?', '45', '105', null, null],
            ['45', 'Bersediakah anda membayar layanan iklan google ads untuk menjadi pencarian teratas?', '94', '94', null, null],
            ['46', 'Apakah anda pernah membuat konten?', '47', '52', null, null],
            ['47', 'Apakah anda merasa puas dengan konten yang dibuat?', '48', '52', null, null],
            ['48', 'Apakah konten yang anda buat terkonsep dengan baik?', '49', '52', null, null],
            ['49', 'Cukup seringkah anda mengunggah konten disosial media?', '50', '52', null, null],
            ['50', 'Apakah konten yang anda unggah terjadwal dengan baik?', '51', '52', null, null],
            ['51', 'apakah anda melakukan evaluasi terhadap konten yang dibuat?', '56', '54', null, null],
            ['52', 'Siapkah anda membuat konten yang terkonsep dengan menarik?', '53', '53', '12', null],
            ['53', 'siapkah anda untuk mengunggah konten secara konsisten?', '54', '54', '12', null],
            ['54', 'Dapatkah anda  mengevaluasi setiap konten yang dihasilkan?', '56', '55', null, '16'],
            ['55', 'Apakah Ingin Melanjutkan Layanan Digital Marketing Lainnya?', '56', null, null, null],
            ['56', 'apakah anda bersedia memberikan promosi yang menarik untuk pelanggan anda?', '57', '57', null, null],
            ['57', 'apakah anda mengetahui target konsumen anda?', '58', '57', null, null],
            ['58', 'apakah anda mengetahui promosi yang akan dilakukan?', '60', '59', null, null],
            ['59', 'apakah anda mau mencoba melakukan promosi di sosial media/toko online yang sudah anda punya?', '60', '93', null, null],
            ['60', 'Siapkah anda mencoba mempromosikan produk/jasa anda di sosial media?', '61', '72', null, null],
            ['61', 'apakah anda menggunakan instagram?', '62', '64', null, null],
            ['62', 'apakah anda mau menggunakan instagram untuk mempromosikan produk/jasa anda?', '63', '64', null, null],
            ['63', 'cobalah gunakan instagram ads', '64', '64', '2', null],
            ['64', 'apakah anda menggunakan tiktok?', '65', '67', null, null],
            ['65', 'apakah anda mau menggunakan tiktok untuk mempromosikan produk/jasa anda?', '66', '67', null, null],
            ['66', 'cobalah gunakan tiktok ads', '67', '67', '2', null],
            ['67', 'apakah anda menggunakan facebook?', '68', '72', null, null],
            ['68', 'apakah anda mau menggunakan facebook untuk mempromosikan produk/jasa anda?', '69', '72', null, null],
            ['69', 'cobalah gunakan facebook ads', '70', '70', '2', null],
            ['70', 'Apakah anda dapat melakukan evaluasi di sosial media ads?', '72', '71', null, '7'],
            ['71', 'Apakah Ingin Melanjutkan Layanan Digital Marketing Lainnya?', null, '72', null, null],
            ['72', 'Siapkah anda mencoba mempromosikan produk/jasa anda di toko online(marketplace)?', '73', '93', null, null],
            ['73', 'apakah anda menggunakan shopee?', '74', '76', null, null],
            ['74', 'apakah anda mau menggunakan shopee untuk mempromosikan produk/jasa anda?', '75', '76', null, null],
            ['75', 'cobalah gunakan shopee ads', '76', '76', '3', null],
            ['76', 'apakah anda mengunakan tokopedia?', '77', '79', null, null],
            ['77', 'apakah anda mau menggunakan tokopedia untuk mempromosikan produk/jasa anda?', '78', '79', null, null],
            ['78', 'cobalah gunaka tokopedia ads', '79', '79', '3', null],
            ['79', 'apakah anda menggunakan bukalapak?', '80', '82', null, null],
            ['80', 'apakah anda mau menggunakan bukalapak untuk mempromosikan produk/jasa anda?', '81', '82', null, null],
            ['81', 'cobalah gunakan bukalapak ads', '82', '82', '3', null],
            ['82', 'apakah anda menggunakan Gojek?', '83', '85', null, null],
            ['83', 'apakah anda mau menggunakan Gojek untuk mempromosikan produk/jasa anda?', '84', '85', null, null],
            ['84', 'cobalah gunakan Gojek ads', '85', '85', '3', null],
            ['85', 'apakah anda menggunakan Grab?', '86', '88', null, null],
            ['86', 'apakah anda mau menggunakan Grab untuk mempromosikan produk/jasa anda?', '87', '88', null, null],
            ['87', 'cobalah gunakan Grab ads', '88', '88', '3', null],
            ['88', 'apakah anda menggunakan Traveloka?', '89', '93', null, null],
            ['89', 'apakah anda mau menggunakan Traveloka untuk mempromosikan produk/jasa anda?', '90', '91', null, null],
            ['90', 'cobalah gunakan Traveloka ads', '91', '91', '3', null],
            ['91', 'Apakah anda dapat melakukan evaluasi di marketplace ads?', '93', '92', null, '15'],
            ['92', 'Apakah Ingin Melanjutkan Layanan Digital Marketing Lainnya?', '93', null, null, null],
            ['93', 'apakah anda ingin mempromosikan produk/jasa anda di sebuah situs internet?', '94', '104', null, null],
            ['94', 'apa anda memiliki akun google?', '96', '95', null, null],
            ['95', 'apakah anda mau membuat akun google', '96', '104', null, null],
            ['96', 'apakah anda mau mempromosikan produk/jasa anda di google?', '97', '99', null, null],
            ['97', 'cobalah gunakan google ads', '98', '99', '4', null],
            ['98', 'Apakah anda dapat melakukan evaluasi di google ads?', '99', '99', null, '13'],
            ['99', 'apakah anda menggunakan youtube?', '100', '104', null, null],
            ['100', 'apakah anda mau mempromosikan produk/jasa anda di youtube?', '101', '104', null, null],
            ['101', 'cobalah gunakan youtube ads', '102', '104', '5', null],
            ['102', 'Apakah anda dapat melakukan evaluasi di youtube ads?', '104', '103', null, '14'],
            ['103', 'Apakah Ingin Melanjutkan Layanan Digital Marketing Lainnya?', '104', null, null, null],
            ['104', 'apakah anda ingin memperluas pemasaran produk/jasa anda?', '105', null, null, null],
            ['105', 'Apakah anda mempunyai website?', '107', '106', null, null],
            ['106', 'apakah anda tertarik membuat website?', '109', '111', '8', null],
            ['107', 'Apakah anda puas dengan website anda?', '108', '108', null, null],
            ['108', 'Apakah anda dapat menganalisa kekurangan pada website anda?', '109', '109', null, '8'],
            ['109', 'Apakah anda membutuhkan evaluasi pada website anda?', '104', '110', null, '9'],
            ['110', 'Apakah Ingin Melanjutkan Layanan IT Lainnya?', '111', null, null, null],
            ['111', 'apakah anda sudah menggunakan infrastruktur jaringan?', '113', '112', null, null],
            ['112', 'apakah anda berminat untuk menggunakan infrastruktur jaringan?', '115', '116', '10', null],
            ['113', 'Apakah anda puas dengan infrastuktur jaringan anda?', '114', '114', null, null],
            ['114', 'Apakah anda dapat melakukan evaluasi di infrastruktus jaringan?', '116', '115', null, '10'],
            ['115', 'Apakah Ingin Melanjutkan Layanan IT Lainnya?', '116', null, null, null],
            ['116', 'apakah anda melakukan perawatan terhadap perangkat keras/lunak yang anda gunakan?', '117', '124', null, null],
            ['117', 'Apakah anda puas dengan perawatan perangkat keras/lunak anda?', '118', '118', null, null],
            ['118', 'Apakah anda dapat memeriksa dan memastikan perangkat keras berfungsi dengan normal?', '119', '119', null, '6'],
            ['119', 'Apakah anda dapat memeriksa dan memastikan perangkat keras terhubung kedalam jaringan?', '120', '120', null, '6'],
            ['120', 'Apakah anda dapat memeriksa dan memastikan perangkat lunak dapat berjalan dengan normal?', '121', '121', null, '6'],
            ['121', 'Apakah anda dapat melakukan pembaharuan perangkat lunak?', '122', '122', null, '6'],
            ['122', 'Apakah anda dapat melakukan pemulihan data?', '123', '123', null, '6'],
            ['123', 'Apakah anda dapat menggunakan tool antivirus?', '125', '125', null, '6'],
            ['124', 'apakah anda mau melakukan perawatan terhadap perangkat keras/lunak yang anda gunakan?', '125', null, null, null],
            ['125', 'Apakah anda dapat melakukan evaluasi pada perawatan perangkat kerang/lunak?', null, null, null, '11'],
        ];

        foreach ($questions as [$id, $statement, $next_on_yes, $next_on_no, $answer_yes, $answer_no]) {
            Question::create(compact("statement", "next_on_yes", "next_on_no", "answer_yes", "answer_no"));
        }

        if (!App::environment("production")) {
            $workers = Worker::factory(35)->create();
            foreach ($workers as $w) {
                WorkerExperience::factory(rand(0, 4))->state([
                    "worker_id" => $w->id,
                ])->create();

                WorkerPortofolio::factory(rand(0, 4))->state([
                    "worker_id" => $w->id,
                ])->create();
            }

            $companies = Company::factory(10)->create();
            foreach ($companies as $company) {
                $projects = Project::factory(5)->state([
                    "company_id" => $company->id,
                ])->create();

                foreach ($projects as $p) {
                    $workgroups = Workgroup::factory(rand(1, 5))->state([
                        "project_id" => $p->id,
                    ])->create();

                    foreach ($workgroups as $w) {
                        $jobs = Job::factory(rand(1, 5))->state([
                            "workgroup_id" => $w->id,
                        ])->create();
                    }
                }
            }
        }


        Schema::enableForeignKeyConstraints();
    }
}
