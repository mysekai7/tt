<?php
/**
 * 购物车
 *
 * @copyright Copyright (c) 2006 - 2008 coderhome.net
 * @author 志凡 <dzjzmj@163.com>
 * @package Model
 * @version v0.1
 */

class ModelCart extends Model {

	/**
	 * 构造函数
	 * @param Object $db 数据查询类
	 * @access public
	 * @return void
	 */
	function __construct($query=null) {
		parent::__construct($query);
		session_start();
	}

	function add($productInfo) {
		$product = new ModelProduct();
		$productItem = $product->getInfo($productInfo['id']);
		$info = array(
				'id' => $productInfo['id'],
				'product_name' => $productInfo['product_name'],
				'attribute' => $productInfo['attribute'],
				'price' => $productInfo['price'],
				'sn' => $productItem['sn'],
				'photoUrl' => CFG_URL . 'data/product/'.$productItem['photo'],
				'number' => 1,
				);
		$_SESSION['cartInfo'][$productInfo['id'].'_'.$productInfo['attribute']] = $info;
	}

	function changeNumber($id,$number) {
		$_SESSION['cartInfo'][$id]['number'] = $number;
	}
	static function count() {
		session_start();
		return count($_SESSION['cartInfo']);
	}

	function getAll() {
		$items = $_SESSION['cartInfo'];
		$result = array();
		foreach ($items  as $key => $item) {
			$attrs = explode('||',$item['attribute']);
			foreach ($attrs as $attr) {
				$i = explode('=',$attr);
				$n = explode(',',$i[1]);
				$item[$i[0]] = $n[1];
				$item[$i[0].'_id'] = $n[0];
			}
			$item['prices'] = $item['price']*$item['number'];
			$item['cart_id'] = urlencode($item['id'].'_'.$item['attribute']);
			$result[] = $item;
		}
		return $result;
	}
	function getCount() {
		$items = $this->getAll();
		$count = 0;
		foreach ($items  as $key => $item) {
			$count += $item['prices'];
		}
		return $count;

	}
	function clear() {
		$_SESSION['cartInfo'] = array();
	}

	function delete($id) {
		unset($_SESSION['cartInfo'][$id]);
	}

}
?>