<?php
if(!isset($_SESSION['kullanici']['login'])){
    die('<meta http-equiv="refresh" content="0;URL=index.php">');
}
$_title         =  'Hesabım';
$query = $db->prepare("SELECT * FROM kullanici where id=:id LIMIT 1");
$kullanici = $query->execute(array(":id"=>$_SESSION['kullanici']['id']));
$kullanici = $query->fetch(PDO::FETCH_ASSOC);
?>
<div class="container">
	<div class="row mt-20 mb-20">
		

		<div class="col-md- detay_sol">
			<?php
              if($_POST){
                $query = $db->prepare("SELECT * FROM kullanici where email=:email AND id !=:id LIMIT 1");
                $bilgi = $query->execute(array(":email"=>$_POST['email'],":id"=>$_SESSION['kullanici']['id']));
                $bilgi = $query->fetch(PDO::FETCH_ASSOC);
                if($bilgi){
                    echo '<div class="hata">This email address is already in use.</div>';
                }else{
                  $islem = $db->prepare("UPDATE kullanici SET  ad = ?, soyad = ?, telefon = ?, email = ?, sifre = ?, adres = ?, tc = ? WHERE id = ?");
                  $islem = $islem->execute(array($_POST['ad'],$_POST['soyad'],$_POST['telefon'],$_POST['email'],$_POST['sifre'],$_POST['adres'],$_POST['tc'],$_SESSION['kullanici']['id']));
                  if($islem){
                        echo '<div class="basari">Your information has been successfully changed.<meta http-equiv="refresh" content="2;URL=hesabim"></div>';
                    }else{
                        echo '<div class="hata">Operation failed.</div>';
                    } 
                }
              }
              ?>
			<div class="bg2 border p20" style="float: left;width: 100%">
				<h2>Update My Information</h2>
				<form action="" method="post">
	              <fieldset class="form-group mt-4 col-md-6">
	                 <label>Name</label>
	                 <input type="text" class="form-control" name="ad" required="" placeholder="Name" value="<?php echo $kullanici['ad']; ?>">
	              </fieldset>
	              <fieldset class="form-group mt-4 col-md-6">
	                 <label>Surname</label>
	                 <input type="text" class="form-control" name="soyad" required="" placeholder="Surname" value="<?php echo $kullanici['soyad'];  ?>">
	              </fieldset>
	              <fieldset class="form-group mt-4 col-md-6">
	                 <label>Phone</label>
	                 <input type="text" class="form-control" name="telefon" required="" placeholder="Phone" value="<?php echo $kullanici['telefon']; ?>">
	              </fieldset>
	              <fieldset class="form-group mt-4 col-md-6">
	                 <label>E-mail</label>
	                 <input type="email" class="form-control" name="email" required="" placeholder="E-mail" value="<?php echo $kullanici['email']; ?>">
	              </fieldset>
	              <fieldset class="form-group col-md-12">
	                 <label>Password</label>
	                 <input type="password" class="form-control" name="sifre" required="" value="<?php echo $kullanici['sifre']; ?>">
	              </fieldset>
	              <fieldset class="form-group col-md-12">
	                 <label>Tc</label>
	                 <input type="text" class="form-control" name="tc" required="" value="<?php echo $kullanici['tc']; ?>">
	              </fieldset>
	              <fieldset class="form-group col-md-12">
	                 <label>Address</label>
	                 <textarea class="form-control" name="adres" required="" placeholder="Address" rows="3"><?php echo $kullanici['adres']; ?></textarea>
	              </fieldset>
	              <fieldset class="form-group col-md-12">
	                 <button class="btn icon-btn btn-success" style="width: 100%">Update My Information</button>
	              </fieldset>
	            </form>
			</div>
		</div>


	</div>
</div>