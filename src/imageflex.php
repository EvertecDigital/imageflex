<?php

/**
 * Class ImageFlex
 * 
 * A utility class for managing and manipulating images using GD library.
 */
class ImageFlex
{
    private static $cacheDir = 'cache'; // Directory where cached images are stored
    private static $outputFormat = 'auto'; // Default output format for resized images (options: auto, png, jpg, webp, gif)
    private static $quality = 90; // Default quality for JPEG and WEBP output
    private static $compression = 6; // Default compression level for PNG output
    private static $errors = []; // Array to store error messages
    private static $watermarkPath = null; // Path to watermark image
    private static $watermarkOpacity = 50; // Watermark opacity (0-100)
    private static $watermarkPosition = 'bottom right'; // Default watermark position

    /**
     * Set the output format for resized images.
     * 
     * @param string $format The desired output format ('jpg', 'png', 'webp', 'gif')
     */
    public static function setOutputFormat($format)
    {
        if (in_array($format, ['jpg', 'png', 'webp', 'gif'])) {
            self::$outputFormat = $format;
        }
    }

    /**
     * Set the quality level for JPEG and WEBP output.
     * 
     * @param int $quality The quality level (0-100)
     */
    public static function setQuality($quality)
    {
        self::$quality = (int) $quality;
    }

    /**
     * Set the compression level for PNG output.
     * 
     * @param int $compression The compression level (0-9)
     */
    public static function setCompression($compression)
    {
        self::$compression = (int) $compression;
    }

    /**
     * Set the watermark image path.
     * 
     * @param string $path The path to the watermark image
     */
    public static function setWatermark($path)
    {
        if (file_exists($path)) {
            self::$watermarkPath = $path;
        } else {
            self::$errors[] = 'Watermark image file does not exist: ' . $path;
        }
    }

    /**
     * Set the watermark opacity.
     * 
     * @param int $opacity The opacity level (0-100)
     */
    public static function setWatermarkOpacity($opacity)
    {
        self::$watermarkOpacity = min(max($opacity, 0), 100);
    }

    /**
     * Set the watermark position.
     * 
     * @param string $position The desired watermark position
     */
    public static function setWatermarkPosition($position)
    {
        $validPositions = [
            'top left', 'top', 'top right',
            'middle left', 'middle center', 'middle right',
            'bottom left', 'bottom center', 'bottom right'
        ];

        if (in_array($position, $validPositions)) {
            self::$watermarkPosition = $position;
        } else {
            self::$errors[] = 'Invalid watermark position: ' . $position;
        }
    }

    /**
     * Ensure that the cache directory exists; if not, create it.
     */
    private static function ensureCacheDirExists()
    {
        if (!is_dir(self::$cacheDir)) {
            mkdir(self::$cacheDir, 0777, true);
        }
    }

    /**
     * Check if GD extension is loaded, which is necessary for image manipulation.
     * 
     * @return bool Returns true if GD extension is loaded, false otherwise
     */
    private static function checkRequirements()
    {
        if (!extension_loaded('gd')) {
            self::$errors[] = 'GD extension is not enabled.';
            return false;
        }
        return true;
    }

    /**
     * Sanitize a filename by removing characters that are not alphanumeric, underscore, or hyphen.
     * 
     * @param string $filename The original filename to sanitize
     * @return string The sanitized filename
     */
    private static function sanitizeFileName($filename)
    {
        return preg_replace('/[^a-zA-Z0-9_-]/', '', $filename);
    }

    /**
     * Preserve transparency for PNG and GIF images.
     * 
     * @param resource $resizedImage The resized image resource
     * @param resource $image The original image resource
     * @param string $extension The file extension of the original image
     */
    private static function preserveTransparency($resizedImage, $image, $extension)
    {
        if ($extension == 'png' || $extension == 'gif') {
            imagecolortransparent($resizedImage, imagecolorallocatealpha($resizedImage, 0, 0, 0, 127));
            imagealphablending($resizedImage, false);
            imagesavealpha($resizedImage, true);
        }
    }

