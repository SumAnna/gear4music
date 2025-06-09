<?php

namespace App\Helper;

use Stichoza\GoogleTranslate\GoogleTranslate;
use Stichoza\GoogleTranslate\Exceptions\LargeTextException;
use Stichoza\GoogleTranslate\Exceptions\RateLimitException;
use Stichoza\GoogleTranslate\Exceptions\TranslationRequestException;

class TranslationHelper
{
    /**
     * Translates a given string using Google Translate.
     *
     * @param string $text The input string to translate.
     * @param string $to   Target language (ISO 639-1 code, e.g., "fr", "de").
     *
     * @return string The translated string.
     *
     * @throws LargeTextException
     * @throws RateLimitException
     * @throws TranslationRequestException
     */
    public static function translate(string $text, string $to): string
    {
        if ($to === 'en') {
            return $text;
        }

        $tr = new GoogleTranslate($to);
        return $tr->translate($text);
    }

    /**
     * Translates a string with error suppression fallback.
     *
     * If translation fails due to API issues or other exceptions,
     * the original text is returned unmodified.
     *
     * @param string $text The input string to translate.
     * @param string $to   Target language (ISO 639-1 code).
     *
     * @return string The translated string or original if translation fails.
     */
    public static function safeTranslate(string $text, string $to): string
    {
        try {
            return self::translate($text, $to);
        } catch (\Throwable $e) {
            return $text;
        }
    }
}
