<template lang="pug">
  div(dusk="welcome_page")
    .top-right.links
      template(v-if="authenticated")
        nuxt-link(:to="{ name: 'home' }")
          | {{ $t('home') }}
      template(v-else)
        nuxt-link(:to="{ name: 'login' }")
          | {{ $t('login') }}
        nuxt-link(:to="{ name: 'register' }")
          | {{ $t('register') }}

    .text-center
      .title.mb-4
        | {{ title }}
      .links
        nuxt-link(:to="{ name: 'comments' }") Comments
        nuxt-link(:to="{ name: 'users' }") User
        | AAABBBCCC
</template>

<script>
import { mapGetters } from 'vuex'

export default {
  layout: 'simple',

  head () {
    return { title: this.$t('home') }
  },

  data: () => ({
    title: process.env.appName,
  }),

  computed: mapGetters({
    authenticated: 'auth/check',
  }),
}
</script>

<style scoped>
.top-right {
  position: absolute;
  right: 10px;
  top: 18px;
}

.title {
  font-size: 85px;
}
</style>
