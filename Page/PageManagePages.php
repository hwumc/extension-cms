<?php
// check for invalid entry point
if(!defined("HMS")) die("Invalid entry point");

class PageManagePages extends PageBase
{

	public function __construct()
	{
		$this->mPageUseRight = "cms-view";
		$this->mMenuGroup = "Content Management";
		$this->mPageRegisteredRights = array( "cms-edit", "cms-create", "cms-delete" );
		
	}

	protected function runPage()
	{
		$data = explode( "/", WebRequest::pathInfoExtension() );
		if( isset( $data[0] ) ) {
			switch( $data[0] ) {
				case "edit":
					$this->editMode( $data );
					return;

				case "delete":
					$this->deleteMode( $data );
					return;

				case "create":
					$this->createMode( $data );
					return;

				case "view":
					$this->viewMode( $data );
					return;
				
				case "history":
					$this->historyMode( $data );
					return;

			}
	
		}
		
		// try to get more access than we may have.
		try	{
			self::checkAccess('cms-create');
			$this->mSmarty->assign("allowCreate", 'true');
		} catch(AccessDeniedException $ex) { 
			$this->mSmarty->assign("allowCreate", 'false');
		} 
		try {
			self::checkAccess('cms-delete');
			$this->mSmarty->assign("allowDelete", 'true');
		} catch(AccessDeniedException $ex) { 
			$this->mSmarty->assign("allowDelete", 'false');
		}
		try {
			self::checkAccess('cms-edit');
			$this->mSmarty->assign("allowEdit", 'true');
		} catch(AccessDeniedException $ex) { 
			$this->mSmarty->assign("allowEdit", 'false');
		}
		
		$this->mBasePage = "cms/list.tpl";
		$pages = Page::getArray();
		$this->mSmarty->assign("pagelist", $pages );
	}
	
