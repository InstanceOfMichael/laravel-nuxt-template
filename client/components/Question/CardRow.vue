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
    QuestionSidesFlair(:item="item")
  .card-body.small: tt: pre {{ {item} }}
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
  },
}
</script>

<style lang="sass">
.card.card-question
</style>
