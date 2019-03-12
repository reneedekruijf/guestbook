<?php 

require "kint.phar";

class DatabaseConnect {

    private $db_host;
    private $db_name;
    private $db_user;
    private $db_pass;

    protected function connect() {
      require_once 'env.php';

      $this->db_host = $host;
      $this->db_user = $user;
      $this->db_pass = $pass;
      $this->db_name = $name;

      $connect = new mysqli($this->db_host, $this->db_user, $this->db_pass, $this->db_name);
      
      return $connect;
    }
}

class GetAllId extends DatabaseConnect {

  protected function getData() {
    $sql = "SELECT *
      FROM gastenboek
      ORDER BY id DESC";
    $result = $this->connect()->query($sql);
    $numRows = $result->num_rows;
    if ($numRows > 0) {
      while ($row = $result-> fetch_assoc()) {
        $data[] = $row;
      }
      return $data;
    }
  }
}

class Show extends GetAllId {
  
  public function showAllId() {
   $all_data = $this->getData();
   echo json_encode($all_data);
  }
}

class GetSingleId extends DatabaseConnect {
  public function GetSingleId() {
    
  if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $sql = "SELECT *
            FROM gastenboek
            WHERE id = " . $_GET['id'];
    
    $result = $this->connect()->query($sql);

    if($result === false) {
      echo $result->connect_error;
    } else {
      $numRows = $result->num_rows;
      if ($numRows > 0) {
        while ($row = $result-> fetch_assoc()) {
          $data[] = $row;
        }
        return $data;
      }
    }
  } else {
    $data = null;
    }
  }
}


class ShowSingleId extends GetSingleId {

  public function name() {

    $all_data = $this->GetSingleId();
    if($all_data != null) {
      foreach ($all_data as $data) {
        return $all_data;
      }
    }
  }

  // public function email() {
  //   $all_data = $this->GetSingleId();
  //   if($all_data != null) {
  //     foreach ($all_data as $data) {
  //       return $data["email"];  
  //     }
  //   } 
  // }

  // public function content() {
  //   $all_data = $this->GetSingleId();
  //   if($all_data != null) {
  //     foreach ($all_data as $data) {
  //       return $data["content"];  
  //     }
  //   }
  // }

  // public function date() {
  //   $all_data = $this->GetSingleId();
  //   if($all_data != null) {
  //     foreach ($all_data as $data) {
  //       return $data["published_at"];  
  //     }
  //   }
  // }
}

class Inject extends DatabaseConnect {
  
  private $name;
  private $email;
  private $bericht;
  private $date;
  
  public function injectData($user_data) {
    date_default_timezone_set('Europe/Amsterdam');

    function test_input($data) {
      $data = trim($data);
      $data = stripslashes($data);
      $data = htmlspecialchars($data);
      return $data;
    }


    $this->name = test_input($user_data["naam"]);
    $this->email = test_input($user_data["email"]);
    $this->bericht = test_input($user_data["bericht"]);
    $this->date = date("d-m-Y"); 


    $errorMSG = [];
    $errorName = $errorMail = $errorBericht = "";

    /* show errors on wrong input or no input */

    // naam
    if (empty($this->name)) {
      $errorName .= "Vul aub uw naam in";
    } else {
      $this->name = $this->name;
    }


    // email
    if (empty($this->email)) {
      $errorMail .= "Vul aub een emailadres in";
    } else if(!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
      $errorMail = "Vul een geldig emailadres in";
    }else {
      $this->email = $this->email;
    }


    // bericht
    if (empty($this->bericht)) {
      $errorBericht .= "Voer uw bericht in";
    } else {
      $this->bericht = $this->bericht;
    }


    // collect all the errors, if there is one don't inject
    $errorMSG = $errorName . $errorMail . $errorBericht;

    $sql = "INSERT INTO gastenboek (naam, email, content, published_at)
          VALUES ('".$this->name."','".$this->email."','".$this->bericht."','".$this->date."')";

    if(empty($errorMSG)){
      $result = $this->connect()->query($sql);

      if ($result === false) {
        // $error =  $mysqli->error($result);
        echo json_encode(['code'=>404, 'msg'=>"helaas er is iets mis gegaan!"]);
      } else {  
          $msg = "Uw bericht is geplaatst!";
          echo json_encode(['code'=>200, 'msg'=>$msg]);
      }
      exit;
    }
    // show error if there is one
    echo json_encode(['code'=>404, 'error_name'=>$errorName, 'error_mail'=>$errorMail, 'error_bericht'=>$errorBericht]);
  }
}


