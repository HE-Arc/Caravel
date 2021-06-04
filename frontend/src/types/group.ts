export interface Group {
  id: Number;
  name: string;
  description: string;
  picture: string;
  isPrivate: Boolean;
  user_id: Number;
  status: Number | undefined;
}
