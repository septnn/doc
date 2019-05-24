# phpDoc(PHP Documentor) 标签使用指南

phpDocumentor 标签与 JavaDoc 很相似。只有位于文本块(DocBlock)新行开头的标签才会被解析，并且在单行范围内，@ character后面的文本可以保持任意长度。例如：

```php
<?php
/**
 * tags demonstration
 * @author this tag is parsed, but this @version tag is ignored
 * @version 1.0 this version tag is parsed
 */
?>
```

任何 phpDocumentor 无法辨识的标签将不会被解析，但会将其以文本形式作为文本块的一部分进行输出。以下是 phpDocumentor 支持的标签列表

- @access
- @author
- @copyright
- @deprecated
- @example
- @ignore
- @internal
- @link
- @method
- @package
- @param
- @return
- @see
- @since
- @tutorial
- @version

此以上常规标签(regular tags)外，phpDocumentor 中还有一种行内标签(inline tags)。与常规标签不同，行内标签不要求出现在新行的开头，而可以出现在文本流中。以下几个常用的行内标签：

- inline {@internal}
- inline {@inheritdoc}
- inline {@link}

## @access

@access 控制 phpDocumentor 对元素的文档化操作

如果 @access 被设置为 private，只有在命令行下使用 --parseprivate 参数时才会对应用此标签属性的元素进行文档化

```php
<?php
/**
 * funciton func1, public access is assumed
 */
function func1()
{
}

/**
 * function func2, access is private, will not be documented
 * @access private
 */
function func2()
{
}

/**
 * This is possible, but redundant. An element has @access public by default
 * @access public
 */
class class1
{
  /**
   * all text in this DocBlock will be ignored, unless command-line switch or 
   * setting in a user INI file enable documenting of private elments
   * @access private
   */
  var $private_var;

  /**
   * Protected is allowed, but does absolutely nothing. Use it to inform users
   * that an element should only be referenced by this and child classes,
   * and not directly
   * @access protected
   */

  /**
   * This funciton is documented
   */
  function publicmethod()
  {
  }
}
?>
```

## @abstract

abstract 有“抽象”的意思，使用 @abstract 声明函数或者类(class)，或者是必须经由子类重写才有效的抽象类

@abstract 标签只在有 abstract 关键词的 PHP4 和 PHP5 中有效

代码示例

```php
<?php
/**
 * Example of basic @abstract usage in a class
 * Use this if every single element of the class is abstract 
 * @abstract
 */
class myabstractclass
{
  function function1($baz)
  {
    //...
  }

  function function2()
  {
    //...
  }
}
?>
```

## @author
　
　@author 标签可以应用于一些可拥有“作者”属性的元素，如全局变量(global variable)、引用(include)、常量(constant)、函数(function)、定义(define)、类(class)、变量(variable)、方法(method)、页面(page)。phpDocumentor 还会尝试解析尖括号中的文本，如果解析成email成功，则会在生成的文档中生成一个 mailto 链接。

　　v1.2 新特性：@author 属性可以从父类直接遗传到子类，详情请参考 inline {inheritdoc}
```php
<?php
/**
 * Page-Level DocBlock example.
 * displays as Gregory Beaver<u>cellog@php.net</u>
 * , where underlined text is a "mailto:cellog@php.net"
 * @author Gregory Beaver <cellog@php.net>
 */
/**
 * funciton datafunction 
 * another contributor authored this function 
 * @author Joe Shmoe
 */
fucntion dataFunction()
{
}
?>
```

## @category
　　@category 标签用户将多个包(packages)组织归类。这里定义的分类可以被 XML:DocBook/peardoc2 转换器直接调用，且可以被其它转换器使用。其它使用 phpDocumentor 打包的转换器会忽略这个分类，但这一情况在将来的版本里可能会有所改变。

　　在命令行下，可以使用 -dc, --defaultcategoryname 选项动态地指定分类

@category 应用示例：
```php
<?php
/**
 * Page-Level DocBlock
 * @package MyPackage
 * @category mycategory
 */

/**
 * @global array used for stuff
 */
function mine()
{
  global $baz;
  //...
}
?>
```
## @copyright

@copyright 可以应用于任何可以使用版权声明的元素，如全局变量(global variable)、引用(include)、常量(constant)、函数(function)、定义(define)、类(class)、变量(variable)、方法(method)、页面(page)。phpDocumentor 将会直接显示 @copyright 后台定义的文本串。

v1.2 新特性：@copyright 属性可以从父类直接遗传到子类，详情请参考 inline {inheritdoc}

