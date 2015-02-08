<?php

$link = mysql_connect('localhost','root','422302');

mysql_select_db('message',$link);

var_dump(mysql_query('insert into message set title="ouxingzhi",user="dieieiieieiei"'));


array(
	"TITLE"=>'OUXINGZHI',
	"@OR"=>
	);