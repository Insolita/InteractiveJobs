<?php

namespace App\ActiveJobs\Contracts;


use Illuminate\Support\Collection;

interface Reportable
{
    public function storeReport(Collection $result);
}
