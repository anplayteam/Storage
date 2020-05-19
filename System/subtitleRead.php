<?php
class subtitleRead
{
    
    function __construct($file)
    {
        $filename = "Subtitles/".basename($file);
        if (file_exists($filename)) {
            $file_extension = strtolower(substr(strrchr($filename,"."),1));
            $this->_type($file_extension);$this->_render($filename);
        }
        else {
            http_response_code(404);
            header('HTTP/1.0 404 Not Found');
            exit;
        }
    }
    public function _type($file_extension)
    {
       switch( $file_extension ) {
            case "srt": $ctype="application/x-subrip"; break;
            case "sub": $ctype="text/x-microdvd"; break;
            case "cap": $ctype="application/vnd.tcpdump.pcap"; break;
            case "smi": $ctype="application/smil+xml"; break;
            case "sami": $ctype="application/x-sami"; break;
            case "rt": $ctype="text/vnd.rn-realtext"; break;
            case "sbv":
            case "vtt": $ctype="text/vtt"; break;
            default:
        }
        header('Content-type: ' . $ctype);
    }
    public function _render($filename)
    {
        readfile($filename);
    }
}