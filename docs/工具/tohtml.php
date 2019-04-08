<?php
declare(strict_types=1);

$username = '';
$token = 'fa7cd9430600d788b58d006402d5b95c681365aa';
$url = 'https://api.github.com/markdown';
$author = [
	'User-Agent: Septnn',
	'Content-Type:text/plain',
	'Authorization: Basic '.base64_encode($username.':'.$token),
];
$json = json_encode([
	'text' => file_get_contents('../1_简历/简历.md'),
	'mode' => 'markdown',
	'context' => 'github/gollum',
]);

$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($curl, CURLOPT_HTTPHEADER, $author);
curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
curl_setopt($curl, CURLOPT_POSTFIELDS, $json);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);

$res = curl_exec($curl);

if(curl_errno($curl)) {
	$error = curl_error($curl);
	var_dump($error);exit;
}
curl_close($curl);


file_put_contents('简历.html', '
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
'.$res.'
</article>
</body>
</html>');
exit;