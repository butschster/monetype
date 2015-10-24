<?php

namespace Modules\Support\Contracts;

use Illuminate\Support\Collection;

interface SearchRepositoryContract
{

    /**
     * @param string $query
     *
     * @return Collection
     */
    public function search($query = "");
}