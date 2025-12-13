<?php
class Db{
		private $_numRow;
		private $dbh= null;
		
		public function __construct()
		{
			
			$host = $_ENV['DB_HOST'] ?? 'localhost';
			$port = $_ENV['DB_PORT'] ?? '5432';
			$dbname = $_ENV['DB_NAME'] ?? 'guitar_shop';
			$username = $_ENV['DB_USERNAME'] ?? 'root';
			$password = $_ENV['DB_PASSWORD'] ?? '';

			try {
				$dsn = "pgsql:host=$host;port=$port;dbname=$dbname;sslmode=require";
				$pdo = new PDO($dsn, $username, $password);
				$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
				$this->dbh = $pdo;
			} catch (PDOException $e) {
				try {
					$dsn = "mysql:host=$host;dbname=$dbname;";
					$pdo = new PDO($dsn, $username, $password);
					$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
					$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
					$this->dbh = $pdo;
				} catch (PDOException $e) {
				die("Lỗi kết nối DB: " . $e->getMessage());
			}
			}
		}
		
		public function __destruct()
		{
			$this->dbh= null;
		}
	
		public function getRowCount()
		{
			return $this->_numRow;	
		}
		
		private function query($sql, $arr = array(), $mode = PDO::FETCH_ASSOC)
		{
			$stm = $this->dbh->prepare($sql);
			if (!$stm->execute( $arr)) 
				{
				echo "Sql lỗi."; exit;	
				}
			$this->_numRow = $stm->rowCount();
			return $stm->fetchAll($mode);
			
		}
		/*
		Sử dụng cho các sql select
		*/
		public function exeQuery($sql,  $arr = array(), $mode = PDO::FETCH_ASSOC)
		{
			return $this->query($sql, $arr, $mode);	
		}
		/*
		Sử dụng cho các sql cập nhật dữ liệu. Kết quả trả về số dòng bị tác động
		*/
		public function exeNoneQuery($sql,  $arr = array(), $mode = PDO::FETCH_ASSOC)
		{
			$this->query($sql, $arr, $mode);	
			return $this->getRowCount();
		}
		
		
	
	
	}
?>