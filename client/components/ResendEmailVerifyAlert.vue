<template>
  <div dusk="resent_email_verify_alert">
    <div v-if="isResent" class="alert alert-success alert-is-resent" role="alert">
      {{ $t('a_fresh_verification_link_has_been_sent_to_your_email_address') }}
    </div>

    <div class="alert alert-info alert-please-verify" role="alert">
      {{ $t('verify_before_proceeding_please_check_email_for_verification_link') }}
      <div v-if="!isResent" dusk="prompt_verify_email_resend">
        {{ $t('if_you_did_not_receive_the_email') }},
        <a :class="{ busy }" href="#" dusk="resend" @click.prevent.stop="resend">
          {{ $t('click_here_to_request_another') }}
        </a>.
      </div>
    </div>
  </div>
</template>

<script>
import { mapGetters } from 'vuex'
import axios from 'axios'

export default {
  name: 'ResendEmailVerifyAlert',

  data: () => ({
    isResent: false,
    busy: false,
  }),

  computed: mapGetters({
    user: 'auth/user',
  }),

  methods: {
    async resend () {
      try {
        this.busy = true
        await axios.post('email/resend')
        this.isResent = true
      } finally {
        this.busy = false
      }
    },
  },
}
</script>

<style scoped>
</style>
