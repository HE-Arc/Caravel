module.exports = {
  preset: "@vue/cli-plugin-unit-jest/presets/typescript-and-babel",
  setupFilesAfterEnv: ["./tests/setup.js"],
  transformIgnorePatterns: ["node_modules/(?!vue-router|@babel|vuetify)"],
};
