<template lang="pug">
  .claims-list
    LengthAwarePaginator(
      :pagination="claims"
    )
    ClaimCardRow(
      v-for="claim in claims.data"
      :key="claim.id"
      :item="claim"
    )
    LengthAwarePaginator(
      v-if="claims.data.length > 2"
      :pagination="claims"
    )
    .empty-page.text-center(v-else-if="claims.data.length === 0") No claims
    br
    br
</template>

<script>
import ClaimCardRow from '~/components/Claim/CardRow'

export default {
  components: {
    ClaimCardRow,
  },

  // these 2 lines are required for query+pagination to apply
  // scrollToTop: true,
  watchQuery: ['page'],
  key: (to) => to.fullPath,

  async asyncData ({ api, route }) {
    return {
      claims: (await api.get('/claims', {
        params: {
          page: route.query.page || 1,
        },
      })).data,
    }
  },
}
</script>

<style lang="sass">
.claims-list
  .card.card-claim
    margin-bottom: 15px
</style>
