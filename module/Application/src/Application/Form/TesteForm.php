<?php

namespace Application\Form;

use Zend\Form\Element\Text;

class TesteForm extends Form{
    public function __construct($name = null) {
        parent::__construct('Teste');
        
        $search = new Text('pesquisa');
        $search->setLabel('Pesquisar:')->setAttribute(array('style' => 'width:400px'));
        
        $this->add($search);
    }
}
