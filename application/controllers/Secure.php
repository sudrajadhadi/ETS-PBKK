<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * ------------------------------------------------------------------------
 * CLASS NAME : Secure
 * ------------------------------------------------------------------------
 *
 * @author     Muhammad Akbar <muslim.politekniktelkom@gmail.com>
 * @copyright  2016
 * @license    http://aplikasiphp.net
 *
 */

class Secure extends MY_Controller 
{
	public function index()
	{
		if($this->input->is_ajax_request())
		{
			$this->load->library('form_validation');
			$this->form_validation->set_rules('username','Username','trim|required|min_length[3]|max_length[40]');
			$this->form_validation->set_rules('password','Password','trim|required|min_length[3]|max_length[40]');
			$this->form_validation->set_message('required','%s harus diisi !');
			
			if($this->form_validation->run() == TRUE)
			{
				$username 	= $this->input->post('username');
				$password	= $this->input->post('password');

				$this->load->model('m_user');
				$validasi_login = $this->m_user->validasi_login($username, $password);

				if($validasi_login->num_rows() > 0)
				{
					$data_user = $validasi_login->row();

					$session = array(
						'ap_id_user' => $data_user->id_user,
						'ap_password' => $data_user->password,
						'ap_nama' => $data_user->nama,
						'ap_level' => $data_user->level,
						'ap_level_caption' => $data_user->level_caption 
					);
					$this->session->set_userdata($session);	

					$URL_home = site_url('penjualan');
					if($data_user->level == 'inventory')
					{
						$URL_home = site_url('barang');
					}
					if($data_user->level == 'keuangan')
					{
						$URL_home = site_url('penjualan/history');
					}

					$json['status']		= 1;
					$json['url_home'] 	= $URL_home;
					echo json_encode($json);
				}
				else
				{
					$this->query_error("Login Gagal, Cek Kombinasi Username & Password !");
				}
			}
			else
			{
				$this->input_error();
			}
		}
		else
		{
			$this->load->view('secure/login_page');
		}
	}


	function logout()
	{
		$this->session->unset_userdata('ap_id_user');
		$this->session->unset_userdata('ap_password');
		$this->session->unset_userdata('ap_nama');
		$this->session->unset_userdata('ap_level');
		$this->session->unset_userdata('ap_level_caption');
		redirect();
	}
}
