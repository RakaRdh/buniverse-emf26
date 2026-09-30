<?php

/**
 * The goal of this file is to allow developers a location
 * where they can overwrite core procedural functions and
 * replace them with their own. This file is loaded during
 * the bootstrap process and is called during the framework's
 * execution.
 *
 * This can be looked at as a `master helper` file that is
 * loaded early on, and may also contain additional functions
 * that you'd like to use throughout your entire application
 *
 * @see: https://codeigniter.com/user_guide/extending/common.html
 */if (!function_exists('format_schedule_time')) {
    /**
     * Format a schedule time string or range into standard 12-hour AM/PM WIB format.
     * Examples:
     *   "13:00 - 14:14 WIB" -> "01:00 PM - 02:14 PM WIB"
     *   "08:30 AM - 09:30 AM" -> "08:30 AM - 09:30 AM WIB"
     *   "13:30 PM - 14:00 PM" -> "01:30 PM - 02:00 PM WIB"
     *
     * @param string|null $timeStr
     * @param string|null $startTime
     * @param string|null $endTime
     * @return string
     */
    function format_schedule_time(?string $timeStr, ?string $startTime = null, ?string $endTime = null): string
    {
        $formatSingle = function (?string $raw): ?string {
            if (empty($raw)) {
                return null;
            }
            $raw = trim($raw);
            // Handle malformed formats like "13:30 PM" by stripping AM/PM first
            $cleaned = preg_replace('/(\d{1,2}:\d{2})\s*(?:AM|PM)/i', '$1', $raw);
            $ts = strtotime($cleaned);
            if ($ts === false) {
                $ts = strtotime($raw);
            }
            return $ts !== false ? date('h:i A', $ts) : $raw;
        };

        if (!empty($startTime) && !empty($endTime)) {
            $st = $formatSingle($startTime);
            $et = $formatSingle($endTime);
            if ($st && $et) {
                return "{$st} - {$et} WIB";
            }
        }

        if (empty($timeStr)) {
            return '';
        }

        $cleanStr = trim(preg_replace('/\bWIB\b/i', '', $timeStr));

        if (str_contains($cleanStr, '-')) {
            $parts = explode('-', $cleanStr, 2);
            $p1 = $formatSingle(trim($parts[0]));
            $p2 = $formatSingle(trim($parts[1]));
            if ($p1 && $p2) {
                return "{$p1} - {$p2} WIB";
            }
        }

        $single = $formatSingle($cleanStr);
        return $single ? "{$single} WIB" : $timeStr;
    }
}
