<?php
class Book extends Db{
	private $_page_size =5;//Một trang hiển hị 5 cuốn sách
	private $_page_count;
	public function getRand($n)
	{
		$sql="select book_id, book_name, img from book order by rand() limit 0, $n ";
		return $this->exeQuery($sql);	
	}
	
	public function getByPubliser($manhaxb)
	{
		
	}
	public function delete($book_id)
	{
		$sql="delete from book where book_id=:book_id ";
		$arr =  Array(":book_id"=>$book_id);
		return $this->exeNoneQuery($sql, $arr);	
	}
	
	public function getDetail($book_id)
	{
		$sql="select book.*, pub_name, cat_name 
			from book, publisher, category
			where book.cat_id = category_cat_id
				and book.pub_id = publisher.pub_name
				and book_id=@book_id ";
		$arr = array("@book_id"=>$book_id);
		$data = $this->exeQuery($sql, $arr);
		if (Count($data)>0) return $data[0];
		else return array();
	}
	
	public function getAll($currPage=1)
	{
		$offset = ($currPage -1) * $this->_page_size;
		$sql="SELECT
				Count(*)
				FROM
				category
				INNER JOIN book ON book.cat_id = category.cat_id
				INNER JOIN publisher ON book.pub_id = publisher.pub_id";
				$n  = $this->count($sql);
				$this->_page_count = ceil($n/$this->_page_size);
		$sql="SELECT
				book.book_id,
				book.book_name,
				book.description,
				book.price,
				book.img,
				book.pub_id,
				book.cat_id,
				category.cat_name,
				publisher.pub_name
				FROM
				category
				INNER JOIN book ON book.cat_id = category.cat_id
				INNER JOIN publisher ON book.pub_id = publisher.pub_id
				limit $offset, " . $this->_page_size;
		
		return $this->exeQuery($sql);
	}
	
	public function search($currPage=1)
	{
		$key = Utils::getIndex("key");
		$arr = array(":book_name"=>"%". $key ."%");
		
		$offset = ($currPage -1) * $this->_page_size;
		$sql="SELECT
				Count(*)
				FROM
				category
				INNER JOIN book ON book.cat_id = category.cat_id
				INNER JOIN publisher ON book.pub_id = publisher.pub_id
				where book_name like :book_name	";
				$n  = $this->count($sql, $arr);
				$this->_page_count = ceil($n/$this->_page_size);
		$sql="SELECT
				book.book_id,
				book.book_name,
				book.description,
				book.price,
				book.img,
				book.pub_id,
				book.cat_id,
				category.cat_name,
				publisher.pub_name
				FROM
				category
				INNER JOIN book ON book.cat_id = category.cat_id
				INNER JOIN publisher ON book.pub_id = publisher.pub_id
				where book_name like :book_name	
				limit $offset, " . $this->_page_size;
						
		return $this->exeQuery($sql, $arr);
	}
	
	public function count($sql, $arr=array())
	{
		return $this->countItems($sql, $arr);
	}
	
	public function getPageCount()
	{
		return $this->_page_count;	
	}

}
?>