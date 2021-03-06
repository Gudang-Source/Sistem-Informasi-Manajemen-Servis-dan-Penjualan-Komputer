<head>
<script src="assets/js/jquery-2.0.3.min.js"></script>
<script src="assets/js/jquery.validate.min.js"></script>
	<script type="text/javascript">
$(document).ready(function() {
	$("#myForm").validate();
})

function angka(evt) {
    var charCode = (evt.which) ? evt.which : event.keyCode
    if (charCode > 31 && (charCode < 48 || charCode > 57))
 
    return false;
    return true;
}

function huruf(evt){
        var charCode = (evt.which) ? evt.which : event.keyCode
        if ((charCode < 65 || charCode > 90)&&(charCode < 97 || charCode > 122)&&charCode>32)
        return false;
        return true;
}



</script>


<style type="text/css">
input { padding: 3px; border: 1px solid #999; }
input.error, select.error { border: 1px solid red; }
label.error { color:red; margin-left: 10px; }
td { padding: 5px; }

</style>
</head>

<?php
include_once "library/inc.seslogin.php";
include_once "library/inc.library.php";

if(isset($_POST['btnSimpan'])){
	# Validasi form, jika kosong sampaikan pesan error
	$pesanError = array();
	if (trim($_POST['txtNama'])=="") {
		$pesanError[] = "Data <b>Nama Barang</b> tidak boleh kosong !";		
	}
//	if (trim($_POST['txtKeterangan'])=="") {
//		$pesanError[] = "Data <b>Nama Barang (lengkap)</b> tidak boleh kosong !";		
//	}
//	if (trim($_POST['cmbSatuan'])=="KOSONG") {
//		$pesanError[] = "Data <b>Satuan Barang</b> belum dipilih !";		
//	}
	if (trim($_POST['txtHargaBeli'])=="0" or ! is_numeric(trim($_POST['txtHargaBeli']))) {
		$pesanError[] = "<b>Harga Beli (Rp.)</b> tidak boleh  0 !";		
	}
	if (trim($_POST['txtHargaJual'])=="0" or ! is_numeric(trim($_POST['txtHargaJual']))) {
		$pesanError[] = "<b>Harga Jual (Rp.)</b> tidak boleh 0 !";		
	}
	if (trim($_POST['txtHargaBeli'])>=trim($_POST['txtHargaJual'])) {
		$pesanError[] = "<b>Harga Jual </b>harus lebih mahal dari Harga Beli (Rp.) !";		
	}
	if (trim($_POST['cmbKategori'])=="KOSONG") {
		$pesanError[] = "Data <b>Kategori Barang</b> belum dipilih !";		
	}
	if (trim($_POST['cmbSupplier'])=="KOSONG") {
		$pesanError[] = "Data <b>Supplier Barang</b> belum dipilih !";		
	}
	
	# Baca Variabel Form
//	$txtBarcode		= $_POST['txtBarcode'];
	
	$txtNama	= $_POST['txtNama'];
	$txtNama	= str_replace("'","&acute;",$txtNama); // menghalangi penulisan tanda petik satu (')
	
	$txtKeterangan	= $_POST['txtKeterangan'];
	$txtKeterangan	= str_replace("'","&acute;",$txtKeterangan); // menghalangi penulisan tanda petik satu (')
	
//	$cmbSatuan		= $_POST['cmbSatuan'];
	
	$txtKeterangan	= $_POST['txtKeterangan'];
	$txtKeterangan	= str_replace("'","&acute;",$txtKeterangan); // menghalangi penulisan tanda petik satu (')
	
	$txtHargaBeli	= $_POST['txtHargaBeli'];
	$txtHargaBeli	= str_replace(".","",$txtHargaBeli); // validasi, supaya tanda titik dihilangkan, angka 1.700 = 1700
	
	$txtHargaJual	= $_POST['txtHargaJual'];
	$txtHargaJual	= str_replace(".","",$txtHargaJual); // validasi, supaya tanda titik dihilangkan, angka 1.700 menjadi 1700
	
	$cmbKategori		= $_POST['cmbKategori'];
	$cmbSupplier	= $_POST['cmbSupplier'];
	
	# Validasi Nama barang, jika sudah ada akan ditolak
	$sqlCek="SELECT * FROM barang WHERE nm_barang='$txtNama'";
	$qryCek=mysql_query($sqlCek, $koneksidb) or die ("Eror Query".mysql_error()); 
	if(mysql_num_rows($qryCek)>=1){
		$pesanError[] = "Maaf, Nama Barang <b> $txtNama </b> sudah dipakai, ganti dengan yang lain";
	}


	# JIKA ADA PESAN ERROR DARI VALIDASI
	if (count($pesanError)>=1 ){
		echo "<div class='alert alert-error'>";
		echo "<button type='button' class='close' data-dismiss='alert'>
											<i class='icon-remove'></i></button>

										<strong>
											";
			$noPesan=0;
			foreach ($pesanError as $indeks=>$pesan_tampil) { 
			$noPesan++;
				echo "&nbsp;&nbsp; $noPesan. $pesan_tampil<br>";	
			} 
		echo "</strong></div> <br>"; 
	}
	else {
		# SIMPAN DATA KE DATABASE. // Jika tidak menemukan error, simpan data ke database
		$kodeBaru	= buatKode("barang", "B");
		$mySql	= "INSERT INTO barang (kd_barang, nm_barang, keterangan, harga_beli, harga_jual,
										stok, kd_kategori, kd_supplier) 
							VALUES ('$kodeBaru',
									'$txtNama',
									'$txtKeterangan',
									'$txtHargaBeli',
									'$txtHargaJual',
									'0',
									'$cmbKategori',
									'$cmbSupplier')";
		$myQry	= mysql_query($mySql, $koneksidb) or die ("Gagal query".mysql_error());
		if($myQry){
			echo "<meta http-equiv='refresh' content='0; url=?page=Barang-Data'>";
		}
		exit;
	}

} // Penutup POST
	
