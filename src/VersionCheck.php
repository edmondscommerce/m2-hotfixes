<?php namespace EdmondsCommerce\M2HotFixes;

use function GuzzleHttp\json_decode;
use function Magento\Framework\Filesystem\Driver\file_get_contents;
use function Magento\Update\file_exists;

class VersionCheck
{
    const COMPOSER_JSON = "../../../composer.json";

    /**
     * @var \stdClass
     */
    private $composerJson;

    public function check()
    {
        $json = $this->getComposerJson();


        $magentoVersion = '';
        $moduleVersion = '';
    }

    protected function getComposerJson()
    {
        if($this->composerJson)
        {
            return $this->composerJson;
        }

        if(!file_exists(self::COMPOSER_JSON))
        {
            throw new \Exception('Unable to find composer json file');
        }

        $this->composerJson = json_decode(file_get_contents(self::COMPOSER_JSON));
        return $this->getComposerJson();
    }
}