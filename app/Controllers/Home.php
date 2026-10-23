<?php

namespace App\Controllers;

use Config\MockData;

class Home extends BaseController
{
    public function index(): string
    {
        $mock = config(MockData::class);

        $site = $mock->site;
        $overview = $mock->overview ?? $mock->economicModel ?? [];
        $economicModel = $overview; // backward compatibility
        $speakers = $mock->speakers;
        $schedule = $mock->schedule;
        $gallery = $mock->gallery;
        $relatedNewsInvestor = $this->fetchNewsFromPortal('investor', 4) ?: [];
        $relatedNewsBeritaSatu = $this->fetchNewsFromPortal('beritasatu', 4) ?: [];
        $relatedNewsJakartaGlobe = $this->fetchNewsFromPortal('jakartaglobe', 4) ?: [];
        $relatedNewsDatasatu = $this->fetchNewsFromPortal('datasatu', 4) ?: [];
        $summaryNews = $this->fetchNewsFromPortal('overview', 100) ?: [];
        $relatedNews = $relatedNewsInvestor;
        $sponsors = $mock->sponsors;

        $themeProperties = [];
        $heroCarousel = [];
        $galleryImages = [];
        $sponsorPartnership = null;
        $sectionDecorations = [];
        $customPlacements = [];
        $seo = [];
        $locations = null;

        // Attempt live synchronization with BackendCMS REST API for EMF Event
        $cacheKey = 'cms_live_event_emf-2026';
        $backupKey = 'cms_live_event_emf-2026_bak';
        $noCache = $this->request->getGet('nocache') !== null || $this->request->getGet('refresh') !== null;
        $live = $noCache ? null : cache($cacheKey);

        if ($live === null) {
            $apiUrls = [
                'http://[::1]:8081/api/v1/event/emf-2026',
                'http://127.0.0.1:8081/api/v1/event/emf-2026',
                'http://localhost:8081/api/v1/event/emf-2026',
                base_url('backend/api/v1/event/emf-2026'),
            ];

            $fetched = null;
            foreach ($apiUrls as $apiUrl) {
                try {
                    $client = \Config\Services::curlrequest([
                        'timeout' => 5.0,
                        'verify'  => false,
                    ]);
                    $response = $client->get($apiUrl, ['http_errors' => false]);
                    if ($response->getStatusCode() === 200) {
                        $json = json_decode($response->getBody(), true);
                        if (!empty($json['data'])) {
                            $fetched = $json['data'];
                            log_message('info', "[EMF Sync] Fetched successfully from: {$apiUrl}");
                            break;
                        }
                    }
                } catch (\Throwable $e) {
                    // Try next URL fallback
                }
            }

            if (!empty($fetched)) {
                $live = $fetched;
                $cacheTtl = (ENVIRONMENT === 'development') ? 5 : 60;
                cache()->save($cacheKey, $live, $cacheTtl);
                // Save long-term snapshot backup (30 days)
                cache()->save($backupKey, $live, 86400 * 30);
                log_message('info', "[EMF Sync] Saved active cache & snapshot backup cache.");
            } else {
                // Fallback to snapshot backup cache if API failed
                $backupData = cache($backupKey);
                if (!empty($backupData)) {
                    $live = $backupData;
                    log_message('warning', "[EMF Sync] API failed. Using snapshot backup cache ({$backupKey}).");
                }
            }
        }

        if (!empty($live)) {
            // Helper to normalize any assets/uploads URLs returned from BackendCMS (port 8081)
            // so they load directly from Frontend domain/tunnel without mixed content or localhost blocking
            $fixUrl = function ($url) {
                if (empty($url) || !is_string($url))
                    return $url;
                if (preg_match('#^https?://[^/]+/(assets/.*|uploads/.*)$#', $url, $m)) {
                    return base_url($m[1]);
                }
                return $url;
            };

            // Sync SEO metadata
            if (!empty($live['seo'])) {
                $seo = $live['seo'];
            }

            // Sync site metadata & branding
            if (!empty($live['site'])) {
                $site['title'] = $live['site']['name'] ?? $site['title'];
                $site['theme'] = $live['site']['theme'] ?? $site['theme'];
                $site['dates'] = $live['site']['dates'] ?? $site['dates'];
                $site['venue'] = $live['site']['venue'] ?? $site['venue'];
                $site['ticket_url'] = $live['site']['cta_ticket_url'] ?? $site['ticket_url'];
                $site['cta_ticket_label'] = $live['site']['cta_ticket_label'] ?? 'Get Tickets';
                $site['cta_ticket_active'] = isset($live['site']['cta_ticket_active']) ? $live['site']['cta_ticket_active'] : true;
                $site['floating_bar'] = $live['site']['floating_bar'] ?? [
                    'enabled' => true,
                    'text' => 'Get Your Seat Now!',
                    'btn_label' => 'Get Tickets',
                    'btn_url' => null,
                ];
                $site['copyright'] = $live['site']['copyright'] ?? 'Investor Daily Summit.';
                $site['logo_a'] = $live['site']['logo_a'] ?? '';
                $site['logo_b'] = $live['site']['logo_b'] ?? '';
                $site['logo_a_color'] = $live['site']['logo_a_color'] ?? ($live['site']['logo_a'] ?? '');
                $site['logo_a_white'] = $live['site']['logo_a_white'] ?? '';
                $site['logo_a_target_url'] = $live['site']['logo_a_target_url'] ?? '#home';
                $site['logo_b_color'] = $live['site']['logo_b_color'] ?? ($live['site']['logo_b'] ?? '');
                $site['logo_b_white'] = $live['site']['logo_b_white'] ?? '';
                $site['logo_b_target_url'] = $live['site']['logo_b_target_url'] ?? 'https://investor.id';
                $site['logo_overview'] = $live['site']['logo_overview'] ?? '';
                $site['logo_overview_target_url'] = $live['site']['logo_overview_target_url'] ?? '#home';
                $site['logo_floating'] = $live['site']['logo_floating'] ?? '';
                $site['logo_footer'] = $live['site']['logo_footer'] ?? '';
                $site['logo_footer_white'] = $live['site']['logo_footer_white'] ?? '';
                $site['logo_footer_target_url'] = $live['site']['logo_footer_target_url'] ?? 'https://investor.id';
                $site['logo_placements'] = $live['site']['logo_placements'] ?? [];
                $site['contact_phone'] = $live['site']['contact']['phone'] ?? '+62 812-1267-8789';
                $site['contact_label'] = $live['site']['contact']['label'] ?? 'Phone';
                $site['contact_url'] = $live['site']['contact']['url'] ?? null;
                $site['socials'] = $live['site']['socials'] ?? [];
            }

            // Cek status kuota tiket langsung dari database
            $db = \Config\Database::connect();
            $eventDb = $db->table('events')->where('slug', 'emf-2026')->get()->getRowArray();
            $quota = (int)($eventDb['quota'] ?? 0);
            $confirmedCount = 0;
            if (!empty($eventDb['id'])) {
                $confirmedCount = $db->table('participants')
                    ->where('event_id', $eventDb['id'])
                    ->where('status', 'confirmed')
                    ->countAllResults();
            }
            $isSoldOut = ($quota > 0 && $confirmedCount >= $quota);
            $site['is_sold_out'] = $isSoldOut;
            if ($isSoldOut) {
                $site['cta_ticket_label'] = 'Sold Out';
                if (!empty($site['floating_bar'])) {
                    $site['floating_bar']['btn_label'] = 'Sold Out';
                    $site['floating_bar']['text'] = 'Mohon Maaf, Kuota Tiket Sudah Penuh (Sold Out)';
                }
            }

            // Define EMF Green & Yellow Palette Theme Properties
            $emfThemeTokens = [
                '--yellow-1' => '#fefbed',
                '--yellow-2' => '#fcf6d3',
                '--yellow-3' => '#faeeb1',
                '--yellow-4' => '#f8e68c',
                '--yellow-5' => '#f6df6a',
                '--yellow-6' => '#f4d849',
                '--yellow-7' => '#cfb83e',
                '--yellow-8' => '#ad9934',
                '--yellow-9' => '#8b7b2a',
                '--yellow-10' => '#6e6121',
                '--green-1' => '#edf5ea',
                '--green-2' => '#d3e6cd',
                '--green-3' => '#b1d3a5',
                '--green-4' => '#8cbe7b',
                '--green-5' => '#6aab54',
                '--green-6' => '#49982e',
                '--green-7' => '#3e8127',
                '--green-8' => '#346c21',
                '--green-9' => '#2a571a',
                '--green-10' => '#214415',
                // Semantic Tokens: BASE ADALAH KUNING (yellow-6: #f4d849)
                '--bg-page' => '#f4d849',
                '--bg-section-alt' => '#fefbed',
                '--bg-card' => '#fcf6d3',
                '--text-primary' => '#49982e',
                '--text-secondary' => '#3e8127',
                '--text-dark' => '#49982e',
                '--accent-cyan' => '#49982e',
                '--accent-blue' => '#49982e',
                '--accent-light' => '#8cbe7b',
                '--navbar-cta-bg' => '#49982e',
                '--navbar-cta-text' => '#ffffff',
                '--navbar-cta-hover-bg' => '#3e8127',
                '--navbar-cta-hover-text' => '#ffffff',
                '--navbar-cta-hover-outline' => '#49982e',
                '--footer-bg' => '#f4d849',
                '--footer-social-bg' => '#49982e',
                '--footer-social-color' => '#ffffff',
                '--footer-text-color' => '#346c21',
                '--footer-border-color' => 'rgba(73, 152, 46, 0.25)',
            ];

            // If BackendCMS returned live theme_properties, merge them EXCEPT for protected EMF theme tokens
            $protectedEmfTokens = [
                '--footer-bg',
                '--footer-social-bg',
                '--footer-social-color',
                '--footer-text-color',
                '--footer-border-color',
                '--bg-page',
                '--bg-section-alt',
                '--bg-card',
                '--text-primary',
                '--text-secondary',
                '--text-dark',
                '--accent-cyan',
                '--accent-blue',
                '--accent-light',
                '--navbar-cta-bg',
                '--navbar-cta-text',
                '--navbar-cta-hover-bg',
                '--navbar-cta-hover-text',
                '--navbar-cta-hover-outline',
            ];

            if (!empty($live['theme_properties']) && is_array($live['theme_properties'])) {
                foreach ($live['theme_properties'] as $tp) {
                    if (!empty($tp['property']) && isset($tp['value'])) {
                        if (!in_array($tp['property'], $protectedEmfTokens, true)) {
                            $emfThemeTokens[$tp['property']] = $tp['value'];
                        }
                    }
                }
            }

            // Convert to array of ['property' => ..., 'value' => ...]
            $themeProperties = [];
            foreach ($emfThemeTokens as $prop => $val) {
                $themeProperties[] = ['property' => $prop, 'value' => $val];
            }

            // Sync hero carousel
            if (!empty($live['hero_carousel'])) {
                $heroCarousel = $live['hero_carousel'];
            }

            // Sync gallery images
            if (!empty($live['gallery'])) {
                $galleryImages = $live['gallery'];
            }

            // Sync overview (f.k.a economic model)
            $liveOverview = $live['overview'] ?? $live['economicModel'] ?? null;
            if (!empty($liveOverview)) {
                if (!empty($liveOverview['badge'])) {
                    $overview['badge'] = $liveOverview['badge'];
                }
                if (!empty($liveOverview['intro'])) {
                    $overview['intro'] = $liveOverview['intro'];
                }
                if (!empty($liveOverview['collage_images'])) {
                    $overview['collage_images'] = $liveOverview['collage_images'];
                }
                if (!empty($liveOverview['content'])) {
                    $paras = array_filter(explode("\n\n", str_replace("\r\n", "\n", $liveOverview['content'])));
                    if (!empty($paras)) {
                        $overview['paragraphs'] = array_values($paras);
                    }
                }
                $economicModel = $overview;
            }

            // Sync livestream settings from live API
            $livestream = $live['livestream'] ?? [
                'title' => 'Investor Daily Summit Live Broadcast',
                'youtube_url' => 'https://www.youtube.com/live/mKr5aNganlc',
                'is_active' => true,
            ];
            $livestreams = $live['livestreams'] ?? (
                !empty($livestream['is_active']) && !empty($livestream['youtube_url'])
                ? [$livestream]
                : []
            );

            // Sync news portals from live API
            $newsPortals = $live['news_portals'] ?? [
                'investor-daily' => ['name' => 'investor.id', 'url' => 'https://investor.id', 'is_active' => true],
                'beritasatu' => ['name' => 'beritasatu.com', 'url' => 'https://www.beritasatu.com', 'is_active' => true],
                'jakartaglobe' => ['name' => 'jakartaglobe.id', 'url' => 'https://jakartaglobe.id', 'is_active' => true],
            ];

            // Sync highlights from live API
            $highlights = $live['highlights'] ?? [
                'is_active' => false,
                'items' => [],
            ];

            // Sync speakers if database has them
            if (!empty($live['speakers']) && count($live['speakers']) > 0) {
                $syncedSpeakers = [];
                foreach ($live['speakers'] as $idx => $sp) {
                    if (isset($sp['is_active']) && empty($sp['is_active'])) {
                        continue;
                    }
                    $slug = !empty($sp['slug']) ? $sp['slug'] : url_title($sp['name'], '-', true);
                    $syncedSpeakers[$slug] = [
                        'id' => $sp['id'],
                        'slug' => $slug,
                        'name' => $sp['name'],
                        'role' => $sp['role'],
                        'photo' => 'placeholder.png',
                        'bio' => $sp['bio'] ?? '',
                        'featured' => ($idx < 4),
                    ];
                }
                $speakers = $syncedSpeakers;
            }

            // Sync single-image sponsor & partnership banner from live API
            if (!empty($live['sponsor_partnership'])) {
                $sponsorPartnership = $live['sponsor_partnership'];
            }

            // Sync floating section decorations from live API
            if (!empty($live['section_decorations'])) {
                $sectionDecorations = $live['section_decorations'];
            }

            // Sync custom placements from live API
            if (!empty($live['custom_placements'])) {
                $customPlacements = $live['custom_placements'];
            }

            // Sync event locations from live API
            if (!empty($live['locations'])) {
                $locations = $live['locations'];
                $locations['address'] = $locations['hotel_address'] ?? ($locations['address'] ?? '');
                $locations['gmaps_embed_url'] = $locations['gmaps_iframe'] ?? ($locations['gmaps_embed_url'] ?? '');
                $locations['floor_plan_image'] = $locations['layout_image_url'] ?? ($locations['floor_plan_image'] ?? '');
                if (!empty($locations['floor_plan_image'])) {
                    $locations['floor_plan_image'] = $fixUrl($locations['floor_plan_image']);
                }
            }

            /* === LEGACY TIER-BASED SPONSORS SYNC (COMMENTED OUT) ===
            if (!empty($live['sponsors'])) {
                $sponsors = $live['sponsors'];
            }
            === END LEGACY SPONSORS SYNC === */

            // Sync live schedule if available from current event
            $hasLiveSessions = false;
            if (!empty($live['schedule'])) {
                if (!empty($live['schedule']['days'])) {
                    foreach ($live['schedule']['days'] as $ld) {
                        if (!empty($ld['sessions'])) {
                            $hasLiveSessions = true;
                            break;
                        }
                    }
                } elseif (!empty($live['schedule']['day_1']['sessions']) || !empty($live['schedule']['day_2']['sessions'])) {
                    $hasLiveSessions = true;
                }
            }

            if ($hasLiveSessions) {
                if (!empty($live['schedule']['days'])) {
                    $daysData = $live['schedule']['days'];
                    if (isset($daysData[0])) {
                        $schedule['day_1']['label'] = $daysData[0]['label'] ?? ($schedule['day_1']['label'] ?? 'Day 1');
                        $schedule['day_1']['date'] = $daysData[0]['date'] ?? ($schedule['day_1']['date'] ?? '');
                        if (!empty($daysData[0]['sessions'])) {
                            $schedule['day_1']['items'] = $daysData[0]['sessions'];
                        }
                    }
                    if (isset($daysData[1])) {
                        $schedule['day_2']['label'] = $daysData[1]['label'] ?? ($schedule['day_2']['label'] ?? 'Day 2');
                        $schedule['day_2']['date'] = $daysData[1]['date'] ?? ($schedule['day_2']['date'] ?? '');
                        if (!empty($daysData[1]['sessions'])) {
                            $schedule['day_2']['items'] = $daysData[1]['sessions'];
                        }
                    }
                } elseif (!empty($live['schedule']['day_1']) || !empty($live['schedule']['day_2'])) {
                    if (!empty($live['schedule']['day_1'])) {
                        $schedule['day_1']['label'] = $live['schedule']['day_1']['label'] ?? ($schedule['day_1']['label'] ?? 'Day 1');
                        $schedule['day_1']['date'] = $live['schedule']['day_1']['date'] ?? ($schedule['day_1']['date'] ?? '');
                        if (!empty($live['schedule']['day_1']['sessions'])) {
                            $schedule['day_1']['items'] = $live['schedule']['day_1']['sessions'];
                        }
                    }
                    if (!empty($live['schedule']['day_2'])) {
                        $schedule['day_2']['label'] = $live['schedule']['day_2']['label'] ?? ($schedule['day_2']['label'] ?? 'Day 2');
                        $schedule['day_2']['date'] = $live['schedule']['day_2']['date'] ?? ($schedule['day_2']['date'] ?? '');
                        if (!empty($live['schedule']['day_2']['sessions'])) {
                            $schedule['day_2']['items'] = $live['schedule']['day_2']['sessions'];
                        }
                    }
                }
            } else {
                // Fallback data sementara: Ambil dari API production investordailysummit.com
                $prodScheduleCacheKey = 'cms_temp_schedule_ids_prod';
                $prodSchedule = cache($prodScheduleCacheKey);
                if (empty($prodSchedule)) {
                    try {
                        $client = \Config\Services::curlrequest(['timeout' => 4.0, 'verify' => false]);
                        $resp = $client->get('https://investordailysummit.com/backend/api/v1/event/investor-daily-summit-2026', ['http_errors' => false]);
                        if ($resp->getStatusCode() === 200) {
                            $pJson = json_decode($resp->getBody(), true);
                            if (!empty($pJson['data']['schedule'])) {
                                $prodSchedule = $pJson['data']['schedule'];
                                cache()->save($prodScheduleCacheKey, $prodSchedule, 86400);
                            }
                        }
                    } catch (\Throwable $e) {}
                }

                if (!empty($prodSchedule)) {
                    if (!empty($prodSchedule['days'])) {
                        if (isset($prodSchedule['days'][0])) {
                            $schedule['day_1']['label'] = $prodSchedule['days'][0]['label'] ?? 'Day 1';
                            $schedule['day_1']['date'] = $prodSchedule['days'][0]['date'] ?? '';
                            $schedule['day_1']['items'] = $prodSchedule['days'][0]['sessions'] ?? [];
                        }
                        if (isset($prodSchedule['days'][1])) {
                            $schedule['day_2']['label'] = $prodSchedule['days'][1]['label'] ?? 'Day 2';
                            $schedule['day_2']['date'] = $prodSchedule['days'][1]['date'] ?? '';
                            $schedule['day_2']['items'] = $prodSchedule['days'][1]['sessions'] ?? [];
                        }
                    } elseif (!empty($prodSchedule['day_1']) || !empty($prodSchedule['day_2'])) {
                        if (!empty($prodSchedule['day_1']['sessions'])) {
                            $schedule['day_1']['label'] = $prodSchedule['day_1']['label'] ?? 'Day 1';
                            $schedule['day_1']['date'] = $prodSchedule['day_1']['date'] ?? '';
                            $schedule['day_1']['items'] = $prodSchedule['day_1']['sessions'];
                        }
                        if (!empty($prodSchedule['day_2']['sessions'])) {
                            $schedule['day_2']['label'] = $prodSchedule['day_2']['label'] ?? 'Day 2';
                            $schedule['day_2']['date'] = $prodSchedule['day_2']['date'] ?? '';
                            $schedule['day_2']['items'] = $prodSchedule['day_2']['sessions'];
                        }
                    }
                }
            }

            // Successfully synced

            if (!empty($overview['collage_images'])) {
                foreach ($overview['collage_images'] as &$cImg) {
                    if (!empty($cImg))
                        $cImg = $fixUrl($cImg);
                }
                unset($cImg);
            }

            if (!empty($seo['og_image']))
                $seo['og_image'] = $fixUrl($seo['og_image']);
            if (!empty($seo['og_image_url']))
                $seo['og_image_url'] = $fixUrl($seo['og_image_url']);
            if (!empty($site['logo_a']))
                $site['logo_a'] = $fixUrl($site['logo_a']);
            if (!empty($site['logo_b']))
                $site['logo_b'] = $fixUrl($site['logo_b']);
            if (!empty($site['logo_a_color']))
                $site['logo_a_color'] = $fixUrl($site['logo_a_color']);
            if (!empty($site['logo_a_white']))
                $site['logo_a_white'] = $fixUrl($site['logo_a_white']);
            if (!empty($site['logo_b_color']))
                $site['logo_b_color'] = $fixUrl($site['logo_b_color']);
            if (!empty($site['logo_b_white']))
                $site['logo_b_white'] = $fixUrl($site['logo_b_white']);
            if (!empty($site['logo_overview']))
                $site['logo_overview'] = $fixUrl($site['logo_overview']);
            if (!empty($site['logo_floating']))
                $site['logo_floating'] = $fixUrl($site['logo_floating']);
            if (!empty($site['logo_footer']))
                $site['logo_footer'] = $fixUrl($site['logo_footer']);
            if (!empty($site['logo_footer_white']))
                $site['logo_footer_white'] = $fixUrl($site['logo_footer_white']);

            foreach ($heroCarousel as &$slide) {
                if (!empty($slide['image_url']))
                    $slide['image_url'] = $fixUrl($slide['image_url']);
            }
            unset($slide);

            foreach ($galleryImages as &$gal) {
                if (!empty($gal['image_url']))
                    $gal['image_url'] = $fixUrl($gal['image_url']);
            }
            unset($gal);

            if (!empty($sponsorPartnership['logo_url'])) {
                $sponsorPartnership['logo_url'] = $fixUrl($sponsorPartnership['logo_url']);
            }

            foreach ($speakers as &$spk) {
                if (!empty($spk['photo']))
                    $spk['photo'] = $fixUrl($spk['photo']);
            }
            unset($spk);

            foreach ($sectionDecorations as &$dec) {
                if (!empty($dec['image_url'])) {
                    $dec['image_url'] = $fixUrl($dec['image_url']);
                }
                // Jika dekorasi berada di section location, ganti aset cakram/disc lama menjadi ribbon.png
                if (($dec['section_key'] ?? '') === 'location') {
                    $dec['image_url'] = base_url('assets/img/ribbon.png');
                    $dec['tint_color'] = null; // Tampilkan ribbon warna asli tanpa tint biru/hitam
                }
            }
            unset($dec);

            foreach ($customPlacements as &$cp) {
                if (!empty($cp['image_url'])) {
                    $cp['image_url'] = $fixUrl($cp['image_url']);
                }
                // Jika custom placement berada di section location, ganti aset cakram/disc lama menjadi ribbon.png
                if (($cp['section_key'] ?? '') === 'location') {
                    $cp['image_url'] = base_url('assets/img/ribbon.png');
                    $cp['tint_color'] = null; // Tampilkan ribbon warna asli tanpa tint biru/hitam
                }
            }
            unset($cp);

            if (!empty($schedule)) {
                foreach ($schedule as &$day) {
                    if (empty($day['items']))
                        continue;
                    foreach ($day['items'] as &$item) {
                        if (!empty($item['moderator']['photo'])) {
                            $item['moderator']['photo'] = $fixUrl($item['moderator']['photo']);
                        }
                        if (!empty($item['pengantar'])) {
                            foreach ($item['pengantar'] as &$png) {
                                if (!empty($png['photo']))
                                    $png['photo'] = $fixUrl($png['photo']);
                            }
                            unset($png);
                        }
                        if (!empty($item['speakers'])) {
                            foreach ($item['speakers'] as &$spk) {
                                if (!empty($spk['photo']))
                                    $spk['photo'] = $fixUrl($spk['photo']);
                            }
                            unset($spk);
                        }
                        if (!empty($item['rooms'])) {
                            foreach ($item['rooms'] as &$room) {
                                if (empty($room['sessions']))
                                    continue;
                                foreach ($room['sessions'] as &$sub) {
                                    if (!empty($sub['moderator']['photo'])) {
                                        $sub['moderator']['photo'] = $fixUrl($sub['moderator']['photo']);
                                    }
                                    if (!empty($sub['pengantar'])) {
                                        foreach ($sub['pengantar'] as &$png) {
                                            if (!empty($png['photo']))
                                                $png['photo'] = $fixUrl($png['photo']);
                                        }
                                        unset($png);
                                    }
                                    if (!empty($sub['speakers'])) {
                                        foreach ($sub['speakers'] as &$spk) {
                                            if (!empty($spk['photo']))
                                                $spk['photo'] = $fixUrl($spk['photo']);
                                        }
                                        unset($spk);
                                    }
                                }
                                unset($sub);
                            }
                            unset($room);
                        }
                    }
                    unset($item);
                }
                unset($day);
            }
        }

        $data = [
            'site' => $site,
            'themeProperties' => $themeProperties,
            'heroCarousel' => $heroCarousel,
            'overview' => $overview,
            'economicModel' => $economicModel,
            'livestream' => $livestream ?? [
                'title' => 'Live Stream',
                'youtube_url' => 'https://www.youtube.com/live/mKr5aNganlc',
                'is_active' => true,
            ],
            'livestreams' => $livestreams ?? [],
            'newsPortals' => $newsPortals ?? [
                'investor-daily' => ['name' => 'investor.id', 'url' => 'https://investor.id', 'is_active' => true],
                'beritasatu' => ['name' => 'beritasatu.com', 'url' => 'https://www.beritasatu.com', 'is_active' => true],
                'jakartaglobe' => ['name' => 'jakartaglobe.id', 'url' => 'https://jakartaglobe.id', 'is_active' => true],
            ],
            'highlights' => $highlights ?? [
                'is_active' => false,
                'items' => [],
            ],
            'speakers' => $speakers,
            'schedule' => $schedule,
            'gallery' => $gallery,
            'galleryImages' => $galleryImages,
            'relatedNews' => $relatedNews,
            'relatedNewsInvestor' => $relatedNewsInvestor,
            'relatedNewsBeritaSatu' => $relatedNewsBeritaSatu,
            'relatedNewsJakartaGlobe' => $relatedNewsJakartaGlobe,
            'relatedNewsDatasatu' => $relatedNewsDatasatu,
            'summaryNews' => $summaryNews,
            'sponsors' => $sponsors,
            'sponsorPartnership' => $sponsorPartnership,
            'sectionDecorations' => $sectionDecorations,
            'customPlacements' => $customPlacements,
            'locations' => $locations,
            'seo' => $seo,
        ];

        return view('home', $data);
    }

