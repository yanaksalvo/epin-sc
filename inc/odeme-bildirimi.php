<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
$_title         =  'Ödeme Bildirimi';
?>
<div class="container">
	<div class="row mt-10 mb-10">
		<div class="col-md-12">
			<ul class="adres_cubugu">
				<li><a href="index.php"><i class="las la-home"></i> Home</a> <span><i class="las la-angle-right"></i></span></li>
				<li><a href="odeme-bildirimi">Payment Notification</a></li>
			</ul>
		</div>
	</div>
	<div class="row mt-20 mb-20">
		<div class="col-md-5 detay_sol">
			<div class="bg2 border p20">
				<?php
					if($_POST){
						
						if(!empty($_POST['no']) AND !empty($_POST['adsoyad']) AND !empty($_POST['bank'])){
							 

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
			                                  <th>Sipariş Numarası</th>
			                                  <th>Ad Soyad</th>
			                                  <th>Banka</th>
			                                </tr>
			                                <tr>
			                                  <td>'.$_POST['no'].'</td>
			                                  <td>'.$_POST['adsoyad'].'</td>
			                                  <td>'.$_POST['bank'].'</td>
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
			                      $mail->addAddress($cek['siparis_mail'], $cek['title']);
			                      $mail->isHTML(true);
			                      $mail->Subject = 'Payment Notification';
			                      $mail->Body    = $mailbody;
			                      $mail->AltBody = '';
			                      $mail->send();

			                      echo '<center><img src="assets/images/basari.png" style="width:150px"><br><br><span style="font-size:25px;color:red;font-weight:bold">Your notification was sent successfully.</span></center>';
			              
						}
					}
				?>
				<h2>Report Payment</h2>
				<form action="" method="post">
					<div class="row mt-20">
						<div class="col-md-12">
							<input type="number" name="no" class="form-control" placeholder="Order ID" required="">
						</div>
					</div>
					<div class="row mt-20">
						<div class="col-md-12">
							<input type="text" name="adsoyad" class="form-control" placeholder="Name and Surname" required="">
						</div>
					</div>
					<div class="row mt-20">
						<div class="col-md-12">
							<label>Your Payment Bank</label>
							<select class="form-control" required="" name="bank">
								<?php
									$query = $db->query("SELECT * FROM banka_hesaplari", PDO::FETCH_ASSOC);
								      if($query->rowCount()){
								        foreach($query as $row){
								        	echo '<option value="'.$row['baslik'].'">'.$row['baslik'].'</option>';
								        }
								      }
								?>
							</select>
						</div>
					</div>
			       	<div class="row mt-10">
						<div class="col-md-12"><button type="submit" class="btn btn-success" style="width: 100%;font-size: 20px">Gönder</button></div>
			       	</div>
				</form>
			</div>
		</div>
	</div>
</div>