import slugify from 'slugify'

const RADIX = 36

export default (ctx) => {
  ctx.slug = (value) => {
    if (value) {
      let output = 'untitled'
      if (value.handle) {
        output = slugify(value.handle)
      } else if (value.title) {
        output = slugify(value.title)
      } else if (value.name) {
        output = slugify(value.name)
      }
      return `${output}-${value.id.toString(RADIX)}`
    }
  }
  ctx.deslug = (value) => {
    if (value && value.lastIndexOf) {
      value = value.slice(value.lastIndexOf('-') + 1)
      return parseInt(value, RADIX)
    }
  }
}
