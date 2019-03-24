<template lang="pug">
.card.card-comment
  .card-body
    .float-right(v-if="item.op")
      UserProfileLink(:user="item.op" with-image)
    h5.card-title {{ item.title }}
    //- h6.card-title {{ item.title }}
    .card-text {{ item.text }}
    nuxt-link(
      :to="{ name: 'comments-slug', params: { slug: $slug(item) } }"
    )
      | asked&#32;
      TimeAgo(:value="item.created_at")
    | &#32;
    template(
      v-if="item.pc_id"
    )
      | &#32;|&#32;
      nuxt-link(
        :to="{ name: 'comments-slug', params: { slug: $slug(item.pc || { id: item.pc_id }) } }"
      ) parent
  .card-body.small: tt: pre {{ {item} }}
</template>

<script>
import UserProfileLink from '~/components/User/ProfileLink'

export default {
  name: 'CommentCardRow',
  components: {
    UserProfileLink,
  },
  props: {
    item: {
      type: Object,
      required: true,
    },
    expanded: {
      type: Boolean,
      default: false,
    },
  },
}
</script>

<style lang="sass">
.card.card-comment
</style>
