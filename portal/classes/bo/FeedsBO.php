<?php
include_once(dirname(__FILE__)."/../vo/ConfigPortalVO.php");
include_once(ConfigPortalVO::getDirClassesRaiz().'util/Util.php');
include_once(ConfigPortalVO::getDirClassesRaiz().'util/PlayerUtil.php');
include_once(ConfigPortalVO::getDirClassesRaiz()."vo/ConfigVO.php");
include_once(ConfigVO::getDirSite().'gerenciador/classes/vo/ConfigGerenciadorVO.php');

class FeedsBO {

	public function __construct() {
	}
	
	private function getConteudoVO($codformato, $codconteudo) {
		switch ($codformato) {
			case 1:
				include_once(ConfigPortalVO::getDirClassesRaiz()."dao/TextoDAO.php");
				include_once(ConfigPortalVO::getDirClassesRaiz()."vo/TextoVO.php");
				$contdao = new TextoDAO;
				$contvo = $contdao->getTextoVO($codconteudo);
				break;
			case 2:
				include_once(ConfigPortalVO::getDirClassesRaiz()."dao/ImagemDAO.php");
				include_once(ConfigPortalVO::getDirClassesRaiz()."vo/AlbumImagemVO.php");
				$contdao = new ImagemDAO;
				$contvo = $contdao->getAlbumVO($codconteudo);
				break;
			case 3:
				include_once(ConfigPortalVO::getDirClassesRaiz()."dao/AudioDAO.php");
				include_once(ConfigPortalVO::getDirClassesRaiz()."vo/AlbumAudioVO.php");
				$contdao = new AudioDAO;
				$contvo = $contdao->getAudioVO($codconteudo);
				break;
			case 4:
				include_once(ConfigPortalVO::getDirClassesRaiz()."dao/VideoDAO.php");
				include_once(ConfigPortalVO::getDirClassesRaiz()."vo/VideoVO.php");
				$contdao = new VideoDAO;
				$contvo = $contdao->getVideoVO($codconteudo);
				break;
			//case 50:
			//	include_once(ConfigPortalVO::getDirClassesRaiz()."dao/ImagemDAO.php");
			//	include_once(ConfigPortalVO::getDirClassesRaiz()."vo/AlbumImagemVO.php");
			//	$contdao = new ImagemDAO;
			//	$contvo = $contdao->getAlbumVO($codconteudo);
			//	break;
		}
		return $contvo;
	}
	
