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
        echo $path."\n";
    }

    public function check()
    {
        $files = [];
        $dirIter = new \DirectoryIterator($this->overridePath);

        $files = $this->searchDir($dirIter);

        var_dump($files);

        return true;
    }

    public function searchDir(\DirectoryIterator $directoryIterator)
    {
        $files = [];
        foreach($directoryIterator as $node)
        {
            if($node->isDir() && !$node->isDot())
            {
                $files[basename($node->getPath())] = $this->searchDir(new \DirectoryIterator($node->getPathname()));
                continue;
            }

            if($node->isDot())
            {
                continue;
            }

            $files[] = $node->getFilename();
        }

        return $files;
    }
}