<?php
if(!isset($_SESSION['kullanici']['login'])){
    die('<meta http-equiv="refresh" content="0;URL=index.php">');
}
$_title         =  'Siparişlerim';


?>
<div class="container">
	<div class="row mt-20 mb-20">
		
		<div class="col-md-9 detay_sol">
			
			<div class="bg2 border p20" style="float: left;width: 100%">
				<h2>Orders</h2>
				
				<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
          
	                <?php
	                  $i = 0;
	                  $query = $db->query("SELECT * FROM siparis WHERE kullanici_id = '{$_SESSION['kullanici']['id']}' ORDER BY id DESC ", PDO::FETCH_ASSOC);
	                  if($query->rowCount()){
	                    foreach($query as $row){
	                          
	                          $in = '';
	                          if($i == 0){
	                            $in = 'in';
	                          }

	                          $odeme_durumu = '';
	                          if($row['odeme_yontemi'] == 1){
	                            $odeme_durumu = '<b>Payment Status :</b> <br>'.$kredi_karti_odendi[$row['kredi_karti_odendi']];
	                          }

	                          echo '<div class="panel panel-default">
	                              <div class="panel-heading" role="tab" id="heading'.$row['id'].'">
	                                  <h4 class="panel-title">
	                                      <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse'.$row['id'].'" aria-expanded="false" aria-controls="collapse'.$row['id'].'">
	                                          Order ID: '.$row['id'].' - Total Amount: '.$row['toplam_tutar'].' TL - Payment Method : '.$odeme_yontemi[$row['odeme_yontemi']].'
	                                      </a>
	                                  </h4>
	                              </div>
	                              <div id="collapse'.$row['id'].'" class="panel-collapse collapse '.$in.'" role="tabpanel" aria-labelledby="heading'.$row['id'].'">
	                                  <div class="panel-body">
	                                  <div class="row">';

	                                  $query1 = $db->query("SELECT * FROM siparis_urun WHERE siparis_id = '{$row['id']}'", PDO::FETCH_ASSOC);
	                                  if($query1->rowCount()){
	                                    foreach($query1 as $row1){
	                                      $urunquery = $db->prepare("SELECT * FROM urun where id=:id LIMIT 1");
	                                      $urun = $urunquery->execute(array(":id"=>$row1['urun_id']));
	                                      $urun = $urunquery->fetch(PDO::FETCH_ASSOC);

	                                      $urunimg = $db->prepare("SELECT * FROM urun_img where urun_id=:urun_id LIMIT 1");
	                                      $uimg = $urunimg->execute(array(":urun_id"=>$row1['urun_id']));
	                                      $uimg = $urunimg->fetch(PDO::FETCH_ASSOC);


	                                      $kodq = $db->prepare("SELECT * FROM urun_secenek_alt where id=:id LIMIT 1");
	                                      $kod = $kodq->execute(array(":id"=>$row1['secenek']));
	                                      $kod = $kodq->fetch(PDO::FETCH_ASSOC);

	                                      echo '
	                                        <div class="col-md-6">
	                                          <div style="float:left;width:30%"><img src="upload/'.$uimg['img'].'" style="max-width:100%;border:1px solid #ddd;padding:5px;"></div>
	                                          <div style="float:left;width:65%;padding-left:5%">
	                                          <b style="color:#000">'.$urun['baslik'].'</b><br>';
	                                          if($row['durum'] == 3){
	                                          echo '
	                                          <b>Ürün Kodunuz:</b> '.$kod['baslik'].'<br>
	                                          ';
	                                      }
	                                         echo '<b>Quantity:</b> '.$row1['adet'].'<br>
	                                          <b>Price:</b> '.$row1['fiyat'].' TL<br>
	                                          '.$odeme_durumu.'<br>
	                                          <b>Order Date:</b> '.date('Y-m-d H:i:s', $row['siparis_tarihi']).'<br>
	                                          <b style="color:green;">Order Status:<br> '.$siparis_durum[$row['durum']].'</b>
	                                          </div>
	                                        </div>
	                                      ';


	                                    }
	                                  }

	                          echo '    </div>
	                                  </div>
	                              </div>
	                          </div>';
	                          $i++;
	                    } 
	                  }else{
	                    echo '<center><h1>You do not have an order.</h1></center>';
	                  }
	                ?>
	                
	            </div>

			</div>
		</div>


	</div>
</div>