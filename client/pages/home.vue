<template lang="pug">
  div
    card(:title="$t('home')" dusk="home_page")
      | {{ $t('you_are_logged_in') }}
    br
    .text-center
      .title.mb-4
        | {{ title }}
      .links
        nuxt-link(:to="{ name: 'comments' }") Comments
        nuxt-link(:to="{ name: 'users' }") User
</template>

<script>
export default {
  middleware: 'auth',

  head () {
    return { title: this.$t('home') }
  },
}
</script>
