<?php
$query = $db->prepare("SELECT * FROM urun where sef=:sef LIMIT 1");
$urun = $query->execute(array(":sef"=>$_GET['sef']));
$urun = $query->fetch(PDO::FETCH_ASSOC);

if(!$urun){
  die ('<meta http-equiv="refresh" content="0;URL=index.php">');
}

$_title         =  $urun['baslik'];
$_description   =  $urun['kisa_aciklama'];


$img = $db->query("SELECT * FROM urun_img WHERE urun_id = '{$urun['id']}' LIMIT 1")->fetch(PDO::FETCH_ASSOC);

?>
<div class="container mt-20 mb-20">
<div class="row">
  



  <div class="col-md-3">
    <div class="detay_sol">
      <img src="upload/<?php echo $img['img']; ?>" class="img-responsive">
      <h1><?php echo $urun['baslik']; ?></h1>
      <p><?php echo $urun['aciklama']; ?></p>
    </div>
  </div>

  <div class="col-md-9">
    <div class="detay_sag">

        <ul>

          <?php
            $query = $db->query("SELECT * FROM urun_secenek WHERE urun_id = '{$urun['id']}' ", PDO::FETCH_ASSOC);
            if($query->rowCount()){
              foreach( $query as $row ){

                $as = $db->query("SELECT * FROM urun_secenek_alt WHERE urun_secenek_id = '{$row['id']}' AND stok > 0 LIMIT 1")->fetch(PDO::FETCH_ASSOC);
                if($as){
          ?>
          <li>
            <div class="row">
              <div class="col-md-1"><div class="idis"><img src="assets/images/hediye.svg" class="img-responsive"></div></div>
              <div class="col-md-7">
                <b><?php echo $row['baslik']; ?></b>
                <p><?php echo $urun['baslik']; ?> - <?php echo $row['baslik']; ?></p>
              </div>
              <div class="col-md-2"><b><?php echo $as['fiyat']; ?> TL</b></div>
              <div class="col-md-2"><a href="sepetim/<?php echo $as['id']; ?>" class="btn btn-success">Buy</a></div>
            </div>
          </li>
          <?php } } } ?>

        </ul>

    </div>
  </div>

</div>
</div>