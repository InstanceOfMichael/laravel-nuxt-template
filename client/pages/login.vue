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
</template>

<script>
import Form from 'vform'

export default {
  head () {
    return { title: this.$t('login') }
  },

  data: () => ({
    form: new Form({
      email: '',
      password: '',
    }),
    remember: false,
  }),

  methods: {
    async login () {
      // Submit the form.
      const { data } = await this.form.post('/login')

      // Save the token.
      this.$store.dispatch('auth/saveToken', {
        token: data.token,
        remember: this.remember,
      })

      // Fetch the user.
      await this.$store.dispatch('auth/fetchUser')

      // Redirect home.
      this.$router.push({ name: 'home' })
    },
  },
}
</script>