    /**
     * Apply watermark to an image resource.
     * 
     * @param resource $image The image resource to apply the watermark to
     * @param string $extension The file extension of the original image
     */
    private static function applyWatermark($image, $extension)
    {
        if (self::$watermarkPath) {
            $watermark = null;
            $watermarkExtension = strtolower(pathinfo(self::$watermarkPath, PATHINFO_EXTENSION));

            switch ($watermarkExtension) {
                case 'jpeg':
                case 'jpg':
                    $watermark = imagecreatefromjpeg(self::$watermarkPath);
                    break;
                case 'png':
                    $watermark = imagecreatefrompng(self::$watermarkPath);
                    break;
                case 'gif':
                    $watermark = imagecreatefromgif(self::$watermarkPath);
                    break;
                case 'webp':
                    $watermark = imagecreatefromwebp(self::$watermarkPath);
                    break;
                default:
                    self::$errors[] = 'Unsupported watermark image format: ' . $watermarkExtension;
                    return;
            }

            if ($watermark) {
                $wmWidth = imagesx($watermark);
                $wmHeight = imagesy($watermark);
                $imgWidth = imagesx($image);
                $imgHeight = imagesy($image);

                switch (self::$watermarkPosition) {
                    case 'top left':
                        $destX = 0;
                        $destY = 0;
                        break;
                    case 'top':
                        $destX = ($imgWidth - $wmWidth) / 2;
                        $destY = 0;
                        break;
                    case 'top right':
                        $destX = $imgWidth - $wmWidth;
                        $destY = 0;
                        break;
                    case 'middle left':
                        $destX = 0;
                        $destY = ($imgHeight - $wmHeight) / 2;
                        break;
                    case 'middle center':
                        $destX = ($imgWidth - $wmWidth) / 2;
                        $destY = ($imgHeight - $wmHeight) / 2;
                        break;
                    case 'middle right':
                        $destX = $imgWidth - $wmWidth;
                        $destY = ($imgHeight - $wmHeight) / 2;
                        break;
                    case 'bottom left':
                        $destX = 0;
                        $destY = $imgHeight - $wmHeight;
                        break;
                    case 'bottom center':
                        $destX = ($imgWidth - $wmWidth) / 2;
                        $destY = $imgHeight - $wmHeight;
                        break;
                    case 'bottom right':
                        $destX = $imgWidth - $wmWidth;
                        $destY = $imgHeight - $wmHeight;
                        break;
                }

                // Preserve transparency of the watermark
                imagealphablending($image, true);
                imagealphablending($watermark, true);
                imagesavealpha($image, true);
                imagesavealpha($watermark, true);

                // Apply watermark with opacity
                if ($watermarkExtension == 'png' && self::$watermarkOpacity < 100) {
                    // Use imagecopymerge to apply watermark with opacity for PNG
                    self::imagecopymerge_alpha($image, $watermark, $destX, $destY, 0, 0, $wmWidth, $wmHeight, self::$watermarkOpacity);
                } else {
                    imagecopy($image, $watermark, $destX, $destY, 0, 0, $wmWidth, $wmHeight);
                }

                imagedestroy($watermark);
            }
        }
    }

    /**
     * Custom function to merge images with alpha support and opacity
     * 
     * @param resource $dstIm Destination image resource
     * @param resource $srcIm Source image resource
     * @param int $dstX X-coordinate of destination point
     * @param int $dstY Y-coordinate of destination point
     * @param int $srcX X-coordinate of source point
     * @param int $srcY Y-coordinate of source point
     * @param int $srcW Source width
     * @param int $srcH Source height
     * @param int $pct Opacity percentage
     */
    private static function imagecopymerge_alpha($dstIm, $srcIm, $dstX, $dstY, $srcX, $srcY, $srcW, $srcH, $pct)
    {
        // Get image width and height
        $w = imagesx($srcIm);
        $h = imagesy($srcIm);

        // Create a cut resource
        $cut = imagecreatetruecolor($srcW, $srcH);

        // Preserve transparency
        imagealphablending($cut, false);
        imagesavealpha($cut, true);
        $transparent = imagecolorallocatealpha($cut, 0, 0, 0, 127);
        imagefill($cut, 0, 0, $transparent);

        // Copy the relevant section from the destination image to the cut resource
        imagecopy($cut, $dstIm, 0, 0, $dstX, $dstY, $srcW, $srcH);

        // Copy the relevant section from the source image to the cut resource
        imagecopy($cut, $srcIm, 0, 0, $srcX, $srcY, $srcW, $srcH);

        // Merge the cut resource with the destination image
        imagecopymerge($dstIm, $cut, $dstX, $dstY, 0, 0, $srcW, $srcH, $pct);

        // Destroy the cut resource
        imagedestroy($cut);
    }

