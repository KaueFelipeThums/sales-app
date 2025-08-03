<?php
namespace SalesAppApi\Domain\ValueObjects;

class Date
{
    private string $date;

    public function __construct(string $date)
    {
        $this->date = date('Y-m-d', strtotime($date));
    }

    public function getDate(): string
    {
        return $this->date;
    }
}