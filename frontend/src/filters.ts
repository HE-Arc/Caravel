import Vue from "vue";

Vue.filter("capitalize", function (value: string) {
  if (!value) return "";
  return value.toString().charAt(0).toUpperCase() + value.slice(1);
});
