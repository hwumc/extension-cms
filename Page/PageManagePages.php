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
					break;
				case "delete":
					$this->deleteMode( $data );
					return;
					break;
				case "create":
					$this->createMode( $data );
					return;
					break;
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
        
		$g = Page::getById( $data[ 1 ] );

        $this->mSmarty->assign("allowEdit", $allowEdit);
        
		if( WebRequest::wasPosted() ) {
			if( ! $allowEdit ) throw new AccessDeniedException();
			
            $g->setTitle ( WebRequest::post( "cmspagetitle" ) );
			$g->setSlug( WebRequest::post( "slug" ) );
			$g->setAccessRight( WebRequest::post( "accessright" ) );
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
		
            $rev = Revision::getById( $g->getRevision() );
            $content = "";
            if( $rev != null ) {
                $content = $rev->getText();
            }
            
			$this->mBasePage = "cms/create.tpl";
			$this->mSmarty->assign( "cmspagetitle", $g->getTitle() );
			$this->mSmarty->assign( "slug", $g->getSlug() );
			$this->mSmarty->assign( "accessright", $g->getAccessRight() );
            $this->mSmarty->assign( "pagecontent", $content );
            $loadingMenuGroup = MenuGroup::getById( $g->getMenuGroup() );
            if($loadingMenuGroup != null) {
                $this->mSmarty->assign( "menugroup", $loadingMenuGroup->getSlug() );
            } else {
                $this->mSmarty->assign( "menugroup", "" );
            }
		}
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
			
			
		} else {
			$this->mBasePage = "cms/delete.tpl";
		}
	}
	
	private function createMode( $data ) {
		self::checkAccess( "cms-create" );
		$this->mSmarty->assign("allowEdit", 'true');
	
		if( WebRequest::wasPosted() ) {
			$g = new Page();
			$g->setTitle( WebRequest::post( "cmspagetitle" ) );
			$g->setSlug( WebRequest::post( "slug" ) );
            $g->setAccessRight( WebRequest::post( "accessright" ) );
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
		
			$this->mBasePage = "cms/create.tpl";
			$this->mSmarty->assign( "cmspagetitle", "" );
			$this->mSmarty->assign( "slug", "" );
            $this->mSmarty->assign( "accessright", "public" );
            $this->mSmarty->assign( "pagecontent", "");
            $this->mSmarty->assign( "menugroup", "main");
		}
	}
}
