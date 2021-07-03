<template>
  <div class="nprogress-container"></div>
</template>

<script>
import { Component, Vue } from "vue-property-decorator";

@Component
export default class NprogressContainer extends Vue {}
</script>

<style lang="css">
@import "~nprogress/nprogress.css";
</style>
