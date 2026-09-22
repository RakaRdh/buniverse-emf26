<?php

namespace App\Config;

use CodeIgniter\Config\BaseConfig;

class MockData extends BaseConfig
{
    /**
     * Site General Information
     */
    public array $site = [
        'title'       => 'Investor Daily Summit 2026',
        'theme'       => "INDONESIA'S ECONOMIC MODEL",
        'dates'       => 'October 7th-8th, 2026',
        'venue'       => 'Raffles Hotel, Jakarta',
        'ticket_url'  => '#tickets',
        'logo_ids'    => 'logo_ids26.webp',
        'logo_id'     => 'logo_id.png',
        'hero_image'  => 'hero.jpeg',
    ];

    /**
     * Editorial Copy for Overview ("Indonesia's Economic Model")
     */
    public array $overview = [
        'badge'      => "INDONESIA'S ECONOMIC MODEL",
        'logo'       => 'logo_ids26.png',
        'intro'      => "Investor Daily Summit 2026, Indonesia’s largest investment forum is monumental gathering of power and potential. Against global uncertainty, the event became a masterclass in resilience",
        'paragraphs' => [
            "Indonesia is shifting toward an expansive state-capitalist model by consolidating strategic state assets under Danantara, enforcing natural resource downstreaming, and funding redistributive initiatives like Makan Bergizi Gratis (MBG) and Koperasi Desa Merah Putih (KDMP).",
            "This institutional transformation builds upon policies from previous administrations to pursue an ambitious 8% growth target, supported by initial growth rates above 5% in early 2026.",
            "To ensure inclusive and sustainable growth, policymakers must navigate significant structural risks, including Danantara's internal governance conflicts, the limited labor absorption of capital-intensive downstream industries, and workforce disruptions from automation and artificial intelligence."
        ]
    ];
    public array $economicModel = []; // alias for backward compatibility

