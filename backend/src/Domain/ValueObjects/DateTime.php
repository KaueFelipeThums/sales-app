<?php
namespace SalesAppApi\Domain\ValueObjects;

class DateTime
{
    private string $dateTime;

    public function __construct(string $dateTime)
    {
        $this->dateTime = date('Y-m-d H:i:s', strtotime($dateTime));
    }

    public function getDateTime(): string
    {
        return $this->dateTime;
    }
}