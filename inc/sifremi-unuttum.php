<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

?>
<div class="container mt-20 mb-20">
    <div class="row">
      
    <div class="col-md-6 col-md-offset-3">
      <div class="detay_sol">
            

          <?php
          if($_POST){
            if(!empty($_POST['email'])){
                $query = $db->prepare("SELECT * FROM kullanici where email=:email LIMIT 1");
                        $kullanici = $query->execute(array(":email"=>$_POST['email']));
                        $kullanici = $query->fetch(PDO::FETCH_ASSOC);
                        if($kullanici){

                            if($sms_izin['sifre_sifirlama'] == 1){
                              $mesaj = 'E-mail: '.$kullanici['email'].' Password: '.$kullanici['sifre'];
                              sms($mesaj,$kullanici['telefon']);
                            }
                            
                            $mailbody = '<!DOCTYPE html>
                              <html>
                              <head>
                                <title>'.$cek['title'].'</title>
                                <meta charset="utf-8">
                              </head>
                              <body style="padding: 30px">
                                <div style="width: 98%;margin:0 auto;background: #02add9;padding: 1%;display: inline-block;border-radius: 10px">
                                  <div style="width: 90%;float: left;background: #fff;padding: 10px 5% 20px 5%;">
                                    <center><img src="'.$site.'upload/'.$cek['logo'].'" style="width: 200px"></center>
                                    <table>
                                      <tr>
                                        <th>Email Adresiniz</th>
                                        <th>Şifreniz</th>
                                      </tr>
                                      <tr>
                                        <td>'.$kullanici['email'].'</td>
                                        <td>'.$kullanici['sifre'].'</td>
                                      </tr>
                                    </table>
                                  </div>
                                </div>
                                <style type="text/css">
                                body{font-family:arial}table{width:100%;border:1px solid #ddd}table tr{padding:0;margin:0}table tr th{border:1px solid #ddd;padding:0;margin:0;background:#02add9;color:#fff;padding:10px}table tr td{border:1px solid #ddd;padding:0;text-align:center;margin:0;border-spacing:0}
                                </style>
                              </body>
                              </html>';

                            require 'vendor/autoload.php';

                            $mail = new PHPMailer(true);

                            $mail->SMTPDebug = SMTP::DEBUG_SERVER;
                            $mail->isSMTP();
                            $mail->Host       = $mail_ayar['host'];
                            $mail->SMTPAuth   = true;
                            $mail->Username   = $mail_ayar['email'];
                            $mail->Password   = $mail_ayar['sifre'];
                            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                            $mail->Port       = 587;
                            $mail->CharSet = 'UTF-8';
                            $mail->SMTPDebug = 0;   
                            $mail->setFrom($mail_ayar['email'], $cek['title']);
                            $mail->addAddress($_POST['email'], $kullanici['ad'].' '.$kullanici['soyad']);
                            $mail->isHTML(true);
                            $mail->Subject = 'Forgot Password';
                            $mail->Body    = $mailbody;
                            $mail->AltBody = '';
                            $mail->send();

                            echo '<center><img src="assets/images/basari.png" style="width:150px"><br><br><span style="font-size:25px;color:red;font-weight:bold">Your password has been sent to your e-mail address.</span></center>';
                        }else{
                           echo '<center><img src="assets/images/hata.png" style="width:150px"><br><br><span style="font-size:25px;color:red;font-weight:bold">User Not Found</span></center>';
                        }
            }
          }
        ?>
        <h2>Forgot Password</h2>
        <form action="" method="post">
          <div class="row mt-20">
            <div class="col-md-12">
              <div>E-mail</div>
              <input type="email" name="email" class="form-control" placeholder="E-Posta Adresi" required="">
            </div>
          </div>
              <div class="row mt-10">
            <div class="col-md-12"><button type="submit" class="btn btn-success" style="width: 100%;font-size: 20px">Şifremi Gönder</button></div>
              </div>
        </form>
      </div>
    </div>

  </div>
</div>
