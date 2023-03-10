<?php

namespace App\Service;

abstract class Importer
{
    abstract function import() : void;
}