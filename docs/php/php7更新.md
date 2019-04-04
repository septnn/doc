#php7的更新

##下面列出一些，认为常用的新特性

 - ?? null合并运算符
 ```php
 $a = $_GET['a'] ?? 'default';
 echo $a;//default
 $x = $_GET['a'] ?? $_POST['b'] ?? 'default';
 echo $x;//default
 ```
 - define('ARR', ['a','b']);
 ```php
 define('ARRAY', ['a','b']);
 echo ARRAY[0];//a
 ```
 - 标量声明
 ```php
 declare(strict_types=1);
 function f(int $int) : ?array {//?代表允许返回null
     return [$int];
 }
 print_r(f(1));// array(1)
 function f2(int $int) : void {
     //no return
 }
 var_dump(f2(1));//null
 ```
 首先开启标量类型
 如果传入参数非int，报错Fatal error
 如果返回非array，报错Fatal error

 - 组合比较符
 ```php
 echo 1<=>1;//0
 echo 1<=>2;//1
 echo 2<=>1;//-1
 echo 'a'<=>'a'//0
 echo 'a'<=>'b'//1
 echo 'b'<=>'a'//-1
 ```

 - use 
 ```php
 use App\{ClassA,ClassB,ClassC as C};
 use function App\{afn,bfn,cfn};
 use const App\{A,B,C};
 ```

 - [其它详见](https://secure.php.net/manual/zh/migration70.new-features.php)