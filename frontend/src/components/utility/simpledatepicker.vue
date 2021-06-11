<template>
  <v-menu
    v-model="menu"
    :close-on-content-click="false"
    :nudge-right="40"
    transition="scale-transition"
    offset-y
    min-width="auto"
  >
    <template v-slot:activator="{ on }">
      <v-text-field
        prepend-inner-icon="mdi-calendar"
        readonly
        v-bind="$attrs"
        v-on="on"
        v-model="date"
      ></v-text-field>
    </template>
    <v-date-picker
      :min="nowDate"
      v-model="date"
      @input="menu = false"
    ></v-date-picker>
  </v-menu>
</template>

<script lang="ts">
import moment from "moment";
import { Vue, Component } from "vue-property-decorator";

@Component
export default class SimpleDatePicker extends Vue {
  menu = false;
  date = moment().toISOString().substr(0, 10);
  nowDate = moment().toISOString().slice(0, 10);
}
</script>
