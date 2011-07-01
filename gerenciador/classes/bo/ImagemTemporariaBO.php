<?php

class ImagemTemporariaBO {

	//private static $dir_imagens_temporarias = '/tmp/conteudo/';
	private static $dir_imagens_temporarias = '/tmp/';

	public static function criar(&$arquivo) {
		$nome_imagem = 'pencimgtemp_'.Util::gera_randomico();
		copy($arquivo["tmp_name"], self::$dir_imagens_temporarias.$nome_imagem);		
		return $nome_imagem;
		//$imagem = new Imagick(self::$dir_imagens_temporarias.$nome_imagem);
		//echo "Width = ".$imagem->getImageWidth();
		//echo "<br>Height = ".$imagem->getImageHeight();
		//echo "<br>Aspect = ".($imagem->getImageWidth()/$imagem->getImageHeight());
	}

	public static function exibir($nome, $width, $height, $crop = true) {
		if (file_exists(self::$dir_imagens_temporarias.$nome)) {
			try {
				$imagem = new Imagick(self::$dir_imagens_temporarias.$nome);
				if ($crop)
					$imagem->cropThumbnailImage($width, $height);
				else
					$imagem->thumbnailImage($width, $height);
				header("Content-Type: image/".$imagem->getImageFormat());
				echo $imagem;
			} catch (Exception $e) {
				$imagem = new Imagick();
				$imagem->newImage(1, 1, new ImagickPixel("rgb(255,255,255)"));
				$imagem->setImageFormat("png");
			}
		}
	}
	
	public static function exibirProporcional($nome) {
		if (file_exists(self::$dir_imagens_temporarias.$nome)) {
			try {
				$imagem = new Imagick(self::$dir_imagens_temporarias.$nome);
				header("Content-Type: image/".$imagem->getImageFormat());
				echo $imagem;
			} catch (Exception $e) {
				$imagem = new Imagick();
				$imagem->newImage(1, 1, new ImagickPixel("rgb(255,255,255)"));
				$imagem->setImageFormat("png");
			}
		}
	}

	public static function crop($nome,$width,$height,$x1,$y1) {
		if (file_exists(self::$dir_imagens_temporarias.$nome)) {
			try {
				$imagem = new Imagick(self::$dir_imagens_temporarias.$nome);
				$imagem->cropImage($width,$height,$x1,$y1);
				$image->setImagePage($width, $height, 0, 0);
				$cortada = self::$dir_imagens_temporarias.$nome."_corte";
				$imagem->writeImage($cortada);

				echo $cortada;
			} catch (Exception $e) {
				$imagem = new Imagick();
				$imagem->newImage(1, 1, new ImagickPixel("rgb(255,255,255)"));
				$imagem->setImageFormat("png");
			}
		}
	}

	public static function criaDefinitiva($nometemp, $nomearquivo_parcial, $dir_destino) {
		$infoimg = getimagesize(self::$dir_imagens_temporarias.$nometemp);
		$nomearquivo = $nomearquivo_parcial.image_type_to_extension($infoimg[2], true);
		copy(self::$dir_imagens_temporarias.$nometemp, $dir_destino.$nomearquivo);
		return $nomearquivo;
	}
}
