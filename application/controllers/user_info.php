<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// 用户个人信息

class User_Info extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
		parent::check_login();
	}
	
	public function index()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('truename', '您的真实姓名', 'trim|max_length[50]');
		$this->form_validation->set_rules('identitycard', '您的身份证号', 'trim|max_length[18]');
		$this->form_validation->set_rules('qq', '您的QQ号码', 'trim|max_length[20]');
		$this->form_validation->set_rules('mobilephone', '您的手机号码', 'trim|max_length[11]');
		$this->form_validation->set_rules('bankcode', '付款银行', 'trim|max_length[20]');
		$this->form_validation->set_rules('bankname', '开户银行', 'trim|max_length[200]');
		$this->form_validation->set_rules('bankcard', '银行卡号', 'trim|max_length[20]');
		
		$email = $this->session->userdata('email');
		$show_error = '';
		$this->load->model('model_user','user');
		$data = $this->user->get_user_info_by_id($this->_UID);
		
		if(empty($data))
		{
			$data = array(
				'true_name'=>'',
				'qq'=>'',
				'mobile_phone'=>'',
				'identity_card'=>'',
				'obverse_identity_thumb'=>'',
				'reverse_identity_thumb'=>'',
				'bank_code'=>'',
				'bank_name'=>'',
				'bank_card'=>''
			);
		}
		$data['email'] = $email;
		$data['bank_list'] = get_config_item('bank_list');
		
		if( isset( $_FILES['obverse_photo'] ) && isset( $_FILES['reverse_photo'] ) )
		{
			$obverse_identity_thumb = $_FILES['obverse_photo']['name'];
			$reverse_identity_thumb = $_FILES['reverse_photo']['name'];
		}
		if( !empty( $obverse_identity_thumb ) && !empty( $reverse_identity_thumb ) )
		{
			$config['upload_path'] = './attachment/verify';
			$config['allowed_types'] = 'gif|jpg|png|bmp';
			$config['max_size'] = '150';
			$config['max_width'] = '0';
			$config['max_height'] = '0';
			$config['file_name'] = random_key();
			$this->load->library('upload', $config);
			if ( !$this->upload->do_upload("obverse_photo") )
			{
				$error = $this->upload->data();
				if( $error['is_image']==0 )
				{
					$show_error = "<p>上传的正面文件类型不正确</p>";
				}
				elseif( $this->upload->display_errors() )
				{
					$show_error = "<p>上传的正面文件请小于150KB</p>";
				}
				
			}
			else
			{
				$obverse_photo = $this->upload->data();
				$data['obverse_identity_thumb'] = $this->db->escape($obverse_photo['file_name']);
			}
			
			if ( !$this->upload->do_upload("reverse_photo") )
			{
				$error = $this->upload->data();
				if( $error['is_image']==0 )
				{
					$show_error = $show_error."<p>上传的反面文件类型不正确</p>";
				}
				elseif( $this->upload->display_errors() )
				{
					$show_error = "<p>上传的反面文件请小于150KB</p>";
				}
			}
			else
			{
				$reverse_photo = $this->upload->data();
				$data['reverse_identity_thumb'] = $this->db->escape($reverse_photo['file_name']);
			}
		}
		
		if ( $this->form_validation->run() == FALSE || !empty($show_error) )
		{
			if( !empty($show_error) )
			{
				$data['obverse_identity_thumb'] = '';
				$data['reverse_identity_thumb'] = '';
			}
			$data['msg'] = validation_errors().$show_error;
			$this->_set_data($data);
			$this->load->view('user_info',$this->_DATA);
		}
		else
		{
			$true_name = trim( $this->input->post('truename') );
			$qq =  trim( $this->input->post('qq') );
			$mobile_phone = trim( $this->input->post('mobilephone') );
			$identity_card = trim( $this->input->post('identitycard') );		
			$bank_code = trim( $this->input->post('bankcode') );
			$bank_name = trim( $this->input->post('bankname') );
			$bank_card = trim( $this->input->post('bankcard') );
			
			$change_data = array(
				'qq'=>$this->db->escape($qq),
				'mobile_phone'=>$this->db->escape($mobile_phone)
			);
			if( !empty( $obverse_identity_thumb ) )
			{
				$change_data['obverse_identity_thumb'] = $data['obverse_identity_thumb']; 
			}
			
			if( !empty( $reverse_identity_thumb ) )
			{
				$change_data['reverse_identity_thumb'] = $data['reverse_identity_thumb']; 
			}
			
			if( !empty( $true_name ) )
			{
				$change_data['true_name'] = $this->db->escape($true_name);
			}
			if( !empty($identity_card) )
			{
				$change_data['identity_card'] = $this->db->escape($identity_card);
			}
			if( !empty($bank_code) )
			{
				$change_data['bank_code'] = $this->db->escape($bank_code);
			}
			if( !empty($bank_name) )
			{
				$change_data['bank_name'] = $this->db->escape($bank_name);
			}
			if( !empty($bank_card) )
			{
				$change_data['bank_card'] = $this->db->escape($bank_card);
			}

			$this->load->model('model_user','user');
			
			$row = $this->user->check_user_data($this->_UID);
			if( $row['total']==1 )
			{
				$id = array(
					'user_id'=>$this->_UID
				);
				$result = $this->user->update_user_info($change_data,$id);
			}
			else
			{
				$change_data['user_id']=$this->_UID;
				$result = $this->user->insert_user_info($change_data);
			}
			redirect( site_url("user_info") );
		}
		
	}

	
	public function user_pwd()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('pwd', '当前密码', 'trim|required|min_length[6]|max_length[50]');
		$this->form_validation->set_rules('new_pwd', '新密码', 'trim|required|min_length[6]|max_length[50]');
		$this->form_validation->set_rules('ck_pwd', '确认密码', 'trim|required|min_length[6]|max_length[50]|matches[new_pwd]');
		
		if ( $this->form_validation->run() == FALSE )
		{
			$show_msg['msg'] = validation_errors();
			$this->_set_data($show_msg);
			$this->load->view("user_pwd", $this->_DATA);
		}
		else
		{
			$pwd = trim( $this->input->post('pwd') );
			$new_pwd = trim( $this->input->post('new_pwd') );
			$ck_pwd = trim ($this->input->post('ck_pwd') );
			$this->load->model('model_user','user');
			
			$row = $this->user->check_userpwd(encrypt($pwd), $this->_UID);
			if( $row['total']==1 )
			{
				$data=array(
					'password'=>$this->db->escape( encrypt($new_pwd) )
				);
				$id=array(
					'user_id'=>$this->_UID
				);
				$result = $this->user->update_user_pwd($data, $id);
				$show_msg['msg'] = "修改成功！";
				$this->_set_data($show_msg);
				$this->load->view('user_pwd', $this->_DATA);
			}
			else
			{
				$show_msg['msg'] = "当前密码错误！";
				$this->_set_data($show_msg);
				$this->load->view('user_pwd', $this->_DATA);
			}
			
		}
		
	}		
}