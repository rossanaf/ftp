<?php
  include($_SERVER['DOCUMENT_ROOT']."/html/header.php");
  include($_SERVER['DOCUMENT_ROOT']."/includes/db.php");
  if (!loginClass::checkLoginState($db)){
      header("location:/html/index.php"); 
      exit();
  }
  $stmt = $db->prepare("SELECT race_relay, race_name, race_id, race_type FROM races ORDER BY race_id ASC");
  $stmt->execute();
  $races = $stmt->fetchAll();
  $i = 0;
?>

<nav class="navbar navbar-expand-lg navbar-light fixed-top bg-light justify-content-between">
  <a class="navbar-brand" href="/"><img class="navbar-brand" src="/images/ftp_logo.png" width="100px"></a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarNavDropdown">
    <ul class="navbar-nav mr-auto">
      <!-- Provas Nacionais -->
      <li class="nav-item dropdown">
	      <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Base de Dados</a>
	      <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
	      	<?php if ($_COOKIE['userid'] < 4) { ?>
            <!------------------------------------------------------>
            <!-- Backup / Restore depende se ha provas carregadas -->
            <?php if (count($races) === 0) { ?>
              <a class="dropdown-item" href="javascript:void(0);" onclick="$('#importdb').slideToggle();" >Restore Evento</a>
              <a class="dropdown-item" href="javascript:void(0);" onclick="$('#importFTPAthletes').slideToggle();">Atletas Federados</a>
            <?php } else { ?>
              <a class="dropdown-item" href="/functions/exportdb.php" >Backup Evento</a>
            <?php } ?>	
	          <!------------------------------------------------------>
            <a class="dropdown-item" href="/startlist" >Startlist</a>
            <!-- ADICIONAR NOVOS CLUBES NA JANELA DE ADICIONAR NOVA INSCRICAO -->
	          <!-- <a class="dropdown-item" href="/teams">Clubes</a> -->
	          <div class="dropdown-divider"></div>
	        <?php } ?>
          <a class="dropdown-item" href="/races">Provas</a>
          <a class="dropdown-item" href="/times">Tempos</a>
          <!-- <a class="dropdown-item" href="javascript:void(0);" onclick="$('#importcheckin').slideToggle();">Importar Check-In</a> -->
          <a class="dropdown-item" href="/functions/chkindns.php">Aplicar DNS e DNF</a>
          <a class="dropdown-item" href="/prints/podiums.php" target="_blank">Pódios</a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" data-toggle="modal" href="#myModal">Acerca do FTP Scoring</a>
	      </div>
      </li>
      <?php 
        foreach ($races as $race):
          if ($i === 0) {
            if ($race['race_type'] === "itu") {
      ?>
        <li class="nav-item">
          <a class="nav-link" href="/athletesitu">Atletas/Registo Meta</a>
        </li>
      <?php } else { ?>
        <li class="nav-item">
          <a class="nav-link" href="/athletes">Atletas/Registo Meta</a>
        </li>    
      <?php }}
        $i++;
      ?>
      <li class="nav-item dropdown">
        <?php
          if (($race['race_type'] === "relay") && ($race['race_relay'] === "Y")) {
        ?>
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?=$race['race_name']?></a>
          <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
            <h6 class="alert alert-danger"><center>Femininas</center></h6>
            <a class="nav-link" href="/prints/relay-f-absgeral.php?race_id=<?=$race['race_id']?>" target="_blank">Absolutos Geral</a>
            <a class="nav-link" href="/prints/relay-f-abs.php?race_id=<?=$race['race_id']?>" target="_blank">Absolutos</a>
            <a class="nav-link" href="/prints/relay-f-cat.php?race_id=<?=$race['race_id']?>" target="_blank">Escalões</a>
            <h6 class="alert alert-danger"><center>Masculinas</center></h6>
            <a class="nav-link" href="/prints/relay-m-absgeral.php?race_id=<?=$race['race_id']?>" target="_blank">Absolutos Geral</a>
            <a class="nav-link" href="/prints/relay-m-abs.php?race_id=<?=$race['race_id']?>" target="_blank">Absolutos</a>
            <a class="nav-link" href="/prints/relay-m-cat.php?race_id=<?=$race['race_id']?>" target="_blank">Escalões</a>
          </div>
          <!------------------------------------------>
          <!------------ Estafetas Mistas ------------>
          <?php
              } elseif (($race['race_type'] === "relay") && ($race['race_relay'] === "X")) {
          ?>
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?=$race['race_name']?></a>
          <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
              <a class="nav-link" href="/prints/mxrelay-absgeral.php?race_id=<?=$race['race_id']?>" target="_blank">Absolutos Geral</a>
              <a class="nav-link" href="/prints/mxrelay-abs.php?race_id=<?=$race['race_id']?>" target="_blank">Absolutos</a>
              <a class="nav-link" href="/prints/mxrelay-cat.php?race_id=<?=$race['race_id']?>" target="_blank">Escalões</a>
          </div>          
          <?php
              } elseif (stripos($race['race_name'],'estafeta') > -1) {
          ?>
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?=$race['race_name']?></a>
          <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
              <h6 class="alert alert-danger"><center>Femininas</center></h6>
              <a class="nav-link" href="/prints/feminino-1t.php?race_id=<?=$race['race_id']?>" target="_blank">1 Tempo</a>
              <a class="nav-link" href="/prints/feminino-3t.php?race_id=<?=$race['race_id']?>" target="_blank">3 Tempos</a>
              <a class="nav-link" href="/prints/feminino-5t.php?race_id=<?=$race['race_id']?>" target="_blank">5 Tempos</a>
              <h6 class="alert alert-danger"><center>Masculinas</center></h6>
              <a class="nav-link" href="/prints/masculino-1t.php?race_id=<?=$race['race_id']?>" target="_blank">1 Tempo</a>
              <a class="nav-link" href="/prints/masculino-3t.php?race_id=<?=$race['race_id']?>" target="_blank">3 Tempos</a>
              <a class="nav-link" href="/prints/masculino-5t.php?race_id=<?=$race['race_id']?>" target="_blank">5 Tempos</a>
              <h6 class="alert alert-danger"><center>Mistas</center></h6>
              <a class="nav-link" href="/prints/mista-1t.php?race_id=<?=$race['race_id']?>" target="_blank">1 Tempo</a>
              <a class="nav-link" href="/prints/mista-3t.php?race_id=<?=$race['race_id']?>" target="_blank">3 Tempos</a>
              <a class="nav-link" href="/prints/mista-5t.php?race_id=<?=$race['race_id']?>" target="_blank">5 Tempos</a>
          </div>
          <?php
              } elseif (($race['race_type'] == "jovem") || ($race['race_type'] == "jovemaq")) {
          ?>
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Campeonato Jovem</a>
          <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
              <a class="nav-link" href="/prints/ben-fem.php?race_id=<?=$race['race_id']?>" target="_blank">Benjamins Fem</a>
              <a class="nav-link" href="/prints/ben-mas.php?race_id=<?=$race['race_id']?>" target="_blank">Benjamins Mas</a>
              <div class="dropdown-divider"></div>
              <a class="nav-link" href="/prints/inf-fem.php?race_id=<?=$race['race_id']?>" target="_blank">Infantis Fem</a>
              <a class="nav-link" href="/prints/inf-mas.php?race_id=<?=$race['race_id']?>" target="_blank">Infantis Mas</a>
              <div class="dropdown-divider"></div>
              <a class="nav-link" href="/prints/ini-fem.php?race_id=<?=$race['race_id']?>" target="_blank">Iniciados Fem</a>
              <a class="nav-link" href="/prints/ini-mas.php?race_id=<?=$race['race_id']?>" target="_blank">Iniciados Mas</a>
              <div class="dropdown-divider"></div>
              <a class="nav-link" href="/prints/juv-fem.php?race_id=<?=$race['race_id']?>" target="_blank">Juvenis Fem</a>
              <a class="nav-link" href="/prints/juv-mas.php?race_id=<?=$race['race_id']?>" target="_blank">Juvenis Mas</a>
              <div class="dropdown-divider"></div>
              <a class="nav-link" href="/prints/club-juv.php?race_id=<?=$race['race_id']?>" target="_blank">Clubes</a>
          </div>
          <?php
              } elseif ($race['race_type'] === "jEstf" && ($race['race_name'] === 'Ag. 1')) {
          ?>
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Estafetas Jovem</a>
          <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
              <a class="nav-link" href="/prints/jestf-abs.php?race_id=<?=$race['race_id']?>" target="_blank">Absolutos</a>
              <div class="dropdown-divider"></div>
              <a class="nav-link" href="/prints/jestf-group1-f.php?race_id=<?=$race['race_id']?>" target="_blank">Ben/Inf Fem</a>
              <a class="nav-link" href="/prints/jestf-group1-m.php?race_id=<?=$race['race_id']?>" target="_blank">Ben/Inf Masc</a>
              <div class="dropdown-divider"></div>
              <a class="nav-link" href="/prints/jestf-group2-f.php?race_id=2" target="_blank">Ini/Juv Fem</a>
              <a class="nav-link" href="/prints/jestf-group2-m.php?race_id=2" target="_blank">Ini/Juv Masc</a>
          </div>
          <?php
            } elseif ($race['race_type'] === "cre") {
          ?>
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?=$race['race_name']?></a>
          <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
              <!-- <a class="nav-link" href="/prints/absoluto.php?race_id=<?=$race['race_id']?>" target="_blank">Geral</a> -->
              <h6 class="alert alert-danger"><center>Femininos</center></h6>
              <a class="nav-link" href="/prints/cre-fem-geral.php?race_id=<?=$race['race_id']?>" target="_blank">Geral</a>
              <a class="nav-link" href="/prints/cre-fem-1t.php?race_id=<?=$race['race_id']?>" target="_blank">Abs 1 Tempo</a>
              <a class="nav-link" href="/prints/cre-fem-3t.php?race_id=<?=$race['race_id']?>" target="_blank">Abs 3 Tempos</a>
              <a class="nav-link" href="/prints/cre-fem-5t.php?race_id=<?=$race['race_id']?>" target="_blank">Abs 5 Tempos</a>
              <a class="nav-link" href="/prints/cre-fem-cat.php?race_id=<?=$race['race_id']?>" target="_blank">Abs Escalões</a>
              <h6 class="alert alert-danger"><center>Masculinos</center></h6>
              <a class="nav-link" href="/prints/cre-masc-geral.php?race_id=<?=$race['race_id']?>" target="_blank">Geral</a>
              <a class="nav-link" href="/prints/cre-masc-1t.php?race_id=<?=$race['race_id']?>" target="_blank">Abs 1 Tempo</a>
              <a class="nav-link" href="/prints/cre-masc-3t.php?race_id=<?=$race['race_id']?>" target="_blank">Abs 3 Tempos</a>
              <a class="nav-link" href="/prints/cre-masc-5t.php?race_id=<?=$race['race_id']?>" target="_blank">Abs 5 Tempos</a>
              <a class="nav-link" href="/prints/cre-masc-cat.php?race_id=<?=$race['race_id']?>" target="_blank">Abs Escalões</a>
          </div>
          <?php
            } elseif (($race['race_type'] == "triatlo") || ($race['race_type'] == "aquathlon")) {
          ?>
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?=$race['race_name']?></a>
          <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
              <a class="nav-link" href="/prints/absoluto.php?race_id=<?=$race['race_id']?>" target="_blank">Geral</a>
              <h6 class="alert alert-danger"><center>Femininos</center></h6>
              <a class="nav-link" href="/prints/feminino-1t.php?race_id=<?=$race['race_id']?>" target="_blank">1 Tempo</a>
              <a class="nav-link" href="/prints/feminino-3t.php?race_id=<?=$race['race_id']?>" target="_blank">3 Tempos</a>
              <?php
                  if ($race['race_type'] == "triatlo") {
              ?>
              <a class="nav-link" href="/prints/feminino-5t.php?race_id=<?=$race['race_id']?>" target="_blank">5 Tempos</a>
              <?php
                  }
              ?>
              <a class="nav-link" href="/prints/feminino-equipas.php?race_id=<?=$race['race_id']?>" target="_blank"> Equipas</a>
              <a class="nav-link" href="/prints/feminino-escaloes.php?race_id=<?=$race['race_id']?>" target="_blank">Escalões</a>
              <h6 class="alert alert-danger"><center>Masculinos</center></h6>
              <a class="nav-link" href="/prints/masculino-1t.php?race_id=<?=$race['race_id']?>" target="_blank">1 Tempo</a>
              <a class="nav-link" href="/prints/masculino-3t.php?race_id=<?=$race['race_id']?>" target="_blank">3 Tempos</a>
              <?php
                  if ($race['race_type'] == "triatlo") {
              ?>
              <a class="nav-link" href="/prints/masculino-5t.php?race_id=<?=$race['race_id']?>" target="_blank">5 Tempos</a>
              <?php
                  }
              ?>
              <a class="nav-link" href="/prints/masculino-equipas.php?race_id=<?=$race['race_id']?>" target="_blank">Equipas</a>
              <a class="nav-link" href="/prints/masculino-escaloes.php?race_id=<?=$race['race_id']?>" target="_blank">Escalões</a>
          </div>
          <?php
              }
          ?>
      </li>
        <?php 
            endforeach 
        ?>
        <li class="nav-item">
            <a class="nav-link" href="/live/">Follow Live</a>
        </li>
        
        
    </ul>
    <ul class="navbar-nav justify-content-end">
      <li>
      	<a href="/html/logout.php">
      		<button class="btn btn-outline-success" type="submit">
      			<?php echo "Logout ".$_SESSION['username']; ?>
      		</button>
      	</a>
      </li>
    </ul>
  </div>
