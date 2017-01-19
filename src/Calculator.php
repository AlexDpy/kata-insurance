<?php

namespace Kata;

use Psr\Log\LoggerInterface;

class Calculator
{
    const MULTIPLIER = 1.8;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * Calculator constructor.
     *
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param Quote quote
     *
     * @return float
     */
    public function compute(Quote $quote)
    {
        $result = 0;


        $result += $this->cover($quote->getCover())
            * $this->days($quote->getNbDays())
            * $this->travellers($quote->getTravellerAges(), $quote->getCountry());

        $result *= $this->country($quote->getCountry());

        $result += $this->options($quote->getOptions());


        return $result;
    }

    private function travellers(array $travellers, $country = null)
    {
        // espage - 16-28 -- 65
        //italie - 18-25 - 50
        $t = $travellers;
        $coef = 0;
        $ageCases = $this->agesCases($country);

        foreach ($t as $travellerAge) {
            if ($travellerAge < 0) {
                throw new BadRequestException('invalid traveller age: '.$travellerAge);
            }

            $coef += 1;
// 1.1 0.9

            // enfant -> 1.1
            // jeune -> 0.9
            // adulte -> 1
            // senior -> 1.5

            if ($travellerAge >= $ageCases[2]) {
                //senior
                $coef += 0.5;
            } elseif ($travellerAge < $ageCases[0]) {
                // enfant
                $coef += 0.1;
            } elseif ($travellerAge < $ageCases[1]) {
                // jeune
                $coef -= 0.1;
            }

        }

        return $coef;
    }

    private function agesCases($country)
    {
        switch ($country) {
            case 'ES':
                return [16, 28, 65];
            case 'IT':
                return [18, 25, 50];
            case 'UK':
                return [18, 22, 65];
            default:
                return [18, 25, 65];
        }
    }

    /**
     * @param int $days
     * @return int
     */
    private function days($days)
    {
        if ($days < 1) {
            throw new BadRequestException('invalid days: '.$days);
        }

        $weeks = $days / 7;

        if ($days < 7) {
            return 7;
        }

        if ($days <= 9) {
            return 7;
        }

        if ($weeks >= 21) {
            $days = floor($weeks * 7);
        }

        return $days;
    }

    // cover extra 2,4 au lieu de 1;8

    private function cover($cover)
    {
        switch (strtolower($cover)) {
            case 'extra' :
                return 2.4;
            case 'Premier' :
                return 4.2;
            default:
                return 1.8;
        }
    }

    private function options($options)
    {
        $result = 0;

        if (in_array('Skiing', $options)) {
            $result += 24;
        }

        if (in_array('Medical', $options)) {
            $result += 72;
        }
        if (in_array('Sports', $options)) {
            $result += 25;
        }


        if (in_array('Yoga', $options)) {
            $result -= 3;
        }

        if (in_array('Scuba', $options)) {
            $result += 36;
        }

        return $result;
    }

    /**
     * @param $country
     * @return float
     */
    private function country($country)
    {
        if (is_numeric($country)) {
            throw new BadRequestException('country numeric form: '.$country);
        }

        if (strlen($country) > 2) {
            throw new BadRequestException('country fake: '.$country);
        }

        switch (strtoupper($country)) {
            case 'PT':
                return 0.5;
            case 'LV':
            case 'GR':
            case 'EL':
                return 0.6;
            case 'NL':
            case 'LT':
            case 'SK':
                return 0.7;
            case 'DE':
            case 'FI':
            case 'SI':
                return 0.8;
            case 'BE':
            case 'EG':
            case 'AT':
                return 0.9;
            case 'FR':
                return 1;
            case 'UK':
            case 'BG':
            case 'ES':
            case 'GB':
            case 'HU':
                return 1.1;
            case 'IT':
            case 'IM':
            case 'SE':
            case 'MT':
            case 'CZ':
            case 'DK':
                return 1.2;
            case 'EE':
            case 'LU':
            case 'RO':
            case 'TD':
            case 'HR':
                return 1.3;
            case 'PL':
                return 1.4;
            case 'WF':
                return 1.5;
            case 'PA':
            case 'TH':
            case 'UY':
            case 'MK':
            case 'MX':
            case 'CY':
            case 'ZA':
            case 'TW':
                return 1.6;
            case 'SZ':
                return 3.7;
            case 'KP':
                return 6.9;
            default:
                $this->logger->alert('country not found', [
                    'country' => $country,
                ]);
                return 1;
        }

        return 1;
    }
}
