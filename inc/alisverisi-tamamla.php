<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

$_title         =  'Alışverişi Tamamla';
if(!$_POST OR @count($_SESSION['urun']) < 1){
  die('<meta http-equiv="refresh" content="0;URL=index.php">');
}

$_POST['adres'] = 'adres yok';
?>
<div class="container mt-20 mb-20">
    <div class="row">
      
    <div class="col-md-8 col-md-offset-2">
      <div class="detay_sol">
            

           <?php
           $urun_secenek_alt = $db->query("SELECT * FROM urun_secenek_alt WHERE id = '{$_SESSION['urun']}' AND stok > 0 LIMIT 1")->fetch(PDO::FETCH_ASSOC);

           if($urun_secenek_alt){

            $urun_secenek = $db->query("SELECT * FROM urun_secenek WHERE id = '{$urun_secenek_alt['urun_secenek_id']}'  LIMIT 1")->fetch(PDO::FETCH_ASSOC);

            if(!isset($_SESSION['kullanici']['login'])){

                      $query = $db->prepare("SELECT * FROM kullanici where email=:email LIMIT 1");
                      $kayit = $query->execute(array(":email"=>$_POST['email']));
                      $kayit = $query->fetch(PDO::FETCH_ASSOC);

                      if($kayit){
                          echo '<div class="hata">You cannot order! <br>Because a user has already registered with this email address.</div>';
                      }else{

                          $islem = $db->prepare("INSERT INTO kullanici SET ad = ?, soyad = ?, telefon = ?, email = ?, sifre = ?, adres = ?, tc = ?, aktif = ?, kayit_tarihi = ?");
                          $islem = $islem->execute(array($_POST['ad'],$_POST['soyad'],$_POST['telefon'],$_POST['email'],$_POST['sifre'],$_POST['adres'],$_POST['tc'],1,$time));
                          if($islem){
                              echo '<div class="basari">You have registered to our website for order tracking purposes.<br><b>Your User Login Information;<br></b>E-mail: '.$_POST['email'].'<br>Password: '.$_POST['sifre'].'</div>';
                              $_SESSION['kullanici']['login'] = 1;
                              $_SESSION['kullanici']['id'] = $db->lastInsertId();
                          }else{
                              echo '<div class="hata">Operation failed.</div>';
                          }
                         
                      }

                    }

                    if(isset($_SESSION['kullanici']['login'])){

                      $siparis_key = $_SESSION['kullanici']['id'].uniqid();

                      $islem = $db->prepare("INSERT INTO siparis SET ad = ?, soyad = ?, telefon = ?, email = ?, adres = ?, tc = ?, siparis_tarihi = ?, toplam_tutar = ?, siparis_key = ?, kullanici_id = ?, odeme_yontemi = ?, kredi_karti_odendi = ?, durum = ?, kargo_adi = ?, kargo_kodu = ?");
                        $islem = $islem->execute(array($_POST['ad'],$_POST['soyad'],$_POST['telefon'],$_POST['email'],$_POST['adres'],$_POST['tc'],$time,0,$siparis_key,$_SESSION['kullanici']['id'],$_POST['odeme_yontemi'],0,0,'',''));
                        if($islem){

                              $siparis_id = $db->lastInsertId();


                              
                              $genel_toplam = $urun_secenek_alt['fiyat'];

                              $islem = $db->prepare("INSERT INTO siparis_urun SET siparis_id = ?, urun_id = ?, fiyat = ?, adet = ?, secenek = ?, kargo_fiyati = ?");
                              $islem = $islem->execute(array($siparis_id,$urun_secenek['urun_id'],$genel_toplam,1,$_SESSION['urun'],0));
                              unset($_SESSION['urun']);


                            $islem = $db->prepare("UPDATE siparis SET toplam_tutar = ? WHERE id = ?");
                            $islem = $islem->execute(array($genel_toplam,$siparis_id));


                             


                                if($sms_izin['siparis_yonetici'] == 1){
                                  $mesaj = 'Merhaba, yeni siparişi var';
                                  sms($mesaj,$cek['telefon']);
                                }

                            if($_POST['odeme_yontemi'] == 4){
                              echo '<center><img src="assets/images/basari.png" style="width:150px"><br><br>
                                    <div style="padding-bottom: 20px;font-size: 18px;color: #229f38;"><b>Your order was created successfully.</b></div></li>
                                    <div style="padding-bottom: 20px;font-size: 18px;color: #229f38;"><b>Total Order Amount: '.fiyat($genel_toplam).' TL</b></div>
                                    <div style="padding-bottom: 20px;font-size: 18px;"><b>You can make your payment to one of the following bank accounts.<br></div>
                                    <div style="color: #9f2222;font-size: 18px;">Please write the reference code below in the description during the transfer.</div><br>
                                    <div style="padding-bottom: 20px;font-size: 18px;color: #229f38;"><b>Your Remittance Disclosure Code: '.$siparis_key.'</b></div>
                                    <div style="padding-bottom: 20px;font-size: 18px;"><b>Place your orders <a href="siparislerim">my order</a> you can check it on the page.</b></div></center>';

                      $query = $db->query("SELECT * FROM banka_hesaplari", PDO::FETCH_ASSOC);
                      if($query->rowCount()){
                        echo '<div class="row">';
                        foreach($query as $row){
                          echo '
                      <div class="col-md-4 mt-20">
                        <div class="bg2 border p20">
                          <div><img src="upload/'.$row['img'].'" class="img-responsive"></div>
                          <div><b>'.$row['baslik'].'</b></div>
                          <div><b>'.$row['aciklama'].'</b></div>
                        </div>
                      </div>';
                    }
                    echo '</div>';
                    }else{
                      echo '<div class="col-md-12"><div class="border bg2"><center><h3>Hesap bilgileri bulunmuyor. Yönetici ile iletişime geçin</h3></center></div></div>';
                    }
                            }else if($_POST['odeme_yontemi'] == 2 || $_POST['odeme_yontemi'] == 3){
                                echo '<center><img src="assets/images/basari.png" style="width:150px"><br><br>
                                    <div style="padding-bottom: 20px;font-size: 18px;color: #229f38;"><b>Your order was created successfully.</b></div></li>
                                    <div style="padding-bottom: 20px;font-size: 18px;color: #229f38;"><b>Total Order Amount: '.fiyat($genel_toplam).' TL</b></div>
                                    <div style="padding-bottom: 20px;font-size: 18px;"><b>Place your orders <a href="siparislerim">my order</a> you can check it on the page.</b></div></center>';
                            }else if($_POST['odeme_yontemi'] == 1){
                              
                              $yontem = $db->query("SELECT * FROM odeme_yontemleri LIMIT 1")->fetch(PDO::FETCH_ASSOC);

                              if($yontem['sanal_pos'] == 1){

                                require_once('iyzipay-php-master/samples/config.php');

                                  # create request class
                                  $request = new \Iyzipay\Request\CreateCheckoutFormInitializeRequest();
                                  $request->setLocale(\Iyzipay\Model\Locale::TR);
                                  $request->setConversationId("123456789");
                                  $request->setPrice($genel_toplam);
                                  $request->setPaidPrice($genel_toplam);
                                  $request->setCurrency(\Iyzipay\Model\Currency::TL);
                                  $request->setBasketId($siparis_key);
                                  $request->setPaymentGroup(\Iyzipay\Model\PaymentGroup::PRODUCT);
                                  $request->setCallbackUrl($site."iyzico-sonuc/".$siparis_key);
                                  $request->setEnabledInstallments(array(2, 3, 6, 9));

                                  $buyer = new \Iyzipay\Model\Buyer();
                                  $buyer->setId($_SESSION['kullanici']['id']);
                                  $buyer->setName($_POST['ad']);
                                  $buyer->setSurname($_POST['soyad']);
                                  $buyer->setGsmNumber("+9".$_POST['telefon']);
                                  $buyer->setEmail($_POST['email']);
                                  $buyer->setIdentityNumber("00000000000");
                                  $buyer->setLastLoginDate("2015-10-05 12:43:35");
                                  $buyer->setRegistrationDate("2013-04-21 15:12:09");
                                  $buyer->setRegistrationAddress($_POST['adres']);
                                  $buyer->setIp("85.34.78.112");
                                  $buyer->setCity("Istanbul");
                                  $buyer->setCountry("Turkey");
                                  $buyer->setZipCode("34732");
                                  $request->setBuyer($buyer);

                                  $shippingAddress = new \Iyzipay\Model\Address();
                                  $shippingAddress->setContactName($_POST['ad'].' '.$_POST['soyad']);
                                  $shippingAddress->setCity("Istanbul");
                                  $shippingAddress->setCountry("Turkey");
                                  $shippingAddress->setAddress($_POST['adres']);
                                  $shippingAddress->setZipCode("34742");
                                  $request->setShippingAddress($shippingAddress);

                                  $billingAddress = new \Iyzipay\Model\Address();
                                  $billingAddress->setContactName($_POST['ad'].' '.$_POST['soyad']);
                                  $billingAddress->setCity("Istanbul");
                                  $billingAddress->setCountry("Turkey");
                                  $billingAddress->setAddress($_POST['adres']);
                                  $billingAddress->setZipCode("34742");
                                  $request->setBillingAddress($billingAddress);

                                  $basketItems = array();


                                  $firstBasketItem = new \Iyzipay\Model\BasketItem();
                                  $firstBasketItem->setId(1);
                                  $firstBasketItem->setName($cek['title']);
                                  $firstBasketItem->setCategory1("Collectibles");
                                  $firstBasketItem->setCategory2("Accessories");
                                  $firstBasketItem->setItemType(\Iyzipay\Model\BasketItemType::PHYSICAL);
                                  $firstBasketItem->setPrice($genel_toplam);
                                  $basketItems[0] = $firstBasketItem;
                                     
                                  $request->setBasketItems($basketItems);

                                  $checkoutFormInitialize = \Iyzipay\Model\CheckoutFormInitialize::create($request, Config::options());

                                  print_r($checkoutFormInitialize->getCheckoutFormContent());

                                  echo '<center><div id="iyzipay-checkout-form" class="responsive"></div></center>';

                              }else if($yontem['sanal_pos'] == 2){

                                $paytr = $db->query("SELECT * FROM paytr_api LIMIT 1")->fetch(PDO::FETCH_ASSOC);
                                /**************** PAY TR *****************/
                                       ## 1. ADIM için örnek kodlar ##

                                      ####################### DÜZENLEMESİ ZORUNLU ALANLAR #######################
                                      #
                                      ## API Entegrasyon Bilgileri - Mağaza paneline giriş yaparak BİLGİ sayfasından alabilirsiniz.
                                      $merchant_id    = $paytr['merchant_id'];
                                      $merchant_key   = $paytr['merchant_key'];
                                      $merchant_salt  = $paytr['merchant_salt'];
                                      #
                                      ## Müşterinizin sitenizde kayıtlı veya form vasıtasıyla aldığınız eposta adresi
                                      $email = $_POST['email'];
                                      #
                                      ## Tahsil edilecek tutar.
                                      $payment_amount = $genel_toplam * 100; //9.99 için 9.99 * 100 = 999 gönderilmelidir.
                                      #
                                      ## Sipariş numarası: Her işlemde benzersiz olmalıdır!! Bu bilgi bildirim sayfanıza yapılacak bildirimde geri gönderilir.
                                      $merchant_oid = $siparis_key;
                                      #
                                      ## Müşterinizin sitenizde kayıtlı veya form aracılığıyla aldığınız ad ve soyad bilgisi
                                      $user_name = $_POST['ad']." ".$_POST['soyad'];
                                      #
                                      ## Müşterinizin sitenizde kayıtlı veya form aracılığıyla aldığınız adres bilgisi
                                      $user_address = $_POST['adres'];
                                      #
                                      ## Müşterinizin sitenizde kayıtlı veya form aracılığıyla aldığınız telefon bilgisi
                                      $user_phone = $_POST['telefon'];
                                      #
                                      ## Başarılı ödeme sonrası müşterinizin yönlendirileceği sayfa
                                      ## !!! Bu sayfa siparişi onaylayacağınız sayfa değildir! Yalnızca müşterinizi bilgilendireceğiniz sayfadır!
                                      ## !!! Siparişi onaylayacağız sayfa "Bildirim URL" sayfasıdır (Bakınız: 2.ADIM Klasörü).
                                      $merchant_ok_url = $site."paytr-sonuc/".$siparis_key;
                                      #
                                      ## Ödeme sürecinde beklenmedik bir hata oluşması durumunda müşterinizin yönlendirileceği sayfa
                                      ## !!! Bu sayfa siparişi iptal edeceğiniz sayfa değildir! Yalnızca müşterinizi bilgilendireceğiniz sayfadır!
                                      ## !!! Siparişi iptal edeceğiniz sayfa "Bildirim URL" sayfasıdır (Bakınız: 2.ADIM Klasörü).
                                      $merchant_fail_url = $site."paytr-odeme-hatali";
                                      #
                                      ## Müşterinin sepet/sipariş içeriği

                                        
                                        $user_basket = base64_encode(json_encode(array(
                                            array($cek['title'], ($genel_toplam * 100), 1)
                                        )));

                                      
                                      #
                                      /* ÖRNEK $user_basket oluşturma - Ürün adedine göre array'leri çoğaltabilirsiniz
                                      $user_basket = base64_encode(json_encode(array(
                                          array("Örnek ürün 1", "18.00", 1), // 1. ürün (Ürün Ad - Birim Fiyat - Adet )
                                          array("Örnek ürün 2", "33.25", 2), // 2. ürün (Ürün Ad - Birim Fiyat - Adet )
                                          array("Örnek ürün 3", "45.42", 1)  // 3. ürün (Ürün Ad - Birim Fiyat - Adet )
                                      )));
                                      */
                                      ############################################################################################

                                      ## Kullanıcının IP adresi
                                      if( isset( $_SERVER["HTTP_CLIENT_IP"] ) ) {
                                          $ip = $_SERVER["HTTP_CLIENT_IP"];
                                      } elseif( isset( $_SERVER["HTTP_X_FORWARDED_FOR"] ) ) {
                                          $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
                                      } else {
                                          $ip = $_SERVER["REMOTE_ADDR"];
                                      }

                                      ## !!! Eğer bu örnek kodu sunucuda değil local makinanızda çalıştırıyorsanız
                                      ## buraya dış ip adresinizi (https://www.whatismyip.com/) yazmalısınız. Aksi halde geçersiz paytr_token hatası alırsınız.
                                      #$user_ip=$ip;
                                      $user_ip = $ip;
                                      ##

                                      ## İşlem zaman aşımı süresi - dakika cinsinden
                                      $timeout_limit = "30";

                                      ## Hata mesajlarının ekrana basılması için entegrasyon ve test sürecinde 1 olarak bırakın. Daha sonra 0 yapabilirsiniz.
                                      $debug_on = 1;

                                      ## Mağaza canlı modda iken test işlem yapmak için 1 olarak gönderilebilir.
                                      $test_mode = 0;

                                      $no_installment = 0; // Taksit yapılmasını istemiyorsanız, sadece tek çekim sunacaksanız 1 yapın

                                      ## Sayfada görüntülenecek taksit adedini sınırlamak istiyorsanız uygun şekilde değiştirin.
                                      ## Sıfır (0) gönderilmesi durumunda yürürlükteki en fazla izin verilen taksit geçerli olur.
                                      $max_installment = 0;

                                      $currency = "TL";
                                      
                                      ####### Bu kısımda herhangi bir değişiklik yapmanıza gerek yoktur. #######
                                      $hash_str = $merchant_id .$user_ip .$merchant_oid .$email .$payment_amount .$user_basket.$no_installment.$max_installment.$currency.$test_mode;
                                      $paytr_token=base64_encode(hash_hmac('sha256',$hash_str.$merchant_salt,$merchant_key,true));
                                      $post_vals=array(
                                              'merchant_id'=>$merchant_id,
                                              'user_ip'=>$user_ip,
                                              'merchant_oid'=>$merchant_oid,
                                              'email'=>$email,
                                              'payment_amount'=>$payment_amount,
                                              'paytr_token'=>$paytr_token,
                                              'user_basket'=>$user_basket,
                                              'debug_on'=>$debug_on,
                                              'no_installment'=>$no_installment,
                                              'max_installment'=>$max_installment,
                                              'user_name'=>$user_name,
                                              'user_address'=>$user_address,
                                              'user_phone'=>$user_phone,
                                              'merchant_ok_url'=>$merchant_ok_url,
                                              'merchant_fail_url'=>$merchant_fail_url,
                                              'timeout_limit'=>$timeout_limit,
                                              'currency'=>$currency,
                                              'test_mode'=>$test_mode
                                          );
                                      
                                      $ch=curl_init();
                                      curl_setopt($ch, CURLOPT_URL, "https://www.paytr.com/odeme/api/get-token");
                                      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                                      curl_setopt($ch, CURLOPT_POST, 1) ;
                                      curl_setopt($ch, CURLOPT_POSTFIELDS, $post_vals);
                                      curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
                                      curl_setopt($ch, CURLOPT_TIMEOUT, 20);
                                      
                                       // XXX: DİKKAT: lokal makinanızda "SSL certificate problem: unable to get local issuer certificate" uyarısı alırsanız eğer
                                       // aşağıdaki kodu açıp deneyebilirsiniz. ANCAK, güvenlik nedeniyle sunucunuzda (gerçek ortamınızda) bu kodun kapalı kalması çok önemlidir!
                                       // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                                       
                                      $result = @curl_exec($ch);

                                      if(curl_errno($ch))
                                          die("PAYTR IFRAME connection error. err:".curl_error($ch));

                                      curl_close($ch);
                                      
                                      $result=json_decode($result,1);
                                          
                                      if($result['status']=='success')
                                          $token=$result['token'];
                                      else
                                          die("PAYTR IFRAME failed. reason:".$result['reason']);
                                       /**************** PAY TR *****************/



                                       

                                      ?>
                                      <!-- Ödeme formunun açılması için gereken HTML kodlar / Başlangıç -->
                                      <script src="https://www.paytr.com/js/iframeResizer.min.js"></script>
                                      <iframe src="https://www.paytr.com/odeme/guvenli/<?php echo $token;?>" id="paytriframe" frameborder="0" scrolling="no" style="width: 100%;"></iframe>
                                      <script>iFrameResize({},'#paytriframe');</script>
                                      <!-- Ödeme formunun açılması için gereken HTML kodlar / Bitiş -->
                                      <?php

                              }else if($yontem['sanal_pos'] == 3){

                                include 'shopier-fonksiyon.php';
                                $shopier = new Shopier(API_KEY, API_SECRET);
                                $shopier->setBuyer([
                                  'id' => 23,
                                  'first_name' => $_GET["ad"], 'last_name' => $_GET["discord"], 'email' => 'info@massacre.store', 'phone' => '5488484006']);
                                $shopier->setOrderBilling([
                                  'billing_address' => 'Dijital Teslimat',
                                  'billing_city' => 'Istanbul',
                                  'billing_country' => 'Turkey',
                                  'billing_postcode' => '34200',
                                ]);
                                $shopier->setOrderShipping([
                                  'shipping_address' => 'Dijital Teslimat',
                                  'shipping_city' => 'Istanbul',
                                  'shipping_country' => 'Turkey',
                                  'shipping_postcode' => '34200',
                                ]);
                              
                                    
                                         die($shopier->run($_GET["discord"], 100, 'https://massacre.store/siparislerim'));;

                              }

                            }
                        
                        }else{
                            echo '<center><img src="assets/images/hata.png" style="width:150px"><br><br><span class="hata">An error has occurred.</span></center>';
                        }

                    }
                  }else{
                            echo '<center><img src="assets/images/hata.png" style="width:150px"><br><br><span class="hata">Out of Stock.</span></center>';
                        }

          ?>
        
      </div>
    </div>

  </div>
</div>
