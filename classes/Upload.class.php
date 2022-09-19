<?php

	class Upload{
		private $arquivo;
		private $altura;
		private $largura;
		private $pasta;

		function __construct($arquivo, $altura, $largura, $pasta){
			$this->arquivo = $arquivo;
			$this->altura  = $altura;
			$this->largura = $largura;
			$this->pasta   = $pasta;
		}
		
//		private function getExtensao(){
//			//retorna a extensao da imagem	
//			return $extensao = strtolower(end(explode('.', $this->arquivo['name'])));
//		}
                
                private function getExtensao()
                {
                    $arqType = $this->arquivo['type'];
                    if($arqType == "image/gif")
                        return "gif";
                    else {
                        if($arqType == "image/jpeg")
                            return "jpeg";
                        else{
                            if($arqType == "image/png")
                                return "png";
                            else
                            {
                                return "invalid";
                            }
                        }
                    }
                        
                }


                private function validaArquivo(){
			$tiposPermitidos= array('image/gif', 'image/jpeg', 'image/png');	 // extensoes permitidas
                        $arqType = $this->arquivo['type'];
			if (array_search($arqType, $tiposPermitidos) === false)
                        {
                            return false;
                        }
                        else
                        {
                            return true;	
                        }
		}
		
		//largura, altura, tipo, localizacao da imagem original
		private function redimensionar($imgLarg, $imgAlt, $tipo, $img_localizacao){
			//descobrir novo tamanho sem perder a proporcao
			if ( $imgLarg > $imgAlt ){
				$novaLarg = $this->largura;
				$novaAlt = round( ($novaLarg / $imgLarg) * $imgAlt );
			}
			elseif ( $imgAlt > $imgLarg ){
				$novaAlt = $this->altura;
				$novaLarg = round( ($novaAlt / $imgAlt) * $imgLarg );
			}
			else // altura == largura
				$novaAltura = $novaLargura = max($this->largura, $this->altura);
			
			//redimencionar a imagem
			
			//cria uma nova imagem com o novo tamanho	
			$novaimagem = imagecreatetruecolor($novaLarg, $novaAlt);
			
			switch ($tipo){
				case 1:	// gif
					$origem = imagecreatefromgif($img_localizacao);
					imagecopyresampled($novaimagem, $origem, 0, 0, 0, 0,
					$novaLarg, $novaAlt, $imgLarg, $imgAlt);
					imagegif($novaimagem, $img_localizacao);
					break;
				case 2:	// jpg
					$origem = imagecreatefromjpeg($img_localizacao);
					imagecopyresampled($novaimagem, $origem, 0, 0, 0, 0,
					$novaLarg, $novaAlt, $imgLarg, $imgAlt);
					imagejpeg($novaimagem, $img_localizacao);
					break;
				case 3:	// png
					$origem = imagecreatefrompng($img_localizacao);
					imagecopyresampled($novaimagem, $origem, 0, 0, 0, 0,
					$novaLarg, $novaAlt, $imgLarg, $imgAlt);
					imagepng($novaimagem, $img_localizacao);
					break;
			}
			
			//destroi as imagens criadas
			imagedestroy($novaimagem);
			imagedestroy($origem);
		}
		
		public function salvar(){									
			$extensao = $this->getExtensao();
			
			//gera um nome unico para a imagem em funcao do tempo
			$novo_nome = time() . '.' . $extensao;
			//localizacao do arquivo 
			$destino = $this->pasta . $novo_nome;
			
			//move o arquivo
			if (! move_uploaded_file($this->arquivo['tmp_name'], $destino)){
				if ($this->arquivo['error'] == 1)
					return "1"; //"Tamanho excede o permitido";
				else
					return "0"; // "Erro " . $this->arquivo['error'];
			}
				
			if ($this->validaArquivo()){												
				//pega a largura, altura, tipo e atributo da imagem
				list($largura, $altura, $tipo, $atributo) = getimagesize($destino);

				// testa se é preciso redimensionar a imagem
				if(($largura > $this->largura) || ($altura > $this->altura))
					$this->redimensionar($largura, $altura, $tipo, $destino);
			}
			return $destino;
		}						
	}
?>