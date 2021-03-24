<ul class="nav">
  <li class="nav__item" role="presentation"><a href="<?php echo base_url(); ?>">Inicio</a></li>
  <li class="nav__item" role="presentation"><a href="<?php echo base_url(); ?>clientes">Clientes</a></li>
  <li class="nav__item" role="presentation"><a href="<?php echo base_url(); ?>logout">Desconectarse</a></li>
  <?php
   if($this->session->userdata('roleID') == '1')
   {
      echo '<li class="nav__item" role="presentation"><a href="'. base_url() . 'register">Crear Usuarios</a></li>';
      echo '<li class="nav__item" role="presentation"><a href="'. base_url() . 'administracion">Administrar</a></li>';
   }
   ?>
</ul>

