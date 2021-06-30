<?php

ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL);


require_once 'classes/user.php';
$objUser = new User();


if(isset($_GET['edit_id'])){
    $id = $_GET['edit_id'];
    $stmt = $objUser->runQuery("SELECT * FROM usuarios WHERE id=:id");
    $stmt->execute(array(":id" => $id));
    $rowUser = $stmt->fetch(PDO::FETCH_ASSOC);
}else{
  $id = null;
  $rowUser = null;
}


if(isset($_POST['btn_save'])){
  $nome   = strip_tags($_POST['nome']);
  $email  = strip_tags($_POST['email']);
  $senha  = strip_tags($_POST['senha']);
  $permissao  = strip_tags($_POST['permissao']);


  try{
     if($id != null){
       if($objUser->update($nome, $email, $senha, $permissao, $id)){
         $objUser->redirect('index.php?editado');
       }
     }else{
       if($objUser->insert($nome, $email, $permissao, $senha)){
         $objUser->redirect('index.php?adicionado');
       }else{
         $objUser->redirect('index.php?erro');
       }
     }
  }catch(PDOException $e){
    echo $e->getMessage();
  }
}

?>
<!doctype html>
<html lang="en">
    <head>

        <?php require_once 'includes/head.php'; ?>
    </head>
    <body>

        <?php require_once 'includes/header.php'; ?>
        <div class="container-fluid">
            <div class="row">

                <?php require_once 'includes/sidebar.php'; ?>
                <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
                  <h1 style="margin-top: 10px">Adicionar / Editar Usuario</h1>
                  <p>Dados obrigatorios (*)</p>
                  <form  method="post">
                    <div class="form-group">
                        <label for="id">ID</label>

                        <input class="form-control" type="text" name="id" id="id" value="<?php if(isset($rowUser['id'])){print($rowUser['id']);} else{echo "";}; ?>" readonly>
                    </div>
                    <div class="form-group">
                        <label for="nome">Nome *</label>
                        <input  class="form-control" type="text" name="nome" id="nome" placeholder="Nome" value="<?php if(isset($rowUser['nome'])){print($rowUser['nome']);} else{echo "";}; ?>" required maxlength="100">
                    </div>
                    <div class="form-group">
                        <label for="email">Email *</label>
                        <input  class="form-control" type="text" name="email" id="email" placeholder="exemplo@gmail.com" value="<?php if(isset($rowUser['email'])){print($rowUser['email']);} else{echo "";}; ?>" required maxlength="100">
                    </div>
                    <div class="form-group">
                        <label for="email">Senha <?php if(!isset($rowUser['senha'])) { echo "*";} ?></label>
                        <input  class="form-control" type="text" name="senha" id="senha" placeholder="*******" value="" <?php if(!isset($rowUser['senha'])) { echo "required";} ?> maxlength="100">
                    </div>
                    
                    <?php if (isset($rowUser)) { ?>
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" value="1" id="permissao" name="permissao" <?php if($rowUser['permissao'] == "1"){echo "checked";} ?>>
                      <label class="form-check-label" for="permissao">
                        Administrador
                      </label>
                    </div> <br>
                    <?php } ?>
                    <input class="btn btn-primary mb-2" type="submit" name="btn_save" value="Salvar">
                  </form>
                </main>
            </div>
        </div>

        <?php require_once 'includes/footer.php'; ?>
    </body>
</html>
