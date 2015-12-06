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
    protected $template;
    protected $imagegroup;

    /**
     * Summary of getBySlug
     * @param string $slug 
     * @return Page
     */
    public static function getBySlug( $slug ) {
        global $gDatabase;
        $statement = $gDatabase->prepare("SELECT * FROM `page` WHERE slug = :slug;");
        $statement->bindParam(":slug", $slug);

        $statement->execute();

        $resultObject = $statement->fetchObject( "Page" );

        if($resultObject != false)
        {
            $resultObject->isNew = false;
        }

        return $resultObject;
    }
    
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
    
    public function getTemplate()
    {
        return $this->template;
    }

    public function getTemplateForDisplay()
    {
        global $cCmsDefaultTemplate;

        if($this->template === ""
            || $this->template === null
            || $this->template === false) {
            return $cCmsDefaultTemplate;
        }

        return $this->template;
    }
    
    public function setTemplate($template)
    {
        $this->template = $template;   
    }
    
    public function getImageGroup()
    {
        return $this->imagegroup;
    }
    
    public function setImageGroup($imageGroup)
    {
        $this->imagegroup = $imageGroup;   
    }
    
    public function getMenuGroup()
    {
        return $this->menugroup;   
    }    
    
    public function getMenuGroupObject()
    {
        return MenuGroup::getById($this->menugroup);   
    }
    
    public function getMenuGroupSlug()
    {
        $group = $this->getMenuGroupObject();
        if( $group == null )
        {
            return "";
        }
        else
        {
            return $group->getSlug();
        }
    }
    
    public function setMenuGroup($menugroup)
    {
        $this->menugroup = $menugroup;   
    }
    
    public function canDelete()
    {
        return true;   
    }
    
    public function delete() 
    {
        global $gDatabase;
        if(! $gDatabase->beginTransaction())
        {
            throw new TransactionAlreadyOpenException();   
        }
        
        try
        {
            $statement = $gDatabase->prepare("UPDATE `page` SET revision = null WHERE id = :id;");
            $statement->bindParam(":id", $this->id);
		    $statement->execute();
        
		    $statement = $gDatabase->prepare("DELETE FROM `revision` WHERE page = :id;");
            $statement->bindParam(":id", $this->id);
	    	$statement->execute();
        
		    $statement = $gDatabase->prepare("DELETE FROM `page` WHERE id = :id LIMIT 1;");
	    	$statement->bindParam(":id", $this->id);
		    $statement->execute();
        }
        catch ( Exception $ex )
        {
            $gDatabase->rollback();
            throw $ex;
        }
        
        $gDatabase->commit();

		$this->id = 0;
		$this->isNew = true;
    }
    
    public function save()
    {
        global $gDatabase;

        if($this->isNew)
        { // insert
            $statement = $gDatabase->prepare("INSERT INTO page (slug, title, accessright, revision, parent, menugroup, template, imagegroup) VALUES (:slug, :title, :accessright, :revision, :parent, :menugroup, :template, :imagegroup );");
            $statement->bindParam(":slug", $this->slug);
            $statement->bindParam(":title", $this->title);
            $statement->bindParam(":accessright", $this->accessright);
            $statement->bindParam(":revision", $this->revision);
            $statement->bindParam(":parent", $this->parent);
            $statement->bindParam(":menugroup", $this->menugroup);
            $statement->bindParam(":template", $this->template);
            $statement->bindParam(":imagegroup", $this->imagegroup);

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
            $statement = $gDatabase->prepare("UPDATE page SET slug = :slug, title = :title, accessright = :accessright, revision = :revision, parent = :parent, menugroup = :menugroup, template = :template, imagegroup = :imagegroup WHERE id = :id LIMIT 1;");
            $statement->bindParam(":slug", $this->slug);
            $statement->bindParam(":title", $this->title);
            $statement->bindParam(":accessright", $this->accessright);
            $statement->bindParam(":revision", $this->revision);
            $statement->bindParam(":parent", $this->parent);
            $statement->bindParam(":menugroup", $this->menugroup);
            $statement->bindParam(":template", $this->template);
            $statement->bindParam(":imagegroup", $this->imagegroup);

            $statement->bindParam(":id", $this->id);

            if(!$statement->execute())
            {
                throw new SaveFailedException();
            }
        }
    }

    public function getHistory()
    {
        global $gDatabase;
		$statement = $gDatabase->prepare("SELECT 
	r.id, 
	coalesce (r.user, 0) AS userid, 
	coalesce (u.username, '(deleted user)') AS username, 
	r.timestamp, 
	r.text, 
	r.page, 
	p.title,
	case when p.revision = r.id then 1 else 0 end as active
FROM revision r
	LEFT JOIN `page` p ON p.id = r.page
	LEFT JOIN `user` u ON u.id = r.user
WHERE r.page = :pageid
;");
        $statement->bindParam(":pageid", $this->id);
		$statement->execute();

		$result = $statement->fetchAll( PDO::FETCH_ASSOC );
        
        $augmented = array();
        foreach($result as $data) {
            $userobject = User::getById($data['userid']);
            $data['userobject'] = $userobject;
            $augmented[] = $data;
        }

		return $augmented;
    }
}
