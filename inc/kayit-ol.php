<div class="container mt-20 mb-20">
    <div class="row">
      
    <div class="col-md-6 col-md-offset-3">
      <div class="detay_sol">
            

            <?php
          if($_POST){
            if(!empty($_POST['ad']) AND !empty($_POST['soyad']) AND !empty($_POST['telefon']) AND !empty($_POST['email']) AND !empty($_POST['sifre'])){
              $query = $db->prepare("SELECT * FROM kullanici where email=:email LIMIT 1");
                      $kayit = $query->execute(array(":email"=>$_POST['email']));
                      $kayit = $query->fetch(PDO::FETCH_ASSOC);

                      if($kayit){
                        echo '<div class="hata">This email address is already in use.</div>';
                      }else{
                        $islem = $db->prepare("INSERT INTO kullanici SET ad = ?, soyad = ?, telefon = ?, email = ?, sifre = ?, kayit_tarihi = ?, aktif = ?, tc = ?, adres = ?");
                        $islem = $islem->execute(array($_POST['ad'],$_POST['soyad'],$_POST['telefon'],$_POST['email'],$_POST['sifre'],$time,1,'',''));
                        if($islem){
                            echo '<div class="basari">You have successfully registered. You are being redirected.<meta http-equiv="refresh" content="2;URL=index.php"></div>';

                            if($sms_izin['kullanici_kayit'] == 1){
                    $mesaj = 'Hello, '.$_POST['ad'].' you have successfully registered.';
                    sms($mesaj,$_POST['telefon']);
                  }

                            $_SESSION['kullanici']['login'] = 1;
                            $_SESSION['kullanici']['id'] = $db->lastInsertId();
                        }else{
                            echo '<div class="hata">Operation failed.</div>';
                        }
                      }
            }else{
              echo '<div class="hata">Please fill in all fields.</div>';
            }
          }
        ?>
        
        <h2>Register Quickly</h2>
        <form action="" method="post">
          <div class="row mt-20">
            <div class="col-md-6">
              <input type="text" name="ad" class="form-control" placeholder="Name" required="">
            </div>
            <div class="col-md-6">
              <input type="text" name="soyad" class="form-control" placeholder="Surname" required="">
            </div>
          </div>
          <div class="row mt-20">
            <div class="col-md-12">
              <div>Phone</div>
              <input type="text" name="telefon" class="form-control" placeholder="Phone" required="">
            </div>
          </div>
          <div class="row mt-20">
            <div class="col-md-12">
              <div>E-mail</div>
              <input type="email" name="email" class="form-control" placeholder="E-mail" required="">
            </div>
          </div>
          <div class="row mt-20">
            <div class="col-md-12">
              <div>Password</div>
              <input type="password" name="sifre" class="form-control" placeholder="Password" required="">
            </div>
          </div>
          <div class=" mt-20">
            <div class="checkbox">
                  <label>
                    <input type="checkbox" value="1" required="">
                    <a href="sayfa/uyelik-sozlesmesi" target="_blank" style="color:green">Membership Agreement</a> i have read and accept.
                  </label>
                </div>
              </div>
              <div class=" mt-10">
            <div class="checkbox">
                  <label>
                    <input type="checkbox" value="1" required="">
                    <a href="sayfa/kullanim-kosullari" target="_blank">Terms of Use</a> i have read and accept.
                  </label>
                </div>
              </div>
              <div class=" mt-10">
            <div class="checkbox">
                  <label>
                    <input type="checkbox" value="1" required="">
                    <a href="sayfa/aydinlatma-ve-riza-metni" target="_blank">Clarification and Consent Text</a> i want to receive electronic messages within the scope of.
                  </label>
                </div>
              </div>
              <div class=" mt-10">
            <div class="col-md-12">By logging in, you can access your personal data <a href="sayfa/aydinlatma-ve-riza-metni" target="_blank">Clarification and Consent Text</a>, processed within the scope. By pressing the Become Member button <a href="sayfa/gizlilik-politikasi" target="_blank">Privacy and Cookie Policy</a>you acknowledge that you have read it and accept it.</div>
              </div>
              <div class="row mt-10">
            <div class="col-md-12"><button type="submit" class="btn btn-success" style="width: 100%;font-size: 20px">Register</button></div>
              </div>
        </form>
        
      </div>
    </div>

  </div>
</div>
