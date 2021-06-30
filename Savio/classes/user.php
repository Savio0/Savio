<?php

require_once 'database.php';

class User {
    private $conn;


    public function __construct(){
      $database = new Database();
      $db = $database->dbConnection();
      $this->conn = $db;
    }



    public function runQuery($sql){
      $stmt = $this->conn->prepare($sql);
      return $stmt;
    }


    public function insert($nome, $email, $senha){

      try{
        $stmt = $this->conn->prepare("INSERT INTO usuarios (nome, email, senha) VALUES(:nome, :email, :senha)");
        $stmt->bindparam(":nome", $nome);
        $stmt->bindparam(":email", $email);
        $stmt->bindparam(":senha", md5($senha));
        $stmt->execute();
        return $stmt;
      }

      catch(PDOException $e){
        echo $e->getMessage();
      }
    }



    public function update($nome, $email, $senha, $permissao, $id){

        try{

          if ($senha == "") {
          $stmt = $this->conn->prepare("UPDATE usuarios SET nome = :nome, email = :email, permissao = :permissao WHERE id = :id");
          $stmt->bindparam(":nome", $nome);
          $stmt->bindparam(":email", $email);
          $stmt->bindparam(":permissao", $permissao);
          $stmt->bindparam(":id", $id);
          $stmt->execute();
          return $stmt;            
          } else {
          $stmt = $this->conn->prepare("UPDATE usuarios SET nome = :nome, email = :email, senha = :senha, permissao = :permissao WHERE id = :id");
          $stmt->bindparam(":nome", $nome);
          $stmt->bindparam(":email", $email);
          $stmt->bindparam(":senha", md5($senha));
          $stmt->bindparam(":permissao", $permissao);
          $stmt->bindparam(":id", $id);
          $stmt->execute();
          return $stmt;
          }
        }

        catch(PDOException $e){
          echo $e->getMessage();
        }
    }



    public function delete($id){
      try{
        $stmt = $this->conn->prepare("DELETE FROM usuarios WHERE id = :id");
        $stmt->bindparam(":id", $id);
        $stmt->execute();
        return $stmt;
      }catch(PDOException $e){
          echo $e->getMessage();
      }
    }


    public function redirect($url){
      header("Location: $url");
    }  


  public function logaFuncionario($email,$senha){
    try{
      $cst = $this->conn->prepare("SELECT * FROM `usuarios` WHERE `email` = :email AND `senha` = :senha");
      $cst->bindParam(':email', $email);
      $cst->bindParam(':senha', md5($senha));
      $cst->execute();
      if($cst->rowCount() == 0){
        header('location: login.php?erro');
      }else{
        session_start();
        $rst = $cst->fetch();
        $_SESSION['logado'] = "sim";
        $_SESSION['func'] = $rst['permissao'];
        header("location: index.php");
      }
    }catch(PDOException $e){
      return 'Error: '.$e->getMassage();
    }
  }


  public function funcionarioLogado($id){
    $cst = $this->con->conectar()->prepare("SELECT * FROM `usuarios` WHERE `id` = :id;");
    $cst->bindParam(':id', $id);
    $cst->execute();
    $rst = $cst->fetch();
    $_SESSION['nome'] = $rst['nome'];
  }


    public function logout() {

      session_start();

      session_destroy();

      header("Location: login.php");
      exit();
    } 
}
?>
