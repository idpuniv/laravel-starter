<?php

declare(strict_types=1);

if (!function_exists('time_ago_short')) {
    /**
     * Formats a timestamp or date into a short, human-readable elapsed time (e.g. "5mn", "2h").
     *
     * Kept as a compact alternative to Carbon::diffForHumans() for contexts
     * where a short unit suffix is preferred over a full sentence.
     *
     * @param int|string|\DateTimeInterface $time
     */
    function time_ago_short(int|string|\DateTimeInterface $time): string
    {
        $timestamp = match (true) {
            $time instanceof \DateTimeInterface => $time->getTimestamp(),
            is_numeric($time) => (int) $time,
            default => strtotime($time),
        };

        // strtotime() returns false on unparsable strings; fail safely rather than
        // letting a bogus timestamp silently produce a nonsensical diff.
        if ($timestamp === false) {
            return '—';
        }

        $diff = time() - $timestamp;

        // Future or same-instant timestamps collapse to the smallest unit.
        if ($diff < 1) {
            return '1s';
        }

        $intervals = [
            'an' => 31_536_000,
            'm'  => 2_592_000,
            'j'  => 86_400,
            'h'  => 3_600,
            'mn' => 60,
            's'  => 1,
        ];

        foreach ($intervals as $unit => $seconds) {
            if ($diff >= $seconds) {
                return ((int) floor($diff / $seconds)) . $unit;
            }
        }

        return '1s';
    }
}