<?php
include_once(dirname( __FILE__ ) ."/config.php");

class db{
	private $dbhost;
	private $dbuser;
	private $dbpw;
	private $dbname;
	private $mysqli;
	public $error;
	
	public function __construct(){
		$this->dbhost = $GLOBALS['web_datahost'];
		$this->dbuser = $GLOBALS['web_datauser'];
		$this->dbpw = $GLOBALS['web_datapassword'];
		$this->dbname = $GLOBALS['web_database'];
		
		$this->mysqli = new mysqli($this->dbhost, $this->dbuser, $this->dbpw, $this->dbname);  //连接MySQL数据库
		if($this->mysqli->connect_errno){//检查连接错误
			exit("MySQL Error:" . $this->mysqli->connect_error);
		}
		$this->mysqli->query("SET character_set_connection=utf8, character_set_results=utf8, character_set_client=binary");
	}

	public function insert_id() {
		return $this->mysqli->insert_id;
	}
	
	public function execute($sql){
		$result = $this->mysqli->query($sql);
		if($result === FALSE){
			$this->error = $this->mysqli->error;
		}
		return $result;
	}
	
	public function getOne($sql){
		$result = $this->mysqli->query($sql);
		$row = $result->fetch_array(MYSQLI_NUM);
		$result->free();
		return $row[0];
	}
	
	public function getRow($sql){
		$result = $this->mysqli->query($sql);
		$row = $result->fetch_array(MYSQLI_ASSOC);
		$result->free();
		return $row;
	}
	
	public function getAll($sql){
		$rows = array();
		$result = $this->mysqli->query($sql);
		while($row = $result->fetch_array(MYSQLI_ASSOC)){
			$rows[] = $row;
		}
		$result->free();
		return $rows;
	}
    
	//一次执行多条SQL查询语句(包括存储过程), 返回三维数组[表index][行index][数据]
	public function MultiQuery($sql){
		$ret = array(array(array())); 
        
		if($this->mysqli->multi_query($sql)){
			$TableIndex = 0;
			$RowIndex = 0;
			do{
				if($result = $this->mysqli->store_result()){ //取得结果集
					while ($row = $result->fetch_array()){ //取得每行
						$ret[$TableIndex][$RowIndex] = $row;                    
						$RowIndex++;
					}
					$result->free();
				}
				
				$TableIndex++;
				$RowIndex = 0;
				
				if(!$this->mysqli->more_results()){    
					break;
				}  
			}while($this->mysqli->next_result()); //获取下一个结果集，并继续执行循环
		}
		
		return $ret;
    }

	public function affected_rows() {
		return $this->mysqli->affected_rows;
	}

	public function close() {
		$this->mysqli->close();
	}
}
?>