<?php namespace EdmondsCommerce\M2HotFixes;

use Composer\Composer;

class VersionCheck
{
    private $composerJsonPath;

    /**
     * @var \stdClass
     */
    private $composerJson;

    public function __construct()
    {
        $this->composerJsonPath = dirname(__DIR__, 4) . DIRECTORY_SEPARATOR . 'composer.json';
    }

    /**
     * Get the composer json, read it and determine version and only apply to the correct version for this module
     */
    public function check()
    {
        echo $this->composerJsonPath . "\n";
        $json = $this->getComposerJson();

        $magentoVersion = substr($json->require->{"magento/product-community-edition"}, 0, 5);
        $moduleVersion = substr($json->require->{"edmondscommerce/m2-hotfixes"}, 0, 5);

        if($magentoVersion != $moduleVersion)
        {
            throw new \Exception('Module version and Magento version mismatch'."\n".'Module: '.$moduleVersion.' Magento: '.$magentoVersion);
        }

        return true;
    }

    protected function getComposerJson()
    {
        if ($this->composerJson)
        {
            return $this->composerJson;
        }

        if (!file_exists($this->composerJsonPath))
        {
            throw new \Exception('Unable to find composer json file: ' . $this->composerJsonPath);
        }

        $this->composerJson = json_decode(file_get_contents($this->composerJsonPath));

        return $this->getComposerJson();
    }
}