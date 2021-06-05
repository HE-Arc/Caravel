<template>
  <v-app>
    <v-main>
      <v-container class="mt-5">
        <v-row no-gutters>
          <v-col cols="12" md="10" offset-md="1">
            <h1 class="mb-5">{{ $t("groups.create.title") }}</h1>
            <v-stepper v-model="e6" vertical flat>
              <v-stepper-step :complete="e6 > 1" step="1">
                {{ $t("groups.create.step1.name") }}
                <small>{{ $t("groups.create.step1.example") }}</small>
              </v-stepper-step>

              <v-stepper-content step="1">
                <v-card class="mb-12" flat>
                  <v-card-text>
                    <v-text-field
                      :label="$t('groups.create.step1.label')"
                      :placeholder="$t('groups.create.step1.placeholder')"
                      v-model="group.name"
                      :messages="$t('groups.create.step1.help')"
                      autocomplete="off"
                    >
                      Le nom de votre groupe search
                    </v-text-field>
                  </v-card-text>
                </v-card>
                <v-btn color="primary" @click="e6 = 2"> Continue </v-btn>
                <v-btn text> Cancel </v-btn>
              </v-stepper-content>

              <v-stepper-step :complete="e6 > 2" step="2">
                Configure analytics for this app
              </v-stepper-step>

              <v-stepper-content step="2">
                <v-card
                  color="grey lighten-1"
                  class="mb-12"
                  height="200px"
                ></v-card>
                <v-btn color="primary" @click="e6 = 3"> Continue </v-btn>
                <v-btn text> Cancel </v-btn>
              </v-stepper-content>

              <v-stepper-step :complete="e6 > 3" step="3">
                Select an ad format and name ad unit
              </v-stepper-step>

              <v-stepper-content step="3">
                <v-card
                  color="grey lighten-1"
                  class="mb-12"
                  height="200px"
                ></v-card>
                <v-btn color="primary" @click="e6 = 4"> Continue </v-btn>
                <v-btn text> Cancel </v-btn>
              </v-stepper-content>

              <v-stepper-step step="4">
                View setup instructions
              </v-stepper-step>
              <v-stepper-content step="4">
                <v-card
                  color="grey lighten-1"
                  class="mb-12"
                  height="200px"
                ></v-card>
                <v-btn color="success" @click="e6 = 1"> Continue </v-btn>
                <v-btn text> Cancel </v-btn>
              </v-stepper-content>
            </v-stepper>
          </v-col>
        </v-row>
      </v-container>
    </v-main>
  </v-app>
</template>

<script lang="ts">
import { GroupForm } from "@/types/helpers";
import Vue from "vue";
import Component from "vue-class-component";

@Component({
  components: {},
})
export default class GroupNew extends Vue {
  e6 = 1;
  group: GroupForm = {
    id: -1,
    name: "",
    description: "",
    picture: "",
    isPrivate: false,
  };

  mounted(): void {
    this.group.name = (this.$route.query.text as string) ?? "";
  }
}
</script>
