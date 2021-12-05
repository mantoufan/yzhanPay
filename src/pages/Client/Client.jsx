import React from 'react'
import DevicesIcon from '@material-ui/icons/Devices'
import {
  List,
  Datagrid,
  Edit,
  Create,
  SimpleForm,
  TextField,
  EditButton,
  TextInput
} from 'react-admin'

const PageIcon = DevicesIcon
const PageList = (props) => (
  <List {...props}>
    <Datagrid>
      <TextField source="id" />
      <TextField source="name" />
      <EditButton />
    </Datagrid>
  </List>
)
const PageEdit = (props) => (
  <Edit {...props}>
    <SimpleForm>
      <TextInput disabled source="id" />
      <TextInput source="name" />
      <TextInput source="password" />
    </SimpleForm>
  </Edit>
)
const PageCreate = (props) => (
  <Create {...props}>
    <SimpleForm>
      <TextInput source="name" />
      <TextInput source="password" />
    </SimpleForm>
  </Create>
)

export default {
  PageIcon,
  PageList,
  PageEdit,
  PageCreate
}
