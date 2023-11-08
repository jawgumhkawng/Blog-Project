<?php 
session_start();

require 'config/config.php';
require 'config/common.php';

if (empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])) {

  header('Location: login.php');

}


$stmt = $pdo->prepare("SELECT * FROM posts  WHERE id=".$_GET['id']);
$stmt->execute();
$resultD = $stmt->fetchAll(); 

$stmt = $pdo->prepare("SELECT * FROM users WHERE id =".$resultD[0]['author_id']);
$stmt->execute();
$resultAd = $stmt->fetchAll();


$blogId = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM comments WHERE post_id=$blogId");
$stmt->execute();
$resultCmt = $stmt->fetchAll();


$stmt = $pdo->prepare("SELECT * FROM users WHERE id=".$_SESSION['user_id']);
$stmt->execute();
$result = $stmt->fetchAll();
$user_img = $result[0]['image'];


$resultAu = [];
if ($resultCmt) {
 foreach ($resultCmt as $key => $value) {
  $authorId = $resultCmt[$key]['author_id'];
  $stmt = $pdo->prepare("SELECT * FROM users WHERE id=$authorId");
  $stmt->execute();
  $resultAu[] = $stmt->fetchAll();
 }
}

$blogId = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM comments WHERE post_id=".$_GET['id']);
$stmt->execute();
$rawResult = $stmt->fetchAll();

$total_comment = count($rawResult);


if ($_POST) {

  if (empty($_POST['comment']))   {
    if (empty($_POST['comment'])) {
      $cmtError = 'Comment Cannot be Null';
    }
   } else {
      $comment = $_POST['comment'];

//other way to bind 
$stmt = $pdo->prepare("INSERT INTO comments(content,author_id,user_image,post_id) VALUES (?,?,?,?)");
$resultCm = $stmt->execute([$comment,$_SESSION['user_id'],$user_img,$blogId]);



  
  if ($result) {
    
    header('Location: blogDetail.php?id='.$blogId);
   }

    }




}   

$stmt = $pdo->prepare("UPDATE posts SET comments=? WHERE id=".$_GET['id']);
$resultComment = $stmt->execute([$total_comment]);

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
  <title>A Blog | blogDetail Page</title>

  <link rel="icon" href="admin/images/j'web favicon.png">
  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