	private function editMode( $data ) {
        $allowEdit = "false";
		try {
			self::checkAccess('cms-edit');
			$allowEdit = "true";
		} catch(AccessDeniedException $ex) { 
            $allowEdit = "false";
		}

        global $cCmsTemplates;
        
		$g = Page::getById( $data[ 1 ] );

        $this->mSmarty->assign("allowEdit", $allowEdit);
        
		if( WebRequest::wasPosted() ) {
			if( ! $allowEdit ) throw new AccessDeniedException();
			
            $template = WebRequest::post("template");
            if($template == "" || array_key_exists($template, $cCmsTemplates)) {
                $g->setTemplate($template);
            }

            $imagegroup = WebRequest::post("imagegroup");
            if($imagegroup == "") {
                $g->setImageGroup(null);
            }

            if(is_int($imagegroup) || ImageGroup::getById($imagegroup) !== false) {
                $g->setImageGroup($imagegroup);
            }

            $otherSlugPage = Page::getBySlug(WebRequest::post("slug"));
            if($otherSlugPage !== false && $otherSlugPage->getId() != $g->getId()) {
                Session::appendWarning("slug-already-exists");
            }
            else{
                $g->setSlug( WebRequest::post( "slug" ) );
            }

            $g->setTitle ( WebRequest::post( "cmspagetitle" ) );
			$g->setAccessRight( WebRequest::post( "accessright" ) );
			$g->setPriority( WebRequest::post( "priority" ) );
			$g->setPromoted( WebRequest::post( "promoted" ) == 'on' ? 1 : 0  );

            $menugroup = MenuGroup::getBySlug( WebRequest::post( "menugroup" ) );
            if( $menugroup != null ) {
		        $g->setMenuGroup( $menugroup->getId() );
            } else {
                $g->setMenuGroup( null );
            }
            $g->save();
            
            $r = new Revision();
            $r->setPage( $g->getId() );
            $r->setText( WebRequest::post( "pagecontent" ) );
            $r->setUser( Session::getLoggedInUser() );
            $r->save();
            
            $g->setRevision( $r->getId() );
            $g->save();
            
            global $cScriptPath;
            $this->mHeaders[] = ( "Location: " . $cScriptPath . "/ManagePages" );
            $this->mIsRedirecting = true;
        } else {
            $rightnames= array();
            foreach (Right::getAllRegisteredRights(true) as $v)
            {
                $rightnames[] = "\"" . htmlentities($v) . "\"";
            }
            $this->mSmarty->assign( "jsrightslist", "[" . implode(",", $rightnames ) . "]" );
            
            $menugroups= array();
            foreach (MenuGroup::getArray() as $v)
            {
                $menugroups[] = "\"" . htmlentities($v->getSlug()) . "\"";
            }
            $this->mSmarty->assign( "jsmenugrouplist", "[" . implode(",", $menugroups ) . "]" );
            
            $allfiles = File::getImages();
            $this->mSmarty->assign("allfiles", $allfiles);

            $imageGroups = ImageGroup::getArray();

            if( isset( $data[2] ) ) {
                $rev = Revision::getById( $data[2] );
            } else {
                $rev = Revision::getById( $g->getRevision() );
            }

            $content = "";
            if( $rev != null ) {
                $content = $rev->getText();
            }
            
            $this->mBasePage = "cms/create.tpl";

            $this->mSmarty->assign( "imagegroups", $imageGroups );
            $this->mSmarty->assign( "templates", $cCmsTemplates );
            $this->mSmarty->assign( "cmspagetitle", $g->getTitle() );
            $this->mSmarty->assign( "slug", $g->getSlug() );
            $this->mSmarty->assign( "accessright", $g->getAccessRight() );
            $this->mSmarty->assign( "pagecontent", $content );
            $this->mSmarty->assign( "pageid", $g->getId() );
            $this->mSmarty->assign( "template", $g->getTemplate() );
            $this->mSmarty->assign( "imagegroup", $g->getImageGroup() );
            $this->mSmarty->assign( "promoted", $g->getPromoted() == 1 ? 'checked="checked"' : '' );
            $this->mSmarty->assign( "priority", $g->getPriority() );

            $loadingMenuGroup = MenuGroup::getById( $g->getMenuGroup() );
            if($loadingMenuGroup != null) {
                $this->mSmarty->assign( "menugroup", $loadingMenuGroup->getSlug() );
            } else {
                $this->mSmarty->assign( "menugroup", "" );
            }
        }
    }

    private function historyMode( $data ) {
        $allowEdit = "false";
		try {
			self::checkAccess('cms-edit');
			$allowEdit = "true";
		}
        catch(AccessDeniedException $ex) { 
            $allowEdit = "false";
		}
        
		$g = Page::getById( $data[ 1 ] );

        $history = $g->getHistory();
        $this->mSmarty->assign( "history", $history );
        $this->mSmarty->assign( "allowEdit", $allowEdit );
        $this->mSmarty->assign( "page", $g );
        $this->mBasePage = "cms/history.tpl";
    }

	private function deleteMode( $data ) {
		self::checkAccess( "cms-delete" );
	
		if( WebRequest::wasPosted() ) {
			$g = Page::getById( $data[1] );
			if( $g !== false ) {
				if( WebRequest::post( "confirm" ) == "confirmed" ) {
					$g->delete();
					$this->mSmarty->assign("content", "deleted" );
				}
			}
			
			global $cScriptPath;
			$this->mHeaders[] =  "Location: " . $cScriptPath . "/ManagePages";
            $this->mIsRedirecting = true;
		} else {
			$this->mBasePage = "cms/delete.tpl";
		}
	}
	
