<?php
/*
声明静态的类的成员和方法，使它不需要一个类的实例.一个static成员的声明不能通过一个类对象的实例来访问(尽管一个静态方法可以)。
静态声明必须在可见性声明之后。为了兼容PHP 4,如果没有可见性被声明，那么成员和方法将被当作是已经声明为public。
由于静态方法可以调用非对象实例，伪变量$this不可以在声明为静态的方法中使用。
事实上static方法调用形式在编译时被确定。当使用必须要声明的类名时，方法是完全标识和无继承规则的应用。当使用必须要声明的类名时，这种方法就被完全确认，而且没有使用继承的规则。
如果self已经被声明，那么self就被当前所属的类所解释。也不适用与继承规则。静态属性不能通过箭头操作符->.访问非静态方法，这将产生一个E_STRICT 级的警告。
例子 19-13. 静态成员的例子
PHP代码如下:
*/

class Foo
{   public static $my_static='foo';
    public function staticValue(){   return self::$my_static;   }
}
class Bar extends Foo
{   public function fooStatic(){   return parent::$my_static;   }
}
print Foo::$my_static."\n";
$foo = new Foo();
print $foo->staticValue()."\n";
print $foo->my_static."\n";// Undefined "Property" my_static
// $foo::my_static is not possible
print Bar::$my_static."\n";
$bar = new Bar();
print $bar->fooStatic()."\n";
?>


<?php
/*
例子 19-14.静态方法实例(Static method example)
PHP代码如下:

*/

class Foo
{   public static function aStaticMethod() {    }
}
Foo::aStaticMethod();
?>