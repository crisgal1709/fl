<?php

namespace FL;
use Exception;
use Illuminate\Support\Str;

class FileResponse extends Response
{
	private $path;
	private $file;

	function __construct($path = '', $code = 200)
	{
		$this->setFile($path);
	}

	private function setFile($file)
	{
		$this->path = $file;

		if (empty($file) || !is_readable($file)) {
			throw new Exception("File doesn`t exists");
		}

		$this->file = function() use($file){
			echo file_get_contents($file);
		};
	}

	public function send()
	{
		$p = $this->path;
		$size = filesize($p);
		$myme = finfo_file(finfo_open(FILEINFO_MIME_TYPE), $p);
		$name = pathinfo($p, PATHINFO_FILENAME);
		$ext = pathinfo($p, PATHINFO_EXTENSION);

		$s = str_replace('%', '', Str::ascii($name));
		$disposition = sprintf('%s; filename="%s"', 'attachment', str_replace('"', '\\"', $name . '.' . $ext));

		$this->setHeader('Content-Disposition', $disposition);

		parent::sendHeaders();
		call_user_func($this->file);
	}
}
