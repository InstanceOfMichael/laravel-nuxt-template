<template lang="pug">
  .questions-list
    | TODO
    .card(
      v-for="question in questions.data"
      :key="question.id"
    )
      .card-body
      | {{ {question} }}
</template>

<script>
import axios from 'axios'

export default {
  async asyncData ({ api }) {
    console.log('questions asyncData', process.env.apiUrl+'/questions')
    return {
      questions: (await api.get('/questions')).data,
    }
  },
}
</script>
