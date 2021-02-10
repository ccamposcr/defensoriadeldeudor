<template>
  <div>
    <ul><li v-bind:key="item.id" v-for="item in users">{{ item.name }}</li></ul>

    <form>
      <div class="form-group">
        <label for="personalID">Cedula</label>
        <input v-model="form.personalID" type="text" class="form-control" id="personalID" placeholder="Cedula">
      </div>
      <div class="form-group">
        <label for="name">Nombre</label>
        <input v-model="form.name" type="text" class="form-control" id="name" placeholder="Nombre">
      </div>
      <div class="form-group">
        <label for="lastName1">Primer Apellido</label>
        <input v-model="form.lastName1" type="text" class="form-control" id="lastName1" placeholder="Primer Apellido">
      </div>
      <div class="form-group">
        <label for="lastName2">Segundo Apellido</label>
        <input v-model="form.lastName2" type="text" class="form-control" id="lastName1" placeholder="Segundo Apellido">
      </div>
      <div class="form-group">
        <label for="phone">Telefono</label>
        <input v-model="form.phone" type="text" class="form-control" id="phone" placeholder="Telefono">
      </div>
      <div class="form-group">
        <label for="email">Email</label>
        <input v-model="form.email" type="email" class="form-control" id="email" placeholder="Email">
      </div>
      <div class="form-group">
        <label for="address">Direccion</label>
        <input v-model="form.address" type="text" class="form-control" id="address" placeholder="Direccion">
      </div>
      <button @click.prevent="addNewClient" type="submit" class="btn btn-primary">Agregar</button>
    </form>
  </div>
</template>

<script>
export default {
  name: 'Client',
  data () {
    return {
      users: [],
      form:{
        personalID:'',
        name: '',
        lastName1: '',
        lastName2: '',
        phone: '',
        email: '',
        address: ''
      }
    }
  },
  created: function(){ // Perfect step to retrieve async data
      this.getUsers();
  },
  methods: {
      getUsers: async function(){
          const url = 'clientes/getAllClients';
          const res = await fetch(url);
          const data = await res.json();
          this.users = data;
      },
      addNewClient: function(){
        this.form[csrf_name] = csrf_hash;
        this.form['role'] = 9;
        this.form['status'] = true;
        const url = 'clientes/addClient';
        console.log(this.form);
        fetch(url, {
          credentials: 'include',
          method: 'POST',
          body: new URLSearchParams(this.form),
          headers:{
            'Content-Type': 'application/x-www-form-urlencoded',
            "X-Requested-With": "XMLHttpRequest"
          }
        }).then(res => res.json())
        .catch(error => console.error('Error:', error))
        .then(response => console.log('Success:', response));
      }
  }
}
</script>

<style lang="scss" scoped>

</style>
