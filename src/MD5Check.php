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
        echo $path . "\n";
    }

    public function check()
    {
        $files = [];
        $dirIter = new \DirectoryIterator($this->overridePath);

        $files = $this->searchDir($dirIter);
        $tree = $this->collapseDirArray($files);
        var_dump($tree);

        return true;
    }

    protected function collapseDirArray(array $tree, $prefix = '')
    {
        $result = [];
        foreach ($tree as $key => $value)
        {
            if (is_array($value))
            {
                $result = $result + $this->collapseDirArray($value,   $prefix.'/'.$key);
            }
            else
            {
                $result[] =  trim($prefix.'/'. $value, '/');
            }
        }


        return $result;
    }

    protected function searchDir(\DirectoryIterator $directoryIterator)
    {
        $files = [];
        foreach ($directoryIterator as $node)
        {
            if ($node->isDir() && !$node->isDot())
            {
                $files[basename($node->getPath())] = $this->searchDir(new \DirectoryIterator($node->getPathname()));
                continue;
            }

            if ($node->isDot())
            {
                continue;
            }

            $files[] = $node->getFilename();
        }

        return $files;
    }
}