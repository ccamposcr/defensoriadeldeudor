<template>
    <div>
        <!-- LEGAL CASES -->
        <div v-if="$store.getters.legalCases(user.id)">
        <ul class="box">
            <li class="box__detail" v-bind:key="legalCase.id" v-for="legalCase in $store.getters.legalCases(user.id)">

                <legal-case-detail :legal-case="legalCase"></legal-case-detail>

                <div class="detail__options">
                    <b-button v-if="$emit('checkAccessList', 'editar caso')" @click="$emit('fillEditLegalCaseForm', legalCase.legalCaseID, user.id)" variant="info">Editar caso</b-button>
                    <b-button :disabled="$store.getters.showLoader" @click="$emit('renderLegalCaseNotes', legalCase.legalCaseID)" variant="primary">Ver notas</b-button>
                    <!--<b-button :disabled="$store.getters.showLoader" @click="renderPaymentDates(user.id)" variant="primary">Ver fechas de pago</b-button>-->

                    <b-form-group v-if="legalCase.inUse == '1' && $emit('checkAccessList','administrar')" label="Caso bloqueado -> *Precaución puede estar siendo editado por algún usuario">
                      <b-button @click.prevent="$emit('unblockLegalCase', legalCase.legalCaseID, user.id)" variant="danger">Desbloquear</b-button>
                    </b-form-group>
                </div>

                <legal-notes :legal-case="legalCase"></legal-notes>
            
            </li>
        </ul>
        </div>
        <span class="label-danger" v-if="$store.getters.legalCases(user.id) && !$store.getters.legalCases(user.id).length">No hay casos</span>
        <!-- END LEGAL CASES -->

  </div>
</template>

<script>

import LegalCaseDetail from './LegalCaseDetail.vue';
import legalNotes from './LegalNotes.vue';

export default {
  name: 'LegalCases',
  props: ["user"],
  components: {legalNotes, LegalCaseDetail},
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
    .box{
      list-style-type: none;
      padding: 0;
      display: flex;
      flex-wrap: wrap;
      background-color: #fbfbfb;
      margin-top: 30px;
      
      &__name{
        font-size: 20px;
        font-weight: bold;
      }
      &__options{
        margin-top: 30px;
      }
    }
    .box{
      &__detail{
        padding: 15px;
        flex: 1 1 50%;
        border-bottom: 1px solid gray;

        &:nth-child(odd){
          border-right: 1px solid gray;
        }

        &:last-child{
          border-right: none;
        }
      }
    }
    .detail{
      &__options{
        margin-top: 30px;
      }
    }
}
</style>
