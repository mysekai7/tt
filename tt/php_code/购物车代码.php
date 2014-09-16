<?php
/*
应一网友的要求，将其发的一段购物车类代码进行简单的分析，希望对需要的新手们有所帮助~
本人也是新手，分析讲解的同时也相当于学习了，不对的地方大虾们赶紧指正哈，呵呵^^
代码讲解分析： Linvo. 2008-2-15
*/

//购物车session的产生代码
if(!$session && !$scid) {
/*
session用来区别每一个购物车，相当于每个车的身份证号；
scid只用来标识一个购物车id号，可以看做是每个车的名字；
当该购物车的id和session值两者都不存在时，就产生一个新购物车
*/
    $session = md5(uniqid(rand()));
    /*
    产生一个唯一的购物车session号
    rand()先产生个随机数，uniqid()再在该随机数的基础上产生一个独一无二的字符串，最后对该字符串进行md5
    */
    SetCookie(scid, $session, time() + 14400);
    /*
    设置该购物车cookie
    变量名：scid（不知到这里是不是少了一个$号呢？）
    变量值：$session
    有效时间：当前时间+14400秒（4小时内）
    关于setcookie函数的详细用法，大家还是参看php手册吧~
    */
}


class Cart { //开始购物车类
    function check_item($table, $session, $product) {
    /*
    查验物品(表名，session，物品)
    */

        $query = SELECT * FROM $table WHERE session='$session' AND product='$product' ;
        /*
        看一看'表'里该'购物车'中有没有该'产品'
        即，该产品有没有已经放入购物车
        */

        $result = mysql_query($query);
        if(!$result) {
            return 0;
        }
        /*
        查询失败
        */

        $numRows = mysql_num_rows($result);
        if($numRows == 0) {
            return 0;
        /*
        若没有找到，则返回0
        */
        } else {
            $row = mysql_fetch_object($result);
            return $row->quantity;
            /*
            若找到，则返回该物品数量
            这里有必要解释一下mysql_fetch_object函数（下面还会用到）：
            【mysql_fetch_object() 和 mysql_fetch_array() 类似，只有一点区别 - 返回一个对象而不是数组。】
            上面这句话摘自php手册，说得应该很明白了吧~
            简单的说就是，取一条记录中的某个字段，应该用“->”而不是像数组一样用下标
            */
        }
    }

    function add_item($table, $session, $product, $quantity) {
    /*
    添加新物品(表名，session，物品，数量)
    */
        $qty = $this->check_item($table, $session, $product);
        /*
        调用上面那个函数，先检查该类物品有没有已经放入车中
        */

        if($qty == 0) {
            $query = INSERT INTO $table (session, product, quantity) VALUES ;
            $query .= ('$session', '$product', '$quantity') ;
            mysql_query($query);
            /*若车中没有，则像车中添加该物品*/
        } else {
            $quantity += $qty; //若有，则在原有基础上增加数量
            $query = UPDATE $table SET quantity='$quantity' WHERE session='$session' AND ;
            $query .= product='$product' ;
            mysql_query($query);
            /*
            并修改数据库
            */
        }
    }

    function delete_item($table, $session, $product) {
    /*
    删除物品(表名，session，物品)
    */
        $query = DELETE FROM $table WHERE session='$session' AND product='$product' ;
        mysql_query($query);
        /*
        删除该购物车中该类物品
        */
    }

    function modify_quantity($table, $session, $product, $quantity) {
    /*
    修改物品数量(表名，session，物品，数量)
    */
        $query = UPDATE $table SET quantity='$quantity' WHERE session='$session' ;
        $query .= AND product='$product' ;
        mysql_query($query);
        /*
        将该物品数量修改为参数中的值
        */
    }

    function clear_cart($table, $session) {
    /*
    清空购物车（没什么好说）
    */
        $query = DELETE FROM $table WHERE session='$session' ;
        mysql_query($query);
    }

    function cart_total($table, $session) {
    /*
    车中物品总价
    */
        $query = SELECT * FROM $table WHERE session='$session' ;
        $result = mysql_query($query);
        /*
        先把车中所有物品取出
        */

        if(mysql_num_rows($result) > 0) {
            while($row = mysql_fetch_object($result)) {
            /*
            如果物品数量>0个，则逐个判断价格并计算
            */

                $query = SELECT price FROM inventory WHERE product='$row->product' ;
                $invResult = mysql_query($query);
                /*
                从inventory（库存）表中查找该物品的价格
                */

                $row_price = mysql_fetch_object($invResult);
                $total += ($row_price->price * $row->quantity);
                /*
                总价 += 该物品价格 * 该物品数量
                （ 大家应该能看明白吧:) ）
                */
            }

        }
        return $total; //返回总价钱
    }


    function display_contents($table, $session) {
    /*
    获取关于车中所有物品的详细信息
    */
        $count = 0;
        /*
        物品数量计数
        注意，该变量不仅仅为了对物品数量进行统计，更重要的是，它将作为返回值数组中的下标，用来区别每一个物品！
        */

        $query = SELECT * FROM $table WHERE session='$session' ORDER BY id ;
        $result = mysql_query($query);
        /*
        先取出车中所有物品
        */

        while($row = mysql_fetch_object($result)) {
        /*
        分别对每一个物品进行取详细信息
        */

            $query = SELECT * FROM inventory WHERE product='$row->product' ;
            $result_inv = mysql_query($query);
            /*
            从inventory（库存）表中查找该物品的相关信息
            */

            $row_inventory = mysql_fetch_object($result_inv);
            $contents[product][$count] = $row_inventory->product;
            $contents[price][$count] = $row_inventory->price;
            $contents[quantity][$count] = $row->quantity;
            $contents[total][$count] = ($row_inventory->price * $row->quantity);
            $contents[description][$count] = $row_inventory->description;
            /*
            把所有关于该物品的详细信息放入$contents数组
            $contents是一个二维数组
            第一组下标是区别每个物品各个不同的信息（如物品名，价钱，数量等等）
            第二组下标是区别不同的物品（这就是前面定义的$count变量的作用）
            */
            $count++; //物品数量加一（即下一个物品）
        }
        $total = $this->cart_total($table, $session);
        $contents[final] = $total;
        /*
        同时调用上面那个cart_total函数，计算下总价钱
        并放入$contents数组中
        */

        return $contents;
        /*
        将该数组返回
        */
    }


    function num_items($table, $session) {
    /*
    返回物品种类总数（也就是说，两个相同的东西算一种    好像是废话- -!）
    */
        $query = SELECT * FROM $table WHERE session='$session' ;
        $result = mysql_query($query);
        $num_rows = mysql_num_rows($result);
        return $num_rows;
        /*
        取出车中所有物品，获取该操作影响的数据库行数，即物品总数（没什么好说的）
        */
    }

    function quant_items($table, $session) {
    /*
    返回所有物品总数（也就是说，两个相同的东西也算两个物品   - -#）
    */
        $quant = 0;// 物品总量
        $query = SELECT * FROM $table WHERE session='$session' ;
        $result = mysql_query($query);
        while($row = mysql_fetch_object($result)) {
        /*
        把每种物品逐个取出
        */
            $quant += $row->quantity; //该物品数量加到总量里去
        }
        return $quant; //返回总量
    }

}
?>