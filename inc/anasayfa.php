<div class="modal fade" id="hikaye-popup" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document" style="width: fit-content;">
    <div class="modal-content">
      <div id="saniye" style="width: 0px;height: 10px;background: #f12870;float: left;"></div>
      <div class="modal-body" style="padding: 0px">
        <div class="row">
          <div class="col-md-12" id="icerik"></div>
        </div>
      </div>
    </div>
  </div>
</div>


<div class="container">
  <div class="row">
    
    <?php
      $query = $db->query("SELECT * FROM hikaye ORDER BY id DESC", PDO::FETCH_ASSOC);
      if($query->rowCount()){
      ?>
        <div class="col-md-12 mt-20 mb-20">
          <div id="hikaye" class="owl-carousel">
            <?php
                  foreach($query as $row){
                    echo '<div class="item"><a href="javascript:void(0)" data-buyuk-img="'.$row['buyuk_img'].'" data-link="'.$row['link'].'"><img src="upload/'.$row['kucuk_img'].'"></a></div>';
                  }
              ?>
          </div>
        </div>
      <?php } ?>


      <?php
      $query = $db->query("SELECT * FROM vitrin ORDER BY sira ASC", PDO::FETCH_ASSOC);
      if($query->rowCount()){
        foreach($query as $row1){
      ?>
      <div class="row">
          <div class="col-md-12"><div class="vitrin_baslik"><span class="bg-4"><?php echo $row1['baslik']; ?></span></div></div>
            <?php 
              $query = $db->query("SELECT
              urun.id,
              urun.baslik,
              urun.sef,
              urun_img.img
              FROM
              vitrin_urun
              INNER JOIN urun ON vitrin_urun.urun_id = urun.id
              INNER JOIN urun_img ON urun.id = urun_img.urun_id 
              WHERE
              vitrin_urun.vitrin_id = '{$row1['id']}'
              GROUP BY
              urun_img.urun_id
              ORDER BY
              vitrin_urun.sira ASC", PDO::FETCH_ASSOC);
              if($query->rowCount()){
                foreach($query as $row){
              ?>
            <div class="col-md-2 col-xs-6 mt-20">
              <a href="urun/<?php echo $row['sef']; ?>" class="urun">
                <div class="img-dis">
                  <img src="upload/<?php echo $row['img']; ?>" alt="<?php echo $row['baslik']; ?>">
                </div>
              </a>
            </div>
            <?php } } ?>
      </div>
      <?php
          }
        }
      ?>


</div>
</div>