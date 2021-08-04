<?php
namespace PetrovDAUtils\Interfaces;

use PetrovDAUtils\Model\ModelError;

interface IError
{
    /** @return ModelError */
    public function error();
}