	public function getFeedsConteudo($codformato) {
        include_once(ConfigPortalVO::getDirClassesRaiz()."vo/ConteudoVO.php");
		include_once(ConfigPortalVO::getDirClassesRaiz()."dao/ConteudoDAO.php");
		$contdao = new ConteudoDAO;
		$lista_ultimas = $contdao->getUltimasDoFormato($codformato, 10);
		
		switch($codformato) {
			case 1: $formato = 'Textos'; break;
			case 2: $formato = 'Imagens'; break;
			case 3: $formato = 'Áudios'; break;
			case 4: $formato = 'Vídeos'; break;
			case 5: $formato = 'Jornal'; break;
			case 6: $formato = 'Eventos'; break;
		}
        $rss = "";
        $rss .= "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
        $rss .= '<rss xmlns:media="http://search.yahoo.com/mrss/" xmlns:fp="http://flowplayer.org/fprss/" xmlns:content="http://purl.org/rss/1.0/modules/content/" xmlns:wfw="http://wellformedweb.org/CommentAPI/" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:atom="http://www.w3.org/2005/Atom" xmlns:sy="http://purl.org/rss/1.0/modules/syndication/" xmlns:slash="http://purl.org/rss/1.0/modules/slash/" version="2.0">';        
        $rss .= "<channel>\n";
        $rss .= "<title>".utf8_encode('RSS iTEIA - '.$formato)."</title>\n";
        $rss .= "<link>".ConfigVO::URL_SITE."feeds.php?formato=".$codformato."</link>\n";
        $rss .= "<description>".utf8_encode($formato. " - iTEIA")."</description>\n";
        $rss .= "<language>pt</language>\n";
        $rss .= "<pubDate>".date("r")."</pubDate>\n";
        $rss .= "<pubIn>iTEIA</pubIn>\n";
        
        foreach ($lista_ultimas as $key => $codconteudo) {
            $contvo = $this->getConteudoVO($codformato, $codconteudo);
            $rss .= "<item>\n";
            $rss .= "<title>".htmlspecialchars(utf8_encode($contvo->getTitulo()))."</title>\n";
            $rss .= "<pubDate>".date("r", strtotime($contvo->getDataHora()))."</pubDate>\n";
            $rss .= "<autor>FULANO DE TAL</autor>";
            $rss .= "<image>".ConfigVO::URL_SITE."exibir_imagem.php?img=".$contvo->getImagem()."&amp;tipo=2&amp;s=43</image>\n";
            $rss .= "<link>".ConfigVO::URL_SITE.$contvo->getUrl()."</link>\n";
            
            if($codformato == '3'){
                $contdao2 = new AudioDAO;
                $lista_audios = $contdao2->getAudiosAlbum($codconteudo);
                $dados = $contdao2->getDadosAlbum($codconteudo);
                $rss .= '<description>';
                $rss .= '<![CDATA[
                        <p><span>Autor:</span> '.htmlspecialchars(utf8_encode($dados["Autor"])).'</p>
                        <p><span>Colaborador Respons&aacute;vel:</span> '.htmlspecialchars(utf8_encode($dados["Colaborador"])).'</p>
                        <p><span>Descri&ccedil;&atilde;o:</span> '.htmlspecialchars(utf8_encode($dados["descricao"])).'</p>
                        ]]>';
                $rss .= '</description>';
                $rss .= '<content:encoded>
                        <![CDATA[
                        <p><span class="youtube" align="center" style="width:425px;height:344px;">
                        <object id="flowplayer" width="485" height="24" data="'.ConfigVO::URL_SITE.'js/flowplayer-3.1.5/flowplayer-3.1.5.swf" type="application/x-shockwave-flash">
                        <param name="movie" value="http://www.iteia.org.br/js/flowplayer-3.1.5/flowplayer-3.2.7.swf" />';
                $rss .= "<param name=\"flashvars\" value='config={\"playlist\":[";
                foreach($lista_audios as $cod_audio => $audio){
                    $audio_embed .= "{\"url\":\"http://www.iteia.org.br/conteudo/audios/".$audio['audio']."\", \"autoPlay\":false},";
                }
                $semVirgula = substr($audio_embed,0,-1);
                $rss .= $semVirgula;
                $rss .= "]}' />";
                $rss.= '<param name="allowFullScreen" value="true" />
                        <param name="wmode" value="transparent" />
                        </object>
                        </span></p>
                        ]]>
                        </content:encoded>';
                        
            }
            
            //verifica se é "vídeo do Youtube/Vimeo" ou "vídeo upado pelo usuário"
            if($codformato == '4'){
                $contdao2 = new VideoDAO;
                $dados = $contdao2->getDadosVideo($codconteudo);
                if ($contvo->getArquivo()){
                    $rss .= '<description>';
                    $rss .= '<![CDATA[
                            <p><span>Autor:</span> '.htmlspecialchars(utf8_encode($dados["Autor"])).'</p>
                            <p><span>Colaborador Respons&aacute;vel:</span> '.htmlspecialchars(utf8_encode($dados["Colaborador"])).'</p>
                            <p><span>Descri&ccedil;&atilde;o:</span> '.htmlspecialchars(utf8_encode($dados["descricao"])).'</p>
                            ]]>';
                    $rss .= '</description>';
                    