    /**
     * Fetch related news from external B-Universe article APIs per portal
     * Default limit is 4 items per portal.
     */
    private function fetchNewsFromPortal(string $portal, int $limit = 4): ?array
    {
        $cacheKey = 'ids_news_' . $portal . '_limit' . $limit . '_v8';
        $cached = cache($cacheKey);
        if ($cached !== null) {
            return !empty($cached) ? $cached : null;
        }

        $portalUrls = [
            'investor' => "https://api.investor.id/article/type=latest&num={$limit}&tag=investor-daily-summit-2026&page=1",
            'beritasatu' => "https://api.beritasatu.com/v4/article/type:bytag/tag:investor-daily-summit/start:0/limit:{$limit}",
            'jakartaglobe' => "https://api.jakartaglobe.id/apiweb-v2/search/keyword=investor-daily-summit&num={$limit}&page=0",
            'datasatu' => "http://172.20.70.195:8607/article/type:bytag/tag:investor-daily-summit/start:0/limit:{$limit}",
            'overview' => "http://172.20.70.195:8607/article/type:bytag/tag:investor-daily-summit/start:0/limit:100",
        ];

        $targetUrl = $portalUrls[$portal] ?? null;
        if (!$targetUrl) {
            return null;
        }

        $oldTimeout = ini_get('default_socket_timeout');
        ini_set('default_socket_timeout', 3);

        $context = stream_context_create([
            'http' => [
                'timeout' => 3,
                'user_agent' => 'Mozilla/5.0 (compatible; IDSNewsCrawler/1.0)',
            ]
        ]);

        $response = @file_get_contents($targetUrl, false, $context);
        ini_set('default_socket_timeout', $oldTimeout);

        if (!$response) {
            cache()->save($cacheKey, [], 180);
            return null;
        }

        $res = json_decode($response, true);
        if (empty($res)) {
            cache()->save($cacheKey, [], 180);
            return null;
        }

        // Support array of articles or wrapped object (data / articles / items / result.list / list)
        $articles = [];
        if (isset($res['result']['list']) && is_array($res['result']['list'])) {
            $articles = $res['result']['list'];
        } elseif (isset($res['data']) && is_array($res['data'])) {
            $articles = $res['data'];
        } elseif (isset($res['list']) && is_array($res['list'])) {
            $articles = $res['list'];
        } elseif (isset($res['articles']) && is_array($res['articles'])) {
            $articles = $res['articles'];
        } elseif (isset($res['items']) && is_array($res['items'])) {
            $articles = $res['items'];
        } elseif (is_array($res)) {
            $articles = $res;
        }

        if (empty($articles)) {
            cache()->save($cacheKey, [], 180);
            return null;
        }

        $normalized = [];
        foreach ($articles as $idx => $item) {
            $item = (array) $item;
            $title = $item['title'] ?? $item['headline'] ?? $item['headline_clean'] ?? $item['article_title'] ?? $item['post_title'] ?? '';
            if (empty($title)) {
                continue;
            }

            $id = $item['articleID'] ?? $item['id'] ?? $item['article_id'] ?? ($idx + 1);
            $sefurl = $item['sefurl'] ?? '';

            // Construct Portal-specific redirect URL and image
            if ($portal === 'investor') {
                $url = !empty($sefurl) ? 'https://investor.id/' . ltrim($sefurl, '/') : ($item['url'] ?? 'https://investor.id');
                $image = $item['media'][0]['imglarge'] ?? $item['media'][0]['imgsmall'] ?? ($item['image'] ?? 'image1.webp');
            } elseif ($portal === 'jakartaglobe') {
                $cat = $item['category'][0]['categoryName'] ?? 'news';
                $slug = $item['slug'] ?? ($item['sefurl'] ?? '');
                if (!empty($cat) && !empty($slug)) {
                    $url = 'https://jakartaglobe.id/' . trim($cat, '/') . '/' . trim($slug, '/');
                } elseif (!empty($slug)) {
                    $url = 'https://jakartaglobe.id/' . trim($slug, '/');
                } elseif (!empty($sefurl)) {
                    $url = 'https://jakartaglobe.id/' . ltrim($sefurl, '/');
                } else {
                    $url = $item['url'] ?? 'https://jakartaglobe.id';
                }

                $filename = $item['media'][0]['filename'] ?? '';
                $image = !empty($filename) ? 'https://img2.beritasatu.com/cache/jakartaglobe/960x620-3/' . $filename : ($item['image'] ?? 'image1.webp');
            } elseif ($portal === 'datasatu' || $portal === 'overview') {
                if (!empty($item['canonical'])) {
                    $url = 'https://datasatu.com/' . ltrim($item['canonical'], '/');
                } elseif (!empty($item['categoryName']) && !empty($item['articleID']) && !empty($item['slug'])) {
                    $url = 'https://datasatu.com/' . $item['categoryName'] . '/' . $item['articleID'] . '/' . $item['slug'];
                } elseif (!empty($item['slug'])) {
                    $url = 'https://datasatu.com/' . ltrim($item['slug'], '/');
                } else {
                    $url = $item['url'] ?? 'https://datasatu.com';
                }

                $mediaPath = $item['mediaPath'] ?? ($item['media'][0]['filename'] ?? '');
                $image = !empty($mediaPath) ? 'https://img2.beritasatu.com/cache/datasatu/960x620-3/' . ltrim($mediaPath, '/') : ($item['image'] ?? 'image1.webp');
            } else { // beritasatu
                $url = !empty($sefurl) ? 'https://www.beritasatu.com/' . ltrim($sefurl, '/') : ($item['url'] ?? 'https://www.beritasatu.com');
                $filename = $item['media'][0]['filename'] ?? ($item['media'][0]['url'] ?? '');
                $image = !empty($filename) ? 'https://img2.beritasatu.com/cache/beritasatu/960x620-3/' . $filename : ($item['image'] ?? 'image1.webp');
            }

            $normalized[] = [
                'id' => $id,
                'title' => $title,
                'image' => $image,
                'url' => $url,
            ];
        }

        if (!empty($normalized)) {
            cache()->save($cacheKey, $normalized, 300);
            return $normalized;
        }

        cache()->save($cacheKey, [], 180);
        return null;
    }

