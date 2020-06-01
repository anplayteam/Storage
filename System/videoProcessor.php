<?php
/*
*	Must be included "ffmpeg static builds"
*	url: https://johnvansickle.com/ffmpeg/
*/
class videoProcessor
{
	public $result=[];
	function __construct()
	{
		$this->setting = array(
			'library' => 'System/ffmpeg/ffmpeg', // Full Address
			'directory' => 'Videos', // directory file compressed output
			'encode' => array(// name => resolution bitrates ffmpeg commands
				"high" => '-s hd720 -b:v 5000K -bufsize 5000K -b:a 192K',
				"midle" => '-s hd480 -b:v 2500K -bufsize 2500K -b:a 128K',
				"low" => '-s 480x360 -b:v 1000K -bufsize 1000K -b:a 128K'
			)
		);
	}
	public function run($video)
	{
		$randname = rand ( 1000 , 9999 );
		$this->result["status"]="error";
		foreach ($this->setting["encode"] as $encode) {
			//Encode Info
			$qname = array_search ($encode, $this->setting["encode"]);
			$command = $encode;
			//Create title
			$vo_name = "V".$randname."-".$qname.".mp4";
			$vo_output = $this->setting['directory'].'/'.$vo_name;
			//Create video
			system($this->setting["library"]." -i '$video' $command $vo_output");
			$this->result["status"]="success";
			$this->result[$qname]=$vo_name;
		}
	}
}