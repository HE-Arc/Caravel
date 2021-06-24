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
            {{ $t("groups.create.name.name") }}
            <small>{{ $t("groups.create.name.example") }}</small>
          </v-stepper-step>

          <v-stepper-content step="1">
            <v-card class="mb-6" flat>
              <v-card-text>
                <v-text-field
                  :label="$t('groups.create.name.label')"
                  :placeholder="$t('groups.create.name.placeholder')"
                  v-model="group.name"
                  :messages="$t('groups.create.name.help')"
                  autocomplete="off"
                  :error-messages="errors.name"
                >
                </v-text-field>
              </v-card-text>
              <v-card-text>
                <v-avatar v-if="group.picture" class="mb-5">
                  <img :src="imageUrl" :alt="group.name" />
                </v-avatar>
                <v-file-input
                  :label="$t('groups.create.name.image')"
                  filled
                  prepend-icon="mdi-camera"
                  v-model="group.picture"
                  :error-messages="errors.picture"
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
            {{ $t("groups.create.description.name") }}
            <small> {{ $t("groups.create.description.example") }} </small>
          </v-stepper-step>

          <v-stepper-content step="2">
            <v-card class="mb-12" flat>
              <v-card-text>
                <v-textarea
                  :label="$t('groups.create.description.label')"
                  :placeholder="$t('groups.create.description.placeholder')"
                  v-model="group.description"
                  :messages="$t('groups.create.description.help')"
                  autocomplete="off"
                  :error-messages="errors.description"
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
            {{ $t("groups.create.type.name") }}
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
                        $t("groups.create.type.type2.icon")
                      }}</v-icon>
                      <h3 class="mb-2">
                        {{ $t("groups.create.type.type2.title") }}
                        <v-icon v-if="group.isPrivate === false" color="success"
                          >mdi-check</v-icon
                        >
                      </h3>
                      <small>{{
                        $t("groups.create.type.type2.subtitle")
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
                        $t("groups.create.type.type1.icon")
                      }}</v-icon>
                      <h3 class="mb-2">
                        {{ $t("groups.create.type.type1.title") }}
                        <v-icon v-if="group.isPrivate" color="success"
                          >mdi-check</v-icon
                        >
                      </h3>
                      <small>{{
                        $t("groups.create.type.type1.subtitle")
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
            {{ $t("groups.create.resume") }}
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
                  $t("groups.create.type.type2.icon")
                }}</v-icon>
                <v-icon medium v-else>{{
                  $t("groups.create.type.type1.icon")
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
import groupModule from "@/store/modules/groups";
import { Group } from "@/types/Group";

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
  errors = {};

  get imageUrl(): string | undefined {
    if (!this.group.picture) return "";
    return URL.createObjectURL(this.group.picture);
  }

  mounted(): void {
    this.group.name = (this.$route.query.text as string) ?? "";
  }

  async sendForm(): Promise<void> {
    const formData = new FormData();
    for (const [key, value] of Object.entries(this.group)) {
      if (value) formData.append(key, value);
    }

    try {
      this.errors = {};
      const group: Group = await groupModule.add(formData);
      this.$router.push({
        name: "Group",
        params: { group_id: group.id },
      });
      this.$toast.success(this.$t("groups.create.success").toString());
    } catch (err) {
      this.errors = err.response.data.errors;
      this.$toast.error(err.response.data.message);
    }
  }
}
</script>
