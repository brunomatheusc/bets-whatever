<?php

namespace Score\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use Score\Model\GameModel;
use Application\Classes\Funcoes;

class GameController extends AbstractActionController{
    public function indexAction(){
        echo "<pre>";
        var_dump("Oi");
        echo "</pre>";
        exit;
        
        return new ViewModel();
    }

    public function adicionarAction(){
        
        return new ViewModel();
    }

    public function addGamesAction(){
        $request = $this->getRequest();
        
        if ($request->isPost()){
            $params = $this->params()->fromPost();
            $result = (new GameModel(new Funcoes($this)))->adicionar($params['jogos']);
        }

        return new JsonModel($result);
    }

    public function updateAction(){
        $request = $this->getRequest();
        
        if ($request->isPost()){
            $params = $this->params()->fromPost();         
            $result = (new GameModel(new Funcoes($this)))->atualizar($params['jogos']);
        }
        
        return new JsonModel($result);
    }
}