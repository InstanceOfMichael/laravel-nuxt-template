<template lang="pug">
  div
    card(:title="$t('home')" dusk="home_page")
      | {{ $t('you_are_logged_in') }}
    br
    .text-center
      .title.mb-4
        | {{ title }}
      .links
        nuxt-link(:to="{ name: 'questions' }") Questions
        nuxt-link(:to="{ name: 'claims' }") Claims
        nuxt-link(:to="{ name: 'comments' }") Comments
        nuxt-link(:to="{ name: 'answers' }") Answers
        nuxt-link(:to="{ name: 'sides' }") Sides
        nuxt-link(:to="{ name: 'links' }") Links
        nuxt-link(:to="{ name: 'topics' }") Topics
</template>

<script>
export default {
  middleware: 'auth',

  head () {
    return { title: this.$t('home') }
  },
}
</script>
