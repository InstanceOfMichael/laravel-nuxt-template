<template lang="pug">
  .comments-replies-list
    LengthAwarePaginator(
      :pagination="replies"
    )
    CommentCardRow(
      v-for="comment in replies.data"
      :key="comment.id"
      :item="comment"
    )
    LengthAwarePaginator(
      v-if="replies.data.length > 2"
      :pagination="replies"
    )
</template>

<script>
import CommentCardRow from '~/components/Comment/CardRow'

export default {
  components: {
    CommentCardRow,
  },
  props: {
    comment: {
      type: Object,
      required: true,
    },
  },
  async asyncData ({ api, route, deslug }) {
    return {
      replies: (await api.get('/comments', {
        params: {
          pc_id: deslug(route.params.slug),
        },
      })).data,
    }
  },
}
</script>

<style lang="sass">
.comments-comments-list
  .card.card-comment
    margin-bottom: 15px
</style>
