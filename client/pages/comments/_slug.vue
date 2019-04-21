<template lang="pug">
  .comment-show
    CommentCardRow(
      :item="comment"
      expanded
    )
    b-nav(tabs)
      b-nav-item(
        :to="{ name: 'comments-slug', params: { slug: $slug(comment) } }"
      )
        fa(icon="list" fixed-width)
        | &#32;
        | Index
      b-nav-item(
        :to="{ name: 'comments-slug-replies', params: { slug: $slug(comment) } }"
      )
        fa(icon="list" fixed-width)
        | &#32;
        | {{ comment.reply_count }} Replies
    nuxt-child(:comment="comment")
</template>

<script>
import CommentCardRow from '~/components/Comment/CardRow'
import bNav from 'bootstrap-vue/es/components/nav/nav'
import bNavItem from 'bootstrap-vue/es/components/nav/nav-item'

export default {
  components: {
    CommentCardRow,
    bNav,
    bNavItem,
  },
  async asyncData ({ api, route, deslug }) {
    return {
      comment: (await api.get(`/comments/${deslug(route.params.slug)}`)).data,
    }
  },
}
</script>

<style lang="sass">

</style>
