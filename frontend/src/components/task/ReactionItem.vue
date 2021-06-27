<template>
  <v-chip
    :outlined="!isActive"
    class="mr-2 mb-1"
    @click="reacts"
    :color="isActive ? 'primary' : 'default'"
  >
    {{ $t(`reactions.types.${type}.icon`) }}
    {{ $t(`reactions.types.${type}.label`) }}
  </v-chip>
</template>

<script lang="ts">
import { Component, Vue, Prop, Emit } from "vue-property-decorator";

@Component
export default class Reactions extends Vue {
  @Prop() type!: number;
  @Prop({ default: false }) isActive!: boolean;

  @Emit("click")
  reacts() {
    return this.type;
  }
}
</script>
