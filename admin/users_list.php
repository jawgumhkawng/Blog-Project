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
            
 
  if (!empty($_POST["search"])) {
    setcookie("search",$_POST["search"], time() + (89400 * 30), "/");
  } else {
  if (empty($_GET['pageno'])) {
   unset($_COOKIE["search"]);
   setcookie("search", null, -1, "/");
  }
}

?>
<?php include('header.php'); ?>
<?php
            $stmt = $pdo->prepare("SELECT * FROM users ORDER BY id DESC");
            $stmt->execute();
            $result = $stmt->fetchAll();

          
 
                if (!empty($_GET['pageno'])) {
                  $pageno = $_GET['pageno'];
                }else{
                  $pageno = 1;
                }
          
                $numOfrecs = 5;
                $offset = ($pageno - 1) * $numOfrecs;
                
                if (empty($_POST['search']) && empty($_COOKIE['search'])) {
                        
                    $stmt = $pdo->prepare("SELECT * FROM users ORDER BY id DESC");
                    $stmt->execute();
                    $rawResult = $stmt->fetchAll();
            
                    $total_pages = ceil(count($rawResult) / $numOfrecs);
            
                    $stmt = $pdo->prepare("SELECT * FROM users ORDER BY id DESC LIMIT $offset,$numOfrecs");
                    $stmt->execute();
                    $result = $stmt->fetchAll();
                } else {
                    $searchKey = !empty($_POST['search']) ? $_POST['search'] : $_COOKIE['search'] ;

                    $stmt = $pdo->prepare("SELECT * FROM users WHERE name LIKE '%$searchKey%' ORDER BY id DESC");
                   
                    $stmt->execute();
                    $rawResult = $stmt->fetchAll();            
                             
                    $total_pages = ceil(count($rawResult) / $numOfrecs);
        
                    $stmt = $pdo->prepare("SELECT * FROM users WHERE name LIKE '%$searchKey%' ORDER BY id DESC LIMIT $offset,$numOfrecs ");
                    $stmt->execute();
                    $result = $stmt->fetchAll();
                }

          
          
        
              ?>


      

   <!-- header -->
 


  <!-- Content Wrapper. Contains page content -->
                <div class="content-wrapper">
                    <!-- Content Header (Page header) -->
                    <div class="content-header">
                      <div class="container-fluid">
                        <div class="row mb-2">
                            <div class="col-sm-12 mt-3 mb-3">
                                <h1 class="mb-3 text-bold d-flex justify-content-center align-items-center btn btn-info " >Edit User &nbsp;<i class="fa-solid fas fa-user"></i></h1>
                            </div>
                            <h3 class="text-primary ml-2">User Lists</h3>
                            <div class="row ml-2 " >
                            <nav aria-label="Page navigation example " style="float:right !important; position:absolute; right:20px;" >
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
                        <div class="col-md-12 col-lg-12 col-12 ">
                            <div class="card">
                              
                            <!-- /.card-header -->
                            <div class="card-body">
                                <table class="table table-bordered">
                                <thead>

                                    <tr>
                                    <th style="width: 5%">#</th> 
                                    <th style="width: 7%">Photo</th>
                                    <th style="width: 27%">Name</th>
                                    <th style="width: 20%">Email</th>
                                    <th style="width: 15%">Phone</th>
                                    <th style="width: 10%">Role</th>
                                    <th style="width: 16%">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if ($result) : ?>
                                    <?php $i = 1; ?>
                                    <?php foreach ($result as  $value) : ?>

                                        <tr>
                                            <td><?= $i; ?></td>
                                            <td><img src="../profile_image/<?= $value['image'] ?>" width="30"  height="30" style="border:10px !important;" class="rounded-circle shadow"></td>
                                            <td><?= escape($value['name']) ?></td>   
                                            <td><?= escape($value['email']) ?></td>   
                                            <td><?= escape($value['phone']) ?></td> 
                                            <?php if($value['role'] === 1) : ?>
                                                <td class="text-bold text-success">Admin</td>
                                            <?php else  : ?> 
                                                <td class="text-bold text-primary">User</td>
                                                <?php endif ?>  
                                            
                                            
                                            <td class="">
                                            <a href="user_edit.php?id=<?= $value['id'] ?>" type="button" class="btn btn-outline-warning btn-sm btn-group ml-3 "><i class="fa-solid fa-pen-to-square "></i></a>
                                            <a href="user_del.php?id=<?= $value['id'] ?>" type="button" class="btn btn-outline-danger btn-sm btn-group" onclick="return confirm('Are you sure you want to delete this blog!')">
                                            <i class="fa-solid fa-trash"></i></a>
                                            
                                            </td>
                                        </tr>  

                                        <?php $i++; ?>
                                        <?php endforeach ?>
                                    <?php endif ?>
                                </tbody>
                          </table><br>
                
                <?php if(!$result) : ?>

                <h4 class="text-uppercase text-danger" style="margin-left: 300px;">Result Not Found:(</h4>

                <?php endif ?>
                    </div>
                  </div> 
              </div>
          </div>
        </div><!-- /.row -->
   
      
    <!-- /.content-header -->
    </div><!-- /.container-fluid -->
    </div>
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