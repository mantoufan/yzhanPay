import MenuIcon from '@material-ui/icons/Menu'

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
  path: {
    type: 'text',
  }
}

const PageIcon = MenuIcon
const PageList = (props) => {
  console.log('props', props)
  return (
  <ListRender sort={{ field: 'id', order: 'DESC' }} config={CONFIG} {...props} />
  )
}
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
