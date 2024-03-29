import PaymentIcon from '@material-ui/icons/Payment'

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
  }
}

const PageIcon = PaymentIcon
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
