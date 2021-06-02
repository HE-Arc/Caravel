namespace Types {
  export interface User {
    id: Number;
    name: string;
    email: string;
    timezone: string;
    picture: string;
    isLDAP: boolean;
    isTeacher: boolean;
    groups_available: Group[];
  }
}
