<?php

namespace Modules\Transactions\Contracts;

interface Buyable
{

    /**
     * @return integer|float
     */
    public function getCost();
}
