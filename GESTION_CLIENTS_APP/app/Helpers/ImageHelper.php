<?php

namespace App\Helpers;

class ImageHelper
{
    /**
     * Retourne l'URL d'une image ou une image placeholder
     * 
     * @param string|null $imagePath
     * @param string $placeholder
     * @return string
     */
    public static function url($imagePath = null, $placeholder = 'https://via.placeholder.com/200?text=Image')
    {
        if ($imagePath && \Storage::disk('public')->exists($imagePath)) {
            return asset('storage/' . $imagePath);
        }
        return $placeholder;
    }

    /**
     * Retourne une balise img HTML
     * 
     * @param string|null $imagePath
     * @param string $alt
     * @param string $class
     * @param string $placeholder
     * @return string
     */
    public static function img($imagePath = null, $alt = 'Image', $class = '', $placeholder = 'https://via.placeholder.com/200?text=Image')
    {
        $url = self::url($imagePath, $placeholder);
        return sprintf('<img src="%s" alt="%s" class="%s">', htmlspecialchars($url), htmlspecialchars($alt), htmlspecialchars($class));
    }
}
