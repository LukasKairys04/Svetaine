<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class AdminImage
{
    public static function store(UploadedFile $file, string $folder, int $width, int $height): string
    {
        $directory = public_path('uploads/' . trim($folder, '/'));
        if (! is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        if (! function_exists('imagecreatetruecolor') || ! function_exists('imagewebp')) {
            $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
            $file->move($directory, $filename);
            return '/uploads/' . trim($folder, '/') . '/' . $filename;
        }

        $source = self::createImage($file->getPathname(), (string) $file->getMimeType());
        if (!$source) {
            $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
            $file->move($directory, $filename);
            return '/uploads/' . trim($folder, '/') . '/' . $filename;
        }

        $target = imagecreatetruecolor($width, $height);

        imagealphablending($target, true);
        imagesavealpha($target, true);

        $sourceWidth = imagesx($source);
        $sourceHeight = imagesy($source);
        $sourceRatio = $sourceWidth / $sourceHeight;
        $targetRatio = $width / $height;

        if ($sourceRatio > $targetRatio) {
            $cropHeight = $sourceHeight;
            $cropWidth = (int) round($sourceHeight * $targetRatio);
            $srcX = (int) round(($sourceWidth - $cropWidth) / 2);
            $srcY = 0;
        } else {
            $cropWidth = $sourceWidth;
            $cropHeight = (int) round($sourceWidth / $targetRatio);
            $srcX = 0;
            $srcY = (int) round(($sourceHeight - $cropHeight) / 2);
        }

        imagecopyresampled($target, $source, 0, 0, $srcX, $srcY, $width, $height, $cropWidth, $cropHeight);

        $filename = Str::uuid() . '.webp';
        $path = $directory . DIRECTORY_SEPARATOR . $filename;

        if (! imagewebp($target, $path, 82)) {
            imagedestroy($source);
            imagedestroy($target);

            $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
            $file->move($directory, $filename);
            return '/uploads/' . trim($folder, '/') . '/' . $filename;
        }

        imagedestroy($source);
        imagedestroy($target);

        return '/uploads/' . trim($folder, '/') . '/' . $filename;
    }

    protected static function createImage(string $path, string $mime)
    {
        return match ($mime) {
            'image/jpeg' => imagecreatefromjpeg($path),
            'image/png' => imagecreatefrompng($path),
            'image/gif' => imagecreatefromgif($path),
            'image/webp' => imagecreatefromwebp($path),
            default => imagecreatefromstring(file_get_contents($path)),
        };
    }
}
