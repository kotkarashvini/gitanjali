<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of captcha
 *
 * @author anjalibh
 */
class captcha {
    
    public function show_captcha() {
		if (session_id() == "") {
			session_name("CAKEPHP");
			session_start();
		}

		$path= Vendor.'captcha/images';
		$imgname = 'captchaimg.jpg';
                
               
		$imgpath  = $path.'/'.$imgname;
                $imgpath  = 'Vendor/captcha/images/'.$imgname;

		$captchatext = md5(time());
		$captchatext = substr($captchatext, 0, 5);
		$_SESSION['captcha']=$captchatext;

		if (file_exists($imgpath) ){
			$im = imagecreatefromjpeg($imgpath);
			$grey = imagecolorallocate($im, 128, 128, 128);
			$font = $path.'/fonts/'.'BIRTH_OF_A_HERO.ttf';

			imagettftext($im, 20, 0, 10, 25, $grey, $font, $captchatext) ;

			header('Content-Type: image/jpeg');
			header("Cache-control: private, no-cache");
			header ("Last-Modified: " . gmdate ("D, d M Y H:i:s") . " GMT");
			header("Pragma: no-cache");
			imagejpeg($im);

			imagedestroy($im);
			ob_flush();
			flush();
		}
		else{
			echo 'captcha error';
			exit;
		}
	}
    //put your code here
}