    /**
     * Resize an image and save the resized version in cache directory.
     * 
     * @param string $imagePath The path to the original image file
     * @param int $width The desired width of the resized image
     * @param int|null $height The desired height of the resized image (optional)
     * @return string|bool Returns the path to the resized image on success, false on failure
     */
    public static function resize($imagePath, $width, $height = null)
    {
        if (!self::checkRequirements()) {
            return false;
        }

        if (!file_exists($imagePath)) {
            self::$errors[] = 'Image file does not exist: ' . $imagePath;
            return false;
        }

        self::ensureCacheDirExists();

        $info = pathinfo($imagePath);
        $extension = strtolower($info['extension']);
        $filename = self::sanitizeFileName($info['filename']);
        list($originalWidth, $originalHeight) = getimagesize($imagePath);

        // Calculate height preserving aspect ratio if not provided
        if ($height === null) {
            $height = intval(($originalHeight * $width) / $originalWidth);
        }

        // Calculate crop coordinates
        $srcX = max(0, ($originalWidth - $width) / 2);
        $srcY = max(0, ($originalHeight - $height) / 2);
        $srcW = min($width, $originalWidth);
        $srcH = min($height, $originalHeight);

        $hash = md5_file($imagePath); // Hash of file content

        if (self::$outputFormat == 'auto') {
            $extOut = $extension;
        } else {
            $extOut = self::$outputFormat;
        }

        $cacheFileName = sprintf('%s-%d-%d-%s.%s', $filename, $width, $height, $hash, $extOut);
        $cachePath = self::$cacheDir . '/' . $cacheFileName;

        if (file_exists($cachePath)) {
            return $cachePath;
        }

        switch ($extension) {
            case 'jpeg':
            case 'jpg':
                $image = imagecreatefromjpeg($imagePath);
                break;
            case 'png':
                $image = imagecreatefrompng($imagePath);
                break;
            case 'gif':
                $image = imagecreatefromgif($imagePath);
                break;
            case 'webp':
                $image = imagecreatefromwebp($imagePath);
                break;
            default:
                self::$errors[] = 'Unsupported image format: ' . $extension;
                return false;
        }

        $resizedImage = imagecreatetruecolor($width, $height);
        self::preserveTransparency($resizedImage, $image, $extension);

        // Crop and resize image
        imagecopyresampled($resizedImage, $image, 0, 0, $srcX, $srcY, $width, $height, $srcW, $srcH);

        // Apply watermark if set
        self::applyWatermark($resizedImage, $extension);

        switch ($extOut) {
            case 'jpeg':
            case 'jpg':
                imagejpeg($resizedImage, $cachePath, self::$quality);
                break;
            case 'png':
                imagepng($resizedImage, $cachePath, self::$compression);
                break;
            case 'webp':
                imagewebp($resizedImage, $cachePath, self::$quality);
                break;
            case 'gif':
                imagegif($resizedImage, $cachePath);
                break;
        }

        imagedestroy($image);
        imagedestroy($resizedImage);

        return $cachePath;
    }

    /**
     * Clear all cached images from the cache directory.
     */
    public static function clearCache()
    {
        self::ensureCacheDirExists();
        $files = glob(self::$cacheDir . '/*');
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }
    }

    /**
     * Get the array of error messages encountered during operations.
     * 
     * @return array The array of error messages
     */
    public static function getErrors()
    {
        return self::$errors;
    }
}

?>
