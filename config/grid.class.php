<?php
include_once($_SESSION['DirBaseSite'].'config/conexao.class.php');

class Grid{
     
     private $Con;
     private $Html;
     private $Sql;
     private $Chave;
     private $ArrayColunas;
     private $ArrayNomeColunas;
     private $ArrayAlinhamentoColunas;
     private $ArrayAlinhamentoNomeColunas;
     private $TituloGrid;
     
     
     private $QuantidadeRegistros;
     private $PaginaAtual;
     private $NLinhas;
     
     private $Zebra = 0;

     public function __construct()
     {
          $this->Con = new Conexao();
     }
     
     public function setArrayColunas($ArrayColunas){
          $this->ArrayColunas = $ArrayColunas;
     }
     
     public function getArrayColunas(){
          return $this->ArrayColunas;
     }
     
     
     public function setArrayNomeColunas($ArrayNomeColunas){
          $this->ArrayNomeColunas = $ArrayNomeColunas;
     }
     
     public function getArrayNomeColunas(){
          return $this->ArrayNomeColunas;
     }
     
     
     public function setArrayAlinhamentoNomeColunas($ArrayAlinhamentoNomeColunas){
          $this->ArrayAlinhamentoNomeColunas = $ArrayAlinhamentoNomeColunas;
     }
     
     public function getArrayAlinhamentoNomeColunas(){
          return $this->ArrayAlinhamentoNomeColunas;
     }
     
     
     public function setArrayAlinhamentoColunas($ArrayAlinhamentoColunas){
          $this->ArrayAlinhamentoColunas = $ArrayAlinhamentoColunas;
     }
     
     public function getArrayAlinhamentoColunas(){
          return $this->ArrayAlinhamentoColunas;
     }
     
     
     public function setTituloGrid($TituloGrid){
          $this->TituloGrid = $TituloGrid;
     }
     
     public function getTituloGrid(){
          return $this->TituloGrid;
     }
     
     public function setSql($Sql){
          $this->Sql = $Sql;
     }
     
     public function getSql(){
          return $this->Sql;
     }
     
     public function setChave($Chave){
          $this->Chave = $Chave;
     }
     
     public function getChave(){
          return $this->Chave;
     }
     
     
     public function gridPadrao() {
          $this->Con->conectar();
          
          //Tratando Sql
          $this->tratarSql();
          
          $NLinhas = $this->Con->execNLinhas($this->getSql());
          
          if($NLinhas > 0){
               $this->setNLinhas($NLinhas);
          
               $this->getTituloGridPadrao();
               
               $this->getPaginacao();
               $this->getLimitePaginacao();

               $this->getInicioTabelaGridPadrao();

               $this->getNomeColunasTabelaGridPadrao();
               $this->getColunasTabelaGridPadrao();

               $this->getFimTabelaGridPadrao();

               $this->getPaginacao();
          }else{
               $this->getInicioTabelaSemResultado();
                    $this->getSemResultado();
               $this->getFimTabelaSemResultado();
          }
          
          
          
          //Retornando a Grid Formatada - HTML
          return $this->Html;
     }
    
     public function getTituloGridPadrao(){
         
          $this->Html.= "<table width='100%' border='0' cellspacing='0' cellpadding='0' class='internaTop'>
                              <tr>
                                   <td class='titulo_conteudo'><img src='".$_SESSION['UrlBaseSite']."imagens/bullet.gif' style='padding-bottom:1px;'> ".$this->getTituloGrid()."</td>
                              </tr>
                         </table>";
     }
    
     public function getInicioTabelaGridPadrao(){
         
          $this->Html.= "<table width='100%' border='0' cellspacing='0' cellpadding='0' class='internaBottom'>";
     }
    
     public function getFimTabelaGridPadrao(){
          
          $this->Html.= "</table>";
     }
    
