<?php


class PanierComponent extends Component{

private $controller =  false;




public function __construct(ComponentCollection $collection,$settings){
$this->controller = $collection->getController();

}


//je met un article dans le panier
public function add($id = false ,$quant = 1){


$article = $this->controller->Boutique->Article->findById($id,array('recursive'=>0));

if($id == false){die('1');return;}

if($quant < 1 or $quant > 100){die('2');return;}

if(empty($article)){die('3');return;}

if(!$this->controller->Session->check('Panier')){$this->create();}

if($this->controller->Session->check('Panier.'.$id)){
$nombre = $this->controller->Session->read('Panier.'.$id.'.nombre');
$this->controller->Session->write('Panier.'.$id.'.nombre',$nombre+$quant);
$this->modifTotal( $article['Article']['prix']);
return;
}




$article = array_merge($article,array('nombre'=>$quant,'time'=>'notReady'));
debug($id);

$this->controller->Session->write('Panier.'.$id,$article);
$this->modifTotal( $article['Article']['prix']);
//$this->controller->Session->destroy();

}

//j'enleve les aticle du panier
public function sub($id,$quant=1){

$quantActuel = $this->controller->Session->read('Panier.'.$id.'.nombre');

if($quantActuel ==  null){ return;}

$prix = $this->controller->Session->read('Panier.'.$id.'.Article.prix');

if($quantActuel-$quant <= 0){   
        $this->remove($id);
        $this->modifTotal(- $prix );
        return; 
        }


    $this->controller->Session->write('Panier.'.$id.'.nombre',$quantActuel-$quant);
    $this->modifTotal(- $prix );
    
    
}



//j'enleve les aticle du panier
public function remove($id){
$this->controller->Session->delete('Panier.'.$id);

}



// je cree la commande pas 
public function create(){

$this->controller->Session->write('Panier.Total' ,0.00);

}


// je renvoie vers le lien d'achat
public function buy(){


}


//je renvoi la liste des articles
public function listArticle(){
return $this->controller->Session->read('Panier');
}

//verifie les prix et ce qui est dans le panier
public function check(){}


private function modifTotal($numbre){

    $total = $this->controller->Session->read('Panier.Total');
    $total = $total + $numbre;
    $this->controller->Session->Write('Panier.Total',$total);

}



}
?>