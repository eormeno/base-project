<?php
namespace App\Contracts;

interface MessageProviderInterface
{
    public function getMessages(array $parameters): array;
}
