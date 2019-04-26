<template lang="pug">
  .side-show
    SideCardRow(
      :item="side"
      expanded
    )
    b-nav(tabs)
      b-nav-item(
        :to="{ name: 'sides-slug', params: { slug: $slug(side) } }"
      )
        fa(icon="list" fixed-width)
        | &#32;
        | Index
      b-nav-item(
        :to="{ name: 'sides-slug-answers', params: { slug: $slug(side) } }"
      )
        fa(icon="list" fixed-width)
        | &#32;
        | {{ side.answers_count }} Answers
      b-nav-item(
        :to="{ name: 'sides-slug-comments', params: { slug: $slug(side) } }"
      )
        fa(icon="list" fixed-width)
        | &#32;
        | {{ side.comments_count }} Comments
      b-nav-item(
        v-if="side.sides_type !== 0"
        :to="{ name: 'sides-slug-sides', params: { slug: $slug(side) } }"
      )
        fa(icon="list" fixed-width)
        | &#32;
        | {{ side.sides_count }} Sides
      b-nav-item(
        :to="{ name: 'sides-slug-topics', params: { slug: $slug(side) } }"
      )
        fa(icon="list" fixed-width)
        | &#32;
        | {{ side.topics_count }} Topics
    nuxt-child(:side="side")
</template>

<script>
import SideCardRow from '~/components/Side/CardRow'
import bNav from 'bootstrap-vue/es/components/nav/nav'
import bNavItem from 'bootstrap-vue/es/components/nav/nav-item'

export default {
  components: {
    SideCardRow,
    bNav,
    bNavItem,
  },
  async asyncData ({ api, route, deslug }) {
    return {
      side: (await api.get(`/sides/${deslug(route.params.slug)}`)).data,
    }
  },
}
</script>

<style lang="sass">

</style>
