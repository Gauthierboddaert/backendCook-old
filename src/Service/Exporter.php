<?php

namespace App\Service;

abstract class Exporter
{
    abstract  public function export() : ?array;
}