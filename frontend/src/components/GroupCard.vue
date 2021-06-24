<template>
  <v-card>
    <div class="d-flex flex-no-wrap justify-space-between">
      <div>
        <v-card-title class="text-h5" v-text="group.name"></v-card-title>

        <v-card-subtitle
          class="text-justify"
          v-text="group.description"
        ></v-card-subtitle>

        <v-card-actions class="mb-2">
          <v-btn
            text
            color="primary"
            :to="{ name: 'Group', params: { group_id: group.id } }"
            >OPEN</v-btn
          >
        </v-card-actions>
      </div>

      <v-avatar class="ma-3" size="125" tile v-if="group.picture">
        <v-img :src="group.picture"></v-img>
      </v-avatar>
    </div>
  </v-card>
</template>

<script lang="ts">
import { Group } from "@/types/Group";
import { Component, Vue, Prop } from "vue-property-decorator";

@Component
export default class GroupCard extends Vue {
  @Prop() group!: Group;
}
</script>
