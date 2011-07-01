<?php
include_once("classes/vo/ConfigGerenciadorVO.php");
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."dao/NewsletterDAO.php");
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."dao/ConteudoExibicaoDAO.php");
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."util/Util.php");
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."dao/AgendaDAO.php");

class NewsletterBO {

	private $newsdao = null;
	private $dados_form = array();
	private $meses = array("Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho", "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro");
    private $emaildescadastro = '';
	
	public function __construct() {
		$this->newsdao = new NewsletterDAO();
	}
	
	public function getEnviadas($inicial, $mostrar) {
		return $this->newsdao->getNewsletterEnviadasRecentes($inicial, $mostrar);
	}
	
	public function getAgendadas() {
		return $this->newsdao->getNewsletterAgendadasRecentes();
	}
	
	public function removeProgramacao($codnewsletter) {
		$this->newsdao->removeProgramacao($codnewsletter);
		header('location: home_newsletter.php');
	}

	public function getListaConteudoSelecionados($codplay) {
		return $this->newsdao->getListaConteudoSelecionados($codplay);
	}
	
	public function getPrevisaoInterna($codnewsletter, $sessao=true, $mail='') {
		
		$template = file_get_contents(ConfigVO::DIR_SITE.'portal/templates/template_newsletter.html');
		
		//$template = eregi_replace("<!--%IMG_URL%-->", ConfigVO::URL_SITE."newsletter/", $template);
		$template = eregi_replace("<!--%HOME_SITE%-->", ConfigVO::URL_SITE, $template);
		$template = eregi_replace("<!--%PARTICIPAR%-->", ConfigVO::URL_SITE."participar", $template);
		$dados_newsletter = $this->newsdao->getDadosNewsletter($codnewsletter);
		$timestamp = strtotime($dados_newsletter['data_cadastro']);
		
		//$template = eregi_replace("<!--%DATA%-->", Util::iif($codnewsletter, date("d", $timestamp)." de ".$this->meses[date("m", $timestamp) - 1]." de ".date("Y", $timestamp), date("d")." de ".$this->meses[date("m") - 1]." de ".date("Y")), $template);
		$template = eregi_replace("<!--%DATA%-->", Util::iif($codnewsletter,
																date("d", $timestamp)." de ".$this->meses[date("m", $timestamp) - 1],
																date("d")." de ".$this->meses[date("m") - 1]
															 ),
															$template
								);
		
		$template = eregi_replace("<!--%EMAIL%-->", ConfigVO::EMAIL, $template);
        $template = eregi_replace("<!--%EMAILDESCADASTRO%-->", $this->emaildescadastro, $template);
		
		// destaque principal 23 de Setembro de 2008
		$destaque = $this->newsdao->getConteudoNewsletter($codnewsletter, $sessao, true, false);
		//print_r($destaque);
		
		//if ($destaque[0]['imagem']) {
		//	$html_foto_destaque = "<div style=\"background:#fff; border:1px solid #cecece; padding:4px; margin:18px 15px 10px 0; width:265px; float:left;\">".Util::iif($destaque[0]['credito'], "<span style=\"font-size:10px;\">".$destaque[0]['credito']."</span><br />")."\n<img src=\"".ConfigVO::URL_SITE."exibir_imagem.php?img=".$destaque[0]['imagem']."&amp;tipo=".Util::iif($destaque[0]['galeria'], '2', '1')."&amp;s=21\" width=\"265\" height=\"170\" /></div>\n";
		//}
		
		//$template = eregi_replace("<!--%IMG_DESTAQUE%-->", $html_foto_destaque, $template);
		//
		//$template = eregi_replace("<!--%TITULO_DESTAQUE%-->", "<a href=\"".ConfigVO::URL_SITE.$destaque[0]['url']."\" style=\"color: #215eae; text-decoration: none;\">".$destaque[0]['titulo']."</a>", $template);
		//
		//$template = eregi_replace("<!--%DESCRICAO_DESTAQUE%-->", $destaque[0]['descricao'], $template);
		
		// demais noticias
		// extraindo a ultima do array
		//$lista_noticias = $this->newsdao->getConteudoNewsletter(0, $sessao, false, true);
        $lista = $this->newsdao->getConteudoNewsletter($codnewsletter, $sessao, false, true);
		$lista_noticias = array();
		$lista_conteudos = array();
		foreach($lista as $conteudo){
			if($conteudo['cod_formato'] == 5){
				$lista_noticias[] = $conteudo;
			}else{
				$lista_conteudos[] = $conteudo;
			}
		}
		
		if(!empty($destaque)){
			if($destaque[0]['cod_formato'] == 5){
				array_unshift($lista_noticias,$destaque[0]);
			}else{
				array_unshift($lista_conteudos,$destaque[0]);
			}
		}
		
		////////NOTICIAS
		if(!empty($lista_noticias)){
			$html_noticias .='<img src="<!--%IMG_URL%-->images/noticias.jpg" alt="" />';
			
			$noticia_destaque = array_shift($lista_noticias);
			
			$html_noticias .= "
					<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\" width=\"440px\" style=\"margin:0 auto; margin-top:15px;\">
						<tr>
							<td>";
			$url_conteudo = ConfigVO::URL_SITE.$noticia_destaque['url']."?utm_source=Boletim&utm_medium=email&utm_content=Not%C3%ADcias&utm_campaign=Boletim%2Biteia";
			if($noticia_destaque['imagem'] != '')
								$html_noticias .= "<a href=\"".$url_conteudo."\" style=\"float:left; margin:0 5px 5px 0;\" target=\"_blank\" title=\"\"><img src=\"".ConfigVO::URL_SITE."exibir_imagem.php?img=".$noticia_destaque['imagem']."&amp;tipo=a&amp;s=7\" alt=\"\" width=\"156\" height=\"120\" style=\"border:none;\" /></a>";
			$html_noticias .= "<h1 style=\"padding:0; margin:0;\"><a href=\"".$url_conteudo."\" target=\"_blank\" title=\"".$noticia_destaque['titulo']."\" style=\"font-size:18px; color:#000; line-height:30px; padding-bottom:5px;\">".$noticia_destaque['titulo']."</a></h1>
								<p>".$noticia_destaque['descricao']."</p>
							</td>
						</tr>
					</table>";

			if(!empty($lista_noticias)){
				$html_noticias .= "<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\" width=\"440px\" style=\"margin:0 auto; margin-top:5px;\">";
				foreach ($lista_noticias as $noticia) {
					$url_conteudo = ConfigVO::URL_SITE.$noticia['url']."?utm_source=Boletim&utm_medium=email&utm_content=Not%C3%ADcias&utm_campaign=Boletim%2Biteia";
					//$html_noticias .= "<tr>\n<td width=\"100%\" style=\"border-bottom:1px dotted #cecece; padding:0 0 6px 17px; *padding:10px 0 10px 17px; background:url(".ConfigVO::URL_SITE."newsletter/bullet.gif) left 16px no-repeat;\"><h2 style=\"color:#215eae; font-size:14px; margin-bottom:5px;\"><a href=\"".ConfigVO::URL_SITE.$noticia['url']."\" style=\"color: #215eae; text-decoration: none;\">".$noticia['titulo']."</a></h2>".$noticia['descricao']."</td>\n</tr>\n";
					$html_noticias .= '
						<tr>
							<td width="72" style="border-bottom:2px solid #ed1c24; border-top:15px solid #ffffff; background:#ed1c24; color:#fff; padding:5px 3px; font-size:14px; "> <strong>
								'.date("d.m.Y", strtotime($noticia["datahora"])).'
							</strong></td>
							
							<td style="border-top:15px solid #ffffff; border-bottom:2px solid #ed1c24; padding-left:10px;">
								<a href="'.$url_conteudo.'" target="_blank" title="'.$noticia['titulo'].'" style="text-decoration:none; color:#333; line-height:16px; font-weight:bold;">'.$noticia['titulo'].'</a>
							</td>
						</tr>';
				}
				$html_noticias.="</table>";
			}
		}
		
		$template = eregi_replace("<!--%LISTA_NOTICIAS%-->", $html_noticias, $template);
		
		//if(!empty($destaque))
		//	array_unshift($lista_noticias,$destaque[0]);
			
		//print_r($lista);
		//$ultima_noticia = $lista_noticias[(count($lista_noticias) - 1)];
		
		//array_pop($lista_noticias);
		
		////////CONTEUDOS		
		if(!empty($lista_conteudos)){
			$html_conteudos .="<img src=\"<!--%IMG_URL%-->images/conteudos-destaque.jpg\" alt=\"\" width=\"500\" height=\"41\" vspace=\"15\" />
							<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\" width=\"100%\">";
							
			foreach ($lista_conteudos as $noticia) {
				$url_conteudo = ConfigVO::URL_SITE.$noticia['url']."?utm_source=Boletim&utm_medium=email&utm_content=Conte%C3%BAdos%2Bmultim%C3%ADdia&utm_campaign=Boletim%2Biteia";
				//$html_noticias .= "<tr>\n<td width=\"100%\" style=\"border-bottom:1px dotted #cecece; padding:0 0 6px 17px; *padding:10px 0 10px 17px; background:url(".ConfigVO::URL_SITE."newsletter/bullet.gif) left 16px no-repeat;\"><h2 style=\"color:#215eae; font-size:14px; margin-bottom:5px;\"><a href=\"".ConfigVO::URL_SITE.$noticia['url']."\" style=\"color: #215eae; text-decoration: none;\">".$noticia['titulo']."</a></h2>".$noticia['descricao']."</td>\n</tr>\n";
				$html_conteudos .= '<!-- destaque -->
									<tr>
									<td rowspan="2" valign="top" background="'.ConfigVO::URL_SITE.'templates/images/bg-foto-destaques.gif" style=" padding-left:25px; border-bottom:15px solid #ffffff">
										<a href="'.$url_conteudo.'" target="_blank" title="" style="float:left; display:block; width:110px; height:83px; margin-right:2px; margin-top:5px;">
											<img src="'.ConfigVO::URL_SITE.'exibir_imagem.php?img='.$noticia['imagem'].'&amp;tipo='.$noticia['cod_formato'].'&amp;s=44" alt="" style="border:none;" />
										</a>
									</td>
									
									<td valign="top" bgcolor="#b5b5b5" style="border-top:3px solid #ffffff; padding:5px">
										<a href="'.$url_conteudo.'" target="_blank" title="" style=" line-height:21px; font-size:18px; color:#333333; text-decoration:none;">'.$noticia['titulo'].'</a>
									</td>
									</tr>
									
									<tr>
									<td valign="middle" style="padding:5px;border-bottom:15px solid #ffffff">
										'.$noticia['descricao'].'
									</td>
									</tr>
									<!-- destaque -->';
			}
			$html_conteudos .="</table>";
		}
		
		$template = eregi_replace("<!--%LISTA_CONTEUDOS%-->", $html_conteudos, $template);
		
		////////Eventos
		$agendao = new AgendaDAO;
		$eventos = $agendao->getListaAgendaPortal(array(), 0, 5, true);
		if (count($eventos) > 1) {
			$html_agenda .="<img src=\"<!--%IMG_URL%-->images/eventos.jpg\" alt=\"\" width=\"500\" height=\"41\" />
				<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\" width=\"444px\" style=\"margin:0 auto 15px;\">";
			foreach ($eventos as $i => $agenda) {
				$url_conteudo = ConfigVO::URL_SITE.$agenda["url_agenda"]."?utm_source=Boletim&utm_medium=email&utm_content=Eventos&utm_campaign=Boletim%2Biteia";
				if (isset($agenda["cod_conteudo"])){
					//$html .= "<li><small>".date("d/m - H:i", strtotime($agenda["data_inicial"]." ".$agenda["hora_inicial"]))."</small><br/><a href=\"/evento.php?cod=".$agenda["cod_conteudo"]."\">".Util::cortaTexto(htmlentities($agenda["titulo"]), 65)."</a></li>\n";
					//<li><small>".date("d/m - H:i", strtotime($agenda["data_inicial"]." ".$agenda["hora_inicial"]))."</small><br/><a href=\"/".$agenda["url_agenda"]."\">".Util::cortaTexto(htmlentities($agenda["titulo"]), 65)."</a></li>\n";
					$html_agenda .= "<tr>
							<td width=\"72\" style=\"border-bottom:2px solid #d22898; border-top:15px solid #ffffff; background:#d22898; color:#fff; padding:5px 3px; font-size:14px;\">
								<strong>".date("d.m.Y", strtotime($agenda["data_inicial"]))."</strong>
							</td>
							
							<td valign=\"middle\" style=\"border-bottom:2px solid #d22898; padding-left:10px; border-top:15px solid #ffffff\">
								<a href=\"".$url_conteudo."\" target=\"_blank\" title=\"".Util::cortaTexto(htmlentities($agenda["titulo"]), 65)."\" style=\"text-decoration:none; color:#333; line-height:16px;\"><strong>".Util::cortaTexto(htmlentities($agenda["titulo"]), 65)."</strong></a>
							</td>
						</tr>";
				}
					
			}
			$html_agenda .= "</table>";
		}
		$template = eregi_replace("<!--%LISTA_AGENDA%-->", $html_agenda, $template);
		
		$template = eregi_replace("<!--%IMG_URL%-->", ConfigVO::URL_SITE."templates/", $template);
		
		// ultimo elemento
		if ($ultima_noticia['imagem']) {
			$html_foto_ultima = "<div align=\"center\"><img src=\"".ConfigVO::URL_SITE."exibir_imagem.php?img=".$ultima_noticia['imagem']."&amp;tipo=".Util::iif($ultima_noticia['galeria'], '2', '1')."&amp;s=6\" width=\"124\" height=\"124\" style=\"background:#fff; padding:4px; border:1px solid #cecece; margin-right:15px;\" /></div>\n";
		}
		
		$contdao = new ConteudoExibicaoDAO;
		$dados_autor = $contdao->getAutoresConteudo($ultima_noticia['cod_conteudo'], ' style="text-decoration:none; color:#215eae; font-size:10px;"');
		
		$template = eregi_replace("<!--%TITULO_ULTIMA%-->", "<a href=\"".ConfigVO::URL_SITE.$ultima_noticia['url']."\" style=\"text-decoration:none; color:#215eae; font-size:18px;\">".$ultima_noticia['titulo']."</a>", $template);
		
		$template = eregi_replace("<!--%DESCRICAO_ULTIMA%-->", $ultima_noticia['descricao'], $template);
		
		$template = eregi_replace("<!--%AUTORES_ULTIMA%-->", $dados_autor, $template);
		
		$template = eregi_replace("<!--%IMG_ULTIMA%-->", $html_foto_ultima, $template);
		
		return $template;
	}
	
	/*
	public function setDadosBusca($dados) {
		$this->dadosform['buscar'] = (int)$dados['buscar'];
		$this->dadosform['palavrachave'] = trim($dados['palavrachave']);
		$this->dadosform['buscarpor'] = trim($dados['buscarpor']);
		$this->dadosform['de'] = trim($dados['de']);
		$this->dadosform['ate'] = trim($dados['ate']);

		$this->dadosform['acao'] = (int)$dados['acao'];
		$this->dadosform['codplaylist'] = (array)$dados['codplaylist'];
	}
	
	public function getListaPlayList($get, $inicial, $mostrar, $tempo) {
		$this->setDadosBusca($get);

		if ($this->dadosform['acao']) {
			$this->playdao->executaAcoes($this->dadosform['acao'], $this->dadosform['codplaylist']);
			Header('Location: home.php');
			die;
		}
		
		return $this->playdao->getListaPlayList($get, $inicial, $mostrar, $tempo);
	}
	
	public function getValorCampo($campo) {
		return $this->dadosform[$campo];
	}
	*/
    public function setEmailDescadastro($mail){
        $this->emaildescadastro = $mail;
    }
}