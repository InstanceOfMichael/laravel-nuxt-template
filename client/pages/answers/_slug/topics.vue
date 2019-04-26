<template lang="pug">
  .answers-topics-list
    TopicCardRow(
      v-for="topic in topics.data"
      :key="topic.id"
      :item="topic"
    )
</template>

<script>
import AnswerCardRow from '~/components/Answer/CardRow'

export default {
  components: {
    AnswerCardRow,
  },
  props: {
    claim: {
      type: Object,
      required: true,
    },
  },
  async asyncData ({ api, route, deslug }) {
    return {
      answers: (await api.get('/answers', {
        params: {
          claim_id: deslug(route.params.slug),
        },
      })).data,
    }
  },
}
</script>

<style lang="sass">
.answers-topics-list
  .card.card-topic
    margin-bottom: 15px
</style>
