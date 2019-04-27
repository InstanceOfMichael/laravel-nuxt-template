const splitables = [
  'side',
  'topic',
  'group',
  'user',
  'domain',
]

const comma = ','

export function queryToApiParams (ctx) {
  const { deslug } = ctx
  ctx.queryToApiParams = (route) => {
    const data = { page: route.query.page || 1 }

    for (let i = 0; i < splitables.length; i++) {
      let key = splitables[i]
      if (route.query[key] && route.query[key].split) {
        const deslugged = route.query[key].split(comma).map(deslug)
        data[`${key}_id`] = deslugged.join(comma)
      }
    }

    return data
  }
}