     public function getNomeColunasTabelaGridPadrao(){
         
         $ArrayNomeColunas = $this->getArrayNomeColunas();
         $ArrayAlinhamentoNomeColunas = $this->getArrayAlinhamentoNomeColunas();
         
         $Zebra = (++$this->Zebra % 2) ? 'zebraA' : 'zebraB';
         $this->Html.= "<tr class='textoTituloGrid $Zebra'>";
         foreach($ArrayNomeColunas as $ValueNomeColunas){              
              $Alinhamento = $this->getAlinhamento($ArrayAlinhamentoNomeColunas[$ValueNomeColunas]);
              $this->Html.= "<td $Alinhamento>$ValueNomeColunas</td>";
         }
         
         //Botoes
         $this->Html.= "<td width='30px' class='textoCentro'>&nbsp;</td>
                        <td width='30px' class='textoCentro'>&nbsp;</td>
                        <td width='30px' class='textoCentro'>&nbsp;</td>";
         
         $this->Html.= "</tr>";
     }
     
     public function getColunasTabelaGridPadrao(){
         
          $ArrayColunas = $this->getArrayColunas();
          $ArrayAlinhamentoColunas = $this->getArrayAlinhamentoColunas();
          $ArrayRs = $this->Con->execTodosArray($this->getSql());
          $Chave = $this->getChave();
         
          foreach($ArrayRs as $Rs){
         
               $Zebra = (++$this->Zebra % 2) ? 'zebraA' : 'zebraB';
               $this->Html.= "<tr class='textoGrid $Zebra'>";
               
               foreach($ArrayColunas as $ValueColunas){
                    $Alinhamento = $this->getAlinhamento($ArrayAlinhamentoColunas[$ValueColunas]);
                    $this->Html.= "<td $Alinhamento>".$Rs[$ValueColunas]."</td>";
               }               
               //Botoes
               $this->Html.= "<td><img title='Visualizar' src='".$_SESSION['UrlBaseSite']."imagens/viz.png' style='padding-bottom:1px; cursor: pointer;' onclick='visualizar(\"".$Rs[$Chave]."\");'></td>
                              <td><img title='Alterar' src='".$_SESSION['UrlBaseSite']."imagens/alt.png' style='padding-bottom:1px; cursor: pointer;' onclick='alterar(\"".$Rs[$Chave]."\");'></td>
                              <td><img title='Inativar' src='".$_SESSION['UrlBaseSite']."imagens/del.png' style='padding-bottom:1px; cursor: pointer;' onclick='deletar(\"".$Rs[$Chave]."\");'></td>";
               
               $this->Html.= "</tr>";

          }
     }
     
     public function getInicioTabelaSemResultado(){
         
          $this->Html.= "<table width='100%' border='0' cellspacing='0' cellpadding='0' class='interna'>";
     }
    
     public function getFimTabelaSemResultado(){
          
          $this->Html.= "</table>";
     }
     
     public function getSemResultado(){
          
          $this->Html.= "<tr><td><div class='semResultado'>Nenhum resultado encontrado.</div></td></tr>";
     }

     public function getAlinhamento($Valor){
          
          $Alinhamento = "";
     
          switch ($Valor){
               case "C": #Centro        
                    $Alinhamento = 'class=\'textoCentro\'';
               break;
               case "E": #Esquerda        
                    $Alinhamento = 'class=\'textoEsquerda\'';
               break;
               case "D": #Direita        
                    $Alinhamento = 'class=\'textoDireita\'';
               break;          
               default: #Nada
                    $Alinhamento = '';
               break;          
          }
          
          return $Alinhamento;         
    }
    
    
    /*
    ** Paginacao 
    */    
    public function setQuantidadeRegistros($QuantidadeRegistros){
         $this->QuantidadeRegistros = $QuantidadeRegistros;
    }
    public function getQuantidadeRegistros(){
         return$this->QuantidadeRegistros;
    }
    
    public function setPaginaAtual($PaginaAtual){
         $this->PaginaAtual = $PaginaAtual;
    }
    public function getPaginaAtual(){
         return$this->PaginaAtual;
    }
    
