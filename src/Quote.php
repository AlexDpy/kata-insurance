<?php

namespace Kata;

class Quote
{
    /**
     * @var string
     */
    private $country;

    /**
     * @var \DateTimeImmutable
     */
    private $departureDate;

    /**
     * @var \DateTimeImmutable
     */
    private $returnDate;

    /**
     * @var int[]
     */
    private $travellerAges;

    /**
     * @var array
     */
    private $options;

    /**
     * @var string
     */
    private $cover;

    /**
     * Quote constructor.
     *
     * @param string $country
     * @param \DateTimeImmutable $departureDate
     * @param \DateTimeImmutable $returnDate
     * @param \int[] $travellerAges
     * @param array $options
     * @param string $cover
     */
    public function __construct(
        $country,
        \DateTimeImmutable $departureDate,
        \DateTimeImmutable $returnDate,
        array $travellerAges,
        array $options,
        $cover
    ) {
        $this->country = $country;
        $this->departureDate = $departureDate;
        $this->returnDate = $returnDate;
        $this->travellerAges = $travellerAges;
        $this->options = $options;
        $this->cover = $cover;
    }

    /**
     * @return int
     */
    public function getNbDays()
    {
        return $this->returnDate->diff($this->departureDate)->days;
    }

    /**
     * @return int
     */
    public function getNgTravellers()
    {
        return count($this->travellerAges);
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getDepartureDate()
    {
        return $this->departureDate;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getReturnDate()
    {
        return $this->returnDate;
    }

    /**
     * @return \int[]
     */
    public function getTravellerAges()
    {
        return $this->travellerAges;
    }

    /**
     * @return string
     */
    public function getCover()
    {
        return $this->cover;
    }

}
