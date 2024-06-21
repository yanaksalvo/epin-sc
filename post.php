<?php
  foreach($_GET    as $k => $v) $_GET[$k]    = strip_tags($v);
  foreach($_POST   as $k => $v) $_POST[$k]   = strip_tags($v);


  if($_POST){
    include 'panel/fonksiyon.php';

    if($_POST['islem'] == 'urun-ekle'){
        if(is_numeric($_POST['urun_id']) AND is_numeric($_POST['adet']) AND is_numeric($_POST['secenek_id'])){
            if($_POST['adet'] < 1){
              $_POST['adet'] = 1;
            }
            $urunquery = $db->prepare("SELECT * FROM urun where id=:id LIMIT 1");
            $urun = $urunquery->execute(array(":id"=>$_POST['urun_id']));
            $urun = $urunquery->fetch(PDO::FETCH_ASSOC);
            if($urun){
              if($_POST['secenek_id'] > 0){
                $query = $db->prepare("SELECT * FROM urun_secenek_alt where id=:id LIMIT 1");
                $alt_secenek = $query->execute(array(":id"=>$_POST['secenek_id']));
                $alt_secenek = $query->fetch(PDO::FETCH_ASSOC);
                if($alt_secenek){
                  if($_POST['adet'] > $alt_secenek['stok']){
                    echo 0;
                  }else{
                    $uniqid = $_POST['urun_id'].'-'.$_POST['secenek_id'];
                    $_SESSION['sepet']['urun_id'][$uniqid]      = $_POST['urun_id'];
                    $_SESSION['sepet']['adet'][$uniqid]         = $_POST['adet'];
                    $_SESSION['sepet']['secenek_id'][$uniqid]   = $_POST['secenek_id'];
                    $_SESSION['sepet']['key'][$uniqid]          = $uniqid;
                    echo 2;
                  }
                }else{
                  echo 1;
                }
              }else{
                  if($_POST['adet'] > $urun['stok']){
                    echo 0;
                  }else{
                    $uniqid = $_POST['urun_id'];
                    $_SESSION['sepet']['urun_id'][$uniqid]      = $_POST['urun_id'];
                    $_SESSION['sepet']['adet'][$uniqid]         = $_POST['adet'];
                    $_SESSION['sepet']['secenek_id'][$uniqid]   = 0;
                    $_SESSION['sepet']['key'][$uniqid]          = $uniqid;
                    echo 2;
                  }
              }
            }else{
              echo 1;
            }
        }else{
          echo 1;
        }
    }else if($_POST['islem'] == 'listele'){
      echo '<div class="sepet_baslik">My Cart <span>('.count($_SESSION['sepet']['key']).' Product)</span> <i class="las la-times" data-sepet-kapat=""></i></div><ul class="sepet_urunler secimli">';
      if(count($_SESSION['sepet']['key']) > 0){
        $toplam = 0;
        foreach ($_SESSION['sepet']['key'] as $key) {

          $urunquery = $db->prepare("SELECT * FROM urun where id=:id LIMIT 1");
          $urun = $urunquery->execute(array(":id"=>$_SESSION['sepet']['urun_id'][$key]));
          $urun = $urunquery->fetch(PDO::FETCH_ASSOC);

          $urunimg = $db->prepare("SELECT * FROM urun_img where urun_id=:urun_id LIMIT 1");
          $uimg = $urunimg->execute(array(":urun_id"=>$_SESSION['sepet']['urun_id'][$key]));
          $uimg = $urunimg->fetch(PDO::FETCH_ASSOC);

          $secenek = '';

          $alt_secenek_fiyat = 0;
          if($_SESSION['sepet']['secenek_id'][$key] !=0){
            $alt_secenek = $db->prepare("SELECT * FROM urun_secenek_alt where id=:id LIMIT 1");
                    $as = $alt_secenek->execute(array(":id"=>$_SESSION['sepet']['secenek_id'][$key]));
                    $as = $alt_secenek->fetch(PDO::FETCH_ASSOC);

                    $ust_secenek = $db->prepare("SELECT * FROM urun_secenek where id=:id LIMIT 1");
                    $us = $ust_secenek->execute(array(":id"=>$as['urun_secenek_id']));
                    $us = $ust_secenek->fetch(PDO::FETCH_ASSOC);

                    $alt_secenek_fiyat = $as['fiyat'];

                    $secenek = '<div><span>'.$us['baslik'].':</span> <span>'.$as['baslik'].'</span></div>';
          }

          echo '<li>
                  <div class="col-md-3 col-xs-4"><a href="urun/'.$urun['sef'].'"><img src="upload/'.$uimg['img'].'" class="img-responsive"></a></div>
                  <div class="col-md-9 col-xs-8">
                    <a href="urun/'.$urun['sef'].'">'.$urun['baslik'].'</a>
                    '.$secenek.'
                    <div><span>Quantity:</span> <span>'.$_SESSION['sepet']['adet'][$key].' Quantity</span></div>
                    <div><span>Price:</span> <span>'.fiyat($urun['fiyat'] + $alt_secenek_fiyat).' TL</span></div>
                    <div data-sepet-sil="'.$key.'"><i class="las la-trash"></i></div>
                  </div>
              </li>';

          $toplam += $_SESSION['sepet']['adet'][$key] * ($urun['fiyat'] + $alt_secenek_fiyat);
        }

        echo '</ul><div class="sepet_alt">
              <div class="toplam_tutar"><span>Total Amount:</span> <span>'.fiyat($toplam).' TL</span></div>
                <div class="col-md-12 mb-10 mt-10"><a href="sepetim" class="btn icon-btn btn-success  mt-10"><i class="las la-shopping-cart"></i> Go to My Cart</a></div>
                <div class="col-md-12"><a href="index.php" class="btn icon-btn btn-warning"><i class="las la-user-lock"></i> Keep Shopping</a></div>
            </div>';
      }else{
        echo '<li class="sepet_bos"><p>There are no products in your cart.</p></li>';
      }
    }else if($_POST['islem'] == 'sepet_sil'){
        $uniqid = $_POST['id'];
        unset($_SESSION['sepet']['urun_id'][$uniqid]);
        unset($_SESSION['sepet']['adet'][$uniqid]);
        unset($_SESSION['sepet']['secenek_id'][$uniqid]);
        unset($_SESSION['sepet']['key'][$uniqid]);
        echo 1;
    }else if($_POST['islem'] == 'sepet_sayisi'){
        echo @count($_SESSION['sepet']['key']);
    }else if($_POST['islem'] == 'favori-ekle'){

      $uniqid = $_POST['urun_id'];
      $_SESSION['favori']['urun_id'][$uniqid]      = $_POST['urun_id'];;
      $_SESSION['favori']['key'][$uniqid]          = $uniqid;
      echo 1;
    }else if($_POST['islem'] == 'favori-sil'){
        $uniqid = $_POST['urun_id'];
        unset($_SESSION['favori']['urun_id'][$uniqid]);
        unset($_SESSION['favori']['key'][$uniqid]);
        echo 1;
    }

  }


?>