<?php

namespace App\Helper;

class DateHelper
{
    public static function format(string $date, string $format = 'd.m.Y'): string
    {
        try {
            $dt = new \DateTime($date);
            return $dt->format($format);
        } catch (\Exception $e) {
            return '';
        }
    }
    
    public static function formatWithTime(string $date): string
    {
        return self::format($date, 'd.m.Y H:i');
    }
    
    public static function relative(string $date): string
    {
        try {
            $dt = new \DateTime($date);
            $now = new \DateTime();
            $diff = $now->diff($dt);
            
            if ($diff->y > 0) {
                return $diff->y . ' ' . self::pluralize($diff->y, 'rok', 'lata', 'lat') . ' temu';
            }
            if ($diff->m > 0) {
                return $diff->m . ' ' . self::pluralize($diff->m, 'miesiąc', 'miesiące', 'miesięcy') . ' temu';
            }
            if ($diff->d > 7) {
                $weeks = floor($diff->d / 7);
                return $weeks . ' ' . self::pluralize($weeks, 'tydzień', 'tygodnie', 'tygodni') . ' temu';
            }
            if ($diff->d > 0) {
                return $diff->d . ' ' . self::pluralize($diff->d, 'dzień', 'dni', 'dni') . ' temu';
            }
            if ($diff->h > 0) {
                return $diff->h . ' ' . self::pluralize($diff->h, 'godzinę', 'godziny', 'godzin') . ' temu';
            }
            if ($diff->i > 0) {
                return $diff->i . ' ' . self::pluralize($diff->i, 'minutę', 'minuty', 'minut') . ' temu';
            }
            
            return 'przed chwilą';
        } catch (\Exception $e) {
            return '';
        }
    }
    
    private static function pluralize(int $n, string $one, string $few, string $many): string
    {
        if ($n === 1) {
            return $one;
        }
        if ($n % 10 >= 2 && $n % 10 <= 4 && ($n % 100 < 12 || $n % 100 > 14)) {
            return $few;
        }
        return $many;
    }
}
