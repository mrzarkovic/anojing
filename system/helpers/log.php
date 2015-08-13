<?php

class Log
{
   protected $_date_fmt	= 'Y-m-d H:i:s';

   public function write_log( $type = 'error', $msg = "" )
   {
      $type = strtoupper($type);
      $filepath = dirname(dirname(__FILE__)).'/logs/log-'.date('Y-m-d').'.php';
      $message = '';

      if ( ! file_exists($filepath))
		{
			$message .= "<"."?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?".">\n\n";
		}


      if ( ! $fp = @fopen($filepath, "x"))
		{
			return FALSE;
		}

      $message .= $type.' '.(($type == 'INFO') ? ' -' : '-').' '.date($this->_date_fmt). ' --> '.$msg."\n";

		flock($fp, LOCK_EX);
		fwrite($fp, $message);
		flock($fp, LOCK_UN);
		fclose($fp);

		@chmod($filepath, FILE_WRITE_MODE);
		return TRUE;
   }
}
