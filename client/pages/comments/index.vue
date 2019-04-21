<template lang="pug">
  .comments-list
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
    .empty-page.text-center(v-else-if="comments.data.length === 0") No comments
    br
    br
</template>

<script>
import CommentCardRow from '~/components/Comment/CardRow'

export default {
  components: {
    CommentCardRow,
  },

  // these 2 lines are required for query+pagination to apply
  // scrollToTop: true,
  watchQuery: ['page'],
  key: (to) => to.fullPath,

  async asyncData ({ api, route }) {
    return {
      comments: (await api.get('/comments', {
        params: {
          page: route.query.page || 1,
        },
      })).data,
    }
  },
}
</script>

<style lang="sass">
.comments-list
  .card.card-comment
    margin-bottom: 15px
</style>
