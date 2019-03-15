import Vue from 'vue'

export default async ctx => {
  //
}

const disabledClass = 'disabled'

Vue.directive('disabled', (el, binding) => {
  if (binding.value) {
    el.disabled = true
    el.setAttribute('aria-disabled', true)
    el.classList.add(disabledClass)
    el.style.cursor = 'not-allowed'
  } else {
    el.disabled = false
    el.removeAttribute('aria-disabled')
    el.classList.remove(disabledClass)
    el.style.cursor = null
  }
})