    public function setNLinhas($NLinhas){
         $this->NLinhas = $NLinhas;
    }
    public function getNLinhas(){
         return$this->NLinhas;
    }
    
    public function getPaginacao(){
          
          //Tratando Campo
          $QuantidadeRegistros = $this->getQuantidadeRegistros();
          $PaginaAtual = $this->getPaginaAtual();
          (empty($QuantidadeRegistros)) ? $this->setQuantidadeRegistros(25) : $this->setQuantidadeRegistros($QuantidadeRegistros);
          (empty($PaginaAtual)) ? $this->setPaginaAtual(1) : $this->setPaginaAtual($PaginaAtual);
          
          //Pegando Valores
          $NLinhas = $this->getNLinhas();
          $QuantidadeRegistros = $this->getQuantidadeRegistros();
          $PaginaAtual = $this->getPaginaAtual();
          
          $Espaco = "&nbsp;&nbsp;&nbsp;";
         
          //Extremo dos Proximos QLinhas
          $Inicio = ($PaginaAtual == 1) ? 0 :(($PaginaAtual * $QuantidadeRegistros) - $QuantidadeRegistros);         
          
          //Definir Limit        
          $Limit = ($QuantidadeRegistros != 0) ? " LIMIT $QuantidadeRegistros OFFSET $Inicio;" : "";
          
          $TotalPaginas = ceil($NLinhas / $QuantidadeRegistros);
          
          //Alterando o SQL
          $this->setSql($this->getSql().$Limit);
          
          if($TotalPaginas > 1){
               
               $this->Html .= "<div class='getPaginacao'>";
               
               //Primeira e Anterior
               if($PaginaAtual > 1){
                    $this->Html .= "<span class='paginacao'><a href='javascript:paginacao(1, $QuantidadeRegistros);' class='linkpag'><img src='".$_SESSION['UrlBaseSite']."imagens/pag_vol.gif' border='0' />&nbsp;Primeira</a></span>";
                    $this->Html .= $Espaco;
                    $this->Html .= "<span class='paginacao'><a href='javascript:paginacao(".($PaginaAtual-1).", $QuantidadeRegistros);' class='linkpag'><img src='".$_SESSION['UrlBaseSite']."imagens/pag_vol1.gif' border='0' />&nbsp;Anterior</a></span>";
               }else{
                    $this->Html .= "<span class='paginacaoOff'><img src='".$_SESSION['UrlBaseSite']."imagens/pag_vol_off.gif' />&nbsp;Primeira</span>";
                    $this->Html .= $Espaco;
                    $this->Html .= "<span class='paginacaoOff'><img src='".$_SESSION['UrlBaseSite']."imagens/pag_vol1_off.gif' />&nbsp;Anterior</span>";
               }
               
               //Campo Pagina Atual
               $this->Html .=  "$Espaco<span>".$this->getCampoPaginas()."</span>$Espaco";
               
               //Proximo e Ultimo
               if($PaginaAtual < $TotalPaginas){
                    $this->Html .= "<span class='paginacao'><a href='javascript:paginacao(".($PaginaAtual+1).", $QuantidadeRegistros);' class='linkpag'>Próximo&nbsp;<img src='".$_SESSION['UrlBaseSite']."imagens/pag_ava.gif' border='0' /></a></span>";
                    $this->Html .= $Espaco;
                    $this->Html .= "<span class='paginacao'><a href='javascript:paginacao($TotalPaginas, $QuantidadeRegistros);' class='linkpag'>Último&nbsp;<img src='".$_SESSION['UrlBaseSite']."imagens/pag_ava1.gif' border='0' /></a></span>";
               }else{
                    $this->Html .= "<span class='paginacaoOff'>Próximo&nbsp<img src='".$_SESSION['UrlBaseSite']."imagens/pag_ava_off.gif' /></span>";
                    $this->Html .= $Espaco;
                    $this->Html .= "<span class='paginacaoOff'>Último&nbsp<img src='".$_SESSION['UrlBaseSite']."imagens/pag_ava1_off.gif' /></span>";
               }
               
               //Calculo de Páginas
               if($PaginaAtual == 1){
                    $InicioPag = 1;
                    $FimPag    = ($NLinhas > $QuantidadeRegistros) ? $QuantidadeRegistros : $NLinhas;
               }else{
                    $InicioPag = ((($PaginaAtual - 1) * $QuantidadeRegistros) + 1);
                    $FimPag    = ($PaginaAtual == $TotalPaginas) ? $NLinhas : $PaginaAtual * $QuantidadeRegistros;
               }
               
               $this->Html .= "$Espaco<span class='paginacaoOff'>$InicioPag - $FimPag de $NLinhas</span>";
               $this->Html .= "</div>";               
          }          
          
     }
     
