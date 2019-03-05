import Vue from 'vue'
import Router from 'vue-router'
import { scrollBehavior } from '~/utils'

Vue.use(Router)

const Home = () => import('~/pages/home').then(defaultOrModule)
const Welcome = () => import('~/pages/welcome').then(defaultOrModule)

const Login = () => import('~/pages/login').then(defaultOrModule)
const Register = () => import('~/pages/register').then(defaultOrModule)
const PasswordReset = () => import('~/pages/password/reset').then(defaultOrModule)
const PasswordRequest = () => import('~/pages/password/email').then(defaultOrModule)
const Verify = () => import('~/pages/verify').then(defaultOrModule)

const Settings = () => import('~/pages/settings/index').then(defaultOrModule)
const SettingsProfile = () => import('~/pages/settings/profile').then(defaultOrModule)
const SettingsPassword = () => import('~/pages/settings/password').then(defaultOrModule)
const SettingsEmail = () => import('~/pages/settings/email').then(defaultOrModule)

const routes = [
  { path: '/', name: 'welcome', component: Welcome },
  { path: '/home', name: 'home', component: Home },

  { path: '/login', name: 'login', component: Login },
  { path: '/register', name: 'register', component: Register },
  { path: '/password/reset', name: 'password.request', component: PasswordRequest },
  { path: '/password/reset/:token', name: 'password.reset', component: PasswordReset },

  { path: '/settings',
    component: Settings,
    children: [
      { path: '', redirect: { name: 'settings.profile' } },
      { path: 'profile', name: 'settings.profile', component: SettingsProfile },
      { path: 'password', name: 'settings.password', component: SettingsPassword },
      { path: 'email', name: 'settings.email', component: SettingsEmail }
    ] },
  { path: '/verify', name: 'verify', component: Verify }
]

export function createRouter () {
  return new Router({
    routes,
    scrollBehavior,
    mode: 'history'
  })
}

function defaultOrModule (m) {
  return m.default || m
}