@copyright 应用示例：
```php
<?php
/**
 * Page-Level DocBlock example.
 * @author Gregory Beaver <cellog@php.net>
 * @copyright Copyright (c) 2002, Gregory Beaver
 */
/**
 * function datafunciton
 */
function datafunction()
{
}
?>
```

## @deprecated

　　@deprecate 标签可以应用于那些已经废弃的版本或声明一些相关信息的元素，除了页面之外，@deprecated 可应用于全局变量(global variable)、引用(include)、常量(constant)、函数(function)、定义(define)、类(class)、变量 (variable)、方法(method)。phpDocumentor 将直接显示 @deprecated 标签后面的文本串

　　使用 @deprecated 标签，来告知用户那些不再被使用的元素。

@deprecated 应用示例：

```php
<?php
/**
 * @deprecated deprecated since version 2.0
 */
funciton uselessfunction()
{
}

/**
 * alse legal
 * @deprecated
 */
class stupidclass
{
}
?>
```

## @filesource
　　@filesource 标签只可作为文档级文本块(DocBlock)的应用，在其它地方使用此标签都将被忽略。phpDocumentor 解析当前文件的源代码，将其源代码进行语法高亮、添加行号，并且在生成的文档中添加它的链接。

　　@example 标签的意图与其相似，区别在于 @example 用于解析和创建链接到外部文件。

注意：@filesource 标签只在 PHP 4.3.0 中使用。

应用示例：

```php
<?php
/**
 * Contains serveral example classes that I want to parse but I
 * also want to show the full source
 * @package mypackage
 * @subpackage example
 * @filesource
 */
/**
 * This class does things
 * @package mypackage
 * @subpackage example
 */
class oneofmany extends mainclass
{
  //...
}
?>
```

## @final
　　@final 用于通告一个方法不能被子类覆写

@final 标签只能在使用 final 关键词的PHP4和PHP5中使用

应用示例：

```php
<?php
/**
 * example of basic @final usage in a class
 */
class myclass 
{
  /**
   * function should never be overridden
   * @final
   */
  function function1 ($baz) 
  {
    //...
  }

  function function2()
  {
    //..
  }
}
?>
```

### @global
@global 用法
@global datatype $globalvariablename
@global datatype description
phpDocumentor 通过在文本块中使用 @global 标签来执行全局变量的声明。为了支持以前的 @global 用法，有两种方法使用 @global 标签，即变量定义和函数说明。

phpDocument 不会自动解析任何 @global 标签，且每个文本块中只能使用一个 @global 标签。一个全局变量文本块必须在其它任何元素或文本块这前，否则则会出现错误。

datetype 可以是任意有效的PHP类型或者 "mixed"。

$varname 必须是与源文件中声明的全局变量一致（使用 @name 标签可以改变名称在文档中的显示）。

未完...

应用示例：

```php
<?php
/**
 * example of incorrect @global declaration #1
 * @global bool $GLOBALS['baz']
 * @author blahblah
 * @version -6
 */
include("file.ext");
// error - element encountered before global variable declaration, docblock will apply to this include!
$GLOBALS['baz'] = array('foo', 'bar');

/** 
 * example of incorrect @global declaration #2
 * @global parserElement $_Element
 */
/**
 * error - this DocBlock occurs before the global variable definition and will apply to the function,
 * ignoring the global variable completely
 */
$_Element = new parserElement;

function oopsie()
{
  //...
}

/**
 * example of correct @global declaration,
 * even with many irrelevant things in between
 * @global mixed $_GLOBALS["myvar"]
 */
// this is OK
if ($pre) 
{
  $thisstuff = 'is fine too';
}
$_GLOBALS["myvar"] = array("this" => 'works just fine');

/**
 * example of using @name with @global
 * the @name tag *must* have a $ in the name, or an error will be raised
 * @global array $GLOBALS['neato']
 * @name $neato
 */
$GLOBALS['neato'] = 'This variable\'s name is documented as $neato, and not as $GLOBALS[\'neato\'];
?>
Here's an example of documenting the use of a global variable in a function/method

<?php
/**
 * Used to showcase linking feature of function @global
 */
class test
{
}

/**
 * @global test $GLOBALS['baz'] 
 * @name $bar
 */
$GLOBALS['bar'] = new test

/**
 * example of basic @global usage in a function
 * assume global variables "$foo" and "$bar" are already documented
 * @global bool used to control the weather
 * @global test used to calculate the division tables
 * @param bool $baz 
 * @return mixed 
 */
function function1($baz)
{
  global $foo,$bar;
  // note that this also works as:
  // global $foo;
  // global $bar;
  if ($baz)
  {
    $a = 5;
  } else
  {
    $a = array(1,4);
  }
  return $a;
}
?>
```