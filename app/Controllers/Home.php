<?php

namespace App\Controllers;

use App\Config\MockData;

class Home extends BaseController
{
    public function index(): string
    {
        $mock = config(MockData::class);

        $site          = $mock->site;
        $economicModel = $mock->economicModel;
        $speakers      = $mock->speakers;
        $schedule      = $mock->schedule;
        $gallery       = $mock->gallery;
        $relatedNews   = $mock->relatedNews;
        $sponsors      = $mock->sponsors;

        $themeProperties    = [];
        $heroCarousel       = [];
        $galleryImages      = [];
        $sponsorPartnership = null;
        $sectionDecorations = [];

        // Attempt live synchronization with BackendCMS REST API
        $apiUrls = [
            'http://127.0.0.1:8081/api/v1/event/active',
            'http://[::1]:8081/api/v1/event/active',
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

                        // Sync site metadata & branding
                        if (!empty($live['site'])) {
                            $site['title']            = $live['site']['name'] ?? $site['title'];
                            $site['theme']            = $live['site']['theme'] ?? $site['theme'];
                            $site['dates']            = $live['site']['dates'] ?? $site['dates'];
                            $site['venue']            = $live['site']['venue'] ?? $site['venue'];
                            $site['ticket_url']       = $live['site']['cta_ticket_url'] ?? $site['ticket_url'];
                            $site['cta_ticket_label'] = $live['site']['cta_ticket_label'] ?? 'Get Tickets';
                            $site['copyright']        = $live['site']['copyright'] ?? 'Investor Daily Summit.';
                            $site['logo_a']           = $live['site']['logo_a'] ?? '';
                            $site['logo_b']           = $live['site']['logo_b'] ?? '';
                            $site['logo_footer']      = $live['site']['logo_footer'] ?? '';
                            $site['nav_items']        = $live['site']['nav_items'] ?? [];
                            $site['socials']          = $live['site']['socials'] ?? [];
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

                        // Sync economic model
                        if (!empty($live['economicModel'])) {
                            if (!empty($live['economicModel']['badge'])) {
                                $economicModel['badge'] = $live['economicModel']['badge'];
                            }
                            if (!empty($live['economicModel']['content'])) {
                                $economicModel['intro'] = $live['economicModel']['title'] ?? $economicModel['intro'];
                                $paras = array_filter(explode("\n\n", str_replace("\r\n", "\n", $live['economicModel']['content'])));
                                if (!empty($paras)) {
                                    $economicModel['paragraphs'] = array_values($paras);
                                }
                            }
                        }

                        // Sync speakers if database has them
                        if (!empty($live['speakers']) && count($live['speakers']) > 0) {
                            $syncedSpeakers = [];
                            foreach ($live['speakers'] as $idx => $sp) {
                                $slug = url_title($sp['name'], '-', true);
                                $syncedSpeakers[$slug] = [
                                    'id'       => $sp['id'],
                                    'slug'     => $slug,
                                    'name'     => $sp['name'],
                                    'role'     => $sp['role'],
                                    'photo'    => $sp['photo'] ?: 'wowo.png',
                                    'bio'      => $sp['bio'] ?? '',
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

                        /* === LEGACY TIER-BASED SPONSORS SYNC (COMMENTED OUT) ===
                        if (!empty($live['sponsors'])) {
                            $sponsors = $live['sponsors'];
                        }
                        === END LEGACY SPONSORS SYNC === */

                        // Sync live schedule if available
                        if (!empty($live['schedule']['days'])) {
                            // Map days into day_1 and day_2 if needed
                            $daysData = $live['schedule']['days'];
                            if (isset($daysData[0])) {
                                $schedule['day_1']['label'] = $daysData[0]['label'] ?? $schedule['day_1']['label'];
                                if (!empty($daysData[0]['sessions'])) {
                                    $schedule['day_1']['items'] = $daysData[0]['sessions'];
                                }
                            }
                            if (isset($daysData[1])) {
                                $schedule['day_2']['label'] = $daysData[1]['label'] ?? $schedule['day_2']['label'];
                                if (!empty($daysData[1]['sessions'])) {
                                    $schedule['day_2']['items'] = $daysData[1]['sessions'];
                                }
                            }
                        }

                        // Successfully synced, break loop
                        break;
                    }
                }
            } catch (\Throwable $e) {
                // Try next URL fallback
            }
        }

        $data = [
            'site'               => $site,
            'themeProperties'    => $themeProperties,
            'heroCarousel'       => $heroCarousel,
            'economicModel'      => $economicModel,
            'speakers'           => $speakers,
            'schedule'           => $schedule,
            'gallery'            => $gallery,
            'galleryImages'      => $galleryImages,
            'relatedNews'        => $relatedNews,
            'sponsors'           => $sponsors,
            'sponsorPartnership' => $sponsorPartnership,
            'sectionDecorations' => $sectionDecorations,
        ];

        return view('home', $data);
    }
}