     public function getLimitePaginacao(){
          //Pegando Valores
          $NLinhas = $this->getNLinhas();
          $QuantidadeRegistros = $this->getQuantidadeRegistros();
          
          $TotalPaginas = ceil($NLinhas / $QuantidadeRegistros);
          
          $this->Html .= "<div class='getLimitePaginacao'>";
          if($TotalPaginas > 1){
               $this->Html .= "<span>Mostrando ".$this->getCampoQuantidadeRegistros()." registros por página</span>";
          }else{
               $Resultado  = $NLinhas.' registro'.($NLinhas > 1 ? 's' : '').' encontrado'.($NLinhas > 1 ? 's' : '');            
               $this->Html .= "<span>$Resultado - Mostrando até ".$this->getCampoQuantidadeRegistros()." registros por página </span>";
          }
          $this->Html .= "</div>";
          $this->Html .= "<div style='height: 25px;'>&nbsp;</div>";
          
     }
    
     public function tratarSql(){
         
          $Sql = $this->getSql();
         
          if(substr(trim($Sql), -1) == chr(59)){
               $this->setSql(substr(trim($Sql),0, -1));
          }
     }
    
     public function getCampoQuantidadeRegistros(){
          $QuantidadeRegistros = $this->getQuantidadeRegistros();
          
          $Range = range(5,50,5);
          $Array = array_combine($Range,$Range);
          
          $QuantidadeRegistros = (empty($QuantidadeRegistros)) ? 25 : $QuantidadeRegistros;
		
          $Buffer = "<select name='quantidade_registros' id='quantidade_registros' class='campoPaginacao' onchange='paginacao(1, this.value)'>";
		
          foreach($Array as $Key => $Value){
               if($Key == $QuantidadeRegistros){
                    $Buffer.= '<option value="'.$Key.'" selected="selected">'.$Value.'</option>';
               }else{
                    $Buffer.= '<option value="'.$Key.'">'.$Value.'</option>';
               }
          }		
          return $Buffer.= '</select>';
     }
     
     public function getCampoPaginas(){
          
          $NLinhas             = $this->getNLinhas();
          $QuantidadeRegistros = $this->getQuantidadeRegistros();
          $PaginaAtual         = $this->getPaginaAtual();
          
          $TotalPaginas = ceil($NLinhas / $QuantidadeRegistros);
          
          $Range = range(1,$TotalPaginas);
          $Array = array_combine($Range,$Range);          
          $PaginaAtual = (empty($PaginaAtual))? 1 : $PaginaAtual;
		
          $Buffer = "<select name='pagina_atual' id='pagina_atual' class='campo texto' onchange='paginacao(this.value, $QuantidadeRegistros)'>";
		
          foreach($Array as $Key => $Value){
               if($Key == $PaginaAtual){
                    $Buffer.= '<option value="'.$Key.'" selected="selected">'.$Value.'</option>';
               }else{
                    $Buffer.= '<option value="'.$Key.'">'.$Value.'</option>';
               }
          }		
          return $Buffer.= '</select>';
     }
}