<?php

namespace App\Http\Controllers\Api\v1;

use OpenApi\Attributes as OAT;

#[OAT\Info(
    version: '1.0',
    title: 'Document E-sign API'
)]
abstract class ApiController
{}
