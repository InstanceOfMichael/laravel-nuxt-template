<template lang="pug">
  .layout
    navbar
    .container.mt-4
      nuxt
    no-ssr
      BackToTop
        button.btn.btn-secondary.btn-to-top(type="button")
          fa(icon="chevron-up" fixed-width)
          i.fa.fa-chevron-up
</template>

<script>
import Navbar from '~/components/Navbar'

export default {
  components: {
    Navbar,
  },
}
</script>
