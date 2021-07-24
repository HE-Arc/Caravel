<template>
  <div>
    <slot v-bind:items="visibleItems"></slot>
    <div class="text-center mt-4">
      <v-pagination
        v-show="pages > 1"
        v-model="page"
        :length="pages"
        circle
        v-bind="$attrs"
        v-on="$listeners"
      ></v-pagination>
    </div>
  </div>
</template>

<script lang="ts">
import { Component, Vue, Prop, Watch } from "vue-property-decorator";

@Component
export default class Paginate extends Vue {
  @Prop() items!: unknown[];
  @Prop({ default: 10, validator: (val) => val > 0 }) perPage!: number;
  page = 1;

  get pages(): number {
    return Math.ceil(this.items.length / this.perPage);
  }

  get visibleItems(): unknown[] {
    return this.items.slice(
      (this.page - 1) * this.perPage,
      this.page * this.perPage
    );
  }

  @Watch("items")
  updatePage(): void {
    this.page = 1;
  }
}
</script>
