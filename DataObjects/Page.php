<?php
// check for invalid entry point
if(!defined("HMS")) die("Invalid entry point");

class Page extends DataObject
{
    protected $slug;
    protected $title;
    protected $accessright;
    protected $revision;
    protected $parent;
    protected $menugroup;
    
    public function getSlug()
    {
        return $this->slug;    
    }
    
    public function setSlug($slug)
    {
        $this->slug = $slug;   
    }
    
    public function getTitle()
    {
        return $this->title;
    }
    
    public function setTitle($title)
    {
        $this->title = $title;
    }
    
    public function getAccessRight()
    {
        return $this->accessright;   
    }
    
    public function setAccessRight($accessright)
    {
        $this->accessright = $accessright;   
    }
    
    public function getRevision()
    {
        return $this->revision;
    }
    
    public function setRevision($revision)
    {
        $this->revision = $revision;   
    } 
    
    public function getParent()
    {
        return $this->parent;
    }
    
    public function setParent($parent)
    {
        $this->parent = $parent;   
    }
    
    public function getMenuGroup()
    {
        return $this->menugroup;   
    }
    
    public function setMenuGroup($menugroup)
    {
        $this->menugroup = $menugroup;   
    }
    
    public function delete() 
    {
        global $gDatabase;
		$statement = $gDatabase->prepare("DELETE FROM `page` WHERE id = :id LIMIT 1;");
		$statement->bindParam(":id", $this->id);
		$statement->execute();

		$this->id = 0;
		$this->isNew = true;
    }
    
    public function save()
    {
        global $gDatabase;

        $godmodevalue = 0;

        if($this->isNew)
        { // insert
            $statement = $gDatabase->prepare("INSERT INTO page VALUES (null, :slug, :title, :accessright, :revision, :parent, :menugroup );");
            $statement->bindParam(":slug", $this->slug);
            $statement->bindParam(":title", $this->title);
            $statement->bindParam(":accessright", $this->accessright);
            $statement->bindParam(":revision", $this->revision);
            $statement->bindParam(":parent", $this->parent);
            $statement->bindParam(":menugroup", $this->menugroup);

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
            $statement = $gDatabase->prepare("UPDATE page SET slug = :slug, title = :title, accessright = :accessright, revision = :revision, parent = :parent, menugroup = :menugroup WHERE id = :id LIMIT 1;");
            $statement->bindParam(":slug", $this->slug);
            $statement->bindParam(":title", $this->title);
            $statement->bindParam(":accessright", $this->accessright);
            $statement->bindParam(":revision", $this->revision);
            $statement->bindParam(":parent", $this->parent);
            $statement->bindParam(":menugroup", $this->menugroup);

            $statement->bindParam(":id", $this->id);

            if(!$statement->execute())
            {
                throw new SaveFailedException();
            }
        }
    }
}
