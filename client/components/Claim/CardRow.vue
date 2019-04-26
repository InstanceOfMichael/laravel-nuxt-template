<template lang="pug">
.card.card-claim
  .card-body
    .float-right(v-if="item.op")
      UserProfileLink(:user="item.op" with-image)
    h5.card-title {{ item.title }}
    //- h6.card-title {{ item.title }}
    .card-text {{ item.text }}
    nuxt-link(
      :to="{ name: 'claims-slug', params: { slug: $slug(item) } }"
    )
      | made&#32;
      TimeAgo(:value="item.created_at")
    | &#32;
    | &#32;|&#32;
    nuxt-link(
      :to="{ name: 'claims-slug-answers', params: { slug: $slug(item) } }"
    ) {{ item.answers_count }} Answers
    | &#32;|&#32;
    nuxt-link(
      :to="{ name: 'claims-slug-comments', params: { slug: $slug(item) } }"
    ) {{ item.comments_count }} Comments
    template(v-if="item.sides_type !== 0")
      | &#32;|&#32;
      nuxt-link(
        :to="{ name: 'claims-slug-sides', params: { slug: $slug(item) } }"
      ) {{ item.sides_count }} Sides
    | &#32;|&#32;
    nuxt-link(
      :to="{ name: 'claims-slug-topics', params: { slug: $slug(item) } }"
    ) {{ item.topics_count }} Topics
    | &#32;|&#32;
  //- .card-body.small: tt: pre {{ {item} }}
</template>

<script>
import UserProfileLink from '~/components/User/ProfileLink'

export default {
  name: 'ClaimCardRow',
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
.card.card-claim
</style>