	private function createMode( $data ) {
		self::checkAccess( "cms-create" );
		$this->mSmarty->assign("allowEdit", 'true');

        global $cCmsTemplates;
	
		if( WebRequest::wasPosted() ) {
			$g = new Page();
            
            $template = WebRequest::post("template");
            if($template == "" || array_key_exists($cCmsTemplates, $template)) {
                $g->setTemplate($template);
            }

            $imagegroup = WebRequest::post("imagegroup");
            if($imagegroup == "") {
                $g->setImageGroup(null);
            }

            if(is_int($imagegroup) || ImageGroup::getById($imagegroup) !== false) {
                $g->setImageGroup($imagegroup);
            }

            $otherSlugPage = Page::getBySlug(WebRequest::post("slug"));
            if($otherSlugPage !== false) {
                Session::appendWarning("slug-already-exists-set-random");
                $g->setSlug( base64_encode(openssl_random_pseudo_bytes(9)) );
            }
            else{
                $g->setSlug( WebRequest::post( "slug" ) );
            }

			$g->setTitle( WebRequest::post( "cmspagetitle" ) );
            $g->setAccessRight( WebRequest::post( "accessright" ) );

			$g->setPriority( WebRequest::post( "priority" ) );
			$g->setPromoted( WebRequest::post( "promoted" ) == 'on' ? 1 : 0  );

            $menugroup = MenuGroup::getBySlug( WebRequest::post( "menugroup" ) );
            if( $menugroup != null ) {
		        $g->setMenuGroup( $menugroup->getId() );
            }
			$g->save();
            
            $r = new Revision();
            $r->setPage( $g->getId() );
            $r->setText( WebRequest::post( "pagecontent" ) );
            $r->setUser( Session::getLoggedInUser() );
            $r->save();
            
            $g->setRevision( $r->getId() );
			$g->save();
            
			global $cScriptPath;
			$this->mHeaders[] =  "Location: " . $cScriptPath . "/ManagePages";
            $this->mIsRedirecting = true;
        } else {
            $rightnames= array();
            foreach (Right::getAllRegisteredRights(true) as $v)
            {
                $rightnames[] = "\"" . $v . "\"";
            }  
            $this->mSmarty->assign( "jsrightslist", "[" . implode(",", $rightnames ) . "]" );
            
            $menugroups= array();
            foreach (MenuGroup::getArray() as $v)
            {
                $menugroups[] = "\"" . $v->getSlug() . "\"";
            }  
            $this->mSmarty->assign( "jsmenugrouplist", "[" . implode(",", $menugroups ) . "]" );

            $allfiles = File::getImages();
            $this->mSmarty->assign("allfiles", $allfiles);

            $imageGroups = ImageGroup::getArray();

            $this->mBasePage = "cms/create.tpl";

            
            $this->mSmarty->assign( "imagegroups", $imageGroups );
            $this->mSmarty->assign( "templates", $cCmsTemplates );
            $this->mSmarty->assign( "cmspagetitle", "" );
            $this->mSmarty->assign( "slug", "" );
            $this->mSmarty->assign( "accessright", "public" );
            $this->mSmarty->assign( "pagecontent", "");
            $this->mSmarty->assign( "menugroup", "main");
            $this->mSmarty->assign( "template", "" );
            $this->mSmarty->assign( "imagegroup", "" );
            $this->mSmarty->assign( "promoted", "0" );
            $this->mSmarty->assign( "priority", "10" );
		}
	}

    private function viewMode( $data ) {
        $rev = Revision::getById( $data[ 1 ] );

        if( $rev === false ) {
            throw new NonexistantObjectException();
        }

        $pageid = $rev->getPage();

        $page = Page::getById( $pageid );

        if( $page === false ) {
            $title = "Orphan Revision Text";
        } else {
            $title = $page->getTitle();
        }

        $imageGroup = ImageGroup::getById($page->getImageGroup());
        $files = array();
        if($imageGroup !== false) {
            $files = $imageGroup->getFiles();
        }

        $this->mSmarty->assign( "cmsImageGroupFiles", $files );
        $this->mSmarty->assign( "cmsPageContent", $rev->getText() );
        $this->mSmarty->assign( "cmsPageHeader", $title );
        $this->mBasePage = $page->getTemplateForDisplay();
    }
}
