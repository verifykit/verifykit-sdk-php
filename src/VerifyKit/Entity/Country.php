<?php


namespace VerifyKit\Entity;

/**
 * Class Country
 * @package VerifyKit\Entity
 */
class Country
{

    /** @var string */
    private $phoneCode;

    /** @var string */
    private $countryCode;

    /** @var string */
    private $title;

    /**
     * @return string
     */
    public function getPhoneCode()
    {
        return $this->phoneCode;
    }

    /**
     * @param string $phoneCode
     */
    public function setPhoneCode($phoneCode)
    {
        $this->phoneCode = $phoneCode;
    }

    /**
     * @return string
     */
    public function getCountryCode()
    {
        return $this->countryCode;
    }

    /**
     * @param string $countryCode
     */
    public function setCountryCode($countryCode)
    {
        $this->countryCode = $countryCode;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

}