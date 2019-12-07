<?php


namespace UonSoftware\RefreshTokens\Contracts;


interface RefreshTokenParser
{
    public const DATA_FORMAT = '%s.%d';
    public const FORMAT      = self::DATA_FORMAT . '.%s';
}
