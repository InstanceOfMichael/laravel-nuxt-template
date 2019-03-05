<template>
  <div class="row" dusk="settings_frame">
    <div class="col-md-3">
      <card :title="$t('settings')" class="settings-card">
        <ul class="nav flex-column nav-pills">
          <li v-for="tab in tabs" :key="tab.route" class="nav-item">
            <router-link :to="{ name: tab.route }" :dusk="tab.dusk" class="nav-link" active-class="active">
              <fa :icon="tab.icon" fixed-width/>
              {{ tab.name }}
            </router-link>
          </li>
        </ul>
      </card>
    </div>

    <div class="col-md-9">
      <transition name="fade" mode="out-in">
        <nuxt-child/>
      </transition>
    </div>
  </div>
</template>

<script>
export default {
  middleware: 'auth',

  computed: {
    tabs () {
      return [
        {
          icon: 'user',
          name: this.$t('profile'),
          route: 'settings-profile',
          dusk: 'settings_profile_link'
        }, {
          icon: 'lock',
          name: this.$t('password'),
          route: 'settings-password',
          dusk: 'settings_password_link'
        }, {
          icon: 'envelope',
          name: this.$t('email'),
          route: 'settings-email',
          dusk: 'settings_email_link'
        }
      ]
    }
  }
}
</script>

<style>
.settings-card .card-body {
  padding: 0;
}
</style>
