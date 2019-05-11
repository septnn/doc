<?php

require_once __DIR__ . '/../../../../vendor/autoload.php';
/**
 * 加密文件内容
 */
use Symfony\Component\Console\Application;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

use phpseclib\Crypt\AES;

$application = new Application();


$application->add(new class() extends Command{
    protected static $defaultName = 'encry';

    protected function configure()
    {
        $this
        ->setDescription('文件内容加密，加密密钥')
        ->setHelp('encryption.php help '.self::$defaultName);
        $this
        ->addArgument('type', InputArgument::REQUIRED, 'The encyption type[encrypt|decrypt].')
        ->addArgument('path', InputArgument::REQUIRED, 'The path of the email user.');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln($this->send($input->getArgument('type'), $input->getArgument('path')));
    }
    public function send($type, $path = '')
    {
        if(!file_exists($path)) {
            return '文件不存在'.PHP_EOL;
        }
        echo '源文件 : '.$path.PHP_EOL;

        $fun = $type;
        if(method_exists($this, $fun)) {
            $key = $this->getKey();
            echo 'key length : '.strlen($key).PHP_EOL;
            $seclib = new AES();
            return $this->$fun($path, $key, $seclib);
        }
        return '没有此方法';
    }
    /**
     * Undocumented function
     *
     * @param [type] $path  源文件
     * @param integer $key
     * @param [type] $seclib
     * @return void
     */
    public function encrypt($path, $key = 123456, $seclib)
    {
        // 获取源文件内容 // 文件命名规范 带有 .source
        $str = file_get_contents($path);
        $target_path = str_replace('.source','',$path);
        echo '生成的加密文件 : '.$target_path.PHP_EOL;

        $seclib->setKey($key);
        $encrypt = $seclib->encrypt($str);
        $str = base64_encode($encrypt);
        // 写入加密文件
        file_put_contents($target_path, $str);
        return '加密成功';
    }
    public function decrypt($path, $key = 123456, $seclib)
    {
        $target_path = str_replace('.source','',$path);
        $str = file_get_contents($target_path);
        $encrypt = base64_decode($str);
        $seclib->setKey($key);
        return $seclib->decrypt($encrypt);
    }

    public function getKey()
    {
        $key_file = __DIR__ . 'key';
        if(file_exists($key_file)) {
            return file_get_contents($key_file);
        }
        try {
            $res = openssl_pkey_new([
                'config' => 'C:\Users\JWD\Downloads\php-7.3.4\extras\ssl\openssl.cnf',
            ]);
            $pkey = openssl_pkey_get_details($res);
            $key = $pkey['key'];
        } catch (\Throwable $th) {
            var_dump($th->getMessage());
        }
        //file_put_contents($key_file, $key);
        return $key;
    }
});

$application->run();
