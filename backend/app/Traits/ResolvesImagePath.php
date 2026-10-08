<?php

namespace App\Traits;

trait ResolvesImagePath
{
    /**
     * Resolve image path using fallbacks.
     *
     * @param string|null $filename
     * @param string $directory
     * @return string|null
     */
    protected function resolveImagePath(?string $filename, string $directory): ?string
    {
        if (!$filename) return null;
        if (str_starts_with($filename, 'http://') || str_starts_with($filename, 'https://')) return $filename;
        if (str_starts_with($filename, 'storage/')) return asset($filename);
        if (str_starts_with($filename, 'collections/')) return asset('storage/' . $filename);
        if (str_starts_with($filename, 'uploads/')) return asset($filename);
        if (str_contains($filename, '/')) return asset($filename);
        return asset('storage/' . $directory . '/' . $filename);
    }
}
