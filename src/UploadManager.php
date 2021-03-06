<?php

namespace abdulsametsahin\UploadManager;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use abdulsametsahin\UploadManager\File;

class UploadManager extends \App\Http\Controllers\Controller
{
	
	public $upload_path = "storage";

	/**
	 * Main view
	 */
	public function mainPage(Request $req)
	{
		return view("UploadManager::mainPage");
	}

	/**
	 * returns files and folders in given path.
	 */
	public function getDir(Request $req)
	{
		$path = $req->path ?? "/";
		$path = str_replace("///", "/", $path);
		$path = str_replace("//", "/", $path);
		$path = str_replace($this->upload_path, null, $path);
		$path = $this->upload_path . $path;

		$req->session()->put('upload_manager_last_path', $path);

		try {
			$folder = scandir(public_path($path), true);
		}catch(\Exception $e) {
			mkdir(public_path($path));
			$folder = scandir(public_path($path), true);
		}

		// Remove "." and ".."
		unset($folder[ count($folder) - 1 ]);
		unset($folder[ count($folder) - 1 ]);

		$array = [];

		foreach ($folder as $f) {
			$elPath = $path . "/" . $f;
			$type = "folder";
			if (!is_dir($elPath)) {
				$type = "file";
			}

			if(@is_array(getimagesize($elPath))){
			    $type = "image";
			} 

			$array[] = [
				'type' => $type, 
				'name' => $f, 
				'isSelected' => false,
				'size' => $type == 'folder' ? 0 : number_format(filesize($elPath)/1048576, 3),
				'path' => str_replace(["///", "//"], "/", $elPath)
			];
		}

		unset($folder);

		return $array;
	}

	/**
	 * Uploads every given file.
	 */
	public function upload(Request $req)
	{
		try {
			$path = str_replace("storage", "public", session('upload_manager_last_path', $this->upload_path));
			$upload = ($req->filepond->storeAs($path, $req->filepond->getClientOriginalName()));
			return [
				'status' => 'success',
				'path' => $upload
			];
		}catch (\Exception $e){
			return $e->getMessage();
		}
		//return $req->all();
	}

	/**
	 * Create folder.
	 */
	public function createFolder(Request $req)
	{
		try {
			$make = mkdir(storage_path("app/".$this->getLastPath())."/". $req->folderName, 755);
			return [
				'status' => 'success',
			];
		}catch (\Exception $e) {
			return [
				'status' => 'error',
				'message' => $e->getMessage()
			];
		}
	}

	/**
	 * This is for upload method. 
	 * Gives the last listed path of getDir method.
	 */
	public function getLastPath()
	{
		return $path = str_replace($this->upload_path, "public", session('upload_manager_last_path', $this->upload_path));;
	}

	/**
	 * Delete given files/folders.
	 */
	public function delete(Request $req)
	{
		try {
			foreach ($req->selectedFiles as $filepath) {
				$file = new File($filepath);
				$file->delete();
			}
			return [
				'status' => 'success',
			];
		}catch (\Exception $e) {
			return [
				'status' => 'error',
				'message' => $e->getMessage()
			];
		}
	}

	

	/**
	 * Move given files/folders.
	 */
	public function move(Request $req)
	{
		try {
			foreach ($req->selectedFiles as $filepath) {

				$file = new File($filepath);
				$file->move($req->movePath);
				
			}
			return [
				'status' => 'success',
			];
		}catch (\Exception $e) {
			return [
				'status' => 'error',
				'message' => $e->getMessage()
			];
		}
	}

}