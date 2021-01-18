import FrontPage from '../components/FrontPage'
import Login from '../components/auth/Login'
import Logout from '../components/auth/Logout'
import Dashboard from '../components/Dashboard'
import SavingTips from '../components/SavingTips'

import UserDetails from '../components/UserDetails'

const routes = [
  {
    path: '/',
    name: 'FrontPage',
    component: FrontPage
  },
  {
    path: '/login',
    name: 'login',
    component: Login,
    meta: {
      requiresVisitor: true
    }
  },
  {
    path: '/dashboard',
    name: 'dashboard',
    component: Dashboard,
    props: true,
    meta: {
      requiresAuth: true
    }
  },
  {
    path: '/logout',
    name: 'logout',
    component: Logout
  },
  {
    path: '/SavingTips',
    name: 'SavingTips',
    component: SavingTips
  },
  {
    path: '/userdetails',
    name: 'details',
    component: UserDetails
  }
]

export default routes
