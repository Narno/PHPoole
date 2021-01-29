<?php
/**
 * This file is part of the Cecil/Cecil package.
 *
 * Copyright (c) Arnaud Ligny <arnaud@ligny.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cecil;

use Symfony\Component\Filesystem\Filesystem;

class Util
{
    /** @var Filesystem */
    protected static $fs;

    /**
     * Returns a Symfony\Component\Filesystem instance.
     *
     * @return Filesystem
     */
    public static function getFS(): Filesystem
    {
        if (!self::$fs instanceof Filesystem) {
            self::$fs = new Filesystem();
        }

        return self::$fs;
    }

    /**
     * Checks if a date is valid.
     *
     * @param string $date
     * @param string $format
     *
     * @return bool
     */
    public static function isDateValid(string $date, string $format = 'Y-m-d'): bool
    {
        $d = \DateTime::createFromFormat($format, $date);

        return $d && $d->format($format) === $date;
    }

    /**
     * Date to DateTime.
     *
     * @param mixed $date
     *
     * @return \DateTime
     */
    public static function dateToDatetime($date): \DateTime
    {
        // DateTime
        if ($date instanceof \DateTime) {
            return $date;
        }
        // timestamp or 'AAAA-MM-DD'
        if (is_numeric($date)) {
            return (new \DateTime())->setTimestamp($date);
        }
        // string (ie: '01/01/2019', 'today')
        return new \DateTime($date);
    }

    /**
     * Formats a class name.
     *
     * ie: "Cecil\Step\PostProcessHtml" become "PostProcessHtml"
     *
     * @param object $class
     * @param array  $options
     *
     * @return string
     */
    public static function formatClassName($class, array $options = []): string
    {
        $lowercase = false;
        extract($options, EXTR_IF_EXISTS);

        $className = substr(strrchr(get_class($class), '\\'), 1);
        if ($lowercase) {
            $className = strtolower($className);
        }

        return $className;
    }

    /**
     * Tests if a string is an URL.
     *
     * @param string $url
     *
     * @return bool
     */
    public static function isUrl(string $url): bool
    {
        return (bool) preg_match('~^(?:f|ht)tps?://~i', $url);
    }

    /**
     * Tests if a remote file exists.
     *
     * @param string $remoteFile
     *
     * @return bool
     */
    public static function isRemoteFileExists(string $remoteFile): bool
    {
        $handle = @fopen($remoteFile, 'r');
        if (is_resource($handle)) {
            return true;
        }

        return false;
    }

    /**
     * Converts an array of strings into a path.
     *
     * @param string[] $path
     *
     * @return string
     */
    public static function joinPath(string ...$path): string
    {
        array_walk($path, function (&$value, $key) {
            $value = str_replace('\\', '/', $value);
            $value = rtrim($value, '/');
            $value = $key == 0 ? $value : ltrim($value, '/');
        });

        return implode('/', $path);
    }

    /**
     * Converts an array of strings into a system path.
     *
     * @param string[] $path
     *
     * @return string
     */
    public static function joinFile(string ...$path): string
    {
        array_walk($path, function (&$value, $key) use (&$path) {
            $value = str_replace(['\\', '/'], [DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR], $value);
            $value = rtrim($value, DIRECTORY_SEPARATOR);
            $value = $key == 0 ? $value : ltrim($value, DIRECTORY_SEPARATOR);
            // unset entry with empty value
            if (empty($value)) {
                unset($path[$key]);
            }
        });

        return implode(DIRECTORY_SEPARATOR, $path);
    }

    /**
     * Converts an array to a string.
     *
     * ie: [0 => 'A', 1 => 'B'] become '0:A, 1:B'
     *
     * @param array  $array
     * @param string $separator Separtor between the key and the value in the result string
     *
     * @return string
     */
    public static function arrayToString(array $array, string $separator = ':'): string
    {
        $string = '';

        foreach ($array as $key => $value) {
            $string .= sprintf('%s%s%s, ', $key, $separator, $value);
        }

        return substr($string, 0, -2);
    }

    /**
     * Combines an array into a string.
     *
     * @param array  $array
     * @param string $keyToKey   The key that become the key of the new array
     * @param string $keyToValue The key that become the value of the new array
     * @param string $separator  The separtor between the key and the value in the result string
     *
     * @return string
     */
    public static function combineArrayToString(
        array $array,
        string $keyToKey,
        string $keyToValue,
        string $separator = ':'
    ): string {
        $string = '';

        foreach ($array as $subArray) {
            $string .= sprintf('%s%s%s, ', $subArray[$keyToKey], $separator, $subArray[$keyToValue]);
        }

        return substr($string, 0, -2);
    }

    /**
     * file_get_contents() function with error handler.
     *
     * @param string $filename
     *
     * @return string|false
     */
    public static function fileGetContents($filename)
    {
        set_error_handler(
            function ($severity, $message, $file, $line) {
                throw new \ErrorException($message, 0, $severity, $file, $line, null);
            }
        );

        try {
            $return = file_get_contents($filename);
        } catch (\Exception $e) {
            $return = false;
        }
        restore_error_handler();

        return $return;
    }

    /**
     * Returns MIME content type and subtype of a file.
     *
     * ie: ['text', 'text/plain']
     *
     * @param string $path
     *
     * @return string[]
     */
    public static function getMimeType(string $path): array
    {
        if (Util::isUrl($path)) {
            if ($stream = fopen($path, 'r')) {
                $path = $stream;
            }
        }

        if (false === $subtype = mime_content_type($path)) {
            throw new \Exception(sprintf('Can\'t get MIME content type of "%s"', $path));
        }
        if (is_resource($stream)) {
            fclose($stream);
        }
        $type = explode('/', $subtype)[0];

        return [
            $type,
            $subtype,
        ];
    }
}
