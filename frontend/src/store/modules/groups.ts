import { RootState } from "@/types/RootState";
import { GroupState } from "@/types/GroupState";
import { GetterTree, Module } from "vuex";
import { Group } from "@/types/group";

const state: GroupState = {
    groups: [],
    status: "",
    selected: undefined,
}

const getters: GetterTree<GroupState, RootState> = {
    groups: (state: GroupState): Group[] => state.groups,
    groupStatus: (state: GroupState): string => state.status,
    groupSelected: (state: GroupState): Group | undefined => state.selected
}

export enum GroupActions {
    LOAD = "group_load",
    SELECT = "group_select",
}

export enum GroupMutations {
    REQUEST = "group_request",
    SUCCESS = "group_success",
}

export const groups: Module<GroupState, RootState> = {
    state,
    getters,
}