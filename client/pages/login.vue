<template lang="pug">
  .row(dusk="login_page")
    .col-lg-8.m-auto
      card(:title="$t('login')")
        form(@submit.prevent='login' @keydown='form.onKeydown($event)')
          // Email
          .form-group.row
            label.col-md-3.col-form-label.text-md-right {{ $t('email') }}
            .col-md-7
              input.form-control(v-model='form.email' :class="{ 'is-invalid': form.errors.has('email') }" type='email' name='email')
              has-error(:form='form' field='email')
          // Password
          .form-group.row
            label.col-md-3.col-form-label.text-md-right {{ $t('password') }}
            .col-md-7
              input.form-control(v-model='form.password' :class="{ 'is-invalid': form.errors.has('password') }" type='password' name='password')
              has-error(:form='form' field='password')
          // Remember Me
          .form-group.row
            .col-md-3
            .col-md-7.d-flex
              checkbox(v-model='remember' name='remember')
                | {{ $t('remember_me') }}
              router-link.small.ml-auto.my-auto(:to="{ name: 'password-reset' }" dusk='forgot_password')
                | {{ $t('forgot_password') }}
          .form-group.row
            .col-md-7.offset-md-3.d-flex
              // Submit Button
              v-button(:loading='form.busy' name='sign_in')
                | {{ $t('login') }}
              // GitHub Login Button
              login-with-github
          .form-group.row
            .col
              .alert.alert-danger(v-if="error" v-text="error")
</template>

<script>
import get from 'lodash/get'
import isObject from 'lodash/isObject'
import Form from 'vform'

export default {
  head () {
    return { title: this.$t('login') }
  },

  data: () => ({
    error: null,
    form: new Form({
      email: '',
      password: '',
    }),
    remember: false,
  }),

  methods: {
    async login () {
      this.error = null
      this.form.clear()
      if (!this.form.email) {
        this.form.errors.set('email', 'Email required')
      }
      if (!this.form.password) {
        this.form.errors.set('password', 'Password required')
      }
      if (this.form.errors.any()) {
        return false
      }

      try {
        // Submit the form.
        const { data } = await this.form.post('/login')

        if (data && !isObject(data)) {
          // You probably recieved the nuxt generate pre-rendered index.html,
          // an apache/nginx message
          // or an intercepted file
          throw new Error('Invalid login response! (no json object)')
        }
        const { expires_in, token, token_type } = data
        if (!(expires_in && token && token_type)) {
          // You probably recieved the nuxt generate pre-rendered index.html,
          // an apache/nginx message
          // or an intercepted file
          throw new Error('Invalid login response (missing {expires_in, token, token_type})!')
        }
        // Save the token.
        this.$store.dispatch('auth/saveToken', {
          token: data.token,
          remember: this.remember,
        })

        // Fetch the user.
        await this.$store.dispatch('auth/fetchUser')

        // Redirect home.
        this.$router.push({ name: 'home' })
      } catch (err) {
        if (get(err, 'response.status') !== 422 && !this.form.errors.any()) {
          console.error(err)
          this.error = err
        }
      }
    },
  },
}
</script>
