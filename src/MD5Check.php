<?php namespace EdmondsCommerce\M2HotFixes;

/**
 * Class MD5Check
 * @package EdmondsCommerce\M2HotFixes
 * Checks that MD5 files match the destination of the overrides
 */
class MD5Check
{
    private $overridePath;
    private $vendorPath;
    /**
     * @var OverrideCollection
     */
    private $overrideCollection;

    public function __construct($overridePath, $vendorPath)
    {
        $this->overridePath = $overridePath;
        $this->vendorPath = $vendorPath;
        $this->overrideCollection = new OverrideCollection($overridePath);
    }

    public function check()
    {
        $files = [];
        $continue = true;
        foreach ($this->overrideCollection->getOverrideFiles() as $overrideFile)
        {
            $overridePath = $this->overridePath . $overrideFile;
            $md5FilePath = $overridePath . '.md5';
            $filePath = $this->vendorPath . '/' . $overrideFile;

            $md5Check = file_get_contents($md5FilePath);
            $file = file_get_contents($filePath);
            if (!$file)
            {
                echo "Could not read file: " . $filePath . "\n";
                $continue = false;
                continue;
            }

            $fileMd5 = md5($file);

            if ($fileMd5 != $md5Check)
            {
                $continue = false;
                echo "MD5 check failed for " . $filePath . "\n";
            }
        }

        return $continue;
    }

    /**
     * @return OverrideCollection
     */
    public function getOverrideCollection()
    {
        return $this->overrideCollection;
    }


}