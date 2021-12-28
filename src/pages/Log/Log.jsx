import AssignmentIcon from '@material-ui/icons/Assignment'

import ListRender from '@components/ConfigRender/ListRender'
import EditRender from '@components/ConfigRender/EditRender'
import CreateRender from '@components/ConfigRender/CreateRender'

const CONFIG = {
  id: {
    disabled: true,
    type: 'text'
  },
  path: {
    type: 'text'
  },
  method: {
    type: 'text'
  },
  controller: {
    type: 'text'
  },
  action: {
    type: 'text'
  },
  payload: {
    type: 'text'
  },
  user_id: {
    type: 'text'
  },
  add_time: {
    type: 'text'
  }
}

const PageIcon = AssignmentIcon
const PageList = (props) => <ListRender config={CONFIG} {...props} />

const PageEdit = (props) => <EditRender config={CONFIG} {...props} />
const PageCreate = (props) => <CreateRender config={CONFIG} {...props} />

export default {
  PageIcon,
  PageList,
  PageEdit,
  PageCreate
}
