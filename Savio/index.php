<?php

ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL);

require_once 'classes/user.php';

$objUser = new User();
if(isset($_GET['delete_id'])){
  $id = $_GET['delete_id'];
  try{
    if($id != null){
      if($objUser->delete($id)){
        $objUser->redirect('index.php?removido');
      }
    }else{
      var_dump($id);
    }
  }catch(PDOException $e){
    echo $e->getMessage();
  }
}
session_start();
if (isset($_SESSION['func']) && $_SESSION['logado'] == "sim" && $_SESSION['func'] == 1){
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
                    <h1 style="margin-top: 10px">Usuarios</h1>
                    <?php
                      if(isset($_GET['editado'])){
                        echo '<div class="alert alert-info alert-dismissable fade show" role="alert">
                        <strong>User!<trong> Editado com sucesso.
                          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true"> &times; </span>
                          </button>
                        </div>';
                      }else if(isset($_GET['removido'])){
                        echo '<div class="alert alert-info alert-dismissable fade show" role="alert">
                        <strong>User!<trong> Removido com sucesso.
                          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true"> &times; </span>
                          </button>
                        </div>';
                      }else if(isset($_GET['inserido'])){
                        echo '<div class="alert alert-info alert-dismissable fade show" role="alert">
                        <strong>User!<trong> Inserido com sucesso.
                          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true"> &times; </span>
                          </button>
                        </div>';
                      }else if(isset($_GET['erro'])){
                        echo '<div class="alert alert-info alert-dismissable fade show" role="alert">
                        <strong>DB Error!<trong> Algo deu errado com sua ação. Tente novamente!
                          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true"> &times; </span>
                          </button>
                        </div>';
                      }
                    ?>
                      <div class="table-responsive">
                        <table class="table table-striped table-sm">
                            <thead>
                              <tr>
                                <th>#</th>
                                <th>Nome</th>
                                <th>Email</th>
                                <th></th>
                              </tr>
                            </thead>
                            <?php
                              $query = "SELECT * FROM usuarios";
                              $stmt = $objUser->runQuery($query);
                              $stmt->execute();
                            ?>
                            <tbody>
                                <?php if($stmt->rowCount() > 0){
                                  while($rowUser = $stmt->fetch(PDO::FETCH_ASSOC)){
                                 ?>
                                 <tr>
                                    <td><?php print($rowUser['id']); ?></td>

                                    <td>
                                      <a href="form.php?edit_id=<?php print($rowUser['id']); ?>">
                                      <?php print($rowUser['nome']); ?>
                                      </a>
                                    </td>

                                    <td><?php print($rowUser['email']); ?></td>

                                    <td>
                                      <a class="confirmation" href="index.php?delete_id=<?php print($rowUser['id']); ?>">
                                      <span data-feather="trash"></span>
                                      </a>
                                    </td>
                                 </tr>
                          <?php } } ?>
                            </tbody>
                        </table>
                      </div>
                </main>
            </div>
        </div>
        <?php require_once 'includes/footer.php'; ?>

        <script>
            $('.confirmation').on('click', function () {
                return confirm('Tem certeza que deseja remover?');
            });
        </script>
    </body>
</html>
<?php
  }
  else if(isset($_SESSION['func']) && $_SESSION['func'] != 1){
    session_destroy();
    echo "Bem Vindo!, você não é administrador ainda estamos trabalhando para a nova tela!";
  } else { header("Location: login.php"); }
?>
