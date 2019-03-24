<template lang="pug">
  .questions-comments-list
    LengthAwarePaginator(
      :pagination="comments"
    )
    CommentCardRow(
      v-for="comment in comments.data"
      :key="comment.id"
      :item="comment"
    )
    LengthAwarePaginator(
      v-if="comments.data.length > 2"
      :pagination="comments"
    )
</template>

<script>
import CommentCardRow from '~/components/Comment/CardRow'

export default {
  components: {
    CommentCardRow,
  },
  props: {
    question: {
      type: Object,
      required: true,
    },
  },
  async asyncData ({ api, route, deslug }) {
    return {
      comments: (await api.get('/comments', {
        params: {
          question_id: deslug(route.params.slug),
        },
      })).data,
    }
  },
}
</script>

<style lang="sass">
.questions-comments-list
  .card.card-comment
    margin-bottom: 15px
</style>
