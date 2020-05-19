<?php
class Uploader
{
	private $file;
	private $targetFile;
	private $filetype;
	private $filesize;
	private $status;
	public $result;

	function __construct($file)
	{
		$this->file = $file;
		$this->targetDir = ".Temp/";
		$this->targetFile = $this->targetDir . basename($_FILES["file"]["name"]);

		//calling
		$this->_filetype();
		$this->_filesize();
		$this->_upload();
	}
	// Allow certain file formats
	private function _filetype() {
		$FileType = strtolower(pathinfo($this->targetFile,PATHINFO_EXTENSION));

		if ($FileType == "jpg" || $FileType == "png" || $FileType == "jpeg" || $FileType == "gif") {$this->filetype = "image";$this->status = "success";}
		elseif ($FileType == "mp4" || $FileType == "mkv" || $FileType == "avi") {$this->filetype = "video";$this->status = "success";}
		elseif ($FileType == "srt" || $FileType == "sbv" || $FileType == "sub" || $FileType == "cap" || $FileType == "smi" || $FileType == "sami" || $FileType == "rt" || $FileType == "vtt") {$this->filetype = "subtitle";$this->status = "success";}
		else {$this->error = "Sorry, your file not support";$this->status = "error";die();}

	}
	// Check file size
	private function _filesize() {
		$size = $this->file["size"];

		if ($this->filetype=="image") {
			if ($size < 3 * 1000 * 1000) {
				$this->filesize = $size;$this->status = "success";
			}
			else{$this->error = "Sorry, your image is too large.";$this->status = "error";die();}
		}
		elseif ($this->filetype=="video") {
			if ($size < 3 * 1000 * 1000 * 1000) {
				$this->filesize = $size;$this->status = "success";
			}
			else {$this->error = "Sorry, your video is too large.";$this->status = "error";die();}
		}
		elseif ($this->filetype=="subtitle") {
			if ($size < 300 * 1000 * 1000) {
				$this->filesize = $size;$this->status = "success";
			}
			else {$this->error = "Sorry, your subtitle is too large.";$this->status = "error";die();}
		}
	}
	// Uploading to temp dir and result value
	private function _upload()
	{
		if ($this->status=="success") {
			if (move_uploaded_file($_FILES["file"]["tmp_name"], $this->targetFile)) {
				chmod($this->targetFile, 0755);
				$this->result = array('status' => $this->status, 'file' => $this->targetFile, 'filetype' => $this->filetype);
			}
		}
		else {
			$this->status = "error";
			$this->result = array('status' => $this->status, 'error' => $this->error);
			die();
		}
	}
	function __destruct() {
		@unlink($this->targetFile);
	}
}