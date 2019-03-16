<template lang="pug">
  .questions-list
    QuestionCardRow(
      v-for="question in questions.data"
      :key="question.id"
      :item="question"
    )
</template>

<script>
import axios from 'axios'
import QuestionCardRow from '~/components/Question/CardRow'

export default {
  components: {
    QuestionCardRow,
  },
  async asyncData ({ api }) {
    return {
      questions: (await api.get('/questions')).data,
    }
  },
}
</script>

<style lang="sass">
.questions-list
  .card
    margin-bottom: 15px
</style>
