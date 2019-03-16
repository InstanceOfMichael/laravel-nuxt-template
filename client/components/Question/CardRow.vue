<template lang="pug">
.card.card-question
  .card-body
    .float-right(v-if="item.op")
      UserProfileLink(:user="item.op" with-image)
    h5.card-title {{ item.title }}
    //- h6.card-title {{ item.title }}
    .card-text {{ item.text }}
    nuxt-link(
      :to="{ name: 'questions-slug', params: { slug: $slug(item) } }"
    )
      | asked&#32;
      TimeAgo(:value="item.created_at")
    | &#32;
    | &#32;|&#32;
    nuxt-link(
      :to="{ name: 'questions-slug-answers', params: { slug: $slug(item) } }"
    ) {{ item.answers_count }} Answers
    | &#32;|&#32;
    nuxt-link(
      :to="{ name: 'questions-slug-comments', params: { slug: $slug(item) } }"
    ) {{ item.comments_count }} Comments
    template(v-if="item.sides_type !== 0")
      | &#32;|&#32;
      nuxt-link(
        :to="{ name: 'questions-slug-sides', params: { slug: $slug(item) } }"
      ) {{ item.answers_count }} Sides
    | &#32;|&#32;
    QuestionSidesFlair(:item="item")
  //- .card-body.small: tt: pre {{ {item} }}
</template>

<script>
import axios from 'axios'
import UserProfileLink from '~/components/User/ProfileLink'
import QuestionSidesFlair from '~/components/Question/SidesFlair'

export default {
  name: 'QuestionCardRow',
  components: {
    UserProfileLink,
    QuestionSidesFlair,
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
.card.card-question
</style>
