<?php

declare(strict_types=1);

namespace App\Services;

use DateTimeInterface;

final class DateService
{
    public function __construct(
        private readonly SettingsService $settings
    ) {
    }


    public function format(
        string|DateTimeInterface|null $value
    ): string {

        if ($value === null || $value === '') {
            return '';
        }


        if ($value instanceof DateTimeInterface) {

            $date = $value;

        } else {

            try {

                $date = new \DateTime($value);

            } catch (\Exception) {

                return $value;
            }
        }


        $format =
            $this->settings->get(
                'date_format',
                'Y-m-d'
            );


        return $date->format($format);
    }
}
