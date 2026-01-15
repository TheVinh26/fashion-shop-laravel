<?php

namespace App\Services;

class ProductCodeService
{
    public function generate(string $name, int $number): string
    {
        // Separate each word
        $words = preg_split('/\s+/', trim($name));

        $prefix = '';

        foreach ($words as $word) {
            // Extract the first letter of each word (Unicode supported)
            $char = strtoupper(mb_substr($word, 0, 1));

            // Only accepts letters A-Z
            if (preg_match('/[A-Z]/', $char)) {
                $prefix .= $char;
            }
        }

        // If the name is invalid
        if ($prefix === '') {
            $prefix = 'P';
        }

        // 6 digits are enough for > 1 million records
        return $prefix . str_pad($number, 6, '0', STR_PAD_LEFT);
    }
}
