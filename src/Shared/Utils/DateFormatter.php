<?php

declare(strict_types=1);

namespace App\Shared\Utils;

use DateTimeInterface;

final readonly class DateFormatter
{

    /**
     * Formats a given DateTime object to a specified format.
     *
     * @param \DateTimeInterface $dateTime
     * @param string $format
     * @return string
     */
    public function format(\DateTimeInterface $dateTime, string $format = 'Y-m-d H:i:s'): string
    {
        return $dateTime->format($format);
    }

    /**
     * Converts a string to a DateTime object.
     *
     * @param string $dateString
     * @param string $format
     * @return \DateTimeInterface
     * @throws \Exception
     */
    public function parse(string $dateString, string $format = 'Y-m-d H:i:s'): \DateTimeInterface
    {
        $dateTime = \DateTime::createFromFormat($format, $dateString);
        if (!$dateTime) {
            throw new \RuntimeException("Invalid date format: $dateString");
        }
        return $dateTime;
    }
    
    /**
     * Returns the current date and time in a specified format.
     *
     * @param string $format
     * @return string
     */
    public function now(string $format = 'Y-m-d H:i:s'): string
    {
        return (new \DateTime())->format($format);
    }

    /**
     * Formats a given DateTime object to ISO 8601 format.
     *
     * @param \DateTimeInterface $dateTime
     * @return string
     */
    public function toIso8601(\DateTimeInterface $dateTime): string
    {
        return $dateTime->format(DateTimeInterface::ATOM);
    }
    
}