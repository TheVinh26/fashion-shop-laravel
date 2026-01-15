<?php

namespace App\Exceptions;

use Exception;

class InsufficientStockException extends Exception
{
    public array $products = [];

    public function __construct(array $products)
    {
        $this->products = $products;

        parent::__construct('Some products are out of stock');
    }
}
