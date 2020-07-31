<?php

namespace Application\Form;

use Zend\Form\Form;
use Zend\Form\Element\Hidden;
use Zend\Form\Element\Text;
use Zend\Form\Element\File;
use Zend\Form\Element\Textarea;
use Zend\Form\Element\Checkbox;
use Zend\Form\Element\Button;

class ProdutoForm extends Form{
    public function __construct($name = null){
        parent::__construct('produto'); 
        
        //Setando para o formulário receber arquvios
        $this->setAttribute('enctype', 'multipart/form-data');
        
        $id = new Hidden('produto_id');
        
        $nome = new Text('produto_nome');
        $nome->setLabel('Nome:')->setAttributes(array(
                'style' => 'width:300px'
        ));
        
        $preco = new Text('produto_preco');
        $preco->setLabel('Preço:')->setAttributes(array(
                'style' => 'width:100px' 
        ));
        
        $foto = new File('produto_foto');
        $foto->setLabel('Foto:')->setAttributes(array(
                'style' => 'width:500px' 
        ));
        
        $descricao = new Textarea('produto_descricao');
        $descricao->setLabel('Descricao:')->setAttributes(array(
                'style' => 'width:300px; height:100px'  
        ));
        
        $status = new Checkbox('produto_status');
        $status->setLabel('Status:')->setValue(1);
        
        $submit = new Button('submit');
        $submit->setLabel('Cadastrar')->setAttributes(array(
                'type' => 'submit' 
        ));
        
        $btnvoltar = new Button('voltar');
        $btnvoltar->setLabel('Voltar');
        
        $this->add($id);
        $this->add($nome);
        $this->add($preco);
        $this->add($foto);
        $this->add($descricao);
        $this->add($status);
        $this->add($btnvoltar);
        $this->add($submit, array('priority' => -100));        
    }
}