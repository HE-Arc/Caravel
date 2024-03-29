import store from "@/store";
import { GroupStatus } from "@/types/helpers";
import { Member } from "@/types/member";
import axios, { AxiosResponse } from "axios";
import {
  VuexModule,
  Module,
  Mutation,
  Action,
  getModule,
} from "vuex-module-decorators";
import Vue from "vue";

interface payloadRemove {
  groupId: string;
  member: Member;
}

interface payloadChange {
  groupId: string;
  member: Member;
  status: number;
}

@Module({
  namespaced: true,
  dynamic: true,
  store,
  name: "members",
})
class MemberModule extends VuexModule {
  _items: Member[] = [];
  _status = "";
  protected name = "";

  get pending(): Member[] {
    if (!this._items) return [];
    return this._items.filter((item) => item.status == GroupStatus.PENDING);
  }

  get refused(): Member[] {
    if (!this._items) return [];
    return this._items.filter((item) => item.status == GroupStatus.REFUSED);
  }

  get accepted(): Member[] {
    if (!this._items) return [];
    return this._items.filter((item) => item.status == GroupStatus.ACCEPTED);
  }

  get getMember() {
    return (id: string): Member | undefined =>
      this._items.find((item) => item.id == id);
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
  protected LOAD_ITEMS(items: Member[]): void {
    this._items = items;
    this._status = "loaded";
  }

  @Mutation
  protected UPSERT_ITEM(data: Member): void {
    const index = this._items.findIndex((item) => item.id == data.id);
    if (index === -1) {
      this._items.push(data);
    } else {
      Vue.set(this._items, index, data);
    }
  }

  @Mutation
  protected REMOVE_ITEM(data: Member): void {
    const index = this._items.findIndex((item) => item.id == data.id);
    if (index !== -1) {
      Vue.delete(this._items, index);
      this._status = "delete";
    }
  }

  /**
   * Change the group status of the member, state can be found in under GroupState
   * @param param0 payload data to send
   * @returns API's response
   */
  @Action
  changeStatus({ groupId, member, status }: payloadChange) {
    return new Promise<AxiosResponse>((resolve, reject) => {
      this.REQUEST();
      axios({
        url: process.env.VUE_APP_API_BASE_URL + `groups/${groupId}/members`,
        method: "PATCH",
        data: {
          user_id: member.id,
          status_id: status,
        },
      })
        .then((response) => {
          member.status = status;
          this.UPSERT_ITEM(member);
          resolve(response);
        })
        .catch((err) => {
          this.ERROR();
          reject(err);
        });
    });
  }

  /**
   * Remove the member of from the group
   * @param param0 payload data to send
   * @returns API's Response
   */
  @Action
  removeMember({ groupId, member }: payloadRemove) {
    return new Promise<AxiosResponse>((resolve, reject) => {
      this.REQUEST();
      axios({
        url: process.env.VUE_APP_API_BASE_URL + `groups/${groupId}/members`,
        method: "DELETE",
        data: {
          user_id: member.id,
        },
      })
        .then((response) => {
          this.REMOVE_ITEM(member);
          resolve(response);
        })
        .catch((err) => {
          this.ERROR();
          reject(err);
        });
    });
  }

  /**
   * Load the list of users in module
   * @param items list of users
   */
  @Action
  load(items: Member[]): void {
    this.LOAD_ITEMS(items);
  }
}

const instance = getModule(MemberModule);

export default instance;
