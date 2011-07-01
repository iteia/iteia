<?php
include_once(dirname(__FILE__)."/../vo/ConfigVO.php");

class PlayerUtil {

    public function playerAudio($item) {
		//$link_audio = self::urlArquivo($item, 2);
		$link_audio = ConfigVO::getUrlAudio().$item;
    	$html_player = "<object type=\"application/x-shockwave-flash\" data=\"".ConfigVO::URL_SITE."FlowPlayerDark.swf\" width=\"320\" height=\"29\" id=\"FlowPlayer\"><param name=\"allowScriptAccess\" value=\"sameDomain\" /><param name=\"movie\" value=\"".ConfigVO::URL_SITE."FlowPlayerDark.swf\" /><param name=\"quality\" value=\"high\" /><param name=\"scale\" value=\"noScale\" /><param name=\"wmode\" value=\"transparent\" /><param name=\"flashvars\" value=\"config={videoFile: '".$link_audio."', autoBuffering: false, streamingServer: 'lighttpd', initialScale: 'orig', loop: false }\" /></object>";
        return $html_player;
    }

	public static function urlArquivo($arquivo, $tipo) {
		if ($tipo == 1) {
			//$AgetHeaders = @get_headers('http://www.achix.inf.br/penc/videos/'.$arquivo);
			//if (preg_match("|200|", $AgetHeaders[0]))
			//	return 'http://www.achix.inf.br/penc/videos/'.$arquivo;
			return ConfigVO::getUrlVideo().$arquivo;
		}
		elseif ($tipo == 2) {
			//$AgetHeaders = @get_headers('http://www.achix.inf.br/penc/audios/'.$arquivo);
			//if (preg_match("|200|", $AgetHeaders[0]))
			//	return 'http://www.achix.inf.br/penc/audios/'.$arquivo;
			return ConfigVO::getUrlAudio().$arquivo;
		}
	}

    public function playerVideo($item, $tipo, $width='100%', $height='440') {
    	global $video_url;
    	
		if ($tipo == 1) {
			//$link_video = self::urlArquivo($item, 1);
			$link_video = ConfigVO::getUrlVideo().$item;
			$html_player = '<a href="'.$link_video.'" style="display:block;width:620px;height:430px" id="player"></a>';
			$html_player .= '<script type="text/javascript">flowplayer("player", "'.ConfigVO::URL_SITE.'js/flowplayer-3.1.5/flowplayer-3.1.5.swf");</script>';

        } elseif ($tipo == 2) {
            //$link = explode('=', $item);
            //$url = "http://www.youtube.com/v/$link[1]";
            //$html_player = "<object width=\"".$width."\" height=\"".$height."\"><param name=\"movie\" value=\"$url\"></param><param name=\"wmode\" value=\"transparent\"></param><embed src=\"".$url."\" type=\"application/x-shockwave-flash\" wmode=\"transparent\" width=\"".$width."\" height=\"".$height."\"></embed></object>";
			switch(self::youtubeOuVimeo($item)){
				case 1:
					$link = explode('=', $item);
					$url = "http://www.youtube.com/v/$link[1]";
					$html_player =  "<object width=\"".$width."\" height=\"".$height."\"><param name=\"movie\" value=\"$url\"></param><param name=\"wmode\" value=\"transparent\"></param><embed src=\"".$url."\" type=\"application/x-shockwave-flash\" wmode=\"transparent\" width=\"".$width."\" height=\"".$height."\"></embed></object>";
				break;
				case 2:
					$link = explode('com/', $item);
					$num = $link[1];
					//$html_player = "<object width=\"425\" height=\"239\"><param name=\"allowfullscreen\" value=\"true\" /><param name=\"allowscriptaccess\" value=\"always\" /><param name=\"movie\" value=\"http://vimeo.com/moogaloop.swf?clip_id=$num&amp;server=vimeo.com&amp;show_title=1&amp;show_byline=1&amp;show_portrait=1&amp;color=00ADEF&amp;fullscreen=1&amp;autoplay=0&amp;loop=0\" /><embed src=\"http://vimeo.com/moogaloop.swf?clip_id=$num&amp;server=vimeo.com&amp;show_title=1&amp;show_byline=1&amp;show_portrait=1&amp;color=00ADEF&amp;fullscreen=1&amp;autoplay=0&amp;loop=0\" type=\"application/x-shockwave-flash\" allowfullscreen=\"true\" allowscriptaccess=\"always\" width=\"425\" height=\"239\"></embed></object>";
					$html_player = "<object width=\"620\" height=\"430\"><param name=\"allowfullscreen\" value=\"true\" /><param name=\"allowscriptaccess\" value=\"always\" /><param name=\"movie\" value=\"http://vimeo.com/moogaloop.swf?clip_id=$num&server=vimeo.com&show_title=1&show_byline=1&show_portrait=1&color=00ADEF&fullscreen=1&autoplay=0&loop=0\" /><embed src=\"http://vimeo.com/moogaloop.swf?clip_id=$num&server=vimeo.com&show_title=1&show_byline=1&show_portrait=1&color=00ADEF&fullscreen=1&autoplay=0&loop=0\" type=\"application/x-shockwave-flash\" allowfullscreen=\"true\" allowscriptaccess=\"always\" width=\"620\" height=\"430\"></embed></object>";
				break;
			}
        }
        return $html_player;
    }
	
	public static function getImagemVideo($arquivo, $url, $imagem, $size = 72, $width = 90, $height = 90) {
		if ($arquivo) {
			if (file_exists(ConfigVO::getDirVideo().'convertidos/'.$arquivo.'.png'))
				return '<img src="/exibir_imagem.php?img='.$arquivo.'.png&amp;tipo=15&amp;s='.$size.'" alt="" width="'.$width.'" height="'.$height.'" />';
		}
		elseif ($url) {
			switch(self::youtubeOuVimeo($url)){
				case 1:
					$link = explode('=', $url);
					return '<img src="http://i3.ytimg.com/vi/'.str_replace('&feature', '', $link[1]).'/default.jpg" width="'.$width.'" height="'.$height.'" alt="" />';
				break;
				case 2:
					$link = explode('com/', $url);
					$num = $link[1];
					$hash = unserialize(file_get_contents("http://vimeo.com/api/v2/video/$num.php"));
					return '<img src="'.$hash[0]['thumbnail_large'].'" width="'.$width.'" height="'.$height.'" alt="" />';
				break;
			}
		}
		elseif ($imagem) {
			return '<img src="/exibir_imagem.php?img='.$imagem.'&amp;tipo=4&amp;s='.$size.'" alt="" width="'.$width.'" height="'.$height.'" />';
		}
		return '';
	}
	
	public static function youtubeOuVimeo($item){
		$html_player = '';
		if (preg_match("/youtube/i", $item)) {
			return 1;
		}elseif(preg_match("/vimeo/i", $item)){
			return 2;
		}
	}

}
