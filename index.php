<?php 
session_start();

require 'config/config.php';
require 'config/common.php';

if (empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])) {

  header('Location: login.php');

}


  $stmt = $pdo->prepare("SELECT * FROM users WHERE id=".$_SESSION['user_id']);
  $stmt->execute();
  $result = $stmt->fetchAll();

  if (!empty($_POST["search"])) {
    setcookie("search",$_POST["search"], time() + (89400 * 30), "/");
  } else {
  if (empty($_GET['pageno'])) {
   unset($_COOKIE["search"]);
   setcookie("search", null, -1, "/");
  }
  
  }

?>

<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>A Blog | Blog Page</title>

  <link rel="icon" href="admin/images/j'web favicon.png">
  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <style>
    .image {
      background-image: linear-gradient(to right, #d7d2cc 0%, #304352 100%);
      background-color: rgb(255 255 255 / 0.3);
      opacity: 0.9;     
    }
    .card-img-top {
     
      opacity: 0.1;
    }
    .cc{
      z-index: 1;
      opacity: 1;
    }
    .example{
      position: absolute !important;
      right: 0px !important;
    }

  </style>
</head>
<body class="hold-transition sidebar-collapse layout-top-nav">
<div class="wrapper">

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand-md navbar-light navbar-white fixed-top">
    <div class="container">
      <a href="index.php" class="navbar-brand">
        <img src="admin/images/j'web logo.png" alt="AdminLTE Logo" class=" elevation-3 rounded-3 " style="opacity: .8; width: 50px;height: 25px; border-radius: 7px; border: 2px ">
        <span class="brand-text font-weight-light">A Blog</span>
      </a>

      <button class="navbar-toggler order-1" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
          </li>
      </button>

      <div class="collapse navbar-collapse order-3" id="navbarCollapse">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
          <li class="nav-item ml-5">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
          </li>
          
        <!-- SEARCH FORM -->
        <form class="form-inline ml-0 ml-md-3" method="post" action="index.php">
          <div class="input-group input-group-sm">
            <input class="form-control form-control-navbar" type="search" name="search" placeholder="Search" aria-label="Search">
            <input name="_token" type="hidden" value="<?= $_SESSION['_token'] ?>">

            <div class="input-group-append">
              <button class="btn btn-navbar" type="submit"  >
                <i class="fas fa-search"></i>
              </button>
            </div>
          </div>
        </form>
    
      </div>

      
        <!-- Notifications Dropdown Menu -->
       
        
      </ul>
    </div>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4" style="position: fixed !important;">
    <!-- Brand Logo -->
    <a href="index.php" class="brand-link">
      <img src="admin/images/j'web logo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3 mt-1" style="opacity: .8; width: 50px; border-radius: 7px; border: 2px">
      <span class="brand-text font-weight-light">A Blog</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <!-- Profile Image -->
        <div class="card card-primary  bg-dark">
            <div class="card-body box-profile">

            <?php if($result) : ?>
              <?php foreach ($result as $value) : ?>

                <div class="text-center">
                <img class="img-circle elevation-2"
                     src="profile_image/<?= $value['image'] ?>"
                     alt="User profile picture" style="height: 70px !important; width: 70px !important;" >
              </div>

              <h3 class="profile-username text-center"><?= escape($value['name']) ?></h3>

              <p class="text-muted text-center"><?= escape($value['work']) ?></p>

              <ul class="list-group  mb-3">
                <li class="list-group-item">
                    <i class="fas fa-envelope"></i> &nbsp; : &nbsp;<a class="float"><?= escape( $value['email']) ?></a>
                </li>
                <li class="list-group-item">
                    <i class="fas fa-phone-square-alt"></i> &nbsp; :  &nbsp;<a class="float"><?=  escape($value['phone']) ?></a>
                </li>
                <li class="list-group-item">
                    <i class="fas fa-map-marked-alt"></i> &nbsp; :  &nbsp;<a class="float"><?= escape($value['address']) ?></a>
                </li>
              </ul>

                <?php endforeach ?>
              <?php endif ?>
              

              <a href="#" class="btn btn-primary btn-block"><b>Follow</b></a>
            </div>
            <!-- /.card-body -->
          </div>
      </div>


      <!-- Sidebar Menu -->
      <nav class="mt-2 mb-3">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          
          <li class="nav-item ">
            <a href="logout.php" class="nav-link active bg-danger">
                <i class="fas fa-sign-out-alt"></i>&nbsp;&nbsp;
              <p>Logout</p>
              
            </a>
          </li>
         
          </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0"> Top Navigation <small>Example 3.0</small></h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item"><a href="#">Layout</a></li>
              <li class="breadcrumb-item active">Top Navigation</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
       

    <!-- Main content -->
    <div class="content">
      <div class="container">
        <div class="row">
        
          <div class="card card-success mb-4">
            <div class="card-body" >
              <div class="row">

            <?php 
                
                if (!empty($_GET['pageno'])) {
                  $pageno = $_GET['pageno'];
                }else{
                  $pageno = 1;
                }
          
                $numOfrecs = 6;
                $offset = ($pageno - 1) * $numOfrecs;
          
                if (empty($_POST['search']) && empty($_COOKIE['search'])) {
                  $stmt = $pdo->prepare("SELECT * FROM posts ORDER BY id DESC");
                  $stmt->execute();
                  $rawResult = $stmt->fetchAll();
          
                $total_pages = ceil(count($rawResult) / $numOfrecs);
          
                $stmt = $pdo->prepare("SELECT * FROM posts ORDER BY id DESC LIMIT $offset,$numOfrecs");
                $stmt->execute();
                $result = $stmt->fetchAll();
                } else {
                  $searchKey = !empty($_POST['search']) ? $_POST['search'] : $_COOKIE['search'];

                  $stmt = $pdo->prepare("SELECT * FROM posts WHERE title LIKE '%$searchKey%' ORDER BY id DESC ");
                 
                  $stmt->execute();
                  $rawResult = $stmt->fetchAll();            
                           
                  $total_pages = ceil(count($rawResult) / $numOfrecs);
      
                  $stmt = $pdo->prepare("SELECT * FROM posts WHERE title LIKE '%$searchKey%' ORDER BY id DESC LIMIT $offset,$numOfrecs ");
                  $stmt->execute();
                  $result = $stmt->fetchAll();
                }
          
        
              ?>
                 <?php if(empty($rawResult)) : ?>
                 <?php echo"<script>alert('Result Not Found!');window.location.href='index.php?pageno=0';</script>";?>
                  <?php endif ?>

                      <?php if ($result) : ?>
                      <?php $i = 1; ?>
                      <?php foreach ($result as  $value) : ?>
                       
                      <div class="col-md-12 col-lg-4 col-xl-4 mb-4 mt-1  " style="height: 250px !important; width: 850px !important;"> 
                      
                      <div class="card mb-2 image" style="width: 100% !important; height: 100% !important;">
                      
                      <img class="card-img-top rounded shadow-lg " src="admin/images/<?=$value['image'] ?>" alt="Dist Photo 3"style="width: 100% !important; height: 100% !important;" >
                      
                      <a href="blogDetail.php?id=<?= $value['id'] ?>" class="cc"> 
                        <div class="card-img-overlay mb-4">
                          <h5 class="card-title text-white text-bold" style="margin-top: auto !important; font-size :27px">-<?= escape(substr($value['title'],0,16)) ?>-</h5>
                          <p class="card-text pb-1 pt-1 text-dark"> <?= escape(substr($value['content'],0,140)) ?>.....</p>
                          <a href="#" class="text-warning" style="position: absolute; bottom:5px"><?= escape($value['created_at']) ?></a>
                        </div>
                      </a>
                      </div>
                     
                    </div>
                    
                    <?php $i++; ?>
                      <?php endforeach ?>
                    <?php endif ?>
               
                </div>
                </div>
              </div>

              <div class="row" >
              <nav aria-label="Page navigation example " style="float:right !important;" >
                <ul class="pagination">
                  <li class="page-item  <?php if($pageno == 1){ echo 'disabled';} ?>"><a class="page-link" href="?pageno=1" aria-label="Previous">First </a></li>

                  <li class="page-item  <?php if($pageno <= 1){ echo 'disabled';} ?>">
                      <a class="page-link" href="<?php if($pageno <= 1) { echo '#';}else{ echo "?pageno=".($pageno-1);} ?>">
                  <span aria-hidden="true">&laquo;</span></a></li>

                  <li class="page-item active"><a class="page-link" href="#"><?= $pageno; ?></a></li>

                  <li class="page-item  <?php if($pageno >= $total_pages){ echo 'disabled';} ?>">
                     <a class="page-link" href="<?php if($pageno >= $total_pages) { echo '#';}else{ echo "?pageno=".($pageno+1);} ?>"> 
                  <span aria-hidden="true">&raquo;</span></a></li>

                  <li class="page-item  <?php if($pageno == $total_pages){ echo 'disabled';} ?>"><a class="page-link" href="?pageno=<?= $total_pages ?>" aria-label="Next">Last</a></li>
                </ul>
              </nav>
              </div>
            </div>
          </div>
          <!-- /.col-md-6 -->
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->

  <!-- Main Footer -->
  <footer class="main-footer">
    <!-- To the right -->
    <div class="float-right d-none d-sm-inline">
      Anything you want
    </div>
    <!-- Default to the left -->
    <strong>Copyright &copy; 2023 <a href="#">J web</a>.</strong> All rights reserved.
  </footer>
</div>
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->

<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="dist/js/demo.js"></script>
</body>
</html>
