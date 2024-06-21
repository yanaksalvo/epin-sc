<?php
    foreach($_GET    as $k => $v) $_GET[$k]    = strip_tags($v);
    foreach($_POST   as $k => $v) $_POST[$k]   = strip_tags($v);

    include 'panel/fonksiyon.php';


    if(isset($_GET['sayfa'])) {
      $sayfa = cleanAZ($_GET['sayfa']);
      if($sayfa == 'cikis-yap'){
        unset($_SESSION['kullanici']['login']);
        unset($_SESSION['kullanici']['id']);
      }
      if (!is_file('inc/'.$sayfa.'.php')) {
            $sayfa = 'anasayfa';
      }
    }else{
      $sayfa = 'anasayfa';
    }

    $cek = $db->query("SELECT * FROM ayar LIMIT 1")->fetch(PDO::FETCH_ASSOC);

    function meta_degistir($icerik) {
      global  $_title, $_description;
      $icerik = str_replace('[$_title]', $_title, $icerik);
      $icerik = str_replace('[$_description]', $_description, $icerik);
      return $icerik;
    }

    ob_start('meta_degistir');

    $_title         =  $cek['title'];
    $_description   =  $cek['description'];
?>
<!doctype html>
<html lang="tr">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="all">
    <title>[$_title]</title>
    <meta name="description" content="[$_description]" />
    <base href="<?php echo $site; ?>">
    <meta property="og:title" content="[$_title]">
    <meta property="og:description" content="[$_description]">
    <meta property="og:locale" content="tr_TR">
    <meta property="og:type" content="website">
    <meta name="author" content="https://www.massacre.store/">
    <meta name="robots" content="index, follow">
    <meta name="googlebot" content="index, follow">
    <meta name="theme-color" content="#08C" />
    <link rel="shortcut icon" href="upload/<?php echo $cek['fav']; ?>" type="image/x-icon">
    <link rel="stylesheet" href="assets/css/style.css?v=<?php echo uniqid(); ?>">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/owl.carousel.css">
    <link rel="stylesheet" href="assets/css/owl.theme.css">
    <link rel="stylesheet" href="assets/css/line-awesome.min.css">
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body class="theme-default">

    <?php if(!empty($cek['site_ust_img'])){ ?><div><a href="<?php echo $cek['ust_img_link']; ?>" class=""><img src="upload/<?php echo $cek['site_ust_img']; ?>" style="width: 100%;"></a></div><?php } ?>

    <div class="container-fluid ust">
      <div class="row">

          <div class="logo-menu-giris-dis">
            <div class="container">
              <div class="row">
                <div class="col-md-12">
                  <div class="logo-menu-giris">
                    <div id="logo">
                      <a href="index.php" title="<?php echo $cek['title']; ?>"><img src="upload/<?php echo $cek['logo']; ?>" alt="<?php echo $cek['title']; ?>" title="<?php echo $cek['title']; ?>"></a>
                    </div>

                     <a class="toggle ekmenu" href="#" style="display: none"><i class="las la-compass"></i> Menü</a>

                    <ul id="menu" class="duzmenu hidden-xs">
                      <li><a href=""><span>Home</span></a></li> 
                      <li><a href="odeme-bildirimi" title="Payment Notification"><span><i class="las la-redo-alt"></i> Payment Notification</span></a></li>
                      <?php
                        $query = $db->query("SELECT * FROM sayfa WHERE ust_menu = 1", PDO::FETCH_ASSOC);
                        if($query->rowCount()){
                          foreach($query as $row){
                            echo '<li><a href="sayfa/'.$row['sef'].'" title="'.$row['baslik'].'"><span>'.$row['baslik'].'</span></a></li>';
                          }
                        }
                      ?>        
                    </ul>
                    <ul id="kayit-giris" class="duzmenu hidden-xs">
                      <?php if(isset($_SESSION['kullanici']['login'])){ ?>
                      <li><a href="hesabim"><span><i class="las la-user-check"></i> Account</span></a></li>          
                      <li><a href="siparislerim"><span><i class="las la-user-check"></i> Orders</span></a></li>          
                      <li><a href="cikis-yap"><span>Sign out</span></a></li>  
                      <?php }else{ ?>
                      <li><a href="giris-yap"><span><i class="las la-user-check"></i> Login</span></a></li>          
                      <li><a href="kayit-ol"><span><i class="las la-user"></i> Register</span></a></li>  
                      <?php } ?>

                    </ul>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div id="slider" class="owl-carousel">
            
            <?php
              $query = $db->query("SELECT * FROM slider ORDER BY sira ASC", PDO::FETCH_ASSOC);
              if($query->rowCount()){
                foreach($query as $row){
            ?>
              <div class="item"><a href="<?php echo $row['link']; ?>"><div style="background: url('upload/<?php echo $row['img']; ?>')" class="img-responsive"></div></a></div>
            <?php } } ?>
          </div>

          <div class="radyal-bg"></div>

      </div>
    </div>

   
   <?php include 'inc/'.$sayfa.'.php'; ?>



    <div class="container-fluid mt-20">
      <div class="row">
        <div class="rengarenk"><div></div><div></div><div></div><div></div><div></div>
        </div>
      </div>
    </div>
    <footer>
      <div class="container">
        <div class="row">
          <div class="col-md-3">
              <a href="index.php" title=""><img src="assets/images/logo.png" alt="" title=""></a>
              <p style="color: #fff;margin-top: 5px">Massacre Store provides you account support in many games, we provide the safest, fastest account sales and we are here to give you the best service, you can reach our support team in any error or you can come to our discord address and get help.</p>
              <ul class="footer-menu1">
                <li><i class="las la-envelope-open-text"></i> info@massacre.store</li>
              </ul>
          </div>
          <div class="col-md-3">
            <div class="footer-baslik">Pages</div>
            <ul class="footer-menu2">
             <?php
              $query = $db->query("SELECT * FROM sayfa WHERE alt_menu = 1", PDO::FETCH_ASSOC);
              if($query->rowCount()){
                foreach($query as $row){
                  echo '<li><a href="sayfa/'.$row['sef'].'" title="'.$row['baslik'].'"><span>'.$row['baslik'].'</span></a></li>';
                }
              }
            ?>
            </ul>
          </div>
          <div class="col-md-3">
            <div class="footer-baslik">Products</div>
            <ul class="footer-menu2">
              <?php
              $query = $db->query("SELECT * FROM urun", PDO::FETCH_ASSOC);
              if($query->rowCount()){
                foreach($query as $row){
                  echo '<li><a href="urun/'.$row['sef'].'" title="'.$row['baslik'].'"><span>'.$row['baslik'].'</span></a></li>';
                }
              }
            ?>
            </ul>
          </div>
          <div class="col-md-3">
          <iframe src="https://discord.com/widget?id=1228442441867329566&theme=dark" width="350" height="500" allowtransparency="true" frameborder="0" sandbox="allow-popups allow-popups-to-escape-sandbox allow-same-origin allow-scripts"></iframe>
        </div>
      </div>
    </footer>
    <div class="copyright pb-10 pt-10">
      <div class="container">
        <div class="row">
          <div class="col-md-4">Massacre Store © 2024</div>
          <div class="col-md-4"><center>This website was created by Massacre Store</center></div>
          <div class="col-md-4"><div class="pull-right"><img src="assets/images/odeme-yontemleri.png" alt="ödeme yöntemleri" class="img-responsive"></div></div>
        </div>
      </div>
    </div>


    <header>
      <div class="wrapper cf">
        <nav id="main-nav">
          <ul class="first-nav">
            <?php
              $query = $db->query("SELECT * FROM urun", PDO::FETCH_ASSOC);
              if($query->rowCount()){
                foreach($query as $row){
                  echo '<li><a href="urun/'.$row['sef'].'" title="'.$row['baslik'].'">'.$row['baslik'].'</a>';
                }
              }
            ?>
          </ul>
          <h2 style="float: left;margin-top: 1px;background: none">Pages</h2>
          <ul class="second-nav">
            <?php if(isset($_SESSION['kullanici']['login'])){ ?>
            <li><a href="hesabim"><span><i class="las la-user-check"></i> Account</span></a></li>          
            <li><a href="siparislerim"><span><i class="las la-user-check"></i> Orders</span></a></li>          
            <li><a href="cikis-yap"><span>Sign out</span></a></li>  
            <?php }else{ ?>
            <li><a href="giris-yap"><span><i class="las la-user-check"></i> Login</span></a></li>          
            <li><a href="kayit-ol"><span><i class="las la-user"></i> Register</span></a></li>  
            <?php } ?>

            <?php
                $query = $db->query("SELECT * FROM sayfa", PDO::FETCH_ASSOC);
                if($query->rowCount()){
                  foreach($query as $row){
                    echo '<li><a href="sayfa/'.$row['sef'].'">'.$row['baslik'].'</a></li>';
                  }
                }
            ?>
          </ul>
        </nav>
      </div>
    </header>

    <script src="assets/js/jquery-1.12.4.min.js"></script>
    <script src="assets/js/owl.carousel.js"></script>
    <script src="assets/js/main.js?v=<?php echo uniqid(); ?>"></script>
    <script src="assets/js/bootstrap.min.js"></script>
  
    <link rel="stylesheet" href="assets/css/menu.css">
    <script src="assets/js/menu.js"></script>
    <script src="assets/js/menu-ek.js"></script>
  </body>
</html>