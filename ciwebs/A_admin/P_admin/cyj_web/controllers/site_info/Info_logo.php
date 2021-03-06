<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Info_logo extends MY_Controller {

	public function __construct() {
	    parent::__construct();
      $this->login_check();
		  $this->load->model('site_info/Info_logo_model');
	}

	public function logo_index() {
            $index_id = $this->input->get('index_id');
            $index_id = empty($index_id)?'a':$index_id;
            $map = array();
            $map['table'] = 'info_logo_edit_view';
            $map['where']['site_id'] = $_SESSION['site_id'];
            $map['where']['index_id'] = $index_id;
            $logo = $this->Info_logo_model->rget($map);
            $info_logomap = array();
            $info_logomap['table'] = 'info_logo_edit';
            $info_logomap['where']['site_id'] = $_SESSION['site_id'];
            $info_logomap['where']['index_id'] = $index_id;
            $info_logo = $this->Info_logo_model->rget($info_logomap);
            $pass = 0;
            foreach($info_logo as $k => $v){
                if($v['type']==30){
                   $pass+=1;
                }
            }
            //如果没有type为30的数据没有就初始化一条数据
            if($pass==0){
                $add_flash['site_id'] = $_SESSION['site_id'];
                $add_flash['index_id'] = $index_id;
                $add_flash['type'] = 30;
                $add_flash['title'] = 'wapLOGO';
                if($this->Info_logo_model->create_wap_date($add_flash)){
                    $this->logo_index();
                    die;
                }else{
                    show_error('初始化数据失败，请重新操作!<a href="javascript:history.go(-1)">返回</a>', 200, '提示');
                }
            }

            $map['table'] = 'web_config';
		  $img_url = $this->Info_logo_model->rfind($map);
	  	foreach ($logo as $k => $val) {
			if ($val['state'] == 2) {
				$logo[$k]['state_z'] = '<font style="color:#CB102C;;">停用</font>';
			}else{
         $logo[$k]['state_z'] = '<font style="color:#07B817;">启用</font>';
			}
			if ($val['type'] == 11) {
				$logo[$k]['width'] = $val['site_logo_w'];
				$logo[$k]['height'] = $val['site_logo_h'];
			}else{
                $logo[$k]['width'] = $val['mem_logo_w'];
				$logo[$k]['height'] = $val['mem_logo_h'];
			}
			if (strstr($val['logo_url'],'swf')) {
			   $logo[$k]['itype'] = 2;
			}else{
				$logo[$k]['itype'] = 1;
			}
                        if($val['type']==11){
                            $logo[$k]['logo_url'] = 'http://'.$img_url['conf_www'].ltrim($val['logo_url'],'.');
                            //$logo[$k]['name']  ='PC_端LOGO';
                        }elseif($val['type']==30){
                            $logo[$k]['logo_url'] = 'http://'.$img_url['wap_url'].ltrim($val['logo_url'],'.');
                            //$logo[$k]['name']  ='WAP端LOGO';
                        }

	  	}

      //多站点判断
      if (!empty($_SESSION['index_id'])) {
            $this->add('sites_str','站点：'.str_replace('全部', '请选择站点',$this->Info_logo_model->select_sites()));
            $this->add('index_id',$index_id);
      }

		  $this->add("logo",$logo);
		  $this->add("img_url",$img_url['conf_www']);
		  $this->display('site_info/logo_index.html');
	}

	public function logo_title_do(){
		$id = intval($this->input->post("id"));
        $arr['title'] = $this->input->post('title');
        // $arr['start_date'] = $this->input->post('start_date');
        // $arr['end_date'] = $this->input->post('end_date');
        $arr['state'] = $this->input->post('state');
        if (empty($id) || empty($arr['title'])) {
            show_error('参数错误!<a href="javascript:history.go(-1)">返回</a>', 200, '提示');
        }
        $map = array();
        $map['table'] = 'info_logo_edit';
        $map['where']['id'] = $id;
        $map['where']['site_id'] = $_SESSION['site_id'];
        $lid = $this->Info_logo_model->rupdate($map,$arr);
        if ($lid) {
        	$drr['log_info'] = '成功修改站内LOGO,ID:'.$lid;
            $this->Info_logo_model->Syslog($drr);
            show_error('修改成功!<a href="javascript:history.go(-1)">返回</a>', 200, '提示');
        }else{
        	$drr['log_info'] = '修改站内LOGO失败,ID:'.$lid;
            $this->Info_logo_model->Syslog($drr);
            show_error('修改成功!<a href="javascript:history.go(-1)">返回</a>', 200, '提示');
        }
	}

	//图片上传
	public function up_logo_do(){
		$id = intval($this->input->post('id'));
		$arr['type'] = intval($this->input->post('type'));
        $index_id = $this->input->get('index_id');
        $index_id = empty($index_id)?'a':$index_id;
		if (empty($id) || empty($arr['type'])) {
            show_error('参数错误!<a href="javascript:history.go(-1)">返回</a>', 200, '提示');
        }

        if($arr['type']==30){
            $config['upload_path'] = UPIMG_URL.$index_id.'_wap/public/cdn/';
            if(!is_dir($config['upload_path'])) {
                mkdir($config['upload_path'],0777,true);
            }
        }elseif($arr['type']==11){
            $config['upload_path'] = UPIMG_URL.$index_id.'/site_info/img/';
            if(!is_dir($config['upload_path'])) {
                mkdir($config['upload_path'],0777,true);
            }
        }
		$config['allowed_types'] = 'gif|jpeg|jpg|png|swf';//文件类型
		$config['max_size'] = '1000000';

        if($_FILES['flash']['size']>=$config['max_size']){
           $msg = '您上传的文件有'.$_FILES['flash']['size']/1024 .'KB，不要大于'.$config['max_size']/1024 . 'KB!';
           show_error('上传失败!'.$msg.'<a href="javascript:history.go(-1)">返回</a>', 200, '提示');
        }


		$config['file_name'] = 'logo'.date('YmdHis');
        $config['file_name'] = 'lg'.date('YmdHis');
		$this->load->library('upload',$config);
        if($this->upload->do_upload('logo')){
           $upload_data = $this->upload->data();
            if($arr['type']==30){
                $arr['img_url'] = '/public/cdn/'.$upload_data['file_name'];
            }elseif($arr['type']==11){
                 $arr['img_url'] = '/site_info/img/'.$upload_data['file_name'];
            }
           $arr['site_id'] = $_SESSION['site_id'];
           $arr['add_date'] = date('Y-m-d H:i:s');
           if ($this->Info_logo_model->uplogo($arr,$id)) {
           	  $drr['log_info'] = '上传LOGO成功,ID:'.$id;
              $this->Info_logo_model->Syslog($drr);
	          show_error('上传成功!<a href="javascript:history.go(-1)">返回</a>', 200, '提示');
	       }else{
              //删除上传的文件
              $drr['log_info'] = '上传LOGO失败,ID:'.$id;
              $this->Info_logo_model->Syslog($drr);
			  //$result = @unlink($arr['img_url']);
	          show_error('上传失败!<a href="javascript:history.go(-1)">返回</a>', 200, '提示');
	       }
         }else{
         	$drr['log_info'] = '上传LOGO失败,ID:'.$id;
            $this->Info_logo_model->Syslog($drr);
            $error = array("error" => $this->upload->display_errors());
            show_error('上传错误!<a href="javascript:history.go(-1)">返回</a>', 200, '提示');
         }
	}

	public function logo_case(){
            $index_id = $this->input->get('index_id');
            $index_id = empty($index_id)?'a':$index_id;
            $id = intval($this->input->get("id"));
        if (empty($id)) {
            show_error('参数错误!<a href="javascript:history.go(-1)">返回</a>', 200, '提示');
        }
        $map = array();
        $map['table'] = 'info_logo_edit_view';
        $map['select'] = 'title,type,site_id';
        $map['where']['id'] = $id;
        $map['where']['site_id'] = $_SESSION['site_id'];
        $arr = $this->Info_logo_model->rfind($map);
        $arr['eid'] = $id;//文案对应详情id
        $arr['add_date'] = date('Y-m-d H:i:s');//提交时间
        $arr['index_id'] = $index_id;
        if ($this->Info_logo_model->logo_case($arr,$id)) {
        	$drr['log_info'] = '存储LOGO案件成功,ID:'.$id;
              $this->Info_logo_model->Syslog($drr);
            show_error('存储案件成功!<a href="javascript:history.go(-1)">返回</a>', 200, '提示');
        }else{
        	$drr['log_info'] = '存储LOGO案件失败,ID:'.$id;
            $this->Info_logo_model->Syslog($drr);
            show_error('存储案件失败!<a href="javascript:history.go(-1)">返回</a>', 200, '提示');
        }
	}
}