import { useEffect } from 'react'

// set document.title
function CustomDocumentTitle() {
  useEffect(() => {
    const title = 'Payment Gateway'
    function titleCase(str) {
      return str.slice(0, 1).toUpperCase() + str.slice(1).toLowerCase();
    }
    document.title = document.location.pathname.split('/').reverse().map(b => b ? titleCase(b) : title).join(' | ')
  }, [])
  return null
}
export default CustomDocumentTitle