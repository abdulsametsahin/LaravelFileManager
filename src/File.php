<?php

namespace abdulsametsahin\UploadManager;

class File
{
	public $filepath;
	public $filename;

	function __construct($filepath)
	{
		$this->filename = explode("/", $filepath);
		$this->filename = $this->filename[count($this->filename) - 1];

		$filepath = $filepath;
		$this->filepath = public_path($filepath);
	}

	public function move($newFilePath)
	{
		$newFilePath = public_path("storage/" . $newFilePath ."/". $this->filename);
		return rename($this->filepath, $newFilePath);
	}

	public function delete()
	{
		if (is_dir($this->filepath))
			return $this->deleteDir($this->filepath);
		else
			return ($this->filepath);
	}

	/**
	 * Delete folder.
	 */
	public function deleteDir($dirPath) {
	    if (! is_dir($dirPath)) {
	        throw new InvalidArgumentException("$dirPath must be a directory");
	    }
	    if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
	        $dirPath .= '/';
	    }
	    $files = glob($dirPath . '*', GLOB_MARK);
	    foreach ($files as $file) {
	        if (is_dir($file)) {
	            self::deleteDir($file);
	        } else {
	            unlink($file);
	        }
	    }
	    return rmdir($dirPath);
	}
}