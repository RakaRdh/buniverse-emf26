<?php

namespace App\Helpers;

class PhoneHelper
{
    /**
     * Supported countries for WhatsApp / phone validation.
     */
    public const SUPPORTED_COUNTRIES = [
        'ID' => ['name' => 'Indonesia', 'dial_code' => '62', 'flag' => '🇮🇩', 'min_digits' => 9, 'max_digits' => 13],
        'MY' => ['name' => 'Malaysia', 'dial_code' => '60', 'flag' => '🇲🇾', 'min_digits' => 9, 'max_digits' => 10],
        'SG' => ['name' => 'Singapore', 'dial_code' => '65', 'flag' => '🇸🇬', 'min_digits' => 8, 'max_digits' => 8],
        'TH' => ['name' => 'Thailand', 'dial_code' => '66', 'flag' => '🇹🇭', 'min_digits' => 9, 'max_digits' => 9],
        'PH' => ['name' => 'Philippines', 'dial_code' => '63', 'flag' => '🇵🇭', 'min_digits' => 10, 'max_digits' => 10],
        'IN' => ['name' => 'India', 'dial_code' => '91', 'flag' => '🇮🇳', 'min_digits' => 10, 'max_digits' => 10],
        'JP' => ['name' => 'Japan', 'dial_code' => '81', 'flag' => '🇯🇵', 'min_digits' => 10, 'max_digits' => 11],
        'KR' => ['name' => 'South Korea', 'dial_code' => '82', 'flag' => '🇰🇷', 'min_digits' => 9, 'max_digits' => 11],
        'US' => ['name' => 'USA / Canada', 'dial_code' => '1', 'flag' => '🇺🇸', 'min_digits' => 10, 'max_digits' => 10],
        'GB' => ['name' => 'United Kingdom', 'dial_code' => '44', 'flag' => '🇬🇧', 'min_digits' => 10, 'max_digits' => 11],
        'AU' => ['name' => 'Australia', 'dial_code' => '61', 'flag' => '🇦🇺', 'min_digits' => 9, 'max_digits' => 9],
        'AE' => ['name' => 'UAE', 'dial_code' => '971', 'flag' => '🇦🇪', 'min_digits' => 9, 'max_digits' => 9],
        'SA' => ['name' => 'Saudi Arabia', 'dial_code' => '966', 'flag' => '🇸🇦', 'min_digits' => 9, 'max_digits' => 9],
    ];

    /**
     * Normalize a phone number to pure digits E.164 without '+'.
     */
    public static function normalize(?string $raw): string|false
    {
        if ($raw === null) {
            return false;
        }

        $trimmed = trim($raw);
        if ($trimmed === '') {
            return false;
        }

        $hasPlus = str_starts_with($trimmed, '+');
        $digits = preg_replace('/[^0-9]/', '', $trimmed);

        if (empty($digits)) {
            return false;
        }

        if ($hasPlus) {
            foreach (self::SUPPORTED_COUNTRIES as $country) {
                $dial = $country['dial_code'];
                if (str_starts_with($digits, $dial)) {
                    $national = substr($digits, strlen($dial));
                    if (str_starts_with($national, '0')) {
                        $national = ltrim($national, '0');
                    }
                    return $dial . $national;
                }
            }
            return $digits;
        }

        if (str_starts_with($digits, '0')) {
            return '62' . substr($digits, 1);
        }

        if (str_starts_with($digits, '62')) {
            return $digits;
        }

        foreach (self::SUPPORTED_COUNTRIES as $country) {
            $dial = $country['dial_code'];
            if ($dial !== '62' && str_starts_with($digits, $dial)) {
                return $digits;
            }
        }

        if (str_starts_with($digits, '8') && strlen($digits) >= 8 && strlen($digits) <= 13) {
            return '62' . $digits;
        }

        if (strlen($digits) >= 8 && strlen($digits) <= 15) {
            return $digits;
        }

        return false;
    }

    /**
     * Validate normalized phone number.
     */
    public static function validate(string $normalized): array
    {
        $len = strlen($normalized);
        if ($len < 8 || $len > 15) {
            return [
                'valid'   => false,
                'message' => 'Panjang nomor telepon harus antara 8 hingga 15 digit.'
            ];
        }

        if (preg_match('/(\d)\1{6,}/', $normalized)) {
            return [
                'valid'   => false,
                'message' => 'Nomor telepon tidak valid (terdeteksi digit berulang).'
            ];
        }

        $consecutiveSeqs = [
            '12345678', '23456789', '34567890',
            '87654321', '98765432', '09876543'
        ];
        foreach ($consecutiveSeqs as $seq) {
            if (str_contains($normalized, $seq)) {
                return [
                    'valid'   => false,
                    'message' => 'Nomor telepon tidak valid (pola nomor sekuensial).'
                ];
            }
        }

        $matched = null;
        foreach (self::SUPPORTED_COUNTRIES as $code => $country) {
            if (str_starts_with($normalized, $country['dial_code'])) {
                if ($matched === null || strlen($country['dial_code']) > strlen($matched['dial_code'])) {
                    $matched = $country;
                }
            }
        }

        if ($matched !== null) {
            $national = substr($normalized, strlen($matched['dial_code']));
            $nationalLen = strlen($national);

            if ($nationalLen < $matched['min_digits']) {
                return [
                    'valid'   => false,
                    'message' => "Nomor telepon {$matched['name']} terlalu pendek (minimal {$matched['min_digits']} digit setelah kode negara)."
                ];
            }
            if ($nationalLen > $matched['max_digits']) {
                return [
                    'valid'   => false,
                    'message' => "Nomor telepon {$matched['name']} terlalu panjang (maksimal {$matched['max_digits']} digit setelah kode negara)."
                ];
            }
        }

        return ['valid' => true, 'message' => 'Nomor telepon valid.', 'normalized' => $normalized];
    }

    /**
     * Generate wa.me link from any phone format.
     */
    public static function waUrl(?string $phone): string
    {
        if (empty($phone)) {
            return '';
        }

        $norm = self::normalize($phone);
        if (!$norm) {
            $digits = preg_replace('/[^0-9]/', '', $phone);
            return $digits ? "https://wa.me/{$digits}" : '';
        }

        return "https://wa.me/{$norm}";
    }

    /**
     * Standardize and format phone number for human display (e.g. "+62 812-1267-8789").
     */
    public static function formatDisplay(?string $phone): string
    {
        if (empty($phone)) {
            return '-';
        }

        $normalized = self::normalize($phone);
        if (!$normalized) {
            return $phone;
        }

        $matched = null;
        foreach (self::SUPPORTED_COUNTRIES as $country) {
            if (str_starts_with($normalized, $country['dial_code'])) {
                if ($matched === null || strlen($country['dial_code']) > strlen($matched['dial_code'])) {
                    $matched = $country;
                }
            }
        }

        if ($matched !== null) {
            $dial = $matched['dial_code'];
            $national = substr($normalized, strlen($dial));
            $pattern = ($dial === '62') ? [3, 4, 4] : [3, 3, 4];
            $chunks = [];
            $cursor = 0;
            foreach ($pattern as $len) {
                if ($cursor >= strlen($national)) break;
                $chunks[] = substr($national, $cursor, $len);
                $cursor += $len;
            }
            if ($cursor < strlen($national)) {
                $chunks[] = substr($national, $cursor);
            }

            return "+{$dial} " . implode('-', $chunks);
        }

        return "+{$normalized}";
    }
}