# MASUKKAN DATA KE VARIABEL
$dataKode	= buatKode("barang", "B");
// $barcode	= substr($dataKode, -6, 6); // 6 digit dari kanan (hilangkan karakter simbol B)
// $dataBarcode= isset($_POST['txtBarcode']) ? $_POST['txtBarcode'] : $barcode;
$dataNama	= isset($_POST['txtNama']) ? $_POST['txtNama'] : '';
$dataKeterangan	= isset($_POST['txtKeterangan']) ? $_POST['txtKeterangan'] : '';
// $dataSatuan		= isset($_POST['cmbSatuan']) ? $_POST['cmbSatuan'] : '';
$dataHargaBeli	= isset($_POST['txtHargaBeli']) ? $_POST['txtHargaBeli'] : '0';
$dataHargaJual	= isset($_POST['txtHargaJual']) ? $_POST['txtHargaJual'] : '0';
$dataKategori	= isset($_POST['cmbKategori']) ? $_POST['cmbKategori'] : '';
$dataSupplier	= isset($_POST['cmbSupplier']) ? $_POST['cmbSupplier'] : '';
?>
<form  id="myForm"  action="<?php $_SERVER['PHP_SELF']; ?>" method="post" name="frmadd" target="_self">
<h3 class="header smaller lighter blue">TAMBAH DATA BARANG </h3>
<table width="100%" cellpadding="2" cellspacing="1" class="table-list" style="margin-top:0px;">
	
	<tr>
	  <td width="15%"><b>Kode Barang</b></td>
	  <td width="1%"><b>:</b></td>
	  <td width="84%"><input type="text" name="textfield" value="<?php echo $dataKode; ?>" size="14" maxlength="10" readonly="readonly"/></td></tr>
