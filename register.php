 <?php 

session_start();

require 'config/config.php';
require 'config/common.php';

if ($_POST) {

  if (empty($_POST['name']) || empty($_POST['email']) || empty($_POST['phone']) || strlen($_POST['password']) < 8 || empty($_POST['work']) || empty($_POST['address']))   {
    if (empty($_POST['name'])) {
      $nameError = 'Name Required!';
    }
    if (empty($_POST['email'])) {
      $emailError = 'Email Required!';
    }
    if (empty($_POST['phone'])) {
      $phoneError = 'Phone Required!';
    }
    if (empty($_POST['password'])) {
      $passwordError = 'password Required!';
    }
    if (strlen($_POST['password']) < 8) {
      $passwordError = 'Password should be 8 characters at least!';
    }
    if (empty($_POST['work'])) {
      $workError = 'Work Required!';
    }
    if (empty($_POST['address'])) {
      $addressError = 'Address Required!';
    }
   
    
  } else {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = password_hash($_POST['password'],PASSWORD_DEFAULT);
    $work = $_POST['work'];
    $address = $_POST['address'];
    $role = 0;
    $image = $_FILES['image']['name'];

    $file = 'profile_image/'.($_FILES['image']['name']);
  $imageType = pathinfo($file,PATHINFO_EXTENSION);

  if ($imageType != 'png' && $imageType != 'jpg' && $imageType != 'jpeg') {
    echo"<script>alert('Image must be png,jpg or jpeg!')</script>";
  }else {
    

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email=:email");

    $stmt->bindValue(':email',$email);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        echo"<script>alert('This User already exist!')</script>";
    } else {
        move_uploaded_file($_FILES['image']['tmp_name'],$file);

    $stmt = $pdo->prepare("INSERT INTO users(name,email,phone,role,password,work,address,image) VALUES (:name,:email,:phone,:role,:password,:work,:address,:image)");
    $result = $stmt->execute(
      array(':name'=>$name,':email'=>$email,':phone'=>$phone,':role'=>$role,':password'=>$password,':work'=>$work,':address'=>$address,':image'=>$image)
    );
 
   
    
    if ($result) {
      echo"<script>alert('Registeration Success!');window.location.href='login.php';</script>";
      
     }
    }

    
  }
   
    
  }
 
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Blog Page | Sgin up</title>

  <link rel="icon" href="admin/images/j'web favicon.png">
  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <!-- /.login-logo -->
  <div class="card card-outline card-primary">
    <div class="card-header text-center">
      <a href="#" class="h1"><b>Blogs |</b> register</a>
    </div>
    <div class="card-body">
      <p class="login-box-msg">Sign up to start your session</p>

      <form action="register.php" method="post" class="mb-5" enctype="multipart/form-data">
      <input name="_token" type="hidden" value="<?= $_SESSION['_token'] ?>">
      <p class="text-danger d-inline "><?= empty($nameError) ? '' : '*'.$nameError ?></p>
        <div class="input-group mb-3">
          <input type="text" class="form-control" name="name" placeholder="Name" required>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-user"></span>
            </div>
          </div>
        </div>
        <p class="text-danger d-inline "><?= empty($emailError) ? '' : '*'.$emailError ?></p>
        <div class="input-group mb-3">
          <input type="email" class="form-control" name="email" placeholder="Email" required>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>
        <p class="text-danger d-inline "><?= empty($phoneError) ? '' : '*'.$phoneError ?></p>
        <div class="input-group mb-3">
          <input type="number" class="form-control" pattern=" [0-9] {3}- [0-9] {2}- [0-9] {3}" name="phone" placeholder="Phone No" required>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-phone"></span>
            </div>
          </div>
        </div>
        <p class="text-danger d-inline "><?= empty($passwordError) ? '' : '*'.$passwordError ?></p>
        <div class="input-group mb-3">
          <input type="password" class="form-control" name="password" placeholder="Password" required>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <p class="text-danger d-inline "><?= empty($workError) ? '' : '*'.$workError ?></p>
        <div class="input-group mb-3">
          <input type="text" class="form-control" name="work" placeholder="Work at" required>
          <div class="input-group-append">
            <div class="input-group-text">
            <i class="fas fa-briefcase"></i>
            </div>
          </div>
        </div>
        <p class="text-danger d-inline "><?= empty($addressError) ? '' : '*'.$addressError ?></p>
        <div class="input-group mb-3">
          <input type="text" class="form-control" name="address" placeholder="Address" required>
          <div class="input-group-append">
            <div class="input-group-text">
            <i class="fas fa-map-marked-alt"></i>
              </div>
          </div>
        </div>
        <div class="input-group mb-3">
        <label for="exampleFormControlTextarea1" class="form-label">Image</label>
        <input type="file" class="" name="image" id="exampleFormControlInput1" placeholder="" required>

        </div>
        <div class="row ">
          <!-- /.col -->
          <div class="col-10 mt-4 ml-4 ">
            <button type="submit" class="btn btn-outline-primary btn-block">Sign up </button><br><br>
            <p>Have an account?Please <a href="login.php">Login Here.</a></p>
          </div>
          
        </div>
      </form>

      
      <!-- /.social-auth-links -->
      <!-- <p class="mb-0">
        <a href="register.html" class="text-center">Register a new membership</a>
      </p> -->
    </div>
    <!-- /.card-body -->
  </div>
  <!-- /.card -->
</div>
<!-- /.login-box -->

<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
</body>
</html>
