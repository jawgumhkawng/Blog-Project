
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


<!-- header -->


         
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-12 ">
            <h1 class="mb-3 float-right mr-3">Blogs Lists</h1>
            <a href="add.php" class="btn btn-outline-success ">New Blogs Post &nbsp; <i class="fa-solid fa-file-circle-plus"></i></a> 

          </div>
          <?php 

              $stmt = $pdo->prepare("SELECT * FROM users WHERE role = 1 ");
              $stmt->execute();
              $resultAd = $stmt->fetchAll();

            

            if(!empty($_GET['pageno'])) {
                $pageno = $_GET['pageno'];
              } else {
                $pageno = 1;
              }
            $numOfrecs = 5 ;
            $offset = ($pageno - 1) * $numOfrecs;

                
            if (empty($_POST['search']) && empty($_COOKIE['search'])) {
            $stmt = $pdo->prepare("SELECT * FROM posts ORDER BY id DESC");
            $stmt->execute();
            $rawResult = $stmt->fetchAll();            
                     
            $total_pages = ceil(count($rawResult) / $numOfrecs);

            $stmt = $pdo->prepare("SELECT * FROM posts ORDER BY id DESC LIMIT $offset,$numOfrecs ");
            $stmt->execute();
            $result = $stmt->fetchAll();

           } else {

            $searchKey = !empty($_POST['search']) ? $_POST['search'] : $_COOKIE['search'] ;

            $stmt = $pdo->prepare("SELECT * FROM posts WHERE title LIKE '%$searchKey%' ORDER BY id DESC");
           
            $stmt->execute();
            $rawResult = $stmt->fetchAll();            
                     
            $total_pages = ceil(count($rawResult) / $numOfrecs);

            $stmt = $pdo->prepare("SELECT * FROM posts WHERE title LIKE '%$searchKey%' ORDER BY id DESC LIMIT $offset,$numOfrecs ");
            $stmt->execute();
            $result = $stmt->fetchAll();

           }

   
          
          ?>


          
        
          
          <div class="col-md-12 col-lg-12 col-12 ">
            <div class="card">
              <div class="card-header">
              <div class="mr-4 mt-0" >
              <nav aria-label="Page navigation example " style="float:right">
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
            <?php 
             $stmt = $pdo->prepare("SELECT * FROM posts");
             $stmt->execute();
             $postResult = $stmt->fetch(PDO::FETCH_ASSOC);
     
            ?>
              
              <!-- /.card-header -->
              <div class="card-body">
                <table class="table table-bordered">
                  <thead>

                    <tr>
                      <th style="width: 5%">#</th>
                      <th style="width: 20%">Title</th>
                      <th style="width: 30%">Content</th>
                      <th style="width: 20%">Comments</th>
                      <th style="width: 25%">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if ($result) : ?>
                      <?php $i = 1; ?>
                      <?php foreach ($result as  $value) : ?>

                          <tr>
                            <td><?= $i; ?></td>
                            <td><?= escape(substr($value['title'],0,11)) ?>..</td>
                            <td><?= escape(substr($value['content'],0,30)) ?>...</td>                           
                            <td class="text-center"><?= escape($value['comments']) ?></td>                           
                            <td>
                              <a href="edit.php?id=<?= $value['id'] ?>" type="button" class="btn btn-warning"><i class="fa-solid fa-pen-to-square "></i> Edit</a>
                              <a href="delete.php?id=<?= $value['id'] ?>" type="button" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this blog!')">
                              <i class="fa-solid fa-trash"></i>  Delete</a>
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
          </div>
          
          
          <!-- /.col -->
          <!-- <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Starter Page</li>
            </ol>
          </div>/.col -->
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