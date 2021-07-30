<template>
    <div>

        <!-- LEGAL NOTES -->
        <div v-if="$store.getters.legalCaseNotes(legalCase.legalCaseID)">
            
            <ul class="detail__list">
                <li class="list__option" v-bind:key="legalCaseNote.id" v-for="legalCaseNote in $store.getters.legalCaseNotes(legalCase.legalCaseID)">
                    <p v-if="legalCaseNote.note"><strong>Nota:</strong> {{ legalCaseNote.note }}</p>
                    <p v-if="legalCaseNote.name"><strong>Hecha por:</strong> {{ legalCaseNote.name }} {{ legalCaseNote.lastName1 }} {{ legalCaseNote.lastName2 }}</p>
                    <p v-if="legalCaseNote.date"><strong>Fecha:</strong> {{ legalCaseNote.date }}</p>
                </li>
            </ul>
            <span class="label-danger" v-if="$store.getters.legalCaseNotes(legalCase.legalCaseID) && !$store.getters.legalCaseNotes(legalCase.legalCaseID).length">No hay notas</span>
        </div>
        <!-- LEGAL NOTES END -->

  </div>
</template>

<script>

export default {
  name: 'LegalNotes',
  props: ["legalCase"],
  components: {},
  data () {
    return {
    }
  },
  methods: {
  }
}
</script>

<style lang="scss">
.v-application{
    .detail{
        &__list{
            list-style-type: none;
            padding: 0;
            margin-top: 30px;
            background-color: #e6e5e5;
        }
    }
    .list__option{
        padding: 15px;
        border-bottom: 1px solid gray;
        &:last-child{
            border-bottom: none;
        }
        p{
            margin-bottom: 0;
        }
    }
}
</style>
