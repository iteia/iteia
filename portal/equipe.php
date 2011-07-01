<?php
include_once('classes/vo/ConfigPortalVO.php');
include_once(ConfigPortalVO::getDirClassesRaiz().'util/Util.php');
$topo_class = 'cat-equipe iteia';
$titulopagina = 'Equipe';
include ('includes/topo.php');
?>
    <div id="migalhas"><span class="localizador">Você está em:</span> <a href="/" title="Voltar para a página inicial" id="inicio">Início</a> <span class="marcador">&raquo;</span> <span class="atual">Equipe</span></div>
    <div id="conteudo">
		
		<div class="principal">
        <h2 class="midia">Equipe</h2>
        <h3 class="sub">Conselho Articulador Nacional</h3>

        <p><strong>Presidente (Licenciado)</strong><br />
        Sérgio Xavier<br />
        Idealizador do projeto iteia, jornalista e patrocinador do Instituto InterCidadania.</p>
        <p>          <strong>Presidente (Em exercício)</strong><br />
          Pedro Jatobá<br />
        Diretor de Ações Culturais do Instituto Intercidadania </p>

        <p>          <strong>Representante Nacional</strong><br />
          Célio Turino   <br />
          Idealizador do Programa Cultura Viva</p>
        <p>          <strong>Articulador Centro-Oeste</strong><br />
          Manoel Corrêa<br />

          Laboratório Cultura Viva (DF)</p>
        <p>          <strong>Articulador Sudeste</strong><br />
          César Piva<br />
          Fábrica do Futuro (MG)</p>
        <p>          <strong>Articulador Nordeste</strong><br />

          Felipe Machado <br />
          Pontão de Cultura Digital CDTL (PE)</p>
        <p>          <strong>Articulador Sul</strong><br />
          Everton Rodrigues<br />
          Quilombo do Sopapo (RS)</p>
        <p>          <strong>Articulador Norte</strong><br />

          Nilton Silva <br />
          Argonautas da Amazônia (PA)</p>
        <p>          <strong>Representante da Ação Griô</strong><br />
          Guitinho da Xambá <br />
          Nação Xambá (PE)</p>
        <p><strong> Representante da Coord. Nacional dos Pontos de Cultura</strong><br />

        Gerardo Damasceno<br />
        Acartes (CE) </p>
        <h3 class="sub">Coordenação do Projeto</h3>
        <p><strong>Coordenação Executiva</strong><br />
          Paulo Ramalho - Instituto InterCidadania<br />
          Gestão administrativa e coordenação das atividades do projeto.<br />

        </p>
        <p><strong>Coordenação de Formação e Articulação</strong><br />
          Pedro Jatobá - Instituto InterCidadania<br />
          Coordena a integração entre os beneficiários / colaboradores (Pontos de Cultura e iniciativas culturais independentes) e equipe do projeto iTEIA.</p>
        <p><strong>Coordenação de Desenvolvimento</strong><br />
          Billy Blay - Instituto InterCidadania<br />

          Coordena o desenvolvimento dos suportes tecnológicos necessários à execução do projeto. </p>
        <p><strong>Coordenação de Jornalismo</strong><br />
          João Paulo Seixas - Instituto InterCidadania<br />
          Coordena a manutenção do Jornal iteia, moderação dos conteúdos enviados pelos colaboradores e criação de pautas.</p>
        <h3 class="sub">Desenvolvimento Web</h3>
        <p><strong>Coordenação de Produção</strong><br />

          Billy Blay - Instituto InterCidadania <br />
          Coordenação da equipe de Desenvolvimento Web, planejamento das interações.</p>
        <p><strong>Design</strong><br />
          Tales Pereira<br />
          Criação dos padrões visuais, interfaces gráficas do  Portal.</p>
        <p><strong>Desenvolvimento Webstandards</strong><br />

          Billy Blay - Instituto InterCidadania<br />
          Implementação das interfaces em camadas de conteúdo (XHTML), apresentação (CSS) e comportamento (JavaScript). É responsável pela manutenção da acessibilidade do portal a portadores de deficiência e por meio de dispositivos móveis.<br />
        </p>
        <h3 class="sub">Desenvolvimento dos Sistemas</h3>
        <p><strong>Coordenação de Tecnologia</strong><br />
          Kerchenn Elteque - KMF<br />

          Coordenação da equipe de Tecnologia, desenvolvimento e implementação dos sistemas que possibilitam o funcionamento do portal e do gerenciamento de conteúdo.</p>
        <p><strong>Coordenação de Projetos</strong><br />
          Jonas Lucena - KMF</p>
        <p><strong>Coordenação de Engenharia de Software</strong><br />
          Mozart de Melo - KMF</p>
        <p><strong>Líder de Programação</strong><br />

          Marcel Ramos Cavalcante - KMF</p>
        <p><strong>Programação</strong><br />
          Eduardo Douglas - KMF<br />
          Diogo Burgos - KMF<br />
        </p>
        <h3 class="sub"> Desenvolvimento dos Módulos Multimidia</h3>

        <p><strong>Coordenação de Desenvolvimento</strong><br />
          Felipe Machado - Cultura Digital<br />
          Coordena a equipe de desenvolvimento da solução multimídia em software livre necessários à execução do projeto.</p>
        <p><strong>Desenvolvimento</strong><br />
          Cláudio da Silveira - Cultura Digital<br />
          Lúcio Corrêa - Cultura Digital<br />

          Pesquisa, desenvolvimento e testes.</p>
        <p><strong>Documentação e Processos</strong><br />
          Thiago Moreira - Cultura Digital<br />
          Análise, modelagem e desenvolvimento de processos.</p>
        <h3 class="sub">Liberação dos Códigos</h3>
        <p><strong>Coordenação</strong><br />

          Anderson Goulart <br />
          Marcelo Soares Souza <br />
          Equipe responsável pela abertura dos códigos e publicação na internet</p>
      </div>



      <div class="lateral">
        <?php include('includes/banners_lateral.php');?>
      </div>
    </div>
<?php
include ('includes/rodape.php');
