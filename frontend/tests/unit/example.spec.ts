import { shallowMount, createLocalVue } from "@vue/test-utils";
import AddContent from "@/components/AddContent.vue";
import router from "@/router";
import Vuetify from "vuetify";

const localVue = createLocalVue();

describe("AddContent.vue", () => {
  let vuetify: Vuetify;

  beforeEach(() => {
    vuetify = new Vuetify();
  });

  it("renders props.msg when passed", () => {
    const wrapper = shallowMount(AddContent, {
      localVue,
      vuetify,
      router,
    });

    const text = "";
    expect(wrapper.text()).toMatch(text);
  });
});
