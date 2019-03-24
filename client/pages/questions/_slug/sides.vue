<template lang="pug">
  div
    | Question Sides
</template>

<script>
import QuestionCardRow from '~/components/Question/CardRow'

export default {
  components: {
    QuestionCardRow,
  },
  props: {
    question: {
      type: Object,
      required: true,
    },
  },
}
</script>
