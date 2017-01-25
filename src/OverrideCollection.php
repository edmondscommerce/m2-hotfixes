<?php namespace EdmondsCommerce\M2HotFixes;

class OverrideCollection
{
    private $overridePath;

    /**
     * @var array
     */
    private $filePaths;

    /**
     * List of MD5 files for the original files
     * @var array
     */
    private $md5FilePaths;
    /**
     * List of files to override with
     * @var array
     */
    private $updateFilePaths;

    public function __construct($overridePath)
    {
        $this->overridePath = $overridePath;
        $this->md5FilePaths = [];
        $this->updateFilePaths = [];
        $dirIter = new \DirectoryIterator($overridePath);

        $files = $this->searchDir($dirIter);
        $this->filePaths = $this->collapseDirArray($files);
        $this->filterFiles();
    }

    /**
     * Prepare file lists
     */
    protected function filterFiles()
    {
        foreach($this->filePaths as $path)
        {
            if(preg_match('/(.+)\.md5$/', $path) === 1)
            {
                $this->md5FilePaths[] = $path;
            }
            else
            {
                $this->updateFilePaths[] = $path;
            }
        }
    }

    public function getMd5Files()
    {
        return $this->md5FilePaths;
    }

    /**
     * Get all files to override
     */
    public function getOverrideFiles()
    {
        return $this->updateFilePaths;
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
                $result[] =  preg_replace('/^overrides\//', '' ,trim($prefix.'/'. $value, '/'));
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

            $files[basename($node->getPath())][] = $node->getFilename();
        }

        return $files;
    }
}