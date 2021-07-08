<template>
  <div>
    <gantt />
  </div>
</template>

<script lang="ts">
import { Vue } from "vue-property-decorator";
import Component from "vue-class-component";
import Gantt from "@/components/Gantt.vue";

@Component({
  components: { Gantt },
})
export default class Timeline extends Vue {}
</script>
