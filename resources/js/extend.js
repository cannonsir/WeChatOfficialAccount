import Layout from './views/account/layout'
import Index from './views/account/index'
import Menu from './views/account/menu'
import User from './views/account/user'
import Material from './views/account/material'
import Setting from './views/account/setting'
import Accounts from './views/accounts'
import Avue from '@smallwei/avue';
// import ElementUI from 'element-ui'

// import './assets/css/element-variables.scss'
import '@smallwei/avue/lib/index.css';

VueAdmin.booting((Vue, router, store) => {
  Vue.use(Avue)
  // Vue.use(ElementUI)

  router.addRoutes([
        {
            name: 'WeChatOfficialAccounts',
            path: '/wechat-official-accounts',
            component: Accounts
        },
        {
            name: 'WeChatOfficialAccount.layout',
            path: '/wechat-official-accounts/:id',
            props: true,
            component: Layout,
            children: [
                {
                    name: 'WeChatOfficialAccount.index',
                    path: 'index',
                    component: Index
                },
                {
                    name: 'WeChatOfficialAccount.menu',
                    path: 'menu',
                    component: Menu
                },
                {
                    name: 'WeChatOfficialAccount.user',
                    path: 'user',
                    component: User
                },
                {
                    name: 'WeChatOfficialAccount.material',
                    path: 'material',
                    component: Material
                },
                {
                    name: 'WeChatOfficialAccount.setting',
                    path: 'setting',
                    component: Setting
                }
            ]
        }
    ])

  Vue.component("WeChatOfficialAccount", require('./views/accounts.vue').default)
});
