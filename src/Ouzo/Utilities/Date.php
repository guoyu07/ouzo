<?php
namespace Ouzo\Utilities;

use DateInterval;
use DateTime;

/**
 * Class Date
 * @package Ouzo\Utilities
 */
class Date
{
    const DEFAULT_TIME_FORMAT = 'Y-m-d H:i';

    /**
     * Return formatted date.
     *
     * @param string $date
     * @param string $format
     * @return null|string
     */
    public static function formatDate($date, $format = 'Y-m-d')
    {
        if (!$date) {
            return null;
        }
        $date = new DateTime($date);
        return $date->format($format);
    }

    /**
     * Return formatted date time.
     *
     * @param string $date
     * @param string $format
     * @return null|string
     */
    public static function formatDateTime($date, $format = self::DEFAULT_TIME_FORMAT)
    {
        return self::formatDate($date, $format);
    }

    /**
     * Adding interval for the freeze time.
     *
     * @link http://php.net/manual/en/dateinterval.construct.php
     *
     * @param string $interval
     * @param string $format
     * @return string
     */
    public static function addInterval($interval, $format = self::DEFAULT_TIME_FORMAT)
    {
        $date = Clock::now()->toDateTime();
        $date->add(new DateInterval($interval));
        return $date->format($format);
    }

    /**
     * Modify the freeze date using date time formats and formatting returned date.
     *
     * @link http://php.net/manual/en/datetime.formats.php
     *
     * @param string $interval
     * @param string $format
     * @return string
     */
    public static function modifyNow($interval, $format = self::DEFAULT_TIME_FORMAT)
    {
        return Clock::now()->toDateTime()->modify($interval)->format($format);
    }

    /**
     * Modify given date using date time formats and formatting returned date.
     *
     * @link http://php.net/manual/en/datetime.formats.php
     *
     * @param string $dateAsString
     * @param string $interval
     * @param string $format
     * @return string
     */
    public static function modify($dateAsString, $interval, $format = self::DEFAULT_TIME_FORMAT)
    {
        $dateTime = new DateTime($dateAsString);
        return $dateTime->modify($interval)->format($format);
    }

    /**
     * Return beginning of day for the given date.
     *
     * Example:
     * <code>
     * $date = '2013-09-09 13:03:43';
     * $begin = Date::beginningOfDay($date);
     * </code>
     * Result:
     * <code>
     * 2013-09-09 00:00:00
     * </code>
     *
     * @param string $date
     * @return string
     */
    public static function beginningOfDay($date)
    {
        return self::formatDate($date) . ' 00:00:00';
    }

    /**
     * Return end of day for the given date.
     *
     * Example:
     * <code>
     * $date = '2013-09-09 13:03:43';
     * $end = Date::endOfDay($date);
     * </code>
     * Result:
     * <code>
     * 2013-09-09 23:59:59.9999
     * </code>
     *
     * @param string $date
     * @return string
     */
    public static function endOfDay($date)
    {
        return self::formatDate($date) . ' 23:59:59.9999';
    }

    /**
     * Return formatted time.
     *
     * @param string $time
     * @param string $format
     * @return null|string
     */
    public static function formatTime($time, $format = 'H:i')
    {
        return self::formatDate($time, $format);
    }

    /**
     * Return formatted Unix timestamp.
     *
     * @param int $timestamp
     * @param string $format
     * @return string
     */
    public static function formatTimestamp($timestamp, $format = self::DEFAULT_TIME_FORMAT)
    {
        $dateTime = new DateTime();
        $dateTime->setTimestamp($timestamp);
        return $dateTime->format($format);
    }
}