    /**
     * Distinct Speakers Roster
     * Gambar speaker menggunakan wowo.png sesuai instruksi user
     */
    public array $speakers = [
        'enggartiasto-lukita' => [
            'id'       => 1,
            'slug'     => 'enggartiasto-lukita',
            'name'     => 'Enggartiasto Lukita',
            'role'     => 'Executive Chairman B-Universe',
            'photo'    => 'placeholder.png',
            'featured' => false
        ],
        'rosan-roeslani' => [
            'id'       => 2,
            'slug'     => 'rosan-roeslani',
            'name'     => 'Rosan Roeslani',
            'role'     => 'Minister of Investment and Downstream Industry/Head of Investment Coordinating Board',
            'photo'    => 'placeholder.png',
            'featured' => true
        ],
        'prabowo-subianto' => [
            'id'       => 3,
            'slug'     => 'prabowo-subianto',
            'name'     => 'Prabowo Subianto',
            'role'     => 'President of The Republic of Indonesia',
            'photo'    => 'speakers.png',
            'featured' => true
        ],
        'hashim-djojohadikusumo' => [
            'id'       => 4,
            'slug'     => 'hashim-djojohadikusumo',
            'name'     => 'Hashim Djojohadikusumo',
            'role'     => 'Special Envoy of the President of The Republic of Indonesia for Climate and Energy',
            'photo'    => 'placeholder.png',
            'featured' => true
        ],
        'bahlil-lahadalia' => [
            'id'       => 5,
            'slug'     => 'bahlil-lahadalia',
            'name'     => 'Bahlil Lahadalia',
            'role'     => 'Minister of Energy and Mineral Resources of the Republic of Indonesia',
            'photo'    => 'placeholder.png',
            'featured' => true
        ],
        'simon-aloysius-mantiri' => [
            'id'       => 6,
            'slug'     => 'simon-aloysius-mantiri',
            'name'     => 'Simon Aloysius Mantiri',
            'role'     => 'President Director PT Pertamina (Persero)',
            'photo'    => 'placeholder.png',
            'featured' => false
        ],
        'darmawan-prasodjo' => [
            'id'       => 7,
            'slug'     => 'darmawan-prasodjo',
            'name'     => 'Darmawan Prasodjo',
            'role'     => 'President Director PT PLN (Persero)',
            'photo'    => 'placeholder.png',
            'featured' => false
        ],
        'bernardus-imanto' => [
            'id'       => 8,
            'slug'     => 'bernardus-imanto',
            'name'     => 'Bernardus Imanto',
            'role'     => 'President Director PT Vale Indonesia Tbk',
            'photo'    => 'placeholder.png',
            'featured' => false
        ],
        'tony-wenas' => [
            'id'       => 9,
            'slug'     => 'tony-wenas',
            'name'     => 'Tony Wenas',
            'role'     => 'President Director PT Freeport Indonesia',
            'photo'    => 'placeholder.png',
            'featured' => false
        ],
        'rachmat-makkasau' => [
            'id'       => 10,
            'slug'     => 'rachmat-makkasau',
            'name'     => 'Rachmat Makkasau',
            'role'     => 'President Director PT Amman Mineral Nusa Tenggara',
            'photo'    => 'placeholder.png',
            'featured' => false
        ],
        'iwan-dewono-budiyuwono' => [
            'id'       => 11,
            'slug'     => 'iwan-dewono-budiyuwono',
            'name'     => 'Iwan Dewono Budiyuwono',
            'role'     => 'President Director PT Alamtri Resources Indonesia Tbk',
            'photo'    => 'placeholder.png',
            'featured' => false
        ],
        'pramono-anung' => [
            'id'       => 12,
            'slug'     => 'pramono-anung',
            'name'     => 'Pramono Anung',
            'role'     => 'Governor of Jakarta',
            'photo'    => 'placeholder.png',
            'featured' => true
        ],
        'airlangga-hartarto' => [
            'id'       => 13,
            'slug'     => 'airlangga-hartarto',
            'name'     => 'Airlangga Hartarto',
            'role'     => 'Coordinating Minister for Economic Affairs',
            'photo'    => 'placeholder.png',
            'featured' => true
        ],
        'purbaya-yudhi-sadewa' => [
            'id'       => 14,
            'slug'     => 'purbaya-yudhi-sadewa',
            'name'     => 'Purbaya Yudhi Sadewa',
            'role'     => 'Chairman of the Board of Commissioners LPS',
            'photo'    => 'placeholder.png',
            'featured' => false
        ],
        'rizki-nauli-siregar' => [
            'id'       => 15,
            'slug'     => 'rizki-nauli-siregar',
            'name'     => 'Dr. Rizki Nauli Siregar',
            'role'     => 'Economist, Institute for Economic and Social Research (LPEM FEB UI)',
            'photo'    => 'placeholder.png',
            'featured' => false
        ],
        'kiki-verico' => [
            'id'       => 16,
            'slug'     => 'kiki-verico',
            'name'     => 'Kiki Verico ,S.E., MRI., Ph.D',
            'role'     => 'Associate Professor of International Economics & Vice Dean FEB UI',
            'photo'    => 'placeholder.png',
            'featured' => false
        ],
        'talitha-chairunissa' => [
            'id'       => 17,
            'slug'     => 'talitha-chairunissa',
            'name'     => 'Talitha Chairunissa',
            'role'     => 'Economist and Public Policy Expert',
            'photo'    => 'placeholder.png',
            'featured' => false
        ],
        'sri-mulyani' => [
            'id'       => 18,
            'slug'     => 'sri-mulyani',
            'name'     => 'Sri Mulyani Indrawati',
            'role'     => 'Minister of Finance of The Republic of Indonesia',
            'photo'    => 'placeholder.png',
            'featured' => true
        ],
        'erick-thohir' => [
            'id'       => 19,
            'slug'     => 'erick-thohir',
            'name'     => 'Erick Thohir',
            'role'     => 'Minister of State-Owned Enterprises',
            'photo'    => 'placeholder.png',
            'featured' => true
        ],
        'luhut-binsar' => [
            'id'       => 20,
            'slug'     => 'luhut-binsar',
            'name'     => 'Luhut Binsar Pandjaitan',
            'role'     => 'Chairman of the National Economic Council',
            'photo'    => 'placeholder.png',
            'featured' => true
        ],
        'agus-harimurti' => [
            'id'       => 21,
            'slug'     => 'agus-harimurti',
            'name'     => 'Agus Harimurti Yudhoyono',
            'role'     => 'Coordinating Minister for Infrastructure & Regional Development',
            'photo'    => 'placeholder.png',
            'featured' => false
        ],
        'zulkifli-hasan' => [
            'id'       => 22,
            'slug'     => 'zulkifli-hasan',
            'name'     => 'Zulkifli Hasan',
            'role'     => 'Coordinating Minister for Food Affairs',
            'photo'    => 'placeholder.png',
            'featured' => false
        ],
        'suahasil-nazara' => [
            'id'       => 23,
            'slug'     => 'suahasil-nazara',
            'name'     => 'Suahasil Nazara',
            'role'     => 'Vice Minister of Finance of Indonesia',
            'photo'    => 'placeholder.png',
            'featured' => false
        ],
        'thomas-djiwandono' => [
            'id'       => 24,
            'slug'     => 'thomas-djiwandono',
            'name'     => 'Thomas Djiwandono',
            'role'     => 'Vice Minister of Finance of Indonesia',
            'photo'    => 'placeholder.png',
            'featured' => false
        ],
        'perry-warjiyo' => [
            'id'       => 25,
            'slug'     => 'perry-warjiyo',
            'name'     => 'Perry Warjiyo',
            'role'     => 'Governor of Bank Indonesia',
            'photo'    => 'placeholder.png',
            'featured' => true
        ],
        'mahendra-siregar' => [
            'id'       => 26,
            'slug'     => 'mahendra-siregar',
            'name'     => 'Mahendra Siregar',
            'role'     => 'Chairman of Board of Commissioners Otoritas Jasa Keuangan (OJK)',
            'photo'    => 'placeholder.png',
            'featured' => false
        ],
        'iman-rachman' => [
            'id'       => 27,
            'slug'     => 'iman-rachman',
            'name'     => 'Iman Rachman',
            'role'     => 'President Director Indonesia Stock Exchange (IDX)',
            'photo'    => 'placeholder.png',
            'featured' => false
        ],
        'anindya-bakrie' => [
            'id'       => 28,
            'slug'     => 'anindya-bakrie',
            'name'     => 'Anindya Bakrie',
            'role'     => 'Chairman of the Indonesian Chamber of Commerce and Industry (KADIN)',
            'photo'    => 'placeholder.png',
            'featured' => false
        ],
        'arsjad-rasjid' => [
            'id'       => 29,
            'slug'     => 'arsjad-rasjid',
            'name'     => 'Arsjad Rasjid',
            'role'     => 'President Director PT Indika Energy Tbk',
            'photo'    => 'placeholder.png',
            'featured' => false
        ],
        'shinta-kamdani' => [
            'id'       => 30,
            'slug'     => 'shinta-kamdani',
            'name'     => 'Shinta Widjaja Kamdani',
            'role'     => 'Chairwoman of the Indonesian Employers Association (APINDO)',
            'photo'    => 'placeholder.png',
            'featured' => false
        ],
        'pandu-sjahrir' => [
            'id'       => 31,
            'slug'     => 'pandu-sjahrir',
            'name'     => 'Pandu Patria Sjahrir',
            'role'     => 'Founding Partner AC Ventures & Chairman AFTECH',
            'photo'    => 'placeholder.png',
            'featured' => false
        ],
        'garibaldi-thohir' => [
            'id'       => 32,
            'slug'     => 'garibaldi-thohir',
            'name'     => 'Garibaldi Thohir',
            'role'     => 'President Director PT Adaro Energy Indonesia Tbk',
            'photo'    => 'placeholder.png',
            'featured' => false
        ],
        'anggito-abimanyu' => [
            'id'       => 33,
            'slug'     => 'anggito-abimanyu',
            'name'     => 'Anggito Abimanyu',
            'role'     => 'Chairman of the Board of Commissioners Indonesia Deposit Insurance Corporation',
            'photo'    => 'placeholder.png',
            'featured' => false
        ],
        'ngurah-wirawan' => [
            'id'       => 34,
            'slug'     => 'ngurah-wirawan',
            'name'     => 'Ngurah Wirawan',
            'role'     => 'President Director Danantara Industrial Estate',
            'photo'    => 'placeholder.png',
            'featured' => false
        ],
        'suryo-pratomo' => [
            'id'       => 35,
            'slug'     => 'suryo-pratomo',
            'name'     => 'Suryo Pratomo',
            'role'     => 'President Director PT Gajah Tunggal Tbk',
            'photo'    => 'placeholder.png',
            'featured' => false
        ],
        'setyono-djuandi-darmono' => [
            'id'       => 36,
            'slug'     => 'setyono-djuandi-darmono',
            'name'     => 'Setyono Djuandi Darmono',
            'role'     => 'President Director PT Kawasan Industri Jababeka Tbk (KIJA)',
            'photo'    => 'placeholder.png',
            'featured' => false
        ],
        'edi-rivai' => [
            'id'       => 37,
            'slug'     => 'edi-rivai',
            'name'     => "Edi Riva'i",
            'role'     => 'Director of Legal, External Affairs & Circular Economy PT Chandra Asri Pacific Tbk',
            'photo'    => 'placeholder.png',
            'featured' => false
        ],
        'indrieffouny-indra' => [
            'id'       => 38,
            'slug'     => 'indrieffouny-indra',
            'name'     => 'Indrieffouny Indra',
            'role'     => 'President Director PT Semen Indonesia (Persero) Tbk',
            'photo'    => 'placeholder.png',
            'featured' => false
        ],
    ];

