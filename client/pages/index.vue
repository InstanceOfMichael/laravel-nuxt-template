<template>
  <div dusk="welcome_page">
    <div class="top-right links">
      <template v-if="authenticated">
        <router-link :to="{ name: 'home' }">
          {{ $t('home') }}
        </router-link>
      </template>
      <template v-else>
        <router-link :to="{ name: 'login' }">
          {{ $t('login') }}
        </router-link>
        <router-link :to="{ name: 'register' }">
          {{ $t('register') }}
        </router-link>
      </template>
    </div>

    <div class="text-center">
      <div class="title mb-4">
        {{ title }}
      </div>

      <div class="links">
        <nuxt-link :to="{ name: 'questions' }">Questions</nuxt-link>
        <nuxt-link :to="{ name: 'questions' }">Claims</nuxt-link>
        <nuxt-link :to="{ name: 'questions' }">Comments</nuxt-link>
        <nuxt-link :to="{ name: 'questions' }">Answers</nuxt-link>
        <nuxt-link :to="{ name: 'questions' }">Sides</nuxt-link>
      </div>
    </div>
  </div>
</template>

<script>
import { mapGetters } from 'vuex'

export default {
  layout: 'simple',

  head () {
    return { title: this.$t('home') }
  },

  data: () => ({
    title: process.env.appName
  }),

  computed: mapGetters({
    authenticated: 'auth/check'
  })
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
