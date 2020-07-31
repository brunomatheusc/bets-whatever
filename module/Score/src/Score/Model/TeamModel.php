<?php

namespace Score\Model;

use Exception;
use Application\Classes\Funcoes;
use Zend\Session\Container;

class TeamModel{
    private $funcao, $sessao, $config;

    public function __construct(Funcoes $funcao, $config = ''){
        $this->funcao = $funcao;
        $this->config = $config;
        $this->sessao = new Container('bets');
    }

    public function times(){
        try{
            $sql = "select distinct * 
                    from teams_view
                    order by time";
            return $this->funcao->executarSQL($sql);
        } catch(Exception $ex){
            throw new Exception($ex->getMessage());
        }        
    }

    public function timesByLiga($liga){
        try{
            $sql = "select * 
                    from teams_view
                    where league = :liga
                    order by time";
            return $this->funcao->executarSQL($sql, array("liga" => $liga));
        } catch(Exception $ex){
            throw new Exception($ex->getMessage());
        }        
    }
}