<template lang="pug">
.card.card-answer
  .card-body
    .float-right(v-if="item.claim.op")
      UserProfileLink(:user="item.claim.op" with-image)
    h5.card-title {{ item.claim.title }}
    //- h6.card-title {{ item.title }}
    .card-text {{ item.claim.text }}
    nuxt-link(
      :to="{ name: 'answers-slug', params: { slug: $slug(item) } }"
    )
      | answered&#32;
      TimeAgo(:value="item.created_at")
    | &#32;
    | &#32;|&#32;
    nuxt-link(
      :to="{ name: 'answers-slug-answers', params: { slug: $slug(item) } }"
    ) {{ item.answers_count }} Answers
    | &#32;|&#32;
    nuxt-link(
      :to="{ name: 'answers-slug-comments', params: { slug: $slug(item) } }"
    ) {{ item.comments_count }} Comments
    //- AnswerSidesFlair(:item="item")
  //- .card-body.small: tt: pre {{ {item} }}
</template>

<script>
import axios from 'axios'
import UserProfileLink from '~/components/User/ProfileLink'
// import AnswerSidesFlair from '~/components/Answer/SidesFlair'

export default {
  name: 'AnswerCardRow',
  components: {
    UserProfileLink,
    // AnswerSidesFlair,
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
.card.card-answer
</style>
