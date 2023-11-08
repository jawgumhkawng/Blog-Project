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
  if (empty($_POST['title']) || empty($_POST['content']) || empty($_FILES['image']))   {
    if (empty($_POST['title'])) {
      $titleError = 'Title Not Update Yet!';
    }
    if (empty($_POST['content'])) {
      $contentError = 'Content Not Update Yet!';
    }
   
  }else {

    $id = $_POST['id'];
    $title = $_POST['title'];
    $content = $_POST['content'];


    if ($_FILES['image']['name'] != null) {
        $file = 'images/'.($_FILES['image']['name']);
        $imageType = pathinfo($file,PATHINFO_EXTENSION);

        if ($imageType != 'png' && $imageType != 'jpg' && $imageType != 'jpeg') {
        echo"<script>alert('Image must be png,jpg or jpeg!')</script>";
        

        }else {
                
                $image = $_FILES['image']['name'];
                move_uploaded_file($_FILES['image']['tmp_name'],$file);

                $stmt = $pdo->prepare("UPDATE posts SET title='$title',content='$content',image='$image' WHERE id='$id'");
                $result = $stmt->execute();
                
                
                if ($result) {
                echo"<script>alert('Successfully Updated');'window.location.href='index.php'</script>";
                }
            }

    }else {

        $stmt = $pdo->prepare("UPDATE posts SET title='$title',content='$content' WHERE id='$id'");
        $result = $stmt->execute();

        if ($result) {
            
            echo "<script>alert('Successfully Updated');window.location.href='index.php'</script>";
        }
    }
  }

  }


$stmt = $pdo->prepare("SELECT * FROM posts WHERE id=".$_GET['id']);

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
                                <h1 class="mb-3 text-bold d-flex justify-content-center align-items-center btn btn-info " >Add New &nbsp;<i class="fa-solid fa-file-circle-plus"></i></h1>
                            </div>
                         
                        <div class="col-md-12 col-lg-12 col-12 ">
                            <div class="card">
                              
                            <!-- /.card-header -->
                                <form action="" method="post" enctype="multipart/form-data">
                                <input name="_token" type="hidden" value="<?= escape($_SESSION['_token']) ?>">
                                <input type="hidden" name="id" value="<?= $result[0]['id'] ?>">
                                    <div class="card-body">
                                        <div class="mb-4">
                                        <label for="exampleFormControlInput1" class="form-label ">Title</label><p class="text-danger d-inline ml-3"><?= empty($titleError) ? '' : '*'.$titleError ?></p>
                                        <input type="text" class="form-control" name="title" id="exampleFormControlInput1" value="<?= escape($result[0]['title']) ?>" placeholder="title..." >
                                        </div>

                                        <div class="mb-3">
                                            <label for="exampleFormControlTextarea1" class="form-label">Content</label><p class="text-danger d-inline ml-3"><?= empty($contentError) ? '' : '*'.$contentError ?></p>
                                            <textarea class="form-control" name="content" id="exampleFormControlTextarea1" rows="3" value="" placeholder="content..." ><?= escape($result[0]['content']) ?></textarea>
                                        </div>

                                        <div class="mb-5">
                                            <label for="exampleFormControlTextarea1" class="form-label">Image</label><br>
                                            <img src="images/<?= $result[0]['image'] ?>" width="170"  height="170" style="border:10px"><br><br>
                                            <input type="file" class="" name="image" id="exampleFormControlInput1" placeholder="" >
                                        </div>

                                        <div class="mb-2">
                                         <a href="index.php" class="btn btn-outline-secondary  mr-3 "><i class="fa-solid fa-backward">&nbsp;&nbsp;</i>BACK </a>            
                                        <input type="submit" class="btn btn-success" value="SUBMIT">
                                                                               
                                        </div>
                                    </div>   
                                </form>
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
