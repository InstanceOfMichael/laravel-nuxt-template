<template lang="pug">
  .claim-show
    ClaimCardRow(
      :item="claim"
      expanded
    )
    b-nav(tabs)
      b-nav-item(
        :to="{ name: 'claims-slug', params: { slug: $slug(claim) } }"
      )
        fa(icon="list" fixed-width)
        | &#32;
        | Index
      b-nav-item(
        :to="{ name: 'claims-slug-answers', params: { slug: $slug(claim) } }"
      )
        fa(icon="list" fixed-width)
        | &#32;
        | {{ claim.answers_count }} Answers
      b-nav-item(
        :to="{ name: 'claims-slug-comments', params: { slug: $slug(claim) } }"
      )
        fa(icon="list" fixed-width)
        | &#32;
        | {{ claim.comments_count }} Comments
      b-nav-item(
        v-if="claim.sides_type !== 0"
        :to="{ name: 'claims-slug-sides', params: { slug: $slug(claim) } }"
      )
        fa(icon="list" fixed-width)
        | &#32;
        | {{ claim.sides_count }} Sides
      b-nav-item(
        :to="{ name: 'claims-slug-topics', params: { slug: $slug(claim) } }"
      )
        fa(icon="list" fixed-width)
        | &#32;
        | {{ claim.topics_count }} Topics
    nuxt-child(:claim="claim")
</template>

<script>
import ClaimCardRow from '~/components/Claim/CardRow'
import bNav from 'bootstrap-vue/es/components/nav/nav'
import bNavItem from 'bootstrap-vue/es/components/nav/nav-item'

export default {
  components: {
    ClaimCardRow,
    bNav,
    bNavItem,
  },
  async asyncData ({ api, route, deslug }) {
    return {
      claim: (await api.get(`/claims/${deslug(route.params.slug)}`)).data,
    }
  },
}
</script>

<style lang="sass">

</style>
