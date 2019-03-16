import Vue from 'vue'
import slugify from 'slugify'

const RADIX = 36

export default (ctx) => {
  Vue.prototype.$slug = ctx.slug = (value) => {
    if (value) {
      let output = 'untitled'
      if (value.handle) {
        output = slugify(value.handle)
      } else if (value.title) {
        output = slugify(value.title)
      } else if (value.name) {
        output = slugify(value.name)
      }
      if (output && output.toLowerCase) {
        output = output.toLowerCase()
      }
      return `${output}-${value.id.toString(RADIX)}`
    }
  }
  Vue.prototype.$deslug = ctx.deslug = (value) => {
    if (value && value.lastIndexOf) {
      value = value.slice(value.lastIndexOf('-') + 1)
      return parseInt(value, RADIX)
    }
  }
}

Vue.prototype.$mergeRouteQuery = function (object) {
  return {
    ...this.$route,
    query: {
      ...this.$route.query,
      ...object
    }
  }
}
