<?php

namespace Cache;

use Psr\SimpleCache\CacheException;

class IOException extends \Exception implements CacheException
{
}
