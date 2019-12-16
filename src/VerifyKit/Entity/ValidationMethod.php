<?php

namespace VerifyKit\Entity;

/**
 * Class ValidationMethod
 * @package VerifyKit\Entity
 */
class ValidationMethod
{
    /** @var string */
    private $name;

    /** @var string */
    private $app;

    /** @var string */
    private $text;

    /** @var string */
    private $textColour;

    /** @var string */
    private $bgColour;

    /** @var string */
    private $iconPath;

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getApp()
    {
        return $this->app;
    }

    /**
     * @param string $app
     */
    public function setApp($app)
    {
        $this->app = $app;
    }

    /**
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param string $text
     */
    public function setText($text)
    {
        $this->text = $text;
    }

    /**
     * @return string
     */
    public function getTextColour()
    {
        return $this->textColour;
    }

    /**
     * @param string $textColour
     */
    public function setTextColour($textColour)
    {
        $this->textColour = $textColour;
    }

    /**
     * @return string
     */
    public function getBgColour()
    {
        return $this->bgColour;
    }

    /**
     * @param string $bgColour
     */
    public function setBgColour($bgColour)
    {
        $this->bgColour = $bgColour;
    }

    /**
     * @return string
     */
    public function getIconPath()
    {
        return $this->iconPath;
    }

    /**
     * @param string $iconPath
     */
    public function setIconPath($iconPath)
    {
        $this->iconPath = $iconPath;
    }


}