<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
   <h2 class="show-for-small-only"></h2>    
	<br>
   <div class="container">
    <div class="row">
    	<h3> M&aacute;quina de Votaci&oacute;n Elegida para Auditor&iacute;a</h3>
    	
        <?php 
            $fila=$consulta->result();
            $centrovotacion=$fila[0]->codigo_centrovotacion .'-'. $fila[0]->centro_votacion;
            
            if ($consulta_candidatos != null) {
                $candidatos = $consulta_candidatos->result();
            }
            
            if ($consulta_organizacion_politica != null) {
                $organizaciones_politicas = $consulta_organizacion_politica->result();
            }
        ?>

                <div class="large-12 medium-4 columns">
                    <label>Centro de votaci&oacute;n</label>
                    <input type="text" placeholder="" name="centrovotacion" id="centrovotacion" disabled value="<?= $centrovotacion; ?>"/>
                </div>
                <div class="large-4 medium-4 columns">
                    <label>Modelo M&aacute;quina Votaci&oacute;n</label>
                    <input type="text" placeholder="" name="modelomaquina" id="modelomaquina" disabled value="<?= $fila[0]->modelo_maquina; ?>"/>
                </div>
               <div class="large-4 medium-4 columns">
                    <label>N&uacute;mero de mesa</label>
                    <input type="text" placeholder="" name="mesa" id="mesa" disabled value="<?= $fila[0]->mesa; ?>"/>
                </div> 
               <div class="large-4 medium-4 columns">
                    <label>Estatus</label>
                    <input type="text" placeholder="" name="estatus" id="estatus" disabled value="<?= $fila[0]->estatus; ?>"/>
                </div> 
              
              <h3> Auditor&iacute;a</h3>
    			    <h4> - Cargo: Gobernadora o Gobernador del Estado... </h4>
    			<div class="field small-6 columns">
    				<label for="num:">Candidato:</label> 
        				<select id=id_tipo_documento_identidad name="id_tipo_documento_identidad">
        					<option selected="selected" value="">Seleccione</option>
                                  <?php
                                      if (isset($candidatos)) {
                                        foreach ($candidatos as $data) { ?>
                                            	<option value="<?= $data->id ?>"><?= $data->candidato ?></option>
                                            <?php 
                                        }
                                     }
                                 ?>
                   		</select>
    			</div>
    			<div class="field small-6 columns">
    				<label for="num:">Organizaci&oacute;n Pol&iacute;tica:</label> 
       				<select id=id_tipo_documento_identidad name="id_tipo_documento_identidad">
        					<option selected="selected" value="">Seleccione</option>
                                  <?php
                                  if (isset($organizaciones_politicas)) {
                                      foreach ($organizaciones_politicas as $data) { ?>
                                            	<option value="<?= $data->id ?>"><?= $data->organizacion_politica ?></option>
                                            <?php 
                                        }
                                     }
                                 ?>
                   		</select>
    			</div>
    			  <h4> - Cargo: Diputado lista Consejo Legislativo... </h4>
    			    			<div class="field small-6 columns">
    				<label for="num:">Candidato:</label> 
        				<select id=id_tipo_documento_identidad name="id_tipo_documento_identidad">
        					<option selected="selected" value="">Seleccione</option>
                                  <?php
                                      if (isset($candidatos)) {
                                        foreach ($candidatos as $data) { ?>
                                            	<option value="<?= $data->id ?>"><?= $data->candidato ?></option>
                                            <?php 
                                        }
                                     }
                                 ?>
                   		</select>
    			</div>
    			<div class="field small-6 columns">
    				<label for="num:">Organizaci&oacute;n Pol&iacute;tica:</label> 
       				<select id=id_tipo_documento_identidad name="id_tipo_documento_identidad">
        					<option selected="selected" value="">Seleccione</option>
                                  <?php
                                  if (isset($organizaciones_politicas)) {
                                      foreach ($organizaciones_politicas as $data) { ?>
                                            	<option value="<?= $data->id ?>"><?= $data->organizacion_politica ?></option>
                                            <?php 
                                        }
                                     }
                                 ?>
                   		</select>
    			</div>
    		   			
    			<div class="small-1 column right buttonPanel<br>">
        			<input id="btnEnviar" class="button small right" value="Registrar" type="submit" onclick="this.form.action = '<?=base_url()?>index.php/audit/consultada'">
    			</div>
    			
				 <div class="large-12 medium-4 columns">
                    <label>Lista de Votos</label>
                 </div>
                 
                  <div class="large-4 medium-4 columns">
                    <label>Cargo</label>
                    <input type="text" placeholder="" name="cargo" id="cargo" disabled value="<?= $fila[0]->modelo_maquina; ?>"/>
                </div>
               <div class="large-4 medium-4 columns">
                    <label>Candidato</label>
                    <input type="text" placeholder="" name="mesa" id="mesa" disabled value="<?= $fila[0]->mesa; ?>"/>
                </div> 
               <div class="large-4 medium-4 columns">
                    <label>Organizacion Politica</label>
                    <input type="text" placeholder="" name="estatus" id="estatus" disabled value="<?= $fila[0]->estatus; ?>"/>
                </div> 
               
                    
                    <div class="small-12 column text-right buttonPanel">
            	<?php if ($fila[0]->id_estatus_maquina == "6"){?>
            		<input id="btnEnviar" class="button small right" value="Aceptar" type="submit" onclick="this.form.action = '<?=base_url()?>index.php/voting_machine/cancelar'">
            	
            	<?php }else{?>
                    <input id="btnEnviar" class="button small  " value="Finalizar Auditor&iacute;a" type="submit">
                    <input id="btnEnviar" class="button small Secondary " value="Descargar" type="submit" onclick="this.form.action = '<?=base_url()?>index.php/voting_machine/cancelar'">
                <?php }?>
            </div>
            </div>
                </div>