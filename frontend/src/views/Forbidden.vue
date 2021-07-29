<template>
  <div class="text-center">
    <v-container>
      <v-layout row wrap>
        <v-flex xs12>
          <v-card height="400" color="transparent" flat>
            <div class="display-3 mt-15 mb-7">{{ $t("403.title") }}</div>
            <div class="grey--text lighten-5 mb-10">
              {{ $t("403.subtitle-1") }}
            </div>
            <div class="paragraph-text mt-2">
              {{ $t("403.subtitle-2") }}
            </div>
          </v-card>
        </v-flex>
      </v-layout>
    </v-container>
  </div>
</template>

<script lang="ts">
import { Vue, Component } from "vue-property-decorator";

@Component
export default class Forbidden extends Vue {}
</script>
