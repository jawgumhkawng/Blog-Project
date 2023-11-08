<?php 
session_start();

require '../config/config.php';
require '../config/common.php';

if (empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])) {

  header('Location: login.php');

}
if ($_SESSION['role'] != 1) {
    header('Location: login.php');
  }

if ($_POST) {

    if (empty($_POST['name']) || empty($_POST['email']) || empty($_POST['phone']) || empty($_POST['work']) || empty($_POST['address']))   {
        if (empty($_POST['name'])) {
          $nameError = 'Name Not Update Yet!';
        }
        if (empty($_POST['email'])) {
          $emailError = 'Email Not Update Yet!';
        }
        if (empty($_POST['phone'])) {
          $phoneError = 'Phone Not Update Yet!';
        }
        if (empty($_POST['work'])) {
          $workError = 'Work Not Update Yet!';
        }
        if (empty($_POST['address'])) {
          $addressError = 'Address Not Update Yet!';
        }
       
        
      } else {
        $id = $_POST['id'];
        $name = $_POST['name'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $work = $_POST['work'];
        $address = $_POST['address'];
        $image = $_FILES['image']['name'];
        if (empty($_POST['role'])) {
            $role = 0;
        } else {
            $role = 1;
        }
    
    
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email=:email AND id!=:id ");
    
    $stmt->execute(array(':email'=>$email,':id'=>$id));
    
    $user = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if ($user) {
        echo "<script>alert('Email Duplicated!')</script>";
    
    }else {
    
        if ($_FILES['image']['name'] != null) {
            $file = '../profile_image/'.($_FILES['image']['name']);
            $imageType = pathinfo($file,PATHINFO_EXTENSION);
    
            if ($imageType != 'png' && $imageType != 'jpg' && $imageType != 'jpeg') {
            echo"<script>alert('Image must be png,jpg or jpeg!')</script>";
            
    
            }else {
                    
                    $image = $_FILES['image']['name'];
                    move_uploaded_file($_FILES['image']['tmp_name'],$file);
    
                    $stmt = $pdo->prepare("UPDATE users SET name='$name',email='$email',phone='$phone',work='$work',address='$address',role='$role',image='$image' WHERE id='$id'");
                    $result = $stmt->execute();
                    
                    
                    if ($result) {
                    echo"<script>alert('Successfully Updated');'window.location.href='users_list.php'</script>";
                    }
                }
    
        }else {
    
            $stmt = $pdo->prepare("UPDATE users SET name='$name',email='$email',phone='$phone',work='$work',address='$address',role='$role' WHERE id='$id'");
            $result = $stmt->execute();
    
            if ($result) {
                
                echo "<script>alert('Successfully Updated');window.location.href='users_list.php'</script>";
            }
        }
    
    }
    
      }

   

}

$stmt = $pdo->prepare("SELECT * FROM users WHERE id=".$_GET['id']);

$stmt->execute();

$result = $stmt->fetchAll();

?>


      

<!-- header -->

<?php include('header.php'); ?>

  <!-- Content Wrapper. Contains page content -->
                <div class="content-wrapper">
                    <!-- Content Header (Page header) -->
                    <div class="content-header">
                    <div class="container-fluid">
                        <div class="row mb-2">
                            <div class="col-sm-12 mt-3 mb-3">
                                <h1 class="mb-3 text-bold d-flex justify-content-center align-items-center btn btn-info " >Edit User &nbsp;<i class="fa-solid fas fa-user"></i></h1>
                            </div>

                            <h3 class="ml-2  text-primary">User Edit</h3>
                         
                        <div class="col-md-12 col-lg-12 col-12 ">
                            <div class="card">               
                            <div class="card-body">

                            <form action="" method="post" enctype="multipart/form-data">
                            <input name="_token" type="hidden" value="<?= $_SESSION['_token'] ?>">
                            <input type="hidden" name="id" value="<?= $result[0]['id'] ?>">
                                    <div class="card-body">
                                    <div class="mb-5">
                                            <label for="exampleFormControlTextarea1" class="form-label">Image</label><br>
                                            <img src="../profile_image/<?= $result[0]['image'] ?>" width="200"  height="170" style="over-flow:hidden; border-radius:10px" class="rounded-3"><br><br>
                                            <input type="file" class="" name="image" id="exampleFormControlInput1" placeholder="" >
                                        </div>
                                        <div class="mb-4">
                                        <label for="exampleFormControlInput1" class="form-label ">Name</label><p class="text-danger d-inline ml-3"><?= empty($nameError) ? '' : '*'.$nameError ?></p>
                                        <input type="text" class="form-control" name="name" id="exampleFormControlInput1" value="<?= escape($result[0]['name']) ?>" placeholder="name..." >
                                        </div>

                                        <div class="mb-3">
                                            <label for="exampleFormControlTextarea1" class="form-label">Email</label><p class="text-danger d-inline ml-3"><?= empty($emailError) ? '' : '*'.$emailError ?></p>
                                            <input type="email" class="form-control" name="email" id="exampleFormControlInput1" value="<?= escape($result[0]['email']) ?>" placeholder="email..." >
                                        </div>
                                        <div class="mb-3">
                                            <label for="exampleFormControlTextarea1" class="form-label">Phone</label><p class="text-danger d-inline ml-3"><?= empty($phoneError) ? '' : '*'.$phoneError ?></p>
                                            <input type="text" class="form-control" name="phone" id="exampleFormControlInput1" value="<?= escape($result[0]['phone']) ?>" placeholder="phone..." >
                                        </div>
                                        <div class="mb-3">
                                            <label for="exampleFormControlTextarea1" class="form-label">Work</label><p class="text-danger d-inline ml-3"><?= empty($workError) ? '' : '*'.$workError ?></p>
                                            <input type="text" class="form-control" name="work" id="exampleFormControlInput1" value="<?= escape($result[0]['work']) ?>" placeholder="work..." >
                                        </div>
                                        <div class="mb-3">
                                            <label for="exampleFormControlTextarea1" class="form-label">Address</label><p class="text-danger d-inline ml-3"><?= empty($addressError) ? '' : '*'.$addressError ?></p>
                                            <input type="text" class="form-control" name="address" id="exampleFormControlInput1" value="<?= escape($result[0]['address']) ?>" placeholder="address..." >
                                        </div>
                                        <div class="mb-3">
                                            <label for="exampleFormControlTextarea1" class="form-label">Set Admin or User</label><br>
                                            <?php if(escape($result[0]['role']) == 1)  : ?>
                                              
                                              <input type="checkbox" class="" name="role" id="exampleFormControlInput1" value="1" placeholder="admin or user" checked>
                                              <span class="text-success">He is admin</p>
                                              <?php else : ?>
                                                
                                                <input type="checkbox" class="" name="role" id="exampleFormControlInput1" value="1" placeholder="admin or user" >
                                                <span class="text-primary">He is user</p>
                                            <?php endif ?>
                                         </div>



                                        <div class="mb-2">
                                         <a href="users_list.php" class="btn btn-outline-secondary  mr-3 "><i class="fa-solid fa-backward">&nbsp;&nbsp;</i>BACK </a>            
                                        <input type="submit" class="btn btn-success" value="SUBMIT">
                                                                               
                                        </div>
                                    </div>   
                                </form>
                            </div>
                            </div>  
                            </div>
                        </div>
                        </div><!-- /.row -->
                    </div><!-- /.container-fluid -->
                    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        <div class="row">
  
          <!-- /.col-md-6 -->
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->


  <!-- Main Footer -->
 <?php include('footer.php'); ?>
