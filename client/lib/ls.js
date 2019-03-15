import noop from 'lodash/noop'
const prefix = 'lnd_'

export function get (key, defaultValue = null) {
  if (process.server) {
    return defaultValue
  }
  try {
    const rawValue = window.localStorage.getItem(`${prefix}${key}`)
    const parsedValue = JSON.parse(rawValue)
    return parsedValue
  } catch (err) {
    console.error(err)
    return defaultValue
  }
}

export function set (key, value) {
  if (process.server) {
    return
  }
  try {
    const json = JSON.stringify(value)
    window.localStorage.setItem(`${prefix}${key}`, json)
  } catch (err) {
    console.error(err)
  }
}
export function syncVuexMutators (arr) {
  return arr.reduce(process.client ? (carry, key) => {
    carry[key] = (state, payload) => {
      if (payload instanceof StorageEvent) {
        state[key] = JSON.parse(payload.newValue)
      } else {
        set(key, payload)
        state[key] = payload
      }
    }
    return carry
  } : (carry, key) => {
    carry[key] = noop
    return carry
  }, {})
}
export function addSyncVuexListener ({ syncMap, commit }) {
  if (process.client) {
    window.addEventListener('storage', event => {
      // console.log('storage!', event);
      if (event.key === null) {
        Object.values(syncMap).forEach(mutator => {
          commit(mutator, {})
        })
      } else if (event.key && event.key.startsWith(prefix)) {
        const key = event.key.replace(prefix, '')
        if (syncMap[key]) {
          // console.log('storage.key', event.key, event);
          commit(syncMap[key], event)
        }
      }
    })
  }
}
