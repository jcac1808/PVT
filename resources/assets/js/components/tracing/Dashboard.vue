<template>
  <v-container fluid class="ma-0 pa-0">
    <v-row>
      <v-col cols="12" class="text-center">
        <v-row>
          <v-col cols="12" class="text-center py-0" style="margin-bottom:10px">
            <v-card class="py-0" color="#151515" dark max-width="100%" max-height="500">
              <v-card-text class="headline font-weight-bold">
                <v-row>
                  <v-col cols="5" class="py-0">
                    <h6  v-show="loan.borrower.length == 1"><v-icon large left style="font-size: 25px;">
                      mdi-shield-account
                      </v-icon>
                    <strong><b style="color:white">PRESTATARIO: </b></strong>
                      {{ $options.filters.fullName(loan.borrower[0], true) }} <br>
                       <b style="color:white">C.I: </b>{{loan.borrower[0].identity_card +' '+loan.borrower[0].city_identity_card.first_shortened}}</h6>
                     <h6 v-show="loan.borrower[0].type=='spouses'" v-if="loan.borrower.length == 1"><v-icon large left style="font-size: 25px;">
                      mdi-account-check-outline
                    </v-icon>
                    <strong ><b style="color:white">TITULAR:  </b></strong>
                      {{ $options.filters.fullName(affiliate, true) }}</h6>


                    <div v-show="loan.borrower.length > 1">
                      <h6><v-icon large left style="font-size: 25px;">
                        mdi-shield-account
                        </v-icon>
                      <strong><b style="color:white">PRESTATARIO:</b></strong> {{ $options.filters.fullName(loan.borrower[0], true) }}
                      <b style="color:white">C.I: </b> {{ loan.borrower[0].identity_card +' '+loan.borrower[0].city_identity_card.first_shortened }}</h6>
                    </div>
                    <div   v-for="(lenders,i) in loan.borrower" :key="i" v-show="loan.borrower.length > 1">
                      <h6 v-if="i > 0"><v-icon large left style="font-size: 25px;">
                      mdi-account-check-outline
                    </v-icon><strong><b style="color:white"  v-if="loan.borrower.length > 1 ">CODEUDOR: </b>{{  $options.filters.fullName( loan.borrower[i],true)}}</strong>
                      <b style="color:white">C.I: </b>{{ loan.borrower[i].identity_card +' '+ loan.borrower[i].city_identity_card.first_shortened}} </h6>
                    </div>
                  </v-col>
                  <v-col cols="7" class="py-0 ">
                    <h6><v-icon large left style="font-size: 25px;">
                    mdi-bank
                    </v-icon>
                    <strong><b style="color:white">MODALIDAD:</b></strong>
                      {{ loan.modality }}</h6>
                    <h6><strong><b style="color:white">MONTO SOLICITADO:</b></strong>
                      {{ loan.amount_approved | moneyString}}  Bs
                    <strong><b style="color:white">MESES PLAZO:</b></strong>
                      {{ loan.loan_term }}</h6>
                  </v-col>
                </v-row>
              </v-card-text>
            </v-card>
          </v-col>
        </v-row>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
export default {
  name: "flow-dashboard",
  props: {
    affiliate: {
      type: Object,
      required: true
    },
    loan: {
      type: Object,
      required: true
    },
  },
  computed: {
    isNew() {
      return this.$route.params.id == "new"
    },
  },
};
</script>