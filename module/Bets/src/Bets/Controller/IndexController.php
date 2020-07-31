<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Bets\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Score\Model\MatchModel;
use Application\Classes\Funcoes;

class IndexController extends AbstractActionController{
    public function indexAction(){
        $dados = (new MatchModel(new Funcoes($this)))->home();
        return new ViewModel(array("dados" => $dados));
    }
}