    /**
     * Schedule Items (Day 1 & Day 2)
     */
    public array $schedule = [
        'day_1' => [
            'label' => 'Day 1',
            'date'  => 'October 7th, 2026',
            'items' => [
                [
                    'time'        => '08:30 AM - 09:30 AM',
                    'room'        => 'Investor Daily Plenary Room',
                    'title'       => 'Registration',
                    'is_break'    => false,
                    'moderator'   => null,
                    'speaker_ids' => []
                ],
                [
                    'time'        => '09:30 AM - 09:35 AM',
                    'room'        => 'Investor Daily Plenary Room',
                    'title'       => 'Menyanyikan Lagu Nasional',
                    'is_break'    => false,
                    'moderator'   => null,
                    'speaker_ids' => []
                ],
                [
                    'time'        => '09:35 AM - 09:50 AM',
                    'room'        => 'Investor Daily Plenary Room',
                    'title'       => 'Welcoming Speech',
                    'is_break'    => false,
                    'moderator'   => null,
                    'speaker_ids' => ['enggartiasto-lukita']
                ],
                [
                    'time'        => '09:50 AM - 10:20 AM',
                    'room'        => 'Investor Daily Plenary Room',
                    'title'       => 'Apresiasi Investasi',
                    'is_break'    => false,
                    'moderator'   => null,
                    'speaker_ids' => []
                ],
                [
                    'time'        => '10:20 AM - 10:50 AM',
                    'room'        => 'Investor Daily Plenary Room',
                    'title'       => 'Opening Speech',
                    'is_break'    => false,
                    'moderator'   => null,
                    'speaker_ids' => ['rosan-roeslani']
                ],
                [
                    'time'        => '10:50 AM - 11:20 AM',
                    'room'        => 'Investor Daily Plenary Room',
                    'title'       => 'Keynote Speech',
                    'is_break'    => false,
                    'moderator'   => null,
                    'speaker_ids' => ['prabowo-subianto']
                ],
                [
                    'time'        => '11:20 AM - 11:30 AM',
                    'room'        => 'Investor Daily Plenary Room',
                    'title'       => 'Opening Ceremony',
                    'is_break'    => false,
                    'moderator'   => null,
                    'speaker_ids' => []
                ],
                [
                    'time'        => '12:30 AM - 12:00 PM',
                    'room'        => 'Investor Daily Plenary Room',
                    'title'       => "Fireside Chat 1:\nIndonesia’s Economic Model",
                    'is_break'    => false,
                    'moderator'   => null,
                    'speaker_ids' => ['hashim-djojohadikusumo']
                ],
                [
                    'time'        => '12:00 PM - 13:30 PM',
                    'room'        => null,
                    'title'       => 'Lunch Break',
                    'is_break'    => true,
                    'moderator'   => null,
                    'speaker_ids' => []
                ],
                [
                    'time'        => '13:30 PM - 14:00 PM',
                    'room'        => 'Investor Daily Plenary Room',
                    'title'       => "Keynote Speech 1:\nPowering Tomorrow: Integrating Sustainable Energy and Minerals for Economic Leap",
                    'is_break'    => false,
                    'moderator'   => null,
                    'speaker_ids' => ['bahlil-lahadalia']
                ],
                [
                    'time'        => '14:00 PM - 15:15 PM',
                    'room'        => 'Investor Daily Plenary Room',
                    'title'       => "Plenary Session 1:\nPowering Tomorrow: Integrating Sustainable Energy and Minerals for Economic Leap",
                    'is_break'    => false,
                    'moderator'   => [
                        'label' => 'MOD:',
                        'name'  => 'Djaka Susila',
                        'role'  => 'Editor In Chief - Integrated Investor',
                        'photo'    => 'placeholder.png'
                    ],
                    'speaker_ids' => [
                        'simon-aloysius-mantiri',
                        'darmawan-prasodjo',
                        'bernardus-imanto',
                        'tony-wenas',
                        'rachmat-makkasau',
                        'iwan-dewono-budiyuwono'
                    ]
                ],
                [
                    'time'        => '15:15 PM - 15:45 PM',
                    'room'        => 'Investor Daily Plenary Room',
                    'title'       => "Special Remarks:\nFive Centuries of Legacy: Shaping Jakarta as Inclusive World Megacity",
                    'is_break'    => false,
                    'moderator'   => null,
                    'speaker_ids' => ['pramono-anung']
                ],
                [
                    'time'        => '15:45 PM - 16:00 PM',
                    'room'        => null,
                    'title'       => 'Coffee Break',
                    'is_break'    => true,
                    'moderator'   => null,
                    'speaker_ids' => []
                ],
                [
                    'time'        => '15:15 PM - 15:45 PM',
                    'title'       => 'Special Session A',
                    'is_break'    => false,
                    'is_multi_room' => true,
                    'rooms'       => [
                        [
                            'room' => 'Investor Daily Room',
                            'sessions' => [
                                [
                                    'title'       => "Keynote Speech Special Session A.1:\nAnchoring Indonesia's Long-Term Development Ambitions",
                                    'moderator'   => null,
                                    'speaker_ids' => ['anggito-abimanyu']
                                ],
                                [
                                    'title'       => "Special Session A.1:\nRebalancing Growth For Stronger Indonesia",
                                    'moderator'   => [
                                        'label' => 'MOD:',
                                        'name'  => 'Nasori Ahmad',
                                        'role'  => 'Managing Editor Investor Daily',
                                        'photo'    => 'placeholder.png'
                                    ],
                                    'speaker_ids' => [
                                        'ngurah-wirawan',
                                        'suryo-pratomo',
                                        'setyono-djuandi-darmono',
                                        'edi-rivai',
                                        'indrieffouny-indra'
                                    ]
                                ]
                            ]
                        ]
                    ]
                ],
            ]
        ],
        'day_2' => [
            'label' => 'Day 2',
            'date'  => 'October 8th, 2026',
            'items' => [
                [
                    'time'        => '08:30 AM - 09:30 AM',
                    'room'        => 'Investor Daily Plenary Room',
                    'title'       => 'Registration & Morning Networking',
                    'is_break'    => false,
                    'moderator'   => null,
                    'speaker_ids' => []
                ],
                [
                    'time'        => '09:30 AM - 10:15 AM',
                    'room'        => 'Investor Daily Plenary Room',
                    'title'       => "Keynote Speech 2:\nAccelerating Digital Infrastructure and AI Sovereignty in Indonesia",
                    'is_break'    => false,
                    'moderator'   => null,
                    'speaker_ids' => ['rosan-roeslani']
                ],
                [
                    'time'        => '10:15 AM - 12:00 PM',
                    'room'        => 'Investor Daily Plenary Room',
                    'title'       => "Plenary Session 2:\nCapital Market Evolution & Financial Inclusivity in Transition Economy",
                    'is_break'    => false,
                    'moderator'   => [
                        'label' => 'MOD:',
                        'name'  => 'Djaka Susila',
                        'role'  => 'Editor In Chief - Integrated Investor',
                        'photo'    => 'placeholder.png'
                    ],
                    'speaker_ids' => [
                        'enggartiasto-lukita',
                        'darmawan-prasodjo',
                        'tony-wenas',
                        'rachmat-makkasau'
                    ]
                ],
                [
                    'time'        => '12:00 PM - 13:30 PM',
                    'room'        => null,
                    'title'       => 'Networking Lunch Break',
                    'is_break'    => true,
                    'moderator'   => null,
                    'speaker_ids' => []
                ],
                [
                    'time'        => '13:30 PM - 15:00 PM',
                    'room'        => 'Investor Daily Plenary Room',
                    'title'       => "Fireside Chat 2:\nSustainable Downstream Industrialization & Green Financing",
                    'is_break'    => false,
                    'moderator'   => null,
                    'speaker_ids' => ['bahlil-lahadalia', 'bernardus-imanto']
                ],
                [
                    'time'        => '15:00 PM - 15:30 PM',
                    'room'        => 'Investor Daily Plenary Room',
                    'title'       => "Closing Remarks & Communique Declaration",
                    'is_break'    => false,
                    'moderator'   => null,
                    'speaker_ids' => ['enggartiasto-lukita']
                ],
            ]
        ]
    ];

