<?php

namespace VerifyKit\Constant;

/**
 * Class ValidationMethod
 * @package VerifyKit\Constant
 */
class ValidationMethod
{
    const TYPE_OTP = 'otp';
    const TYPE_WHATSAPP = 'whatsapp';
    const TYPE_TELEGRAM = 'telegram';
    const TYPE_FLASH_CALL = 'flashcall';

    /**
     * @return array
     */
    public static function getValidationMethods()
    {
        return [
            self::TYPE_OTP,
            self::TYPE_WHATSAPP,
            self::TYPE_TELEGRAM,
            self::TYPE_FLASH_CALL
        ];
    }
}