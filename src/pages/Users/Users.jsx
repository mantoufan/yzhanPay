import React from 'react'
import PersonIcon from '@material-ui/icons/Person'
import {
  List,
  Datagrid,
  Edit,
  Create,
  SimpleForm,
  DateField,
  TextField,
  EditButton,
  TextInput,
  DateInput
} from 'react-admin'

const UserIcon = PersonIcon
const UserList = (props) => (
  <List {...props}>
    <Datagrid>
      <TextField source="id" />
      <TextField source="name" />
    </Datagrid>
  </List>
)
const UserEdit = (props) => (
  <Edit title={<PostTitle />} {...props}>
    <SimpleForm>
      <TextInput disabled source="id" />
      <TextInput source="name" />
      <TextInput source="permission" options={{ multiline: true }} />
    </SimpleForm>
  </Edit>
)
const UserCreate = (props) => (
  <Create title="Create a Post" {...props}>
    <SimpleForm>
      <TextInput source="name" />
      <TextInput source="permission" options={{ multiline: true }} />
    </SimpleForm>
  </Create>
)

export default {
  UserIcon,
  UserList,
  UserEdit,
  UserCreate
}
