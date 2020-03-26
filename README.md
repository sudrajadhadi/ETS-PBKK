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
  sytax tersebut merupakan perintah untuk mengirimkan username pengguna yang nantinya akan divalidasi
  ```php
  echo form_password(array(
  'name' => 'password', 
  'class' => 'form-control', 
  'id' => 'InputPassword'
  ));
  ```
  syntax tersebut merupakan perintah untuk mengirimkan password pengguna yang nantinya akan divalidasi

