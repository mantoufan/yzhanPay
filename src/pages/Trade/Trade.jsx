import LocalAtmIcon from '@material-ui/icons/LocalAtm';

import ListRender from '@components/ConfigRender/ListRender'
import EditRender from '@components/ConfigRender/EditRender'
import CreateRender from '@components/ConfigRender/CreateRender'

const CONFIG = {
  id: {
    disabled: true,
    type: 'text',
  },
  trade_no: {
    type: 'text',
  },
  out_trade_no: {
    type: 'text',
  },
  api_trade_no: {
    type: 'text',
  },
  status: {
    type: 'text',
  },
  user_id: {
    type: 'text',
  },
  app_id: {
    type: 'text',
  },
  app_id: {
    type: 'text',
  },
  subject: {
    type: 'text',
  },
  total_amount: {
    type: 'text',
  },
  timestamp: {
    type: 'text',
  },
  return_url: {
    type: 'text',
  },
  notify_url: {
    type: 'text',
  },
  body: {
    type: 'text',
  },
  notify_status: {
    type: 'text',
  }
}

const PageIcon = LocalAtmIcon
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
