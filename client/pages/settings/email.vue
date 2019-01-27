<template>
  <card :title="$t('your_email')" dusk="settings_email_page">
    <form @submit.prevent="update" @keydown="form.onKeydown($event)">
      <alert-success :form="form" :message="$t('info_updated')"/>

      <!-- Email -->
      <div class="form-group row">
        <label class="col-md-3 col-form-label text-md-right">{{ $t('email') }}</label>
        <div class="col-md-7">
          <input v-model="form.email" :class="{ 'is-invalid': form.errors.has('email') }" type="email" name="email"
                 class="form-control">
          <has-error :form="form" field="email"/>
        </div>
      </div>

      <!-- Submit Button -->
      <div class="form-group row">
        <div class="col-md-9 ml-md-auto">
          <v-button :loading="form.busy" name="update_profile" type="success">{{ $t('update') }}</v-button>
        </div>
      </div>

      <div class="alert alert-info" v-if="isEmailVerified">
        sssss
      </div>
      <ResendEmailVerifyAlert v-else/>

      | {{ {isEmailVerified} }}
    </form>
  </card>
</template>

<script>
import get from 'lodash/get'
import Form from 'vform'
import { mapGetters } from 'vuex'
import ResendEmailVerifyAlert from '~/components/ResendEmailVerifyAlert'

export default {
  scrollToTop: false,

  head () {
    return { title: this.$t('settings') }
  },

  components: {
    ResendEmailVerifyAlert,
  },

  data: () => ({
    form: new Form({
      name: '',
      email: ''
    })
  }),

  computed: {
    ...mapGetters({
      user: 'auth/user'
    }),
    isEmailVerified () {
      return !!get(this.user, 'email_verified_at');
    },
  },

  created () {
    // Fill the form with user data.
    this.form.keys().forEach(key => {
      this.form[key] = this.user[key]
    })
  },

  methods: {
    async update () {
      const { data } = await this.form.patch('/settings/email')

      this.$store.dispatch('auth/updateUser', { user: data })
    }
  }
}
</script>
