export default (ctx) => {
  ctx.store.dispatch('nuxtClientInit', ctx)

  if (typeof window === 'object') {
    window.onfocus = () => {
      if (!ctx.store.state.windowActive) {
        ctx.store.commit('windowActive', true)
      }
    }
    window.onblur = () => {
      if (ctx.store.state.windowActive) {
        ctx.store.commit('windowActive', false)
      }
    }
  }
}