    /**
     * Telemetry Proxy for Ticket Clicks
     * Forwards click event from Frontend to BackendCMS REST API
     */
    public function trackTicket()
    {
        $cmsUrls = [
            'http://localhost:8081/api/v1/tickets/click',
            'http://127.0.0.1:8081/api/v1/tickets/click',
            'http://[::1]:8081/api/v1/tickets/click',
        ];

        $client = \Config\Services::curlrequest(['timeout' => 2.0]);
        $success = false;

        foreach ($cmsUrls as $cmsUrl) {
            try {
                $response = $client->post($cmsUrl, [
                    'headers' => [
                        'User-Agent' => (string) $this->request->getUserAgent(),
                        'X-Forwarded-For' => $this->request->getIPAddress(),
                        'Referer' => (string) $this->request->getServer('HTTP_REFERER') ?: base_url(),
                    ],
                    'http_errors' => false,
                ]);

                if ($response->getStatusCode() === 200) {
                    $success = true;
                    break;
                }
            } catch (\Throwable $e) {
                // Try next endpoint
            }
        }

        return $this->response->setJSON([
            'status' => $success ? 'success' : 'forwarded',
            'message' => 'Ticket click telemetry logged',
        ]);
    }

    /**
     * Cache Invalidation Endpoint (triggered by BackendCMS or manually)
     * Route: api/clear-cache
     */
    public function clearCache()
    {
        cache()->delete('cms_live_event_emf-2026');

        // Optional: clear file uploads disk cache if needed
        $cacheDir = WRITEPATH . 'cache/uploads/';
        if (is_dir($cacheDir)) {
            $files = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($cacheDir, \RecursiveDirectoryIterator::SKIP_DOTS),
                \RecursiveIteratorIterator::CHILD_FIRST
            );
            foreach ($files as $fileinfo) {
                if ($fileinfo->isFile()) {
                    @unlink($fileinfo->getRealPath());
                }
            }
        }

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Frontend cache cleared successfully.',
            'timestamp' => date('Y-m-d H:i:s'),
        ]);
    }
}

