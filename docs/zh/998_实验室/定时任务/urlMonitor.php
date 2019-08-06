<?php
/**
 * 发送对账提醒邮件脚本，定时每天上午发送邮件
 */
require_once __DIR__ . '/../../../../vendor/autoload.php';

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

$application = new Application();

$application->add(new class() extends Command
{
    protected static $defaultName = 'urlMonitor';

    public $mails = [
        '达威' => 'dawei@juewei.com',
        '李洋' => 'liyang@juewei.com',
    ];

    public $cc = [
        '达威' => 'dawei@juewei.com',
    ];

    function configure()
    {
        $this
            ->setDescription('url 监控');
        $this
            ->addArgument('password', InputArgument::REQUIRED, 'The password of the email user.');
    }

    function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln($this->send($input->getArgument('password')));
    }
    function send($password = '')
    {
        try {
            $url = 'https://test-cdn-wap.juewei.com/m/shop/static/css/goodslist.c9a2d089ddd79fc24120d94bf8f2a40d.css';
            $code = 200;
            $c = curl_init();
            curl_setopt($c, CURLOPT_URL, $url);
            curl_setopt($c, CURLOPT_TIMEOUT, 200);
            curl_setopt($c, CURLOPT_HEADER, false);
            curl_setopt($c, CURLOPT_NOBODY, true);
            curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($c, CURLOPT_FOLLOWLOCATION, false);
            curl_setopt($c, CURLOPT_CUSTOMREQUEST, 'GET');
            curl_setopt($c, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($c, CURLOPT_SSL_VERIFYHOST, 0);
            $res = curl_exec($c);
            var_dump($res);
            $error = curl_error($c);
            $code = curl_getinfo($c, CURLINFO_HTTP_CODE);
            curl_close($c);
        } catch (\Throwable $th) {
            echo "Throwable" . PHP_EOL;
            $code = 100;
            var_dump($th->getMessage());
        }

        if ($code == 200) {
            return false;
        }

        $str = "URL:[ $url ]" . "<br/ >";
        $str .= "HTTP CODE: [ $code ]" . "<br/ >";
        $str .= "ERROR: [ $error ]" . "<br/ >";
        $transport = (new Swift_SmtpTransport('smtp.juewei.com', 80))
            ->setUsername('dawei@juewei.com')
            ->setPassword($password)
        ;
        // Create the Mailer using your created Transport
        $mailer = new Swift_Mailer($transport);

        // Create a message
        $message = (new Swift_Message('url 监控 HTTP CODE ' . $code))
            ->setFrom(['dawei@juewei.com']);
        $message->setTo($this->getTo(true));
        $message->setCc($this->getCc(true));
        $message->setBody($this->body($str), 'text/html');
        // Send the message
        $result = $mailer->send($message);
        return 'send:' . $result;
    }

    function getTo($s = false)
    {
        if ($s === false) {
            return ['dawei@juewei.com'];
        }
        return array_values($this->mails);
    }

    function getCc($s = false)
    {
        if ($s === false) {
            return [];
        }
        return array_values($this->cc);
    }

    function body($str = '')
    {
        $sign = $this->sign();
        $str = <<<EOT
<html>
<body>
{$str}
<div style="clear:both;"><br></div>
<div style="clear:both;"><br></div>
{$sign}
</body>
</html>
EOT;
        return $str;
    }

    function sign()
    {
        /*
        $str = <<<EOT
        <div style="clear:both;"><div style="margin:.0px;padding:.0px;border:.0px;outline:.0px;text-align:start;text-indent:.0px;widows:1;line-height:20.0px;"><div style="margin:.0px;padding:.0px;border:.0px;outline:.0px;color:#000000;font-family:Tahoma,Arial;font-size:14.0px;font-style:normal;font-variant-ligatures:normal;font-variant-caps:normal;font-weight:400;text-align:start;text-indent:.0px;text-transform:none;widows:2;text-decoration-style:initial;text-decoration-color:initial;clear:both;"><div style="margin:.0px;padding:.0px;border:.0px;outline:.0px;color:#000000;font-family:微软雅黑;font-size:14.0px;font-style:normal;font-variant:normal;font-weight:normal;line-height:21.0px;text-align:start;text-indent:.0px;text-transform:none;widows:1;"><div style="margin:.0px;padding:.0px;border:.0px;outline:.0px;line-height:20.0px;"><strong style="font-family:simhei;font-size:13.0px;background-color:window;"><span style="margin:.0px;padding:.0px;border:.0px;outline:.0px;font-size:16.0px;color:#808080;">祝好！&nbsp;</span></strong></div></div></div><div style="margin:.0px;padding:.0px;border:.0px;outline:.0px;color:#000000;font-family:Tahoma,Arial;font-size:14.0px;font-style:normal;font-variant-ligatures:normal;font-variant-caps:normal;font-weight:400;text-align:start;text-indent:.0px;text-transform:none;widows:2;text-decoration-style:initial;text-decoration-color:initial;clear:both;"><div style="margin:.0px;padding:.0px;border:.0px;outline:.0px;color:#000000;font-family:Tahoma,Arial;font-size:14.0px;font-style:normal;font-variant:normal;font-weight:normal;line-height:23.3px;text-align:start;text-indent:.0px;text-transform:none;widows:1;clear:both;"><div style="margin:.0px;padding:.0px;border:.0px;outline:.0px;text-align:start;text-indent:.0px;widows:1;line-height:20.0px;"><br></div></div></div><div style="margin:.0px;padding:.0px;border:.0px;outline:.0px;color:#000000;font-family:Tahoma,Arial;font-size:14.0px;font-style:normal;font-variant-ligatures:normal;font-variant-caps:normal;font-weight:400;text-align:start;text-indent:.0px;text-transform:none;widows:2;text-decoration-style:initial;text-decoration-color:initial;clear:both;"><div style="margin:.0px;padding:.0px;border:.0px;outline:.0px;color:#000000;font-family:Tahoma,Arial;font-size:14.0px;font-style:normal;font-variant:normal;font-weight:normal;line-height:23.3px;text-align:start;text-indent:.0px;text-transform:none;widows:1;clear:both;"><div style="margin:.0px;padding:.0px;border:.0px;outline:.0px;text-align:start;text-indent:.0px;widows:1;line-height:20.0px;"><span style="margin:.0px;padding:.0px;border:.0px;outline:.0px;color:#808080;font-family:微软雅黑;font-size:18.0px;font-weight:bold;">致力打造一流特色美食平台</span><br></div></div></div><div style="margin:.0px;padding:.0px;border:.0px;outline:.0px;color:#000000;font-family:Tahoma,Arial;font-size:14.0px;font-style:normal;font-variant-ligatures:normal;font-variant-caps:normal;font-weight:400;text-align:start;text-indent:.0px;text-transform:none;widows:2;text-decoration-style:initial;text-decoration-color:initial;clear:both;"><div style="margin:.0px;padding:.0px;border:.0px;outline:.0px;color:#000000;font-family:Tahoma,Arial;font-size:14.0px;font-style:normal;font-variant:normal;font-weight:normal;line-height:23.3px;text-align:start;text-indent:.0px;text-transform:none;widows:1;clear:both;"><div style="margin:.0px;padding:.0px;border:.0px;outline:.0px;text-align:start;text-indent:.0px;widows:1;line-height:20.0px;"><span style="margin:.0px;padding:.0px;border:.0px;outline:.0px;font-family:微软雅黑;font-style:normal;font-variant:normal;text-transform:none;font-size:12.0px;color:#7f7f7f;font-weight:bold;">————————————————————&nbsp; &nbsp;&nbsp;</span></div></div></div></div></div><div style="clear:both;"><div style="margin:.0px;padding:.0px;border:.0px;outline:.0px;text-align:start;text-indent:.0px;widows:1;line-height:20.0px;"><span style="margin:.0px;padding:.0px;border:.0px;outline:.0px;color:#000000;font-family:微软雅黑;font-size:14.0px;font-style:normal;font-variant:normal;font-weight:normal;text-transform:none;"><img height="60" src="http://mail.juewei.com/attachment/downloadex?ri=%2Falimail%2FinternalLinks%2FrefreshToken&o=1&et=normal&f=92092401-2057-4364-9adf-3169d9eba285&e=dawei%40juewei.com&n=InsertPic_8EF3.jpg&m=8_0%3ADzzzzyXoZah%24---.DgoxATu&ext=jpg" width="245"></span><br></div><div style="margin:.0px;padding:.0px;border:.0px;outline:.0px;text-align:start;text-indent:.0px;widows:1;line-height:20.0px;"><span style="margin:.0px;padding:.0px;border:.0px;outline:.0px;color:#000000;font-family:微软雅黑;font-style:normal;font-variant:normal;font-weight:normal;text-transform:none;font-size:15.0px;"><strong><span style="margin:.0px;padding:.0px;border:.0px;outline:.0px;color:#808080;">达威&nbsp;</span></strong></span></div><div style="margin:.0px;padding:.0px;border:.0px;outline:.0px;text-align:start;text-indent:.0px;widows:1;line-height:20.0px;"><span style="margin:.0px;padding:.0px;border:.0px;outline:.0px;font-family:微软雅黑;font-style:normal;font-variant:normal;font-weight:normal;text-transform:none;font-size:12.0px;color:#808080;">地址：北京市朝阳区紫檀大厦6层</span></div><div style="margin:.0px;padding:.0px;border:.0px;outline:.0px;text-align:start;text-indent:.0px;widows:1;line-height:20.0px;"><span style="margin:.0px;padding:.0px;border:.0px;outline:.0px;font-family:微软雅黑;font-style:normal;font-variant:normal;font-weight:normal;text-transform:none;font-size:12.0px;color:#808080;">手机：86-13001979905</span></div><div style="margin:.0px;padding:.0px;border:.0px;outline:.0px;text-align:start;text-indent:.0px;widows:1;line-height:20.0px;"><span style="margin:.0px;padding:.0px;border:.0px;outline:.0px;font-family:微软雅黑;font-style:normal;font-variant:normal;font-weight:normal;text-transform:none;font-size:12.0px;color:#808080;">邮箱：dawei@juewei.com</span></div><div style="margin:.0px;padding:.0px;border:.0px;outline:.0px;text-align:start;text-indent:.0px;widows:1;line-height:20.0px;"><span style="margin:.0px;padding:.0px;border:.0px;outline:.0px;font-family:微软雅黑;font-style:normal;font-variant:normal;font-weight:normal;text-transform:none;font-size:12.0px;color:#808080;background-color:window;">平台：www.juewei.cn</span></div></div>
        EOT;
         */
        $img = $this->signImg();
        $str = <<<EOT
<div style="clear:both;"><div style="margin:.0px;padding:.0px;border:.0px;outline:.0px;text-align:start;text-indent:.0px;widows:1;line-height:20.0px;"><div style="margin:.0px;padding:.0px;border:.0px;outline:.0px;color:#000000;font-family:Tahoma,Arial;font-size:14.0px;font-style:normal;font-variant-ligatures:normal;font-variant-caps:normal;font-weight:400;text-align:start;text-indent:.0px;text-transform:none;widows:2;text-decoration-style:initial;text-decoration-color:initial;clear:both;"><div style="margin:.0px;padding:.0px;border:.0px;outline:.0px;color:#000000;font-family:微软雅黑;font-size:14.0px;font-style:normal;font-variant:normal;font-weight:normal;line-height:21.0px;text-align:start;text-indent:.0px;text-transform:none;widows:1;"><div style="margin:.0px;padding:.0px;border:.0px;outline:.0px;line-height:20.0px;"><strong style="font-family:simhei;font-size:13.0px;background-color:window;"><span style="margin:.0px;padding:.0px;border:.0px;outline:.0px;font-size:16.0px;color:#808080;">祝好！&nbsp;</span></strong></div></div></div><div style="margin:.0px;padding:.0px;border:.0px;outline:.0px;color:#000000;font-family:Tahoma,Arial;font-size:14.0px;font-style:normal;font-variant-ligatures:normal;font-variant-caps:normal;font-weight:400;text-align:start;text-indent:.0px;text-transform:none;widows:2;text-decoration-style:initial;text-decoration-color:initial;clear:both;"><div style="margin:.0px;padding:.0px;border:.0px;outline:.0px;color:#000000;font-family:Tahoma,Arial;font-size:14.0px;font-style:normal;font-variant:normal;font-weight:normal;line-height:23.3px;text-align:start;text-indent:.0px;text-transform:none;widows:1;clear:both;"><div style="margin:.0px;padding:.0px;border:.0px;outline:.0px;text-align:start;text-indent:.0px;widows:1;line-height:20.0px;"><br></div></div></div><div style="margin:.0px;padding:.0px;border:.0px;outline:.0px;color:#000000;font-family:Tahoma,Arial;font-size:14.0px;font-style:normal;font-variant-ligatures:normal;font-variant-caps:normal;font-weight:400;text-align:start;text-indent:.0px;text-transform:none;widows:2;text-decoration-style:initial;text-decoration-color:initial;clear:both;"><div style="margin:.0px;padding:.0px;border:.0px;outline:.0px;color:#000000;font-family:Tahoma,Arial;font-size:14.0px;font-style:normal;font-variant:normal;font-weight:normal;line-height:23.3px;text-align:start;text-indent:.0px;text-transform:none;widows:1;clear:both;"><div style="margin:.0px;padding:.0px;border:.0px;outline:.0px;text-align:start;text-indent:.0px;widows:1;line-height:20.0px;"><span style="margin:.0px;padding:.0px;border:.0px;outline:.0px;color:#808080;font-family:微软雅黑;font-size:18.0px;font-weight:bold;">致力打造一流特色美食平台</span><br></div></div></div><div style="margin:.0px;padding:.0px;border:.0px;outline:.0px;color:#000000;font-family:Tahoma,Arial;font-size:14.0px;font-style:normal;font-variant-ligatures:normal;font-variant-caps:normal;font-weight:400;text-align:start;text-indent:.0px;text-transform:none;widows:2;text-decoration-style:initial;text-decoration-color:initial;clear:both;"><div style="margin:.0px;padding:.0px;border:.0px;outline:.0px;color:#000000;font-family:Tahoma,Arial;font-size:14.0px;font-style:normal;font-variant:normal;font-weight:normal;line-height:23.3px;text-align:start;text-indent:.0px;text-transform:none;widows:1;clear:both;"><div style="margin:.0px;padding:.0px;border:.0px;outline:.0px;text-align:start;text-indent:.0px;widows:1;line-height:20.0px;"><span style="margin:.0px;padding:.0px;border:.0px;outline:.0px;font-family:微软雅黑;font-style:normal;font-variant:normal;text-transform:none;font-size:12.0px;color:#7f7f7f;font-weight:bold;">————————————————————&nbsp; &nbsp;&nbsp;</span></div></div></div></div></div><div style="clear:both;"><div style="margin:.0px;padding:.0px;border:.0px;outline:.0px;text-align:start;text-indent:.0px;widows:1;line-height:20.0px;"><span style="margin:.0px;padding:.0px;border:.0px;outline:.0px;color:#000000;font-family:微软雅黑;font-size:14.0px;font-style:normal;font-variant:normal;font-weight:normal;text-transform:none;"><img height="60" src="{$img}" width="245"></span><br></div><div style="margin:.0px;padding:.0px;border:.0px;outline:.0px;text-align:start;text-indent:.0px;widows:1;line-height:20.0px;"><span style="margin:.0px;padding:.0px;border:.0px;outline:.0px;color:#000000;font-family:微软雅黑;font-style:normal;font-variant:normal;font-weight:normal;text-transform:none;font-size:15.0px;"><strong><span style="margin:.0px;padding:.0px;border:.0px;outline:.0px;color:#808080;">达威&nbsp;</span></strong></span></div><div style="margin:.0px;padding:.0px;border:.0px;outline:.0px;text-align:start;text-indent:.0px;widows:1;line-height:20.0px;"><span style="margin:.0px;padding:.0px;border:.0px;outline:.0px;font-family:微软雅黑;font-style:normal;font-variant:normal;font-weight:normal;text-transform:none;font-size:12.0px;color:#808080;">地址：北京市朝阳区紫檀大厦6层</span></div><div style="margin:.0px;padding:.0px;border:.0px;outline:.0px;text-align:start;text-indent:.0px;widows:1;line-height:20.0px;"><span style="margin:.0px;padding:.0px;border:.0px;outline:.0px;font-family:微软雅黑;font-style:normal;font-variant:normal;font-weight:normal;text-transform:none;font-size:12.0px;color:#808080;">手机：86-13001979905</span></div><div style="margin:.0px;padding:.0px;border:.0px;outline:.0px;text-align:start;text-indent:.0px;widows:1;line-height:20.0px;"><span style="margin:.0px;padding:.0px;border:.0px;outline:.0px;font-family:微软雅黑;font-style:normal;font-variant:normal;font-weight:normal;text-transform:none;font-size:12.0px;color:#808080;">邮箱：dawei@juewei.com</span></div><div style="margin:.0px;padding:.0px;border:.0px;outline:.0px;text-align:start;text-indent:.0px;widows:1;line-height:20.0px;"><span style="margin:.0px;padding:.0px;border:.0px;outline:.0px;font-family:微软雅黑;font-style:normal;font-variant:normal;font-weight:normal;text-transform:none;font-size:12.0px;color:#808080;background-color:window;">平台：www.juewei.cn</span></div></div>
EOT;
        return $str;
    }
    function signImg()
    {
        return "data:image/png;base64,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";
    }
});

$application->run();
