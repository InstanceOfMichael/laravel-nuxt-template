<template lang="pug">
  nuxt-link(
    :to="{ name: 'users-handle', params: { handle: $slug(user) } }"
  )
    img.rounded(
      width="100"
      height="100"
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
