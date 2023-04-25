<?php
session_start();
include("pt_db.php");
$pageName = "Edit User";

//initialize the variables
$userName = "";
$userEmail = "";
$userType = "";
$userPassword = "";
$phoneNumber = "";
$userHobbies = "";

$errorMessage = "";
$sucessMessage = "";

//Define regular expression variable
$reg = "/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/";

//post the values
if($_SERVER['REQUEST_METHOD'] == 'GET')
{
  if(!isset($_GET["userId"]))
  {
    header("location:pt_index.php");
    exit;
  }
  $userId = $_GET["userId"];
  //read the row of the selected user from database table
  $SQLR = "SELECT * FROM users WHERE userId = $userId";
  $resultr = $conn->query($SQLR);
  $row = $resultr->fetch_assoc();

  if(!$row)
  {
    header("location:pt_index.php");
    exit;
  }
  $userName = $row["userName"];
  $userEmail =$row["userEmail"];
  $userType = $row["userType"];
  $userPassword = $row["userPassword"];
  $phoneNumber = $row["phoneNumber"];
  $userHobbies = $row["userHobbies"];
  

  
}
else{
  //update data of client
  
    $userId = $_GET["userId"];
    $userName = $_POST["userName"];
    $userEmail = $_POST["userEmail"];
    $userType = $_POST["userType"];
    $userPassword = $_POST["userPassword"];
    $phoneNumber = $_POST["phoneNumber"];
    $userHobbies = $_POST["userHobbies"];

    do {
      //if the email does not match the regular expression 
      if (!preg_match($reg, $userEmail)) {
        
        echo "<br><p>Email not valid";
        echo "<br>Make sure you enter a correct email address</p>";
        break;
      }
      //check whether the fields are empty
      if(empty($userName) || empty($userEmail) ||  empty($userType) || empty($userPassword) || empty($phoneNumber) ) {
        $errorMessage = "All the fields are Required";
        break;
      }
      if($userType == 'customer' && empty($userHobbies))
      {
        $errorMessage = "Customer should have hobbies";
        break;
      }
      else{
        //Write a SQL query to insert a new user into users table 
        if($userType == 'customer'){
          $SQL ="UPDATE users SET userType = '$userType', userName = '$userName', userEmail = '$userEmail', userPassword = '$userPassword', userHobbies = '$userHobbies' WHERE userId = '$userId'"; 
          
        }
        if ($userType == 'admin') {
          $SQL ="UPDATE users SET userType = '$userType', userName = '$userName', userEmail = '$userEmail', userPassword = '$userPassword' WHERE userId = '$userId'";                                                                                                                                                                                   
          
        }
        $result = $conn->query($SQL);

        //Execute the INSERT INTO SQL query
        //if SQL execution is correct
        if (!$result)
        {
          $errorMessage = "Invalid query: ".$conn->error;
        }
        $sucessMessage = "Edited successfully ";
          header("location:pt_index.php");
          exit;
  
      } 
    }while(true);
}

echo "<head>";
    echo "<link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css'>";
echo "</head>";
echo "<body><h2>$pageName</h2>";
  if (!empty($errorMessage)){
    echo"<div class=alert alert-warning alert-dismissable fade show role = 'alert'>
      
    <strong>$errorMessage</strong>
    <button type=submit class = btn btn-close data-bs-dismiss= alert aria-label- close></button>
 
    </div> 
    ";  
  }
  echo"<div class = container mb-3>";

    echo"<form method = post>";
      echo"<input type = hidden value=$userId>";

      echo '<div class="row mb-3">
        <label class="col-sm-3 col-form-label">Name:</label>
        <div class="col-sm-6">
          <input type="text" class="form-control" name="userName" value="' . $userName . '">
        </div>  
      </div>';
      
      echo"<div class=row mb-3>
        <label class = col-sm-3 col-form-label>Email :</label>
        <div class=col-sm-6>
          <input type=text class = form-control name = userEmail value = $userEmail>
        </div>  
      </div>
      ";

      echo"<div class=row mb-3>
        <label class = col-sm-3 col-form-label>Password :</label>
        <div class=col-sm-6>
          <input type=text class = form-control name = userPassword value = $userPassword>
        </div>  
      </div> 
      ";
      echo"<div class=row mb-3>";
      if($userType == 'customer') {
        echo"<label class=col-sm-3 col-form-label>User Type :</label>";
        echo"<div class=col-sm-6>
          <select class=form-select name=userType value = $userType>
            <option value=customer>customer</option>
            <option value=admin>admin</option>
          </select>
        </div>
        ";
      }
      else{
        echo"<label class=col-sm-3 col-form-label>User Type :</label>";
        echo"<div class=col-sm-6>
          <select class=form-select name=userType value = $userType>
            <option value=admin>admin</option>
            <option value=customer>customer</option>
          </select>
        </div>
        ";
      }
         
      echo"</div>";
      
      echo '<div class="row mb-3">
        <label class="col-sm-3 col-form-label">Phone Number :</label>
        <div class="col-sm-6">
          <input type="text" class="form-control" name="phoneNumber" value="' . $phoneNumber . '">
        </div>  
      </div>';

      echo"<div class=row mb-3>
        <label class = col-sm-3 col-form-label>Hobbies :</label>
        <div class=col-sm-6>
          <input type=text class = form-control name = userHobbies value = $userHobbies>
        </div>  
      </div>
      ";
      if (!empty($errorMessage)){
    
        echo"<div class ='row mb-3'>
          <div class=offset-sm-3 col-sm-6>  
            <div class=alert alert-warning alert-dismissable fade show role = 'alert'>
              <strong>$sucessMessage</strong>
             <button type=submit class = btn btn-close data-bs-dismiss= alert aria-label- close></button>
           </div>
          </div>
        </div> 
        ";
      } 

      echo"<div class=\"row mb-3\">
        <div class=\"offset-sm-3 col-sm-3 d-grid\">
          <button type=submit class =\"btn btn-primary\">Submit</button>
        </div>
        <div class=\"col-sm-3 d-grid\">
          <a class =\"btn btn-outline-primary\" href =pt_index.php role = button>Cancel</a>
        </div>  
      </div>      
      ";
        
    echo"</form>";
  echo"</div>";
echo"
</body>
";
?>