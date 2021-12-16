import PersonIcon from '@material-ui/icons/Person'

import ListRender from '@components/ConfigRender/ListRender'
import EditRender from '@components/ConfigRender/EditRender'
import CreateRender from '@components/ConfigRender/CreateRender'

const CONFIG = {
  id: {
    disabled: true,
    type: 'text',
  },
  name: {
    type: 'text',
  },
  permission: {
    type: 'json',
    jsonString: true,
    reactJsonOptions: {
      collapsed: true,
    }
  },
}

const PageIcon = PersonIcon
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
