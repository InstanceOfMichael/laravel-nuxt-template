import Cookies from 'js-cookie'
import { cookieFromRequest } from '~/utils'
import get from 'lodash/get'

export const state = () => ({
  windowActive: process.server || !!get(document, 'hidden'),
})

export const mutations = {
  windowActive (state, value) {
    state.windowActive = !!value
  },
}

export const actions = {
  nuxtServerInit ({ commit }, { req }) {
    const token = cookieFromRequest(req, 'token')
    if (token) {
      commit('auth/SET_TOKEN', token)
    }

    const locale = cookieFromRequest(req, 'locale')
    if (locale) {
      commit('lang/SET_LOCALE', { locale })
    }
  },

  nuxtClientInit ({ commit }) {
    const token = Cookies.get('token')
    if (token) {
      commit('auth/SET_TOKEN', token)
    }

    const locale = Cookies.get('locale')
    if (locale) {
      commit('lang/SET_LOCALE', { locale })
    }
  },
}

// export const strict = false;
