import { resolveBrowserLocale } from 'react-admin'
import polyglotI18nProvider from 'ra-i18n-polyglot'
import i18n from './i18n'

export default polyglotI18nProvider(locale => i18n[locale] || i18n.en, resolveBrowserLocale())