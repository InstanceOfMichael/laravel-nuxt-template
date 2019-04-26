<template lang="pug">
  .answer-comments-list
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
    answer: {
      type: Object,
      required: true,
    },
  },
  async asyncData ({ api, route, deslug }) {
    return {
      comments: (await api.get('/comments', {
        params: {
          answer_id: deslug(route.params.slug),
        },
      })).data,
    }
  },
}
</script>

<style lang="sass">
.answers-comments-list
  .card.card-comment
    margin-bottom: 15px
</style>
