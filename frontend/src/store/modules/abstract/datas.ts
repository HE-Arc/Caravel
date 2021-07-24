import { Data } from "@/types/data";
import { VuexModule, Mutation, Action } from "vuex-module-decorators";
import Vue from "vue";

// inherance broken https://github.com/championswimmer/vuex-module-decorators/issues/125 wait for vue 3

export default abstract class DataModule<T extends Data> extends VuexModule {
  _items: T[] = [];
  _status = "";
  protected name = "";

  get items(): T[] {
    return this._items;
  }

  get status(): string {
    return this._status;
  }

  @Mutation
  protected ERROR(): void {
    this._status = "error";
  }

  @Mutation
  protected REQUEST(): void {
    this._status = "loading";
  }

  @Mutation
  protected FINISH(): void {
    this._status = "loaded";
  }

  @Mutation
  protected LOAD_ITEMS(items: T[]): void {
    this._items = items;
    this._status = "loaded";
  }

  @Mutation
  protected UPSERT_ITEM(data: T): void {
    const index = this._items.findIndex((item) => item.id == data.id);
    if (index === -1) {
      this._items.push(data);
    } else {
      Vue.set(this._items, index, data);
    }
  }

  @Mutation
  protected REMOVE_ITEM(data: T): void {
    const index = this._items.findIndex((item) => item.id == data.id);
    if (index !== -1) {
      Vue.delete(this._items, index);
    }
  }

  @Action
  load(items: T[]): void {
    this.LOAD_ITEMS(items);
  }

  //TODO refactor actions for a more generic version
}
