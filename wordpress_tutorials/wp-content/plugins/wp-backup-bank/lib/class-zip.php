<?php

/**
* @author		 DavidAnderson <https://updraftplus.com>
* @copyright 2011-16 David Anderson, 2010 Paul Kehrer
* @license	 https://opensource.org/licenses/gpl-license
* @link			 https://updraftplus.com
* @since		 available since Release 2.4.1
*/

if(!defined("ABSPATH")) exit; // Exit if accessed directly
if (class_exists("ZipArchive")):
# We just add a last_error variable for comaptibility with our Backup_bank_PclZip object
class Backup_bank_ZipArchive extends ZipArchive
{
	public $last_error = "Unknown: ZipArchive does not return error messages";
}
endif;

/**
* @author		 DavidAnderson <https://updraftplus.com>
* @copyright 2011-16 David Anderson, 2010 Paul Kehrer
* @license	 https://opensource.org/licenses/gpl-license
* @link			 https://updraftplus.com
* @since		 available since Release 2.4.1
*/

# A ZipArchive compatibility layer, with behaviour sufficient for our usage of ZipArchive
class Backup_bank_PclZip
{
	public $pclzip;
	public $path;
	public $addfiles;
	public $adddirs;
	public $last_error;

	/**
	* @author		 DavidAnderson <https://updraftplus.com>
	* @copyright 2011-16 David Anderson, 2010 Paul Kehrer
	* @license	 https://opensource.org/licenses/gpl-license
	* @link			 https://updraftplus.com
	* @since		 available since Release 2.4.1
	*/
	
	public function __construct()
	{
		$this->addfiles = array();
		$this->adddirs = array();
	}

	/**
	* @author		 DavidAnderson <https://updraftplus.com>
	* @copyright 2011-16 David Anderson, 2010 Paul Kehrer
	* @license	 https://opensource.org/licenses/gpl-license
	* @link			 https://updraftplus.com
	* @since		 available since Release 2.4.1
	*/

	public function open($path, $flags = 0)
	{
		if(!defined("PCLZIP_TEMPORARY_DIR")) define("PCLZIP_TEMPORARY_DIR", trailingslashit(dirname($path)));
		if(!class_exists("PclZip")) include_once(ABSPATH."/wp-admin/includes/class-pclzip.php");
		if(!class_exists("PclZip"))
		{
			$this->last_error = "No PclZip class was found";
			return false;
		}

		# Route around PHP bug (exact version with the problem not known)
		$ziparchive_create_match = (version_compare(PHP_VERSION, "5.2.12", ">") && defined("ZIPARCHIVE::CREATE")) ? ZIPARCHIVE::CREATE : 1;

		if ($flags == $ziparchive_create_match && file_exists($path)) @unlink($path);

		$this->pclzip = new PclZip($path);
		if(empty($this->pclzip))
		{
			$this->last_error = "Could not get a PclZip object";
			return false;
		}
		$this->path = $path;
		return true;
	}

	/**
	* @author		 DavidAnderson <https://updraftplus.com>
	* @copyright 2011-16 David Anderson, 2010 Paul Kehrer
	* @license	 https://opensource.org/licenses/gpl-license
	* @link			 https://updraftplus.com
	* @since		 available since Release 2.4.1
	*/

	public function close()
	{
		if(empty($this->pclzip))
		{
			$this->last_error = "Zip file was not opened";
			return false;
		}
		$activity = false;
		if(isset($this->addfiles) && count($this->addfiles) > 0)
		{
			foreach($this->addfiles as $rdirname => $adirnames)
			{
				foreach($adirnames as $adirname => $files)
				{
					if(false == $this->pclzip->add($files, PCLZIP_OPT_REMOVE_PATH, $rdirname, PCLZIP_OPT_ADD_PATH, $adirname))
					{
						$this->last_error = $this->pclzip->errorInfo(true);
						return false;
					}
					$activity = true;
				}
			}
		}

		$this->pclzip = false;
		$this->addfiles = array();
		$this->adddirs = array();

		clearstatcache();
		if($activity && filesize($this->path) < 50)
		{
			$this->last_error = "Write failed - unknown cause (check your file permissions)";
			return false;
		}
		return true;
	}

	/**
	* @author		 DavidAnderson <https://updraftplus.com>
	* @copyright 2011-16 David Anderson, 2010 Paul Kehrer
	* @license	 https://opensource.org/licenses/gpl-license
	* @link			 https://updraftplus.com
	* @since		 available since Release 2.4.1
	*/

	public function addFiles_unset()
	{
		if(isset($this->addfiles) && count($this->addfiles) > 0)
		{
			foreach($this->addfiles as $rdirname => $adirnames)
			{
				foreach($adirnames as $adirname => $files)
				{
					if(false == $this->pclzip->add($files, PCLZIP_OPT_REMOVE_PATH, $rdirname, PCLZIP_OPT_ADD_PATH, $adirname))
					{
						$this->last_error = $this->pclzip->errorInfo(true);
						return false;
					}
					$activity = true;
				}
			}
		}
		$this->addfiles = array();
	}

	/**
	* @author		 DavidAnderson <https://updraftplus.com>
	* @copyright 2011-16 David Anderson, 2010 Paul Kehrer
	* @license	 https://opensource.org/licenses/gpl-license
	* @link			 https://updraftplus.com
	* @since		 available since Release 2.4.1
	*/

	# Note: basename($add_as) is irrelevant; that is, it is actually basename($file) that will be used. But these are always identical in our usage.
	public function addFile($file, $add_as)
	{
		# Add the files. PclZip appears to do the whole (copy zip to temporary file, add file, move file) cycle for each file - so batch them as much as possible. We have to batch by dirname(). On a test with 1000 files of 25Kb each in the same directory, this reduced the time needed on that directory from 120s to 15s (or 5s with primed caches).
		$rdirname = dirname($file);
		$adirname = dirname($add_as);
		$this->addfiles[$rdirname][$adirname][] = $file;
	}

	/**
	* @author		 DavidAnderson <https://updraftplus.com>
	* @copyright 2011-16 David Anderson, 2010 Paul Kehrer
	* @license	 https://opensource.org/licenses/gpl-license
	* @link			 https://updraftplus.com
	* @since		 available since Release 2.4.1
	*/

	# PclZip doesn't have a direct way to do this
	public function addEmptyDir($dir)
	{
		$this->adddirs[] = $dir;
	}
}
