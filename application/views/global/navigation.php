<ul class="nav">
  <li class="nav__item" role="presentation"><a href="<?php echo base_url(); ?>">Inicio</a></li>
  <li class="nav__item" role="presentation"><a href="<?php echo base_url(); ?>clientes">Clientes</a></li>
  <?php
   if(in_array("7", json_decode($this->session->userdata('accessList'))))
   {
      echo '<li class="nav__item" role="presentation"><a href="'. base_url() . 'register">Crear Usuarios</a></li>';
   }
   if(in_array("8", json_decode($this->session->userdata('accessList'))))
   {
      echo '<li class="nav__item" role="presentation"><a href="'. base_url() . 'administracion">Administrar</a></li>';
   }
   ?>
    <li class="nav__item" role="presentation"><a href="<?php echo base_url(); ?>logout">Desconectarse</a></li>
</ul>

