<?php

if(isset($_POST) and isset($_GET['function']) and $_POST['msg']){
	
	@$function = $_GET['function'];
	@$sp = isset($_POST['sp']) ? preg_replace('/\D/i', '', $_POST['sp']) : 0;
	
	if($function  == 'codificar' or $function  == 'decodificar' ){
		
		$msg = trim($_POST['msg']);
		
		if(!is_numeric($sp) or $sp < 0) $sp = 0;
		
		$retorno = null;
		$array_msg = str_split($msg);
		
		foreach($array_msg as $value){
			
			$numberAscii = ord($value);
			
			if($function == 'codificar')
				$numberAscii+= $sp;
			else if($function == 'decodificar')
				$numberAscii-= $sp;
			
			$retorno.= chr($numberAscii);
		}
		
	}else if($function  == 'auto'){
		
		$msg = trim($_POST['msg']);
		
		$descodificacao = null;
		
		$array_msg = str_split($msg);
		
		for($i = 0; $i <= 126 ; $i++){
			
			$palavras = null;
			
			foreach($array_msg as $value){
				
				$numberAscii = ord($value) - $i;
				
				if($numberAscii)
					$palavras.= chr($numberAscii);
				else
					$palavras.= ' ';
			}
			
			$descodificacao[] = $palavras;
			
		}
		
		$retorno = [];
		
		if(is_array($descodificacao)){
			
			function stringLimpa($string){
				
				$what = array("á","à","ã","â","ä","Á","À","Ã","Â","Ä","é","è","ê","ë","É","È","Ê","Ë","í","ì","î","ï","Í","Ì","Î","Ï","ó","ò","õ","ô","ö","Ó","Ò","Õ","Ô","Ö","ú","ù","û","ü","Ú","Ù","Û","Ü",'ñ','Ñ','ç','Ç',' ','(',')',',',';',':','|','!','"','#','$','%','&','/','=','?','~','^','>','<','ª','º' );
				$by   = array('a','a','a','a','a','a','a','a','a','a','e','e','e','e','e','e','e','e','i','i','i','i','i','i','i','i','o','o','o','o','o','o','o','o','o','o','u','u','u','u','u','u','u','u','n','n','c','c','','','','','','','','','','','','','','','','','','','','','','');
		
				$string = str_replace($what, $by, $string);
				$string = strtolower($string);

				return $string;
			}
						
			$arquivo = "texto.txt";
			$palavrasTXT = file($arquivo,FILE_IGNORE_NEW_LINES);
			
			foreach($descodificacao as $texto){
				
				$palavrasDoTexto = explode(' ',$texto);
				
				$NPE = 0;
				
				foreach($palavrasDoTexto as $palavra){
					
					if(strlen($palavra) >= 3){
						
						$palavra = stringLimpa($palavra);
						foreach($palavrasTXT as $value){
							
							$value = stringLimpa($value);
						
							if($value == $palavra){
								$NPE++;
								break;
							}
						}
					}
				}
				
				if($NPE)
					$retorno[] = $texto;
			}
		}
		
		$retorno = implode('<br><br>',$retorno);

	}
}
	
