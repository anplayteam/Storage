<?php
require_once "System/imageRead.php";
require_once "System/videoRead.php";
require_once "System/subtitleRead.php";

require_once "System/Uploader.php";

require_once "System/imageProcessor.php";
require_once "System/videoProcessor.php";
require_once "System/subtitleProcessor.php";

class Storage {
	public function log($log,$messages) {
		if ($log=="image") {$file = "Images/.images_log";}
		elseif ($log=="video") {$file = "Videos/.videos_log";}
		elseif ($log=="subtitle") {$file = "Subtitles/.subtitles_log";}
		elseif ($log=="main") {$file = ".log";}
		$messages = "[".date("Y/m/d H:i:s")."]"." "."[".$messages."]"."\r\n";
		@$myfile = fopen($file, "a");
		@fwrite($myfile, $messages);
		@fclose($myfile);
	}
	public function read($file) {
		if ($file[0]=="P" || $file[0]=="p") {
			$image = new imageRead($file);
		}
		elseif ($file[0]=="V" || $file[0]=="v") {
			$stream = new videoRead($file);
			$stream->start();
		}
		elseif ($file[0]=="S" || $file[0]=="s") {
			$stream = new subtitleRead($file);
		}
	}
	public function write() {
		if (isset($_FILES["file"])) {
			$uploader = new Uploader($_FILES["file"]);
			if ($uploader->result["status"]="success") {
				if ($uploader->result["filetype"]=="image") {
					$processor = new imageProcessor;
					$result = $processor->run($uploader->result["file"]);
					if ($result['status']=="success") {
						$output = json_encode(array('status' => "success", 'image' => $result['data']['compressed']['name']));
						$this->log("image",$output);
						print_r($output);
						return $output;
					}
					else {
						$output = json_encode(array('status' => "error"));
						$this->log("image",$output);
						print_r($output);
						return $output;
					}
				}
				elseif ($uploader->result["filetype"]=="video") {
					$processor = new videoProcessor;
					$processor->run($uploader->result["file"]);
					$output = json_encode($processor->result);
					$this->log("video",$output);
					print_r($output);
					return $output;
				}
				elseif ($uploader->result["filetype"]=="subtitle") {
					$processor = new subtitleProcessor;
					$processor->run($uploader->result["file"]);
					$output = json_encode($processor->result);
					$this->log("subtitle",$output);
					print_r($output);
					return $output;
				}
			}
		}
		else {
			http_response_code(404);
			return $messages = 'No file attached for upload.';
		}
	}
	public function delete($file) {
		if ($file[0]=="P" || $file[0]=="p") {@unlink('Images/'.$file);}
		if ($file[0]=="V" || $file[0]=="v") {@unlink('Videos/'.$file);}
		if ($file[0]=="S" || $file[0]=="s") {@unlink('Subtitles/'.$file);}
	}
}
$storage = new Storage;
//Api

//Fetching Must In GET
if ($_SERVER['REQUEST_METHOD']=="GET") {
	if (isset($_GET["file"])) {
		http_response_code(200);
		$storage->read($_GET["file"]);
	}
}
// Uploading Must Be in POST
if ($_SERVER['REQUEST_METHOD']=="POST") {
	function getIPAddress() {
		if(!empty($_SERVER['HTTP_CLIENT_IP'])) {  $ip = $_SERVER['HTTP_CLIENT_IP'];  }  
		elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];}  
		else{$ip = $_SERVER['REMOTE_ADDR']; }
		return $ip;
	}
	@include_once 'passkeys.php';
	if (isset($Keys)) {
		$Keys = array_flip($Keys);
		if (isset($_POST["key"])) {
			$input_key = md5($_POST["key"]);
			if (isset($Keys[$input_key])) {
				http_response_code(200);
				$message = $storage->write();
				$output = json_encode(array('status' => "successful",'Key' => $Keys[$input_key], 'message' => $message, 'ip' => getIPAddress()));
				$storage->log("main",$output);
			}
			else {
				http_response_code(403);
				$output = json_encode(array('status' => "error", 'error' => "api key is not vaild.", 'ip' => getIPAddress()));
				$storage->log("main",$output);
				print_r($output);
				header('HTTP/1.0 403 Forbidden');
				exit;
			}
		}
		else {
			http_response_code(403);
			$output = json_encode(array('status' => "error", 'error' => "api key not found.", 'ip' => getIPAddress()));
			$storage->log("main",$output);
			print_r($output);
			header('HTTP/1.0 403 Forbidden');
			exit;
		}
	}
	else {
		http_response_code(200);
		$message = $storage->write();
		$output = json_encode(array('status' => "successful",'message' => $message, 'ip' => getIPAddress()));
		$storage->log("main",$output);
	}
}
if ($_SERVER['REQUEST_METHOD']=="DELETE") {
	if (isset($_REQUEST["file"])) {
		http_response_code(200);
		$storage->delete($_REQUEST["file"]);
	}
}