    /**
     * Gallery Media (Featured landscape + 3 thumbnails menggunakan image1.png)
     */
    public array $gallery = [
        'featured' => [
            'id'    => 1,
            'image' => 'image1.png',
            'alt'   => 'Investor Daily Summit Dignitaries Walking to Main Hall'
        ],
        'thumbnails' => [
            [
                'id'    => 2,
                'image' => 'image1.png',
                'alt'   => 'Dignitaries and Security Escort at Venue'
            ],
            [
                'id'    => 3,
                'image' => 'image1.png',
                'alt'   => 'Executive Discussion During VIP Session'
            ],
            [
                'id'    => 4,
                'image' => 'image1.png',
                'alt'   => 'Enggartiasto Lukita and Delegation Greeting Guests'
            ]
        ]
    ];

    /**
     * Related News (Slider dengan 8 cards, gesture scroll + arrow nav)
     */
    public array $relatedNews = [
        [
            'id'    => 1,
            'title' => 'Investor Daily Summit 2026: Menakar Peluang Hilirisasi dan Transisi Energi Bersih',
            'image' => 'image1.png',
            'url'   => '#news-1'
        ],
        [
            'id'    => 2,
            'title' => 'Danantara Siap Konsolidasikan Aset Strategis BUMN Menuju Pertumbuhan Ekonomi 8%',
            'image' => 'image2.jpg',
            'url'   => '#news-2'
        ],
        [
            'id'    => 3,
            'title' => 'Investor Day 2026, Adrie Basuki Meraup Cuan Berkelanjutan dari Limbah Fashion',
            'image' => 'image3.jpg',
            'url'   => '#news-3'
        ],
        [
            'id'    => 4,
            'title' => 'Strategi Nasional Integrasi Mineral Kritis untuk Ekosistem Kendaraan Listrik Dunia',
            'image' => 'image1.png',
            'url'   => '#news-4'
        ],
        [
            'id'    => 5,
            'title' => 'Sinergi B-Universe & Pelaku Pasar Dorong Iklim Investasi Berdaya Saing Global',
            'image' => 'image2.jpg',
            'url'   => '#news-5'
        ],
        [
            'id'    => 6,
            'title' => 'Transformasi Digital dan AI dalam Akselerasi Efisiensi Sektor Perbankan Indonesia',
            'image' => 'image3.jpg',
            'url'   => '#news-6'
        ],
        [
            'id'    => 7,
            'title' => 'Kadin Optimistis Aliran Modal Asing ke Proyek Hijau Melonjak di Akhir 2026',
            'image' => 'image1.png',
            'url'   => '#news-7'
        ],
        [
            'id'    => 8,
            'title' => 'Masa Depan Ketahanan Pangan Nasional: Kolaborasi Pemerintah dan Sektor Agrikultur',
            'image' => 'image2.jpg',
            'url'   => '#news-8'
        ],
    ];

