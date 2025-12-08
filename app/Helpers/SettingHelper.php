<?php

namespace App\Helpers;

class SettingHelper
{
    private static $settings = null;

    /**
     * Get all settings
     */
    public static function all()
    {
        if (self::$settings === null) {
            self::$settings = self::loadSettings();
        }
        return self::$settings;
    }

    /**
     * Get specific setting
     */
    public static function get($key, $default = null)
    {
        $settings = self::all();
        return $settings[$key] ?? $default;
    }

    /**
     * Get logo URL
     */
    public static function logo()
    {
        $logo = self::get('site_logo');
        if ($logo) {
            return asset('storage/' . $logo);
        }
        return asset('images/logo-katingan.png');
    }

    /**
     * Get favicon URL
     */
    public static function favicon()
    {
        $favicon = self::get('site_favicon');
        if ($favicon) {
            return asset('storage/' . $favicon);
        }
        return asset('images/favicon.ico');
    }

    /**
     * Get site name
     */
    public static function siteName()
    {
        return self::get('site_name', 'Sekretariat Daerah Kabupaten Katingan');
    }

    /**
     * Get site tagline
     */
    public static function tagline()
    {
        return self::get('site_tagline', 'Melayani dengan Sepenuh Hati');
    }

    /**
     * Get site description
     */
    public static function description()
    {
        return self::get('site_description', 'Website resmi Sekretariat Daerah Kabupaten Katingan');
    }

    /**
     * Get contact email
     */
    public static function email()
    {
        return self::get('site_email', 'setda@katingankab.go.id');
    }

    /**
     * Get contact phone
     */
    public static function phone()
    {
        return self::get('site_phone', '(0537) 31234');
    }

    /**
     * Get address
     */
    public static function address()
    {
        return self::get('site_address', 'Jl. Cilik Riwut, Kasongan, Katingan, Kalimantan Tengah');
    }

    /**
     * Get visi
     */
    public static function visi()
    {
        return self::get('visi', '"Terwujudnya Kabupaten Katingan yang Maju, Sejahtera, Berkeadilan dan Berakhlak Mulia"');
    }

    /**
     * Get misi (returns array)
     */
    public static function misi()
    {
        $misi = self::get('misi', []);
        if (is_string($misi)) {
            // If stored as JSON string, decode it
            $decoded = json_decode($misi, true);
            return $decoded ?: [];
        }
        return is_array($misi) ? $misi : [];
    }

    /**
     * Get social media links
     */
    public static function social($platform = null)
    {
        $socials = [
            'facebook' => self::get('social_facebook'),
            'instagram' => self::get('social_instagram'),
            'twitter' => self::get('social_twitter'),
            'youtube' => self::get('social_youtube'),
        ];

        if ($platform) {
            return $socials[$platform] ?? null;
        }

        return $socials;
    }

    /**
     * Get maps embed code
     */
    public static function maps()
    {
        return self::get('maps_embed');
    }

    /**
     * Load settings from JSON file
     */
    private static function loadSettings()
    {
        $path = storage_path('app/settings.json');

        if (file_exists($path)) {
            return json_decode(file_get_contents($path), true) ?? self::defaultSettings();
        }

        return self::defaultSettings();
    }

    /**
     * Default settings
     */
    private static function defaultSettings()
    {
        return [
            'site_name' => 'Sekretariat Daerah Kabupaten Katingan',
            'site_tagline' => 'Melayani dengan Sepenuh Hati',
            'site_description' => 'Website resmi Sekretariat Daerah Kabupaten Katingan, Provinsi Kalimantan Tengah',
            'site_email' => 'setda@katingankab.go.id',
            'site_phone' => '(0537) 31234',
            'site_address' => 'Jl. Cilik Riwut, Kasongan, Katingan, Kalimantan Tengah',
            'site_logo' => '',
            'site_favicon' => '',
            'social_facebook' => '',
            'social_instagram' => '',
            'social_twitter' => '',
            'social_youtube' => '',
            'maps_embed' => '',
            'visi' => '"Terwujudnya Kabupaten Katingan yang Maju, Sejahtera, Berkeadilan dan Berakhlak Mulia"',
            'misi' => [
                'Mewujudkan suasana kehidupan yang rukun, aman, damai dan sejahtera',
                'Mewujudkan kehidupan masyarakat yang religius dan harmonis',
                'Mewujudkan kualitas sumber daya manusia yang handal dan berdaya saing',
                'Mewujudkan tingkat kesehatan masyarakat yang baik dan memenuhi standar',
                'Mewujudkan pelayanan publik yang memuaskan dan membahagiakan',
                'Mewujudkan infrastruktur yang baik dan mantap',
                'Mewujudkan kenyamanan dalam berusaha dan berinvestasi',
            ],
        ];
    }

    /**
     * Clear cached settings
     */
    public static function clearCache()
    {
        self::$settings = null;
    }
}
