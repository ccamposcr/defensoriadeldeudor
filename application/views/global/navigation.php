<div class="navigation">
   <ul class="nav">
      <li class="nav__item" role="presentation"><a class="btn btn btn-secondary <?php echo (strpos(current_url(), 'inicio') ? ' active' : '') ?>" href="<?php echo base_url(); ?>">Citas</a></li>
      <li class="nav__item" role="presentation"><a class="btn btn btn-secondary <?php echo (strpos(current_url(), 'clientes') ? ' active' : '') ?>" href="<?php echo base_url(); ?>clientes">Clientes - Casos</a></li>
      <li class="nav__item" role="presentation"><a class="btn btn btn-secondary <?php echo (strpos(current_url(), 'financiero') ? ' active' : '') ?>" href="<?php echo base_url(); ?>financiero">Financiero</a></li>
      <?php
         if(in_array("7", json_decode($this->session->userdata('roleAccessList'))))
         {
            echo '<li class="nav__item" role="presentation" ><a class="btn btn btn-secondary'. (strpos(current_url(), 'register') ? ' active' : '') .'" href="'. base_url() . 'register">Crear Usuarios</a></li>';
         }
         if(in_array("9", json_decode($this->session->userdata('roleAccessList'))))
         {
            echo '<li class="nav__item" role="presentation"><a class="btn btn btn-secondary'. (strpos(current_url(), 'clientes') ? ' active' : '') .'" href="'. base_url() . 'clientes?showSystemUsers=true">Editar Usuarios</a></li>';
         }
         if(in_array("8", json_decode($this->session->userdata('roleAccessList'))))
         {
            echo '<li class="nav__item" role="presentation"><a class="btn btn btn-secondary'. (strpos(current_url(), 'administracion') ? ' active' : '') .'" href="'. base_url() . 'administracion">Administrar</a></li>';
         }
         ?>
   </ul>
</div>