</nav>

<!-- Modal -->
<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">

      <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">FTP Scoring - Controlo de Versões</h4>
            </div>
            <div class="modal-body">
                <p>FTP Scoring é uma aplicação web para gestão de eventos da Federação de Triatlo de Portugal.</p>
                <p>v2.0 
                    <br>- Primeira versão oficial
                </p>
                <p>v2.10
                    <br>- Nova estrutura de SubMenus
                    <br>- Correção de bugs nas Provas Jovens, horas de gun, distâncias e segmentos por escalão e género
                    <br>- Nova ordenação default da tabela de Atletas/Registo de Meta
                </p>
                <p>v2.11
                    <br>- Opção para Backup dum Evento
                    <br>- Opção para Recuperar um Evento guardado em disco
                </p>
                <p>v2.12
                    <br>- Opção que permite eliminar todas as Provas dum Evento
                    <br>- Alteração da tabela de Atletas/Registo de Meta, adição do tempo T0, para as provas de Estafetas Mistas e Contrarrelógio
                </p>
                <p>v3.0
                    <br>- Novo layout da Homepage, opção de ver os ultimos 5 resultados em direto e de consultar as classificações provisórias
                    <br>- Opção para abrir novo Evento
                </p>
                <p>v3.01
                    <br>- Mostrar tempos dos segmentos nos atletas desqualificados (DSQ)
                    <br>- Melhoria do algoritmo do cálculo de tempo e penalizações
                    <br>- Automatização dos DNF / DNS
                    <br>- Opção de imprimir listagem para pódios para provas de triatlo / duatlo
                </p>
                <p>v3.02
                    <br>- Alteracao do algoritmo de export/import da prova, de forma a exportar os tempos da tabela 'Times'
                    <!-- <br>- Correção do cálcula da hora do dia, quando se insere o tempo de prova -->
                    <!-- <br>- Opção para abrir novo Evento -->
                </p>
            </div>
            <!-- <div class="modal-footer">
            <h6 class="modal-title">Web: Rossana Ferreira</h6>
            </div> -->
        </div>    
    </div>