                    $rss .= '<content:encoded>
                            <![CDATA[
                            <p><span class="youtube" align="center" style="width:425px;height:344px;">
                                <object id="flowplayer" width="425" height="344" data="http://www.iteia.org.br/js/flowplayer-3.1.5/flowplayer-3.2.7.swf" type="application/x-shockwave-flash" >
                                    <param name="movie" value="http://www.iteia.org.br/js/flowplayer-3.1.5/flowplayer-3.2.7.swf" />';
                            $rss.= "<param name=\"flashvars\" value=\"config={'playlist':[{'url':'".PlayerUtil::urlArquivo($contvo->getArquivo(),'1')."', 'autoPlay':false}]}\" />";
                            $rss.= '<param name="allowFullScreen" value="true" />
                                    <param name="wmode" value="transparent" />
                                </object>
                            </span></p>
                            ]]>
                            </content:encoded>';

                }else{
                    // pega o link do vídeo e quebra
                    $tipo_video = PlayerUtil::youtubeOuVimeo($contvo->getLinkVideo());
                    if ($tipo_video=='1'){
                        $link = explode('=', $contvo->getLinkVideo());
                        $rss .= '<description>';
                        $rss .= '<![CDATA[
                             <p><span>Autor:</span> '.htmlspecialchars(utf8_encode($dados["Autor"])).'</p>
                             <p><span>Colaborador Respons&aacute;vel:</span> '.htmlspecialchars(utf8_encode($dados["Colaborador"])).'</p>
                             <p><span>Descri&ccedil;&atilde;o:</span> '.htmlspecialchars(utf8_encode($dados["descricao"])).'</p>
                             ]]>';
                        $rss .= '</description>';
                        $rss .= '<content:encoded>
                                <![CDATA[
                                <p><span class="youtube" align="center">
                                <object width="425" height="344">
                                <param name="movie" value="http://www.youtube.com/v/'.$link[1].'&amp;color1=d6d6d6&amp;color2=f0f0f0&amp;border=0&amp;fs=1&amp;hl=en&amp;autoplay=0&amp;showinfo=0&amp;iv_load_policy=3&amp;showsearch=0?rel=0" />
                                <param name="allowFullScreen" value="true" />
                                <embed wmode="transparent" src="http://www.youtube.com/v/'.$link[1].'&amp;color1=d6d6d6&amp;color2=f0f0f0&amp;border=0&amp;fs=1&amp;hl=en&amp;autoplay=0&amp;showinfo=0&amp;iv_load_policy=3&amp;showsearch=0?rel=0" type="application/x-shockwave-flash" allowfullscreen="true" width="425" height="344"></embed>
                                <param name="wmode" value="transparent" />
                                </object>
                                </span></p>
                                ]]>
                                </content:encoded>';              
                        
                    }else{
                    //se for video do vimeo
                        $link = explode('.com/', $contvo->getLinkVideo());
                        $rss .= '<description>';
                        $rss .= '<![CDATA[
                                <p><span>Autor:</span> '.htmlspecialchars(utf8_encode($dados["Autor"])).'</p>
                                <p><span>Colaborador Respons&aacute;vel:</span> '.htmlspecialchars(utf8_encode($dados["Colaborador"])).'</p>
                                <p><span>Descri&ccedil;&atilde;o:</span> '.htmlspecialchars(utf8_encode($dados["descricao"])).'</p>
                                ]]>';
                        $rss .= '</description>';
                        $rss .= '<content:encoded>
                                <![CDATA[
                                <p><span class="vimeo" align="center">
                                <object width="620" height="430"><param name="allowfullscreen" value="true" />
                                <param name="allowscriptaccess" value="always" />
                                <param name="movie" value="http://vimeo.com/moogaloop.swf?clip_id='.$link[1].'&server=vimeo.com&show_title=1&show_byline=1&show_portrait=1&color=00ADEF&fullscreen=1&autoplay=0&loop=0" />
                                <embed src="http://vimeo.com/moogaloop.swf?clip_id='.$link[1].'&server=vimeo.com&show_title=1&show_byline=1&show_portrait=1&color=00ADEF&fullscreen=1&autoplay=0&loop=0" type="application/x-shockwave-flash" allowfullscreen="true" allowscriptaccess="always" width="620" height="430">
                                </embed>
                                </object>
                                </span></p>
                                ]]>
                                </content:encoded>';                         
                     
                    }
                }
            }
            $rss .= "</item>\n";
        }
        
		$rss .= "</channel>\n</rss>\n";
		
		return $rss;
	}
    
	public function getFeedsCanal($codcanal, $codconteudo = '') {
		include_once('BuscaiTeiaBO.php');
		$buscabo = new BuscaiTeiaBO;
		
		$dados = array();
		$dados['extras'] = array('codcanal' => $codcanal);
		
		$dados_audios = $dados;
        if ($codconteudo){
            $dados_audios['formatos'] = array($codconteudo);
        }else{
            $dados_audios['formatos'] = array(2, 3, 4, 5, 6, 7);
        }
		
		$memid2 = $buscabo->efetuaBusca($dados_audios);
		
		$buscabo2 = new BuscaiTeiaBO($memid2, 1);
		$itens = $buscabo2->getItensBusca();
		
		$rss = "";
		$rss = "<"."?xml version=\"1.0\" encoding=\"utf-8\"?".">\n<rss version=\"2.0\" xml:base=\"".ConfigVO::getUrlSite()."\">\n";
		$rss .= "<channel>\n<title>".utf8_encode('RSS iTEIA - Canal')."</title>\n";
		$rss .= "<link>".ConfigVO::getUrlSite()."feeds.php?formato=8&amp;canal=".$codcanal."</link>\n";
		$rss .= "<description>".utf8_encode("Últimos Conteúdos do Canal - iTEIA")."</description>\n";
		$rss .= "<language>pt</language>\n";
		$rss .= "<pubDate>".date("r")."</pubDate>\n";

		foreach ($itens as $item) {
			$rss .= "<item>\n";
			$rss .= "<title>".htmlspecialchars(utf8_encode($item['titulo']))."</title>\n";
			$rss .= "<link>".ConfigVO::URL_SITE.$item['url_titulo']."</link>\n";
			$rss .= "<pubDate>".date("r", strtotime($item['datahora']))."</pubDate>\n";
			$rss .= "</item>\n";
		}
		$rss .= "</channel>\n</rss>\n";
		return $rss;
	}

	public function getFeedsNoticias() {
		include_once(ConfigPortalVO::getDirClassesRaiz()."dao/NoticiaDAO.php");
		$notdao = new NoticiaDAO;
		$ultimas = $notdao->getUltimasNoticias(array(), 10);
		
		$rss = "";
		$rss = "<"."?xml version=\"1.0\" encoding=\"utf-8\"?".">\n<rss version=\"2.0\" xml:base=\"".ConfigVO::getUrlSite()."\">\n";
		$rss .= "<channel>\n<title>".utf8_encode('RSS iTEIA - Jornal')."</title>\n";
		$rss .= "<link>".ConfigVO::getUrlSite()."feeds.php?formato=5</link>\n";
		$rss .= "<description>".utf8_encode("Últimas notícias - iTEIA")."</description>\n";
		$rss .= "<language>pt</language>\n";
		$rss .= "<pubDate>".date("r")."</pubDate>\n";

		foreach ($ultimas as $noticia) {
			$rss .= "<item>\n";
			$rss .= "<title>".htmlspecialchars(utf8_encode($noticia["titulo"]))."</title>\n";
			$rss .= "<link>".ConfigVO::getUrlSite().$noticia["url"]."</link>\n";
            $rss .= '<description>';
            $rss .= '
                    <![CDATA[
                    <p>'.utf8_encode($noticia["subtitulo"]).'</p>
                    <p />
                    <p> '.utf8_encode($noticia["descricao"]).'</p>
                    <p><span><strong>Editor Respons&aacute;vel:</strong></span>  '.utf8_encode($noticia["assinatura"]).'</p>
                    ]]>'; 
            $rss .= '</description>';				
            //$rss .= "<description>".htmlspecialchars(utf8_encode($noticia["subtitulo"]))."</description>\n";
			$rss .= "<pubDate>".date("r", strtotime($noticia["datahora"]))."</pubDate>\n";
			$rss .= "</item>\n";
		}
		$rss .= "</channel>\n</rss>\n";
		return $rss;
	}
	
	public function getFeedsAgenda() {
		include_once('classes/bo/AgendaBO.php');
		$agebo = new AgendaBO;
		$eventos = $agebo->getListaAgenda($_GET, 0, 10);
		//print_r($eventos);die;
		$rss = "";
		$rss = "<"."?xml version=\"1.0\" encoding=\"utf-8\"?".">\n<rss version=\"2.0\" xml:base=\"".ConfigVO::getUrlSite()."\">\n";
		$rss .= "<channel>\n<title>".utf8_encode('RSS iTEIA - Eventos')."</title>\n";
		$rss .= "<link>".ConfigVO::getUrlSite()."feeds.php?formato=6</link>\n";
		$rss .= "<description>".utf8_encode("Eventos - iTEIA")."</description>\n";
		$rss .= "<language>pt</language>\n";
		$rss .= "<pubDate>".date("r")."</pubDate>\n";

		foreach ($eventos as $evento) {
			if ($evento['cod_conteudo']) {
            
			$rss .= "<item>\n";
				$rss .= "<title>".htmlspecialchars(utf8_encode($evento["titulo"]))."</title>\n";
				//$rss .= "<link>".ConfigVO::getUrlSite()."evento.php?cod=".$evento['cod_conteudo']."</link>\n";
                $rss .= "<link>".ConfigVO::getUrlSite().$evento['url_agenda']."</link>\n";
				$rss .= "<description>".htmlspecialchars(utf8_encode($evento["descricao"]))."</description>\n";
                $rss .= "<pubDate>".date("r", strtotime($evento['hora_inicial']))."</pubDate>\n";
				$rss .= "</item>\n";
			}
		}
		$rss .= "</channel>\n</rss>\n";
		return $rss;
	}
	
	public function getFeedsGeral() {
		include_once(ConfigPortalVO::getDirClassesRaiz()."dao/ConteudoDAO.php");
		$contdao = new ConteudoDAO;
		$ultimas = $contdao->getListaConteudo(array('evento'=>'evento'), 0, 20);

		$rss = "";
		$rss = "<"."?xml version=\"1.0\" encoding=\"utf-8\"?".">\n<rss version=\"2.0\" xml:base=\"".ConfigVO::getUrlSite()."\">\n";
		$rss .= "<channel>\n<title>".utf8_encode('RSS iTEIA - Geral')."</title>\n";
		$rss .= "<link>".ConfigVO::getUrlSite()."feeds.php</link>\n";
		$rss .= "<description>".utf8_encode("Últimas Obras - iTEIA")."</description>\n";
		$rss .= "<language>pt</language>\n";
		$rss .= "<pubDate>".date("r")."</pubDate>\n";

		foreach ($ultimas as $noticia) {
			if ((int)$noticia['cod']) {
				$url = $contdao->getUrl($noticia['cod']);
				
				$rss .= "<item>\n";
				$rss .= "<title>".utf8_encode(Util::unhtmlentities($noticia["titulo"]))."</title>\n";
				$rss .= "<link>".ConfigVO::getUrlSite().$url."</link>\n";
				$rss .= "<description>".htmlspecialchars(utf8_encode($noticia["descricao"]))."</description>\n";
				$rss .= "<pubDate>".date("r", strtotime($noticia["datahora"]))."</pubDate>\n";
				$rss .= "</item>\n";
			}
		}
		$rss .= "</channel>\n</rss>\n";
		return $rss;
	}

	public function getFeedsUsuario($codusuario) {
		include_once(ConfigPortalVO::getDirClassesRaiz()."dao/ConteudoDAO.php");
		include_once(ConfigPortalVO::getDirClassesRaiz()."dao/UsuarioDAO.php");
		$contdao = new ConteudoDAO;
		$usuariodao = new UsuarioDAO;

		$usuario = $usuariodao->getUsuarioDados($codusuario);
		if ($usuario['cod_tipo'] == 1)
			$ultimas = $contdao->getMaisAcessados('recentes', $usuario['cod_usuario'], '', '', '', 10);
		elseif ($usuario['cod_tipo'] == 2)
			$ultimas = $contdao->getMaisAcessados('recentes', '', $usuario['cod_usuario'], '', '', 10);
		if ($usuario['cod_tipo'] == 3)
			$ultimas = $contdao->getMaisAcessados('recentes', '', '', $usuario['cod_usuario'], '', 10);

		$rss = "";
		$rss = "<"."?xml version=\"1.0\" encoding=\"utf-8\"?".">\n<rss version=\"2.0\" xml:base=\"".ConfigVO::getUrlSite()."\">\n";
		$rss .= "<channel>\n<title>".utf8_encode('RSS iTEIA - '.$usuario['nome'])."</title>\n";
		$rss .= "<link>".ConfigVO::getUrlSite()."feeds.php?cod=".$codusuario."</link>\n";
		$rss .= "<description>".utf8_encode($usuario['nome']." - Pernambuco Nação Cultural")."</description>\n";
		$rss .= "<language>pt</language>\n";
		$rss .= "<pubDate>".date("r")."</pubDate>\n";

		foreach ($ultimas as $conteudo) {
			$rss .= "<item>\n";
			$rss .= "<title>".htmlspecialchars(utf8_encode($conteudo["titulo"]))."</title>\n";
			$rss .= "<link>".ConfigVO::getUrlSite().$conteudo["url"]."</link>\n";
			//$rss .= "<description>".htmlspecialchars(utf8_encode($noticia["subtitulo"]))."</description>\n";
			$rss .= "<pubDate>".date("r", strtotime($conteudo["datahora"]))."</pubDate>\n";
			$rss .= "</item>\n";
		}
		$rss .= "</channel>\n</rss>\n";
		return $rss;
	}
	
	//public function getFeedsImagens() {
	//	$codformato = 2;
	//	include_once(ConfigPortalVO::getDirClassesRaiz()."vo/ConteudoVO.php");
	//	include_once(ConfigPortalVO::getDirClassesRaiz()."dao/ConteudoDAO.php");
	//	$contdao = new ConteudoDAO;
	//	$lista_ultimas = $contdao->getUltimasDoFormato($codformato, 10);
	//	
	//	switch($codformato) {
	//		case 1: $formato = 'Textos'; break;
	//		case 2: $formato = 'Imagens'; break;
	//		case 3: $formato = 'Áudios'; break;
	//		case 4: $formato = 'Vídeos'; break;
	//		case 5: $formato = 'Jornal'; break;
	//		case 6: $formato = 'Eventos'; break;
	//	}
	//	
	//	$rss = "";
	//	$rss = "<"."?xml version=\"1.0\" encoding=\"utf-8\"?".">\n<rss version=\"2.0\" xml:base=\"".$GLOBALS['iteiaurl']."\" >\n";
	//	$rss .= "<channel>\n<title>".utf8_encode('RSS iTEIA - '.$formato)."</title>\n";
	//	$rss .= "<link>".ConfigVO::URL_SITE."feeds.php?formato=".$codformato."</link>\n";
	//	$rss .= "<description>".utf8_encode($formato. " - iTEIA")."</description>\n";
	//	$rss .= "<language>pt</language>\n";
	//	$rss .= "<pubDate>".date("r")."</pubDate>\n";
	//
	//	foreach ($lista_ultimas as $key => $codconteudo) {
	//		$contvo = $this->getConteudoVO($codformato, $codconteudo);
	//		$rss .= "<item>\n";
	//		$rss .= "<![CDATA[\n";
	//		$rss .= "<img src=\"".ConfigVO::URL_SITE."exibir_imagem.php?img=".$contvo->getImagem()."&amp;tipo=2&amp;s=22\" />\n";
	//		$rss .= "]]>\n";
	//		$rss .= "<title>".htmlspecialchars(utf8_encode($contvo->getTitulo()))."</title>\n";
	//		$rss .= "<link>".ConfigVO::URL_SITE.$contvo->getUrl()."</link>\n";
	//		//$rss .= "<img src=\"".ConfigVO::URL_SITE."exibir_imagem.php?img=".$contvo->getImagem()."&amp;tipo=2&amp;s=22\" />\n";
	//		$rss .= "<pubDate>".date("r", strtotime($contvo->getDataHora()))."</pubDate>\n";
	//		$rss .= "</item>\n";
	//	}
	//	$rss .= "</channel>\n</rss>\n";
	//	return $rss;
	//}
}
