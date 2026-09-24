<?php

namespace App\Controllers;

use App\Config\MockData;

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

        // Attempt live synchronization with BackendCMS REST API (cached 120s for optimal performance)
        $cacheKey = 'cms_live_event_active';
        $live = cache($cacheKey);

        if ($live === null) {
            $apiUrls = [
                'http://[::1]:8081/api/v1/event/active',
                'http://127.0.0.1:8081/api/v1/event/active',
                'http://localhost:8081/api/v1/event/active',
            ];

            foreach ($apiUrls as $apiUrl) {
                try {
                    $client = \Config\Services::curlrequest(['timeout' => 2.0]);
                    $response = $client->get($apiUrl, ['http_errors' => false]);
                    if ($response->getStatusCode() === 200) {
                        $json = json_decode($response->getBody(), true);
                        if (!empty($json['data'])) {
                            $live = $json['data'];
                            cache()->save($cacheKey, $live, 120);
                            break;
                        }
                    }
                } catch (\Throwable $e) {
                    // Try next URL fallback
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
                $site['nav_items'] = $live['site']['nav_items'] ?? [];
                $site['socials'] = $live['site']['socials'] ?? [];
            }

            // Sync theme CSS properties
            if (!empty($live['theme_properties'])) {
                $themeProperties = $live['theme_properties'];
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
                'youtube_url' => 'https://www.youtube.com/watch?v=rFZHOHl-L8A',
                'is_active' => true,
            ];

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
                    $slug = !empty($sp['slug']) ? $sp['slug'] : url_title($sp['name'], '-', true);
                    $syncedSpeakers[$slug] = [
                        'id' => $sp['id'],
                        'slug' => $slug,
                        'name' => $sp['name'],
                        'role' => $sp['role'],
                        'photo' => $sp['photo'] ?: 'placeholder.png',
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

            // Sync live schedule if available
            if (!empty($live['schedule'])) {
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
                if (!empty($live['schedule']['days'])) {
                    $daysData = $live['schedule']['days'];
                    if (isset($daysData[0])) {
                        $schedule['day_1']['label'] = $daysData[0]['label'] ?? $schedule['day_1']['label'];
                        $schedule['day_1']['date'] = $daysData[0]['date'] ?? ($schedule['day_1']['date'] ?? '');
                        if (!empty($daysData[0]['sessions'])) {
                            $schedule['day_1']['items'] = $daysData[0]['sessions'];
                        }
                    }
                    if (isset($daysData[1])) {
                        $schedule['day_2']['label'] = $daysData[1]['label'] ?? $schedule['day_2']['label'];
                        $schedule['day_2']['date'] = $daysData[1]['date'] ?? ($schedule['day_2']['date'] ?? '');
                        if (!empty($daysData[1]['sessions'])) {
                            $schedule['day_2']['items'] = $daysData[1]['sessions'];
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
                if (!empty($dec['image_url']))
                    $dec['image_url'] = $fixUrl($dec['image_url']);
            }
            unset($dec);

            foreach ($customPlacements as &$cp) {
                if (!empty($cp['image_url']))
                    $cp['image_url'] = $fixUrl($cp['image_url']);
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
                'title' => 'Investor Daily Summit Live Broadcast',
                'youtube_url' => 'https://www.youtube.com/watch?v=rFZHOHl-L8A',
                'is_active' => true,
            ],
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
        $cacheKey = 'ids_news_' . $portal . '_limit' . $limit . '_v6';
        $cached = cache($cacheKey);
        if ($cached !== null) {
            return !empty($cached) ? $cached : null;
        }

        $portalUrls = [
            'investor' => "https://api.investor.id/article/type=latest&num={$limit}&tag=investor-daily-summit&page=1",
            'beritasatu' => "https://api.beritasatu.com/v4/article/type:bytag/tag:investor-daily-summit/start:0/limit:{$limit}",
            'jakartaglobe' => "https://api.jakartaglobe.id/apiweb-v2/search/keyword=investor-daily-summit&num={$limit}&page=0",
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
}

