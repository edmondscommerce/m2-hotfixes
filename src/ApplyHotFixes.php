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
    const composer_json = "";

    public static function run()
    {
        //Sanity check version
        $versionCheck = new VersionCheck();
        $md5Check = new MD5Check();

        // Do the stuff
        if(!$versionCheck->check())
        {
            throw new \Exception('Magento version mismatch');
        }
    }

    /**
    * Get the composer json, read it and determine version and only apply to the correct version for this module
     */
    private static function checkMagentoVersionMatch()
    {

    }
}



