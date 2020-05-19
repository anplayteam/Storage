<?php
class imageRead
{
    
    function __construct($file)
    {
        $filename = "Images/".basename($file);
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
            case "gif": $ctype="image/gif"; break;
            case "png": $ctype="image/png"; break;
            case "jpeg":
            case "jpg": $ctype="image/jpeg"; break;
            case "svg": $ctype="image/svg+xml"; break;
            default:
        }
        header('Content-type: ' . $ctype);
    }
    public function _render($filename)
    {
        readfile($filename);
    }
}