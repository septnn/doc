<?php
declare(strict_types=1);

/**
 * 执行类
 */
class Tohtml
{
	public $filename = 'resume/Resume';
	public $env;
	public $token;
	public $username;
	public $access_token = 'fa7cd9430600d788b58d006402d5b95c681365aa';
	
	function __construct()
	{
		$this->env();
		$this->getToken();
	}

	public function getToken()
	{
		global $argv;
		if($this->env) {
			if(!isset($argv[1])) {
				echo "Username is null\n";
				echo "Like php Tohtml.php username\n";
				exit;
			}
			$this->username = $argv[1];
		} else {
			if(!isset($_GET['username'])) {
				echo "Username is null\n";
				echo "Like /Tohtml.php?username=username\n";
				exit;
			}
			$this->username = $_GET['username'];
		}
		$this->token = $this->username.':'.$this->access_token;
	}

	public function getMd()
	{
		return file_get_contents('../'.$this->filename.'.md');
	}

	public function api($md)
	{
		$json = json_encode([
			'text' => $md,
			'mode' => 'markdown',
			'context' => 'github/gollum',
		]);

		$shell = "curl -u {$this->token}  https://api.github.com/markdown -d '{$json}'";
		$re = shell_exec($shell);
		return $re;
	}

	public function w($html)
	{
		$filename = '.Rresume.html';
		file_put_contents($filename, $html);
		return $filename;
	}

	public function css($html)
	{
		$css = <<<EOT
<!DOCTYPE html>
<html>
<head>
<title>MarkdownPad Document</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/github-markdown-css/2.10.0/github-markdown.min.css" integrity="sha256-Ndk1ry+oGNFEaXt4kxlW/SYLbxat1O0DhaDd+lob0SY=" crossorigin="anonymous" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
	.markdown-body {
		box-sizing: border-box;
		min-width: 200px;
		max-width: 980px;
		margin: 0 auto;
		padding: 0px;
		font-size: 14px;
	}
	.markdown-body blockquote, .markdown-body dl, .markdown-body ol, .markdown-body p, .markdown-body pre, .markdown-body table, .markdown-body ul {
	    margin-top: 0;
	    margin-bottom: 8px;
	}


	@media (max-width: 767px) {
		.markdown-body {
			padding: 15px;
		}
	}
</style>

</head>
<body>
	<article class="markdown-body">
EOT;
		$html = $css.$html;
		$css = <<<EOT
</article>
</body>
</html>		
EOT;
		$html .= $css;
		return $html;
		# code...
	}

	public function env()
	{
		// echo "Env is ".PHP_SAPI."\n";
		$this->env = (PHP_SAPI === 'cli' OR defined('STDIN'));
		return $this->env;
	}

	public function echo($html)
	{
		if($this->env) {
			echo "Done...\n";
			$filename = $this->w($html);
			echo "You need sz ".$filename."\n";
			exit;
		} else {
			echo $html;
			exit;
		}
	}

	public function run()
	{
		$md = $this->getMd();
		$html = $this->api($md);
		$html = $this->css($html);
		$this->echo($html);
	}
}

$m = new Tohtml();

$m->run();