</head>
<body class="hold-transition sidebar-collapse layout-top-nav">
<div class="wrapper">

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand-md navbar-light navbar-white fixed-top">
    <div class="container">
      <a href="index.php" class="navbar-brand">
        <img src="dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
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
        <!-- <form class="form-inline ml-0 ml-md-3" action="post">
          <div class="input-group input-group-sm">
            <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search" name="search">
            <div class="input-group-append">
              <button class="btn btn-navbar" >
                <i class="fas fa-search"></i>
              </button>
            </div>
          </div>
        </form> -->
      </div>

      
        <!-- Notifications Dropdown Menu -->
       
        
      </ul>
    </div>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4" style="position: fixed !important;">
    <!-- Brand Logo -->
    <a href=".index3.html" class="brand-link">
      <img src="dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
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
                        <i class="fas fa-envelope"></i> &nbsp; : &nbsp;<a class="float"><?= escape($value['email']) ?></a>
                    </li>
                    <li class="list-group-item">
                        <i class="fas fa-phone-square-alt"></i> &nbsp; :  &nbsp;<a class="float"><?= escape($value['phone']) ?></a>
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
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item">
            <a href="index.php" class="nav-link ml-3">
                <i class="fas fa-arrow-alt-circle-left"></i>&nbsp;&nbsp;
              <p>Back</p>
            </a>
          </li>
      </nav>
    </div>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header mb-5">
    
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
      <div class="container">
        <div class="row ">
           <div class="col-12 ml-lg-5 ml-xl-0 " >
                  <!-- Box Comment -->
                <div class="card card-widget col-lg-10 col-xl-12 ml-xl-0 ml-lg-5">
                  <span class="ml-2 mt-2"><a href="index.php" style="font-size: large;"><i class="fas fa-arrow-left"></i></a></span>

                    <div class="card-header ">
                      <div class="user-block" style="position: absolute; top: -25px; left: 39px">
                        <img class="img-circle shadow-lg border-2 " src="profile_image/<?= $resultAd[0]['image'] ?>" alt="User Image">
                        <span class="username"><a href="#"><?= escape($resultAd[0]['name']) ?></a></span>
                        <span class="description"><?= escape($resultD[0]['created_at']) ?></span>
                      </div>
                      <!-- /.user-block -->
                      <!-- <div class="card-tools mt-0">
                        <button type="button" class="btn btn-tool" title="Mark as read">
                          <i class="far fa-circle"></i>
                        </button>
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                          <i class="fas fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-tool" data-card-widget="remove">
                          <i class="fas fa-times"></i>
                        </button>
                      </div> -->
                      <!-- /.card-tools -->
                    </div>
                    <!-- /.card-header -->

                    <?php if($resultD) : ?>
                      
                    <div class="card-body col-12">
                    
                     
                     <img class="img-fluid pad rounded" src="admin/images/<?= $resultD[0]['image'] ?>" alt="Photo" height="100%" width="100%"><br><br>

                      <hr>

                      <h2 class="mt-3 text-bold"><?= escape($resultD[0]['title']) ?></h2>
      
                      <p><?= escape($resultD[0]['content']) ?></p>

                      <?php endif ?>
                      <!-- <button type="button" class="btn btn-default btn-sm"><i class="fas fa-share"></i> Share</button>
                      <button type="button" class="btn btn-default btn-sm"><i class="far fa-thumbs-up"></i> Like</button>
                      <span class="float-right text-muted">127 likes - 3 comments</span> -->
                    </div>
                    <hr>
                    <!-- /.card-body -->

                    <h4 class="ml-3  text-bold">Comments (<span class="text-danger"><?= $total_comment ?></span>)</h4><br>
                    <?php if ($resultCmt) : ?>
               <div class="card-footer card-comments  mb-4">
                <?php foreach ($resultCmt as $key => $value) : ?>
                  <div class="card-comment ">
                  <!-- User image -->
                    <img class="img-circle img-sm" src="profile_image/<?= $value['user_image'] ?>" alt="User Image">
                  
                  <div class="comment-text">
                    <span class="username">
                    <?= escape($resultAu[$key][0]['name']) ?>
                      <span class="text-muted float-right "><?= escape($value['created_at']) ?></span>
                    </span><!-- /.username -->
                    <?=  escape($value['content']) ?>
                  </div>
                  <!-- /.comment-text -->
                </div>
                <?php endforeach ?>
                <!-- /.card-comment -->
          
                <!-- /.card-comment -->
              </div>
              <?php endif ?>

           
            <!-- /.card-footer -->
            
            <p class="text-danger d-inline ml-3"><?= empty($cmtError) ? '' : '*'.$cmtError ?></p>
                    <div class="card-footer col-lg-12 mb-4">
                      <form action="" method="post">
                     <input name="_token" type="hidden" value="<?= $_SESSION['_token'] ?>">
                        <img class="img-fluid img-circle img-sm" src="profile_image/<?= $result[0]['image'] ?>" alt="Alt Text">
                        <!-- .img-push is used to add margin to elements next to floating images -->
                        <div class="img-push">  
                          <input type="text" name="comment" class="form-control form-control-sm" placeholder="Press enter to post comment" >
                        </div>
                      </form>
                    </div>
                    <!-- /.card-footer -->
                  </div>
                  <!-- /.card -->
                </div>
                <!-- /.col -->
                
                <!-- /.col -->
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
  <a id="back-to-top" href="#" class="btn btn-primary back-to-top" role="button" aria-label="Scroll to top" style="scroll-behavior: smooth !important;">
    <i class="fas fa-chevron-up"></i>
  </a>
  <!-- Main Footer -->
  <footer class="main-footer">
    <!-- To the right -->
    <div class="float-right d-none d-sm-inline">
      Anything you want <strong>J web</strong>
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