<template>
  <div>
    <gantt v-if="isLoaded" />
  </div>
</template>

<script lang="ts">
import { Vue } from "vue-property-decorator";
import Component from "vue-class-component";
import Gantt from "@/components/Gantt.vue";
import taskModule from "@/store/modules/tasks";

@Component({
  components: { Gantt },
})
export default class Timeline extends Vue {
  get isLoaded(): boolean {
    return taskModule.status == "loaded";
  }
}
</script>
