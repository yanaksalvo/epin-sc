<?php
  if(!isset($_GET['id'])){
    die('<meta http-equiv="refresh" content="0;URL='.$site.'">');
  }

  $as = $db->query("SELECT * FROM urun_secenek_alt WHERE id = '{$_GET['id']}' AND stok > 0 LIMIT 1")->fetch(PDO::FETCH_ASSOC);
  if(!$as){
    die('<meta http-equiv="refresh" content="0;URL='.$site.'">');
  }

  $_SESSION['urun'] = $as['id'];
?>
<div class="container mt-20 mb-20">
    <div class="row">
      
    <div class="col-md-6 col-md-offset-3">



      <div class="col-md-12 mb-20" style="padding: 10px 47px;position: relative;z-index: 2;background: #2a3982;color: #ffffff;border-radius: 10px;font-size: 18px;">
        <center>Toplam Tutar: <?php echo $as['fiyat']; ?> TL</center>
      </div>


      <div class="detay_sol">
            

        <?php 

        if(isset($_SESSION['kullanici']['login'])){
        $query = $db->prepare("SELECT * FROM kullanici where id=:id LIMIT 1");
              $kullanici = $query->execute(array(":id"=>$_SESSION['kullanici']['id']));
              $kullanici = $query->fetch(PDO::FETCH_ASSOC);
          }

    ?>
      <div class="row">

        <div class="col-md-12 pt-10 pb-10 bg3"><center><b style="font-size: 20px">Sipariş Bilgileriniz</b></center></div>
        <div class="col-md-12 pt-10 pb-10">
          <form action="alisverisi-tamamla" method="post">
                    <fieldset class="form-group mt-4 col-md-6">
                       <label>Name</label>
                       <input type="text" class="form-control" name="ad" required="" placeholder="Name" value="<?php if(isset($_SESSION['kullanici']['login'])){ echo $kullanici['ad']; } ?>">
                    </fieldset>
                    <fieldset class="form-group mt-4 col-md-6">
                       <label>Surname</label>
                       <input type="text" class="form-control" name="soyad" required="" placeholder="Surname" value="<?php if(isset($_SESSION['kullanici']['login'])){ echo $kullanici['soyad']; } ?>">
                    </fieldset>
                    <fieldset class="form-group mt-4 col-md-6">
                       <label>Phone</label>
                       <input type="text" class="form-control" name="telefon" required="" placeholder="Phone" value="<?php if(isset($_SESSION['kullanici']['login'])){ echo $kullanici['telefon']; } ?>">
                    </fieldset>
                    <fieldset class="form-group mt-4 col-md-6">
                       <label>E-mail</label>
                       <input type="email" class="form-control" name="email" required="" placeholder="E-mail" value="<?php if(isset($_SESSION['kullanici']['login'])){ echo $kullanici['email']; } ?>">
                    </fieldset>
                    <fieldset class="form-group mt-4 col-md-12">
                       <label>Tc ID Number For Invoice</label>
                       <input type="text" class="form-control" name="tc" placeholder="Tc Identity No" value="<?php if(isset($_SESSION['kullanici']['login'])){ echo $kullanici['tc']; } ?>">
                    </fieldset>
                    <?php if(!isset($_SESSION['kullanici']['login'])){ ?>
                    <fieldset class="form-group col-md-12">
                       <label>Password</label>
                       <input type="password" class="form-control" name="sifre" required="" placeholder="********">
                    </fieldset>
                    <?php } ?>
                    <fieldset class="form-group col-md-12">
                      <select name="odeme_yontemi" required="" class="form-control">
                        <option value="">Select Payment Method</option>
                        <?php
                          $yontem = $db->query("SELECT * FROM odeme_yontemleri LIMIT 1")->fetch(PDO::FETCH_ASSOC);
                          if($yontem['online_odeme'] == 1){ echo '<option value="1">Online Credit Card</option>'; }
                          if($yontem['kapida_kredi_karti'] == 1){ echo '<option value="2">Credit Card at the door</option>'; }
                          if($yontem['kapida_nakit'] == 1){ echo ' <option value="3">Cash on Delivery</option>'; }
                          if($yontem['banka_havalesi'] == 1){ echo '<option value="4">Bank Transfer </option>'; }
                        ?>
                      </select>
                    </fieldset>
                    <fieldset class="form-group col-md-12">
                       <button class="btn icon-btn btn-success" style="width: 100%">Create Order</button>
                    </fieldset>
                  </form>
        </div>      
      </div>

        
      </div>
    </div>

  </div>
</div>
