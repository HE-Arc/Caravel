import Vue from "vue";

Vue.filter("capitalize", function (value: string) {
  if (!value) return "";
  return value.toString().charAt(0).toUpperCase() + value.slice(1);
});

Vue.filter("initials", function (value: string) {
  if (value === undefined) return "";

  const split = value.split(" ");
  let name = "";

  if (split.length > 1) {
    name = split[0].charAt(0) + split[split.length - 1].charAt(0);
  } else {
    name = value.replace(/[^a-zA-Z]/gi, "").substring(0, 2);
  }

  return name.toUpperCase();
});

Vue.filter("limit", function (value: string, size: number) {
  if (!value) return "";
  value = value.toString();

  if (value.length <= size) {
    return value;
  }
  return value.substr(0, size) + "...";
});
