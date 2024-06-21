<?php

$query = $db->prepare("SELECT * FROM sayfa where sef=:sef LIMIT 1");
$icsayfa = $query->execute(array(":sef"=>$_GET['sef']));
$icsayfa = $query->fetch(PDO::FETCH_ASSOC);

if(!$icsayfa){
  echo '<meta http-equiv="refresh" content="0;URL=index.php">';
}


$_title         =  $icsayfa['baslik'];
$_description   =  $icsayfa['kisa_aciklama'];


?>
<div class="container mt-20 mb-20">
    <div class="row">
      
      <div class="col-md-12">
        <div class="detay_sol">
          <h2><?php echo $icsayfa['baslik']; ?></h2>
        <?php echo $icsayfa['aciklama']; ?>
        </div>
      </div>

    </div>
    </div>
