<?php
// check for invalid entry point
if(!defined("HMS")) die("Invalid entry point");

class Revision extends DataObject
{
    protected $user;
    protected $timestamp;
    protected $page;
    protected $text;
    
    public function getUser()
    {
        return $this->user;    
    }
    
    public function setUser($user)
    {
        $this->user = $user;   
    }
    
    public function getTimestamp()
    {
        return $this->timestamp;
    }
    
    public function setTimestamp($timestamp)
    {
        $this->timestamp = $timestamp;
    }
    
    public function getPage()
    {
        return $this->page;   
    }
    
    public function setPage($page)
    {
        $this->page = $page;   
    }
    
    public function getText()
    {
        return $this->text;
    }
    
    public function setText($text)
    {
        $this->text = $text;   
    }
    
    public function delete() 
    {
        throw new NotImplementedException();
    }
    
    public function save()
    {
        global $gDatabase;

		if($this->isNew)
		{ // insert
			$statement = $gDatabase->prepare("INSERT INTO revision (user, page, text) VALUES (:user, :page, :text);");
			$statement->bindParam(":user", $this->user);
			$statement->bindParam(":page", $this->page);
			$statement->bindParam(":text", $this->text);
			if($statement->execute())
			{
				$this->isNew = false;
				$this->id = $gDatabase->lastInsertId();
			}
			else
			{
				throw new SaveFailedException();
			}
		}
		else
		{ // update
			throw new YouShouldntBeDoingThatException();
		}
    }
}
