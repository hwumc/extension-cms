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
    
    public function delete() 
    {
        throw new NotImplementedException();
    }
    
    public function save()
    {
        throw new NotImplementedException();
    }
}