<!--	<tr>
      <td><b>Barcode</b></td>
	  <td><b>:</b></td>
	  <td><input type="text" name="txtBarcode" value="<?php // echo $dataBarcode; ?>" size="40" maxlength="20" 
	  				onblur="if (value == '') {value = '<?php // echo $dataBarcode; ?>'}" 
				 	onfocus="if (value == '<?php // echo $dataBarcode; ?>') {value =''}"/></td>
    </tr> -->
	<tr>
	  <td><b>Nama Barang</b></td>
      <td><b>:</b></td>
	  <td><input type="text" name="txtNama" value="<?php echo $dataNama; ?>" size="40" maxlength="40"  title="Nama Barang Tidak Boleh Kosong" class="required"/>	  
      </td>
    </tr>
	<tr>
	  <td><b>Keterangan</b></td>
	  <td><b>:</b></td>
	  <td><input type="text" name="txtKeterangan" value="<?php echo $dataKeterangan; ?>" size="80" maxlength="200" /></td>
	</tr>
<!--	<tr>
	  <td><strong>Satuan</strong></td>
	  <td><b>:</b></td>
	  <td><b>
	    <select name="cmbSatuan">
          <option value="KOSONG">--Pilih--</option>
         satuan php
        </select>
	  </b></td>
    </tr>  -->
	<tr>
      <td><b>Harga Beli (Rp.) </b></td>
	  <td><b>:</b></td>
	  <td><input type="text" name="txtHargaBeli" value="<?php echo $dataHargaBeli; ?>" size="20" maxlength="12" 
	  			onblur="if (value == '') {value = '0'}" 
				onfocus="if (value == '0') {value =''}"
				title="Harga Beli Tidak Boleh Kosong" class="required" onkeypress="return angka(event)"/></td>
    </tr>
	<tr>
      <td><b>Harga Jual (Rp.) </b></td>
	  <td><b>:</b></td>
	  <td><input type="text" name="txtHargaJual" value="<?php echo $dataHargaJual; ?>" size="20" maxlength="12" 
	  			onblur="if (value == '') {value = '0'}" 
				onfocus="if (value == '0') {value =''}"
				title="Harga Jual Tidak Boleh Kosong" class="required" onkeypress="return angka(event)" /></td>
    </tr>
	<tr>
      <td><strong>Kategori </strong></td>
	  <td><strong>:</strong></td>
	  <td><select name="cmbKategori" class="required" title="Kategori Tidak Boleh Kosong">
          <option></option>
          <?php
		$mySql = "SELECT * FROM kategori ORDER BY nm_kategori";
		$myQry = mysql_query($mySql, $koneksidb) or die ("Gagal Query".mysql_error());
		while ($myData = mysql_fetch_array($myQry)) {
		if ($myData['kd_kategori']== $dataKategori) {
			$cek = " selected";
		} else { $cek=""; }
		echo "<option value='$myData[kd_kategori]' $cek>$myData[nm_kategori] </option>";
		}
		?>
      </select></td>
    </tr>
	<tr>
      <td><b>Supplier</b></td>
	  <td><b>:</b></td>
	  <td><b>
        <select name="cmbSupplier" class="required" title="Supplier Tidak Boleh Kosong">
          <option></option>
          <?php
	  $mySql = "SELECT * FROM supplier ORDER BY kd_supplier";
	  $myQry = mysql_query($mySql, $koneksidb) or die ("Gagal Query".mysql_error());
	  while ($myData = mysql_fetch_array($myQry)) {
	  	if ($dataSupplier == $myData['kd_supplier']) {
			$cek = " selected";
		} else { $cek=""; }
	  	echo "<option value='$myData[kd_supplier]' $cek> $myData[nm_supplier]</option>";
	  }
	  ?>
        </select>
      </b></td>
    </tr>
	<tr><td>&nbsp;</td>
	  <td>&nbsp;</td>
	  <td><input type="submit" name="btnSimpan" value=" SIMPAN " style="cursor:pointer;" class="btn-primary"></td>
    </tr>
</table>
<!-- <strong># Note:</strong> Stok akan bertambah jika sudah ada Order dari Form <a href="dasboard.php?page=Transaksi-Pembelian">Pembelian (Penerimaan Barang Masuk)</a> -->
</form>
