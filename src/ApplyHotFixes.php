<?php namespace EdmondsCommerce\M2HotFixes;

/**
 * Class ApplyHotFixes
 * @package EdmondsCommerce\M2HotFixes
 * Apply all hot fixes in this modules vendor directory to the canonical Composer vendor directory
 * Retain original files and apply a suffic of ".orig" for backup purposes
 * Should notify user of static-content:deploy / di:compile / cache:flush requirement following update
 *
 *
 */
class ApplyHotFixes
{

    public static $overridePath;
    public static $vendorPath;

    public static function run()
    {
        //Set paths
        self::$overridePath = dirname(__DIR__, 1).'/overrides/';
        self::$vendorPath = dirname(__DIR__, 3);

        //Sanity check version
        $versionCheck = new VersionCheck();
        $versionCheck->check();

        $md5Check = new MD5Check(self::$overridePath, self::$vendorPath);
        if(!$md5Check->check())
        {
            echo "MD5 check failed, aborting\n";
            return 1;
        }

        //Copy the files
        $files = $md5Check->getOverrideCollection()->getOverrideFiles();

        $copyCheck = true;
        foreach($files as $file)
        {
            $source = self::$overridePath.$file;
            $destination = self::$vendorPath.'/'.$file;

            echo "Rewriting: ".$destination."...\n";
            //Backup the original file
            if(!copy($destination, $destination.'.orig'))
            {
                echo 'Could not back up to '.$destination.'.orig';
                $copyCheck = false;
            }
            //Overwrite the file
            if(!copy($source, $destination))
            {
                echo 'Could not copy to file '.$destination."\n";
                $copyCheck = false;
            }
        }

        return $copyCheck;
    }
}



