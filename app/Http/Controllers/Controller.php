<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;

abstract class Controller extends BaseController
{
    // Application base controller — extends Laravel's routing controller so helpers like
    // middleware() and callAction() are available to child controllers.
}
