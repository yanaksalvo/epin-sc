<div class="container mt-20 mb-20">
    <div class="row">
      
    <div class="col-md-6 col-md-offset-3">
      <div class="detay_sol">
            

            <?php

          if($_POST){

            if(!empty($_POST['email']) AND !empty($_POST['sifre'])){

              $query = $db->prepare("SELECT * FROM kullanici where email=:email AND sifre=:sifre AND aktif=:aktif LIMIT 1");

                      $giris = $query->execute(array(":email"=>$_POST['email'],":sifre"=>$_POST['sifre'],":aktif"=>1));

                      $giris = $query->fetch(PDO::FETCH_ASSOC);



                      if($giris){

                          echo '<div class="basari">You have successfully logged in. You are being redirected.<meta http-equiv="refresh" content="2;URL=index.php"></div>';

                          $_SESSION['kullanici']['login'] = 1;

                          $_SESSION['kullanici']['id'] = $giris['id'];

                      }else{

                          echo '<div class="hata">Your login details may be incorrect or your membership may have been stopped.</div>';

                      }

            }

          }

        ?>

        <h2>Login</h2>

        <form action="" method="post">

          <div class="row mt-20">

            <div class="col-md-12">

              <div>E-mail</div>

              <input type="email" name="email" class="form-control" placeholder="E-mail" value="" required="">

            </div>

          </div>

          <div class="row mt-20">

            <div class="col-md-12">

              <div>Password</div>

              <input type="password" name="sifre" class="form-control" placeholder="Password" value="" required="">

            </div>

          </div>

              <div class="row mt-10">

            <div class="col-md-12"><a href="sifremi-unuttum" style="color:green"><i class="las la-binoculars"></i> Forgot Password</a></div>

              </div>

              <div class="row mt-10">

            <div class="col-md-12"><button type="submit" class="btn btn-success" style="width: 100%;font-size: 20px">Login</button></div>

              </div>

        </form>
        
      </div>
    </div>

  </div>
</div>
