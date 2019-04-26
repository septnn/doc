# Laravel 管道设计模式

最近正在阅读laravel框架的源码。

追踪到一个new Pipeline，简单看了一下代码。

感觉比较晦涩，所以下面详细的分析一下此逻辑并记录下来，如果有什么不对的地方请大家指出并纠正。

分割线

---

## 首先调通laravel，输出“welcome”。

## 代码追踪Pipeline

通过`index.php`代码追踪到`new Pipeline`，见另外一个laravel代码追踪逻辑结构图（略糟，凑活看）。

代码片段new，也是逻辑入口。
```php
# Kernel.php:148
	return (new Pipeline($this->app))
                    ->send($request)
                    ->through($this->app->shouldSkipMiddleware() ? [] : $this->middleware)
                    ->then($this->dispatchToRouter());
```
此处的主要逻辑是`then()`。

找到这个类，代码片段then，主要逻辑代码。
```php
# Pipeline.php父类:98|114
    public function then(Closure $destination)
    {
        $pipeline = array_reduce(
            array_reverse($this->pipes), $this->carry(), $this->prepareDestination($destination)
        );

        return $pipeline($this->passable);
    }
    protected function prepareDestination(Closure $destination)
    {
        return function ($passable) use ($destination) {
            return $destination($passable);
        };
    }
# Pipeline.php类:26
    protected function prepareDestination(Closure $destination)
    {
        return function ($passable) use ($destination) {
            try {
                return $destination($passable);
            } catch (Exception $e) {
                return $this->handleException($passable, $e);
            } catch (Throwable $e) {
                return $this->handleException($passable, new FatalThrowableError($e));
            }
        };
    }
```

分析`then()`：

先分析一下`array_reduce`函数（laravel优雅的使用？）。

官方文档：用回调函数迭代地将数组简化为单一的值。

也就是说，代码片段2经过转换应该同下代码，确实看着代码多一些：
```php
public function then(Closure $destination)
    {
    	$pipeline = $this->prepareDestination($destination);
        $carry = $this->carry();
        foreach(array_reverse($this->pipes) as $key => $value) {
            $pipeline = $carry($pipeline, $value);
        }

        return $pipeline($this->passable);
    }
```
让我们替代一下`pipeline`代码，执行一下看看效果。

替换执行完毕，看上去是正常的，可以正常的输出“welcome”。

## 继续分析then()：

替换后，代码迭代`array_reverse($this->pipes)`，调用`$carry`回调函数。`array_reverse`不说了，自行查阅。

迭代前，`$pipeline=$this->prepareDestination($destination);`，一个回调函数。这个回调函数干嘛的，我们一会再看。

第一次迭代，相当于`$carry($this->prepareDestination($destination), $value);`

紧接着`$carry`是什么呢？看代码片段`carry`：

```php
# Pipeline类:44
protected function carry()
    {
        return function ($stack, $pipe) {
            return function ($passable) use ($stack, $pipe) {
                try {
                    $slice = parent::carry();

                    $callable = $slice($stack, $pipe);

                    return $callable($passable);
                } catch (Exception $e) {
                    return $this->handleException($passable, $e);
                } catch (Throwable $e) {
                    return $this->handleException($passable, new FatalThrowableError($e));
                }
            };
        };
    }
# Pipeline父类:126
protected function carry()
    {
        return function ($stack, $pipe) {
            return function ($passable) use ($stack, $pipe) {
                if (is_callable($pipe)) {
                    // If the pipe is an instance of a Closure, we will just call it directly but
                    // otherwise we'll resolve the pipes out of the container and call it with
                    // the appropriate method and arguments, returning the results back out.
                    return $pipe($passable, $stack);
                } elseif (! is_object($pipe)) {
                    list($name, $parameters) = $this->parsePipeString($pipe);

                    // If the pipe is a string we will parse the string and resolve the class out
                    // of the dependency injection container. We can then build a callable and
                    // execute the pipe function giving in the parameters that are required.
                    $pipe = $this->getContainer()->make($name);

                    $parameters = array_merge([$passable, $stack], $parameters);
                } else {
                    // If the pipe is already an object we'll just make a callable and pass it to
                    // the pipe as-is. There is no need to do any extra parsing and formatting
                    // since the object we're given was already a fully instantiated object.
                    $parameters = [$passable, $stack];
                }

                $response = method_exists($pipe, $this->method)
                                ? $pipe->{$this->method}(...$parameters)
                                : $pipe(...$parameters);

                return $response instanceof Responsable
                            ? $response->toResponse($this->container->make(Request::class))
                            : $response;
            };
        };
    }
```
如果是回调函数，则返回。

如果是对象，则调用对象的`handle`方法，并把回调函数传进去。

什么意思呢，意思就是迭代已经定义好的一些类，并把对象`$request`和回调函数`$this->prepareDestination($destination)`，传到`handle()`函数里面。

`handle()`里面有两种情况：

1. `handle()`函数，返回回调函数`$this->prepareDestination($destination)`，并且把参数`$request`传到`$this->prepareDestination($request)`。

最终`$this->prepareDestination()`处理的`$request`，都是经过`$this->pipes`类处理后的`$request`。理解为beforeaction。



2. `handle()`函数，返回执行回调函数`$this->prepareDestination($destination)`，并且把参数`$request`传到`$this->prepareDestination($request)`。理解为afteraction。



有点绕，可能表达的不是很清楚。欢迎来讨论。
