<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Website extends MY_Controller {

	/* static 合作状态 */
	private static $COOPERATIVE = array(
						'0' => '暂停中',
						'1' => '合作中'
						
				);
	/* static 结算周期 */
	private static $SETTLE_TYPE = array(
						'1' => '日结',
						'2' => '周结',
						'3' => '月结'
				);
	/* static 验证状态 */
	private static $STATE = array(
						'1' => '已验证',
						'2' => '验证失败'
				);
	
	/* 网站主信息 */
	private $user_id;
	
	/* 网址匹配正则式 */
	private $url_reg = '@^http\\://[a-zA-Z0-9\\-]+(\\.[a-zA-Z0-9\\-]+)+(\\:[0-9]+)?/?$@';
	
	function __construct() {
		parent::__construct();
		
		parent::check_login();
		
		$this->user_id = $this->_UID;
	}
	
	/* 默认显示 站点列表 */
	public function index()
	{
		$this->load->model("model_website");

		/* 显示新增或修改等操作完成后 提示消息 */
		if ( ($msg = $this->session->flashdata('msg')) ) {
			$data['msg'] = $this->session->flashdata('msg');
		}

		/* 站点列表详情 */
		$websites = $this->model_website->get_websites_detailinfo_by_userid($this->user_id);
		if ($websites) {
			foreach ($websites as &$web) {
				$web['state_str'] = Website::$STATE[$web['state']] . ', ' . Website::$COOPERATIVE[$web['is_cooperative']];
				
				$web['settle_type_str'] = Website::$SETTLE_TYPE[$web['settle_type']];
				
				$web['types_str'] = '';
				if ( ! empty($web['types'])) {
					$type_ids = explode(',', $web['types']);
					
					/* 配置文件 取得 网站的类别信息 */
					$type_names = & $this->config->item('website_type');
					
					foreach ($type_ids as $index) {
						if ( ! empty($web['types_str'])) {
							$web['types_str'] .= ', ';
						}
						$web['types_str'] .= isset($type_names[$index]) ? $type_names[$index] : '';
					}
				}
				
				$web['add_time_str'] = $web['add_time'] ? date("Y年m月d日", $web['add_time']) : "";
			}
			unset($web);
		}
		
		$data['websites'] = $websites;
		
		$this->_set_data($data);
		$this->load->view("website_index", $this->_DATA);
	}
	
	/* 判断验证码-ajax */
	public function validate() {
		$weburl = $this->_format_weburl($this->input->post('weburl'));
		
		/* json 返回数组 */
		$result = array(
				'rcode'	=> FALSE,
				'rdata'	=> array()
		);
		
		/* 网站地址非空，且 url 格式正确 且 网址唯一 */
		if ( ! empty($weburl) && preg_match($this->url_reg, $weburl)) {
			$this->load->model("model_website");
			
			if ( $this->model_website->exist_website_by_domain($weburl)) {
			
				$result['rdata'][] = "此站点已被注册过，请重新选择";
			} else {
				$validate_str = $this->_get_secret_key($weburl);
			
				if ($this->input->post('__validate') == '1') {
					/* 验证 */
					if ($this->_confirm_secret_key($weburl, $validate_str)) {
						$result['rcode'] = TRUE;
						$result['rdata'][] = "已经验证成功！";
					} else {
						$result['rdata'][] = "无法 获取文件内容，或 验证失败！";
					}
				} else {
					/* 返回验证码 */
					$result['rcode'] = TRUE;
					$result['rdata'][] = $this->config->item('secret_key_filename');
					$result['rdata'][] = $validate_str;
				}
			}
		} else {
			/* 网站地址非法的 */
			$result['rdata'][] = "网站地址非法的！";
		}
		
		exit(json_encode($result));
	}
	
	/* 验证文件下载 */
	public function validator_down() {
		$weburl = $this->_format_weburl($this->input->post('weburl'));
		
		$validate_str = $this->_get_secret_key($weburl);
		$validate_file = $this->config->item('secret_key_filename');
		
		header('Content-Type: application/octet-stream; charset=utf-8');
		header('Content-Disposition: attachment; filename="' . $validate_file . '"');
		
		exit($validate_str);
	}
	
	/* 获取网址title-ajax */
	public function parse_title() {
		$weburl = $this->_format_weburl($this->input->post('weburl'));
		
		/* json 返回数组 */
		$result = array(
				'rcode'	=> FALSE,
				'rdata'	=> array()
		);
		
		if ( ! empty($weburl) ) {
			/* 加载 treebuilder 类解析html */
			$this->load->library('treebuilder');
			
			/* 解析 title */
			$this->treebuilder->parse_file($weburl);
			$e = $this->treebuilder->look_down('title');
			$title = $e->as_text();
		
			if ( ! empty($title)) {
				$result['rcode'] = TRUE;
				$result['rdata'][] = $title;
			}
		}	
		
		exit(json_encode($result));
	}
	
	/* 新添网站站点  */
	public function newone() {
		$this->load->library('form_validation');
		$this->load->model("model_website");
		
		$data = array(
				'type_names' => get_config_item('website_type'),
		);
		
		/* form 提交 */
		if ($this->input->post('__submit') == '1') {
			/* 验证 规则
			 * 网站域名：非空，格式正确，唯一
			 * 网站名称：非空
			 * 网站类别：最多选择两项
			 * 网站安全码：正确
			 */
			$error_str = '';
			
			$this->form_validation->set_rules('domain', '网站域名', 'trim|required|regex_match[' . $this->url_reg . ']|callback_domain_unique');
			$this->form_validation->set_rules('website_name', '网站名称', 'trim|required');
			$this->form_validation->set_rules('website_types', '网站类别', 'callback_types_count');
			$this->form_validation->set_message('domain_unique', '<p>此站点已被注册过，请重新选择</p>');
			$this->form_validation->set_message('types_count', '<p>网站类别最多选择两项</p>');
			
			/* 安全码 验证 */
			$weburl = $this->_format_weburl($this->input->post('domain'));
			$validate_str = $this->_get_secret_key($weburl);
			$is_secret_key_ok = $this->_confirm_secret_key($weburl, $validate_str);
			if ( ! $is_secret_key_ok) {
				$error_str = "<p>网址-无法 获取文件内容，或 验证失败</p>";
			}
			
			if ($this->form_validation->run() == FALSE || $error_str) {
				$data['msg'] = array('error', validation_errors() . $error_str);
				
				$this->_set_data($data);
				$this->load->view("website_newone", $this->_DATA);
			} else {
				$insert_fields = array(
							'website_name'	=> trim($this->input->post('website_name')),
							'domain'	=> $weburl,
							'description'	=> trim($this->input->post('description', TRUE)),
							'state'		=> '1',
							'add_time'	=> time()
				);
				$insert_fields['domain'] = rtrim($insert_fields['domain'], '/') . '/';			
				/* icp */
				$icp = trim($this->input->post('icp'));
				if ( ! empty($icp) ) {
					$insert_fields['icp'] = $icp;
				}
				
				/* 数据库 insert 操作 */
				if ( ($website_id = $this->model_website->insert($this->user_id, $insert_fields) ) ) {
					$type_ids = $this->input->post('website_types');
					
					if ( is_array($type_ids) && ! empty($type_ids)) {
						/* 插入 网址类别 */
						$this->model_website->insert_types($website_id, $type_ids);
					}
					
					redirect('website');
				} else {
					/* 插入 失败 */
					show_error("Oop!");
				}
			}
		} else {
			/* 首次请求 显示输入页面 */

			$this->_set_data($data);
			$this->load->view("website_newone", $this->_DATA);
		}
	}
	
	/* 更新网站站点 */
	public function modify($id = 0) {
		if (intval($id) == 0) { redirect('website'); }
		
		$this->load->model("model_website");
		$this->load->library('form_validation');
		
		/* 网站站点 信息 */
		$website = $this->model_website->get_website_by_id($this->user_id, $id);
		if ( empty($website)) { redirect('website'); }
		
		$data = array(
				'id'		=> $id,
				'website'	=> &$website,
				'website_types'	=> explode(',', $website['types']),
				'type_names'	=> $this->config->item('website_type')
		);
		
		/* 确定 二次提交 */
		if ($this->input->post('__submit') == '1') {
			/* 验证 规则
			 * 网站名称：非空
			 * 网站类别：最多选择两项
			 */
			$this->form_validation->set_rules('website_name', '网站名称', 'trim|required');
			$this->form_validation->set_rules('website_types', '网站类别', 'callback_types_count');
			$this->form_validation->set_message('types_count', '<p>网站类别最多选择两项</p>');
						
			if ($this->form_validation->run() == FALSE) {
				$data['website_types'] = $this->input->post('website_types');
				
				$data['msg'] = array('error', validation_errors());
				
				$this->_set_data($data);
				$this->load->view("website_modify", $this->_DATA);
			} else {
				/* 更新前 网站站点 信息 */
				$old_website = $website;
				
				if ($old_website) {
					$update_fields = array(
								'website_name'	=> trim($this->input->post('website_name')),
								'description'	=> trim($this->input->post('description'), TRUE)
					);
					
					/* 是否更新 网站站点ICP */
					if ($old_website['icp'] == '' && trim($this->input->post('icp')) != '') {
						$update_fields['icp'] = trim($this->input->post('icp'));
					}
					
					/* 数据库 update 操作 */
					if ($this->model_website->update($this->user_id, $id, $update_fields)) {
						$type_ids = $this->input->post('website_types');
											
						if ( is_array($type_ids) && ! empty($type_ids)) {
							/* 修改 网址类别 */
							$this->model_website->delete_types($id, $type_ids)
								&& $this->model_website->insert_types($id, $type_ids);
						}
						
						redirect('website');
					} else {
						/* 更新 失败 */
						show_error("Oop!");
					}
				} else {
					/* 无网站站点信息 */
					show_error("Oop!");
				}
			}
		} else {
			/* 首次请求 显示更新页面 */
			$this->_set_data($data);
			$this->load->view("website_modify", $this->_DATA);
		}
	}
	
	/* 获取网站广告代码 */
	public function sitecode($id = 0) {
		$id = intval($id) ? intval($id) : intval($this->input->get('id'));
		
		$this->load->model("model_website");
		
		/* 二次提交-修改 站点结算周期 */
		/*
		if ($this->input->post('__submit') == '1') {
			$settle_type = intval($this->input->post('settle_type'));
			if ( ! in_array($settle_type, array_keys(Website::$SETTLE_TYPE)) || $settle_type == 0) {
				$settle_type = '2';
			}
			
			$fields = array('settle_type' => $settle_type);
			$this->model_website->update($this->user_id, $id, $fields);
			
			redirect("website/sitecode/{$id}");
		}
		*/
		
		$data['id']		= $id;
		$data['settles']	= Website::$SETTLE_TYPE;
		
		/* 可用-状态正常-网站类表 */
		$websites = $this->model_website->get_websites_active_by_userid($this->user_id);
		array_unshift($websites, array (
						'id' 		=> '0', 
						'domain' 	=> '--选择站点--', 
						'settle_type' 	=> '0')
		) ;
		
		$data['websites']	= $websites;
		
		if ( count($websites) > 1) {

			/* 网站是否-状态正常 */
			$website_active = null;
			for ($i = 0, $length = count($websites); $i < $length; $i++) {
				if ($websites[$i]['id'] == $id && $id != 0) {
					$website_active = &$websites[$i];
					break;
				}
			}
			if ( $website_active) {
				$data['website']	= &$website_active;
			}
		}
		
		$this->_set_data($data);
		$this->load->view("website_sitecode", $this->_DATA);
	}
	
	/* 验证
	 * 网站域名 唯一
	 */
	public function domain_unique($domain) {
		$domain = $this->_format_weburl($domain);
		$domain_exist = $this->model_website->exist_website_by_domain($domain);
		if ( $domain_exist) {
			return false;
		}
		return true;
	}
	
	/* 验证
	 * 网站类别 最对选择两项
	 */
	public function types_count($types) {
		if (count($types) > 2) {
			return false;
		}
		return true;
	}
	
	/* 网址格式 */
	private function _format_weburl($weburl) {
		$weburl = trim($weburl);
		$weburl = rtrim($weburl, '/') . '/';
		return $weburl;
	}
	
	/* 生成 站点安全码 
	 * 规则： str_encrypt, 求md5值 
	 */
	private function _get_secret_key($weburl) {
		return  md5(str_encrypt($weburl));
	}
	
	/* 验证 安全码
	 * 取得 站点文件 $config['secret_key_filename'] 内容，和 安全码比较
	 */
	private function _confirm_secret_key($weburl, $secret_key) {
		$file_uri = $weburl . $this->config->item('secret_key_filename');
		
		/* 请求安全码文件 内容 */
		$content = @file_get_contents($file_uri); 
		
		if (trim($content) == $secret_key) {
			return true;
		}
		return false;
	}
}
