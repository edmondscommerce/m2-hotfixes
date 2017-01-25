<?php namespace EdmondsCommerce\M2HotFixes;

/**
 * Class MD5Check
 * @package EdmondsCommerce\M2HotFixes
 * Checks that MD5 files match the destination of the overrides
 */
class MD5Check
{
    private $overridePath;

    public function __construct($path)
    {
        $this->overridePath = $path;
    }

    public function check($path)
    {

    }
}