    /**
     * Sponsors & Partners Wall
     * Point 2 User request:
     * - 2 logo paling gede (Baris 1)
     * - 2 logo lagi lebih kecil (Baris 2)
     * - 6 logo reguler per baris, total ada 6 baris (2, 2, 6, 6, 6, 6)
     * - Partnership ukurannya lebih kecil lagi serta ada 4
     */
    public array $sponsors = [
        'sponsored_by' => [
            'badge' => 'SPONSORED BY:',
            'rows'  => [
                // Baris 1: 2 logo paling gede
                [
                    'tier'  => 'tier-xl',
                    'logos' => [
                        ['name' => 'Sponsor 1', 'logo' => 'sponsor1.png'],
                        ['name' => 'Sponsor 2', 'logo' => 'sponsor2.png'],
                    ]
                ],
                // Baris 2: 2 logo lagi lebih kecil
                [
                    'tier'  => 'tier-lg',
                    'logos' => [
                        ['name' => 'Sponsor 3', 'logo' => 'sponsor3.png'],
                        ['name' => 'Sponsor 4', 'logo' => 'sponsor4.png'],
                    ]
                ],
                // Baris 3: 6 logo reguler
                [
                    'tier'  => 'tier-regular',
                    'logos' => [
                        ['name' => 'Sponsor 1', 'logo' => 'sponsor1.png'],
                        ['name' => 'Sponsor 2', 'logo' => 'sponsor2.png'],
                        ['name' => 'Sponsor 3', 'logo' => 'sponsor3.png'],
                        ['name' => 'Sponsor 4', 'logo' => 'sponsor4.png'],
                        ['name' => 'Sponsor 5', 'logo' => 'sponsor5.png'],
                        ['name' => 'Sponsor 6', 'logo' => 'sponsor6.png'],
                    ]
                ],
                // Baris 4: 6 logo reguler
                [
                    'tier'  => 'tier-regular',
                    'logos' => [
                        ['name' => 'Sponsor 7', 'logo' => 'sponsor7.png'],
                        ['name' => 'Sponsor 1', 'logo' => 'sponsor1.png'],
                        ['name' => 'Sponsor 2', 'logo' => 'sponsor2.png'],
                        ['name' => 'Sponsor 3', 'logo' => 'sponsor3.png'],
                        ['name' => 'Sponsor 4', 'logo' => 'sponsor4.png'],
                        ['name' => 'Sponsor 5', 'logo' => 'sponsor5.png'],
                    ]
                ],
                // Baris 5: 6 logo reguler
                [
                    'tier'  => 'tier-regular',
                    'logos' => [
                        ['name' => 'Sponsor 6', 'logo' => 'sponsor6.png'],
                        ['name' => 'Sponsor 7', 'logo' => 'sponsor7.png'],
                        ['name' => 'Sponsor 1', 'logo' => 'sponsor1.png'],
                        ['name' => 'Sponsor 2', 'logo' => 'sponsor2.png'],
                        ['name' => 'Sponsor 3', 'logo' => 'sponsor3.png'],
                        ['name' => 'Sponsor 4', 'logo' => 'sponsor4.png'],
                    ]
                ],
                // Baris 6: Bronze Tier (sm)
                [
                    'tier'  => 'tier-sm',
                    'logos' => [
                        ['name' => 'Bronze Sponsor 1', 'logo' => 'sponsor1.png'],
                        ['name' => 'Bronze Sponsor 2', 'logo' => 'sponsor2.png'],
                        ['name' => 'Bronze Sponsor 3', 'logo' => 'sponsor3.png'],
                        ['name' => 'Bronze Sponsor 4', 'logo' => 'sponsor4.png'],
                        ['name' => 'Bronze Sponsor 5', 'logo' => 'sponsor5.png'],
                        ['name' => 'Bronze Sponsor 6', 'logo' => 'sponsor6.png'],
                    ]
                ],
            ]
        ],
        'partnerships' => [
            'badge' => 'PARTNERSHIP:',
            'rows'  => [
                // Partnership: 4 logo, ukuran lebih kecil lagi
                [
                    'tier'  => 'tier-partnership',
                    'logos' => [
                        ['name' => 'Partner 1', 'logo' => 'sponsor4.png'],
                        ['name' => 'Partner 2', 'logo' => 'sponsor5.png'],
                        ['name' => 'Partner 3', 'logo' => 'sponsor6.png'],
                        ['name' => 'Partner 4', 'logo' => 'sponsor7.png'],
                    ]
                ]
            ]
        ]
    ];
}
