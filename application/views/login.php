
<div class="container">
   
   <h3>Ingreso</h3>
   
   <div class="panel panel-default">
      <div class="panel-body">
         <?php
            if($this->session->flashdata('message'))
            {
                  echo '
                  <div class="alert alert-success">
                     '.$this->session->flashdata("message").'
                  </div>
                  ';
            }
            ?>
         <?php
            $attributes = array('role' => 'form', 'method' => 'post');
            echo form_open('login/validation', $attributes);
         ?>
            <div class="form-group">
               <label>Ingrese su Cédula</label>
               <input type="text" name="personalID" class="form-control" value="<?php echo set_value('personalID'); ?>" />
               <span class="text-danger"><?php echo form_error('personalID'); ?></span>
            </div>

            <div class="form-group">
               <label>Ingrese su Contraseña</label>
               <input type="password" name="password" class="form-control" value="<?php echo set_value('password'); ?>" />
               <span class="text-danger"><?php echo form_error('password'); ?></span>
            </div>
            
            <div class="form-group">
               <input type="submit" name="login" value="Login" class="btn btn-primary" />
               <!--<a href="<?php echo base_url(); ?>register">Registrarse</a>-->
            </div>
         </form>
      </div>
   </div>
</div>