</div>

<div class="container">
    <form action="/functions/importdb.php" id="importdb" method="post" enctype="multipart/form-data">
        <div class="panel panel-default">
            <div class="panel-heading">
                <button><a href="#" onclick="alert('Ao restaurar um Evento guardado em disco, TODOS os dados da Base de Dados serão eliminados.')"> ALERTA: Ler antes de continuar! </a></button>
                <input type="file" name="file" />
                <input type="submit" class="btn btn-primary" name="importSubmit" value="IMPORTAR EVENTO">
            </div>
        </div>
    </form>
    <form action="/imports/importStartlistETU.php" id="importStartlistETU" method="post" enctype="multipart/form-data">
        <div class="panel panel-default">
            <div class="panel-heading">
                <input type="file" name="file" />
                <input type="submit" class="btn btn-primary" name="importSubmit" value="IMPORTAR STARTLIST">
            </div>
        </div>
    </form>
    <form action="/imports/importcheckin.php" id="importcheckin" method="post" enctype="multipart/form-data">
        <div class="panel panel-default">
            <div class="panel-heading">
                <input type="file" name="file" />
                <input type="submit" class="btn btn-primary" name="importSubmit" value="IMPORTAR CHECK-IN">
            </div>
        </div>
    </form>
    <form action="/imports/importFTPAthletes.php" id="importFTPAthletes" method="post" enctype="multipart/form-data">
        <div class="panel panel-default">
            <div class="panel-heading">
                <input type="file" name="file" />
                <input type="submit" class="btn btn-primary" name="importSubmit" value="IMPORTAR ATLETAS FEDERADOS">
            </div>
        </div>
    </form>
</div>

<?php include("footer.php"); ?>