<?php

$action = $_GET['action'];
$actions = array('tk', 'up', 'fd');
//判断是否正确的请求
if(! in_array($action, $actions)){
	//错误
	exit;
}

$upload = new upload();
$upload->$action();

/**
 * 上传类
 * @author ZhouHr   <2014.04.30>
 */
class upload
{
	private $_tokenPath = 'uploads/tokens/';            //令牌保存目录
	private $_filePath = 'uploads/files/';              //上传文件保存目录

	/**
	 * 获取令牌
	 */
	public function tk(){
	
		$file['name'] = $_GET['name'];                  //上传文件名称
		$file['size'] = $_GET['size'];                  //上传文件总大小
		$file['token'] = md5(json_encode($file['name'] . $file['size']));
		//判断是否存在该令牌信息
		if(! file_exists($this->_tokenPath . $file['token'] . '.token')){
		
			$file['up_size'] = 0;                       //已上传文件大小
			$pathInfo = pathinfo($file['name']);
			$path = $this->_filePath . date('Ymd') .'/';
			//生成文件保存子目录
			if(! is_dir($path)){
				mkdir($path, 0700);
			}
			//上传文件保存目录
			$file['filePath'] = $path . $file['token'] .'.'. $pathInfo['extension'];
			$file['modified'] = $_GET['modified'];      //上传文件的修改日期
			//保存令牌信息
			$this->setTokenInfo($file['token'], $file);
		}
		$result['token'] = $file['token'];
		$result['success'] = true;
		//$result['server'] = '';

		echo json_encode($result);
		exit;
	}
	
	
	/**
	 * 上传接口
	 */
	public function up(){
		if('html5' == $_GET['client']){
			$this->html5Upload();
		}
		elseif('form' == $_GET['client']){
			$this->flashUpload();
		}
		else {
			//错误
			exit;
		}

	}
	
	/**
	 * HTML5上传
	 */
	protected function html5Upload(){
		$token = $_GET['token'];
		$fileInfo = $this->getTokenInfo($token);
		
		if($fileInfo['size'] > $fileInfo['up_size']){
			//取得上传内容
			$data = file_get_contents('php://input', 'r');
			if(! empty($data)){
				//上传内容写入目标文件
				$fp = fopen($fileInfo['filePath'], 'a');
				flock($fp, LOCK_EX);
				fwrite($fp, $data);
				flock($fp, LOCK_UN);
				fclose($fp);
				//累积增加已上传文件大小
				$fileInfo['up_size'] += strlen($data);
				if($fileInfo['size'] > $fileInfo['up_size']){
					$this->setTokenInfo($token, $fileInfo);
				}
				else {
					//上传完成后删除令牌信息
					@unlink($this->_tokenPath . $token . '.token');
				}
			}
		}
		$result['start'] = $fileInfo['up_size'];
		$result['success'] = true;

		echo json_encode($result);
		exit;
	}
	
	/**
	 * FLASH上传
	 */
	public function flashUpload(){
	
		//$result['start'] = $fileInfo['up_size'];
		$result['success'] = false;

		echo json_encode($result);
		exit;
	}
	
	/**
	 * 生成文件内容
	 */
	protected function setTokenInfo($token, $data){
		
		file_put_contents($this->_tokenPath . $token . '.token', json_encode($data));
	}

	/**
	 * 获取文件内容
	 */
	protected function getTokenInfo($token){
		$file = $this->_tokenPath . $token . '.token';
		if(file_exists($file)){
			return json_decode(file_get_contents($file), true);
		}
		return false;
	}


}//endclass
