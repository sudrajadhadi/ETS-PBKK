# ETS-PBKK
Sistem informasi kasir

## Membuat Database
* https://github.com/sudrajadhadi/ETS-PBKK/blob/master/penjualan_db.sql
  link tersebut merupakan pengisian database untuk sistem informasi pencatatan transaksi pada kasir
  ![database](https://github.com/sudrajadhadi/ETS-PBKK/blob/master/foto/database.png)

* Menghubungkan database
  https://github.com/sudrajadhadi/ETS-PBKK/blob/master/application/config/database.php
  ```sql
  $db['default'] = array(
	'dsn'	=> '',
	'hostname' => $CI->config->item("database_host"),
	'username' => $CI->config->item("database_user"),
	'password' => $CI->config->item("database_pass"),
	'database' => $CI->config->item("database_name"),
	'dbdriver' => 'mysqli',
	'dbprefix' => '',
	'pconnect' => FALSE,
	'db_debug' => (ENVIRONMENT !== 'production'),
	'cache_on' => FALSE,
	'cachedir' => '',
	'char_set' => 'utf8',
	'dbcollat' => 'utf8_general_ci',
	'swap_pre' => '',
	'encrypt' => FALSE,
	'compress' => FALSE,
	'stricton' => FALSE,
	'failover' => array(),
	'save_queries' => TRUE
  );
  ```
  hostname, username, dan password diubah sesuai dengan apa yang ada pada https://github.com/sudrajadhadi/ETS-PBKK/blob/master/application/config/config.php
  ```sql
  $config['database_host']	= 'localhost';
  $config['database_user']	= 'root';
  $config['database_pass'] 	= '';
  $config['database_name']	= 'penjualan_db';
  ```
  1. host diisi dengan localhost karena menggunakan xampp
  2. user diisi sesuai dengan setting database pada komputer masing masing
  3. password diisi sesuai dengan setting database pada komputer masing masing
  3. database_name diisi sesuai dengan nama database yang diintegrasikan 
  
## Membuat Halaman Login
* https://github.com/sudrajadhadi/ETS-PBKK/blob/master/application/views/secure/login_page.php merupakan halaman login pada sistem informasi kasir
  ![login](https://github.com/sudrajadhadi/ETS-PBKK/blob/master/foto/login.png)
  ```php
  <?php 
  echo form_input(array(
  'name' => 'username', 
  'class' => 'form-control', 
  'autocomplete' => 'off', 
  'autofocus' => 'autofocus'
  )); 
  ?>
  ```
* sytax tersebut merupakan perintah untuk mengirimkan username pengguna yang nantinya akan divalidasi
  ```php
  echo form_password(array(
  'name' => 'password', 
  'class' => 'form-control', 
  'id' => 'InputPassword'
  ));
  ```
* syntax tersebut merupakan perintah untuk mengirimkan password pengguna yang nantinya akan divalidasi
  ```php
  $('#FormLogin').submit(function(e){
	e.preventDefault();
	$.ajax({
		url: $(this).attr('action'),
		type: "POST",
		cache: false,
		data: $(this).serialize(),
		dataType:'json',
		success: function(json){
			//response dari json_encode di controller

			if(json.status == 1){ window.location.href = json.url_home; }
			if(json.status == 0){ $('#ResponseInput').html(json.pesan); }
			if(json.status == 2){
				$('#ResponseInput').html(json.pesan);
				$('#InputPassword').val('');
			}
		}
	});
   });
   ```
 * code diatas mengirimkan username dan password ke controller untuk divalidasi dan mendapatkan response dari json_encode yang nantinya akan ditentukan kemana website akan diredirect
   ```php
   $('#ResetData').click(function(){
   $('#ResponseInput').html('');
   });
   ```
   untuk meriset input yang dimasukkan sebelumnya

## Membuat Halaman Transaksi
 * merupakan gambar halaman utama untuk melakukan transaksi
   ![transaksi](https://github.com/sudrajadhadi/ETS-PBKK/blob/master/foto/dashboard%20admin.png)
 * mengambil tanggal dan waktu terkini untuk data tanggal transaksi
   ```php
   $('#tanggal').datetimepicker({
	lang:'en',
	timepicker:true,
	format:'Y-m-d H:i:s'
   });
   ```
 * fungsi tersebut untuk menampilkan data pelanggan yang ada di pojok kiri bawah halaman utama transaksi dan menambah baris baru untuk transaksi yang melebihi jumlah input yang disediakan
   ```php
   $(document).ready(function(){

	for(B=1; B<=1; B++){
		BarisBaru();
	}

	$('#id_pelanggan').change(function(){
		if($(this).val() !== '')
		{
			$.ajax({
				url: "<?php echo site_url('penjualan/ajax-pelanggan'); ?>",
				type: "POST",
				cache: false,
				data: "id_pelanggan="+$(this).val(),
				dataType:'json',
				success: function(json){
					$('#telp_pelanggan').html(json.telp);
					$('#alamat_pelanggan').html(json.alamat);
					$('#info_tambahan_pelanggan').html(json.info_tambahan);
				}
			});
		}
		else
		{
			$('#telp_pelanggan').html('<small><i>Tidak ada</i></small>');
			$('#alamat_pelanggan').html('<small><i>Tidak ada</i></small>');
			$('#info_tambahan_pelanggan').html('<small><i>Tidak ada</i></small>');
		}
	});

	$('#BarisBaru').click(function(){
		BarisBaru();
	});

	$("#TabelTransaksi tbody").find('input[type=text],textarea,select').filter(':visible:first').focus();
   });
   ```
 * fungsi tersebut digunakan untuk menampilkan field yang berguna untuk menambahkan kolom pada halaman transaksi setelah itu memanggil fungsi HitungTotalBayar()
   ```php
   function BarisBaru()
   {
	var Nomor = $('#TabelTransaksi tbody tr').length + 1;
	var Baris = "<tr>";
		Baris += "<td>"+Nomor+"</td>";
		Baris += "<td>";
			Baris += "<input type='text' class='form-control' name='kode_barang[]' id='pencarian_kode' placeholder='Ketik Kode / Nama Barang'>";
			Baris += "<div id='hasil_pencarian'></div>";
		Baris += "</td>";
		Baris += "<td></td>";
		Baris += "<td>";
			Baris += "<input type='hidden' name='harga_satuan[]'>";
			Baris += "<span></span>";
		Baris += "</td>";
		Baris += "<td><input type='text' class='form-control' id='jumlah_beli' name='jumlah_beli[]' onkeypress='return check_int(event)' disabled></td>";
		Baris += "<td>";
			Baris += "<input type='hidden' name='sub_total[]'>";
			Baris += "<span></span>";
		Baris += "</td>";
		Baris += "<td><button class='btn btn-default' id='HapusBaris'><i class='fa fa-times' style='color:red;'></i></button></td>";
		Baris += "</tr>";

	$('#TabelTransaksi tbody').append(Baris);

	$('#TabelTransaksi tbody tr').each(function(){
		$(this).find('td:nth-child(2) input').focus();
	});

	HitungTotalBayar();
	}
	```
 * fungsi tersebut digunakan untuk menghapus input transaksi dan memanggil fungsi HitungTotalBayar()
   ```php
   $(document).on('click', '#HapusBaris', function(e){
	e.preventDefault();
	$(this).parent().parent().remove();

	var Nomor = 1;
	$('#TabelTransaksi tbody tr').each(function(){
		$(this).find('td:nth-child(1)').html(Nomor);
		Nomor++;
	});

	HitungTotalBayar();
   });
   ```
 * fungsi yang digunakan untuk melakukan auto complete berdasarkan kode barang dan nama barang pada saat pengisian field transaksi dan memanggil fungsi HitungTotalBayar();
   ```php
   function AutoCompleteGue(Lebar, KataKunci, Indexnya)
   {
	$('div#hasil_pencarian').hide();
	var Lebar = Lebar + 25;

	var Registered = '';
	$('#TabelTransaksi tbody tr').each(function(){
		if(Indexnya !== $(this).index())
		{
			if($(this).find('td:nth-child(2) input').val() !== '')
			{
				Registered += $(this).find('td:nth-child(2) input').val() + ',';
			}
		}
	});

	if(Registered !== ''){
		Registered = Registered.replace(/,\s*$/,"");
	}

	$.ajax({
		url: "<?php echo site_url('penjualan/ajax-kode'); ?>",
		type: "POST",
		cache: false,
		data:'keyword=' + KataKunci + '&registered=' + Registered,
		dataType:'json',
		success: function(json){
			if(json.status == 1)
			{
				$('#TabelTransaksi tbody tr:eq('+Indexnya+') td:nth-child(2)').find('div#hasil_pencarian').css({ 'width' : Lebar+'px' });
				$('#TabelTransaksi tbody tr:eq('+Indexnya+') td:nth-child(2)').find('div#hasil_pencarian').show('fast');
				$('#TabelTransaksi tbody tr:eq('+Indexnya+') td:nth-child(2)').find('div#hasil_pencarian').html(json.datanya);
			}
			if(json.status == 0)
			{
				$('#TabelTransaksi tbody tr:eq('+Indexnya+') td:nth-child(3)').html('');
				$('#TabelTransaksi tbody tr:eq('+Indexnya+') td:nth-child(4) input').val('');
				$('#TabelTransaksi tbody tr:eq('+Indexnya+') td:nth-child(4) span').html('');
				$('#TabelTransaksi tbody tr:eq('+Indexnya+') td:nth-child(5) input').prop('disabled', true).val('');
				$('#TabelTransaksi tbody tr:eq('+Indexnya+') td:nth-child(6) input').val(0);
				$('#TabelTransaksi tbody tr:eq('+Indexnya+') td:nth-child(6) span').html('');
			}
		}
	});

	HitungTotalBayar();
    }
    ```
 * fungsi untuk menampilkan daftar barang yang disarankan untuk auto complete
   ```php
   $(document).on('click', '#daftar-autocomplete li', function(){
	$(this).parent().parent().parent().find('input').val($(this).find('span#kodenya').html());
	
	var Indexnya = $(this).parent().parent().parent().parent().index();
	var NamaBarang = $(this).find('span#barangnya').html();
	var Harganya = $(this).find('span#harganya').html();

	$('#TabelTransaksi tbody tr:eq('+Indexnya+') td:nth-child(2)').find('div#hasil_pencarian').hide();
	$('#TabelTransaksi tbody tr:eq('+Indexnya+') td:nth-child(3)').html(NamaBarang);
	$('#TabelTransaksi tbody tr:eq('+Indexnya+') td:nth-child(4) input').val(Harganya);
	$('#TabelTransaksi tbody tr:eq('+Indexnya+') td:nth-child(4) span').html(to_rupiah(Harganya));
	$('#TabelTransaksi tbody tr:eq('+Indexnya+') td:nth-child(5) input').removeAttr('disabled').val(1);
	$('#TabelTransaksi tbody tr:eq('+Indexnya+') td:nth-child(6) input').val(Harganya);
	$('#TabelTransaksi tbody tr:eq('+Indexnya+') td:nth-child(6) span').html(to_rupiah(Harganya));

	var IndexIni = Indexnya + 1;
	var TotalIndex = $('#TabelTransaksi tbody tr').length;
	if(IndexIni == TotalIndex){
		BarisBaru();
		$('html, body').animate({ scrollTop: $(document).height() }, 0);
	}
	else {
		$('#TabelTransaksi tbody tr:eq('+Indexnya+') td:nth-child(5) input').focus();
	}

	HitungTotalBayar();
   });
   ```
 * digunakan untuk mengecek apakah stock tersedia atau tidak pada database
   ```php
   $(document).on('keyup', '#jumlah_beli', function(){
	var Indexnya = $(this).parent().parent().index();
	var Harga = $('#TabelTransaksi tbody tr:eq('+Indexnya+') td:nth-child(4) input').val();
	var JumlahBeli = $(this).val();
	var KodeBarang = $('#TabelTransaksi tbody tr:eq('+Indexnya+') td:nth-child(2) input').val();

	$.ajax({
		url: "<?php echo site_url('barang/cek-stok'); ?>",
		type: "POST",
		cache: false,
		data: "kode_barang="+encodeURI(KodeBarang)+"&stok="+JumlahBeli,
		dataType:'json',
		success: function(data){
			if(data.status == 1)
			{
				var SubTotal = parseInt(Harga) * parseInt(JumlahBeli);
				if(SubTotal > 0){
					var SubTotalVal = SubTotal;
					SubTotal = to_rupiah(SubTotal);
				} else {
					SubTotal = '';
					var SubTotalVal = 0;
				}

				$('#TabelTransaksi tbody tr:eq('+Indexnya+') td:nth-child(6) input').val(SubTotalVal);
				$('#TabelTransaksi tbody tr:eq('+Indexnya+') td:nth-child(6) span').html(SubTotal);
				HitungTotalBayar();
			}
			if(data.status == 0)
			{
				$('.modal-dialog').removeClass('modal-lg');
				$('.modal-dialog').addClass('modal-sm');
				$('#ModalHeader').html('Oops !');
				$('#ModalContent').html(data.pesan);
				$('#ModalFooter').html("<button type='button' class='btn btn-primary' data-dismiss='modal' autofocus>Ok, Saya Mengerti</button>");
				$('#ModalGue').modal('show');

				$('#TabelTransaksi tbody tr:eq('+Indexnya+') td:nth-child(5) input').val('1');
			}
		}
	});
   });
   ```
 * menghitung jumlah semua barang yang diinputkan
   ```php
   function HitungTotalBayar()
   {
	var Total = 0;
	$('#TabelTransaksi tbody tr').each(function(){
		if($(this).find('td:nth-child(6) input').val() > 0)
		{
			var SubTotal = $(this).find('td:nth-child(6) input').val();
			Total = parseInt(Total) + parseInt(SubTotal);
		}
	});

	$('#TotalBayar').html(to_rupiah(Total));
	$('#TotalBayarHidden').val(Total);

	$('#UangCash').val('');
	$('#UangKembali').val('');
   }
   ```
 * menghitung jumlah uang kembalian
   ```php
   function HitungTotalKembalian()
   {
	var Cash = $('#UangCash').val();
	var TotalBayar = $('#TotalBayarHidden').val();

	if(parseInt(Cash) >= parseInt(TotalBayar)){
		var Selisih = parseInt(Cash) - parseInt(TotalBayar);
		$('#UangKembali').val(to_rupiah(Selisih));
	} else {
		$('#UangKembali').val('');
	}
    }
    ```
   
 * fungsi untuk menconvert semua angka bisah menjadi betuk satuan mata uang indonesia
   ```php
   function to_rupiah(angka){
    var rev     = parseInt(angka, 10).toString().split('').reverse().join('');
    var rev2    = '';
    for(var i = 0; i < rev.length; i++){
        rev2  += rev[i];
        if((i + 1) % 3 === 0 && i !== (rev.length - 1)){
            rev2 += '.';
        }
    }
    return 'Rp. ' + rev2.split('').reverse().join('');
    }
    ```
  * memberikan shotcut untuk beberapa tombol sehingga memperbudah proses transaksi
    ```php
    $(document).on('keydown', 'body', function(e){
	var charCode = ( e.which ) ? e.which : event.keyCode;

	if(charCode == 118) //F7
	{
		BarisBaru();
		return false;
	}

	if(charCode == 119) //F8
	{
		$('#UangCash').focus();
		return false;
	}

	if(charCode == 120) //F9
	{
		CetakStruk();
		return false;
	}

	if(charCode == 121) //F10
	{
		$('.modal-dialog').removeClass('modal-lg');
		$('.modal-dialog').addClass('modal-sm');
		$('#ModalHeader').html('Konfirmasi');
		$('#ModalContent').html("Apakah anda yakin ingin menyimpan transaksi ini ?");
		$('#ModalFooter').html("<button type='button' class='btn btn-primary' id='SimpanTransaksi'>Ya, saya yakin</button><button type='button' class='btn btn-default' data-dismiss='modal'>Batal</button>");
		$('#ModalGue').modal('show');

		setTimeout(function(){ 
	   		$('button#SimpanTransaksi').focus();
	    }, 500);

		return false;
	}
    });
    ```
 * fungsi untuk memberikan perintah controller untuk menyimpan transaksi pada database
   ```php
   function SimpanTransaksi()
   {
	var FormData = "nomor_nota="+encodeURI($('#nomor_nota').val());
	FormData += "&tanggal="+encodeURI($('#tanggal').val());
	FormData += "&id_kasir="+$('#id_kasir').val();
	FormData += "&id_pelanggan="+$('#id_pelanggan').val();
	FormData += "&" + $('#TabelTransaksi tbody input').serialize();
	FormData += "&cash="+$('#UangCash').val();
	FormData += "&catatan="+encodeURI($('#catatan').val());
	FormData += "&grand_total="+$('#TotalBayarHidden').val();

	$.ajax({
		url: "<?php echo site_url('penjualan/transaksi'); ?>",
		type: "POST",
		cache: false,
		data: FormData,
		dataType:'json',
		success: function(data){
			if(data.status == 1)
			{
				alert(data.pesan);
				window.location.href="<?php echo site_url('penjualan/transaksi'); ?>";
			}
			else 
			{
				$('.modal-dialog').removeClass('modal-lg');
				$('.modal-dialog').addClass('modal-sm');
				$('#ModalHeader').html('Oops !');
				$('#ModalContent').html(data.pesan);
				$('#ModalFooter').html("<button type='button' class='btn btn-primary' data-dismiss='modal' autofocus>Ok</button>");
				$('#ModalGue').modal('show');
			}	
		}
	});
   }
   ```
 * fungsi yang digunakan untuk cetak struk transaksi
   ```php
   function CetakStruk()
   {
	if($('#TotalBayarHidden').val() > 0)
	{
		if($('#UangCash').val() !== '')
		{
			var FormData = "nomor_nota="+encodeURI($('#nomor_nota').val());
			FormData += "&tanggal="+encodeURI($('#tanggal').val());
			FormData += "&id_kasir="+$('#id_kasir').val();
			FormData += "&id_pelanggan="+$('#id_pelanggan').val();
			FormData += "&" + $('#TabelTransaksi tbody input').serialize();
			FormData += "&cash="+$('#UangCash').val();
			FormData += "&catatan="+encodeURI($('#catatan').val());
			FormData += "&grand_total="+$('#TotalBayarHidden').val();

			window.open("<?php echo site_url('penjualan/transaksi-cetak/?'); ?>" + FormData,'_blank');
		}
		else
		{
			$('.modal-dialog').removeClass('modal-lg');
			$('.modal-dialog').addClass('modal-sm');
			$('#ModalHeader').html('Oops !');
			$('#ModalContent').html('Harap masukan Total Bayar');
			$('#ModalFooter').html("<button type='button' class='btn btn-primary' data-dismiss='modal' autofocus>Ok</button>");
			$('#ModalGue').modal('show');
		}
	}
	else
	{
		$('.modal-dialog').removeClass('modal-lg');
		$('.modal-dialog').addClass('modal-sm');
		$('#ModalHeader').html('Oops !');
		$('#ModalContent').html('Harap pilih barang terlebih dahulu');
		$('#ModalFooter').html("<button type='button' class='btn btn-primary' data-dismiss='modal' autofocus>Ok</button>");
		$('#ModalGue').modal('show');

	}
   }
   ```
## Membuat History Penjualan
 * Tampilan History Penjualan
   ![history](https://github.com/sudrajadhadi/ETS-PBKK/blob/master/foto/history%20transaksi.png)
 * fungsi untuk melakukan pencarian data pada halaman histroy penjualan
   ```php
   "oLanguage": {
	"sSearch": "<i class='fa fa-search fa-fw'></i> Pencarian : ",
	"sLengthMenu": "_MENU_ &nbsp;&nbsp;Data Per Halaman <?php echo $tambahan; ?>",
	"sInfo": "Menampilkan _START_ s/d _END_ dari <b>_TOTAL_ data</b>",
	"sInfoFiltered": "(difilter dari _MAX_ total data)", 
	"sZeroRecords": "Pencarian tidak ditemukan", 
	"sEmptyTable": "Data kosong", 
	"sLoadingRecords": "Harap Tunggu...", 
	"oPaginate": {
		"sPrevious": "Prev",
		"sNext": "Next"
		}
   },
   ```
 * fungsi untuk mengurutkan data history penjualan
   ```php
   "aaSorting": [[ 0, "desc" ]],
	"columnDefs": [ 
	{
		"targets": 'no-sort',
	 	"orderable": false,
	}
   ],
   ```
 * fungsi untuk menampilkan beberapa data dan memberikan error kesalahan apabila data tidak ditampilkan
   ```php
   "sPaginationType": "simple_numbers", 
	"iDisplayLength": 10,
	"aLengthMenu": [[10, 20, 50, 100, 150], [10, 20, 50, 100, 150]],
	"ajax":{
		url :"<?php echo site_url('penjualan/history-json'); ?>",
		type: "post",
		error: function(){ 
			$(".my-grid-error").html("");
			$("#my-grid").append('<tbody class="my-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
			$("#my-grid_processing").css("display","none");
			}
   ```
 * fungsi meampilkan pop up notifikasi untuk menghapus data pada history transaksi
   ```php
   $(document).on('click', '#HapusTransaksi', function(e){
	e.preventDefault();
	var Link = $(this).attr('href');
	var Check = "<br /><hr style='margin:10px 0px 8px 0px;' /><div class='checkbox'><label><input type='checkbox' name='reverse_stok' value='yes' id='reverse_stok'> Kembalikan stok barang</label></div>";
	$('.modal-dialog').removeClass('modal-lg');
	$('.modal-dialog').addClass('modal-sm');
	$('#ModalHeader').html('Konfirmasi');
	$('#ModalContent').html('Apakah anda yakin ingin menghapus transaksi <b>'+$(this).parent().parent().find('td:nth-child(3)').text()+'</b> ?' + Check);
	$('#ModalFooter').html("<button type='button' class='btn btn-primary' id='YesDelete' data-url='"+Link+"' autofocus>Ya, saya yakin</button><button type='button' class='btn btn-default' data-dismiss='modal'>Batal</button>");
	$('#ModalGue').modal('show');
   });
   ```
 * fungsi untuk menghapus data transaksi ketika menekan tombol yes pada pop up notifikasi penghapusan data transaksi
   ```php
   $(document).on('click', '#YesDelete', function(e){
	e.preventDefault();
	$('#ModalGue').modal('hide');
	var reverse_stok = 'no';
	if($('#reverse_stok').prop('checked')){
		var reverse_stok = 'yes';
	}
	$.ajax({
		url: $(this).data('url'),
		type: "POST",
		cache: false,
		data: "reverse_stok="+reverse_stok,
		dataType:'json',
		success: function(data){
			$('#Notifikasi').html(data.pesan);
			$("#Notifikasi").fadeIn('fast').show().delay(3000).fadeOut('fast');
			$('#my-grid').DataTable().ajax.reload( null, false );
		}
	});
   });
   ```
 * fungsi untuk melihat detail transaksi pada setiap data
   ```php
   $(document).on('click', '#LihatDetailTransaksi', function(e){
	e.preventDefault();
	var CaptionHeader = 'Transaksi Nomor Nota ' + $(this).text();
	$('.modal-dialog').removeClass('modal-sm');
	$('.modal-dialog').addClass('modal-lg');
	$('#ModalHeader').html(CaptionHeader);
	$('#ModalContent').load($(this).attr('href'));
	$('#ModalFooter').html("<button type='button' class='btn btn-primary' data-dismiss='modal'>Tutup</button>");
	$('#ModalGue').modal('show');
   });
   ```
   
## Membuat Halaman Data Pelanggan
 * Berikut merupakan halaman utama untuk menambah data pelanggan
   ![history](https://github.com/sudrajadhadi/ETS-PBKK/blob/master/foto/data%20pelanggan.png)
 * cek level user
   ```php
   if($level == 'admin' OR $level == 'kasir' OR $level == 'keuangan')
   {
	$tambahan .= nbs(2)."<a href='".site_url('penjualan/tambah-pelanggan')."' class='btn btn-default' id='TambahPelanggan'><i class='fa fa-plus fa-fw'></i> Tambah</a>";
	$tambahan .= nbs(2)."<span id='Notifikasi' style='display: none;'></span>";
   }
   ```
 * fungsi menampilkan pop-up konfirmasi penghapusan data pelanggan
   ```php
   $(document).on('click', '#HapusPelanggan', function(e){
	e.preventDefault();
	var Link = $(this).attr('href');

	$('.modal-dialog').removeClass('modal-lg');
	$('.modal-dialog').addClass('modal-sm');
	$('#ModalHeader').html('Konfirmasi');
	$('#ModalContent').html('Apakah anda yakin ingin menghapus <br /><b>'+$(this).parent().parent().find('td:nth-child(2)').html()+'</b> ?');
	$('#ModalFooter').html("<button type='button' class='btn btn-primary' id='YesDeletePelanggan' data-url='"+Link+"'>Ya, saya yakin</button><button type='button' class='btn btn-default' data-dismiss='modal'>Batal</button>");
	$('#ModalGue').modal('show');
   });
   ```
 * fungsi untuk menghapus data pelanggan
   ```php
   $(document).on('click', '#YesDeletePelanggan', function(e){
	e.preventDefault();
	$('#ModalGue').modal('hide');

	$.ajax({
		url: $(this).data('url'),
		type: "POST",
		cache: false,
		dataType:'json',
		success: function(data){
			$('#Notifikasi').html(data.pesan);
			$("#Notifikasi").fadeIn('fast').show().delay(3000).fadeOut('fast');
			$('#my-grid').DataTable().ajax.reload( null, false );
		}
	});
   });
   ```
 * fungsi tambah pelanggan
   ```php
   $(document).on('click', '#TambahPelanggan, #EditPelanggan', function(e){
	e.preventDefault();

	$('.modal-dialog').removeClass('modal-sm');
	$('.modal-dialog').removeClass('modal-lg');
	if($(this).attr('id') == 'TambahPelanggan')
	{
		$('#ModalHeader').html('Tambah Pelanggan');
	}
	if($(this).attr('id') == 'EditPelanggan')
	{
		$('#ModalHeader').html('Edit Pelanggan');
	}
	$('#ModalContent').load($(this).attr('href'));
	$('#ModalGue').modal('show');
   });
   ```
## Membuat Halaman Daftar Barang
 * Berikut tampilan halaman utama daftar barang
   ![barang](https://github.com/sudrajadhadi/ETS-PBKK/blob/master/foto/semua%20barang.png)
 * cek level user
   ```php
   if($level == 'admin' OR $level == 'inventory')
   {
	$tambahan .= nbs(2)."<a href='".site_url('barang/tambah')."' class='btn btn-default' id='TambahBarang'><i class='fa fa-plus fa-fw'></i> Tambah Barang</a>";
	$tambahan .= nbs(2)."<span id='Notifikasi' style='display: none;'></span>";
   }
   ?>
   ```
 * fungsi modal hapus barang
   ```php
   $(document).on('click', '#HapusBarang', function(e){
	e.preventDefault();
	var Link = $(this).attr('href');
	$('.modal-dialog').removeClass('modal-lg');
	$('.modal-dialog').addClass('modal-sm');
	$('#ModalHeader').html('Konfirmasi');
	$('#ModalContent').html('Apakah anda yakin ingin menghapus <br /><b>'+$(this).parent().parent().find('td:nth-child(3)').html()+'</b> ?');
	$('#ModalFooter').html("<button type='button' class='btn btn-primary' id='YesDelete' data-url='"+Link+"'>Ya, saya yakin</button><button type='button' class='btn btn-default' data-dismiss='modal'>Batal</button>");
	$('#ModalGue').modal('show');
   });
   ```
 * fungsi hapus barang
   ```php
   $(document).on('click', '#YesDelete', function(e){
	e.preventDefault();
	$('#ModalGue').modal('hide');
	$.ajax({
		url: $(this).data('url'),
		type: "POST",
		cache: false,
		dataType:'json',
		success: function(data){
			$('#Notifikasi').html(data.pesan);
			$("#Notifikasi").fadeIn('fast').show().delay(3000).fadeOut('fast');
			$('#my-grid').DataTable().ajax.reload( null, false );
		}
	});
   });
   ```
 * fungsi modal tambah barang
   ```php
   $(document).on('click', '#TambahBarang, #EditBarang', function(e){
	e.preventDefault();
	if($(this).attr('id') == 'TambahBarang')
	{
		$('.modal-dialog').removeClass('modal-sm');
		$('.modal-dialog').addClass('modal-lg');
		$('#ModalHeader').html('Tambah Barang');
	}
	if($(this).attr('id') == 'EditBarang')
	{
		$('.modal-dialog').removeClass('modal-sm');
		$('.modal-dialog').removeClass('modal-lg');
		$('#ModalHeader').html('Edit Barang');
	}
	$('#ModalContent').load($(this).attr('href'));
	$('#ModalGue').modal('show');
   });
   ```
 * fungsi cek kode barang tersedia atau belum dan menambahkannya pada database
   ```php
   $(document).on('keyup', '.kode_barang', function(){
	$(this).parent().find('span').html("");

	var Kode = $(this).val();
	var Indexnya = $(this).parent().parent().index();
	var Pass = 0;
	$('#TabelTambahBarang tbody tr').each(function(){
		if(Indexnya !== $(this).index())
		{
			var KodeLoop = $(this).find('td:nth-child(2) input').val();
			if(KodeLoop !== '')
			{
				if(KodeLoop == Kode){
					Pass++;
				}
			}
		}
	});

	if(Pass > 0)
	{
		$(this).parent().find('span').html("<font color='red'>Kode sudah ada</font>");
		$('#SimpanTambahBarang').addClass('disabled');
	}
	else
	{
		$(this).parent().find('span').html('');
		$('#SimpanTambahBarang').removeClass('disabled');
			$.ajax({
			url: "<?php echo site_url('barang/ajax-cek-kode'); ?>",
			type: "POST",
			cache: false,
			data: "kodenya="+Kode,
			dataType:'json',
			success: function(json){
				if(json.status == 0){ 
					$('#TabelTambahBarang tbody tr:eq('+Indexnya+') td:nth-child(2)').find('span').html(json.pesan);
					$('#SimpanTambahBarang').addClass('disabled');
				}
				if(json.status == 1){ 
					$('#SimpanTambahBarang').removeClass('disabled');
				}
			}
		});
	}
   });
   ```
## Membuat Halaman Laporan
 * Berikut tampilan utama halaman laporan
   ![laporan](https://github.com/sudrajadhadi/ETS-PBKK/blob/master/foto/halaman%20laporan.png)
 * fungsi untuk set tanggal awal
   ```php
   $('#tanggal_dari').datetimepicker({
	lang:'en',
	timepicker:false,
	format:'Y-m-d',
	closeOnDateSelect:true
   });
 * fungsi set tanggal akhir
   ```php
   $('#tanggal_sampai').datetimepicker({
	lang:'en',
	timepicker:false,
	format:'Y-m-d',
	closeOnDateSelect:true
   });
   ```
 * menampilkan modal laporan dan menampilkan hasilnya
   ```php
   $(document).ready(function(){
	$('#FormLaporan').submit(function(e){
		e.preventDefault();

		var TanggalDari = $('#tanggal_dari').val();
		var TanggalSampai = $('#tanggal_sampai').val();

		if(TanggalDari == '' || TanggalSampai == '')
		{
			$('.modal-dialog').removeClass('modal-lg');
			$('.modal-dialog').addClass('modal-sm');
			$('#ModalHeader').html('Oops !');
			$('#ModalContent').html("Tanggal harus diisi !");
			$('#ModalFooter').html("<button type='button' class='btn btn-primary' data-dismiss='modal' autofocus>Ok, Saya Mengerti</button>");
			$('#ModalGue').modal('show');
		}
		else
		{
			var URL = "<?php echo site_url('laporan/penjualan'); ?>/" + TanggalDari + "/" + TanggalSampai;
			$('#result').load(URL);
		}
	});
   });
   ```
## Membuat Halaman List User
 * Berikut merupakan halaman utama list user
   ![listuser](https://github.com/sudrajadhadi/ETS-PBKK/blob/master/foto/list%20user.png)
 * fungsi pencarian
   ```php
   "oLanguage": {
			"sSearch": "<i class='fa fa-search fa-fw'></i> Pencarian : ",
			"sLengthMenu": "_MENU_ &nbsp;&nbsp;Data Per Halaman <?php echo $tambahan; ?>",
			"sInfo": "Menampilkan _START_ s/d _END_ dari <b>_TOTAL_ data</b>",
			"sInfoFiltered": "(difilter dari _MAX_ total data)", 
			"sZeroRecords": "Pencarian tidak ditemukan", 
			"sEmptyTable": "Data kosong", 
			"sLoadingRecords": "Harap Tunggu...", 
			"oPaginate": {
				"sPrevious": "Prev",
				"sNext": "Next"
			}
		},
   ```
 * fungsi sorting
   ```php
   "aaSorting": [[ 0, "desc" ]],
		"columnDefs": [ 
			{
				"targets": 'no-sort',
				"orderable": false,
			}
	       ],
   ```
 * fungsi modal hapus user
   ```php
   $(document).on('click', '#HapusUser', function(e){
	e.preventDefault();
	var Link = $(this).attr('href');

	$('.modal-dialog').removeClass('modal-lg');
	$('.modal-dialog').addClass('modal-sm');
	$('#ModalHeader').html('Konfirmasi');
	$('#ModalContent').html('Apakah anda yakin ingin menghapus <br /><b>'+$(this).parent().parent().find('td:nth-child(3)').html()+'</b> ?');
	$('#ModalFooter').html("<button type='button' class='btn btn-primary' id='YesDelete' data-url='"+Link+"'>Ya, saya yakin</button><button type='button' class='btn btn-default' data-dismiss='modal'>Batal</button>");
	$('#ModalGue').modal('show');
   });
   ```
 * fungsi menghapus user
   ```php
   $(document).on('click', '#YesDelete', function(e){
	e.preventDefault();
	$('#ModalGue').modal('hide');
	$.ajax({
		url: $(this).data('url'),
		type: "POST",
		cache: false,
		dataType:'json',
		success: function(data){
			$('#Notifikasi').html(data.pesan);
			$("#Notifikasi").fadeIn('fast').show().delay(3000).fadeOut('fast');
			$('#my-grid').DataTable().ajax.reload( null, false );
		}
	});
   });
   ```
 * fungsi tambah user
   ```php
   $(document).on('click', '#TambahUser, #EditUser', function(e){
	e.preventDefault();
	if($(this).attr('id') == 'TambahUser')
	{
		$('.modal-dialog').removeClass('modal-lg');
		$('.modal-dialog').removeClass('modal-sm');
		$('#ModalHeader').html('Tambah User');
	}
	if($(this).attr('id') == 'EditUser')
	{
		$('.modal-dialog').removeClass('modal-lg');
		$('.modal-dialog').removeClass('modal-sm');
		$('#ModalHeader').html('Edit User');
	}
	$('#ModalContent').load($(this).attr('href'));
	$('#ModalGue').modal('show');
   );
   ```
