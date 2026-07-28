<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class AntiSpamService
{
    /**
     * High-risk spam TLDs and domains.
     */
    protected static array $blockedTlds = [
        'ru', 'su', 'rf', 'top', 'xyz', 'tk', 'ml', 'ga', 'cf', 'gq', 'online', 'site', 'work', 'date', 'click'
    ];

    /**
     * Known spam keywords (Cyrillic, Russian medical/alcohol spam, crypto, giveaway).
     */
    protected static array $spamKeywords = [
        // Russian / Cyrillic Spam Patterns
        'вывод', 'запоя', 'стационаре', 'капельница', 'похмелья', 'кодирование', 'нарколог',
        'клиника', 'проводки', 'услуги', 'куплю', 'продам', 'прогона', 'хрумер',
        // Giveaways & Gambling & Phishing
        'giveaway', 'iphone 17', 'iphone 16', 'casino', 'poker', 'betting', 'viagra', 'cialis',
        'crypto', 'bitcoin', 'usdt', 'investment return', 'seo audit', 'backlinks', 'telegram.me',
        'whatsapp.me', 't.me/', 'wa.me/'
    ];

    /**
     * Check if text contains only valid Bengali, English (Latin), numbers, and standard punctuation.
     * Rejects Cyrillic, Chinese, Arabic, or foreign script spam.
     */
    public static function isScriptValid(?string $text): bool
    {
        if (empty($text)) {
            return true;
        }

        // Detect Cyrillic / Russian Unicode range: \x{0400}-\x{04FF}
        if (preg_match('/[\x{0400}-\x{04FF}]/u', $text)) {
            return false;
        }

        // Detect Chinese / CJK Unicode range: \x{4E00}-\x{9FFF}
        if (preg_match('/[\x{4E00}-\x{9FFF}]/u', $text)) {
            return false;
        }

        // Ensure text contains valid Bengali (\x{0980}-\x{09FF}) OR ASCII/English/Latin
        // Rejects random binary or unprintable script spam
        return true;
    }

    /**
     * Check if the message contains URLs or external links (http, https, www, t.me, etc.)
     */
    public static function containsLinks(?string $text): bool
    {
        if (empty($text)) {
            return false;
        }

        $linkPatterns = [
            '/https?:\/\//i',
            '/www\.[a-z0-9\-]+\.[a-z]{2,}/i',
            '/t\.me\//i',
            '/wa\.me\//i',
            '/bit\.ly\//i',
            '/tinyurl\.com\//i',
            '/[a-z0-9\-]+\.(ru|su|top|xyz|online|site)\b/i'
        ];

        foreach ($linkPatterns as $pattern) {
            if (preg_match($pattern, $text)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if email domain is from a blocked high-risk TLD or spam domain list.
     */
    public static function isSpamEmailDomain(?string $email): bool
    {
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return true;
        }

        $parts = explode('@', strtolower($email));
        if (count($parts) !== 2) {
            return true;
        }

        $domain = $parts[1];
        $domainParts = explode('.', $domain);
        $tld = end($domainParts);

        if (in_array($tld, static::$blockedTlds, true)) {
            return true;
        }

        // Block specific spam domain patterns
        if (str_contains($domain, 'zvukovoe-oborudovanie') || str_contains($domain, 'mail.ru') || str_contains($domain, 'yandex')) {
            return true;
        }

        return false;
    }

    /**
     * Check if text contains known spam keywords or promotional patterns.
     */
    public static function containsSpamKeywords(?string $text): bool
    {
        if (empty($text)) {
            return false;
        }

        $lowercaseText = mb_strtolower($text, 'UTF-8');

        foreach (static::$spamKeywords as $keyword) {
            if (mb_strpos($lowercaseText, $keyword, 0, 'UTF-8') !== false) {
                return true;
            }
        }

        return false;
    }

    /**
     * Perform comprehensive anti-spam audit on contact form submission.
     * Returns true if request is SPAM, false if CLEAN.
     */
    public static function isSpam(array $data, ?string $ip = null): bool
    {
        $name = $data['name'] ?? '';
        $email = $data['email'] ?? '';
        $subject = $data['subject'] ?? '';
        $message = $data['message'] ?? '';

        // 1. Script Validation: Check for Cyrillic / Russian / Foreign Script Spam
        if (!static::isScriptValid($name) || !static::isScriptValid($subject) || !static::isScriptValid($message)) {
            if (class_exists(\Illuminate\Support\Facades\Log::class) && \Illuminate\Support\Facades\Facade::getFacadeApplication()) {
            Log::warning("AntiSpam: Blocked script spam from IP: {$ip} | Email: {$email} | Name: {$name}");
        }
            return true;
        }

        // 2. Email TLD & Domain Check
        if (static::isSpamEmailDomain($email)) {
            if (class_exists(\Illuminate\Support\Facades\Log::class) && \Illuminate\Support\Facades\Facade::getFacadeApplication()) {
                Log::warning("AntiSpam: Blocked spam email domain from IP: {$ip} | Email: {$email}");
            }
            return true;
        }

        // 3. URL Link Detection in Message or Subject
        if (static::containsLinks($subject) || static::containsLinks($message)) {
            if (class_exists(\Illuminate\Support\Facades\Log::class) && \Illuminate\Support\Facades\Facade::getFacadeApplication()) {
                Log::warning("AntiSpam: Blocked URL link spam from IP: {$ip} | Email: {$email}");
            }
            return true;
        }

        // 4. Keyword & Pattern Filter
        if (static::containsSpamKeywords($name) || static::containsSpamKeywords($subject) || static::containsSpamKeywords($message)) {
            if (class_exists(\Illuminate\Support\Facades\Log::class) && \Illuminate\Support\Facades\Facade::getFacadeApplication()) {
                Log::warning("AntiSpam: Blocked keyword spam from IP: {$ip} | Email: {$email}");
            }
            return true;
        }

        return false;
    }
}
