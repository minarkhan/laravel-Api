<?php

namespace App\Exceptions;

use Exception;

class ProductNotBeLongsToUser extends Exception
{
    function render(){
        return ['errors' => 'product not belongs to user'];
    }
}
