<template lang="pug">
  nuxt-link(
    :to="{ name: 'users-handle', params: { handle: $slug(user) } }"
  )
    img.rounded(
      v-if="withImage && user.photo_url"
      width="160"
      height="160"
      :src="user && user.photo_url"
    )
    br
    | {{ user.handle }}
</template>

<script>
export default {
  name: 'UserProfileLink',
  props: {
    user: {
      type: Object,
      required: true,
    },
    withImage: {
      type: Boolean,
      default: false,
    },
  },
}
</script>
