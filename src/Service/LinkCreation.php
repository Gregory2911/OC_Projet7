<?php

namespace App\Service;

class LinkCreation
{

    public function getLinks($id, $isSelf, $isUpdate, $isDelete, $ressource)
    {
        $linkSelf = array();
        $linkUpdate = array();
        $linkDelete = array();

        $links = array();

        if($isSelf == 1){
            $linkSelf = ['rel' => 'self', 'href' => '/api/'. $ressource .'/' . $id, 'action' => 'GET'];
            $links[] = $linkSelf;            
        }
        
        if($isUpdate == 1){
            $linkUpdate = ['rel' => 'self', 'href' => '/api/' . $ressource . '/' . $id, 'action' => 'PUT'];
            $links[] = $linkUpdate;            
        }

        if($isDelete == 1){
            $linkDelete = ['rel' => 'self', 'href' => '/api/' . $ressource . '/' . $id, 'action' => 'DELETE'];
            $links[] = $linkDelete;
        }
        
        return $links;

    }

}