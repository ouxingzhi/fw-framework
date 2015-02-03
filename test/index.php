<?php




class A{
	public $t = "sss";
	public function name(){

	}
}

class B extends A{

}


$b = new B();

var_dump($b instanceof A);
