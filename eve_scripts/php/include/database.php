<?php

$Dev_test_ally_id = '503818424';
$LOG_FILE_PATH = 'log/';

$DEBUG = 1;



class db {

	var $link;
	var $result;
	var $DB_HOST;
	var $DB_USER_NAME;
	var $DB_PASS;
	var $DB_NAME;



	function db(){
		$this->DB_HOST = 'localhost';
		$this->DB_USER_NAME = 'db_user';
		$this->DB_PASS = '';
		$this->DB_NAME = 'db_eve';

		$this->connect();
	}

	function connect() {

		$this->link = mysql_connect($this->DB_HOST, $this->DB_USER_NAME, $this->DB_PASS) or
                     die("Ошибка: невозможно произвести подключение к серверу базы данных. Обратитесь к администратору");

		mysql_select_db($this->DB_NAME, $this->link) or
                     die("Ошибка: доступ к базе данных невозможен. Обратитесь к администратору");
	}


	function close() {
		mysql_close($this->link);
	}

	function query($query) {
		$this->result = mysql_query($query, $this->link);
		return $this->result;
	}

	function num_rows() {
		return mysql_num_rows($this->result);
	}

	function free_result() {
		return mysql_free_result($this->result);
	}

	function fetch_assoc() {
		return mysql_fetch_assoc($this->result);
	}

}



class loggs {

	var $file;

	function loggs(){
		global $LOG_FILE_PATH;
		$this->file = fopen($LOG_FILE_PATH . date('d.m.Y') .'.txt', 'a');

	}

	function event($event, $txt){
		$str = ''. date('d.m.Y') .' '. date('G:i:s') .' - '. $event .' '. $txt ."\r\n";

		fputs($this->file, $str);
	}
}


?>
