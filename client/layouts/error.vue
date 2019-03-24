<template lang="pug">
  .layout-error(dusk="layout_error")
    navbar
    .container.mt-4
      h1.status-code(
        v-if="error.statusCode"
        v-text="error.statusCode"
      )
      h2.message(
        v-if="error.message"
        v-text="error.message"
      )
</template>

<script>
import Navbar from '~/components/Navbar'

export default {
  name: 'NuxtError',
  components: {
    Navbar,
  },
  // eslint-disable-next-line
  props: ['error'],
  mounted () {
    const err = this.error || { falsy: true }
    // eslint-disable-next-line
    console.error({ err, statusCode: err.statusCode, message: err.message })
  },
}
</script>

<style lang="sass">

</style>
