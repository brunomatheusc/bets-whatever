<?php

namespace Bets\Controller;

use Bets\Form\ProdutoForm;
use Bets\Model\Produto;
use Zend\File\Transfer\Adapter\Http;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Validator\File\Size;
use Zend\View\Model\ViewModel;

class ProdutosController extends AbstractActionController{
    protected $produtoTable;
    
    public function getProdutoTable(){
        if(!$this->produtoTable){
            $sm = $this->getServiceLocator();
            $this->produtoTable = $sm->get('produto_table');
        }
        
        return $this->produtoTable;
    }

    public function indexAction() {
        //Mensagens do status do cadastro
        $messages = $this->flashMessenger()->getMessages();
        $pageNumber = (int) $this->params()->fromQuery('pagina');
        
        if ($pageNumber == 0){
            $pageNumber = 1;
        }
        
        $produtos = $this->getProdutoTable()->fetchAll($pageNumber);
        
        return new ViewModel(array(
            'messages' => $messages,
            'produtos' => $produtos,
            'titulo' => 'Listagem de Produtos'
        ));        
    }
    
    public function novoAction(){
        $form = new ProdutoForm();
        $request = $this->getRequest();
        
        if ($request->isPost()){
            $nonFile = $request->getPost()->toArray();
            $File = $this->params()->fromFiles('produto_foto');

            //Instanciando produto
            $produto = new Produto();
            
            //Dados postados
            $data = $request->getPost();
            
            //Verifica se os dados estão corretos
            $form->setInputFilter($produto->getInputFilter());
            $form->setData($data);
            
            $data = array_merge($nonFile, array('produto_foto' => $File['name']));
            $form->setData($data);
            
            $size = new Size(array('max' => 10000000));
            $adapter = new Http();
            $adapter->setValidators(array($size), $File['name']);

            //Valida o formulário e cadastra os dados
            if ($form->isValid()){
                $produto->exchangeArray($data);                
                $this->getProdutoTable()->saveProduto($produto);
                
                $diretorio = $request->getServer()->DOCUMENT_ROOT . '/conteudos/produtos';
                $adapter->setDestination($diretorio);

                if ($adapter->receive($File['name'])) {
                    $this->flashMessenger()->addMessage(array(
                        'success' => 'Foto enviada'
                    ));
                }

                $this->flashMessenger()->addMessage(array
                        ('sucess' => 'Cadastrado com sucesso'));
                $this->redirect()->toUrl('/produtos');
            }
            
            var_dump($data);
        }
        
        $view = new ViewModel(array(
            'form' => $form
        ));
        $view->setTemplate('Bets/produtos/form.phtml');
        
        return $view;
    }
    
    public function editarAction(){
        //Recebe o id por parâmetro para alterar o produto
        $id = $this->params('id');
        $produto = $this->getProdutoTable()->getProduto($id);
        
        //Cria um novo formulário
        $form = new ProdutoForm();
        $form->setBindOnValidate(false);
        
        //Vincula os objetos ao formulário
        $form->bind($produto);
        $form->get('submit')->setLabel('Alterar');
        
        $request = $this->getRequest();
        
        if ($request->isPost()){
            $nonFile = $request->getPost()->toArray();
            $File = $this->params()->fromFiles('produto_foto');
            
            if($File['name'] == ""){
                $filename = $produto->produto_foto;
            } else {
                $filename = $File['name'];
            }
            
            $data = array_merge($nonFile, array('produto_foto' => $filename));
            $form->setData($data);
            
            //Validar o formulário
            if ($form->isValid()){
                $form->bindValues();
                
                $size = new Size(array('max' => 10000000));
                
                $adapter = new Http();
                $adapter->setValidators(array($size), $File['name']);
                
                if (!$adapter->isValid()){
                    $dataError = $adapter->getMessages();
                    $error = array();
                    
                    foreach ($dataError as $row){
                        $error[] = $row;
                    }
                    
                    $form->setMessages(array('produto_foto' => $error));
                } else {
                    $diretorio = $request->getServer()->DOCUMENT_ROOT . '/conteudos/produtos';
                    $adapter->setDestination($diretorio);
                    
                    if ($adapter->receive($File['name'])){
                        $this->flashMessenger()->addMessage(array(
                            'success' => 'Foto enviada'
                        ));
                    } else {
                        $this->flashMessenger()->addMessage(array(
                            'error' => 'Foto não enviada'
                        ));                        
                    }
                }
                $this->getProdutoTable()->saveProduto($produto);
                $this->flashMessenger()->addMessage(array(
                    'success' => 'Produto atualizado'));
                $this->redirect()->toUrl('/produtos');
            }
        }
        
        $view = new ViewModel(array(
            'form' => $form
        ));
        $view->setTemplate('Bets/produtos/form.phtml');
        
        return $view;
    }
    
    public function removeAction(){
        $id = $this->params('id');
        
        $this->getProdutoTable()->removeProduto($id);
        
        $this->flashMessenger()->addMessage(array(
            'success' => 'Excluído com sucesso',
            'error' => 'Erro ao tentar excluir'
        ));

        $this->redirect()->toUrl('/produtos');
    }
}