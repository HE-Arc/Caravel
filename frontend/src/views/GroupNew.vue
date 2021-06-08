<template>
  <v-container class="mt-5">
    <v-row no-gutters>
      <v-col cols="12" md="10" offset-md="1">
        <h1 class="mb-5">{{ $t("groups.create.title") }}</h1>
        <v-stepper v-model="e6" vertical flat>
          <v-stepper-step
            :complete="e6 > 1"
            step="1"
            editable
            :rules="[() => group.name != undefined, () => group.name != '']"
          >
            {{ $t("groups.create.step1.name") }}
            <small>{{ $t("groups.create.step1.example") }}</small>
          </v-stepper-step>

          <v-stepper-content step="1">
            <v-card class="mb-6" flat>
              <v-card-text>
                <v-text-field
                  :label="$t('groups.create.step1.label')"
                  :placeholder="$t('groups.create.step1.placeholder')"
                  v-model="group.name"
                  :messages="$t('groups.create.step1.help')"
                  autocomplete="off"
                >
                </v-text-field>
              </v-card-text>
              <v-card-text>
                <v-avatar v-if="group.picture" class="mb-5">
                  <img :src="imageUrl" :alt="group.name" />
                </v-avatar>
                <v-file-input
                  :label="$t('groups.create.step1.image')"
                  filled
                  prepend-icon="mdi-camera"
                  v-model="group.picture"
                ></v-file-input>
              </v-card-text>
            </v-card>
            <v-btn
              color="primary"
              @click="e6 = 2"
              :disabled="group.name == undefined || group.name == ''"
            >
              {{ $t("global.resume") }}
            </v-btn>
          </v-stepper-content>

          <v-stepper-step :complete="e6 > 2" step="2" editable>
            {{ $t("groups.create.step2.name") }}
            <small> {{ $t("groups.create.step2.example") }} </small>
          </v-stepper-step>

          <v-stepper-content step="2">
            <v-card class="mb-12" flat>
              <v-card-text>
                <v-textarea
                  :label="$t('groups.create.step2.label')"
                  :placeholder="$t('groups.create.step2.placeholder')"
                  v-model="group.description"
                  :messages="$t('groups.create.step2.help')"
                  autocomplete="off"
                >
                </v-textarea>
              </v-card-text>
            </v-card>
            <v-btn color="primary" @click="e6 = 3">
              {{ $t("global.resume") }}
            </v-btn>
            <v-btn text @click="e6--"> {{ $t("global.back") }} </v-btn>
          </v-stepper-content>

          <v-stepper-step
            :complete="e6 > 3"
            step="3"
            :rules="[() => group.isPrivate != undefined]"
            editable
          >
            {{ $t("groups.create.step3.name") }}
          </v-stepper-step>

          <v-stepper-content step="3">
            <v-container class="mb-6">
              <v-row>
                <v-col cols="12" md="6">
                  <v-card
                    :elevation="group.isPrivate === false ? '8' : '1'"
                    :ripple="true"
                    @click="group.isPrivate = false"
                    style="margin: auto"
                  >
                    <v-card-text class="text-center pa-10">
                      <v-icon x-large>{{
                        $t("groups.create.step3.type2.icon")
                      }}</v-icon>
                      <h3 class="mb-2">
                        {{ $t("groups.create.step3.type2.title") }}
                      </h3>
                      <small>{{
                        $t("groups.create.step3.type2.subtitle")
                      }}</small>
                    </v-card-text>
                  </v-card>
                </v-col>
                <v-col cols="12" md="6">
                  <v-card
                    :elevation="group.isPrivate === true ? '8' : '1'"
                    :ripple="true"
                    @click="group.isPrivate = true"
                    style="margin: auto"
                  >
                    <v-card-text class="text-center pa-10">
                      <v-icon x-large>{{
                        $t("groups.create.step3.type1.icon")
                      }}</v-icon>
                      <h3 class="mb-2">
                        {{ $t("groups.create.step3.type1.title") }}
                      </h3>
                      <small>{{
                        $t("groups.create.step3.type1.subtitle")
                      }}</small>
                    </v-card-text>
                  </v-card>
                </v-col>
              </v-row>
            </v-container>
            <v-btn
              color="primary"
              @click="e6 = 4"
              :disabled="group.isPrivate == undefined"
            >
              {{ $t("global.resume") }}
            </v-btn>
            <v-btn text @click="e6--"> {{ $t("global.back") }} </v-btn>
          </v-stepper-content>

          <v-stepper-step step="4">
            {{ $t("groups.create.step4.name") }}
          </v-stepper-step>
          <v-stepper-content step="4">
            <v-card class="mb-12" dark>
              <v-card-title class="d-flex">
                <v-avatar v-if="group.picture" class="mr-3">
                  <img :src="imageUrl" :alt="group.name" />
                </v-avatar>
                <span class="pa">
                  {{ group.name }}
                </span>
                <v-icon medium v-if="group.isPrivate === false">{{
                  $t("groups.create.step3.type2.icon")
                }}</v-icon>
                <v-icon medium v-else>{{
                  $t("groups.create.step3.type1.icon")
                }}</v-icon>
              </v-card-title>
              <v-card-text>
                {{ group.description }}
              </v-card-text>
            </v-card>
            <v-btn color="success" @click="sendForm">
              {{ $t("global.finish") }}
            </v-btn>
            <v-btn text @click="e6--"> {{ $t("global.back") }} </v-btn>
          </v-stepper-content>
        </v-stepper>
      </v-col>
    </v-row>
  </v-container>
</template>

<script lang="ts">
import { GroupForm } from "@/types/helpers";
import Vue from "vue";
import Component from "vue-class-component";
import axios from "axios";
import { Group } from "@/types/group";

@Component({
  components: {},
})
export default class GroupNew extends Vue {
  e6 = 1;
  group: GroupForm = {
    id: -1,
    name: "",
    description: "",
    picture: undefined,
    isPrivate: undefined,
  };

  get imageUrl(): string | undefined {
    if (!this.group.picture) return "";
    return URL.createObjectURL(this.group.picture);
  }

  mounted(): void {
    this.group.name = (this.$route.query.text as string) ?? "";
  }

  sendForm(): void {
    const formData = new FormData();
    for (const [key, value] of Object.entries(this.group)) {
      if (value) formData.append(key, value);
    }

    axios
      .post(process.env.VUE_APP_API_BASE_URL + "groups", formData)
      .then((response) => {
        const group: Group = response.data;
        this.$router.push({
          name: "Group",
          params: { group_id: group.id },
        });
        this.$toast.success(this.$t("groups.create.success").toString());
      })
      .catch((err) => {
        this.$toast.error(err.response.data.message);
      });
  }
}
</script>
