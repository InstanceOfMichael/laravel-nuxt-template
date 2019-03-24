<template>
  <card :title="$t('home')" dusk="home_page">
    {{ $t('you_are_logged_in') }}
  </card>
</template>

<script>
export default {
  middleware: 'auth',

  head () {
    return { title: this.$t('home') }
  },
}
</script>
