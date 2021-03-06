<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Fc_result extends MY_Controller {
    public function __construct() {
		parent::__construct();
		$this->login_check();
		$this->load->model('result/Fc_result_model');
	}

	 //彩票结果
	public function index(){
	    $date = $this->input->get('date');
	  	$date=$this->input->get('date');      //查询日期
	  	$date = empty($date)?date('Y-m-d'):$date;
        $qishu = $this->input->get('qishu');
	    $enable = $this->input->get('enable');//彩票类型
        $enable = empty($enable)?2:$enable;//默认
        $page = $this->input->get('page');
        $db_model=array();
        $db_model['tab'] = 'c_auto_'.$enable;
        $db_model['type'] = 2;
        	if($qishu&&$date){
        		if($enable==7){
        			$map['nn']=$qishu;
        		}else{
        			$map['qishu']=$qishu;
        		}
			}
			if(empty($qishu)){
				if($date){
					if($enable==7){
						$map['nd']= array('like','%'.$date.'%');
					}else{
						$map['datetime']= array('like','%'.$date.'%');
					}
				}
			}
        //总数
       	$count = $this->Fc_result_model->mcount($map,$db_model);
        $perNumber = 50;    //显示页数
		$totalPage=ceil($count/$perNumber);
		$page=isset($page)?$page:1;
		if(!empty($page)){
	        $CurrentPage=$page;
	    }else{
	        $CurrentPage=1;
	    }
		$startCount=($CurrentPage-1)*$perNumber;
		$limit=$startCount.",".$perNumber; //取得条数
	    $data = $this->Fc_result_model->get_fc_result($enable,$map,$limit);
        //函数名
        $func_name = 'result_'.$enable;
	    foreach ($data as $key => $val) {
	        $data[$key]['info'] = $this->Fc_result_model->$func_name($val);
	    }
	    $this->add('totalPage', $totalPage);
	    $this->add('date',$date);
	    $this->add('data',$data);
	    $this->add('enable',$enable);
	    $this->add('page',$page);
        $this->display('result/fc_result_'.$enable.'.html');
	}


		//获取页面背景配置
	function get_config(){
		$config=array();
		$config['time']=time();
		$config['color']="#FFFFFF";
		$config['over']="#EBEBEB";
		$config['out']="#ffffff";
		return $config;
	}
}
