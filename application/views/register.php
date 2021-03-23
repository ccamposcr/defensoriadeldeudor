
<div class="container">
   
   <h3>Registro</h3>

   <div class="panel panel-default">
      <div class="panel-body">
         <?php
            $attributes = array('role' => 'form', 'method' => 'post');
            echo form_open('register/validation', $attributes);
         ?>
            <div class="form-group">
               <label>Ingrese su nombre</label>
               <input type="text" name="user_name" class="form-control" value="<?php echo set_value('user_name'); ?>" />
               <span class="text-danger"><?php echo form_error('user_name'); ?></span>
            </div>
            <div class="form-group">
               <label>Ingrese su cédula</label>
               <input type="text" name="user_personalID" class="form-control" value="<?php echo set_value('user_personalID'); ?>" />
               <span class="text-danger"><?php echo form_error('user_personalID'); ?></span>
            </div>
            <div class="form-group">
               <label>Ingrese su correo electrónico</label>
               <input type="email" name="user_email" class="form-control" value="<?php echo set_value('user_email'); ?>" />
               <span class="text-danger"><?php echo form_error('user_email'); ?></span>
            </div>
            <div class="form-group">
               <label>Ingrese una contraseña</label>
               <input type="password" name="user_password" class="form-control" value="<?php echo set_value('user_password'); ?>" />
               <span class="text-danger"><?php echo form_error('user_password'); ?></span>
            </div>
            <div class="form-group">
               <input type="submit" name="register" value="Register" class="btn btn-info" />
            </div>
         </form>
      </div>
   </div>
</div>
 