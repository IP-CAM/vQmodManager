<?php
class ControllerModuleVqmodManager extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('module/vqmodmanager');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('extension/module');

		$data['heading_title']      =   $this->language->get('heading_title');

		$data['text_allmodules']    =   $this->language->get('text_allmodules');
		$data['text_vqdetails']     =   $this->language->get('text_vqdetails');
        $data['text_active']        =   $this->language->get('text_active');
        $data['text_inactive']      =   $this->language->get('text_inactive');
        $data['text_file']          =   $this->language->get('text_file');
        $data['text_status']        =   $this->language->get('text_status');
        $data['text_action']        =   $this->language->get('text_action');
        $data['text_purge']         =   $this->language->get('text_purge');
        $data['text_vqmod']         =   $this->language->get('text_vqmod');
        $data['text_vqmodon']       =   $this->language->get('text_vqmodon');
        $data['text_vqmodoff']      =   $this->language->get('text_vqmodoff');

		$data['text_enabled']       =   $this->language->get('text_enabled');
		$data['text_disabled']      =   $this->language->get('text_disabled');

		$data['entry_name']         =   $this->language->get('entry_name');
		$data['entry_title']        =   $this->language->get('entry_title');
		$data['entry_description']  =   $this->language->get('entry_description');
		$data['entry_status']       =   $this->language->get('entry_status');

		$data['button_save']        =   $this->language->get('button_save');
		$data['button_cancel']      =   $this->language->get('button_cancel');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['name'])) {
			$data['error_name'] = $this->error['name'];
		} else {
			$data['error_name'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_module'),
			'href' => $this->url->link('extension/module', 'token=' . $this->session->data['token'], true)
		);

		if (!isset($this->request->get['module_id'])) {
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('module/vqmodmanager', 'token=' . $this->session->data['token'], true)
			);
		} else {
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('module/vqmodmanager', 'token=' . $this->session->data['token'] . '&module_id=' . $this->request->get['module_id'], true)
			);
		}

		if (!isset($this->request->get['module_id'])) {
			$data['action'] = $this->url->link('module/vqmodmanager', 'token=' . $this->session->data['token'], true);
		} else {
			$data['action'] = $this->url->link('module/vqmodmanager', 'token=' . $this->session->data['token'] . '&module_id=' . $this->request->get['module_id'], true);
		}

		$data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], true);

		if (isset($this->request->get['module_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$module_info = $this->model_extension_module->getModule($this->request->get['module_id']);
		}

		if (isset($this->request->post['name'])) {
			$data['name'] = $this->request->post['name'];
		} elseif (!empty($module_info)) {
			$data['name'] = $module_info['name'];
		} else {
			$data['name'] = '';
		}

		if (isset($this->request->post['module_description'])) {
			$data['module_description'] = $this->request->post['module_description'];
		} elseif (!empty($module_info)) {
			$data['module_description'] = $module_info['module_description'];
		} else {
			$data['module_description'] = array();
		}

		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();

		if (isset($this->request->post['status'])) {
			$data['status'] = $this->request->post['status'];
		} elseif (!empty($module_info)) {
			$data['status'] = $module_info['status'];
		} else {
			$data['status'] = '';
		}

		/* Vqmod Logic Start */

		$data['ed_controller_url'] = $this->config->get('config_url').'admin/index.php?route=module/vqmodmanager/enableDisable&token='.$this->session->data['token'];

		if (file_exists(DIR_APPLICATION . '../vqmod/xml/vqmod_opencart.xml')) {
			$data['vqmods']['VQmod'] = 1;
		} else {
			$data['vqmods']['VQmod'] = 0;
		}

		if (file_exists(DIR_APPLICATION . '../vqmod/checked.cache')) {
			$data['vqmods']['Checked.cache'] = 1;
		} else {
			$data['vqmods']['Checked.cache'] = 0;
		}		

		if (file_exists(DIR_APPLICATION . '../vqmod/mods.cache')) {
			$data['vqmods']['Mods.cache'] = 1;
		} else {
			$data['vqmods']['Mods.cache'] = 0;
		}

		$vqmod_dir = DIR_APPLICATION.'../vqmod/';
		$vqmod_xml_dir = $vqmod_dir.'xml/';
		$data['vqdir'] = scandir($vqmod_dir);
		$vqxml = array_diff(scandir($vqmod_xml_dir,1), array('..', '.'));

		$data['vxml'] = [];
		foreach ($vqxml as $vqxml_value) {
			$xml=simplexml_load_file($vqmod_xml_dir.$vqxml_value);

			$path_parts = pathinfo($vqmod_xml_dir.$vqxml_value);
			
			$vxml_temp['id'] = (string) $xml->id; 
			$vxml_temp['author'] = (string) $xml->author;
			$vxml_temp['file'] = $vqxml_value;
			if($path_parts['extension'] == 'xml') {
				$vxml_temp['extension'] = 1;
			} else {
				$vxml_temp['extension'] = 0;
			}
			array_push($data['vxml'], $vxml_temp);
		}
		/* Vqmod logic End */

		$this->document->addStyle('https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css');
		$this->document->addScript('https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js');

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('module/vqmodmanager', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'module/vqmodmanager')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 64)) {
			$this->error['name'] = $this->language->get('error_name');
		}

		return !$this->error;
	}
	
	public function enableDisable() {

		$vqmod_dir = DIR_APPLICATION.'../vqmod/';
		$vqmod_xml_dir = $vqmod_dir.'xml/';
		$file = $this->request->post['fileName'];
		$path_parts = pathinfo($vqmod_xml_dir.$file);
		$message = '';

		if($path_parts['extension'] == 'xml') {
			$newname = basename($path_parts['filename']).".disabled";
			rename($vqmod_xml_dir.$file, $vqmod_xml_dir.$newname);
			$message = 'Disabled';
		} else {
			$newname = basename($path_parts['filename']).".xml";
			rename($vqmod_xml_dir.$file, $vqmod_xml_dir.$newname);
			$message = 'Enabled';
		}

        $this->session->data['success'] = $message;

        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];

            unset($this->session->data['success']);
        } else {
            $data['success'] = '';
        }
	}
}