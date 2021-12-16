import WidgetsIcon from '@material-ui/icons/Widgets'

import ListRender from '@components/ConfigRender/ListRender'
import EditRender from '@components/ConfigRender/EditRender'
import CreateRender from '@components/ConfigRender/CreateRender'

const CONFIG = {
  id: {
    disabled: true,
    type: 'text',
  },
  display_name: {
    type: 'text',
  },
  name: {
    type: 'text',
  },
  payment: {
    type: 'text',
  },
  input: {
    type: 'json',
    jsonString: true,
    reactJsonOptions: {
      collapsed: true,
    }
  },
  author: {
    type: 'text',
  },
  link: {
    type: 'text',
  }
}

const PageIcon = WidgetsIcon
const PageList = (props) => (
  <ListRender config={CONFIG} {...props} />
)

const PageEdit = (props) => (
  <EditRender config={CONFIG} {...props} />
)
const PageCreate = (props) => (
  <CreateRender config={CONFIG} {...props} />
)

export default {
  PageIcon,
  PageList,
  PageEdit,
  PageCreate
}
