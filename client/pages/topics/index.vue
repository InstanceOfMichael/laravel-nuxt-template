<template lang="pug">
  .sides-list
    LengthAwarePaginator(
      :pagination="sides"
    )
    TopicCardRow(
      v-for="side in sides.data"
      :key="side.id"
      :item="side"
    )
    LengthAwarePaginator(
      v-if="sides.data.length > 2"
      :pagination="sides"
    )
    .empty-page.text-center(v-else-if="sides.data.length === 0") No sides
    br
    br
</template>

<script>
import TopicCardRow from '~/components/Topic/CardRow'

export default {
  components: {
    TopicCardRow,
  },

  // these 2 lines are required for query+pagination to apply
  // scrollToTop: true,
  watchQuery: ['page'],
  key: (to) => to.fullPath,

  async asyncData ({ api, route }) {
    return {
      sides: (await api.get('/sides', {
        params: {
          page: route.query.page || 1,
        },
      })).data,
    }
  },
}
</script>

<style lang="sass">
.sides-list
  .card.card-side
    margin-bottom: 15px
</style>
