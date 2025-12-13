<?php
class Category extends Db{
	
	
	public function delete($cat_id)
	{
		$sql="delete from category where cat_id=:cat_id ";
		$arr =  Array(":cat_id"=>$cat_id);
		return $this->exeNoneQuery($sql, $arr);	
	}
	
	public function getById($cat_id)
	{
		$sql="select category.* 
			from category
			where  category.cat_id=:cat_id ";
		$arr = array(":cat_id"=>$cat_id);
		$data = $this->exeQuery($sql, $arr);
		if (Count($data)>0) return $data[0];
		else return array();
	}
	
	public function getAll()
	{
		return $this->exeQuery("select * from category");
	}
	
	public function saveEdit()
	{
		$id =Utils::postIndex("cat_id", "");
		$name =Utils::postIndex("cat_name", "");
		if ($id =="" || $name=="") return 0;//Error
		$sql="update category set cat_name=:name where cat_id=:id ";
		$arr = array(":name"=>$name, ":id"=>$id);
		return $this->exeNoneQuery($sql, $arr);
		
	}
	public function saveAddNew()
	{
		$id =Utils::postIndex("cat_id", "");
		$name =Utils::postIndex("cat_name", "");
		if ($id =="" || $name=="") return 0;//Error
		$sql="insert into category(cat_id, cat_name) values(:cat_id, :cat_name) ";
		$arr = array(":cat_id"=>$id, ":cat_name"=>$name);
		return $this->exeNoneQuery($sql, $arr);
		
	}

}
?>