<?php
namespace Infinity\Common\Data\Environment\Provider;

use Infinity\Common\Data\Environment;

interface ProviderInterface
{
    public function register( Environment $env );
}
