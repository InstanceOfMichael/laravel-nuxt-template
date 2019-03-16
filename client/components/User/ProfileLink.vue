<template lang="pug">
  nuxt-link(
    :to="{ name: 'users-handle', params: { handle: $slug(user) } }"
  )
    img.rounded(
      width="160"
      height="160"
      v-if="user.photo_url"
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
  },
}
</script>
