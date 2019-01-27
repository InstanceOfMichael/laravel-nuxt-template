<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card">
          <div class="card-header">
            {{ $t('verify_your_email_address') }}
          </div>

          <div class="card-body">
            <ResendEmailVerifyAlert/>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import ResendEmailVerifyAlert from '~/components/ResendEmailVerifyAlert';

export default {
  components: {
    ResendEmailVerifyAlert,
  },

  head () {
    return { title: this.$t('verify_email') }
  },

  methods: {
    async resend () {
      // {{ route('verification.resend') }}
    }
  }
}
</script>
