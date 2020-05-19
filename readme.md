# Storage

Allows to storage and retrieval of any amount of data at any time. You can use Storage for storing videos, subtitle & images. it also process images & videos before storing. it's have no own database but it's have log for tracking updates. and it's also have no encryption to encrypt files. but it have own render system to render images and videos file for public view or download.

## Installation

Simple just you have to upload or clone it into your project file / server and give it read and write permission.
And for Video Processoring need to clone [ffmpeg](https://johnvansickle.com/ffmpeg/) and put that file into `System/*` or Change path from `System/videoProcessor.php`

## Authentication

Authentication only work when you uploading any file. By using POST method.

For Set/Change Authentication modify `passkeys.php` file.

```php
// Username ( For identify key or log ) => Password/Key ( Before puting it please manually encrypt it by using md5 method )
$Keys = array('Username1' => '', 'Username2' => '');
```
**Default Keys -**

| Username  | Password         |
| :-------: | ---------------- |
|  Admin   | A$%01m^KxacI |
| System | 6<B:fa*3KaFKA=-? |
| Devloper  | blu3Snow761      |


## Directory

Directory structure

```
.
├── .gitignore
├── .htaccess
├── Images
│   └── .images_log
├── index.php
├── .log
├── passkeys.php
├── readme.md
├── Subtitles
│   └── .subtitles_log
├── System
│   ├── ffmepg (after download)
│   ├── imageProcessor.php
│   ├── imageRead.php
│   ├── subtitleProcessor.php
│   ├── subtitleRead.php
│   ├── Uploader.php
│   ├── videoProcessor.php
│   └── videoRead.php
├── .Temp
│   └── .temp_folder
└── Videos
    └── .videos_log

6 directories, 17 files
```

## Log System

Two type of log exits. 
1. Main log `.log`
2. Sub logs like `.images_log`, `.videos_log`

### Main Log
Main Log will store every post request logs with datetime + ip

### Sub logs
Sub logs will only store upload logs of that specific fileid + datetime.

## Rest API

### Read

To access exits files just have send GET request with FileID.
If file exits then it will show or header to that file.

Ex- `{Path}/Storage/{FILEID}` or `{Path}/Storage?file={FILEID}`

### Write

To upload new files just have send POST request with `file`.
If Authentication exits then also have to send `key` with file POST request.

After successfully upload and processoring it will return status + file ids.
And also create a log.

### Delete

To Delete exits files just have to send Delete request with FileID (GET/POST);
If file exits then it will delete that file.

## Processors

### Image Processor

It will processor image file after uploading done. it will compress image by set level and change file type by set type.
It only support 'image/jpeg','image/png','image/gif' image files. Default Processor set to compress file 50% & type jpeg.
If you want to change then `*/System/imageProcessor.php` just find and edit it as your wish.
```php
$this->setting = array(
	'directory' => 'Images', // directory file compressed output
	'file_type' => array( // file format allowed
		'image/jpeg',
		'image/png',
		'image/gif'
	),
	'c_type' => 'jpeg',
	'level' => '5'
);
```

### Video Processor

It will processor/Encode video file after uploading done. it will create different encoding by set encoding and change file type by set type.
It only support 'video/mp4','video/mkv','video/avi' video files. Default Processor set to high(720p),mid(420p),low(360p) file & type always .mp4.
If you want to change then `*/System/videoProcessor.php` just find and edit it as your wish.
```php
$this->setting = array(
	'library' => 'System/ffmpeg/ffmpeg', // Full Address
	'directory' => 'Videos', // directory file compressed output
	'encode' => array(// name => resolution bitrates ffmpeg commands
		"high" => '-s hd720 -b:v 5000K -bufsize 5000K -b:a 192K',
		"midle" => '-s hd480 -b:v 2500K -bufsize 2500K -b:a 128K',
		"low" => '-s 480x360 -b:v 1000K -bufsize 1000K -b:a 128K'
	)
);
```

Thank You Happy Coding 🤝.