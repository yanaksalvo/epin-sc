<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
$_title         =  'Alışveriş Sonuç';
?>
<div class="container">
	<div class="row mt-10 mb-10">
		<div class="col-md-12">
			<ul class="adres_cubugu">
				<li><a href="index.php"><i class="las la-home"></i> Home</a> <span><i class="las la-angle-right"></i></span></li>
				<li><a href="">Shopping Result</a></li>
			</ul>
		</div>
	</div>
	<div class="row mt-20 mb-20 detay_sol">
		<?php
            if($_POST){
                require_once('iyzipay-php-master/samples/config.php');

                $request = new \Iyzipay\Request\RetrieveCheckoutFormRequest();
                $request->setLocale(\Iyzipay\Model\Locale::TR);
                $request->setConversationId("123456789");
                $request->setToken($_POST['token']);

                $checkoutForm = \Iyzipay\Model\CheckoutForm::retrieve($request, Config::options());

                if($checkoutForm->getPaymentStatus() == 'SUCCESS'){

                    $query = $db->prepare("SELECT * FROM siparis where siparis_key=:siparis_key AND kredi_karti_odendi=:kredi_karti_odendi LIMIT 1");
                    $gel = $query->execute(array(":siparis_key"=>$_GET['siparis_key'],":kredi_karti_odendi"=>0));
                    $gel = $query->fetch(PDO::FETCH_ASSOC);

                    if($gel){
                      
                      $islem = $db->prepare("UPDATE siparis SET kredi_karti_odendi = ?, durum = ? WHERE id = ?");
                      $islem = $islem->execute(array(1,3,$gel['id']));


                      $siparis_urun = $db->query("SELECT * FROM siparis_urun WHERE siparis_id = '{$gel['id']}' LIMIT 1")->fetch(PDO::FETCH_ASSOC);
                      $kod = $db->query("SELECT * FROM urun_secenek_alt WHERE id = '{$siparis_urun['secenek']}' LIMIT 1")->fetch(PDO::FETCH_ASSOC);

                      $stok = $kod['stok'] - 1;

                       $islem = $db->prepare("UPDATE urun_secenek_alt SET stok = ? WHERE id = ?");
                      $islem = $islem->execute(array($stok,$siparis_urun['secenek']));
                      
                      echo '<center><img src="assets/images/basari.png" style="width:150px"><br><br>
                      <div style="padding-bottom: 20px;font-size: 18px;color: #229f38;"><b>Your order was created successfully.</b></div>
                      <div style="padding-bottom: 20px;font-size: 18px;"><b>Your order summary has been sent to your e-mail address.</b></div><br>
                      <div style="padding-bottom: 20px;font-size: 18px;"><b>Product Code: '.$kod['baslik'].'</b></div></center>';




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
                                          <th>Ürün Kodunuz</th>
                                          <th>'.$kod['baslik'].'</th>
                                        </tr>
                                      </table>
                                    </div>
                                  </div>
                                  <style type="text/css">
                                  body{font-family:arial}table{width:100%;border:1px solid #ddd}table tr{padding:0;margin:0}table tr th{border:1px solid #ddd;padding:0;margin:0;background:#02add9;color:#fff;padding:10px}table tr td{border:1px solid #ddd;padding:0;text-align:center;margin:0;border-spacing:0}
                                  </style>
                                </body>
                                </html>';


                                $query = $db->prepare("SELECT * FROM kullanici where id=:id LIMIT 1");
                      $kullanici = $query->execute(array(":id"=>$_SESSION['kullanici']['id']));
                      $kullanici = $query->fetch(PDO::FETCH_ASSOC);
                      
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
                                $mail->setFrom($mail_ayar['email'], 'Sipariş Bildirimi');
                                $mail->addAddress($cek['siparis_mail'], 'Sipariş Bildirimi');
                                $mail->addAddress($kullanici['email'], $kullanici['ad'].' '.$kullanici['soyad']);
                                $mail->isHTML(true);
                                $mail->Subject = 'New Order';
                                $mail->Body    = $mailbody;
                                $mail->AltBody = '';
                                $mail->send();

                    }else{
                      echo '<center><img src="assets/images/hata.png" style="width:150px"><br><br><span style="font-size:25px;color:red;font-weight:bold">An error has occurred.</span></center>';
                    }


                }else{
                  echo '<center><img src="assets/images/hata.png" style="width:150px"><br><br><span style="font-size:25px;color:red;font-weight:bold">An error has occurred.</span></center>';
                }

            }else{
              echo '<center><img src="assets/images/hata.png" style="width:150px"><br><br><span style="font-size:25px;color:red;font-weight:bold">An error has occurred.</span></center>';
            }
        ?>
	</div>
</div>