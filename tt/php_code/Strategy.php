<?php
//PHP中的策略模式

//用户列表类和选择策略类

/*
UserList类是一组用户名的包装类。它实现的find方法，根据某个选择策略，返回一个用户子集。这些策略由IStrategy接口定义，这里包含两个实现：一个是随机选择，另外一个是选择指定名字之后的用户。运行这个例子，结果如下：

% php strategy.php
Array
(
   [0] => Jack
   [1] => Lori
   [2] => Megan
)
Array
(
   [0] => Andy
   [1] => Megan
)
%

测试代码分别使用两个策略运行用户列表类，并输出相应结果。第一个例子是查找排序在”J“之后的所有用户，结果为：Jack, Lori, and Megan。第二个例子是随机选择用户，每次运行的结果可能都不一样，这次输出的是Andy和Megan。

在复杂的数据管理系统或数据处理系统中，需要能够灵活地过滤、查找和处理数据，这时候策略模式就能够提供所需的灵活性。
*/

interface IStrategy {
    function filter($record);
}

class FindAfterStrategy implements IStrategy {//查找某个名字之后的用户
    private $_name;

    public function __construct($name) {
        $this->_name = $name;
    }

    public function filter($record) {
        return strcmp($this->_name, $record) <= 0;
    }
}

class RandomStrategy implements IStrategy {//随机选择用户
    public function filter($record) {
        return rand(0, 1) >= 0.5;
    }
}

class UserList {
    private $_list = array();

    public function __construct($names) {
        if ($names != null) {
            foreach($names as $name) {
                $this->_list []= $name;
            }
        }
    }

    public function add($name) {
        $this->_list []= $name;
    }

    public function find($filter) {
        $recs = array();
        foreach($this->_list as $user) {
            if ($filter->filter($user))
                $recs []= $user;
        }
        return $recs;
    }
}

$ul = new UserList(array("Andy", "Jack", "Lori", "Megan"));
$f1 = $ul->find( new FindAfterStrategy("J") );
print_r($f1);

$f2 = $ul->find(new RandomStrategy());
print_r($f2);

?>