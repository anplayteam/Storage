<?php
class subtitleProcessor {

	public $result=[];

	function __construct()
	{
		$this->setting = array(
			'directory' => 'Subtitles', // directory file compressed output
		);
	}
	public function run($subtitle)
	{
		$randname = rand ( 1000 , 9999 );
		$this->result["status"]="error";
		//Create title
		$so_name = "S".$randname.".".$file_extension = strtolower(substr(strrchr($subtitle,"."),1));
		$so_output = $this->setting['directory'].'/'.$so_name;
		//Create Subtitle
		rename( "$subtitle", $so_output );
		$this->result["status"]="success";
		$this->result["subtitle"]=$so_name;
	}
}