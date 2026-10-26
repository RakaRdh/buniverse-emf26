<?php

namespace App\Helpers;

class PhoneHelper
{
    /**
     * Normalisasi nomor telepon ke format lokal Indonesia (08...) atau internasional.
     * Mengembalikan string nomor yang sudah dibersihkan atau false jika invalid.
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

        // Hapus karakter non-digit selain '+' di awal
        $hasPlus = str_starts_with($trimmed, '+');
        $digits = preg_replace('/[^0-9]/', '', $trimmed);

        if (empty($digits)) {
            return false;
        }

        // Jika diawali 628 -> konversi ke 08 untuk format lokal Indonesia
        if (str_starts_with($digits, '628')) {
            $digits = '08' . substr($digits, 3);
        } elseif (str_starts_with($digits, '8') && strlen($digits) >= 8 && strlen($digits) <= 12) {
            $digits = '08' . substr($digits, 1);
        }

        return $digits;
    }

    /**
     * Validasi nomor telepon khusus Indonesia (awalan 08, 9-13 digit, dan anti-spam).
     */
    public static function validate(string $phone): array
    {
        $normalized = self::normalize($phone);
        if ($normalized === false) {
            return [
                'valid'   => false,
                'message' => 'Nomor telepon tidak boleh kosong dan hanya boleh berupa angka.',
            ];
        }

        // Wajib berawalan 08
        if (!str_starts_with($normalized, '08')) {
            return [
                'valid'   => false,
                'message' => 'Nomor telepon harus nomor Indonesia yang diawali 08 (contoh: 081234567890).',
            ];
        }

        $len = strlen($normalized);
        if ($len < 9 || $len > 13) {
            return [
                'valid'   => false,
                'message' => 'Nomor telepon harus berukuran antara 9 hingga 13 digit (saat ini ' . $len . ' digit).',
            ];
        }

        // Anti-spam: 6 digit berulang berturut-turut (misal: 08111111111)
        if (preg_match('/(\d)\1{5,}/', $normalized)) {
            return [
                'valid'   => false,
                'message' => 'Nomor telepon tidak valid (terdeteksi angka berulang / spam).',
            ];
        }

        // Anti-spam: deret sekuensial
        $consecutiveSeqs = [
            '12345678', '23456789', '34567890',
            '87654321', '98765432', '09876543'
        ];
        foreach ($consecutiveSeqs as $seq) {
            if (str_contains($normalized, $seq)) {
                return [
                    'valid'   => false,
                    'message' => 'Nomor telepon tidak valid (terdeteksi pola urutan angka).',
                ];
            }
        }

        return [
            'valid'      => true,
            'message'    => 'Nomor telepon valid.',
            'normalized' => $normalized,
        ];
    }
}
